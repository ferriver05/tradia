    <div x-data="{ open: false }" class="flex flex-wrap gap-3 justify-center md:justify-start">

        {{-- Botón Editar --}}
        @if (in_array($estado, ['activo', 'pausado']))
            <a href="{{ route('garaje.edit', $item) }}"
            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-pen"></i>
                Editar
            </a>
        @endif

        {{-- Botón Pausar --}}
        @if ($estado === 'activo')
            @can('pause', $item)
                <form method="POST" action="{{ route('garaje.pause', $item) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-pause-circle"></i>
                        Pausar
                    </button>
                </form>
            @endcan
        @endif

        {{-- Botón Reactivar --}}
        @if ($estado === 'pausado')
            @can('reactivate', $item)
                <form method="POST" action="{{ route('garaje.reactivate', $item) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-play-circle"></i>
                        Reactivar
                    </button>
                </form>
            @endcan
        @endif

        {{-- Botón Ver solicitudes --}}
        @if ($estado === 'solicitado')
            <a href="#"
            class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-eye"></i>
                Ver solicitudes
            </a>
        @endif

        {{-- Botón Ver match --}}
        @if ($estado === 'en_match')
            <a href="#"
            class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-handshake"></i>
                Ver match
            </a>
        @endif

        {{-- Botón Ver intercambio --}}
        @if ($estado === 'intercambiado')
            <a href="#"
            class="flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-archive"></i>
                Ver intercambio
            </a>
        @endif

        {{-- Botón Eliminar --}}
        @if (in_array($estado, ['activo', 'solicitado', 'pausado']))
            <a href="#" @click.prevent="open = true"
            class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-trash-alt"></i>
                Eliminar
            </a>
        @endif

        {{-- Modal de confirmación --}}
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

