<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ExchangeRequest;
use Illuminate\Support\Facades\Log;

class ExchangeRequestController extends Controller
{
    public function chooseItem(Item $requestedItem, Request $request)
    {
        $this->authorize('request', $requestedItem);

        if ($requestedItem->user_id === auth()->id()) {
            abort(403, 'No puedes solicitar tu propio objeto.');
        }

        $itemsQuery = Item::where('user_id', auth()->id())
            ->where('status', 'active')
            ->whereDoesntHave('exchangeOffers', function ($q) {
                $q->whereIn('status', ['pending', 'accepted']);
            })
            ->whereDoesntHave('exchangeRequests', function ($q) {
                $q->where('status', 'accepted');
            });

        if ($buscar = $request->buscar) {
            $itemsQuery->where(function ($q) use ($buscar) {
                $q->where('title', 'like', "%{$buscar}%")
                ->orWhere('description', 'like', "%{$buscar}%");
            });
        }

        $items = $itemsQuery->paginate(12);

        return view('exchange_requests.choose-item', [
            'requestedItem' => $requestedItem,
            'items' => $items,
        ]);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $buscar = $request->input('buscar');
        $tipo = $request->input('tipo');
        $itemId = $request->input('item_id');

        $query = ExchangeRequest::with(['offeredItem.photos', 'requestedItem.photos']);

        // Filtros por tipo de intercambio
        if ($tipo === 'match') {
            $query->where('status', 'accepted')
                ->where(function ($q) use ($user) {
                    $q->where('requester_id', $user->id)
                        ->orWhereHas('requestedItem', fn ($q2) => $q2->where('user_id', $user->id));
                });

        } elseif ($tipo === 'offered') {
            $query->where('status', 'pending')
                ->where('requester_id', $user->id);

        } elseif ($tipo === 'requested') {
            $query->where('status', 'pending')
                ->whereHas('requestedItem', function ($q) use ($user, $itemId) {
                    $q->where('user_id', $user->id);

                    if ($itemId) {
                        $q->where('id', $itemId);
                    }
                });

        } elseif ($tipo === 'completed') {
            $query->where('status', 'completed')
                ->where(function ($q) use ($user) {
                    $q->where('requester_id', $user->id)
                        ->orWhereHas('requestedItem', fn ($q2) => $q2->where('user_id', $user->id));
                });

        } elseif ($tipo === 'cancelled') {
            $query->where('status', 'cancelled')
                ->where(function ($q) use ($user) {
                    $q->where('requester_id', $user->id)
                        ->orWhereHas('requestedItem', fn ($q2) => $q2->where('user_id', $user->id));
                });

        } else {
            // Mostrar todos los tipos válidos
            $query->where(function ($q) use ($user) {
                $q->where(function ($sub) use ($user) {
                    $sub->where('status', 'accepted')
                        ->where(function ($x) use ($user) {
                            $x->where('requester_id', $user->id)
                            ->orWhereHas('requestedItem', fn ($q2) => $q2->where('user_id', $user->id));
                        });
                })
                ->orWhere(function ($sub) use ($user) {
                    $sub->where('status', 'pending')
                        ->where('requester_id', $user->id);
                })
                ->orWhere(function ($sub) use ($user) {
                    $sub->where('status', 'pending')
                        ->whereHas('requestedItem', fn ($q2) => $q2->where('user_id', $user->id));
                })
                ->orWhere(function ($sub) use ($user) {
                    $sub->where('status', 'completed')
                        ->where(function ($x) use ($user) {
                            $x->where('requester_id', $user->id)
                            ->orWhereHas('requestedItem', fn ($q2) => $q2->where('user_id', $user->id));
                        });
                })
                ->orWhere(function ($sub) use ($user) {
                    $sub->where('status', 'cancelled')
                        ->where(function ($x) use ($user) {
                            $x->where('requester_id', $user->id)
                            ->orWhereHas('requestedItem', fn ($q2) => $q2->where('user_id', $user->id));
                        });
                });
            });
        }

        // Filtro de búsqueda
        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->whereHas('offeredItem', fn ($sub) =>
                    $sub->where('title', 'like', '%' . $buscar . '%'))
                ->orWhereHas('requestedItem', fn ($sub) =>
                    $sub->where('title', 'like', '%' . $buscar . '%'));
            });
        }

        $exchangeRequests = $query->latest()->paginate(12)->withQueryString();

        return view('exchange_requests.index', compact('exchangeRequests'));
    }

    public function create(Request $request) {
        $request->validate([
            'requested_item_id' => 'required|integer|exists:items,id',
            'offered_item_id' => 'required|integer|exists:items,id',
        ]);

        $requestedItem = Item::with(['user', 'photos'])->findOrFail($request->input('requested_item_id'));
        $offeredItem = Item::with(['user', 'photos'])->findOrFail($request->input('offered_item_id'));

        $this->authorize('request', $requestedItem);

        if ($offeredItem->user_id !== auth()->id()) {
            abort(403, 'Este objeto no te pertenece.');
        }

        if (! $offeredItem->availableForTrade()) {
            abort(403, 'Este objeto no está disponible para intercambiar.');
        }

        // Crear un objeto "falso" de ExchangeRequest para mostrar en la vista
        $exchangeRequest = new ExchangeRequest([
            'offered_item_id' => $offeredItem->id,
            'requested_item_id' => $requestedItem->id,
        ]);

        $exchangeRequest->setRelation('offeredItem', $offeredItem);
        $exchangeRequest->setRelation('requestedItem', $requestedItem);

        return view('exchange_requests.confirm', [
            'exchangeRequest' => $exchangeRequest,
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'offered_item_id' => 'required|integer|exists:items,id',
            'requested_item_id' => 'required|integer|exists:items,id',
        ]);

        $user = auth()->user();

        $offeredItem = Item::with('user')->findOrFail($request->offered_item_id);
        $requestedItem = Item::with('user')->findOrFail($request->requested_item_id);

        // Reglas de negocio
        if ($offeredItem->user_id !== $user->id) {
            abort(403, 'Este objeto no te pertenece.');
        }

        if ($requestedItem->user_id === $user->id) {
            abort(403, 'No puedes solicitar tu propio objeto.');
        }

        if ($offeredItem->hasMatchConfirmed() || $requestedItem->hasMatchConfirmed()) {
            return back()->with('error', 'Uno de los objetos ya está en un intercambio activo.');
        }

        // Verificar que no haya ya una solicitud activa entre estos mismos items
        $exists = ExchangeRequest::where('offered_item_id', $offeredItem->id)
            ->where('requested_item_id', $requestedItem->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Ya existe una solicitud activa entre estos objetos.');
        }

        // Rechazar otras solicitudes donde este objeto ya fue ofrecido
        ExchangeRequest::where('requested_item_id', $offeredItem->id)
            ->where('status', 'pending')    
            ->update(['status' => 'rejected']);
            
        // Crear solicitud
        $exchangeRequest = ExchangeRequest::create([
            'requester_id' => $user->id,
            'offered_item_id' => $offeredItem->id,
            'requested_item_id' => $requestedItem->id,
            'status' => 'pending',
        ]);

        return redirect()->route('intercambios.show', $exchangeRequest->id)->with('success', '¡Solicitud enviada con éxito!');
    }

    public function show($id)
    {
        $exchangeRequest = ExchangeRequest::with([
            'requestedItem.user',
            'requestedItem.photos',
            'offeredItem.user',
            'offeredItem.photos',
            'requester',
        ])->findOrFail($id);

        $user = auth()->user();

        if (
            $user->id !== $exchangeRequest->requester_id &&
            $user->id !== $exchangeRequest->requestedItem->user_id
        ) {
            abort(403, 'No tienes permiso para ver este intercambio.');
        }

        $modo = match ($exchangeRequest->status) {
            'accepted' => 'match',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            default => $exchangeRequest->requester_id === $user->id ? 'oferta' : 'solicitud',
        };

        $yo = auth()->id();
        $isRequester = $exchangeRequest->requester_id === $yo;

        $labelOffered = $isRequester ? 'ENTREGAS' : 'RECIBES';
        $labelRequested = $isRequester ? 'RECIBES' : 'ENTREGAS';

        return view('exchange_requests.show', compact('exchangeRequest', 'modo', 'labelOffered', 'labelRequested'));
    }

    public function edit() {

    }

    public function update() {

    }

    public function delete() {

    }

    public function accept(ExchangeRequest $exchangeRequest)
    {
        $user = auth()->user();

        // Verifica que el usuario sea dueño del objeto solicitado
        if ($exchangeRequest->requestedItem->user_id !== $user->id) {
            abort(403, 'No tienes permiso para aceptar esta solicitud.');
        }

        if ($exchangeRequest->status !== 'pending') {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        // Aceptar la solicitud
        $exchangeRequest->update([
            'status' => 'accepted',
            'match_date' => now(),
        ]);

        // Crear el chat asociado si no existe ya
        if (!$exchangeRequest->chat) {
            $exchangeRequest->chat()->create();
        }

        return redirect()->route('intercambios.show', $exchangeRequest->id)
            ->with('success', '¡Intercambio aceptado y chat creado!');
    }

    public function reject(ExchangeRequest $exchangeRequest)
    {
        $user = auth()->user();

        if ($exchangeRequest->requestedItem->user_id !== $user->id) {
            abort(403, 'No tienes permiso para rechazar esta solicitud.');
        }

        if ($exchangeRequest->status !== 'pending') {
            return back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        $exchangeRequest->update([
            'status' => 'rejected',
        ]);

        return redirect()->route('intercambios.index')->with('success', 'Solicitud rechazada correctamente.');
    }

    public function cancel(ExchangeRequest $exchangeRequest)
    {
        $user = auth()->user();

        // Solo el que hizo la oferta puede cancelarla
        if ($exchangeRequest->requester_id !== $user->id) {
            abort(403, 'No tienes permiso para cancelar esta solicitud.');
        }

        if ($exchangeRequest->status !== 'pending') {
            return back()->with('error', 'Solo se pueden cancelar solicitudes pendientes.');
        }

        $exchangeRequest->update([
            'status' => 'cancelled',
            'cancelled_by_requester' => true,
        ]);

        return redirect()->route('intercambios.index')->with('success', 'Solicitud cancelada correctamente.');
    }

    public function confirmarMatch(ExchangeRequest $exchangeRequest)
    {
        $user = auth()->user();
        $isRequester = $exchangeRequest->requester_id === $user->id;

        if ($exchangeRequest->status !== 'accepted') {
            return back()->with('error', 'Este intercambio ya no está activo.');
        }

        // Si ya canceló alguien, no se puede confirmar
        if ($exchangeRequest->cancelled_by_requester || $exchangeRequest->cancelled_by_owner) {
            return back()->with('error', 'Este intercambio tiene una cancelación pendiente.');
        }

        if ($isRequester) {
            if ($exchangeRequest->confirmed_by_requester) {
                return back()->with('info', 'Ya habías confirmado este intercambio.');
            }
            $exchangeRequest->confirmed_by_requester = true;
        } else {
            if ($exchangeRequest->confirmed_by_owner) {
                return back()->with('info', 'Ya habías confirmado este intercambio.');
            }
            $exchangeRequest->confirmed_by_owner = true;
        }

        if ($exchangeRequest->confirmed_by_requester && $exchangeRequest->confirmed_by_owner) {
            $exchangeRequest->status = 'completed';
            $exchangeRequest->completed_at = now();

            // Marcar objetos como intercambiados
            $exchangeRequest->offeredItem->status = 'traded';
            $exchangeRequest->requestedItem->status = 'traded';

            $exchangeRequest->offeredItem->save();
            $exchangeRequest->requestedItem->save();
        }

        $exchangeRequest->save();

        return back()->with('success', 'Intercambio confirmado exitosamente.');
    }

    public function cancelarMatch(ExchangeRequest $exchangeRequest)
    {
        $user = auth()->user();
        $isRequester = $exchangeRequest->requester_id === $user->id;

        if ($exchangeRequest->status !== 'accepted') {
            return back()->with('error', 'Este intercambio ya no está activo.');
        }

        // Si ya se confirmó alguien, no se puede cancelar
        if ($exchangeRequest->confirmed_by_requester || $exchangeRequest->confirmed_by_owner) {
            return back()->with('error', 'Este intercambio tiene una confirmación pendiente.');
        }

        if ($isRequester) {
            if ($exchangeRequest->cancelled_by_requester) {
                return back()->with('info', 'Ya habías solicitado cancelación.');
            }
            $exchangeRequest->cancelled_by_requester = true;
        } else {
            if ($exchangeRequest->cancelled_by_owner) {
                return back()->with('info', 'Ya habías solicitado cancelación.');
            }
            $exchangeRequest->cancelled_by_owner = true;
        }

        if ($exchangeRequest->cancelled_by_requester && $exchangeRequest->cancelled_by_owner) {
            $exchangeRequest->status = 'cancelled';
        }

        $exchangeRequest->save();

        return back()->with('success', 'Has solicitado cancelar el intercambio.');
    }

    public function revertirConfirmacion(ExchangeRequest $exchangeRequest)
    {
        $user = auth()->user();
        $isRequester = $exchangeRequest->requester_id === $user->id;

        if ($exchangeRequest->status !== 'accepted') {
            return back()->with('error', 'Este intercambio ya no está activo.');
        }

        if (
            $exchangeRequest->cancelled_by_requester || $exchangeRequest->cancelled_by_owner ||
            $exchangeRequest->confirmed_by_requester && $exchangeRequest->confirmed_by_owner
        ) {
            return back()->with('error', 'Ya no puedes revertir esta confirmación.');
        }

        if ($isRequester) {
            $exchangeRequest->confirmed_by_requester = false;
        } else {
            $exchangeRequest->confirmed_by_owner = false;
        }

        $exchangeRequest->save();

        return back()->with('success', 'Has revertido tu confirmación.');
    }

    public function revertirCancelacion(ExchangeRequest $exchangeRequest)
    {
        $user = auth()->user();
        $isRequester = $exchangeRequest->requester_id === $user->id;

        if ($exchangeRequest->status !== 'accepted') {
            return back()->with('error', 'Este intercambio ya no está activo.');
        }

        if (
            $exchangeRequest->confirmed_by_requester || $exchangeRequest->confirmed_by_owner ||
            $exchangeRequest->cancelled_by_requester && $exchangeRequest->cancelled_by_owner
        ) {
            return back()->with('error', 'Ya no puedes revertir esta cancelación.');
        }

        if ($isRequester) {
            $exchangeRequest->cancelled_by_requester = false;
        } else {
            $exchangeRequest->cancelled_by_owner = false;
        }

        $exchangeRequest->save();

        return back()->with('success', 'Has revertido tu solicitud de cancelación.');
    }

    public function rechazarPropuesta(ExchangeRequest $exchangeRequest)
    {
        $yo = auth()->id();
        $isRequester = $exchangeRequest->requester_id === $yo;

        Log::debug('Rechazando propuesta para intercambio ID: ' . $exchangeRequest->id);
        Log::debug('Usuario autenticado: ' . $yo);
        Log::debug('Es requester? ' . ($isRequester ? 'sí' : 'no'));
        Log::debug('Estado actual:', [
            'confirmed_by_requester' => $exchangeRequest->confirmed_by_requester,
            'confirmed_by_owner' => $exchangeRequest->confirmed_by_owner,
            'cancelled_by_requester' => $exchangeRequest->cancelled_by_requester,
            'cancelled_by_owner' => $exchangeRequest->cancelled_by_owner,
        ]);

        if (
            $exchangeRequest->confirmed_by_requester ||
            $exchangeRequest->confirmed_by_owner ||
            $exchangeRequest->cancelled_by_requester ||
            $exchangeRequest->cancelled_by_owner
        ) {
            $exchangeRequest->update([
                'confirmed_by_requester' => false,
                'confirmed_by_owner' => false,
                'cancelled_by_requester' => false,
                'cancelled_by_owner' => false,
            ]);

            Log::debug('Propuesta rechazada exitosamente.');

            return back()->with('status', 'Propuesta rechazada. El intercambio vuelve a estado pendiente.');
        }

        Log::debug('No había propuesta que rechazar.');

        return back()->with('status', 'No hay propuesta que rechazar.');
    }

}
