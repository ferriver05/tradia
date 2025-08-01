<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Item;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Objetos activos
        $objetosActivos = $user->items()
            ->whereNotIn('status', ['paused', 'traded']) // activos aunque estén en match o solicitud
            ->count();

        // Objetos totales (activos + pausados)
        $objetosTotales = $user->items()
            ->whereNot('status', 'traded')
            ->count();

        // Ofertas recibidas (otros usuarios solicitaron mis objetos)
        $ofertasRecibidas = ExchangeRequest::where('status', 'pending')
            ->whereHas('requestedItem', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();

        // Solicitudes enviadas
        $solicitudesEnviadas = $user->exchangeRequests()
            ->where('status', 'pending')
            ->count();

        // Intercambios completados (participó como solicitante u ofertado)
        $intercambiosCompletados = ExchangeRequest::where('status', 'completed')
            ->where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)
                      ->orWhereHas('requestedItem', fn($q) => $q->where('user_id', $user->id))
                      ->orWhereHas('offeredItem', fn($q) => $q->where('user_id', $user->id));
            })
            ->count();

        // Intercambios en curso
        $intercambiosEnCurso = ExchangeRequest::where('status', 'accepted')
            ->where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)
                    ->orWhereHas('requestedItem', fn($q) => $q->where('user_id', $user->id))
                    ->orWhereHas('offeredItem', fn($q) => $q->where('user_id', $user->id));
            })
            ->count();

        // Últimos 3 objetos intercambiados
        $ultimosIntercambios = ExchangeRequest::with(['requestedItem', 'offeredItem', 'requester'])
            ->where('status', 'completed')
            ->where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)
                      ->orWhereHas('requestedItem', fn($q) => $q->where('user_id', $user->id))
                      ->orWhereHas('offeredItem', fn($q) => $q->where('user_id', $user->id));
            })
            ->orderByDesc('completed_at')
            ->take(3)
            ->get();

        // Objetos más solicitados (Top 3)
        $objetosMasSolicitados = $user->items()
            ->whereHas('exchangeRequests', function ($q) {
                $q->where('status', 'pending');
            })
            ->withCount([
                'exchangeRequests as solicitudes_activas_count' => function ($q) {
                    $q->where('status', 'pending');
                }
            ])
            ->orderByDesc('solicitudes_activas_count')
            ->take(3)
            ->get();


        return view('dashboard', compact(
            'objetosActivos',
            'objetosTotales',
            'ofertasRecibidas',
            'solicitudesEnviadas',
            'intercambiosCompletados',
            'intercambiosEnCurso',
            'ultimosIntercambios',
            'objetosMasSolicitados'
        ));
    }
}

