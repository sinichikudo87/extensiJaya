@extends('layouts.admin')

@section('content')
    <div x-data="{ openRow: null }" x-init="$store.nav.setTitle('Financial Management')" class="p-2 md:p-4 antialiased overflow-x-hidden">

        <style>
            * {
                min-width: 0;
            }

            html,
            body {
                overflow-x: hidden;
            }
        </style>

        <div
            class="w-full max-w-full
                mx-auto
                bg-gray-900
                rounded-2xl md:rounded-3xl
                shadow-2xl
                overflow-hidden
                border border-gray-800">

            {{-- HEADER --}}
            <div
                class="bg-gradient-to-r
                   from-gray-800/50 to-transparent
                   px-4 md:px-8 py-5 md:py-6
                   border-b border-gray-800
                   flex flex-col lg:flex-row
                   lg:items-center lg:justify-between
                   gap-4">

                {{-- LEFT --}}
                <div class="flex items-center gap-4 min-w-0">

                    <div class="p-3 bg-emerald-500/10 rounded-xl shrink-0">
                        <i class="fas fa-chart-line text-emerald-400 text-lg md:text-xl"></i>
                    </div>

                    <div class="min-w-0">

                        <h2
                            class="text-base md:text-xl
                               font-bold text-white
                               tracking-tight truncate">

                            Financial Report

                        </h2>

                        <p
                            class="text-[10px] md:text-xs
                               text-gray-400 mt-1
                               truncate">

                            Monitor project budgets and real-time expenses

                        </p>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="flex gap-2 w-full lg:w-auto">
                    {{-- tombol export --}}
                </div>

            </div>

            {{-- DESKTOP TABLE --}}
            <div class="hidden lg:block w-full overflow-x-auto">

                <table class="w-full min-w-full table-fixed border-separate border-spacing-0">

                    <thead>

                        <tr
                            class="bg-gray-800/30
                               text-gray-400
                               text-[10px]
                               uppercase tracking-[0.2em]">

                            <th class="px-4 py-4 text-left font-semibold border-b border-gray-800 w-[70px]">
                                No
                            </th>

                            <th class="px-4 py-4 text-left font-semibold border-b border-gray-800">
                                Project Information
                            </th>

                            <th class="px-4 py-4 text-right font-semibold border-b border-gray-800 w-[180px]">
                                Pemasukan
                            </th>

                            <th class="px-4 py-4 text-center font-semibold border-b border-gray-800 w-[140px]">
                                Progress
                            </th>

                            <th class="px-4 py-4 text-right font-semibold border-b border-gray-800 w-[180px]">
                                Pengeluaran
                            </th>

                            <th class="px-4 py-4 text-center font-semibold border-b border-gray-800 w-[140px]">
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-800">

                        @php $no = $paginatedData->firstItem(); @endphp

                        @forelse ($paginatedData as $projectId => $project)
                            @php
                                $emp = $project;
                                $details = $projectDetails[$emp->project_id] ?? [];
                            @endphp

                            {{-- MAIN ROW --}}
                            <tr class="hover:bg-emerald-500/5
                                   transition-colors duration-200
                                   cursor-pointer group"
                                @click="openRow = (openRow === {{ $emp->project_id }} ? null : {{ $emp->project_id }})"
                                :class="openRow === {{ $emp->project_id }} ? 'bg-emerald-500/5' : ''">

                                {{-- NO --}}
                                <td class="px-4 py-5 text-gray-500 text-xs font-medium">

                                    {{ str_pad($no++, 2, '0', STR_PAD_LEFT) }}

                                </td>

                                {{-- PROJECT --}}
                                <td class="px-4 py-5 min-w-0">

                                    <div class="flex items-center gap-3 min-w-0">

                                        <div
                                            class="w-8 h-8 rounded-lg
                                               bg-gray-800
                                               flex items-center justify-center
                                               group-hover:bg-emerald-500/20
                                               transition-colors
                                               shrink-0">

                                            <i class="fas fa-folder-open text-xs transition-transform duration-300"
                                                :class="openRow === {{ $emp->project_id }} ?
                                                    'rotate-90 text-emerald-400' :
                                                    'text-gray-500'">
                                            </i>

                                        </div>

                                        <div class="flex flex-col min-w-0">

                                            <span
                                                class="text-emerald-400
                                                   text-[10px]
                                                   font-bold
                                                   tracking-tighter
                                                   uppercase
                                                   leading-none mb-1
                                                   truncate">

                                                {{ $emp->code }}

                                            </span>

                                            <span
                                                class="font-semibold
                                                   text-gray-100
                                                   text-sm
                                                   tracking-wide
                                                   truncate">

                                                {{ $emp->name }}

                                            </span>

                                        </div>

                                    </div>

                                </td>

                                {{-- PEMASUKAN --}}
                                <td class="px-4 py-5 text-right">

                                    <div class="flex items-center justify-end gap-3">

                                        <span
                                            class="text-sky-400
                                               font-mono
                                               font-semibold
                                               text-[11px]
                                               tracking-tighter
                                               truncate">

                                            Rp {{ number_format($emp->budget, 0, ',', '.') }}

                                        </span>

                                        <button
                                            onclick="event.stopPropagation(); editPemasukan({{ $emp->project_id }}, {{ $emp->budget }})"
                                            class="p-1.5 text-gray-500
                                               hover:text-white
                                               hover:bg-blue-600
                                               hover:shadow-lg
                                               hover:shadow-blue-500/50
                                               hover:-translate-y-0.5
                                               rounded-md
                                               transition-all duration-300
                                               ease-out active:scale-95"
                                            title="Edit Pemasukan">

                                            <i class="fas fa-pencil-alt text-[10px]"></i>

                                        </button>

                                    </div>

                                </td>

                                {{-- PROGRESS --}}
                                <td class="px-4 py-5">

                                    <div class="flex flex-col items-center gap-1.5">

                                        <div class="w-24 h-1.5 bg-gray-700 rounded-full overflow-hidden">

                                            <div class="h-full bg-emerald-500 rounded-full"
                                                style="width: {{ $emp->project_progress }}%">
                                            </div>

                                        </div>

                                        <span class="text-emerald-400 font-bold text-[10px]">

                                            {{ $emp->project_progress }}%

                                        </span>

                                    </div>

                                </td>

                                {{-- PENGELUARAN --}}
                                <td class="px-4 py-5 text-right">

                                    <span
                                        class="text-amber-400
                                           font-mono
                                           font-bold
                                           text-xs
                                           truncate">

                                        Rp {{ number_format($emp->grand_total_additional_cost, 0, ',', '.') }}

                                    </span>

                                </td>

                                {{-- STATUS --}}
                                <td class="px-4 py-5 text-center">

                                    <span
                                        class="px-3 py-1 rounded-full
                                           bg-gray-800
                                           border border-gray-700
                                           text-gray-300
                                           text-[10px]
                                           font-medium
                                           uppercase tracking-wider
                                           whitespace-nowrap">

                                        {{ $emp->status_update }}

                                    </span>

                                </td>

                            </tr>

                            {{-- DETAIL --}}
                            <tr x-show="openRow === {{ $emp->project_id }}"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0" class="bg-gray-900/40">

                                <td colspan="7" class="px-6 py-6">

                                    <div
                                        class="bg-gray-800/40
                                           border border-gray-700/50
                                           rounded-2xl
                                           p-5 shadow-inner
                                           overflow-hidden">

                                        <div class="flex items-center justify-between mb-6">

                                            <h3
                                                class="flex items-center gap-2
                                                   text-emerald-400
                                                   font-bold
                                                   text-xs
                                                   uppercase
                                                   tracking-widest">

                                                <span
                                                    class="w-2 h-2
                                                       bg-emerald-500
                                                       rounded-full
                                                       animate-pulse">
                                                </span>

                                                Progress History

                                            </h3>

                                        </div>

                                        <div class="overflow-x-auto rounded-xl border border-gray-700">

                                            <table class="w-full min-w-[700px] text-xs text-left">

                                                <thead
                                                    class="bg-gray-800
                                                       text-gray-400
                                                       uppercase
                                                       text-[10px]">

                                                    <tr>

                                                        <th class="px-4 py-3">
                                                            Timestamp
                                                        </th>

                                                        <th class="px-4 py-3">
                                                            Responsible
                                                        </th>

                                                        <th class="px-4 py-3 text-center">
                                                            Progress
                                                        </th>

                                                        <th class="px-4 py-3">
                                                            Cost
                                                        </th>

                                                        <th class="px-4 py-3">
                                                            Notes
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody class="divide-y divide-gray-700/50">

                                                    @forelse ($details as $d)
                                                        <tr class="hover:bg-gray-700/30 transition-colors">

                                                            <td class="px-4 py-3 text-gray-400 font-mono whitespace-nowrap">

                                                                {{ \Carbon\Carbon::parse($d->progress_created_at)->translatedFormat('d M Y, H:i') }}

                                                            </td>

                                                            <td class="px-4 py-3 font-medium text-gray-200">

                                                                {{ $d->employee_name }}

                                                            </td>

                                                            <td class="px-4 py-3 text-center">

                                                                <span class="text-emerald-400 font-bold">

                                                                    {{ $d->progress_val }}%

                                                                </span>

                                                            </td>

                                                            <td class="px-4 py-3 text-sky-400 font-mono whitespace-nowrap">

                                                                Rp {{ number_format($d->additional_cost, 0, ',', '.') }}

                                                            </td>

                                                            <td class="px-4 py-3 text-gray-400 italic">

                                                                "{{ $d->notes }}"

                                                            </td>

                                                        </tr>

                                                    @empty

                                                        <tr>

                                                            <td colspan="5"
                                                                class="text-center py-8 text-gray-500 italic">

                                                                No history records found for this project.

                                                            </td>

                                                        </tr>
                                                    @endforelse

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-24">

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="w-16 h-16 bg-gray-800
                                               rounded-full
                                               flex items-center justify-center
                                               mb-4">

                                            <i class="fas fa-folder-open text-gray-600 text-2xl"></i>

                                        </div>

                                        <p class="text-gray-400 font-medium">

                                            No data available in this period

                                        </p>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- MOBILE CARD --}}
            {{-- MOBILE CARD --}}
            <div class="lg:hidden space-y-4 p-3 w-full box-border overflow-hidden">

                @php $no = $paginatedData->firstItem(); @endphp

                @forelse ($paginatedData as $projectId => $project)
                    @php
                        $emp = $project;
                        $details = $projectDetails[$emp->project_id] ?? [];
                    @endphp

                    <div
                        class="rounded-3xl border border-slate-800/60 bg-gradient-to-b from-slate-900/90 via-slate-900/40 to-slate-950 shadow-2xl backdrop-blur-md overflow-hidden w-full box-border transition-all duration-300">

                        {{-- HEADER CARD (KLIK UNTUK DETAIL) --}}
                        <div class="p-4 active:bg-white/[0.02] cursor-pointer transition-colors w-full"
                            @click="openRow = (openRow === {{ $emp->project_id }} ? null : {{ $emp->project_id }})">

                            {{-- TOP SECTION --}}
                            <div class="flex items-start gap-3 w-full">

                                {{-- MODERN AVATAR / ICON --}}
                                <div
                                    class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500/20 to-teal-500/5 border border-emerald-500/20 flex items-center justify-center shrink-0 shadow-inner">
                                    <i class="fas fa-cubes text-emerald-400 text-xs animate-pulse"></i>
                                </div>

                                {{-- PROJECT TEXT --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 w-full">
                                        {{-- CODE --}}
                                        <div
                                            class="text-[9px] font-black tracking-[0.2em] uppercase text-emerald-400 font-mono truncate">
                                            {{ $emp->code }}
                                        </div>
                                        {{-- STATUS BADGE --}}
                                        <div
                                            class="px-2 py-0.5 rounded-md border border-slate-800 bg-slate-950/80 text-[7px] uppercase font-black tracking-widest text-slate-400 shrink-0 shadow-sm">
                                            {{ $emp->status_update ?? 'ACTIVE' }}
                                        </div>
                                    </div>

                                    {{-- TITLE --}}
                                    <h3 class="mt-1 text-xs font-bold leading-snug text-slate-100 truncate">
                                        {{ $emp->name }}
                                    </h3>
                                </div>

                            </div>

                            {{-- PROGRESS AREA --}}
                            <div class="mt-4 pt-3 border-t border-slate-800/40 w-full">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[8px] uppercase tracking-widest text-slate-500 font-bold">
                                        Completion Rate
                                    </span>
                                    <span
                                        class="text-[10px] font-mono font-black text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded">
                                        {{ $emp->project_progress }}%
                                    </span>
                                </div>
                                {{-- Progress Bar --}}
                                <div
                                    class="w-full h-1.5 rounded-full overflow-hidden bg-slate-950 border border-slate-900 relative">
                                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-cyan-400 shadow-[0_0_12px_rgba(16,185,129,0.3)] transition-all duration-700"
                                        style="width: {{ $emp->project_progress }}%">
                                    </div>
                                </div>
                            </div>

                            {{-- FINANCIAL MATRIX --}}
                            <div class="mt-4 grid grid-cols-2 gap-2 w-full">
                                {{-- BUDGET --}}
                                <div class="rounded-xl border border-slate-800/60 bg-slate-950/40 p-2.5 min-w-0">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-arrow-down text-[8px] text-sky-400 shrink-0"></i>
                                        <span
                                            class="text-[7px] uppercase tracking-wider text-slate-500 font-bold truncate">Pemasukan</span>
                                    </div>
                                    <div class="mt-1 text-[11px] font-black font-mono text-sky-400 truncate">
                                        Rp {{ number_format($emp->budget, 0, ',', '.') }}
                                    </div>
                                </div>

                                {{-- EXPENSES --}}
                                <div class="rounded-xl border border-slate-800/60 bg-slate-950/40 p-2.5 min-w-0">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-arrow-up text-[8px] text-amber-400 shrink-0"></i>
                                        <span
                                            class="text-[7px] uppercase tracking-wider text-slate-500 font-bold truncate">Pengeluaran</span>
                                    </div>
                                    <div class="mt-1 text-[11px] font-black font-mono text-amber-400 truncate">
                                        Rp {{ number_format($emp->grand_total_additional_cost, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            {{-- FLOATING ACTION BUTTON --}}
                            <div class="mt-3 w-full">
                                <button
                                    onclick="event.stopPropagation(); editPemasukan({{ $emp->project_id }}, {{ $emp->budget }})"
                                    class="w-full py-2 rounded-xl border border-slate-800 bg-slate-950 text-slate-300 hover:text-white text-[9px] font-black uppercase tracking-[0.2em] transition-all active:scale-[0.98] flex items-center justify-center gap-1.5 shadow-sm">
                                    <i class="fas fa-pen-to-square text-[8px] text-slate-500"></i> Adjust Budget
                                </button>
                            </div>

                        </div>

                        {{-- EXPANDABLE DETAIL SECTION --}}
                        <div x-show="openRow === {{ $emp->project_id }}"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="border-t border-slate-800/80 bg-slate-950/60 backdrop-blur-sm">

                            <div class="p-4 space-y-2.5">
                                <div class="text-[8px] uppercase tracking-[0.2em] text-slate-500 font-black mb-1">Update
                                    History</div>

                                @forelse ($details as $d)
                                    <div class="rounded-xl border border-slate-900 bg-slate-900/30 p-3 w-full box-border">
                                        <div class="flex items-center justify-between gap-2 w-full">
                                            <div class="text-[9px] font-mono font-medium text-slate-400 truncate">
                                                {{ \Carbon\Carbon::parse($d->progress_created_at)->translatedFormat('d M Y, H:i') }}
                                            </div>
                                            <div
                                                class="text-[9px] font-black font-mono text-emerald-400 bg-emerald-500/10 px-1.5 py-0.2 rounded shrink-0">
                                                +{{ $d->progress_val }}%
                                            </div>
                                        </div>

                                        <div class="mt-1.5 flex items-baseline justify-between gap-4 w-full">
                                            <span class="text-[10px] font-bold text-slate-200 truncate flex-1 min-w-0">
                                                {{ $d->employee_name }}
                                            </span>
                                            <span class="text-[10px] font-black font-mono text-cyan-400 shrink-0">
                                                Rp {{ number_format($d->additional_cost, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        @if (!empty($d->notes))
                                            <div
                                                class="mt-2 text-[9px] leading-relaxed text-slate-400 bg-slate-950/40 p-2 rounded-lg border border-slate-900/50 italic">
                                                "{{ $d->notes }}"
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-4 text-center text-[10px] italic text-slate-600 font-medium">
                                        No log activity recorded for this period.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>

                @empty
                    {{-- EMPTY STATE --}}
                    <div class="py-16 text-center w-full">
                        <div class="flex flex-col items-center justify-center">
                            <div
                                class="w-12 h-12 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center mb-3 shadow-xl">
                                <i class="fas fa-folder-open text-slate-600 text-lg"></i>
                            </div>
                            <p class="text-slate-500 font-semibold text-xs tracking-wide">
                                No data available in this period
                            </p>
                        </div>
                    </div>
                @endforelse

            </div>

            {{-- PAGINATION --}}
            <div
                class="px-4 md:px-8 py-5
                   bg-gray-800/30
                   border-t border-gray-800
                   flex flex-col sm:flex-row
                   justify-between items-center
                   gap-4">

                <p
                    class="text-[10px] md:text-[11px]
                       text-gray-500
                       font-medium
                       uppercase tracking-wider
                       text-center sm:text-left">

                    Showing
                    <span class="text-gray-300">{{ $paginatedData->firstItem() }}</span>
                    to
                    <span class="text-gray-300">{{ $paginatedData->lastItem() }}</span>
                    of
                    <span class="text-gray-300">{{ $paginatedData->total() }}</span>
                    records

                </p>

                <div class="pagination-modern overflow-x-auto">
                    {!! $paginatedData->appends(request()->all())->links() !!}
                </div>

            </div>

        </div>

    </div>

    <style>
        .pagination-modern .pagination {
            @apply flex gap-1 flex-wrap justify-center;
        }

        .pagination-modern .page-link {
            @apply bg-gray-800 border-none text-gray-400 px-3 py-1.5 rounded-lg text-xs hover:bg-emerald-500 hover:text-white transition-all;
        }

        .pagination-modern .page-item.active .page-link {
            @apply bg-emerald-500 text-white shadow-lg shadow-emerald-500/20;
        }
    </style>
@endsection
