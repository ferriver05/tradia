@php
    $isPaused = $item->visualStatus() === 'pausado';
@endphp

<div class="rounded-lg shadow-md overflow-visible hover:shadow-lg transition-shadow flex flex-col h-full
    {{ $isPaused ? 'bg-gray-100' : 'bg-white' }}">
    <div class="relative">
        <img 
            src="{{ $item->photos->first() ? asset('storage/' . $item->photos->first()->photo_url) : 'https://via.placeholder.com/400x250' }}" 
            alt="{{ $item->title }}" 
            class="w-full h-48 object-cover rounded-t-lg {{ $isPaused ? 'grayscale' : '' }}">
        <span class="absolute top-2 left-2 px-2 py-1 rounded-full text-xs font-medium text-white
                     {{ match($item->visualStatus()) {
                        'activo' => 'bg-green-500',
                        'ofrecido' => 'bg-blue-500',
                        'solicitado' => 'bg-orange-500',
                        'en_match' => 'bg-yellow-500',
                        'pausado' => 'bg-gray-500',
                        'intercambiado' => 'bg-purple-500',
                        default => 'bg-gray-400',
                     } }}">
            {{ ucfirst(str_replace('_', ' ', $item->visualStatus())) }}
        </span>
    </div>
    <div class="p-4 flex flex-col flex-1 justify-between">
        <!-- Contenido principal: Título y descripción -->
        <div>
            <h3 class="text-lg font-semibold mb-2 break-words line-clamp-2 {{ $isPaused ? 'text-gray-400' : 'text-gray-900' }}">
                {{ $item->title }}
            </h3>
            <p class="text-sm mb-3 break-words line-clamp-2 {{ $isPaused ? 'text-gray-400' : 'text-gray-600' }}">
                {{ $item->description }}
            </p>
        </div>
        <!-- Footer SIEMPRE abajo -->
        <div>
            <p class="text-gray-500 text-xs mb-4">
                @php

                    $ubicacion = $item->user->full_location ?? 'No disponible';
                @endphp

                @if (($context ?? null) === 'vitrina')
                    <span class="inline-flex items-center gap-1 break-words line-clamp-2">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $ubicacion }}
                    </span>
                @elseif ($item->visualStatus() === 'intercambiado')
                    Intercambiado {{ $item->updated_at->diffForHumans() }}
                @else
                    Publicado {{ $item->created_at->diffForHumans() }}
                @endif
            </p>
            <div class="flex justify-between items-center">

                @php
                    $contexto = $context ?? 'garaje';
                    $rutaShow = $contexto === 'vitrina'
                        ? route('vitrina.show', $item)
                        : route('garaje.show', $item);
                @endphp
                <a href="{{ $rutaShow }}?from={{ $contexto }}"
                   class="bg-red-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-600 transition-colors">
                    Ver
                </a>
                @include('items.partials.dropdown', ['item' => $item])
            </div>
        </div>
    </div>
</div>
