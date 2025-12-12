<nav class="bg-white dark:bg-gray-800 shadow px-4 py-3 flex justify-between items-center sticky top-0 z-50 h-14 border-b border-gray-700">

    <!-- Sidebar Toggle (Mobile) -->
    <button id="toggleSidebar" class="md:hidden text-gray-700 dark:text-gray-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Title -->
    <h1 class="text-2xl font-bold bg-clip-text text-transparent 
            bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-500 
            drop-shadow-lg">
        @yield('title')
    </h1>

    <!-- Right side - User Dropdown -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" 
                class="text-gray-600 dark:text-gray-300 font-medium flex items-center gap-2 focus:outline-none">
            
            <span class="font-bold text-lg bg-clip-text text-transparent 
                        bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 
                        drop-shadow-md">
                Jimbrink
            </span>

            <div class="w-5 h-5 rounded-full bg-indigo-500 flex items-center justify-center ml-2">
                <svg :class="{'rotate-180': open, 'rotate-0': !open}" 
                    class="w-3 h-3 text-white transition-transform duration-300" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" @click.away="open = false" 
            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 shadow-lg rounded-md py-2 z-50 transition-all">

            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                <i class="fas fa-user text-blue-500"></i> Profile
            </a>

            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                <i class="fas fa-cog text-purple-500"></i> Settings
            </a>

            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                <i class="fas fa-sign-out-alt text-indigo-500"></i> Logout
            </a>
        </div>
    </div>

</nav>
