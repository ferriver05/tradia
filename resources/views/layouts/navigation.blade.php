<nav x-data="{ open: false }" class="bg-red-500 text-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo / Nombre -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('user.dashboard') }}" class="flex items-center">
                    <img src="{{ asset('assets/icons/imagotipo/white.png') }}" alt="Tradia" class="h-10">
                </a>
            </div>

            <!-- Links de navegación (desktop) -->
            <div class="hidden md:flex space-x-2">
                <a href="{{ route('user.dashboard') }}"
                class="flex items-center h-16 px-4 hover:bg-red-600 hover:text-white transition">
                Inicio
                </a>

                <a href="{{ route('garaje.index') }}"
                class="flex items-center h-16 px-4 hover:bg-red-600 hover:text-white transition">
                Mi Garaje
                </a>

                <a href="{{ route('vitrina.index') }}"
                class="flex items-center h-16 px-4 hover:bg-red-600 hover:text-white transition">
                Explorar
                </a>

                <a href="{{ route('intercambios.index') }}"
                class="flex items-center h-16 px-4 hover:bg-red-600 hover:text-white transition">
                Intercambios
                </a>

                <a href="{{ route('chats.index') }}"
                class="flex items-center h-16 px-4 hover:bg-red-600 hover:text-white transition">
                Chats
                </a>
            </div>

            <!-- Dropdown de usuario (desktop) -->
            <div class="relative hidden md:block" x-data="{ userOpen: false }">
                <button @click="userOpen = !userOpen" class="flex items-center space-x-2 hover:text-gray-200 focus:outline-none">
                    <i class="fas fa-user-circle text-2xl"></i>
                    <span class="hidden sm:inline">{{ Auth::user()->alias }}</span>
                    <i class="fas fa-chevron-down text-sm"></i>
                </button>

                <!-- Menú desplegable -->
                <div x-show="userOpen" @click.away="userOpen = false" x-cloak
                     x-transition
                     class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded shadow-lg py-1 z-50">
                    <a href="{{ route('profile.show', ['user' => Auth::user()->alias]) }}"
                       class="block px-4 py-2 hover:bg-gray-100">Mi Cuenta</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-4 py-2 hover:bg-gray-100">
                            Salir
                        </button>
                    </form>
                </div>
            </div>

            <!-- Botón hamburguesa (mobile) -->
            <div class="md:hidden">
                <button @click="open = !open" class="focus:outline-none">
                    <i :class="open ? 'fas fa-times' : 'fas fa-bars'" class="text-white text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú responsive completo -->
    <div x-show="open" x-transition class="md:hidden bg-red-500 text-white px-4 pt-2 pb-4 space-y-2">
        <a href="{{ route('user.dashboard') }}" class="block hover:text-gray-200">Inicio</a>
        <a href="{{ route('garaje.index') }}" class="block hover:text-gray-200">Mi Garaje</a>
        <a href="{{ route('vitrina.index') }}" class="block hover:text-gray-200">Vitrina</a>
        <a href="{{ route('intercambios.index') }}" class="block hover:text-gray-200">Intercambios</a>
        <a href="{{ route('chats.index') }}" class="block hover:text-gray-200">Chats</a>
        <hr class="border-white border-opacity-25 my-2">
        <a href="{{ route('profile.show', ['user' => Auth::user()->alias]) }}" class="block hover:text-gray-200">Mi Cuenta</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left hover:text-gray-200">Salir</button>
        </form>
    </div>
</nav>
