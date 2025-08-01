@extends('layouts.app')

@section('content')

    <!-- Contenedor principal -->
    <div class="max-w-7xl mx-auto px-8 py-10 min-h-screen">

        <!-- Grid principal de 2 columnas en pantallas md+ -->
        <div class="grid grid-cols-1 md:grid-cols-2 md:items-start gap-8">

            <!-- LADO IZQUIERDO: Galería de imágenes SIN recuadro blanco -->
            @php
                $photos = $item->photos->pluck('photo_url')->toArray();
            @endphp

            <div x-data="{ 
                    photos: @js($photos),
                    currentIndex: 0,
                    get currentPhoto() { return this.photos[this.currentIndex]; }
                }" 
                class="relative"
            >
                <div class="relative bg-gray-200 overflow-hidden rounded-lg aspect-square">

                    {{-- Imagen actual --}}
                    <img :src="'{{ asset('storage') }}/' + currentPhoto"
                        alt="Imagen del objeto"
                        class="w-full h-full object-cover transition-all duration-300 ease-in-out">

                    @php
                        $colorClass = match($estado) {
                            'activo'         => 'bg-green-500',
                            'ofrecido'       => 'bg-blue-500',
                            'solicitado'     => 'bg-orange-500',
                            'en_match'       => 'bg-yellow-500',
                            'pausado'        => 'bg-gray-500',
                            'intercambiado'  => 'bg-purple-500',
                            default          => 'bg-gray-400',
                        };
                    @endphp

                    {{-- Estado del ítem --}}
                    <div class="absolute top-4 right-4 flex items-center gap-2 bg-white bg-opacity-80 px-3 py-1 rounded-full text-sm font-medium shadow">
                        <div class="w-2 h-2 {{ $colorClass }} rounded-full"></div>
                        {{ ucfirst($estado) }}
                    </div>

                    {{-- Contador de imágenes --}}
                    <div class="absolute top-4 left-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                        <span x-text="(currentIndex + 1) + '/' + photos.length"></span>
                    </div>

                    {{-- Flecha izquierda --}}
                    <button @click="currentIndex = (currentIndex === 0 ? photos.length - 1 : currentIndex - 1)"
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 shadow-md">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    {{-- Flecha derecha --}}
                    <button @click="currentIndex = (currentIndex === photos.length - 1 ? 0 : currentIndex + 1)"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 shadow-md">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                </div>
            </div>

            <!-- LADO DERECHO: Detalles del ítem -->
            <div class="bg-white p-7 rounded-lg shadow-lg space-y-6">

                <!-- Título del objeto -->
                <h1 class="text-3xl font-bold text-gray-900 leading-tight break-words">
                    {{ $item->title }}
                </h1>

                <!-- Fechas de creación y actualización -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Creado: {{ $item->created_at->translatedFormat('d \d\e F, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        <span>Actualizado: {{ $item->updated_at->translatedFormat('d \d\e F, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5s-3 1.343-3 3 1.343 3 3 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 22s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z" />
                        </svg>
                        <span>Ubicación: {{ $ubicacion ?? 'No disponible' }}</span>
                    </div>
                </div>

                <!-- Categoría y condición -->
                <div class="flex flex-wrap gap-3">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                        {{ $item->category->name ?? 'Sin categoría' }}
                    </span>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ \App\Models\Item::CONDITIONS[$item->item_condition] ?? 'Sin especificar' }}
                    </span>
                </div>

                <!-- Descripción -->
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900">Descripción</h3>
                    <p class="text-gray-700 leading-relaxed break-words">
                        {!! nl2br(e($item->description)) !!}
                    </p>
                </div>

                <!-- Preferencias de intercambio -->
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900">Preferencias de Intercambio</h3>
                    @foreach(preg_split('/\r\n|\r|\n/', $item->exchange_preferences) as $pref)
                        @if(trim($pref) !== '')
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <span class="text-gray-700 break-words">{{ $pref }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- BARRA DE ACCIONES dentro del recuadro derecho -->
                <div class="pt-4 border-t">
                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">

                        @switch($contexto)
                            @case('garaje')
                                @include('items.garaje.partials.buttons', ['item' => $item, 'estado' => $estado])
                                @break

                            @case('vitrina')
                                @include('items.vitrina.partials.buttons', ['item' => $item, 'estado' => $estado])
                                @break
                        @endswitch


                        @php
                            $volverA = match($contexto) {
                                'vitrina' => route('vitrina.index'),
                                'garaje' => route('garaje.index'),
                                'confirmacion' => route('exchange-requests.pending'),
                                default => route('dashboard'),
                            };
                        @endphp

                        <a href="{{ $volverA }}"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-arrow-left"></i>
                            Volver
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection