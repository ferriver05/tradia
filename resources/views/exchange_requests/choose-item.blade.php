@extends('layouts.app')

@section('content')
    {{-- Vista: Elegir Objeto a Ofrecer - Tradia App --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Título y contexto --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-red-500 mb-2">ELIGE UN OBJETO PARA OFRECER</h1>
            <p class="text-gray-600 text-base">
                Selecciona uno de tus objetos disponibles para proponer el intercambio.
            </p>
        </div>

        {{-- Barra superior: Botón Volver + Búsqueda --}}
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            {{-- Botón Volver --}}
            <a href="{{ url()->previous() ?? route('items.vitrina.index') }}"
            class="inline-block px-5 py-2 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition">
                ← Volver al objeto
            </a>

            {{-- Formulario de búsqueda --}}
            <form method="GET" action="" class="flex items-center gap-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        class="pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-red-500 focus:border-red-500"
                        placeholder="Buscar en mis objetos...">
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                    Buscar
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($items as $item)
                @include('exchange_requests.item-card-choose')
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600 text-lg mb-4">
                        No tienes objetos disponibles para ofrecer.
                    </p>
                    <a href="{{ route('garaje.create', ['redirect_to' => request()->fullUrl()]) }}"
                    class="inline-block px-6 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition">
                        + Publicar nuevo objeto
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $items->withQueryString()->links() }}
        </div>
    </div>
@endsection
