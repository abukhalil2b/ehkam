<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تقرير المؤشرات الاستراتيجية</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        [x-cloak] {
            display: none !important;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .page-break {
                page-break-after: always;
                margin-top: 0.5cm;
            }

            .kpi-card {
                border: none !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }

            .kpi-card>div:first-child {
                margin-bottom: 0.3cm !important;
                padding-bottom: 0.3cm !important;
            }

            .chart-container {
                margin-top: 0.2cm !important;
                margin-bottom: 0.3cm !important;
                padding: 0.2cm !important;
                height: 280px !important;
            }

            canvas {
                max-width: 100% !important;
                height: 280px !important;
            }

            .container {
                max-width: 100% !important;
                padding: 0.5cm !important;
            }

            main {
                padding: 0.3cm !important;
            }

            .kpi-card .p-6 {
                padding: 0.4cm !important;
            }
        }

        .chart-container {
            position: relative;
            height: 350px;
            width: 100%;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 to-slate-100 font-sans text-gray-900 min-h-screen">

    <div x-data="kpiReportManager()" x-init="init()">
        {{-- Navigation --}}
        <nav class="no-print bg-white border-b sticky top-0 z-50 shadow-sm">
            <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div
                        class="bg-gradient-to-br from-blue-600 to-blue-700 text-white p-2.5 rounded-xl font-bold shadow-md">
                        <span class="material-icons">assessment</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">تقرير المؤشرات الاستراتيجية</h1>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">home</span>
                        <span>Dashboard</span>
                    </a>
                    {{-- Report Settings --}}
                    <a href="{{ route('statistic.settings') }}"
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">tune</span>
                        <span>إعدادات التقرير</span>
                    </a>
                    {{-- KPI Indicators --}}
                    <a href="{{ route('statistic.kpi.indicators') }}"
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">analytics</span>
                        <span>إدارة المؤشرات</span>
                    </a>
                    {{-- Years Management --}}
                    <a href="{{ route('kpi-years.index') }}"
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">calendar_today</span>
                        <span>إدارة السنوات</span>
                    </a>
                    {{-- Data Entry Page --}}
                    <a href="{{ route('statistic.bsc') }}"
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">edit</span>
                        <span>إدخال البيانات</span>
                    </a>
                    {{-- Print --}}
                    <button @click="printReport()"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 shadow-md transition flex items-center gap-2">
                        <span class="material-icons text-lg">print</span>
                        <span>Print</span>
                    </button>
                </div>
            </div>
        </nav>

        <main class="container mx-auto py-8 px-6">
            {{-- Header --}}
            <div class="mb-8 text-center print:mb-2 print:py-2">
                <h2 class="text-2xl font-bold text-slate-800 mb-2 print:text-lg print:mb-1">المديرية العامة للتخطيط
                    والدراسات</h2>
                <p class="text-slate-500 print:text-xs">تقرير أداء المؤشرات الاستراتيجية للفترة {{ min($years) }} -
                    {{ max($years) }}</p>
            </div>

            {{-- Loading State --}}
            <div x-show="loading" class="text-center py-20">
                <span class="material-icons text-5xl text-blue-500 animate-spin">sync</span>
                <p class="text-slate-500 mt-4">جاري تحميل البيانات...</p>
            </div>

            {{-- No Data State --}}
            <div x-show="!loading && kpis.length === 0" class="text-center py-20">
                <span class="material-icons text-6xl text-slate-300">inbox</span>
                <h3 class="text-xl font-bold text-slate-600 mt-4">لا توجد مؤشرات</h3>
                <p class="text-slate-500 mt-2">يرجى تشغيل الـ Seeder لإضافة المؤشرات:</p>
                <code
                    class="bg-slate-100 px-4 py-2 rounded mt-4 inline-block text-sm">php artisan db:seed --class=KpiSeeder</code>
            </div>

            {{-- Report View --}}
            <div x-show="!loading && kpis.length > 0" class="space-y-6 print:space-y-2">
                {{-- Chart Type Selector --}}
                <div class="no-print flex justify-center gap-4 mb-4">
                    <p class="text-sm text-slate-500 font-medium flex items-center gap-2">
                        <span class="material-icons text-sm">pie_chart</span>
                        شكل المخطط:
                    </p>
                    <button @click="changeChartType('bar')" :disabled="chartChanging"
                        :class="chartType === 'bar' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        :style="chartChanging ? 'opacity:0.5;cursor:not-allowed' : ''"
                        class="px-3 py-1.5 rounded-lg border transition flex items-center gap-1.5 text-sm font-medium">
                        <span class="material-icons text-base">bar_chart</span>
                        <span>أعمدة متجاورة</span>
                    </button>
                    <button @click="changeChartType('bar-stacked')" :disabled="chartChanging"
                        :class="chartType === 'bar-stacked' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        :style="chartChanging ? 'opacity:0.5;cursor:not-allowed' : ''"
                        class="px-3 py-1.5 rounded-lg border transition flex items-center gap-1.5 text-sm font-medium">
                        <span class="material-icons text-base">stacked_bar_chart</span>
                        <span>أعمدة متداخلة</span>
                    </button>
                    <button @click="changeChartType('line')" :disabled="chartChanging"
                        :class="chartType === 'line' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        :style="chartChanging ? 'opacity:0.5;cursor:not-allowed' : ''"
                        class="px-3 py-1.5 rounded-lg border transition flex items-center gap-1.5 text-sm font-medium">
                        <span class="material-icons text-base">show_chart</span>
                        <span>خطوط</span>
                    </button>
                    <button @click="changeChartType('radar')" :disabled="chartChanging"
                        :class="chartType === 'radar' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        :style="chartChanging ? 'opacity:0.5;cursor:not-allowed' : ''"
                        class="px-3 py-1.5 rounded-lg border transition flex items-center gap-1.5 text-sm font-medium">
                        <span class="material-icons text-base">radar</span>
                        <span>عجلة</span>
                    </button>
                </div>
                {{-- Dataset Toggle --}}
                <div class="no-print flex justify-center gap-4 mb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="showTarget" @change="renderCharts()"
                            class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="text-sm text-slate-600 flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                            المستهدف
                        </span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="showActual" @change="renderCharts()"
                            class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="text-sm text-slate-600 flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            المحقق
                        </span>
                    </label>
                </div>

                {{-- KPI Report Cards --}}
                <template x-for="(kpi, kIndex) in kpis" :key="'report-' + kpi.id">
                    <div
                        class="kpi-card bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden page-break print:rounded-lg print:shadow-none">
                        {{-- Card Header --}}
                        <div
                            class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 text-white print:px-4 print:py-2 print:mb-0">
                            <div class="flex items-center gap-3">
                                <span
                                    class="bg-white/20 w-9 h-9 rounded-lg flex items-center justify-center font-bold print:w-7 print:h-7 print:text-sm"
                                    x-text="kIndex + 1"></span>
                                <div>
                                    <h2 class="text-lg font-bold print:text-base print:mb-0" x-text="kpi.title"></h2>
                                    <span class="text-xs text-blue-200 print:text-[10px]" x-text="kpi.code"></span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 print:p-4">
                            {{-- Chart --}}
                            <div
                                class="chart-container mb-6 bg-slate-50 rounded-xl p-4 print:mt-2 print:mb-3 print:p-2">
                                <canvas :id="'chart-' + kpi.id"></canvas>
                            </div>

                            {{-- Summary Stats - Per Year --}}
                            <div class="grid grid-cols-{{ count($years) }} gap-4 mb-6 print:mb-3">
                                @foreach($years as $year)
                                    <div
                                        class="bg-{{ $loop->index == 0 ? 'blue' : ($loop->index == 1 ? 'emerald' : ($loop->index == 2 ? 'purple' : 'amber')) }}-50 rounded-xl p-4 border border-{{ $loop->index == 0 ? 'blue' : ($loop->index == 1 ? 'emerald' : ($loop->index == 2 ? 'purple' : 'amber')) }}-100 text-center">
                                        <p
                                            class="font-bold text-{{ $loop->index == 0 ? 'blue' : ($loop->index == 1 ? 'emerald' : ($loop->index == 2 ? 'purple' : 'amber')) }}-700 mb-3 text-sm flex items-center justify-center gap-1">
                                            <span class="material-icons text-base">calendar_today</span>
                                            سنة {{ $year }}
                                        </p>
                                        <div class="space-y-2">
                                            <p class="text-sm print:text-xs">
                                                <span class="text-slate-500 font-medium">المستهدف:</span>
                                                <span class="font-bold text-slate-700"
                                                    x-text="formatNumber(sumArray(kpi.data{{ $year }}.target), kpi.unit)"></span>
                                            </p>
                                            <p class="text-sm print:text-xs">
                                                <span class="text-slate-500 font-medium">المحقق:</span>
                                                <span class="font-bold text-slate-700"
                                                    x-text="formatNumber(sumArray(kpi.data{{ $year }}.actual), kpi.unit)"></span>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Justification --}}
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 print:p-2 print:mt-2"
                                x-show="kpi.justification">
                                <h4
                                    class="text-xs font-bold text-slate-500 uppercase mb-2 flex items-center gap-1 print:text-[10px] print:mb-1">
                                    <span class="material-icons text-sm print:text-xs">description</span>
                                    الملحوظات
                                </h4>
                                <p class="text-sm text-slate-700 leading-relaxed print:text-xs"
                                    x-text="kpi.justification"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="no-print bg-white border-t mt-12 py-6">
            <div class="container mx-auto px-6 text-center text-sm text-slate-500">
                <p>نظام إدارة المؤشرات الاستراتيجية </p>
            </div>
        </footer>
    </div>

    <script>
        function kpiReportManager() {
            return {
                loading: true,
                chartType: 'bar',
                showTarget: true,
                showActual: true,
                settings: {
                    years: null,
                    quarters: null,
                    indicators: []
                },
                kpis: @json($kpis ?? []),
                charts: {},
                quarterNames: ['الربع الأول', 'الربع الثاني', 'الربع الثالث', 'الربع الرابع'],
                chartChanging: false,
                availableYears: @json($years ?? [2023, 2024, 2025]),
                chartsReady: false,
                settingsFetched: false,
                renderTimeout: null,

                get globalSummary() {
                    const summary = {};
                    this.availableYears.forEach(year => {
                        let totalTarget = 0;
                        let totalActual = 0;
                        this.kpis.forEach(kpi => {
                            const data = kpi[`data${year}`] || { target: [0, 0, 0, 0], actual: [0, 0, 0, 0] };
                            totalTarget += this.sumArray(data.target);
                            totalActual += this.sumArray(data.actual);
                        });
                        summary[year] = { target: totalTarget, actual: totalActual };
                    });
                    return summary;
                },

                init() {
                    this.loading = false;
                    // Ensure data arrays exist for all years
                    const availableYears = @json($years ?? [2023, 2024, 2025]);
                    this.kpis = this.kpis.map(kpi => {
                        const newKpi = { ...kpi, justification: kpi.justification || '' };
                        availableYears.forEach(year => {
                            const key = `data${year}`;
                            if (!newKpi[key]) {
                                newKpi[key] = { target: [0, 0, 0, 0], actual: [0, 0, 0, 0] };
                            }
                        });
                        return newKpi;
                    });
                    // Fetch settings from API
                    this.fetchSettings();
                },

                async fetchSettings() {
                    // Prevent double-fetching
                    if (this.settingsFetched) {
                        console.log('Settings already fetched, skipping');
                        return;
                    }
                    this.settingsFetched = true;

                    try {
                        const response = await fetch('{{ route("statistic.settings.data") }}');
                        const result = await response.json();
                        console.log('Settings API response:', result);
                        if (result.success && result.data) {
                            this.settings = result.data;
                            console.log('Loaded settings:', this.settings);

                            // Check if indicators is a valid non-empty array
                            const hasIndicators = this.settings.indicators &&
                                Array.isArray(this.settings.indicators) &&
                                this.settings.indicators.length > 0;

                            if (hasIndicators) {
                                console.log('Filtering by indicators:', this.settings.indicators);
                                // Convert selected indicator IDs to strings for comparison (handles type mismatch)
                                const selectedIndicatorIds = this.settings.indicators.map(id => String(id));
                                this.kpis = this.kpis.filter(kpi =>
                                    selectedIndicatorIds.includes(String(kpi.id))
                                );
                                console.log('Filtered KPIs count:', this.kpis.length);
                            } else {
                                console.log('No indicators selected, clearing all KPIs from report');
                                this.kpis = [];
                            }
                        } else {
                            console.log('No settings data, keeping all KPIs');
                        }

                        // Render charts after settings are loaded and DOM is ready
                        // Wait for Alpine to fully render the filtered KPIs
                        this.$nextTick(() => {
                            this.$nextTick(() => {
                                // Add extra delay to ensure DOM is stable
                                setTimeout(() => {
                                    this.scheduleChartRendering();
                                }, 300);
                            });
                        });
                    } catch (error) {
                        console.error('Error fetching settings:', error);
                        // Still render charts even if settings fail
                        this.$nextTick(() => {
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    this.scheduleChartRendering();
                                }, 300);
                            });
                        });
                    }
                },

                scheduleChartRendering() {
                    // Clear any pending render
                    if (this.renderTimeout) {
                        clearTimeout(this.renderTimeout);
                        this.renderTimeout = null;
                    }

                    // Wait for all canvases to be in the DOM
                    const checkCanvases = () => {
                        if (this.kpis.length === 0) {
                            return;
                        }

                        // Check if all canvas elements exist and are properly attached to DOM
                        let allCanvasesReady = true;
                        let missingCanvases = [];

                        for (let kpi of this.kpis) {
                            const canvasId = 'chart-' + kpi.id;
                            const canvas = document.getElementById(canvasId);

                            if (!canvas) {
                                allCanvasesReady = false;
                                missingCanvases.push(canvasId);
                            } else if (!canvas.parentElement || canvas.offsetParent === null) {
                                // Canvas exists but not properly attached/visible
                                allCanvasesReady = false;
                                missingCanvases.push(canvasId + ' (not attached)');
                            }
                        }

                        if (allCanvasesReady) {
                            this.renderCharts();
                            this.chartsReady = true;
                        } else {
                            this.renderTimeout = setTimeout(checkCanvases, 100);
                        }
                    };

                    checkCanvases();
                },

                changeChartType(type) {
                    // Always update the chart type, even if currently changing
                    this.chartType = type;

                    if (this.chartChanging) {
                        return;
                    }

                    this.chartChanging = true;

                    // Clear any pending render timeouts
                    if (this.renderTimeout) {
                        clearTimeout(this.renderTimeout);
                        this.renderTimeout = null;
                    }

                    // Destroy all existing charts first
                    Object.keys(this.charts).forEach(chartId => {
                        try {
                            const chart = this.charts[chartId];
                            if (chart) {
                                chart.destroy();
                            }
                        } catch (e) {
                            console.warn('Error destroying chart during type change:', e);
                        }
                    });
                    this.charts = {};

                    // Use timeout to allow UI to update before re-rendering
                    setTimeout(() => {
                        this.chartChanging = false;
                        this.scheduleChartRendering();
                    }, 50);
                },

                printReport() {
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            this.renderCharts();
                            setTimeout(() => window.print(), 500);
                        });
                    });
                },

                sumArray(arr) {
                    if (!arr || !Array.isArray(arr)) return 0;
                    // If settings not loaded yet, show all quarters
                    if (!this.settings.quarters || !Array.isArray(this.settings.quarters)) {
                        return arr.reduce((a, b) => (parseFloat(a) || 0) + (parseFloat(b) || 0), 0);
                    }
                    // Only sum selected quarters
                    return arr.reduce((sum, val, index) => {
                        const quarterIndex = index + 1;
                        if (this.settings.quarters.includes(quarterIndex)) {
                            return sum + (parseFloat(val) || 0);
                        }
                        return sum;
                    }, 0);
                },

                formatNumber(value, unit) {
                    // استخدام الأرقام الإنجليزية
                    if (unit === 'currency') {
                        return new Intl.NumberFormat('en-US', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(value);
                    }
                    return new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(value);
                },

                renderCharts() {
                    // Guard: Don't render if loading, no KPIs, or changing chart type
                    if (this.loading || !this.kpis || this.kpis.length === 0 || this.chartChanging) {
                        return;
                    }

                    const chartType = this.chartType === 'bar-stacked' ? 'bar' : this.chartType;
                    const isOverlay = this.chartType === 'bar-stacked'; // Changed from isStacked to isOverlay for clarity

                    // Get years from settings or use available years from PHP
                    const availableYears = @json($years ?? [2023, 2024, 2025]);
                    const years = this.settings.years || availableYears;
                    const quarters = this.settings.quarters || [1, 2, 3, 4];

                    this.kpis.forEach(kpi => {
                        const canvasId = 'chart-' + kpi.id;
                        const canvas = document.getElementById(canvasId);

                        // Skip if canvas is not found
                        if (!canvas) {
                            return;
                        }

                        this.renderSingleChart(kpi, canvas, chartType, isOverlay, years, quarters);
                    });
                },

                renderSingleChart(kpi, canvas, chartType, isOverlay, years, quarters) {
                    // Additional safety checks
                    if (!canvas) return;
                    
                    // Destroy existing chart on this canvas if any (using Chart.js registry if available)
                    if (typeof Chart.getChart === 'function') {
                        const existingChart = Chart.getChart(canvas);
                        if (existingChart) {
                            existingChart.destroy();
                        }
                    }

                    // Also check our local registry and destroy if found
                    if (this.charts[kpi.id]) {
                        try {
                            this.charts[kpi.id].destroy();
                        } catch (e) {
                            // Ignore if already destroyed
                        }
                        delete this.charts[kpi.id];
                    }
                    
                    // Build filtered data based on settings
                    const labels = [];
                    const targetData = [];
                    const actualData = [];
                    
                    years.forEach(year => {
                        quarters.forEach(quarter => {
                            const quarterNames = ['الربع الأول', 'الربع الثاني', 'الربع الثالث', 'الربع الرابع'];
                            labels.push(`${quarterNames[quarter - 1]} ${year}`);
                            
                            const yearData = kpi[`data${year}`] || { target: [0, 0, 0, 0], actual: [0, 0, 0, 0] };
                            targetData.push(yearData.target[quarter - 1] || 0);
                            actualData.push(yearData.actual[quarter - 1] || 0);
                        });
                    });
                    
                    // Build datasets based on visibility
                    const datasets = [];
                    
                    // For overlay charts:
                    // Target: Background, wider (0.9), Order 1
                    // Actual: Foreground, narrower (0.6), Order 0
                    // Both: No stack ID to prevent summing (overlaying is achieved by not stacking but sharing x)
                    
                    if (this.showTarget) {
                        datasets.push({
                            label: 'المستهدف',
                            data: targetData,
                            backgroundColor: isOverlay ? 'rgba(148, 163, 184, 0.4)' : 'rgba(148, 163, 184, 0.3)',
                            borderColor: '#64748b',
                            borderWidth: 2,
                            borderRadius: 4,
                            barPercentage: isOverlay ? 1.0 : 0.9,
                            categoryPercentage: 0.8,
                            order: isOverlay ? 1 : undefined, // Draw background first
                            grouped: isOverlay ? false : true // Allow overlap
                        });
                    }
                    if (this.showActual) {
                        datasets.push({
                            label: 'المحقق فعلياً',
                            data: actualData,
                            backgroundColor: isOverlay ? 'rgba(37, 99, 235, 0.8)' : 'rgba(37, 99, 235, 0.5)',
                            borderColor: '#1d4ed8',
                            borderWidth: 2,
                            borderRadius: 4,
                            barPercentage: isOverlay ? 0.6 : 0.7,
                            categoryPercentage: 0.8,
                            order: isOverlay ? 0 : undefined, // Draw foreground second
                            grouped: isOverlay ? false : true // Allow overlap
                        });
                    }

                    try {
                        // Pass canvas element directly instead of context
                        this.charts[kpi.id] = new Chart(canvas, {
                            type: chartType,
                            data: {
                                labels: labels,
                                datasets: datasets
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                locale: 'en-US', // استخدام الأرقام الإنجليزية
                                animation: {
                                    duration: 0 // Disable animation for better performance during rapid changes
                                },
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        rtl: true,
                                        labels: {
                                            font: { size: 11 },
                                            padding: 15,
                                            usePointStyle: true
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function (context) {
                                                // استخدام الأرقام الإنجليزية في التلميحات
                                                let value = context.parsed.y || (context.parsed.r || 0);
                                                try {
                                                    return context.dataset.label + ': ' +
                                                        new Intl.NumberFormat('en-US').format(value);
                                                } catch (e) {
                                                    return context.dataset.label + ': ' + value;
                                                }
                                            }
                                        }
                                    }
                                },
                                scales: chartType === 'radar' ? {} : {
                                    y: {
                                        beginAtZero: true,
                                        stacked: false, // We don't want to sum the values in overlay mode
                                        grid: { color: '#e2e8f0' },
                                        ticks: {
                                            callback: function (value) {
                                                // استخدام الأرقام الإنجليزية في المحور Y
                                                try {
                                                    return new Intl.NumberFormat('en-US', {
                                                        notation: 'compact',
                                                        maximumFractionDigits: 1
                                                    }).format(value);
                                                } catch (e) { return value; }
                                            },
                                            font: { size: 10 }
                                        }
                                    },
                                    x: {
                                        stacked: false, // We don't want to sum the values in overlay mode
                                        grid: { display: false },
                                        ticks: {
                                            font: { size: 10 },
                                            maxRotation: 45,
                                            minRotation: 0
                                        }
                                    }
                                }
                            }
                        });
                    } catch (e) {
                        console.error('Error creating chart for KPI ' + kpi.id + ':', e);
                    }
                }
            }
        }
    </script>
</body>

</html>