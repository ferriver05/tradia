@php
    $estado = $item->visualStatus();
@endphp

<div class="relative" x-data="{ open: false }">
    <button class="text-gray-400 hover:text-gray-600 p-1 dropdown-toggle" data-dropdown="dropdown-{{ $item->id }}">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
        </svg>
    </button>

    <div id="dropdown-{{ $item->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">

        @if ($esPropietario)

            {{-- Editar --}}
            @can('update', $item)
                @if (in_array($estado, ['activo', 'ofrecido', 'pausado']))
                    <a href="{{ route('garaje.edit', $item) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Editar</a>
                @endif
            @endcan

            {{-- Pausar --}}
            @if ($estado === 'activo')
                @can('pause', $item)
                    <form method="POST" action="{{ route('garaje.pause', $item) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pausar</button>
                    </form>
                @endcan
            @endif

            {{-- Reactivar --}}
            @if ($estado === 'pausado')
                @can('reactivate', $item)
                    <form method="POST" action="{{ route('garaje.reactivate', $item) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Reactivar</button>
                    </form>
                @endcan
            @endif

            {{-- Ver mi oferta (NO protegido) --}}
            @if ($estado === 'ofrecido')
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ver mi oferta</a>
            @endif

            {{-- Ver solicitudes (NO protegido) --}}
            @if ($estado === 'solicitado')
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ver solicitudes</a>
            @endif

            {{-- Ver match (NO protegido) --}}
            @if ($estado === 'en_match')
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ver match</a>
            @endif

            {{-- Ver intercambio (NO protegido) --}}
            @if ($estado === 'intercambiado')
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ver intercambio</a>
            @endif

            {{-- Eliminar --}}
            @can('delete', $item)
                @if (in_array($estado, ['activo', 'ofrecido', 'solicitado', 'pausado']))
                    <a href="#" @click.prevent="open = true" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Eliminar</a>
                @endif
            @endcan

        @else
            {{-- Dropdown limitado para vitrina --}}
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ver perfil</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Proponer oferta</a>
        @endif
    </div>

    {{-- Modal de confirmación para Eliminar --}}
    <div x-show="open" x-cloak
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-xl max-w-sm w-full">
            <h2 class="text-xl font-bold text-red-500 mb-2">¿Eliminar este objeto?</h2>
            <p class="mb-4 text-gray-600 text-sm">
                Al eliminar este objeto, se borrarán todas las solicitudes, ofertas y cualquier historial asociado. <br>
                <span class="text-red-500 font-semibold">Esta acción es irreversible.</span>
            </p>
            <div class="flex justify-end gap-2">
                <button @click="open = false"
                        class="px-4 py-2 rounded text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium">
                    Cancelar
                </button>
                <button @click="$refs.form.submit()"
                        class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600 font-bold">
                    Eliminar definitivamente
                </button>
            </div>
            <form x-ref="form" action="{{ route('garaje.destroy', $item) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
