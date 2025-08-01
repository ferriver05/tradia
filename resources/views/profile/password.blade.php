@extends('layouts.app')

@section('content')
<div class="pt-12 flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-md">

        <h1 class="text-2xl font-bold text-red-500 mb-6 text-center">Cambiar contraseña</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf

            {{-- Contraseña actual --}}
            <div class="mb-4 relative">
                <label for="current_password" class="block text-sm font-medium text-gray-700">Contraseña actual</label>
                <input id="current_password" type="password" name="current_password" required
                       class="mt-1 block w-full pr-10 border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                <button type="button" onclick="togglePassword('current_password')" class="absolute right-3 top-9 text-gray-500 hover:text-red-500">
                    <i class="fa-solid fa-eye"></i>
                </button>
                @error('current_password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nueva contraseña --}}
            <div class="mb-4 relative">
                <label for="password" class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                <input id="password" type="password" name="password" required
                       class="mt-1 block w-full pr-10 border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-9 text-gray-500 hover:text-red-500">
                    <i class="fa-solid fa-eye"></i>
                </button>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirmación --}}
            <div class="mb-6 relative">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="mt-1 block w-full pr-10 border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-9 text-gray-500 hover:text-red-500">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('profile.show', auth()->user()->alias) }}"
                class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al perfil
                </a>

                <button type="submit"
                        class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition font-semibold">
                    Guardar nueva contraseña
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script simple para mostrar/ocultar contraseñas --}}
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endpush
