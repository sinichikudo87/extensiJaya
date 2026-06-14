<aside id="sidebar" x-data="{
    isCollapsed: false,
    openMenus: {},
    toggle() {
        this.isCollapsed = !this.isCollapsed;
        if (this.isCollapsed) { this.openMenus = {}; }
    }
}" :class="isCollapsed ? 'w-20' : 'w-72'"
    class="flex-shrink-0 flex flex-col relative h-screen bg-[#060B18] 
            border-r border-white/5 transition-all duration-500 
            ease-[cubic-bezier(0.4,0,0.2,1)] shadow-[10px_0_30px_rgba(0,0,0,0.5)] z-50">

    {{-- Header Sidebar --}}
    <div class="sidebar-header scrollbar-thin h-24 flex 
        items-center justify-between px-6 border-b border-white/[0.03] 
        bg-gradient-to-b from-white/[0.02] to-transparent transition-all duration-500"
        :class="isCollapsed ? 'justify-center px-0' : 'justify-between'">

        <div class="flex items-center gap-4 overflow-hidden" x-show="!isCollapsed"
            x-transition:enter="transition opacity duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">
            <div class="flex flex-col min-w-0 transition-all duration-500 group cursor-default">
                <span class="font-black text-lg leading-tight text-white uppercase tracking-tighter italic">
                    UP Work <span class="text-cyan-500">Langgeng</span>
                </span>
                <div class="flex items-center gap-2 mt-0.5">
                    <span
                        class="text-[9px] font-black text-cyan-500 uppercase tracking-[0.4em] leading-none">Consultant</span>
                    <span
                        class="h-[1px] flex-1 bg-gradient-to-r from-cyan-600/60 to-transparent rounded-full opacity-50"></span>
                </div>
            </div>
        </div>

        {{-- Tombol Toggle --}}
        <button id="toggleSidebar" @click="toggle()"
            class="group relative flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-slate-950 border border-cyan-500/30 text-cyan-400/60 hover:text-cyan-300 transition-all duration-500 shadow-[0_0_10px_rgba(6,182,212,0.1)] hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] overflow-hidden">

            <div class="absolute inset-0 bg-cyan-500/0 group-hover:bg-cyan-500/10 transition-colors duration-500"></div>

            <div
                class="absolute inset-0 rounded-full border border-cyan-400 opacity-0 group-hover:opacity-100 blur-[1px] transition-opacity duration-500">
            </div>

            <div class="relative z-10 flex items-center justify-center w-4 h-4 transition-transform duration-500"
                :class="isCollapsed ? 'rotate-180' : 'rotate-0'">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    class="w-full h-full drop-shadow-[0_0_3px_rgba(6,182,212,0.5)]">

                    <line x1="4" y1="12" x2="14" y2="12"
                        class="transition-all duration-500" :class="isCollapsed ? 'opacity-0' : 'opacity-100'"></line>

                    <polyline points="8 18 14 12 8 6" class="transition-transform duration-500"
                        :class="isCollapsed ? 'translate-x-1' : ''"></polyline>

                    <line x1="19" y1="18" x2="19" y2="6" class="opacity-40"></line>
                </svg>
            </div>

            <div
                class="absolute inset-y-0 -left-[100%] w-full bg-gradient-to-r from-transparent via-cyan-400/30 to-transparent group-hover:left-[100%] transition-all duration-700">
            </div>
        </button>
    </div>

    {{-- Content --}}
    <div class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar py-6">
        @include('components.sidebar-admin-content')
    </div>

    {{-- PWA Sidebar --}}
    <div class="relative p-4 bg-black/40">
        <button id="installBtn"
            data-state="hidden"
            class="group hidden items-center gap-2 px-3 py-1.5 
                bg-white/[0.03] border border-white/[0.08] rounded-full shadow-inner
                transition-all duration-300
                hover:border-green-400/40 hover:shadow-[0_0_12px_rgba(74,222,128,0.3)]">

            <i id="installIcon"
            class="fab fa-android text-[11px] text-green-400 transition-transform duration-300 group-hover:scale-110"></i>

            <span id="installText"
                class="text-[11px] font-medium text-slate-400 uppercase tracking-tighter">
                Install
            </span>
        </button>
    </div>

    {{-- Footer Sidebar --}}
    <div class="relative p-4 bg-black/40">
        <div
            class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-cyan-500/50 to-transparent">
        </div>
        <div class="absolute top-0 left-1/4 right-1/4 h-[1px] bg-cyan-400 shadow-[0_0_10px_rgba(6,182,212,0.8)] z-10">
        </div>

        {{-- Card Support (Versi Terbuka) --}}
        <div x-show="!isCollapsed" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="p-2.5 rounded-xl bg-gradient-to-br from-cyan-500/10 to-blue-600/5 border border-cyan-500/20 mb-3 relative overflow-hidden group">

            <div class="absolute -right-2 -top-2 w-8 h-8 bg-cyan-500/10 rounded-full blur-lg"></div>

            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <div class="relative flex items-center justify-center shrink-0">
                        <i class="fas fa-headset text-cyan-400 text-[10px] animate-[bounce_3s_infinite]"></i>
                        <span class="absolute inset-0 bg-cyan-400 blur-[2px] opacity-30 animate-pulse"></span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-cyan-400 uppercase tracking-wider leading-none">Fleet
                            Support</p>
                        <p class="text-[8px] text-slate-500 leading-tight mt-0.5">Kendala sistem? Chat aja</p>
                    </div>
                </div>

                <a href="https://wa.me/62881036959865" target="_blank"
                    class="flex items-center gap-1.5 px-2 py-1 bg-emerald-600 hover:bg-emerald-500 text-white text-[8px] font-bold rounded-lg transition shadow-md shadow-emerald-900/30 uppercase shrink-0">
                    <span>Chat</span>
                    <i class="fab fa-whatsapp text-[10px]"></i>
                </a>
            </div>
        </div>

        {{-- Ikon Bantuan (Hanya saat mini) --}}
        <div x-show="isCollapsed" x-transition class="flex flex-col items-center pb-4 transition-all duration-300">
            <a href="#"
                class="relative w-12 h-12 flex items-center justify-center rounded-2xl bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500 hover:text-white transition-all border border-cyan-500/30 group shadow-[0_0_15px_rgba(6,182,212,0.1)] hover:shadow-[0_0_20px_rgba(6,182,212,0.4)]">
                <i class="fas fa-headset text-sm group-hover:scale-110 transition-transform"></i>
                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-cyan-500 border border-[#060B18]"></span>
                </span>
            </a>
        </div>

        {{-- Version & Status --}}
        <div class="px-2 pt-2 flex flex-col gap-2">
            <div class="flex justify-between items-center text-[10px] text-slate-500 font-medium font-mono"
                :class="isCollapsed ? 'justify-center' : ''">
                <span x-show="!isCollapsed" class="opacity-40 tracking-tighter">VER. 1.0.0</span>
                <span class="flex items-center gap-2" :class="isCollapsed ? 'mx-auto' : ''">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span
                            class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                    </span>
                    <span x-show="!isCollapsed"
                        class="uppercase tracking-[0.2em] text-[8px] font-black text-emerald-500/70">Live Data
                        Connection</span>
                </span>
            </div>
        </div>
    </div>
</aside>
