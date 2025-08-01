<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\ItemPhoto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class GarajeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Item::class, 'item');
    }

    /**
     * Display a listing of the resource of the user.
     */
    public function index(Request $request)
    {
        $estado = $request->query('estado');
        $buscar = $request->query('buscar');

        $items = auth()->user()->items()->with(['photos', 'category']);

        if ($buscar) {
            $items->where(function ($q) use ($buscar) {
                $q->where('title', 'like', "%{$buscar}%")
                ->orWhere('description', 'like', "%{$buscar}%");
            });
        }

        if ($estado) {
            switch ($estado) {
                case 'ofrecido':
                    $items->BeingOffered();
                    break;

                case 'solicitado':
                    $items->BeingRequested();
                    break;

                case 'en_match':
                    $items->InMatch();
                    break;

                case 'intercambiado':
                    $items->where('status', 'traded');
                    break;

                case 'pausado':
                    $items->where('status', 'paused');
                    break;

                case 'activo':
                default:
                    $items->where('status', 'active');
                    break;
            }
        }

        $items = $items->latest()->paginate(12);

        return view('items.garaje.index', [
            'items' => $items,
            'estado' => $estado,
            'buscar' => $buscar,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $conditions = Item::CONDITIONS;
        
        return view('items.garaje.create', compact('categories', 'conditions') + [
            'redirectTo' => $request->redirect_to,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'category' => 'required|exists:categories,id',
            'condition' => ['required', Rule::in(array_keys(Item::CONDITIONS))],
            'exchange_preferences' => 'nullable|string|max:255',
            'photos' => 'required|array|min:1|max:5',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $item = Item::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category,
            'item_condition' => $request->condition,
            'exchange_preferences' => $request->exchange_preferences,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                
                $path = $photo->store('fotos_items', 'public');

                ItemPhoto::create([
                    'item_id' => $item->id,
                    'photo_url' => $path,
                ]);
            }
        }

        return redirect()->to($request->input('redirect_to', route('garaje.index')))
            ->with('status', 'Objeto publicado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['photos', 'category', 'user', 'exchangeRequests']);

        $esPropietario = auth()->check() && auth()->id() === $item->user_id;

        $ubicacion = $item->user->full_location ?? 'No disponible';

        $estado = $item->visualStatus();

        return view('items.show', compact('item', 'estado', 'esPropietario', 'ubicacion'))
        ->with('contexto','garaje');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $categories = Category::all();
        $conditions = Item::CONDITIONS;

        return view('items.garaje.edit', [
            'item' => $item,
            'categories' => $categories,
            'conditions' => $conditions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $photosToDelete = array_filter(explode(',', $request->input('photos_to_delete', '')));
        $existingPhotosCount = $item->photos()->whereNotIn('id', $photosToDelete)->count();
        $newPhotosCount = $request->hasFile('photos') ? count($request->file('photos')) : 0;
        $totalPhotos = $existingPhotosCount + $newPhotosCount;

        $rules = [
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'category' => 'required|exists:categories,id',
            'condition' => ['required', Rule::in(array_keys(Item::CONDITIONS))],
            'exchange_preferences' => 'nullable|string|max:255',
            'photos' => $totalPhotos === 0 ? 'required|array|min:1|max:5' : 'array|max:' . (5 - $existingPhotosCount),
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $request->validate($rules);

        if (!empty($photosToDelete)) {
            $photos = $item->photos()->whereIn('id', $photosToDelete)->get();
            foreach ($photos as $photo) {
                \Storage::disk('public')->delete($photo->photo_url);
                $photo->delete();
            }
        }

        $item->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category,
            'item_condition' => $request->condition,
            'exchange_preferences' => $request->exchange_preferences,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('fotos_items', 'public');
                ItemPhoto::create([
                    'item_id' => $item->id,
                    'photo_url' => $path,
                ]);
            }
        }

        return redirect()->route('garaje.index')
        ->with('success', '¡Objeto actualizado correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        if ( $item->status == 'traded' || $item->hasMatchConfirmed() ) {
            return redirect()->route('garaje.index')
                ->with('error', 'No puedes eliminar un objeto que está en proceso de intercambio o ya fue intercambiado.');
        }

        DB::beginTransaction();
        try {
            foreach ($item->photos as $photo) {
                \Storage::disk('public')->delete($photo->photo_url);
                $photo->delete();
            }

            $item->delete();

            DB::commit();
            return redirect()->route('garaje.index')
                ->with('success', 'Objeto eliminado correctamente. Todas las fotos, solicitudes y/o oferta asociadas han sido eliminadas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('garaje.index')
                ->with('error', 'Ocurrió un error al eliminar el objeto. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Sets the item status to 'paused'.
     */
    public function pause(Item $item)
    {
        $this->authorize('pause', $item);

        try {
            $item->pause();
            return back()->with('success', 'El objeto ha sido pausado correctamente.');
        } catch (\Exception $e) {
            return back()->with(['error' => 'No se pudo pausar el objeto.']);
        }
    }

    /**
     * Sets the item status to 'active'.
     */
    public function reactivate(Item $item)
    {
        $this->authorize('reactivate', $item);

        try {
            $item->reactivate();
            return back()->with('success', 'El objeto ha sido reactivado correctamente.');
        } catch (\Exception $e) {
            return back()->with(['error' => 'No se pudo reactivar el objeto.']);
        }
    }
}
