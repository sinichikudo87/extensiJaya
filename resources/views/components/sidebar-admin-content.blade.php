<nav x-data="{ openMenus: {} }" class="mt-4 flex-1 overflow-y-auto text-sm">
    <ul class="space-y-2 px-2">
        @foreach ($menuData as $menu)
            @php
                $allColors = ['text-pink-400', 'text-blue-400', 'text-green-400', 'text-yellow-400', 'text-purple-400', 'text-red-400', 'text-indigo-400', 'text-teal-400'];
                $color = $allColors[$loop->index % count($allColors)];                
                $hasSubmenus = is_array($menu->submenus) && count($menu->submenus) > 0;
            @endphp

            <li>
                @if ($hasSubmenus)
                    <button
                        type="button"
                        class="w-full text-left px-2 py-2 rounded flex justify-between items-center 
                               text-gray-200 hover:bg-gray-800 hover:text-blue-400 transition text-sm sidebar-item"
                        @click="openMenus['{{ $menu->menu_id }}'] = !openMenus['{{ $menu->menu_id }}']">
                        
                        <span class="flex items-center gap-2">
                            <i class="{{ $menu->menu_icon }} w-5 {{ $color }} text-center"></i>
                            <span class="label">{{ $menu->menu_title }}</span>
                        </span>

                        {{-- Icon Panah --}}
                        <svg class="w-3 h-3 ml-2 transform transition-transform duration-200"
                            :class="{ 'rotate-180': openMenus['{{ $menu->menu_id }}'] }" 
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    {{-- Elemen Submenu --}}
                    <ul x-show="openMenus['{{ $menu->menu_id }}']" 
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="ml-4 mt-1 space-y-1 pl-2 border-l border-gray-700">
                        
                        @foreach ($menu->submenus as $sub)
                            @if ($sub['id'] != 5 || session('user_id') == 1)
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-500 flex-shrink-0"></span>
                                    <a href="{{ $sub['url'] }}"
                                        class="block py-1 text-xs text-gray-400 hover:text-blue-400 transition">
                                        {{ $sub['title'] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    {{-- MODE LINK: Untuk menu tunggal (Dashboard, Profile, dll) --}}
                    <a href="{{ $menu->menu_url ?? '#' }}"
                        class="w-full px-2 py-2 rounded flex items-center gap-2 
                               text-gray-200 hover:bg-gray-800 hover:text-blue-400 transition text-sm sidebar-item">
                        <i class="{{ $menu->menu_icon }} w-5 {{ $color }} text-center"></i>
                        <span class="label">{{ $menu->menu_title }}</span>
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</nav>