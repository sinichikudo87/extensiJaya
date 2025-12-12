<nav x-data="{ openMenus: {} }" class="text-sm">
    <ul class="space-y-2">

        @foreach ($menuData as $menu)
            <li>

                @php
                    $allColors = [
                        'text-pink-400',
                        'text-blue-400',
                        'text-green-400',
                        'text-yellow-400',
                        'text-purple-400',
                        'text-red-400',
                        'text-indigo-400',
                        'text-teal-400',
                    ];
                    $color = $allColors[$loop->index % count($allColors)];
                @endphp

                <button
                    class="w-full text-left px-2 py-1 rounded flex justify-between items-center 
                        text-gray-200 hover:text-blue-400 transition text-sm"
                    @click="openMenus['{{ $menu->menu_id }}'] = !openMenus['{{ $menu->menu_id }}']"
                >
                    <span class="flex items-center gap-2">
                        <i class="{{ $menu->menu_icon }} w-5 {{ $color }}"></i>
                        <span>{{ $menu->menu_title }}</span>
                    </span>

                    <!-- Tampilkan panah hanya jika ada submenu -->
                    @if(!empty($menu->submenus))
                        <svg class="w-3 h-3 ml-2 transform transition-transform duration-200"
                            :class="{ 'rotate-180': openMenus['{{ $menu->menu_id }}'] }" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    @endif
                </button>

                <!-- DROPDOWN SUBMENU -->
                @if(!empty($menu->submenus))
                    <ul x-show="openMenus['{{ $menu->menu_id }}']" x-transition
                        class="ml-4 mt-1 space-y-1 pl-2 border-l border-gray-700">
                        @foreach ($menu->submenus as $sub)
                            <li class="flex items-center gap-2">
                                <!-- Bullet Icon -->
                                <span class="w-2 h-2 rounded-full bg-blue-400 flex-shrink-0"></span>

                                <a href="{{ $sub['url'] }}"
                                   class="block text-xs text-gray-300 hover:text-blue-400 transition">
                                    {{ $sub['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

            </li>
        @endforeach

    </ul>
</nav>
