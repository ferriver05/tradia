
<div class="flex flex-col md:flex-row justify-center gap-4 mt-6">
    <!-- Cancelar oferta -->
    <form action="{{ route('intercambios.cancel', $exchangeRequest) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">Cancelar solicitud</button>
    </form>

    @php
        $yo = auth()->id();
        $otroUsuario = $exchangeRequest->requester_id === $yo
            ? $exchangeRequest->requestedItem->user
            : $exchangeRequest->requester;
    @endphp

    <!-- Ver perfil del solicitante -->
    <a href="{{ route('profile.show', $otroUsuario) }}"
    class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm text-center">
        Ver perfil del usuario
    </a>

    <!-- Regresar -->
    <a href="{{ url()->previous() }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">Volver</a>
</div>