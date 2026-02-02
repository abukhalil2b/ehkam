<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>المؤشرات الإستراتيجية</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; margin: 0 !important; }
            .page-break { page-break-after: always; margin-top: 0.5cm; }
            .kpi-card { border: none !important; box-shadow: none !important; page-break-inside: avoid; }
            .kpi-card > div:first-child { margin-bottom: 0.3cm !important; padding-bottom: 0.3cm !important; }
            .chart-container { margin-top: 0.2cm !important; margin-bottom: 0.3cm !important; padding: 0.2cm !important; height: 280px !important; }
            canvas { max-width: 100% !important; height: 280px !important; }
            .container { max-width: 100% !important; padding: 0.5cm !important; }
            main { padding: 0.3cm !important; }
            .kpi-card .p-6 { padding: 0.4cm !important; }
        }
        .chart-container { position: relative; height: 350px; width: 100%; }
        .saving { opacity: 0.6; pointer-events: none; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 font-sans text-gray-900 min-h-screen">

    <div x-data="kpiManager()" x-init="init()">
        {{-- Navigation --}}
        <nav class="no-print bg-white border-b sticky top-0 z-50 shadow-sm">
            <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white p-2.5 rounded-xl font-bold shadow-md">
                        <span class="material-icons">analytics</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">المؤشرات الإستراتيجية</h1>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Save Status --}}
                    <div x-show="saveStatus" x-transition class="flex items-center gap-2 text-sm">
                        <span x-show="saveStatus === 'saving'" class="text-amber-600">
                            <span class="material-icons animate-spin text-base">sync</span>
                            جاري الحفظ...
                        </span>
                        <span x-show="saveStatus === 'saved'" class="text-green-600">
                            <span class="material-icons text-base">check_circle</span>
                            تم الحفظ
                        </span>
                        <span x-show="saveStatus === 'error'" class="text-red-600">
                            <span class="material-icons text-base">error</span>
                            خطأ في الحفظ
                        </span>
                    </div>

                    <button @click="switchView('edit')" 
                        :class="view === 'edit' ? 'bg-blue-100 text-blue-700 shadow-inner' : 'text-gray-600 hover:bg-gray-100'" 
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2">
                        <span class="material-icons text-lg">edit_note</span>
                        <span>إدارة البيانات</span>
                    </button>
                    <button @click="switchView('report')" 
                        :class="view === 'report' ? 'bg-blue-100 text-blue-700 shadow-inner' : 'text-gray-600 hover:bg-gray-100'" 
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2">
                        <span class="material-icons text-lg">assessment</span>
                        <span>عرض التقرير</span>
                    </button>
                    <button @click="printReport()" 
                        class="bg-slate-800 text-white px-5 py-2.5 rounded-lg hover:bg-slate-700 shadow-md transition flex items-center gap-2">
                        <span class="material-icons text-lg">print</span>
                        <span>طباعة</span>
                    </button>
                </div>
            </div>
        </nav>

        <main class="container mx-auto py-8 px-6">
            {{-- Header --}}
            <div class="mb-8 text-center print:mb-2 print:py-2">
                <h2 class="text-2xl font-bold text-slate-800 mb-2 print:text-lg print:mb-1">المديرية العامة للتخطيط والدراسات</h2>
                <p class="text-slate-500 print:text-xs">تقرير أداء المؤشرات الاستراتيجية للفترة 2023 - 2025</p>
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
                <code class="bg-slate-100 px-4 py-2 rounded mt-4 inline-block text-sm">php artisan db:seed --class=KpiSeeder</code>
            </div>

            {{-- Edit View --}}
            <div x-show="!loading && kpis.length > 0 && view === 'edit'" class="no-print space-y-6">
                
                {{-- Info Banner --}}
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                    <span class="material-icons text-green-500 mt-0.5">cloud_done</span>
                    <div>
                        <p class="text-sm text-green-800 font-medium">البيانات محفوظة في قاعدة البيانات</p>
                        <p class="text-xs text-green-600">يتم حفظ التغييرات تلقائياً عند تعديل أي قيمة.</p>
                    </div>
                </div>
                
                {{-- KPI Edit Cards --}}
                <template x-for="(kpi, kIndex) in kpis" :key="kpi.id">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold" x-text="kIndex + 1"></span>
                                <span x-text="kpi.title"></span>
                                <span class="text-xs bg-slate-200 text-slate-600 px-2 py-0.5 rounded" x-text="kpi.code"></span>
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
                            {{-- 2023 Data --}}
                            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                                <h4 class="font-bold text-blue-700 mb-3 text-sm flex items-center gap-2">
                                    <span class="material-icons text-base">calendar_today</span>
                                    سنة 2023
                                </h4>
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-slate-600 text-xs">
                                            <th class="text-right pb-2">الربع</th>
                                            <th class="text-center pb-2">مستهدف</th>
                                            <th class="text-center pb-2">محقق</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="q in [1,2,3,4]">
                                            <tr class="border-t border-blue-100">
                                                <td class="py-1.5 text-xs font-medium text-slate-600" x-text="quarterNames[q-1]"></td>
                                                <td class="py-1.5 px-1">
                                                    <input type="number" 
                                                        x-model.number="kpi.data2023.target[q-1]" 
                                                        @change="saveValue(kpi.id, 2023, q, 'target', kpi.data2023.target[q-1])"
                                                        class="w-full border border-blue-200 rounded p-1.5 text-center text-xs">
                                                </td>
                                                <td class="py-1.5 px-1">
                                                    <input type="number" 
                                                        x-model.number="kpi.data2023.actual[q-1]" 
                                                        @change="saveValue(kpi.id, 2023, q, 'actual', kpi.data2023.actual[q-1])"
                                                        class="w-full border border-blue-200 rounded p-1.5 text-center text-xs">
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- 2024 Data --}}
                            <div class="bg-emerald-50/50 p-4 rounded-xl border border-emerald-100">
                                <h4 class="font-bold text-emerald-700 mb-3 text-sm flex items-center gap-2">
                                    <span class="material-icons text-base">calendar_today</span>
                                    سنة 2024
                                </h4>
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-slate-600 text-xs">
                                            <th class="text-right pb-2">الربع</th>
                                            <th class="text-center pb-2">مستهدف</th>
                                            <th class="text-center pb-2">محقق</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="q in [1,2,3,4]">
                                            <tr class="border-t border-emerald-100">
                                                <td class="py-1.5 text-xs font-medium text-slate-600" x-text="quarterNames[q-1]"></td>
                                                <td class="py-1.5 px-1">
                                                    <input type="number" 
                                                        x-model.number="kpi.data2024.target[q-1]" 
                                                        @change="saveValue(kpi.id, 2024, q, 'target', kpi.data2024.target[q-1])"
                                                        class="w-full border border-emerald-200 rounded p-1.5 text-center text-xs">
                                                </td>
                                                <td class="py-1.5 px-1">
                                                    <input type="number" 
                                                        x-model.number="kpi.data2024.actual[q-1]" 
                                                        @change="saveValue(kpi.id, 2024, q, 'actual', kpi.data2024.actual[q-1])"
                                                        class="w-full border border-emerald-200 rounded p-1.5 text-center text-xs">
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            {{-- 2025 Data --}}
                            <div class="bg-purple-50/50 p-4 rounded-xl border border-purple-100">
                                <h4 class="font-bold text-purple-700 mb-3 text-sm flex items-center gap-2">
                                    <span class="material-icons text-base">calendar_today</span>
                                    سنة 2025
                                </h4>
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-slate-600 text-xs">
                                            <th class="text-right pb-2">الربع</th>
                                            <th class="text-center pb-2">مستهدف</th>
                                            <th class="text-center pb-2">محقق</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="q in [1,2,3,4]">
                                            <tr class="border-t border-purple-100">
                                                <td class="py-1.5 text-xs font-medium text-slate-600" x-text="quarterNames[q-1]"></td>
                                                <td class="py-1.5 px-1">
                                                    <input type="number" 
                                                        x-model.number="kpi.data2025.target[q-1]" 
                                                        @change="saveValue(kpi.id, 2025, q, 'target', kpi.data2025.target[q-1])"
                                                        class="w-full border border-purple-200 rounded p-1.5 text-center text-xs">
                                                </td>
                                                <td class="py-1.5 px-1">
                                                    <input type="number" 
                                                        x-model.number="kpi.data2025.actual[q-1]" 
                                                        @change="saveValue(kpi.id, 2025, q, 'actual', kpi.data2025.actual[q-1])"
                                                        class="w-full border border-purple-200 rounded p-1.5 text-center text-xs">
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Justification in Edit Mode --}}
                        <div class="px-6 pb-6">
                            <label class="block text-sm font-bold text-slate-600 mb-2">
                                <span class="material-icons text-sm align-middle">description</span>
                                   الملحوظات
                            </label>
                            <textarea 
                                x-model="kpi.justification" 
                                @change="saveJustification(kpi.id, kpi.justification)"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg p-3 text-slate-700 text-sm resize-none" 
                                rows="2" 
                                placeholder="أدخل المبررات هنا..."></textarea>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Report View --}}
            <div x-show="!loading && kpis.length > 0 && view === 'report'" class="space-y-8 print:space-y-2">
                {{-- Chart Type Selector --}}
                <div class="no-print flex justify-center gap-4 mb-4">
                    <p class="text-sm text-slate-500 font-medium flex items-center gap-2">
                        <span class="material-icons text-sm">pie_chart</span>
                        شكل المخطط:
                    </p>
                    <button 
                        @click="changeChartType('bar')"
                        :class="chartType === 'bar' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        class="px-3 py-1.5 rounded-lg border transition flex items-center gap-1.5 text-sm font-medium">
                        <span class="material-icons text-base">bar_chart</span>
                        <span>أعمدة متجاورة</span>
                    </button>
                    <button 
                        @click="changeChartType('bar-stacked')"
                        :class="chartType === 'bar-stacked' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        class="px-3 py-1.5 rounded-lg border transition flex items-center gap-1.5 text-sm font-medium">
                        <span class="material-icons text-base">stacked_bar_chart</span>
                        <span>أعمدة متداخلة</span>
                    </button>
                    <button 
                        @click="changeChartType('line')"
                        :class="chartType === 'line' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        class="px-3 py-1.5 rounded-lg border transition flex items-center gap-1.5 text-sm font-medium">
                        <span class="material-icons text-base">show_chart</span>
                        <span>خطوط</span>
                    </button>
                    <button 
                        @click="changeChartType('radar')"
                        :class="chartType === 'radar' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        class="px-3 py-1.5 rounded-lg border transition flex items-center gap-1.5 text-sm font-medium">
                        <span class="material-icons text-base">radar</span>
                        <span>عجلة</span>
                    </button>
                </div>
                {{-- Dataset Toggle --}}
                <div class="no-print flex justify-center gap-4 mb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="showTarget" @change="renderCharts()" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="text-sm text-slate-600 flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                            المستهدف
                        </span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="showActual" @change="renderCharts()" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="text-sm text-slate-600 flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            المحقق
                        </span>
                    </label>
                </div>
                <template x-for="(kpi, kIndex) in kpis" :key="'report-' + kpi.id">
                    <div class="kpi-card bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden page-break print:rounded-lg print:shadow-none">
                        {{-- Card Header --}}
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 text-white print:px-4 print:py-2 print:mb-0">
                            <div class="flex items-center gap-3">
                                <span class="bg-white/20 w-9 h-9 rounded-lg flex items-center justify-center font-bold print:w-7 print:h-7 print:text-sm" x-text="kIndex + 1"></span>
                                <div>
                                    <h2 class="text-lg font-bold print:text-base print:mb-0" x-text="kpi.title"></h2>
                                    <span class="text-xs text-blue-200 print:text-[10px]" x-text="kpi.code"></span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 print:p-4">
                            {{-- Chart --}}
                            <div class="chart-container mb-6 bg-slate-50 rounded-xl p-4 print:mt-2 print:mb-3 print:p-2">
                                <canvas :id="'chart-' + kpi.id"></canvas>
                            </div>

                            {{-- Summary Stats - Simplified Cards for All Years --}}
                            <div class="flex justify-center gap-6 mb-6 print:mb-3 print:gap-4">
                                {{-- 2023 Summary Card --}}
                                <div class="bg-slate-50 rounded-xl p-4 border-2 border-slate-200 text-center min-w-[120px] print:p-3 print:min-w-[100px]">
                                    <p class="text-lg font-bold text-slate-700 mb-3 print:text-base print:mb-2">2023</p>
                                    <div class="space-y-2">
                                        <p class="text-sm print:text-xs">
                                            <span class="text-slate-500 font-medium">المستهدف:</span>
                                            <span class="font-bold text-slate-700" x-text="formatNumber(sumArray(kpi.data2023.target), kpi.unit)"></span>
                                        </p>
                                        <p class="text-sm print:text-xs">
                                            <span class="text-slate-500 font-medium">المحقق:</span>
                                            <span class="font-bold text-slate-700" x-text="formatNumber(sumArray(kpi.data2023.actual), kpi.unit)"></span>
                                        </p>
                                    </div>
                                </div>
                                {{-- 2024 Summary Card --}}
                                <div class="bg-slate-50 rounded-xl p-4 border-2 border-slate-200 text-center min-w-[120px] print:p-3 print:min-w-[100px]">
                                    <p class="text-lg font-bold text-slate-700 mb-3 print:text-base print:mb-2">2024</p>
                                    <div class="space-y-2">
                                        <p class="text-sm print:text-xs">
                                            <span class="text-slate-500 font-medium">المستهدف:</span>
                                            <span class="font-bold text-slate-700" x-text="formatNumber(sumArray(kpi.data2024.target), kpi.unit)"></span>
                                        </p>
                                        <p class="text-sm print:text-xs">
                                            <span class="text-slate-500 font-medium">المحقق:</span>
                                            <span class="font-bold text-slate-700" x-text="formatNumber(sumArray(kpi.data2024.actual), kpi.unit)"></span>
                                        </p>
                                    </div>
                                </div>
                                {{-- 2025 Summary Card --}}
                                <div class="bg-slate-50 rounded-xl p-4 border-2 border-slate-200 text-center min-w-[120px] print:p-3 print:min-w-[100px]">
                                    <p class="text-lg font-bold text-slate-700 mb-3 print:text-base print:mb-2">2025</p>
                                    <div class="space-y-2">
                                        <p class="text-sm print:text-xs">
                                            <span class="text-slate-500 font-medium">المستهدف:</span>
                                            <span class="font-bold text-slate-700" x-text="formatNumber(sumArray(kpi.data2025.target), kpi.unit)"></span>
                                        </p>
                                        <p class="text-sm print:text-xs">
                                            <span class="text-slate-500 font-medium">المحقق:</span>
                                            <span class="font-bold text-slate-700" x-text="formatNumber(sumArray(kpi.data2025.actual), kpi.unit)"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Justification --}}
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 print:p-2 print:mt-2" x-show="kpi.justification">
                                <h4 class="text-xs font-bold text-slate-500 uppercase mb-2 flex items-center gap-1 print:text-[10px] print:mb-1">
                                    <span class="material-icons text-sm print:text-xs">description</span>
                                       الملحوظات
                                </h4>
                                <p class="text-sm text-slate-700 leading-relaxed print:text-xs" x-text="kpi.justification"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="no-print bg-white border-t mt-12 py-6">
            <div class="container mx-auto px-6 text-center text-sm text-slate-500">
                <p>نظام إدارة المؤشرات الاستراتيجية  </p>
            </div>
        </footer>
    </div>

    <script>
        function kpiManager() {
            return {
                view: 'edit',
                loading: true,
                saveStatus: null,
                chartType: 'bar',
                showTarget: true,
                showActual: true,
                kpis: @json($kpis ?? []),
                charts: {},
                quarterNames: ['الربع الأول', 'الربع الثاني', 'الربع الثالث', 'الربع الرابع'],
                
                init() {
                    this.loading = false;
                    // Ensure data arrays exist
                    this.kpis = this.kpis.map(kpi => ({
                        ...kpi,
                        data2023: kpi.data2023 || { target: [0,0,0,0], actual: [0,0,0,0] },
                        data2024: kpi.data2024 || { target: [0,0,0,0], actual: [0,0,0,0] },
                        data2025: kpi.data2025 || { target: [0,0,0,0], actual: [0,0,0,0] },
                        justification: kpi.justification || ''
                    }));
                },

                switchView(newView) {
                    this.view = newView;
                    if (newView === 'report') {
                        this.$nextTick(() => {
                            setTimeout(() => this.renderCharts(), 50);
                        });
                    }
                },

                changeChartType(type) {
                    this.chartType = type;
                    this.renderCharts();
                },

                printReport() {
                    this.view = 'report';
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.renderCharts();
                            setTimeout(() => window.print(), 300);
                        }, 100);
                    });
                },

                async saveValue(indicatorId, year, quarter, type, value) {
                    this.saveStatus = 'saving';
                    
                    // Find current values
                    const kpi = this.kpis.find(k => k.id === indicatorId);
                    const dataKey = `data${year}`;
                    
                    try {
                        const response = await fetch('{{ route("statistic.kpi.update_value") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                indicator_id: indicatorId,
                                year: year,
                                quarter: quarter,
                                target_value: type === 'target' ? value : kpi[dataKey].target[quarter - 1],
                                actual_value: type === 'actual' ? value : kpi[dataKey].actual[quarter - 1]
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            this.saveStatus = 'saved';
                        } else {
                            this.saveStatus = 'error';
                        }
                    } catch (error) {
                        console.error('Save error:', error);
                        this.saveStatus = 'error';
                    }
                    
                    setTimeout(() => this.saveStatus = null, 2000);
                },

                async saveJustification(indicatorId, justification) {
                    this.saveStatus = 'saving';
                    
                    try {
                        const response = await fetch('{{ route("statistic.kpi.update_justification") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                indicator_id: indicatorId,
                                justification: justification
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            this.saveStatus = 'saved';
                        } else {
                            this.saveStatus = 'error';
                        }
                    } catch (error) {
                        console.error('Save error:', error);
                        this.saveStatus = 'error';
                    }
                    
                    setTimeout(() => this.saveStatus = null, 2000);
                },

                sumArray(arr) {
                    if (!arr || !Array.isArray(arr)) return 0;
                    return arr.reduce((a, b) => (parseFloat(a) || 0) + (parseFloat(b) || 0), 0);
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
                    const chartType = this.chartType === 'bar-stacked' ? 'bar' : this.chartType;
                    const isStacked = this.chartType === 'bar-stacked';
                    
                    this.kpis.forEach(kpi => {
                        const canvasId = 'chart-' + kpi.id;
                        const canvas = document.getElementById(canvasId);
                        
                        if (!canvas) return;
                        
                        if (this.charts[kpi.id]) {
                            this.charts[kpi.id].destroy();
                        }

                        const ctx = canvas.getContext('2d');
                        
                        // Build datasets based on visibility
                        const datasets = [];
                        if (this.showTarget) {
                            datasets.push({
                                label: 'المستهدف',
                                data: [...(kpi.data2023?.target || [0,0,0,0]), ...(kpi.data2024?.target || [0,0,0,0]), ...(kpi.data2025?.target || [0,0,0,0])],
                                backgroundColor: 'rgba(148, 163, 184, 0.7)',
                                borderColor: '#64748b',
                                borderWidth: 1,
                                borderRadius: isStacked ? 2 : 4
                            });
                        }
                        if (this.showActual) {
                            datasets.push({
                                label: 'المحقق فعلياً',
                                data: [...(kpi.data2023?.actual || [0,0,0,0]), ...(kpi.data2024?.actual || [0,0,0,0]), ...(kpi.data2025?.actual || [0,0,0,0])],
                                backgroundColor: 'rgba(37, 99, 235, 0.9)',
                                borderColor: '#1d4ed8',
                                borderWidth: 1,
                                borderRadius: isStacked ? 2 : 4
                            });
                        }
                        
                        this.charts[kpi.id] = new Chart(ctx, {
                            type: chartType,
                            data: {
                                labels: [
                                    'الربع الأول 2023', 'الربع الثاني 2023', 'الربع الثالث 2023', 'الربع الرابع 2023',
                                    'الربع الأول 2024', 'الربع الثاني 2024', 'الربع الثالث 2024', 'الربع الرابع 2024',
                                    'الربع الأول 2025', 'الربع الثاني 2025', 'الربع الثالث 2025', 'الربع الرابع 2025'
                                ],
                                datasets: datasets
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                locale: 'en-US', // استخدام الأرقام الإنجليزية
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
                                            label: function(context) {
                                                // استخدام الأرقام الإنجليزية في التلميحات
                                                let value = context.parsed.y || (context.parsed.r || 0);
                                                return context.dataset.label + ': ' + 
                                                    new Intl.NumberFormat('en-US').format(value);
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: { 
                                        beginAtZero: true, 
                                        grid: { color: '#e2e8f0' },
                                        ticks: {
                                            callback: function(value) {
                                                // استخدام الأرقام الإنجليزية في المحور Y
                                                return new Intl.NumberFormat('en-US', {
                                                    notation: 'compact',
                                                    maximumFractionDigits: 1
                                                }).format(value);
                                            },
                                            font: { size: 10 }
                                        }
                                    },
                                    x: { 
                                        stacked: isStacked,
                                        grid: { display: false },
                                        ticks: { font: { size: 9 }, maxRotation: 45, minRotation: 45 }
                                    }
                                }
                            }
                        });
                    });
                }
            }
        }
    </script>
</body>
</html>
