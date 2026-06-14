import Chart from "chart.js/auto";

window.dashboardManager = function () {
    return {
        summary: {},
        kategoriCards: [],
        barChart: null,

        init() {
            this.summary = window.monitoringData?.summary || {};
            this.buildCards();

            this.$nextTick(() => {
                this.renderCharts();
            });
        },

        buildCards() {
            this.kategoriCards = [
                {
                    label: "DOKUMEN LINGKUNGAN",
                    value: this.summary.aktif_dokumen_lingkungan || 0,
                    color: "from-blue-500/20 to-transparent",
                },
                {
                    label: "PERTEK BMAL",
                    value: this.summary.aktif_pertek_bmal || 0,
                    color: "from-emerald-500/20 to-transparent",
                },
                {
                    label: "PERTEK EMISI",
                    value: this.summary.aktif_pertek_emisi || 0,
                    color: "from-purple-500/20 to-transparent",
                },
                {
                    label: "RINTEK LIMBAH B3",
                    value: this.summary.aktif_project_lainnya || 0,
                    color: "from-slate-500/20 to-transparent",
                },
            ];
        },

        renderCharts() {
            this.renderBar();
        },

        renderBar() {
            this.$nextTick(() => {
                const canvas = document.getElementById("barChart");
                if (!canvas) return;

                const ctx = canvas.getContext("2d");
                if (!ctx) return;

                // 1. Clear existing instance from Chart.js global registry
                const existingChart = Chart.getChart(canvas);
                if (existingChart) {
                    existingChart.destroy();
                }

                // 2. Double check our internal reference
                if (this.barChart) {
                    this.barChart.destroy();
                    this.barChart = null;
                }

                // 3. Create new chart
                this.barChart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: ["Dok Lingkungan", "BMAL", "Emisi", "Rin Limbah B3"],
                        datasets: [
                            {
                                label: "Project Aktif",
                                data: [
                                    this.summary.aktif_dokumen_lingkungan || 0,
                                    this.summary.aktif_pertek_bmal || 0,
                                    this.summary.aktif_pertek_emisi || 0,
                                    this.summary.aktif_project_lainnya || 0,
                                ],
                                backgroundColor: [
                                    "rgba(59, 130, 246, 0.5)",
                                    "rgba(16, 185, 129, 0.5)",
                                    "rgba(168, 85, 247, 0.5)",
                                    "rgba(100, 116, 139, 0.5)",
                                ],
                                borderRadius: 8,
                                borderWidth: 0,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 750 },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: "#1e293b",
                                titleFont: { size: 13 },
                                padding: 10,
                                displayColors: false,
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: "#94a3b8" },
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: "rgba(255,255,255,0.05)" },
                                ticks: { color: "#94a3b8" },
                            },
                        },
                    },
                });
            });
        },

        destroy() {
            if (this.barChart) {
                this.barChart.destroy();
                this.barChart = null;
            }
        },
    };
};
