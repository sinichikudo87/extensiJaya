@extends('layouts.admin')

@section('content')
    <script>
        window.monitoringData = {
            summary: {!! json_encode($summary ?? []) !!}
        };
    </script>

    {{-- TAMBAHKAN CLASS 'hidden md:block' DI PEMBUNGKUS UTAMA AGAR KONTEN SKRENING TOTAL DI MOBILE --}}
    <div x-data="dashboardManager()" 
         x-init="init()" 
         class="hidden md:block p-3 md:p-6 bg-[#0f172a] min-h-screen font-sans text-slate-200">

        {{-- HEADER --}}
        <div class="mb-4 md:mb-6">
            <h1 class="text-lg md:text-xl font-semibold tracking-tight">
                MONITORING PROJECT
            </h1>
            <p class="text-slate-400 text-[11px] md:text-xs">
                Real-time data overview
            </p>
        </div>

        {{-- ================= TOP (Kategori & Summary) ================= --}}
        <div class="flex flex-col lg:flex-row gap-3 md:gap-4 mb-6 md:mb-8">

            {{-- KATEGORI --}}
            <div class="flex flex-col flex-grow space-y-2">
                <p class="text-[9px] md:text-[10px] font-bold text-cyan-400 uppercase tracking-[0.2em] ml-1">
                    Project Aktif
                </p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-3">
                    <template x-for="card in kategoriCards" :key="card.label">
                        <div class="bg-slate-800/60 border border-slate-700/50 p-3 md:p-4 rounded-xl bg-gradient-to-br transition-all hover:border-slate-500"
                             :class="card.color">
                            <p class="text-[8px] md:text-[9px] font-bold text-slate-300 uppercase tracking-wider mb-1"
                               x-text="card.label">
                            </p>
                            <h2 class="text-xl md:text-2xl font-black text-white drop-shadow-sm"
                                x-text="card.value">
                            </h2>
                        </div>
                    </template>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="w-full lg:w-44 bg-white text-slate-900 p-3 md:p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-50 pb-2">
                        <p class="text-[8px] md:text-[9px] font-black text-slate-400 uppercase tracking-tighter">
                            Summary
                        </p>
                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-[8px] font-bold text-slate-400 uppercase">Finish</p>
                            <p class="text-xl md:text-lg font-black text-emerald-600 leading-none"
                               x-text="summary.project_finish || 0">
                            </p>
                        </div>
                        <div class="border-l border-slate-100 pl-2">
                            <p class="text-[8px] font-bold text-slate-400 uppercase">Hold</p>
                            <p class="text-xl md:text-lg font-black text-amber-500 leading-none"
                               x-text="summary.project_hold || 0">
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-t border-slate-100">
                    <p class="text-[8px] font-bold text-slate-400 uppercase leading-none">
                        Total Project
                    </p>
                    <h2 class="text-3xl md:text-2xl font-black text-slate-900 tracking-tighter leading-none mt-1"
                        x-text="summary.total_project || 0">
                    </h2>
                </div>
            </div>
        </div>

        {{-- ================= FINANCIAL ================= --}}
        @if (auth()->id() == 1)
            <div class="mb-6 md:mb-8">
                <h2 class="text-sm font-semibold text-cyan-400 mb-3">
                    Monitoring Financial
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                    <div class="bg-slate-800 border border-slate-700 p-4 rounded-xl">
                        <p class="text-[11px] text-slate-400">Pemasukan</p>
                        <h3 class="text-lg md:text-xl font-bold text-rose-400"
                            x-text="formatJuta(summary.total_pengeluaran)">
                        </h3>
                    </div>

                    <div class="bg-slate-800 border border-cyan-500/30 p-4 rounded-xl bg-gradient-to-br from-slate-800 to-slate-900">
                        <p class="text-[11px] text-slate-400">Pengeluaran</p>
                        <h3 class="text-lg md:text-xl font-bold text-cyan-400"
                            x-text="formatJuta(summary.total_additional_cost)">
                        </h3>
                    </div>
                </div>
            </div>
        @endif

        {{-- ================= CHART & DESKTOP TABLE ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 md:gap-4">

            {{-- CHART --}}
            <div class="bg-slate-800/40 border border-slate-700/50 p-3 md:p-4 rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Project Aktif
                    </p>
                    <span class="text-[8px] md:text-[9px] text-slate-500 font-medium">
                        By Category
                    </span>
                </div>
                <div class="relative h-[180px] w-full">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            {{-- DESKTOP TABLE ONLY (Daftar Mobile Card di dalam sini sudah dihapus karena tidak dipakai) --}}
            <div class="bg-slate-800/40 border border-slate-700/50 p-3 md:p-4 rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Latest Project Update
                    </p>
                    <span class="text-[8px] md:text-[9px] bg-indigo-500/20 text-indigo-400 px-2 py-0.5 rounded">
                        Top 5 Recent
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-1">
                        <thead class="text-[9px] uppercase text-slate-500 tracking-tighter">
                            <tr>
                                <th class="pb-2 pl-1">Project</th>
                                <th class="pb-2">Status</th>
                                <th class="pb-2">Progress</th>
                                <th class="pb-2">User</th>
                            </tr>
                        </thead>
                        <tbody class="text-[11px]">
                            @foreach ($projects->take(5) as $p)
                                <tr class="group hover:bg-slate-700/30 transition-all">
                                    <td class="py-2 pl-1">
                                        <p class="font-semibold text-slate-200 truncate max-w-[120px]">
                                            {{ $p->name }}
                                        </p>
                                        <p class="text-[9px] text-slate-500 italic">
                                            {{ $p->last_update ?? '-' }}
                                        </p>
                                    </td>
                                    <td>
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-bold
                                            @if ($p->last_status == 'completed') bg-emerald-500/10 text-emerald-500 border border-emerald-500/20
                                            @elseif($p->last_status == 'on-hold') bg-amber-500/10 text-amber-500 border border-amber-500/20
                                            @else bg-blue-500/10 text-blue-400 border border-blue-500/20 @endif">
                                            {{ strtoupper($p->last_status ?? $p->status) }}
                                        </span>
                                    </td>
                                    <td class="py-2">
                                        <div class="flex flex-col gap-1.5">
                                            <span class="text-[10px] font-bold text-slate-300">
                                                {{ $p->last_progress ?? $p->progress }}%
                                            </span>
                                            <div class="w-20 bg-slate-700 rounded-full h-1 overflow-hidden">
                                                <div class="bg-indigo-500 h-1 rounded-full"
                                                     style="width: {{ $p->last_progress ?? $p->progress }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-slate-500 font-medium">
                                        {{ Str::before($p->pm_name ?? '-', '@') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection