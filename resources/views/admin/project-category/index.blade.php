@extends('layouts.admin')

@section('title', 'Project Entry Category Management')

@section('content')
    <div x-data x-init="$store.nav.setTitle('Project Entry Category Management')">
        <div class="w-full mx-auto space-y-6">

            {{-- HEADER --}}
            <div class="bg-slate-900/50 backdrop-blur-md p-2 md:p-3 rounded-2xl border border-white/5 shadow-xl">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">

                    <div class="flex items-center gap-3 shrink-0">
                        <div
                            class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center border border-cyan-500/20">
                            <i class="fas fa-project-diagram text-cyan-400 text-xs"></i>
                        </div>

                        <div class="flex flex-col">
                            <h2 class="text-[11px] font-black text-white uppercase tracking-tight leading-none">
                                Project Entry Category
                            </h2>

                            <p
                                class="text-[8px] md:text-[10px] text-gray-500 uppercase tracking-[0.15em] md:tracking-[0.2em] font-black leading-relaxed">
                                Live Database • Total: {{ $projects->total() }} Projects
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-3 w-full lg:w-auto">

                        <button onclick="openProjectModalCategory()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-xs font-black tracking-widest transition-all shadow-lg active:scale-95 flex items-center gap-2">
                            <i class="fas fa-plus text-[10px]"></i> NEW PROJECT
                        </button>

                    </div>

                </div>
            </div>

            {{-- TABLE + MOBILE CARD --}}
            <div class="bg-gray-900 rounded-2xl border border-gray-800 shadow-2xl overflow-hidden">

                {{-- DESKTOP TABLE --}}
                <div class="hidden md:block">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-800/50 text-[10px] uppercase tracking-[0.2em] text-gray-400">
                                <th class="px-6 py-4 font-black">Name</th>
                                <th class="px-6 py-4 font-black text-center">Status</th>
                                <th class="px-6 py-4 font-black text-center">Created</th>
                                <th class="px-6 py-4 font-black text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="text-sm text-gray-300 divide-y divide-gray-800/50">
                            @forelse($projects as $project)
                                <tr class="hover:bg-gray-800/30 transition">

                                    <td class="px-6 py-4 font-bold text-white">
                                        {{ $project->name }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-2 py-1 text-[10px] font-black rounded
                                        {{ $project->status == 'active'
                                            ? 'text-green-400 bg-green-400/10 border border-green-400/20'
                                            : 'text-red-400 bg-red-400/10 border border-red-400/20' }}">
                                            {{ strtoupper($project->status) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center text-gray-400 text-xs">
                                        {{ date('d M Y', strtotime($project->created_at)) }}
                                    </td>

                                    <td class="px-6 py-4 text-right space-x-1">
                                        <button class="btn-edit-category p-2 text-gray-600 hover:text-blue-400"
                                            data-id="{{ $project->id }}" data-name="{{ $project->name }}"
                                            data-status="{{ $project->status }}">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>

                                        <button class="p-2 text-gray-600 hover:text-red-500">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                                        No data found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE CARD --}}
                <div class="md:hidden divide-y divide-gray-800/50">

                    @forelse($projects as $project)
                        <div class="p-4 space-y-3">

                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0">
                                    <div class="text-white font-bold text-sm truncate">
                                        {{ $project->name }}
                                    </div>
                                    <div class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">
                                        {{ date('d M Y', strtotime($project->created_at)) }}
                                    </div>
                                </div>

                                <span
                                    class="px-2 py-1 text-[10px] font-black rounded shrink-0
                                {{ $project->status == 'active'
                                    ? 'text-green-400 bg-green-400/10 border border-green-400/20'
                                    : 'text-red-400 bg-red-400/10 border border-red-400/20' }}">
                                    {{ strtoupper($project->status) }}
                                </span>

                            </div>

                            <div class="flex justify-end gap-2 pt-2 border-t border-gray-800/40">

                                <button class="btn-edit-category p-2 text-gray-400 hover:text-blue-400"
                                    data-id="{{ $project->id }}" data-name="{{ $project->name }}"
                                    data-status="{{ $project->status }}">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>

                                <button class="p-2 text-gray-400 hover:text-red-500">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>

                            </div>

                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500 italic">
                            No data found.
                        </div>
                    @endforelse

                </div>

                {{-- PAGINATION --}}
                <div
                    class="px-6 py-5 bg-gray-900/80 border-t border-gray-800 flex flex-col md:flex-row items-center justify-between gap-4">

                    <div class="text-[10px] text-gray-500 uppercase font-black tracking-widest">
                        Showing <span class="text-blue-500">{{ $projects->firstItem() ?? 0 }}</span> -
                        <span class="text-blue-500">{{ $projects->lastItem() ?? 0 }}</span> of
                        <span class="text-white">{{ $projects->total() }}</span> Projects
                    </div>

                    <div class="flex items-center gap-1">
                        {{-- pagination tetap sama --}}
                        @if ($projects->onFirstPage())
                            <span
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-800/50 text-gray-700 border border-gray-800 cursor-not-allowed">
                                <i class="fas fa-chevron-left text-[10px]"></i>
                            </span>
                        @else
                            <a href="{{ $projects->previousPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-800 hover:bg-blue-600 text-gray-400 hover:text-white transition-all border border-gray-700">
                                <i class="fas fa-chevron-left text-[10px]"></i>
                            </a>
                        @endif

                        @foreach ($projects->getUrlRange(max(1, $projects->currentPage() - 2), min($projects->lastPage(), $projects->currentPage() + 2)) as $page => $url)
                            @if ($page == $projects->currentPage())
                                <span
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-600 text-white text-xs font-black">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-400 text-xs font-bold">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($projects->hasMorePages())
                            <a href="{{ $projects->nextPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-800 hover:bg-blue-600 text-gray-400 hover:text-white">
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        @else
                            <span
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-800/50 text-gray-700 border border-gray-800 cursor-not-allowed">
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </span>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>

    @include('admin.project-category.modal')
@endsection
