@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-10 px-4">
    <div class="max-w-4xl mx-auto">

        <!-- Encabezado del perfil -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <!-- Alias -->
                    <h1 class="text-2xl font-bold text-red-500 mb-1">{{ '@' . $user->alias }}</h1>

                    <!-- Nombre completo -->
                    <h2 class="text-xl text-gray-800 mb-2">{{ $user->name }}</h2>

                    <!-- Ubicación -->
                    @if($user->full_location)
                    <div class="flex items-center text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt w-4 h-4 mr-2 text-red-500"></i>
                        <span>{{ $user->full_location }}</span>
                    </div>
                    @endif

                    <!-- Fecha de registro -->
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-calendar-alt w-4 h-4 mr-2 text-red-500"></i>
                        <span>Miembro desde {{ $user->created_at->translatedFormat('F Y') }}</span>
                    </div>
                </div>

                <!-- Botones de acción (solo si es su propio perfil) -->
                @auth
                    @if(auth()->id() === $user->id)
                    <div class="flex flex-col space-y-2 md:space-y-0 md:space-x-3 md:flex-row">
                        <a href="{{ route('profile.edit') }}"
                           class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-edit w-4 h-4 mr-2"></i>
                            Editar perfil
                        </a>
                        <a href="{{ route('profile.password') }}"
                           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-lock w-4 h-4 mr-2"></i>
                            Cambiar contraseña
                        </a>
                    </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Biografía -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-red-500 mb-3 flex items-center">
                <i class="fas fa-info-circle w-5 h-5 mr-2"></i>
                Sobre mí
            </h3>
            <p class="text-gray-700 leading-relaxed">
                {{ $user->bio ?? 'Este usuario aún no ha escrito su biografía.' }}
            </p>
        </div>

        <!-- Resumen de intercambios -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-red-500 mb-4 flex items-center">
                <i class="fas fa-exchange-alt w-5 h-5 mr-2"></i>
                Resumen de intercambios
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Completados -->
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="text-2xl font-bold text-green-600 mb-1">{{ $completados }}</div>
                    <div class="text-sm text-green-700 font-medium">Completados</div>
                </div>

                <!-- Cancelados -->
                <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="text-2xl font-bold text-red-600 mb-1">{{ $cancelados }}</div>
                    <div class="text-sm text-red-700 font-medium">Cancelados</div>
                </div>

                <!-- Tasa de éxito -->
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="text-2xl font-bold text-blue-600 mb-1">{{ $tasa }}%</div>
                    <div class="text-sm text-blue-700 font-medium">Tasa de éxito</div>
                </div>
            </div>

            <div class="mt-4 p-3 bg-gray-50 rounded-lg text-center">
                <p class="text-sm text-gray-600">
                    Total de intercambios registrados: <span class="font-medium">{{ $total }}</span>
                </p>
            </div>

            <div class="mt-6">
                <button type="button"
                    onclick="window.history.back()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
