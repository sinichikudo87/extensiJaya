<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .animate-shimmer { animation: shimmer 3s infinite; }
    
</style>

{{-- HEADER --}}
<div class="mb-4 p-4 rounded-2xl bg-slate-900/60 border border-white/5 backdrop-blur-md relative z-10">
    <div class="flex items-center justify-between">
        <div>
            <div class="text-xs text-slate-400">Selamat datang,</div>
            <div class="text-sm font-bold text-white">{{ session('email') }}</div>
        </div>
        
        {{-- Tombol Install PWA di Sini --}}
        <button id="installBtn"
            data-state="hidden"
            class="hidden items-center gap-2 px-3 py-1.5 bg-white/[0.03] border border-white/[0.08] rounded-full shadow-inner transition-all duration-300 hover:border-green-400/40 hover:shadow-[0_0_12px_rgba(74,222,128,0.3)]">
            <i class="fab fa-android text-[11px] text-green-400"></i>
            <span class="text-[11px] font-medium text-slate-400 uppercase tracking-tighter">Install App</span>
        </button>
    </div>
</div>

{{-- GRID MENU --}}
<div class="relative w-full p-3 bg-transparent">
    <nav class="flex flex-col gap-4 relative z-10">
        @foreach ($menuData as $menu)
            @php
                if (strtolower($menu->menu_title) === 'dashboard' || $menu->menu_url === 'dashboard') continue;

                $colors = [
                    ['text' => 'text-cyan-400', 'bg' => 'bg-cyan-500/10', 'border' => 'hover:border-cyan-500/30', 'glow' => 'rgba(34,211,238,0.25)', 'bg_card' => 'from-cyan-950/10 to-slate-900/60'],
                    ['text' => 'text-purple-400', 'bg' => 'bg-purple-500/10', 'border' => 'hover:border-purple-500/30', 'glow' => 'rgba(192,132,252,0.25)', 'bg_card' => 'from-purple-950/10 to-slate-900/60'],
                    ['text' => 'text-emerald-400', 'bg' => 'bg-emerald-500/10', 'border' => 'hover:border-emerald-500/30', 'glow' => 'rgba(52,211,153,0.25)', 'bg_card' => 'from-emerald-950/10 to-slate-900/60'],
                    ['text' => 'text-amber-400', 'bg' => 'bg-amber-500/10', 'border' => 'hover:border-amber-500/30', 'glow' => 'rgba(251,191,36,0.25)', 'bg_card' => 'from-amber-950/10 to-slate-900/60'],
                    ['text' => 'text-pink-400', 'bg' => 'bg-pink-500/10', 'border' => 'hover:border-pink-500/30', 'glow' => 'rgba(244,114,182,0.25)', 'bg_card' => 'from-pink-950/10 to-slate-900/60'],
                    ['text' => 'text-indigo-400', 'bg' => 'bg-indigo-500/10', 'border' => 'hover:border-indigo-500/30', 'glow' => 'rgba(129,140,248,0.25)', 'bg_card' => 'from-indigo-950/10 to-slate-900/60'],
                    ['text' => 'text-teal-400', 'bg' => 'bg-teal-500/10', 'border' => 'hover:border-teal-500/30', 'glow' => 'rgba(45,212,191,0.25)', 'bg_card' => 'from-teal-950/10 to-slate-900/60'],
                    ['text' => 'text-rose-400', 'bg' => 'bg-rose-500/10', 'border' => 'hover:border-rose-500/30', 'glow' => 'rgba(251,113,133,0.25)', 'bg_card' => 'from-rose-950/10 to-slate-900/60']
                ];
                $colorTheme = $colors[$loop->index % count($colors)];
                $hasSubmenus = is_array($menu->submenus) && count($menu->submenus) > 0;
            @endphp

            @if($hasSubmenus)
                <div class="group w-full p-4 rounded-2xl bg-gradient-to-b {{ $colorTheme['bg_card'] }} backdrop-blur-md border border-white/[0.05] shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] transition-all duration-300 {{ $colorTheme['border'] }} hover:shadow-[0_0_25px_var(--glow-bg)] relative overflow-hidden" style="--glow-bg: {{ $colorTheme['glow'] }}">
                    {{-- Shimmer Effect --}}
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-500 pointer-events-none"><div class="absolute -inset-[100%] bg-gradient-to-r from-transparent via-white to-transparent rotate-[30deg] animate-shimmer"></div></div>
                    
                    {{-- Watermark --}}
                    <div class="absolute -bottom-5 -right-5 pointer-events-none z-0 opacity-[0.06] {{ $colorTheme['text'] }} transition-all duration-700 group-hover:scale-125 group-hover:rotate-12"><i class="{{ $menu->menu_icon }} text-8xl"></i></div>

                    <div class="flex items-center justify-between mb-4 px-0.5 relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl {{ $colorTheme['bg'] }} border border-white/[0.04] flex items-center justify-center group-hover:scale-105 transition-all"><i class="{{ $menu->menu_icon }} text-sm {{ $colorTheme['text'] }}"></i></div>
                            <div class="flex flex-col"><span class="text-xs font-bold uppercase tracking-wider text-slate-100">{{ $menu->menu_title }}</span><span class="text-[9px] text-slate-400">Kelompok Fitur</span></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 relative z-10">
                        @foreach($menu->submenus as $sub)
                            @if($sub['id'] != 5 || session('user_id') == 1)
                                <a href="{{ $sub['url'] }}" class="group/sub flex flex-col items-center justify-between p-3.5 min-h-[95px] rounded-xl bg-slate-950/40 border border-white/[0.03] hover:border-white/[0.1] active:scale-[0.97] transition-all duration-200">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $colorTheme['text'] }} bg-current opacity-60 group-hover/sub:opacity-100"></div>
                                    <span class="text-[11px] font-semibold text-slate-300 group-hover/sub:text-white text-center">{{ $sub['title'] }}</span>
                                    <div class="px-2 py-0.5 rounded-full bg-white/[0.03] border border-white/[0.05] text-[9px] text-slate-400">Buka →</div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                @if ($loop->first || (isset($menuData[$loop->index-1]->submenus) && count($menuData[$loop->index-1]->submenus) > 0)) <div class="grid grid-cols-2 gap-3 w-full"> @endif
                
                <a href="{{ $menu->menu_url ?? '#' }}" class="group h-24 rounded-2xl bg-gradient-to-b {{ $colorTheme['bg_card'] }} backdrop-blur-md border border-white/[0.05] {{ $colorTheme['border'] }} p-3 flex flex-col items-center justify-center gap-2 shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] hover:shadow-[0_0_20px_var(--glow-bg)] active:scale-[0.97] transition-all duration-300 relative overflow-hidden" style="--glow-bg: {{ $colorTheme['glow'] }}">
                    <div class="absolute -bottom-4 -right-4 pointer-events-none z-0 opacity-[0.06] {{ $colorTheme['text'] }} transition-all duration-500 group-hover:scale-125"><i class="{{ $menu->menu_icon }} text-6xl"></i></div>
                    <div class="w-10 h-10 rounded-xl {{ $colorTheme['bg'] }} flex items-center justify-center relative z-10"><i class="{{ $menu->menu_icon }} text-base {{ $colorTheme['text'] }}"></i></div>
                    <span class="text-[11px] font-bold text-slate-200 group-hover:text-white relative z-10">{{ $menu->menu_title }}</span>
                </a>

                @if ($loop->last || (isset($menuData[$loop->index + 1]->submenus) && count($menuData[$loop->index + 1]->submenus) > 0)) </div> @endif
            @endif
        @endforeach
    </nav>
</div>