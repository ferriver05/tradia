@extends('layouts.app')

@section('content')
    {{-- Vista del Garaje - Tradia App --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-red-500 mb-2">MI GARAJE</h1>
            <p class="text-gray-600 text-base">
                Aquí puedes gestionar todos los objetos que has publicado para intercambiar en Tradia.
            </p>
        </div>


        {{-- Selector superior Garaje/Vitrina --}}
        <div class="flex border-b border-gray-200 mb-6 items-center justify-between">
            <div>
                <a href="{{ route('garaje.index') }}"
                    class="px-6 py-3 text-sm font-medium text-red-600 border-b-2 border-red-600 bg-red-100 rounded-t-lg">
                    Garaje
                </a>
                <a href="{{ route('vitrina.index') }}"
                    class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-t-lg ml-2">
                    Vitrina
                </a>
            </div>
            <a href="{{ route('garaje.create') }}"
                class="inline-block px-5 py-2 mb-1 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition">
                + Publicar objeto
            </a>
        </div>

        {{-- Filtros y búsqueda --}}
        <form method="GET" action="{{ route('garaje.index') }}"
            class="flex flex-wrap justify-between items-center gap-4 mb-6">

            {{-- 🔍 Buscar + botón --}}
            <div class="flex items-center gap-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        class="pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-red-500 focus:border-red-500"
                        placeholder="Buscar en mi garaje...">
                </div>

                <button type="submit"
                    class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                    Buscar
                </button>
            </div>

            {{-- 🧩 Filtros por estado (pills) --}}
            <div class="flex flex-wrap gap-2">
                {{-- Pill "Todos" --}}
                <a href="{{ route('garaje.index', ['buscar' => request('buscar')]) }}"
                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                        {{ request('estado') ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-red-100 text-red-600' }}">
                    Todos
                </a>

                {{-- Pills dinámicos --}}
                @foreach(['activo', 'ofrecido', 'solicitado', 'en_match', 'pausado', 'intercambiado'] as $estado)
                    <button type="submit" name="estado" value="{{ $estado }}"
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                    {{ request('estado') === $estado ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ ucfirst(str_replace('_', ' ', $estado)) }}
                    </button>
                @endforeach
            </div>
        </form>

        {{-- Grid de tarjetas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($items as $item)
                {{-- Item Card Partial --}}
                    @include('items.partials.item-card', [
                        'item' => $item,
                        'context' => 'garaje',
                        'esPropietario' => auth()->check() && auth()->id() === $item->user_id,
                    ])
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600 text-lg mb-4">Aún no has publicado ningún objeto en tu garaje.</p>
                    <a href="{{ route('garaje.create') }}"
                        class="inline-block px-6 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition">
                        + Publicar mi primer objeto
                    </a>
                </div>
            @endforelse
        </div>


        <div class="mt-6">
            {{ $items->withQueryString()->links() }}
        </div>
    </div>
@endsection

@push('scripts')

    {{-- JavaScript para los dropdowns --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Obtener todos los botones de dropdown
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const dropdownId = this.getAttribute('data-dropdown');
                    const dropdown = document.getElementById(dropdownId);

                    // Cerrar otros dropdowns
                    document.querySelectorAll('[id^="dropdown-"]').forEach(otherDropdown => {
                        if (otherDropdown.id !== dropdownId) {
                            otherDropdown.classList.add('hidden');
                        }
                    });

                    // Toggle del dropdown actual
                    dropdown.classList.toggle('hidden');
                });
            });

            // Cerrar dropdowns al hacer clic fuera
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.dropdown-toggle')) {
                    document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                        dropdown.classList.add('hidden');
                    });
                }
            });
        });
    </script>

@endpush