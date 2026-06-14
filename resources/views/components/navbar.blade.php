<header x-data="{ searchOpen: false }" @keydown.window.cmd.k.prevent="searchOpen = true"
    class="bg-slate-950/40 backdrop-blur-2xl border-b border-white/[0.05] px-4 md:px-8 py-3 flex items-center justify-between sticky top-0 transition-all duration-500 z-[100]">

    <div class="hidden md:flex items-center gap-4">
    
        <!-- Pages -->
        <div
            class="flex items-center gap-2 px-3 py-1.5 bg-white/[0.03] border border-white/[0.08] rounded-full shadow-inner">
            <i class="fas fa-home text-[10px] text-slate-500"></i>
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-tighter">Pages</span>
            <span class="text-slate-700 text-[10px]">/</span>
            <span x-data x-text="$store.nav.activeTitle"
                class="text-[11px] font-bold text-cyan-400 uppercase tracking-tighter transition-all duration-300">
            </span>
        </div>

    </div>

    <div class="flex items-center gap-6 ml-auto">
        <div
            class="hidden lg:flex flex-col items-start leading-none group-hover:translate-x-1 transition-transform duration-300">
            <div class="flex items-center gap-2 mb-0.5">
                <span
                    class="text-[10px] font-black text-white uppercase tracking-[0.25em] transition-colors group-hover:text-cyan-400">
                    Environmental
                </span>

                <div class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                    <span
                        class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500 shadow-[0_0_10px_rgba(34,211,238,0.8)]"></span>
                </div>
            </div>

            <span class="text-[7px] font-bold text-slate-500 uppercase tracking-[0.15em] flex items-center gap-1">
                <span class="text-cyan-500/50 italic">&</span>
                Engineering Consultant
            </span>
        </div>

        <div class="h-8 w-[1px] bg-gradient-to-b from-transparent via-slate-700 to-transparent"></div>

        <div class="relative flex items-center" x-data="notificationComponent()" x-init="init()">

            <!-- BUTTON -->
            <button @click="toggleNotif()"
                class="relative p-2 rounded-xl border border-white/[0.05] bg-white/[0.02] hover:bg-cyan-500/10 hover:border-cyan-500/30 transition-all duration-300 group">

                <i class="fas fa-bell text-slate-400 group-hover:text-cyan-400 text-sm"></i>

                <!-- 🔴 BADGE -->
                <template x-if="unread > 0">
                    <span class="absolute top-1 right-1 flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                </template>

            </button>

            <!-- DROPDOWN -->
            <template x-teleport="body">
                <div x-show="notifOpen" 
                    @click.outside="notifOpen = false" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-cloak
                    class="fixed z-[999999] w-80 bg-slate-900/95 backdrop-blur-3xl border border-white/[0.1] rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.8)] overflow-hidden"
                    style="top: 70px; right: 20px; bottom: auto;"> <div class="p-4 border-b border-white/[0.05] flex justify-between items-center bg-white/[0.02]">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-comment-alt text-cyan-400 text-xs"></i> <h3 class="text-[10px] font-black text-white uppercase tracking-wider">Notifications</h3>
                        </div>

                        <span x-text="unread + ' NEW'"
                            class="text-[9px] px-2 py-0.5 bg-cyan-500/20 text-cyan-400 rounded-full font-bold animate-pulse">
                        </span>
                    </div>

                    <div class="max-h-80 overflow-y-auto custom-scrollbar">

                        <template x-if="notifications.length === 0">
                            <div class="p-8 text-center text-slate-500 text-xs flex flex-col gap-2">
                                <i class="fas fa-inbox text-2xl opacity-20"></i>
                                <span>Tidak ada notifikasi</span>
                            </div>
                        </template>
                        <template x-for="(notif, index) in notifications" :key="notif.id + '-' + index">
                            <div @click="
                                    notifOpen = false;
                                    openChatModal(
                                        notif.project_id,
                                        notif.project_code,
                                        notif.project_name
                                    )
                                "
                                class="p-4 border-b border-white/[0.02] hover:bg-white/[0.03] transition-colors cursor-pointer group">
                                <div class="flex gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-cyan-500/10 flex items-center justify-center shrink-0 border border-cyan-500/20 group-hover:border-cyan-500/50">
                                        <i class="fas fa-envelope-open text-cyan-400 text-[10px]"></i>
                                    </div>

                                    <div class="flex-1">
                                        <p class="text-[11px] font-bold text-white group-hover:text-cyan-400 transition-colors" x-text="notif.title"></p>
                                        <p class="text-[10px] text-slate-400 leading-relaxed mt-0.5" x-text="notif.message"></p>

                                        <div class="flex items-center justify-between mt-2">
                                            <template x-if="notif.cost && notif.cost > 0">
                                                <span class="text-[9px] text-emerald-400 font-bold flex items-center gap-1">
                                                    <i class="fas fa-coins text-[8px]"></i>
                                                    + Rp <span x-text="new Intl.NumberFormat('id-ID').format(notif.cost)"></span>
                                                </span>
                                            </template>

                                            <span class="text-[8px] text-slate-600 flex items-center gap-1 ml-auto">
                                                <i class="far fa-clock"></i>
                                                <span x-text="notif.notif_time ?? notif.created_at"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="p-3 border-t border-white/[0.05] bg-white/[0.01] text-center">
                        <button class="text-[9px] font-bold text-slate-500 hover:text-cyan-400 transition-colors uppercase tracking-widest">
                            Mark all as read
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex items-center gap-4 group cursor-pointer relative" x-data="{ open: false }">
            <div class="flex items-center gap-4" @click="open = !open">
                <div class="text-right hidden sm:block">
                    <p
                        class="text-xs font-black text-white tracking-tight uppercase group-hover:text-cyan-400 transition-colors">
                        {{ session('employee_name') ?? '-' }}
                    </p>
                    <div class="flex items-center justify-end gap-1.5 mt-0.5">
                        <span class="w-1 h-1 rounded-full bg-cyan-500"></span>
                        <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">
                            {{ session('role_name') ?? '-' }}
                        </p>
                    </div>
                </div>
                <div class="relative">
                    <div
                        class="absolute -inset-1 bg-gradient-to-tr from-cyan-500 to-blue-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-500">
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(session('employee_name') ?? '-') }}&background=0f172a&color=22d3ee&bold=true"
                        alt="Avatar"
                        class="relative w-10 h-10 rounded-2xl border border-white/[0.1] object-cover shadow-2xl">
                </div>
            </div>

            <template x-teleport="body">
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    @click.outside="open = false" x-cloak
                    class="fixed z-[999999] w-56 bg-slate-900/98 backdrop-blur-3xl border border-white/[0.1] rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.8)]"
                    style="top: 70px; right: 20px;">
                    <div class="p-2">
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-300 hover:bg-white/[0.05] hover:text-cyan-400 rounded-2xl transition-all">
                            <i class="fas fa-fingerprint w-4 text-cyan-500"></i> My Identity
                        </a>
                        <div class="my-2 border-t border-white/[0.05]"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-rose-400 hover:bg-rose-500/10 rounded-2xl transition-all">
                                <i class="fas fa-power-off w-4"></i> Terminate Session
                            </button>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="searchOpen" x-cloak @keydown.window.escape="searchOpen = false"
            class="fixed inset-0 z-[1000000] flex items-start justify-center pt-24 p-4">

            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" @click="searchOpen = false"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
            </div>

            <div class="relative w-full max-w-xl bg-slate-900 border border-white/10 rounded-[2.5rem] shadow-2xl overflow-hidden"
                @click.stop x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="p-5 border-b border-white/5 flex items-center gap-4">
                    <i class="fas fa-search text-cyan-500"></i>
                    <input type="text" x-ref="searchInput"
                        x-effect="if(searchOpen) setTimeout(() => $refs.searchInput.focus(), 100)"
                        placeholder="Scanning project codes, reports..."
                        class="w-full bg-transparent border-none outline-none text-white text-sm placeholder:text-slate-600 font-medium focus:ring-0">
                    <button @click="searchOpen = false"
                        class="text-[9px] bg-white/5 hover:bg-white/10 px-2 py-1 rounded text-slate-500 font-mono transition-colors">
                        ESC
                    </button>
                </div>

                <div class="p-8 text-center text-slate-600 italic text-[11px]">
                    No recent scans. Start typing to query terminal...
                </div>
            </div>
        </div>
    </template>
</header>
