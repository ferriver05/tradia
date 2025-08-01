@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-10">

    {{-- Título general de sección --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-red-500 mb-2">MIS CHATS</h1>
        <p class="text-gray-600 text-base">Aquí puedes ver tus conversaciones activas de intercambio.</p>
    </div>

    {{-- Recuadro de contenido --}}
    <div class="bg-white rounded-xl shadow border p-6">
        @if ($chats->isEmpty())
            <p class="text-gray-600">Aún no tienes intercambios activos con chat disponible.</p>
        @else
            <div class="space-y-4">
                @foreach ($chats as $chat)

                    @php
                        $estado = $chat->estado;

                        $clases = [
                            'accepted' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-yellow-100 text-yellow-700', // por si acaso llega uno mal cargado
                            'completed' => 'bg-blue-100 text-blue-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                        ];

                        $textos = [
                            'accepted' => 'Match activo',
                            'completed' => 'Finalizado',
                            'cancelled' => 'Cancelado',
                            'pending' => 'Pendiente',
                        ];

                        $badgeClass = $clases[$estado] ?? 'bg-gray-200 text-gray-700';
                        $badgeText = $textos[$estado] ?? ucfirst($estado);
                    @endphp

                    <a href="{{ route('chats.show', $chat->id) }}"
                       class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition">

                        <div class="flex items-center gap-4">
                            <img src="{{ $chat->foto ?? asset('images/placeholder.png') }}"
                                alt="Foto del objeto"
                                class="w-16 h-16 rounded-lg object-cover border border-gray-300">

                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-gray-800 font-semibold text-lg">{{ $chat->titulo }}</p>
                                    <span class="text-xs {{ $badgeClass }} px-2 py-0.5 rounded-full">
                                        {{ $badgeText }}
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm">con <span class="text-gray-800 font-bold">&#64;{{ $chat->alias }}</span></p>
                                @if($chat->ultimo_mensaje)
                                    <p class="text-sm text-gray-500 truncate max-w-md">"{{ $chat->ultimo_mensaje }}"</p>
                                @endif
                            </div>
                        </div>

                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
    
    <div class="mt-6">
        {{ $chats->links() }}
    </div>
</div>
@endsection
