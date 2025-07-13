@extends('layouts.app')

@section('content')
    {{-- Vista de la Vitrina - Tradia App --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-red-500 mb-2">VITRINA</h1>
            <p class="text-gray-600 text-base">
                Explora objetos publicados por otros usuarios y encuentra algo que te interese para intercambiar.
            </p>
        </div>

        {{-- Selector superior Garaje/Vitrina --}}
        <div class="flex border-b border-gray-200 mb-6 items-center justify-between">
            <div>
                <a href="{{ route('garaje.index') }}"
                   class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-t-lg">
                    Garaje
                </a>
                <a href="{{ route('vitrina.index') }}"
                   class="px-6 py-3 text-sm font-medium text-red-600 border-b-2 border-red-600 bg-red-100 rounded-t-lg ml-2">
                    Vitrina
                </a>
            </div>

            {{-- Este div es solo para que no afecte la altura  --}}
            <div class="w-[170px] h-[44px]"></div>
        </div>

        {{-- Filtros y búsqueda --}}
        <form id="filtroForm" method="GET" action="{{ route('vitrina.index') }}"
            class="flex flex-wrap justify-between items-center gap-4 mb-6 w-full">

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
                        placeholder="Buscar en la vitrina...">
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                    Buscar
                </button>
            </div>

            {{-- 🧩 Filtro por condición --}}
            <div>
                <label for="categoria" class="sr-only">Categoría</label>
                <select name="categoria" id="categoria"
                        class="py-2 me-3 rounded-lg border border-gray-300 bg-white text-sm focus:ring-red-500 focus:border-red-500">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('categoria') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <label for="condicion" class="sr-only">Condición</label>
                <select name="condicion" id="condicion"
                        class="py-2 me-3 rounded-lg border border-gray-300 bg-white text-sm focus:ring-red-500 focus:border-red-500">
                    <option value="">Todas las condiciones</option>
                    <option value="new" {{ request('condicion') == 'new' ? 'selected' : '' }}>Nuevo</option>
                    <option value="like_new" {{ request('condicion') == 'like_new' ? 'selected' : '' }}>Como nuevo</option>
                    <option value="used" {{ request('condicion') == 'used' ? 'selected' : '' }}>Usado</option>
                    <option value="damaged" {{ request('condicion') == 'damaged' ? 'selected' : '' }}>Dañado</option>
                </select>

                <label for="condicion" class="sr-only">Ubicacion</label>
                <select name="ubicacion" id="ubicacion"
                    class="py-2 rounded-lg border border-gray-300 bg-white text-sm focus:ring-red-500 focus:border-red-500">
                    <option value="">Cualquier ubicacion</option>
                    <option value="ciudad" {{ request('ubicacion') === 'ciudad' ? 'selected' : '' }}>En mi ciudad</option>
                    <option value="estado" {{ request('ubicacion') === 'estado' ? 'selected' : '' }}>En mi estado</option>
                    <option value="pais" {{ request('ubicacion') === 'pais' ? 'selected' : '' }}>En mi país</option>
                </select>
            </div>
        </form>


        {{-- Grid de tarjetas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($items as $item)
                @include('items.partials.item-card', [
                    'item' => $item,
                    'context' => 'vitrina',
                    'esPropietario' => false
                ])
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600 text-lg mb-4">No se encontraron objetos con esos filtros.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $items->withQueryString()->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filtroForm');
            const selects = form.querySelectorAll('select');

            selects.forEach(select => {
                select.addEventListener('change', () => {
                    form.submit(); // envía el formulario al cambiar selección
                });
            });
        });
    </script>
@endpush