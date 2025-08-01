<div class="flex flex-wrap gap-3 justify-center md:justify-start">
    <a href="{{ route('exchange-requests.choose-item', $item) }}"
       class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
        <i class="fas fa-exchange-alt"></i>
        Ofrecer un objeto
    </a>

    <a href="{{ route('profile.show', $item->user->alias) }}"
    class="flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
        <i class="fas fa-user"></i>
        Ver perfil del usuario
    </a>
</div>
