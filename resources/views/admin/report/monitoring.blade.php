@extends('layouts.admin')

@section('content')

    {{-- IMPORTANT: HARUS DI ATAS --}}
    <script>
        window.monitoringLogs = @json($monitoringLogs ?? []);
        window.chartSetting = @json($chartSetting ?? null);

        window.routes = {
            projectsByCategory: "{{ route('report.projects-by-category') }}"
        };
    </script>

    <div x-data x-init="$store.nav.setTitle('Project Command Center')">

        {{-- MAIN CONTAINER --}}
        <div class="w-full max-w-full min-h-screen
                text-slate-300 font-sans
                p-3 md:p-4 space-y-3
                overflow-x-hidden"
            x-data="projectMonitor()" x-init="init()">

            {{-- TOP BAR --}}
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-2
                   bg-slate-900/80 border border-cyan-500/20
                   px-4 py-3 rounded-2xl backdrop-blur-md shadow-xl
                   w-full max-w-full min-w-0">

                <div class="flex items-center gap-3 min-w-0">
                    <span class="flex h-2.5 w-2.5 relative shrink-0">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-500"></span>
                    </span>

                    <p class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.25em] text-cyan-400 truncate">
                        System Live : <span x-text="filteredLogs.length"></span> Active Nodes
                    </p>
                </div>

                <div class="text-[9px] md:text-[10px] font-mono text-slate-500 truncate" x-text="currentTime"></div>
            </div>

            {{-- GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 w-full min-w-0">

                {{-- FILTER BOX --}}
                <div
                    class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-4 md:p-5 relative overflow-hidden min-w-0">
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/5 to-transparent"></div>

                    <div class="relative z-10 min-w-0">
                        <h2 class="text-lg md:text-2xl font-black italic tracking-tight text-white mb-4 break-words">
                            PROJECTS <span class="text-cyan-500 underline decoration-2">ANALYTICS</span>
                        </h2>

                        <!-- CONTAINER UTAMA (Susunan Vertikal menggunakan flex-col) -->
                        <div class="flex flex-col gap-4 w-full min-w-0">

                            <!-- BARIS 1: DROPDOWN GRIDS -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 w-full">
                                {{-- CATEGORY SELECT --}}
                                <div class="min-w-0">
                                    <label
                                        class="text-[8px] font-bold uppercase tracking-widest text-slate-500 ml-1">Category</label>
                                    <select @change="fetchProjectsByCategory($el.value)"
                                        class="w-full max-w-full truncate mt-1 bg-slate-950 border border-slate-700 rounded-xl text-[10px] text-white font-bold px-3 py-2.5 outline-none focus:border-cyan-500 transition-all">
                                        <option value="ALL">ALL CATEGORIES</option>
                                        @isset($allProjectsCategory)
                                            @foreach ($allProjectsCategory as $p)
                                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>

                                {{-- PROJECT NAME SELECT --}}
                                {{-- STATUS SELECT --}}
<div class="min-w-0">
    <label
        class="text-[8px] font-bold uppercase tracking-widest text-slate-500 ml-1">
        Status
    </label>

    <select x-model="selectedStatus"
        class="w-full max-w-full truncate mt-1 bg-slate-950 border border-slate-700 rounded-xl text-[10px] text-white font-bold px-3 py-2.5 outline-none focus:border-cyan-500 transition-all">

        <option value="ALL">SHOW ALL STATUS</option>

        <template x-for="status in statusList" :key="status">
            <option :value="status" x-text="status"></option>
        </template>
    </select>
</div>
                            </div>

                            <!-- BARIS 2: INPUT TEXT DI BAWAH -->
                            <div class="w-full">
                                <label class="text-[8px] font-bold uppercase tracking-widest text-slate-500 ml-1">Search
                                    Project</label>
                                <div class="relative mt-1">
                                    <input type="text" x-model="search" @input="selectedProject = 'ALL'"
                                        placeholder="Type to search project..."
                                        class="w-full bg-slate-950/70 border border-slate-700 rounded-xl pl-9 pr-4 py-2.5 text-[10px] text-white placeholder-slate-600 outline-none focus:border-cyan-500 transition-all">
                                    <i
                                        class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-600 text-[10px]"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- GLOBAL PROGRESS --}}
                <div
                    class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex items-center justify-between min-w-0 overflow-hidden">
                    <div class="min-w-0">
                        <p class="text-[8px] uppercase tracking-[0.25em] font-black text-slate-500">Global Progress</p>
                        <div class="text-3xl md:text-4xl font-black text-white truncate" x-text="calculateAvg() + '%'">
                        </div>
                        <div class="text-[7px] text-slate-600 uppercase tracking-[0.2em]">AVG PERFORMANCE</div>
                    </div>
                    <div class="w-16 h-16 max-w-full shrink-0">
                        <canvas id="miniTrendChart"></canvas>
                    </div>
                </div>

                {{-- STATUS --}}
                <div
                    class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex items-center gap-4 min-w-0 overflow-hidden">
                    <div class="relative w-20 h-20 max-w-full shrink-0">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="flex flex-col gap-1.5 min-w-0 flex-1">
                        <template x-for="stat in getStatusCounts()">
                            <div class="flex items-center gap-2 text-[9px] font-bold min-w-0">
                                <span class="w-1.5 h-1.5 rounded-full shrink-0" :class="stat.color"></span>
                                <span class="uppercase text-slate-400 truncate"
                                    x-text="stat.label + ': ' + stat.count"></span>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- TABLE --}}
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl w-full max-w-full">
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-800 bg-slate-950/40 min-w-0">
                    <div class="min-w-0">
                        <h3 class="text-xs md:text-sm font-black uppercase tracking-[0.2em] text-white truncate">Monitoring
                            Nodes</h3>
                        <p class="text-[7px] md:text-[8px] uppercase tracking-[0.25em] text-slate-500 truncate">Realtime
                            Telemetry Data</p>
                    </div>
                    <div class="text-[9px] font-mono text-cyan-400 shrink-0">
                        TOTAL : <span x-text="filteredLogs.length"></span>
                    </div>
                </div>

                {{-- EMPTY STATE --}}
                <template x-if="filteredLogs.length === 0">
                    <div class="py-20 flex flex-col items-center justify-center text-center">
                        <div class="text-cyan-500 text-5xl mb-4"><i class="fas fa-database"></i></div>
                        <div class="text-sm font-bold text-white uppercase tracking-[0.2em]">No Monitoring Data</div>
                        <div class="text-[10px] text-slate-500 mt-2 uppercase tracking-[0.2em]">Waiting telemetry stream...
                        </div>
                    </div>
                </template>

                {{-- DESKTOP TABLE --}}
                {{-- DESKTOP TABLE --}}
                <div x-show="filteredLogs.length > 0" class="hidden md:block w-full max-w-full overflow-x-auto">
                    <table class="w-full min-w-full table-fixed border-collapse">
                        <tbody class="divide-y divide-slate-800/40">
                            <template x-for="(log, index) in filteredLogs" :key="log.id || index">
                                <tr class="hover:bg-cyan-500/[0.03] transition-all duration-300 group">
                                    <td class="px-4 py-3">
                                        <div class="text-[11px] font-bold text-white truncate" x-text="log.name"></div>
                                        <div class="text-[7px] uppercase tracking-[0.2em] text-slate-600 truncate"
                                            x-text="log.category_name || 'GENERAL_SYSTEM'"></div>
                                    </td>
                                    <td class="px-4 py-3 w-[240px]">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-[9px] font-black" :class="getProgressTextClass(log.progress)"
                                                x-text="(log.progress || 0) + '%'"></span>
                                            <div class="h-1.5 w-full bg-slate-950 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-1000"
                                                    :style="`width:${log.progress ?? 0}%; background-color:${getDynamicColor(log.progress ?? 0)};`">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center w-[120px]">
                                        <span
                                            class="inline-flex px-2 py-1 rounded-md text-[7px] font-black uppercase tracking-[0.2em] border whitespace-nowrap"
                                            :class="getStatusStyle(log.status)" x-text="log.status || 'OFFLINE'">
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right w-[160px]">
                                        <div class="text-[9px] font-mono text-slate-400 truncate"
                                            x-text="formatDate(log.updated_at)"></div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE LIST VIEW (TAMBAHKAN INI) --}}
                <div x-show="filteredLogs.length > 0" class="block md:hidden w-full divide-y divide-slate-800/60 bg-slate-950/20">
                    <template x-for="(log, index) in filteredLogs" :key="log.id || index">
                        <div class="p-4 space-y-3 hover:bg-cyan-500/[0.02] transition-all">
                            
                            {{-- Baris Atas: Nama & Status --}}
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-white break-words" x-text="log.name"></div>
                                    <div class="text-[8px] uppercase tracking-wider text-slate-500 mt-0.5"
                                         x-text="log.category_name || 'GENERAL_SYSTEM'"></div>
                                </div>
                                <span class="inline-flex px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider border shrink-0 scale-90 origin-top-right"
                                      :class="getStatusStyle(log.status)" x-text="log.status || 'OFFLINE'">
                                </span>
                            </div>

                            {{-- Baris Tengah: Progress Bar --}}
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[9px] font-bold">
                                    <span class="text-slate-500 uppercase tracking-widest text-[7px]">Progress</span>
                                    <span :class="getProgressTextClass(log.progress)" x-text="(log.progress || 0) + '%'"></span>
                                </div>
                                <div class="h-2 w-full bg-slate-950 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                         :style="`width:${log.progress ?? 0}%; background-color:${getDynamicColor(log.progress ?? 0)};`">
                                    </div>
                                </div>
                            </div>

                            {{-- Baris Bawah: Timestamp --}}
                            <div class="flex justify-end text-[8px] font-mono text-slate-500">
                                <i class="far fa-clock mr-1 mt-0.5"></i>
                                <span x-text="formatDate(log.updated_at)"></span>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>

    <style>
        * {
            min-width: 0;
        }

        html,
        body {
            overflow-x: hidden;
        }
    </style>

    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('alpine:init', () => {
            window.projectMonitor = function() {
                return {
                    search: '',
                    allLogs: [],
                    filteredLogs: [],
                    settings: null,
                    selectedStatus: 'ALL',
                    statusList: [],
                    isLoading: false,
                    currentTime: '',

                    init() {
                        let rawLogs = window.monitoringLogs || [];
                        this.allLogs = Array.isArray(rawLogs) ? rawLogs : Object.values(rawLogs);
                        this.settings = window.chartSetting || null;

                        this.updateClock();
                        setInterval(() => this.updateClock(), 1000);

                        this.refreshStatusList();
                        this.filteredLogs = [...this.allLogs];

                        this.$nextTick(() => {
                            this.renderCharts();
                        });

                        this.$watch('search', () => this.updateData());
                        this.$watch('selectedStatus', () => this.updateData());
                    },

                    updateClock() {
                        const now = new Date();
                        this.currentTime = now.toLocaleTimeString('en-GB') + ' | ' + now.toLocaleDateString(
                            'id-ID');
                    },

                    refreshStatusList() {
                        const statuses = this.allLogs.map(l => l.status).filter(Boolean);
                        this.statusList = [...new Set(statuses)];
                    },

                    calculateAvg() {
                        if (!this.filteredLogs.length) return 0;
                        const sum = this.filteredLogs.reduce((acc, l) => acc + (parseInt(l.progress) || 0), 0);
                        return Math.round(sum / this.filteredLogs.length);
                    },

                    getStatusCounts() {
                        return [{
                                label: 'Stable',
                                count: this.filteredLogs.filter(l => l.progress >= 100).length,
                                color: 'bg-emerald-500'
                            },
                            {
                                label: 'Active',
                                count: this.filteredLogs.filter(l => l.progress < 100 && l.progress > 0)
                                    .length,
                                color: 'bg-cyan-500'
                            },
                            {
                                label: 'Stuck',
                                count: this.filteredLogs.filter(l => !l.progress || l.progress == 0).length,
                                color: 'bg-rose-500'
                            }
                        ];
                    },

                    getDynamicColor(val) {
                        val = parseInt(val) || 0;
                        if (val <= 30) return '#f43f5e';
                        if (val <= 70) return '#facc15';
                        return '#10b981';
                    },

                    getProgressTextClass(val) {
                        val = parseInt(val) || 0;
                        if (!this.settings) return 'text-cyan-400';
                        if (val <= this.settings.progress_1) return 'text-rose-400';
                        if (val <= this.settings.progress_2) return 'text-amber-400';
                        return 'text-emerald-400';
                    },

                    getStatusStyle(status) {
                        if (!status) return 'bg-slate-950 border-slate-800 text-slate-600';
                        const s = status.toLowerCase();
                        if (s.includes('done') || s.includes('complete')) {
                            return 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400 shadow-[0_0_10px_rgba(16,185,129,0.1)]';
                        }
                        if (s.includes('active') || s.includes('progress')) {
                            return 'bg-cyan-500/10 border-cyan-500/30 text-cyan-400 shadow-[0_0_10px_rgba(6,182,212,0.1)]';
                        }
                        return 'bg-rose-500/10 border-rose-500/30 text-rose-400';
                    },

                    updateData() {
                        let logs = [...this.allLogs];

                        // 1. Text Search Filter
                        if (this.search && this.search.trim() !== '') {
                            const query = this.search.toLowerCase().trim();
                            logs = logs.filter(l => {
                                const matchName = l.name && l.name.toLowerCase().includes(query);
                                const matchCategory = l.category_name && l.category_name.toLowerCase()
                                    .includes(query);
                                return matchName || matchCategory;
                            });
                        }

                        // 2. Dropdown Project Filter
                        if (this.selectedStatus !== 'ALL') {
                            logs = logs.filter(l => l.status === this.selectedStatus);
                        }

                        this.filteredLogs = logs;

                        this.$nextTick(() => {
                            this.renderCharts();
                        });
                    },

                    async fetchProjectsByCategory(categoryId) {
                        this.isLoading = true;
                        try {
                            const res = await window.axios.get(window.routes.projectsByCategory, {
                                params: {
                                    category_project_id: categoryId === 'ALL' ? null : categoryId
                                }
                            });

                            if (res.data.success) {
                                let incomingData = res.data.data || [];
                                this.allLogs = Array.isArray(incomingData) ? incomingData : Object.values(
                                    incomingData);

                                this.selectedStatus = 'ALL';
                                this.search = '';

                                this.refreshStatusList();
                                this.updateData();
                            }
                        } catch (e) {
                            console.error(e);
                        } finally {
                            this.isLoading = false;
                        }
                    },

                    renderCharts() {
                        // Doughnut Chart
                        const ctx1 = document.getElementById('statusChart');
                        if (ctx1) {
                            const existing = Chart.getChart(ctx1);
                            if (existing) existing.destroy();

                            new Chart(ctx1, {
                                type: 'doughnut',
                                data: {
                                    datasets: [{
                                        data: [
                                            this.filteredLogs.filter(l => l.progress >= 100)
                                            .length,
                                            this.filteredLogs.filter(l => l.progress <
                                                100 && l.progress > 0).length,
                                            this.filteredLogs.filter(l => !l.progress || l
                                                .progress == 0).length
                                        ],
                                        backgroundColor: ['#10b981', '#06b6d4', '#f43f5e'],
                                        borderWidth: 0,
                                        cutout: '80%'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        tooltip: {
                                            enabled: false
                                        }
                                    }
                                }
                            });
                        }

                        // Mini Trend Line Chart
                        const ctx2 = document.getElementById('miniTrendChart');
                        if (ctx2) {
                            const existing = Chart.getChart(ctx2);
                            if (existing) existing.destroy();

                            new Chart(ctx2, {
                                type: 'line',
                                data: {
                                    labels: [1, 2, 3, 4, 5],
                                    datasets: [{
                                        data: [20, 40, 30, 50, this.calculateAvg()],
                                        borderColor: '#06b6d4',
                                        borderWidth: 1.5,
                                        pointRadius: 0,
                                        fill: true,
                                        backgroundColor: 'rgba(6,182,212,0.05)',
                                        tension: 0.4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        x: {
                                            display: false
                                        },
                                        y: {
                                            display: false
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    }
                                }
                            });
                        }
                    },

                    formatDate(d) {
                        if (!d) return '---';
                        const date = new Date(d);
                        return date.toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        }) + ' | ' + date.toLocaleDateString('id-ID');
                    }
                };
            };
        });
    </script>
@endsection
