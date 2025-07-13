@if(session('success'))
    <div 
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        class="fixed top-20 left-6 z-50 bg-green-100 border border-green-300 text-green-800 px-6 py-4 rounded-lg shadow-xl flex items-center space-x-4 transition-all"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <span class="font-semibold">
            {{ session('success') }}
        </span>
        <button @click="show = false" class="ml-4 text-2xl leading-none text-green-700 hover:text-green-900 focus:outline-none">&times;</button>
    </div>
@endif

@if(session('error'))
    <div 
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        class="fixed top-20 left-6 z-50 bg-yellow-100 border border-yellow-300 text-yellow-800 px-6 py-4 rounded-lg shadow-xl flex items-center space-x-4 transition-all"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <span class="font-semibold">
            {{ session('error') }}
        </span>
        <button @click="show = false" class="ml-4 text-2xl leading-none text-yellow-700 hover:text-yellow-900 focus:outline-none">&times;</button>
    </div>
@endif