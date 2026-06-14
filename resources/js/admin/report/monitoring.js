window.projectMonitor = function () {
    return {
        search: '',
        selectedProject: 'ALL',

        allLogs: [],
        filteredLogs: [],

        settings: null,
        projectList: [],
        isLoading: false,
        currentTime: '',

        init() {

        this.allLogs = window.monitoringLogs || [];

        // support object laravel collection
        if (!Array.isArray(this.allLogs)) {
            this.allLogs = Object.values(this.allLogs);
        }

        this.settings = window.chartSetting || null;

        this.updateClock();

        setInterval(() => {
            this.updateClock();
        }, 1000);

        this.refreshProjectList();

        // isi data awal
        this.filteredLogs = [...this.allLogs];

        this.$nextTick(() => {
            this.renderCharts();
        });

        this.$watch('search', () => {
            this.updateData();
        });

        this.$watch('selectedProject', () => {
            this.updateData();
        });
    },

        updateClock() {
            const now = new Date();
            this.currentTime =
                now.toLocaleTimeString('en-GB') +
                ' | ' +
                now.toLocaleDateString('id-ID');
        },

        refreshProjectList() {
            const names = this.allLogs.map(l => l.name).filter(Boolean);
            this.projectList = [...new Set(names)];
        },

        calculateAvg() {
            if (!this.filteredLogs.length) return 0;

            const sum = this.filteredLogs.reduce((acc, l) => {
                return acc + (parseInt(l.progress) || 0);
            }, 0);

            return Math.round(sum / this.filteredLogs.length);
        },

        getStatusCounts() {
            return [
                {
                    label: 'Stable',
                    count: this.filteredLogs.filter(l => l.progress >= 100).length,
                    color: 'bg-emerald-500'
                },
                {
                    label: 'Active',
                    count: this.filteredLogs.filter(
                        l => l.progress < 100 && l.progress > 0
                    ).length,
                    color: 'bg-cyan-500'
                },
                {
                    label: 'Stuck',
                    count: this.filteredLogs.filter(
                        l => !l.progress || l.progress == 0
                    ).length,
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

            if (val <= this.settings.progress_1)
                return 'text-rose-400';

            if (val <= this.settings.progress_2)
                return 'text-amber-400';

            return 'text-emerald-400';
        },

        getStatusStyle(status) {
            if (!status)
                return 'bg-slate-950 border-slate-800 text-slate-600';

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

            // search filter
            if (this.search && this.search.trim() !== '') {
                const query = this.search.toLowerCase().trim();

                logs = logs.filter(l => {
                    const matchName =
                        l.name &&
                        l.name.toLowerCase().includes(query);

                    const matchCategory =
                        l.category_name &&
                        l.category_name.toLowerCase().includes(query);

                    return matchName || matchCategory;
                });
            }

            // dropdown filter
            if (this.selectedProject !== 'ALL') {
                logs = logs.filter(
                    l => l.name === this.selectedProject
                );
            }

            this.filteredLogs = logs;

            this.$nextTick(() => {
                this.renderCharts();
            });
        },

        async fetchProjectsByCategory(categoryId) {
            this.isLoading = true;

            try {
                const res = await window.axios.get(
                    window.routes.projectsByCategory,
                    {
                        params: {
                            category_project_id:
                                categoryId === 'ALL'
                                    ? null
                                    : categoryId
                        }
                    }
                );

                if (res.data.success) {
                    this.allLogs = res.data.data || [];

                    this.selectedProject = 'ALL';
                    this.search = '';

                    this.refreshProjectList();
                    this.updateData();
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.isLoading = false;
            }
        },

        renderCharts() {
            // doughnut
            const ctx1 = document.getElementById('statusChart');

            if (ctx1) {
                const existing = Chart.getChart(ctx1);

                if (existing) existing.destroy();

                new Chart(ctx1, {
                    type: 'doughnut',
                    data: {
                        datasets: [
                            {
                                data: [
                                    this.filteredLogs.filter(
                                        l => l.progress >= 100
                                    ).length,

                                    this.filteredLogs.filter(
                                        l =>
                                            l.progress < 100 &&
                                            l.progress > 0
                                    ).length,

                                    this.filteredLogs.filter(
                                        l =>
                                            !l.progress ||
                                            l.progress == 0
                                    ).length
                                ],

                                backgroundColor: [
                                    '#10b981',
                                    '#06b6d4',
                                    '#f43f5e'
                                ],

                                borderWidth: 0,
                                cutout: '80%'
                            }
                        ]
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

            // trend
            const ctx2 = document.getElementById('miniTrendChart');

            if (ctx2) {
                const existing = Chart.getChart(ctx2);

                if (existing) existing.destroy();

                new Chart(ctx2, {
                    type: 'line',

                    data: {
                        labels: [1, 2, 3, 4, 5],

                        datasets: [
                            {
                                data: [
                                    20,
                                    40,
                                    30,
                                    50,
                                    this.calculateAvg()
                                ],

                                borderColor: '#06b6d4',
                                borderWidth: 1.5,
                                pointRadius: 0,
                                fill: true,
                                backgroundColor:
                                    'rgba(6,182,212,0.05)',
                                tension: 0.4
                            }
                        ]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        scales: {
                            x: { display: false },
                            y: { display: false }
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

            return (
                date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                }) +
                ' | ' +
                date.toLocaleDateString('id-ID')
            );
        }
    };
};