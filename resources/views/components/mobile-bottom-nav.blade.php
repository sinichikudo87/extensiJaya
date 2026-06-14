<div class="
    fixed bottom-0 left-0 right-0 lg:hidden
    h-16
    bg-[#060B18]/95
    backdrop-blur-md
    z-50
    safe-area-inset-bottom
">
    <div class="absolute top-0 left-0 right-0 h-[1px] bg-white/[0.06]"></div>

    <div class="grid grid-cols-3 h-full relative px-4">

        {{-- HOME --}}
        <a href="{{ route('dashboard') }}"
            class="
                flex flex-col items-center justify-center gap-1
                transition-all duration-200 active:opacity-60
                {{ request()->routeIs('dashboard') ? 'text-cyan-400' : 'text-slate-500' }}
            "
        >
            @if(request()->routeIs('dashboard'))
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 drop-shadow-[0_0_8px_rgba(34,211,238,0.4)]">
                    <path d="M11.47 3.822a.75.75 0 0 1 .96 0l8.25 7.5a.75.75 0 1 1-1.02 1.1l-.53-.482v8.31a.75.75 0 0 1-.75.75h-4.5A.75.75 0 0 1 13 20.25v-4.51c0-.28-.22-.5-.5-.5h-1c-.28 0-.5.22-.5.5v4.51a.75.75 0 0 1-.75.75h-4.5a.75.75 0 0 1-.75-.75v-8.31l-.53.482a.75.75 0 1 1-1.02-1.1l8.25-7.5Z" />
                </svg>
                <span class="text-[10px] font-bold tracking-wide">Home</span>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 opacity-70">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span class="text-[10px] font-normal tracking-wide opacity-70">Home</span>
            @endif
        </a>


        {{-- PROJECT (FIXED DESIGN) --}}
        <div class="flex justify-center items-center h-full relative">
            <div class="absolute -top-5 w-16 h-16 rounded-full bg-[#060B18] flex items-center justify-center shadow-[0_-4px_12px_rgba(0,0,0,0.4)]">
                
                <a href="/project-entry"
                    class="
                        flex flex-col items-center justify-center
                        w-14 h-14
                        rounded-full
                        transition-all duration-300
                        active:scale-95
                        {{ request()->routeIs('/project-entry.*') 
                            ? 'bg-cyan-500 text-slate-950 shadow-[0_4px_16px_rgba(34,211,238,0.4)]' 
                            : 'bg-[#131C31] text-slate-300 border border-white/[0.08]' }}
                    "
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h1.5A2.25 2.25 0 0 0 8.25 8.5h3a2.25 2.25 0 0 0 2.25 1.25h1.5A2.25 2.25 0 0 1 19.5 12v.75m-17.25 0h17.25c.621 0 1.125.504 1.125 1.125v4.125c0 .621-.504 1.125-1.125 1.125H3.375a1.125 1.125 0 0 1-1.125-1.125V13.875c0-.621.504-1.125 1.125-1.125Z" />
                    </svg>
                    
                    <span class="text-[8px] font-semibold tracking-tight -mt-0.5">Project</span>
                </a >

            </div>
        </div>


        {{-- PROFILE --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        <button 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="
                flex flex-col items-center justify-center gap-1 w-full h-full
                transition-all duration-200 active:opacity-60 group/nav
                text-slate-500 hover:text-rose-400
            "
        >
            <div class="relative flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
                    class="w-5 h-5 opacity-70 block group-hover/nav:hidden transition-all">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                </svg>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" 
                    class="w-5 h-5 hidden group-hover/nav:block text-rose-400 drop-shadow-[0_0_8px_rgba(251,113,133,0.4)] transition-all">
                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v9a.75.75 0 0 1-1.5 0V3a.75.75 0 0 1 .75-.75ZM6.166 5.73a.75.75 0 0 1 .7 1.33 7.5 7.5 0 1 0 10.268 0 .75.75 0 1 1 .7-1.33 9 9 0 1 1-11.668 0Z" clip-rule="evenodd" />
                </svg>
            </div>

            <span class="text-[10px] font-normal tracking-wide opacity-70 group-hover/nav:opacity-100 group-hover/nav:text-rose-400 group-hover/nav:font-bold transition-all">
                Logout
            </span>
        </button>

    </div>

</div>