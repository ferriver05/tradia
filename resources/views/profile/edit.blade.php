@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-10 px-4">
    <div class="max-w-4xl mx-auto">

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <!-- Encabezado del perfil -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0 w-full">
                        <!-- Alias -->
                        <h1 class="text-2xl font-bold text-red-500 mb-1">{{ '@' . $user->alias }}</h1>

                        <!-- Nombre completo -->
                        <div class="mb-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fecha de registro -->
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar-alt w-4 h-4 mr-2 text-red-500"></i>
                            <span>Miembro desde {{ $user->created_at->translatedFormat('F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biografía -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-red-500 mb-3 flex items-center">
                    <i class="fas fa-info-circle w-5 h-5 mr-2"></i>
                    Sobre mí
                </h3>
                <textarea id="bio" name="bio" rows="4"
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                >{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ubicación editable si está permitido -->
            @if($puedeCambiarUbicacion)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-red-500 mb-3 flex items-center">
                        <i class="fas fa-globe w-5 h-5 mr-2"></i>
                        Cambiar ubicación
                    </h3>

                    <div>
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
                                        {{ old('country_id', optional(optional($user->cities)->country)->id) == $c->id ? 'selected' : '' }}>
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
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-red-500 mb-3 flex items-center">
                        <i class="fas fa-globe w-5 h-5 mr-2"></i>
                        Ubicación actual
                    </h3>

                    <p class="text-gray-700 leading-relaxed mb-2">
                        {{ $user->full_location ?? 'Sin ubicación definida.' }}
                    </p>

                    <div class="bg-yellow-100 text-yellow-800 text-sm p-3 rounded">
                        No puedes cambiar tu ubicación mientras tengas intercambios pendientes, activos o en curso.
                    </div>
                </div>
            @endif

            <!-- Botones -->
            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('profile.show', $user->alias) }}"
                   class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>

                <button type="submit"
                        class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition font-semibold">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // País → Estados
    document.getElementById('country')?.addEventListener('change', function () {
        const countryId = this.value;
        const stateSelect = document.getElementById('state');
        const citySelect = document.getElementById('city');

        stateSelect.innerHTML = '<option value="">Seleccionar Estado</option>';
        citySelect.innerHTML = '<option value="">Seleccionar Ciudad</option>';
        stateSelect.disabled = true;
        citySelect.disabled = true;

        if (!countryId) return;

        fetch(`/api/states?country_id=${countryId}`)
            .then(res => res.json())
            .then(states => {
                states.forEach(state => {
                    const opt = document.createElement('option');
                    opt.value = state.id;
                    opt.textContent = state.name;
                    stateSelect.appendChild(opt);
                });
                stateSelect.disabled = false;
            });
    });

    // Estado → Ciudades
    document.getElementById('state')?.addEventListener('change', function () {
        const stateId = this.value;
        const citySelect = document.getElementById('city');

        citySelect.innerHTML = '<option value="">Seleccionar Ciudad</option>';
        citySelect.disabled = true;

        if (!stateId) return;

        fetch(`/api/cities?state_id=${stateId}`)
            .then(res => res.json())
            .then(cities => {
                cities.forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city.id;
                    opt.textContent = city.name;
                    citySelect.appendChild(opt);
                });
                citySelect.disabled = false;
            });
    });
</script>
@endpush
