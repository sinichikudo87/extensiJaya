@extends('layouts.admin')

@section('title', 'Project Update Management')

@section('content')
    <div x-data="{
        searchQuery: '',
        expandedProject: null
    }" x-init="$store.nav.setTitle('Project Update Management')">
        <div class="w-full mx-auto space-y-4 md:space-y-6 overflow-x-hidden">

            {{-- HEADER --}}
            <div class="bg-slate-900/50 backdrop-blur-md p-2 md:p-3 rounded-2xl border border-white/5 shadow-xl">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">

                    <div class="flex items-center gap-3 shrink-0">
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center border border-cyan-500/20">
                            <i class="fas fa-project-diagram text-cyan-400 text-xs"></i>
                        </div>

                        <div class="flex flex-col">
                            <h2 class="text-[11px] font-black text-white uppercase tracking-tight leading-none">
                                Finish Projects
                            </h2>
                            <p class="text-[8px] md:text-[10px] text-gray-500 uppercase tracking-[0.15em] md:tracking-[0.2em] font-black leading-relaxed">
                                Live Database • Monitoring Ongoing Tasks
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-3 w-full lg:w-auto">
                        {{-- LEGEND --}}
                        <div class="flex items-center gap-3 px-3 py-1.5 bg-black/20 rounded-xl border border-white/5 shrink-0">
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#b7103a]"></span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase">Dinas</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#3b82f6]"></span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase">Pemrakarsa</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#49f50a]"></span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase">Konsultan</span>
                            </div>
                        </div>

                        {{-- SEARCH --}}
                        <div class="relative w-full sm:w-64 lg:w-72">
                            <input type="text" x-model="searchQuery" placeholder="Search project..."
                                class="w-full bg-gray-950/50 border border-white/10 rounded-xl pl-9 pr-4 py-1.5
                                       text-[11px] text-white placeholder-gray-600
                                       focus:outline-none focus:ring-1 focus:ring-cyan-500/40
                                       focus:border-cyan-500/50 transition-all">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-600 text-[10px]"></i>
                        </div>
                    </div>

                </div>
            </div>

            {{-- DESKTOP VIEW (TABLE) --}}
            <div class="hidden md:block bg-gray-900 rounded-2xl border border-gray-800 shadow-2xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-800/50 text-[10px] uppercase tracking-[0.2em] text-gray-400">
                            <th class="px-6 py-4 font-black">Project Info</th>
                            <th class="px-6 py-4 font-black text-center">Timeline</th>
                            <th class="px-6 py-4 font-black text-right">Progress Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-300 divide-y divide-gray-800/50">
                        @forelse($projects as $project)
                            @php
                                $barColor = $project->color ?? '#3b82f6';
                                $detailId = 'project_history_' . $project->id;
                                $iconId = 'icon_' . $project->id;
                                $progressValue = $project->progress_val ?? 100;
                            @endphp

                            <tr onclick="toggleDetail('{{ $detailId }}', '{{ $iconId }}')"
                                class="hover:bg-gray-800/30 transition-colors group cursor-pointer">
                                
                                {{-- PROJECT --}}
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white group-hover:text-blue-400 transition-colors flex items-center">
                                        <i class="fas fa-chevron-right mr-3 text-[10px] transition-transform duration-300" id="{{ $iconId }}"></i>
                                        <div>
                                            <span>{{ $project->name }}</span>
                                            <div class="text-[10px] text-blue-500 font-mono tracking-tighter block mt-0.5 uppercase">
                                                {{ $project->code }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- DATE --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                        {{ $project->start_date ? date('d M Y', strtotime($project->start_date)) : '-' }}
                                    </div>
                                    <div class="text-[9px] text-gray-600 font-black italic uppercase">
                                        Finished: {{ $project->end_date ? date('d M Y', strtotime($project->end_date)) : '-' }}
                                    </div>
                                </td>

                                {{-- PROGRESS --}}
                                <td class="px-6 py-4">
                                    <div class="w-40 ml-auto">
                                        <div class="flex justify-between items-end mb-1.5">
                                            <span class="text-[9px] font-black uppercase tracking-widest text-green-500">
                                                Completed
                                            </span>
                                            <span class="text-[10px] font-bold text-white">
                                                {{ $progressValue }}%
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-800 h-1.5 rounded-full overflow-hidden border border-gray-700/30">
                                            <div class="h-full transition-all duration-700"
                                                style="width: {{ $progressValue }}%; background-color: {{ $barColor }};">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            {{-- DETAIL DESKTOP --}}
                            <tr id="{{ $detailId }}" class="hidden bg-gray-950">
                                <td colspan="3" class="px-10 py-8">
                                    <div class="grid grid-cols-3 gap-8 text-xs">
                                        {{-- SNAPSHOT --}}
                                        <div class="col-span-1">
                                            <p class="text-blue-400 font-black uppercase tracking-widest mb-3 text-[9px]">Snapshot</p>
                                            <div class="w-full aspect-video rounded-xl overflow-hidden border border-gray-800 bg-gray-900 flex items-center justify-center">
                                                @if ($project->thumbnail)
                                                    <img src="{{ asset($project->thumbnail) }}" class="w-full h-full object-cover">
                                                @else
                                                    <i class="fas fa-image text-3xl text-gray-800"></i>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- HISTORY --}}
                                        <div class="col-span-2">
                                            <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
                                                <div class="bg-gray-800/50 px-4 py-2 border-b border-gray-800 flex justify-between items-center">
                                                    <span class="text-blue-400 font-black uppercase tracking-widest text-[9px]">Timeline & Notes</span>
                                                    <i class="fas fa-history text-[10px] text-gray-600"></i>
                                                </div>
                                                <div class="milestone-content" data-loaded="false">
                                                    <p class="p-4 text-gray-600 italic">Click to expand details...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">No finished projects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- PAGINATION DESKTOP --}}
                <div class="px-6 py-4 border-t border-gray-800 bg-gray-950 flex items-center justify-end">
                    {{ $projects->links('pagination::tailwind') }}
                </div>
            </div>

            {{-- MOBILE VIEW (CARDS) --}}
            <div class="md:hidden space-y-4">
                @forelse($projects as $project)
                    @php
                        $barColor = $project->color ?? '#3b82f6';
                        $detailMobileId = 'project_history_mobile_' . $project->id;
                        $iconMobileId = 'icon_mobile_' . $project->id;
                        $progressValue = $project->progress_val ?? 100;
                    @endphp

                    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 space-y-3 shadow-md">
                        {{-- Klik Area --}}
                        <div onclick="toggleDetailMobile('{{ $project->id }}', '{{ $detailMobileId }}', '{{ $iconMobileId }}')" 
                             class="flex items-start justify-between gap-2 cursor-pointer group">
                            <div>
                                <h3 class="font-bold text-white group-hover:text-blue-400 transition-colors text-xs flex items-center gap-2">
                                    <i class="fas fa-chevron-right text-[9px] transition-transform duration-300" id="{{ $iconMobileId }}"></i>
                                    {{ $project->name }}
                                </h3>
                                <span class="text-[9px] text-blue-500 font-mono uppercase tracking-tighter block mt-0.5">
                                    {{ $project->code }}
                                </span>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-[9px] font-bold text-gray-400 uppercase">
                                    {{ $project->start_date ? date('d M Y', strtotime($project->start_date)) : '-' }}
                                </div>
                                <div class="text-[8px] text-gray-600 font-black italic uppercase">
                                    End: {{ $project->end_date ? date('d M Y', strtotime($project->end_date)) : '-' }}
                                </div>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="space-y-1">
                            <div class="flex justify-between items-center text-[9px]">
                                <span class="font-black uppercase tracking-widest text-green-500">Completed</span>
                                <span class="font-bold text-white">{{ $progressValue }}%</span>
                            </div>
                            <div class="w-full bg-gray-800 h-1.5 rounded-full overflow-hidden border border-gray-700/30">
                                <div class="h-full transition-all duration-700" style="width: {{ $progressValue }}%; background-color: {{ $barColor }};"></div>
                            </div>
                        </div>

                        {{-- DETAIL PANEL MOBILE --}}
                        <div id="{{ $detailMobileId }}" class="hidden pt-3 border-t border-gray-800 space-y-3">
                            <div>
                                <p class="text-blue-400 font-black uppercase tracking-widest text-[8px] mb-1.5">Snapshot</p>
                                <div class="w-full aspect-video rounded-xl overflow-hidden border border-gray-800 bg-gray-950 flex items-center justify-center">
                                    @if ($project->thumbnail)
                                        <img src="{{ asset($project->thumbnail) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-image text-xl text-gray-800"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-2">
                                <p class="text-blue-400 font-black uppercase tracking-widest text-[8px]">Timeline & Notes</p>
                                <div class="milestone-content bg-gray-950 rounded-xl p-2 border border-gray-800/50" data-loaded="false">
                                    <p class="text-gray-600 text-[10px] italic">Loading details...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 text-center text-gray-500 italic text-xs">
                        No finished projects found.
                    </div>
                @endforelse

                {{-- PAGINATION MOBILE --}}
                <div class="pt-2 flex justify-center">
                    {{ $projects->links('pagination::tailwind') }}
                </div>
            </div>

        </div>
    </div>

    {{-- INTERACTION SCRIPT --}}
    <script>
        async function toggleDetail(detailId, iconId) {
            const row = document.getElementById(detailId);
            const icon = document.getElementById(iconId);
            const projectId = detailId.replace('project_history_', '');
            
            if (!row || !icon) return;

            const contentDiv = row.querySelector('.milestone-content');
            const isOpening = row.classList.contains('hidden');

            if (isOpening) {
                row.classList.remove('hidden');
                icon.style.transform = 'rotate(90deg)';

                if (contentDiv && contentDiv.dataset.loaded === "false") {
                    contentDiv.innerHTML =
                        `<div class="p-6 text-center">
                            <i class="fas fa-circle-notch fa-spin text-blue-500"></i>
                        </div>`;

                    try {
                        const response = await fetch(`/project-entry/getHistory/${projectId}`);
                        const result = await response.json();
                        const dataTimeline = result.data ? result.data : result;

                        if (dataTimeline && dataTimeline.length > 0) {
                            let html = `<table class="w-full text-left border-collapse"><tbody class="divide-y divide-gray-800/50">`;
                            dataTimeline.forEach(item => {
                                let progressTxt = item.progress_val !== undefined ? item.progress_val : (item.progress || 0);
                                html += `
                                <tr class="hover:bg-gray-800/20 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="text-[11px] text-gray-300">${item.notes || '-'}</div>
                                        <div class="text-[8px] text-gray-600 font-mono mt-1 uppercase">
                                            ${item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-'}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-[9px] font-black text-blue-400 px-2 py-0.5 bg-blue-400/10 rounded uppercase">
                                            ${item.status_update || 'UPDATE'}
                                        </span>
                                        <div class="text-white font-black mt-1">${progressTxt}%</div>
                                    </td>
                                </tr>`;
                            });
                            html += `</tbody></table>`;
                            contentDiv.innerHTML = html;
                        } else {
                            contentDiv.innerHTML = `<p class="p-6 text-center text-gray-600 text-[10px]">No records found.</p>`;
                        }
                        contentDiv.dataset.loaded = "true";
                    } catch (e) {
                        contentDiv.innerHTML = `<p class="p-6 text-center text-red-500 text-[10px]">Error loading data.</p>`;
                    }
                }
            } else {
                row.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        async function toggleDetailMobile(projectId, detailId, iconId) {
            const row = document.getElementById(detailId);
            const icon = document.getElementById(iconId);

            if (!row || !icon) return;

            const contentDiv = row.querySelector('.milestone-content');
            const isOpening = row.classList.contains('hidden');

            if (isOpening) {
                row.classList.remove('hidden');
                icon.style.transform = 'rotate(90deg)';

                if (contentDiv && contentDiv.dataset.loaded === "false") {
                    contentDiv.innerHTML =
                        `<div class="py-6 text-center">
                            <i class="fas fa-circle-notch fa-spin text-blue-500"></i>
                        </div>`;

                    try {
                        const response = await fetch(`/project-entry/getHistory/${projectId}`);
                        const result = await response.json();
                        const dataTimeline = result.data ? result.data : result;

                        if (dataTimeline && dataTimeline.length > 0) {
                            let html = `<div class="space-y-3">`;
                            dataTimeline.forEach(item => {
                                let progressTxt = item.progress_val !== undefined ? item.progress_val : (item.progress || 0);
                                html += `
                                <div class="bg-gray-950 border border-gray-800 rounded-xl p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-white text-[11px] break-words leading-relaxed">${item.notes || '-'}</div>
                                            <div class="text-[9px] text-gray-600 uppercase mt-2">
                                                ${item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-'}
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="text-[9px] font-black text-blue-400 px-2 py-1 rounded bg-blue-500/10 uppercase">
                                                ${item.status_update || 'UPDATE'}
                                            </span>
                                            <div class="text-white font-black mt-2">${progressTxt}%</div>
                                        </div>
                                    </div>
                                </div>`;
                            });
                            html += `</div>`;
                            contentDiv.innerHTML = html;
                        } else {
                            contentDiv.innerHTML = `<p class="text-center text-gray-600 text-[10px] py-4">No records found.</p>`;
                        }
                        contentDiv.dataset.loaded = "true";
                    } catch (e) {
                        contentDiv.innerHTML = `<p class="text-center text-red-500 text-[10px] py-4">Error loading data.</p>`;
                    }
                }
            } else {
                row.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
@endsection