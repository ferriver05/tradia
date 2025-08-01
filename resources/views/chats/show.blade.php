@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">

    {{-- Botón Volver --}}
    <div class="mb-3">
        <button type="button"
            onclick="history.back()"
            class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver
        </button>
    </div>

    {{-- Título --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-red-600 mb-2">Chat de Intercambio</h1>
        <p class="text-gray-600">Conversación activa con <strong>&#64;{{ $chat->exchangeRequest->requester_id === auth()->id() ? $chat->exchangeRequest->requestedItem->user->alias : $chat->exchangeRequest->requester->alias }}</strong></p>
    </div>

    {{-- Mensajes --}}
    <div id="chat-container"
        class="bg-white border border-red-100 rounded-xl shadow px-6 py-4 h-[380px] overflow-y-auto mb-6 flex flex-col-reverse gap-4 pr-2">

        @forelse ($messages as $message)
            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="{{ $message->sender_id === auth()->id() ? 'bg-red-100 text-red-800' : 'bg-gray-200 text-gray-800' }} px-4 py-2 rounded-lg max-w-[70%] break-words">
                    {{ $message->content }}
                    <div class="text-xs text-gray-500 mt-1 text-right">{{ $message->sent_at->format('H:i') }}</div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-sm">Aún no hay mensajes.</p>
        @endforelse
    </div>


    {{-- Formulario --}}
    <form action="{{ route('chats.store', $chat->id) }}" method="POST" class="mt-6">
        @csrf
        <div class="flex items-center gap-4">
            <input type="text" name="content" required placeholder="Escribe un mensaje..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-red-200">
            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                Enviar
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('chat-container');
        container.scrollTop = container.scrollHeight;
    });
</script>
@endpush