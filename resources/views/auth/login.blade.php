<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .bg-image {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/two-people-exchanging.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .letter-coral { color: #FF6F61; }
        .letter-pink { color: #FFB6B9; }
        .letter-cream { color: #F4F1DE; }
        .letter-dark { color: #2E2E2E; }
        .letter-sage { color: #81B29A; }
    </style>
</head>
<body class="min-h-screen bg-image">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Sección izquierda - Título y subtítulo -->
        <div class="lg:w-1/2 flex flex-col justify-start items-center p-8 lg:p-16 pt-16 lg:pt-24">
            <div class="text-center">
                <h1 class="text-6xl md:text-7xl lg:text-8xl xl:text-9xl font-bold mb-6 drop-shadow-2xl text-white">
                    TRADIA
                </h1>
                <p class="text-xl md:text-2xl lg:text-3xl xl:text-4xl text-white drop-shadow-lg font-medium">
                    Comparte. Cambia. Confía.
                </p>
            </div>
        </div>

        <!-- Sección derecha - Formulario de login -->
        <div class="lg:w-1/2 flex justify-center items-center p-8 lg:p-16">
            <div class="w-full max-w-md bg-white bg-opacity-90 backdrop-blur-sm rounded-lg shadow-xl p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">
                        Iniciar Sesión
                    </h2>
                    <p class="text-gray-600 text-center">
                        Accede a tu cuenta
                    </p>
                </div>

                <!-- Session Status (Breeze) -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Campo de Email (Combinado) -->
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700 mb-2">
                            Alias o correo
                        </label>
                        <input 
                        type="text" 
                        id="login" 
                        name="login" 
                        value="{{ old('login') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 @error('login') border-red-500 @enderror"
                        placeholder="Correo o alias"
                        />
                        @error('login')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo de Contraseña (Combinado) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me (De Breeze, agregado a tu diseño) -->
                    <div class="flex items-center justify-between">
                        <!-- Olvidé mi contraseña (Funcional con Breeze) -->
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-red-600 hover:text-red-700 transition duration-200">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Botón de Login (Funcional) -->
                    <button 
                        type="submit" 
                        class="w-full bg-red-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200"
                    >
                        Iniciar Sesión
                    </button>
                </form>

                <!-- Pregunta para registro (Funcional si tienes registro habilitado) -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600">
                        ¿No tienes una cuenta? 
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-red-600 hover:text-red-700 font-medium transition duration-200">
                                Regístrate aquí
                            </a>
                        @else
                            {{-- Comentario: El enlace de registro no está disponible --}}
                            <span class="text-gray-400">Contacta al administrador</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>