@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-8">
    <div class="max-w-6xl w-full">
        <h1 class="text-3xl font-bold text-red-500 text-center mb-8">Confirmar Intercambio</h1>

        <div class="flex flex-col lg:flex-row items-center justify-center gap-6">
            <!-- Objeto OFRECIDO -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-md p-6 w-full max-w-sm text-center">
                <h2 class="text-red-500 text-xl font-bold mb-4">OFREZCO</h2>
                <img src="{{ $exchangeRequest->offeredItem->photos->first() ? asset('storage/' . $exchangeRequest->offeredItem->photos->first()->photo_url) : 'https://via.placeholder.com/400x250' }}" alt="Imagen del objeto ofrecido" class="w-[250px] h-[200px] object-cover object-center rounded-xl mx-auto mb-4">
                <p class="text-lg font-semibold text-gray-800">{{ $exchangeRequest->offeredItem->title }}</p>
                <p class="text-sm text-gray-600 italic">Condición: {{ \App\Models\Item::CONDITIONS[$exchangeRequest->offeredItem->item_condition] }}</p>
                <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt mr-1"></i>{{ $exchangeRequest->offeredItem->user->full_location }}</p>
            </div>

            <!-- Icono -->
            <div class="hidden lg:flex items-center justify-center">
                <i class="fas fa-exchange-alt text-red-400 text-3xl"></i>
            </div>

            <!-- Objeto SOLICITADO -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-md p-6 w-full max-w-sm text-center">
                <h2 class="text-red-500 text-xl font-bold mb-4">OBTENGO</h2>
                <img src="{{ $exchangeRequest->requestedItem->photos->first() ? asset('storage/' . $exchangeRequest->requestedItem->photos->first()->photo_url) : 'https://via.placeholder.com/400x250' }}" alt="Imagen del objeto solicitado" class="w-[250px] h-[200px] object-cover object-center rounded-xl mx-auto mb-4">
                <p class="text-lg font-semibold text-gray-800">{{ $exchangeRequest->requestedItem->title }}</p>
                <p class="text-sm text-gray-600 italic">Condición: {{ \App\Models\Item::CONDITIONS[$exchangeRequest->requestedItem->item_condition] }}</p>
                <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt mr-1"></i>{{ $exchangeRequest->requestedItem->user->full_location }}</p>
            </div>
        </div>

        <!-- Confirmar o Cancelar -->
        <div class="mt-10 flex flex-col md:flex-row justify-center gap-4">
            <form method="POST" action="{{ route('intercambios.store') }}">
                @csrf
                <input type="hidden" name="offered_item_id" value="{{ $exchangeRequest->offeredItem->id }}">
                <input type="hidden" name="requested_item_id" value="{{ $exchangeRequest->requestedItem->id }}">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                    Enviar Oferta
                </button> 
            </form>

            <a href="{{ route('vitrina.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                Cancelar
            </a>
        </div>
    </div>
</div>
@endsection
