<div class="flex flex-col md:flex-row justify-center gap-4 mt-6">
    @php
        $esPostMatch = $exchangeRequest->match_date || $exchangeRequest->chat;
    @endphp

    @if($esPostMatch)
        <!-- Abrir chat -->
        <a href="{{ route('chats.show', ['chat' => $exchangeRequest->chat->id, 'from' => 'exchange']) }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm text-center">
            Abrir chat
        </a>
    @endif
    
    @php
        $yo = auth()->id();
        $otroUsuario = $exchangeRequest->requester_id === $yo
            ? $exchangeRequest->requestedItem->user
            : $exchangeRequest->requester;
    @endphp

    <!-- Ver perfil del solicitante -->
    <a href="{{ route('profile.show', $otroUsuario->alias) }}"
    class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm text-center">
        Ver perfil del usuario
    </a>

    <!-- Regresar -->
    <a href="{{ route('intercambios.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">Volver</a>
</div>