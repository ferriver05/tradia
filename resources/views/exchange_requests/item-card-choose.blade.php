@php
    $isPaused = $item->visualStatus() === 'pausado';
@endphp

<div class="rounded-lg shadow-md overflow-visible hover:shadow-lg transition-shadow flex flex-col h-full
    {{ $isPaused ? 'bg-gray-100' : 'bg-white' }} border border-gray-200">
    
    {{-- Imagen principal --}}
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

    {{-- Contenido --}}
    <div class="p-4 flex flex-col flex-1 justify-between">
        <!-- Título y descripción -->
        <div>
            <h3 class="text-lg font-semibold mb-2 break-words line-clamp-2 {{ $isPaused ? 'text-gray-400' : 'text-gray-900' }}">
                {{ $item->title }}
            </h3>
            <p class="text-sm mb-3 break-words line-clamp-2 {{ $isPaused ? 'text-gray-400' : 'text-gray-600' }}">
                {{ $item->description }}
            </p>
        </div>

        <!-- Footer con fecha y botón Seleccionar -->
        <div>
            <p class="text-gray-500 text-xs mb-4">
                Publicado {{ $item->created_at->diffForHumans() }}
            </p>

            <a href="{{ route('intercambios.create', [
                    'requested_item_id' => $requestedItem->id,
                    'offered_item_id' => $item->id,
                ]) }}"
                class="block w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium text-center transition-colors">
                Seleccionar este objeto
            </a>
        </div>
    </div>
</div>
