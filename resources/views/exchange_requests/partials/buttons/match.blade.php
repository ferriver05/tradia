@php
    $yo = auth()->id();
    $isRequester = $exchangeRequest->requester_id === $yo;

    $confirmadoPorMi = $isRequester
        ? $exchangeRequest->confirmed_by_requester
        : $exchangeRequest->confirmed_by_owner;

    $confirmadoPorOtro = !$isRequester
        ? $exchangeRequest->confirmed_by_requester
        : $exchangeRequest->confirmed_by_owner;

    $canceladoPorMi = $isRequester
        ? $exchangeRequest->cancelled_by_requester
        : $exchangeRequest->cancelled_by_owner;

    $canceladoPorOtro = !$isRequester
        ? $exchangeRequest->cancelled_by_requester
        : $exchangeRequest->cancelled_by_owner;
@endphp

{{-- BOTONES --}}
<div class="flex flex-col md:flex-row justify-center gap-4 mt-6">
    
    {{-- Siempre disponible --}}
    <a href="{{ route('chats.show', $exchangeRequest->chat->id) }}"
    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm text-center">
        Abrir chat
    </a>

    @if(!$confirmadoPorMi && !$canceladoPorMi && !$confirmadoPorOtro && !$canceladoPorOtro)

        <form action="{{ route('intercambios.match.confirmar', $exchangeRequest) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                Marcar como intercambiado
            </button>
        </form>

        <form action="{{ route('intercambios.match.cancelar', $exchangeRequest) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                Solicitar cancelación
            </button>
        </form>

    @elseif(!$confirmadoPorMi && $confirmadoPorOtro && !$canceladoPorOtro && !$canceladoPorMi)
        {{-- El otro confirmó, vos aún no --}}
        <form action="{{ route('intercambios.match.confirmar', $exchangeRequest) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                Confirmar intercambio
            </button>
        </form>

        <form action="{{ route('intercambios.match.propuesta.rechazar', $exchangeRequest) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                Rechazar propuesta
            </button>
        </form>

    @elseif(!$canceladoPorMi && $canceladoPorOtro && !$confirmadoPorOtro && !$confirmadoPorMi)
        {{-- El otro canceló, vos aún no --}}
        <form action="{{ route('intercambios.match.cancelar', $exchangeRequest) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                Aceptar cancelación
            </button>
        </form>

        <form action="{{ route('intercambios.match.propuesta.rechazar', $exchangeRequest) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                Rechazar cancelación
            </button>
        </form>

    @elseif($confirmadoPorMi && !$confirmadoPorOtro && !$canceladoPorOtro)
        {{-- Vos confirmaste primero --}}
        <form action="{{ route('intercambios.match.confirmar.revertir', $exchangeRequest) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                Revertir confirmación
            </button>
        </form>

    @elseif($canceladoPorMi && !$canceladoPorOtro && !$confirmadoPorOtro)
        {{-- Vos cancelaste primero --}}
        <form action="{{ route('intercambios.match.cancelar.revertir', $exchangeRequest) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                Revertir cancelación
            </button>
        </form>
    @endif

    @php
        $otroUsuario = $isRequester
            ? $exchangeRequest->requestedItem->user
            : $exchangeRequest->requester;
    @endphp

    {{-- Siempre disponibles --}}
    <a href="{{ route('profile.show', $otroUsuario) }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm text-center">
        Ver perfil del usuario
    </a>

    <a href="{{ url()->previous() }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">
        Volver
    </a>
</div>

{{-- TEXTO INFORMATIVO --}}
@php
    $mensaje = null;
    $color = null;
    $icono = null;

    if($confirmadoPorMi && !$confirmadoPorOtro) {
        $mensaje = 'Has marcado como intercambiado. Esperando confirmación del otro usuario.';
        $color = 'green';
        $icono = 'fas fa-check-circle';
    } elseif($confirmadoPorOtro && !$confirmadoPorMi) {
        $mensaje = 'El otro usuario marcó como intercambiado. Confirma si ya se concretó.';
        $color = 'green';
        $icono = 'fas fa-user-clock';
    } elseif($canceladoPorMi && !$canceladoPorOtro) {
        $mensaje = 'Has solicitado la cancelación. Esperando respuesta del otro usuario.';
        $color = 'red';
        $icono = 'fas fa-times-circle';
    } elseif($canceladoPorOtro && !$canceladoPorMi) {
        $mensaje = 'El otro usuario ha solicitado cancelar el intercambio. ¿Estás de acuerdo?';
        $color = 'red';
        $icono = 'fas fa-exclamation-triangle';
    }
@endphp

@if($mensaje)
    <div class="mt-3 flex justify-center">
        <div class="flex items-center gap-3 text-{{ $color }}-700">
            <i class="{{ $icono }} text-lg text-{{ $color }}-600"></i>
            <p class="text-sm font-medium leading-relaxed text-center text-{{ $color }}-700">
                {{ $mensaje }}
            </p>
        </div>
    </div>
@endif
