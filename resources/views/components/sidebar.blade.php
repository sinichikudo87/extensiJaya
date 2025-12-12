<aside id="sidebar"
    class="w-64 bg-gray-900 text-gray-200 shadow-md h-screen 
           fixed left-0 top-0 z-[100] transform -translate-x-full md:translate-x-0
           transition-transform duration-300 ease-in-out flex flex-col scrollbar-thin">

    <!-- Tombol Close (mobile only) -->
    <button id="closeSidebar" 
        class="md:hidden absolute right-3 top-3 text-white text-2xl leading-none">
        ×
    </button>

    <h2 class="text-lg md:text-xl font-bold mt-10 mb-6 text-center 
            bg-gradient-to-r from-yellow-400 via-white to-yellow-400 
            bg-clip-text text-transparent animate-gradient-x">
        Jimat Tracker
    </h2>

    <hr class="border-t-2 border-yellow-400 mb-4">

    <div class="flex-1 overflow-y-auto scrollbar-thin">
        <div class="px-4">
            @include('components.sidebar-content')
        </div>
    </div>

</aside>
