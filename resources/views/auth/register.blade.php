<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Registro</title>
    
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

        <!-- Sección derecha - Formulario de registro -->
        <div class="lg:w-1/2 flex justify-center items-center p-4 lg:p-8">
            <div class="w-full max-w-2xl bg-white bg-opacity-90 backdrop-blur-sm rounded-lg shadow-xl p-6">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800 text-center mb-1">
                        Crear Cuenta
                    </h2>
                    <p class="text-gray-600 text-center text-sm">
                        Únete a nuestra comunidad
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Primera fila: Nombre y Alias -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Campo de Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre Completo
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                maxlength="100"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 @error('name') border-red-500 @enderror"
                                placeholder="Tu Nombre Completo"
                            >
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo de Alias (Username) -->
                        <div>
                            <label for="alias" class="block text-sm font-medium text-gray-700 mb-1">
                                Alias
                            </label>
                            <input 
                                type="text" 
                                id="alias" 
                                name="alias" 
                                pattern="[A-Za-z0-9_]+"
                                title="Sólo letras, números y guión bajo"
                                value="{{ old('alias') }}"
                                required
                                autocomplete="username"
                                maxlength="50"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 @error('alias') border-red-500 @enderror"
                                placeholder="tu_alias"
                            >
                            @error('alias')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Segunda fila: Email -->
                    <div>
                        <!-- Campo de Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Correo Electrónico
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                maxlength="150"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 @error('email') border-red-500 @enderror"
                                placeholder="tu@email.com"
                            >
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tercera fila: Ubicación (País, Estado, Ciudad) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ubicación <span class="text-gray-400 text-xs">(Opcional)</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <!-- País -->
                            <div>
                                <select 
                                    id="country" 
                                    name="country_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 @error('country_id') border-red-500 @enderror"
                                >
                                    <option value="">Seleccionar País</option>
                                    @foreach($countries as $c)
                                        <option value="{{ $c->id }}"
                                        {{ old('country_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div>
                                <select 
                                    id="state" 
                                    name="state_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 @error('state_id') border-red-500 @enderror"
                                    disabled
                                >
                                    <option value="">Seleccionar Estado</option>
                                </select>
                                @error('state_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ciudad -->
                            <div>
                                <select 
                                    id="city" 
                                    name="city_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 @error('city_id') border-red-500 @enderror"
                                    disabled
                                >
                                    <option value="">Seleccionar Ciudad</option>
                                </select>
                                @error('city_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Cuarta fila: Contraseñas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Campo de Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Contraseña
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                autocomplete="new-password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200 @error('password') border-red-500 @enderror"
                                placeholder="••••••••"
                            >
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo de Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirmar Contraseña
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required
                                autocomplete="new-password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200"
                                placeholder="••••••••"
                            >
                        </div>
                    </div>

                    <!-- Botón de Registro -->
                    <button 
                        type="submit" 
                        class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg font-medium hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200"
                    >
                        Crear Cuenta
                    </button>
                </form>

                <!-- Pregunta para login -->
                <div class="mt-4 text-center">
                    <p class="text-gray-600 text-sm">
                        ¿Ya tienes una cuenta? 
                        <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-medium transition duration-200">
                            Inicia sesión aquí
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const countrySelect = document.getElementById('country');
            const stateSelect   = document.getElementById('state');
            const citySelect    = document.getElementById('city');

            const statesUrl = "{{ route('api.states') }}";
            const citiesUrl = "{{ route('api.cities') }}";

            function fill(select, items, placeholder) {
                select.innerHTML = `<option value="">${placeholder}</option>`;
                items.forEach(i => {
                    const o = document.createElement('option');
                    o.value = i.id; o.text = i.name;
                    select.append(o);
                });
            }

            // Al cambiar país
            countrySelect.addEventListener('change', async () => {
                const cid = countrySelect.value;
                fill(stateSelect, [], 'Seleccionar Estado');
                stateSelect.disabled = true;
                fill(citySelect,  [], 'Seleccionar Ciudad');
                citySelect.disabled = true;

                if (!cid) return;

                try {
                    const res = await fetch(`${statesUrl}?country_id=${cid}`);
                    const data = await res.json();

                    fill(stateSelect, data, 'Seleccionar Estado');
                    stateSelect.disabled = false;
                    
                    @if(old('state_id'))
                        stateSelect.value = "{{ old('state_id') }}";
                        stateSelect.dispatchEvent(new Event('change'));
                    @endif
                } catch (e) {
                    console.error('Error cargando estados', e);
                }
            });

            // Al cambiar estado
            stateSelect.addEventListener('change', async () => {
                const sid = stateSelect.value;
                fill(citySelect, [], 'Seleccionar Ciudad');
                citySelect.disabled = true;
                if (!sid) return;

                try {
                    const res = await fetch(`${citiesUrl}?state_id=${sid}`);
                    const data = await res.json();

                    fill(citySelect, data, 'Seleccionar Ciudad');
                    citySelect.disabled = false;

                    @if(old('city_id'))
                        citySelect.value = "{{ old('city_id') }}";
                    @endif
                } catch (e) {
                    console.error('Error cargando ciudades', e);
                }
            });

            // Si venían valores old(), dispara chain
            @if(old('country_id'))
                countrySelect.value = "{{ old('country_id') }}";
                countrySelect.dispatchEvent(new Event('change'));
            @endif
        });
    </script>
</body>
</html>