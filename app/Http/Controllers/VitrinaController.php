<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\State;
use Nnjeim\World\Models\Country;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;


class VitrinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Item::class, 'item');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $user_city_id = $user?->city_id;
        $user_state_id = null;
        $user_country_id = null;

        // Obtener datos de ubicación del usuario
        if ($user_city_id) {
            $city = City::find($user_city_id);
            $user_state_id = $city?->state_id;
            $user_country_id = $city?->country_id;
        }

        // Query base con todos los filtros generales
        $baseQuery = Item::query()
            ->where('status', 'active')
            ->where('user_id', '!=', $user->id)
            ->whereDoesntHave('exchangeOffers', function($q) {
                $q->whereIn('status', ['pending', 'accepted']);
            })
            ->whereDoesntHave('exchangeRequests', function($q) {
                $q->where('status', 'accepted');
            })
            ->whereDoesntHave('exchangeRequests', function($q) use ($user) {
                $q->where('requester_id', $user->id)
                ->where('status', 'pending');
            });

        // Filtros adicionales del usuario (búsqueda, condición, categoría)
        if ($request->filled('buscar')) {
            $baseQuery->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->buscar . '%')
                    ->orWhere('description', 'like', '%' . $request->buscar . '%');
            });
        }
        if ($request->filled('condicion')) {
            $baseQuery->where('item_condition', $request->condicion);
        }
        if ($request->filled('categoria')) {
            $baseQuery->where('category_id', $request->categoria);
        }

        // Inicializar colección y variables de IDs encontrados
        $items = collect();
        $found_ids = [];
        $perPage = 12;

        // Opción seleccionada en el filtro
        $filtroUbicacion = $request->input('ubicacion', 'todo');

        // ---- FILTRO POR UBICACIÓN ----
        if ($filtroUbicacion === 'ciudad') {
            // SOLO los de mi ciudad
            if ($user_city_id) {
                $cityItems = (clone $baseQuery)
                    ->whereHas('user', function ($q) use ($user_city_id) {
                        $q->where('city_id', $user_city_id);
                    })
                    ->latest()
                    ->get();
                $items = $items->concat($cityItems);
            }
        }
        elseif ($filtroUbicacion === 'estado') {
            if ($user_city_id && $user_state_id) {
                // 1. Ciudad
                $cityItems = (clone $baseQuery)
                    ->whereHas('user', function ($q) use ($user_city_id) {
                        $q->where('city_id', $user_city_id);
                    })
                    ->latest()
                    ->get();
                $items = $items->concat($cityItems);
                $found_ids = $cityItems->pluck('id')->all();

                // 2. Estado (pero no ciudad)
                $city_ids_estado = City::where('state_id', $user_state_id)->pluck('id')->all();
                $stateItems = (clone $baseQuery)
                    ->whereHas('user', function ($q) use ($city_ids_estado, $user_city_id) {
                        $q->whereIn('city_id', $city_ids_estado)
                        ->where('city_id', '!=', $user_city_id);
                    })
                    ->latest()
                    ->get()
                    ->whereNotIn('id', $found_ids);
                $items = $items->concat($stateItems);
            }
        }
        elseif ($filtroUbicacion === 'pais') {
            if ($user_city_id && $user_state_id && $user_country_id) {
                // 1. Ciudad
                $cityItems = (clone $baseQuery)
                    ->whereHas('user', function ($q) use ($user_city_id) {
                        $q->where('city_id', $user_city_id);
                    })
                    ->latest()
                    ->get();
                $items = $items->concat($cityItems);
                $found_ids = $cityItems->pluck('id')->all();

                // 2. Estado (pero no ciudad)
                $city_ids_estado = City::where('state_id', $user_state_id)->pluck('id')->all();
                $stateItems = (clone $baseQuery)
                    ->whereHas('user', function ($q) use ($city_ids_estado, $user_city_id) {
                        $q->whereIn('city_id', $city_ids_estado)
                        ->where('city_id', '!=', $user_city_id);
                    })
                    ->latest()
                    ->get()
                    ->whereNotIn('id', $found_ids);
                $items = $items->concat($stateItems);
                $found_ids = array_merge($found_ids, $stateItems->pluck('id')->all());

                // 3. País (pero no estado ni ciudad)
                $city_ids_pais = City::where('country_id', $user_country_id)->pluck('id')->all();
                $countryItems = (clone $baseQuery)
                    ->whereHas('user', function ($q) use ($city_ids_pais, $city_ids_estado, $user_city_id) {
                        $q->whereIn('city_id', $city_ids_pais)
                        ->whereNotIn('city_id', $city_ids_estado)
                        ->where('city_id', '!=', $user_city_id);
                    })
                    ->latest()
                    ->get()
                    ->whereNotIn('id', $found_ids);
                $items = $items->concat($countryItems);
            }
        }
        else { // 'todo' o cualquier otra cosa: prioriza por ciudad, estado, país, resto del mundo
            // 1. Ciudad
            if ($user_city_id) {
                $cityItems = (clone $baseQuery)
                    ->whereHas('user', function ($q) use ($user_city_id) {
                        $q->where('city_id', $user_city_id);
                    })
                    ->latest()
                    ->get();
                $items = $items->concat($cityItems);
                $found_ids = $cityItems->pluck('id')->all();
            }
            // 2. Estado (no ciudad)
            if ($user_state_id) {
                $city_ids_estado = City::where('state_id', $user_state_id)->pluck('id')->all();
                $stateItems = (clone $baseQuery)
                    ->whereHas('user', function ($q) use ($city_ids_estado, $user_city_id) {
                        $q->whereIn('city_id', $city_ids_estado)
                        ->where('city_id', '!=', $user_city_id);
                    })
                    ->latest()
                    ->get()
                    ->whereNotIn('id', $found_ids);
                $items = $items->concat($stateItems);
                $found_ids = array_merge($found_ids, $stateItems->pluck('id')->all());
            }
            // 3. País (no estado, no ciudad)
            if ($user_country_id) {
                $city_ids_pais = City::where('country_id', $user_country_id)->pluck('id')->all();
                $countryItems = (clone $baseQuery)
                    ->whereHas('user', function ($q) use ($city_ids_pais, $user_state_id, $user_city_id) {
                        $q->whereIn('city_id', $city_ids_pais)
                        ->when($user_state_id, function($q2) use ($user_state_id) {
                            $city_ids_estado = City::where('state_id', $user_state_id)->pluck('id')->all();
                            $q2->whereNotIn('city_id', $city_ids_estado);
                        })
                        ->where('city_id', '!=', $user_city_id);
                    })
                    ->latest()
                    ->get()
                    ->whereNotIn('id', $found_ids);
                $items = $items->concat($countryItems);
                $found_ids = array_merge($found_ids, $countryItems->pluck('id')->all());
            }
            // 4. Resto del mundo (no país)
            $otherItems = (clone $baseQuery)
                ->whereHas('user', function ($q) use ($user_country_id) {
                    if ($user_country_id) {
                        $city_ids_pais = City::where('country_id', $user_country_id)->pluck('id')->all();
                        $q->whereNotIn('city_id', $city_ids_pais);
                    }
                })
                ->latest()
                ->get()
                ->whereNotIn('id', $found_ids);
            $items = $items->concat($otherItems);
        }

        $currentPage = Paginator::resolveCurrentPage('page');
        $items_paginated = new LengthAwarePaginator(
            $items->forPage($currentPage, $perPage),
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Traer categorías para filtro
        $categories = Category::all();

        return view('items.vitrina.index', [
            'items' => $items_paginated,
            'categories' => $categories
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item, Request $request)
    {
        $item = Item::with(['photos', 'category', 'user'])->findOrFail($item->id);

        $estado = $item->visualStatus();
        $esPropietario = false;

        $ubicacion = $item->user->full_location ?? 'No disponible';

        $contexto = $request->input('contexto', 'vitrina');

        return view('items.show', compact('item', 'estado', 'esPropietario', 'ubicacion', 'contexto'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
