@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header del Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Bienvenido, {{ Auth::user()->name }}</h1>
        <p class="text-gray-600">Resumen de tu actividad en Tradia</p>
    </div>

    <!-- Sección de KPIs - 6 Tarjetas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Objetos Activos -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $objetosActivos }}</h3>
                    <p class="text-gray-600">Objetos activos</p>
                </div>
            </div>
        </div>

        <!-- Objetos Totales -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $objetosTotales }}</h3>
                    <p class="text-gray-600">Objetos totales</p>
                </div>
            </div>
        </div>

        <!-- Ofertas Recibidas -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $ofertasRecibidas }}</h3>
                    <p class="text-gray-600">Ofertas recibidas</p>
                </div>
            </div>
        </div>

        <!-- Solicitudes Enviadas -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $solicitudesEnviadas }}</h3>
                    <p class="text-gray-600">Solicitudes enviadas</p>
                </div>
            </div>
        </div>

        <!-- Intercambios Completados -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-600">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $intercambiosCompletados }}</h3>
                    <p class="text-gray-600">Intercambios completados</p>
                </div>
            </div>
        </div>

        <!-- Intercambios En Curso -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $intercambiosEnCurso }}</h3>
                    <p class="text-gray-600">Matchs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Intercambios -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @if ($ultimosIntercambios->isEmpty())
            <p class="text-gray-500 text-sm">Aún no has completado ningún intercambio.</p>
        @else
            <!-- Últimos 3 Objetos Intercambiados -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Últimos Intercambios</h3>
                <div class="space-y-4">
                    @foreach($ultimosIntercambios as $intercambio)
                    @php
                        $objeto = $intercambio->requestedItem->user_id === Auth::id()
                            ? $intercambio->offeredItem
                            : $intercambio->requestedItem;
                        $otroUsuario = $intercambio->requester_id === Auth::id()
                            ? $intercambio->requestedItem->user->alias
                            : $intercambio->requester->alias;
                    @endphp
                    <div class="flex items-center space-x-4 p-3 border border-gray-200 rounded-lg">
                        <img src="{{ asset('storage/' . ($objeto->photos->first()->photo_url ?? 'placeholder.jpg')) }}" alt="" class="w-16 h-16 rounded-lg object-cover" />
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $objeto->title }}</h4>
                            <p class="text-sm text-gray-600">Con {{ '@' }}{{ $otroUsuario }}</p>
                            <p class="text-xs text-gray-500">{{ $intercambio->completed_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Top 3 Objetos con Más Solicitudes -->
        @if ($objetosMasSolicitados->isEmpty())
            <p class="text-gray-500 text-sm">Tus objetos aún no han recibido solicitudes.</p>
        @else
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Objetos Más Solicitados</h3>
                <div class="space-y-4">
                    @foreach($objetosMasSolicitados as $item)
                    <div class="flex items-center space-x-4 p-3 border border-gray-200 rounded-lg">
                        <img src="{{ asset('storage/' . ($item->photos->first()->photo_url ?? 'placeholder.jpg')) }}" alt="" class="w-16 h-16 rounded-lg object-cover" />
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $item->title }}</h4>
                            <p class="text-sm text-yellow-600">{{ $item->solicitudes_activas_count }} solicitud(es)</p>
                            <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">{{ ucfirst($item->status) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection