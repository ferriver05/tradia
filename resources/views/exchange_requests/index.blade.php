@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-red-500 mb-2">INTERCAMBIOS</h1>
        <p class="text-gray-600 text-base">
            Gestiona tus solicitudes, ofertas y matches en un solo lugar.
        </p>
    </div>

    {{-- Barra de búsqueda y filtros --}}
    <form method="GET" action="{{ route('intercambios.index') }}"
        class="flex flex-wrap justify-between items-center gap-4 mb-6">

        {{-- 🔍 Buscar --}}
        <div class="flex items-center gap-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                    class="pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500"
                    placeholder="Buscar por título...">
            </div>

            <input type="hidden" name="tipo" value="{{ request('tipo') }}">

            <button type="submit"
                class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                Buscar
            </button>
        </div>

        {{-- 🧩 Filtros tipo "pill" --}}
        <div class="flex flex-wrap gap-2">
            {{-- Todos --}}
            <a href="{{ route('intercambios.index', ['buscar' => request('buscar')]) }}"
                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                    {{ request('tipo') ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-red-100 text-red-600' }}">
                Todos
            </a>

            {{-- Match --}}
            <a href="{{ route('intercambios.index', ['tipo' => 'match', 'buscar' => request('buscar')]) }}"
                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                    {{ request('tipo') === 'match' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Match
            </a>

            {{-- Ofrecido --}}
            <a href="{{ route('intercambios.index', ['tipo' => 'offered', 'buscar' => request('buscar')]) }}"
                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                    {{ request('tipo') === 'offered' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Ofertas
            </a>

            {{-- Solicitado --}}
            <a href="{{ route('intercambios.index', ['tipo' => 'requested', 'buscar' => request('buscar')]) }}"
                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                    {{ request('tipo') === 'requested' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Solicitudes
            </a>

            <a href="{{ route('intercambios.index', ['tipo' => 'completed', 'buscar' => request('buscar')]) }}"
                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                    {{ request('tipo') === 'completed' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Intercambiado
            </a>

            {{-- Cancelado --}}
            <a href="{{ route('intercambios.index', ['tipo' => 'cancelled', 'buscar' => request('buscar')]) }}"
                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                    {{ request('tipo') === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Cancelado
            </a>

        </div>
    </form>

    {{-- Grid de tarjetas estilo "intercambio dividido" --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($exchangeRequests as $exchangeRequest)
            <div class="bg-white rounded-xl shadow overflow-hidden relative">

                <div class="absolute top-2 right-2 z-10">
                    @switch($exchangeRequest->status)
                        @case('accepted')
                            <span class="bg-yellow-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">Match</span>
                            @break

                        @case('pending')
                            @if ($exchangeRequest->requester_id === auth()->id())
                                <span class="bg-blue-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">Oferta enviada</span>
                            @elseif ($exchangeRequest->requestedItem->user_id === auth()->id())
                                <span class="bg-orange-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">Solicitud recibida</span>
                            @endif
                            @break

                        @case('cancelled')
                            <span class="bg-gray-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">Cancelado</span>
                            @break

                        @case('completed')
                            <span class="bg-purple-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">Completado</span>
                            @break
                    @endswitch
                </div>

                <div class="grid grid-cols-2 relative">
                    {{-- Imagen del objeto ofrecido --}}
                    <div class="h-[160px] w-full overflow-hidden">
                        <img src="{{ $exchangeRequest->offeredItem->photos->first()?->photo_url
                                    ? asset('storage/' . $exchangeRequest->offeredItem->photos->first()->photo_url)
                                    : 'https://placehold.co/250x250?text=Ofrezco' }}"
                            class="w-full h-full object-cover" />
                    </div>

                    {{-- Imagen del objeto solicitado --}}
                    <div class="h-[160px] w-full overflow-hidden">
                        <img src="{{ $exchangeRequest->requestedItem->photos->first()?->photo_url
                                    ? asset('storage/' . $exchangeRequest->requestedItem->photos->first()->photo_url)
                                    : 'https://placehold.co/250x250?text=Obtengo' }}"
                            class="w-full h-full object-cover" />
                    </div>

                    {{-- Ícono de intercambio --}}
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <i class="fas fa-exchange-alt text-white text-xl bg-black bg-opacity-60 px-3 py-2 rounded-full"></i>
                    </div>
                </div>


                @php
                    $yo = auth()->id();
                    $esRequester = $exchangeRequest->requester_id === $yo;
                    $otroUsuario = $esRequester
                        ? $exchangeRequest->requestedItem->user
                        : $exchangeRequest->offeredItem->user;
                @endphp

                <div class="p-4 text-center">
                    <p class="font-semibold text-gray-800 mb-1 flex items-center justify-center gap-2 text-sm truncate">
                        {{ $exchangeRequest->offeredItem->title }}
                        <i class="fas fa-exchange-alt text-gray-500"></i>
                        {{ $exchangeRequest->requestedItem->title }}
                    </p>
                    
                    <p class="text-sm text-gray-500 mb-3">
                        Intercambiando con: <span class="font-medium text-gray-700">&#64;{{ $otroUsuario->alias }}</span>
                    </p>

                    <a href="{{ route('intercambios.show', $exchangeRequest) }}"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                        Ver detalles
                    </a>
                </div>

            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <p class="text-gray-600 text-lg">No se encontraron intercambios.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $exchangeRequests->links() }}
    </div>
</div>
@endsection
