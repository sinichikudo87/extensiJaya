@extends('layouts.admin')

@section('title', 'Project Update Management')

@section('content')
    <div x-data="{ searchQuery: '', expandedProject: null }" x-init="$store.nav.setTitle('Project Update Management')">
        <div class="w-full mx-auto space-y-6">
            {{-- STATS / HEADER --}}
            <div class="bg-slate-900/50 backdrop-blur-md p-2 md:p-3 rounded-2xl border border-white/5 shadow-xl">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">

                    <div class="flex items-center gap-3 shrink-0">
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center border border-cyan-500/20">
                            <i class="fas fa-project-diagram text-cyan-400 text-xs"></i>
                        </div>

                        <div class="flex flex-col">
                            <h2 class="text-[11px] font-black text-white uppercase tracking-tight leading-none">
                                Active Projects
                            </h2>
                            <p class="text-[8px] text-slate-500 uppercase tracking-widest font-bold mt-0.5">
                                Live Monitoring
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
                            <input
                                type="text"
                                x-model="searchQuery"
                                placeholder="Search project..."
                                class="w-full bg-gray-950/50 border border-white/10 rounded-xl pl-9 pr-4 py-1.5
                                       text-[11px] text-white placeholder-gray-600
                                       focus:outline-none focus:ring-1 focus:ring-cyan-500/40
                                       focus:border-cyan-500/50 transition-all">

                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-600 text-[10px]"></i>
                        </div>
                    </div>

                </div>
            </div>

            {{-- TABLE CONTAINER (DESKTOP) --}}
            <div class="hidden md:block">
                <div class="bg-gray-900 rounded-2xl border border-gray-800 shadow-2xl overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-800/50 text-[10px] uppercase tracking-[0.2em] text-gray-400">
                                <th class="px-6 py-4 font-black">Project Info</th>
                                <th class="px-6 py-4 font-black text-center">Timeline</th>
                                <th class="px-6 py-4 font-black text-right">Progress Status</th>
                                <th class="px-6 py-4 font-black text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="text-sm text-gray-300 divide-y divide-gray-800/50">
                            @forelse($projects as $project)
                                @if ($project->progress < 100)
                                    @php
                                        $barColor = $project->color ?? '#3b82f6';
                                    @endphp
                                    <tr class="hover:bg-gray-800/30 transition-colors group cursor-pointer"
                                        x-show="'{{ strtolower($project->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($project->code) }}'.includes(searchQuery.toLowerCase())">
                                        <td class="px-6 py-4" @click="expandedProject = (expandedProject === '{{ $project->id }}' ? null : '{{ $project->id }}'); if(expandedProject === '{{ $project->id }}') loadHistory('{{ $project->id }}')">
                                            <div class="font-bold text-white group-hover:text-blue-400 transition-colors flex items-center">                                                
                                                <i class="fas fa-chevron-right mr-3 text-[10px] transition-transform duration-300"
                                                   :class="expandedProject === '{{ $project->id }}' ? 'rotate-90' : ''"></i>
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
                                                Target: {{ $project->end_date ? date('d M Y', strtotime($project->end_date)) : '-' }}
                                            </div>
                                        </td>

                                        {{-- PROGRESS --}}
                                        <td class="px-6 py-4">
                                            <div class="w-40 ml-auto cursor-pointer group progress-wrapper">
                                                <div class="flex justify-between items-end mb-1.5">
                                                    <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">Working</span>
                                                    <span class="text-[10px] font-bold text-white">{{ $project->progress }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-800 h-1.5 rounded-full overflow-hidden border border-gray-700/30">
                                                    <div class="h-full transition-all duration-700 progress-bar"
                                                         style="width: {{ $project->progress }}%; background-color: {{ $barColor }};">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- ACTION --}}
                                        <td class="px-6 py-4 text-right space-x-1" @click.stop>
                                            <button type="button" class="btn-edit p-2 text-gray-600 hover:text-blue-400 transition-colors" data-id="{{ $project->id }}" data-progress="{{ $project->progress }}">
                                                <i class="fas fa-edit text-xs"></i>
                                            </button>
                                            <button type="button" class="btn-delete p-2 text-gray-600 hover:text-red-500 transition-colors" data-id="{{ encrypt($project->id) }}">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                            <button type="button" class="btn-chat p-2 text-green-600 hover:text-white transition-colors" data-id="{{ $project->id }}" data-code="{{ $project->code }}" data-name="{{ $project->name }}">
                                                <i class="fas fa-comments text-xs"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-950 shadow-inner"
                                        x-show="(expandedProject === '{{ $project->id }}') && ('{{ strtolower($project->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($project->code) }}'.includes(searchQuery.toLowerCase()))"
                                        x-cloak>
                                        <td colspan="4" class="px-10 py-8">
                                            <div class="grid grid-cols-3 gap-8 text-xs">
                                                <div class="col-span-1">
                                                    <p class="text-blue-400 font-black uppercase tracking-widest mb-3 text-[9px]">Snapshot</p>
                                                    <div class="w-full aspect-video rounded-xl overflow-hidden border border-gray-800 bg-gray-950 shadow-2xl flex items-center justify-center">
                                                        @if ($project->thumbnail)
                                                            <img src="{{ asset($project->thumbnail) }}" class="w-full h-full object-cover">
                                                        @else
                                                            <i class="fas fa-image text-3xl text-gray-800"></i>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-span-2">
                                                    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
                                                        <div class="bg-gray-800/50 px-4 py-2 border-b border-gray-800 flex justify-between items-center">
                                                            <span class="text-blue-400 font-black uppercase tracking-widest text-[9px]">Timeline & Notes</span>
                                                            <i class="fas fa-history text-[10px] text-gray-600"></i>
                                                        </div>

                                                        <div id="content_desktop_{{ $project->id }}" class="milestone-content" data-loaded="false">
                                                            <p class="p-4 text-gray-600 italic">Click to expand details...</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">No active projects at the moment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- PAGINATION --}}
                    <div class="px-6 py-4 border-t border-gray-800 bg-gray-950 flex items-center justify-end">
                        {{ $projects->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>

            {{-- MOBILE VIEW --}}
            <div class="block md:hidden space-y-4">
                @forelse($projects as $project)
                    @if ($project->progress < 100)
                        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 shadow-lg space-y-3"
                            x-show="'{{ strtolower($project->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($project->code) }}'.includes(searchQuery.toLowerCase())">

                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-[10px] font-bold text-white">{{ $project->name }}</h3>
                                    <p class="text-[10px] text-blue-400 font-mono uppercase">{{ $project->code }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <button class="btn-edit text-gray-400" data-id="{{ $project->id }}" data-progress="{{ $project->progress }}">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button class="btn-chat text-green-400" data-id="{{ $project->id }}" data-code="{{ $project->code }}" data-name="{{ $project->name }}">
                                        <i class="fas fa-comments text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="text-[10px] text-gray-400">
                                <div>Start: <span class="text-white">{{ $project->start_date ? date('d M Y', strtotime($project->start_date)) : '-' }}</span></div>
                                <div>Target: <span class="text-white">{{ $project->end_date ? date('d M Y', strtotime($project->end_date)) : '-' }}</span></div>
                            </div>

                            <div>
                                <div class="flex justify-between text-[10px] mb-1">
                                    <span class="text-gray-500 uppercase">Progress</span>
                                    <span class="text-white font-bold">{{ $project->progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden">
                                    <div class="h-full" style="width: {{ $project->progress }}%; background-color: {{ $project->color ?? '#3b82f6' }}"></div>
                                </div>
                            </div>

                            {{-- PERBAIKAN: Samakan value state expandedProject dengan versi desktop agar sinkron --}}
                            <button @click="expandedProject = (expandedProject === '{{ $project->id }}' ? null : '{{ $project->id }}'); if(expandedProject === '{{ $project->id }}') loadHistory('{{ $project->id }}')" 
                                    class="text-[10px] text-blue-400 font-bold flex items-center gap-2 py-1">
                                <i class="fas fa-chevron-right transition-transform duration-300" :class="expandedProject === '{{ $project->id }}' ? 'rotate-90' : ''"></i>
                                View Details
                            </button>

                            {{-- PERBAIKAN: Kondisi x-show disesuaikan dan ID pembungkus dipastikan siap menerima response HTML --}}
                            <div class="pt-3 border-t border-gray-800" x-show="expandedProject === '{{ $project->id }}'" x-cloak>
                                <div id="content_mobile_{{ $project->id }}" class="milestone-content text-[11px] text-gray-400 space-y-2">
                                    <p class="text-gray-600 italic">Loading details...</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center text-gray-500 text-sm py-6">No active projects.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection