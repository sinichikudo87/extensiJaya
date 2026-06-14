@extends('layouts.admin')

@section('title', 'Project Entry Management')

@section('content')
    <div x-data x-init="$store.nav.setTitle('Project Entry Management')">
        <div class="w-full mx-auto space-y-6">

            {{-- ================= HEADER (COMPACT) ================= --}}
            <div class="bg-slate-900/50 backdrop-blur-md p-2 md:p-3 rounded-2xl border border-white/5 shadow-xl">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">

                    <div class="flex items-center gap-3 shrink-0">
                        <div
                            class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center border border-cyan-500/20">
                            <i class="fas fa-project-diagram text-cyan-400 text-xs"></i>
                        </div>

                        <div class="flex flex-col">
                            <h2 class="text-[11px] font-black text-white uppercase tracking-tight leading-none">
                                Project Entry Management
                            </h2>

                            <p
                                class="text-[8px] md:text-[10px] text-gray-500 uppercase tracking-[0.15em] md:tracking-[0.2em] font-black leading-relaxed">
                                Live Database • Total: {{ $projects->total() }} Projects • {{ date('F Y') }} 
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-3 w-full lg:w-auto">

                        <button onclick="openProjectModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-5 py-2 rounded-lg text-[10px] md:text-xs font-black tracking-widest shadow-md active:scale-95 flex items-center gap-2">
                            <i class="fas fa-plus text-[9px]"></i> NEW PROJECT
                        </button>

                    </div>

                </div>
            </div>

            {{-- ================= DESKTOP TABLE ================= --}}
            <div class="hidden md:block bg-gray-900 rounded-2xl border border-gray-800 shadow-2xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-800/50 text-[10px] uppercase tracking-[0.2em] text-gray-400">
                            <th class="px-6 py-4 font-black">Project Info</th>
                            <th class="px-6 py-4 font-black text-center">Timeline</th>
                            <th class="px-6 py-4 font-black">Budget</th>
                            <th class="px-6 py-4 font-black text-center">Progress</th>
                            <th class="px-6 py-4 font-black text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="text-sm text-gray-300 divide-y divide-gray-800/50">
                        @forelse($projects as $project)
                            <tr class="hover:bg-gray-800/30 transition-colors">

                                <td class="px-6 py-4">
                                    <div class="text-white font-bold">{{ $project->name }}</div>
                                    <div class="text-[10px] text-blue-500 font-mono">{{ $project->code }}</div>
                                </td>

                                <td class="px-6 py-4 text-center text-[10px] text-gray-400">
                                    {{ $project->start_date ? date('d M Y', strtotime($project->start_date)) : 'Pending' }}
                                    →
                                    {{ $project->end_date ? date('d M Y', strtotime($project->end_date)) : '-' }}
                                </td>

                                <td class="px-6 py-4 text-emerald-400 font-bold font-mono">
                                    IDR {{ number_format($project->budget, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="w-40 mx-auto">
                                        <div class="h-1.5 bg-gray-800 rounded overflow-hidden">
                                            <div class="h-full"
                                                style="width: {{ $project->progress ?? 0 }}%; background-color: {{ $project->color ?? '#3b82f6' }}">
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <button class="btn-delete text-red-500 hover:text-red-400 p-2"
                                        data-id="{{ encrypt($project->id) }}">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                    No projects found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ================= MOBILE CARDS ================= --}}
            <div class="md:hidden space-y-3">

                @forelse($projects as $project)
                    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 shadow-lg">

                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <div class="text-white font-bold text-sm">
                                    {{ $project->name }}
                                </div>
                                <div class="text-[10px] text-blue-500 font-mono">
                                    {{ $project->code }}
                                </div>
                            </div>

                            <span
                                class="text-[10px] px-2 py-1 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                New
                            </span>
                        </div>

                        <div class="text-[10px] text-gray-400 mb-1">
                            {{ $project->start_date ? date('d M Y', strtotime($project->start_date)) : 'Pending' }}
                            →
                            {{ $project->end_date ? date('d M Y', strtotime($project->end_date)) : '-' }}
                        </div>

                        <div class="text-emerald-400 font-bold text-sm mb-2">
                            IDR {{ number_format($project->budget, 0, ',', '.') }}
                        </div>

                        <div class="mb-3">
                            <div class="h-1.5 bg-gray-800 rounded overflow-hidden">
                                <div class="h-full"
                                    style="width: {{ $project->progress ?? 0 }}%; background-color: {{ $project->color ?? '#3b82f6' }}">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button class="btn-delete text-red-500 p-2" data-id="{{ encrypt($project->id) }}">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>

                    </div>
                @empty
                    <div class="text-center text-gray-500 italic py-10">
                        No projects found.
                    </div>
                @endforelse

            </div>

            {{-- ================= PAGINATION (DESKTOP ONLY) ================= --}}
            <div
                class="hidden md:flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-900/80 border border-gray-800 p-4 rounded-xl">

                <div class="text-[10px] text-gray-500 uppercase font-black tracking-widest">
                    Showing {{ $projects->firstItem() ?? 0 }} - {{ $projects->lastItem() ?? 0 }}
                    of {{ $projects->total() }} Projects
                </div>

                <div>
                    {{ $projects->links() }}
                </div>

            </div>

            {{-- MOBILE HINT --}}
            <div class="md:hidden text-center text-[10px] text-gray-600 py-3">
                Scroll down for more projects
            </div>

        </div>
    </div>

    @include('admin.project-entry.modal')
    @include('components.modal-chat')
@endsection
