<div class="flex flex-col md:flex-row justify-center gap-4 mt-6">
    <!-- Aceptar -->
    <form action="{{ route('intercambios.accept', $exchangeRequest) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">Aceptar</button>
    </form>

    <!-- Rechazar -->
    <form action="{{ route('intercambios.reject', $exchangeRequest) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">Rechazar</button>
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
    <a href="{{ route('intercambios.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">Volver</a>
</div>