<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة المؤشرات الاستراتيجية</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; margin: 0 !important; }
            .kpi-card { border: none !important; box-shadow: none !important; page-break-inside: avoid; }
            .kpi-card > div:first-child { margin-bottom: 0.3cm !important; padding-bottom: 0.3cm !important; }
            .container { max-width: 100% !important; padding: 0.5cm !important; }
            main { padding: 0.3cm !important; }
            .kpi-card .p-6 { padding: 0.4cm !important; }
        }
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
                        <span class="material-icons">edit</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">إدارة المؤشرات الاستراتيجية</h1>
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
                    {{-- View Report (Active) --}}
                    <a href="{{ route('statistic.bsc') }}" 
                        class="bg-blue-100 text-blue-700 shadow-inner px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2">
                        <span class="material-icons text-lg">edit</span>
                        <span>إدخال البيانات</span>
                    </a>
                    {{-- Report View --}}
                    <a href="{{ route('statistic.bsc.report') }}" 
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">assessment</span>
                        <span>عرض التقرير</span>
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
                <h2 class="text-2xl font-bold text-slate-800 mb-2 print:text-lg print:mb-1">المديرية العامة للتخطيط والدراسات</h2>
                <p class="text-slate-500 print:text-xs">إدارة بيانات المؤشرات الاستراتيجية للفترة {{ min($years) }} - {{ max($years) }}</p>
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
            <div x-show="!loading && kpis.length > 0" class="space-y-6">
                
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
                        <div class="p-6 grid grid-cols-1 lg:grid-cols-{{ count($years) }} gap-4">
                            @foreach($years as $year)
                            <div class="bg-{{ $loop->index == 0 ? 'blue' : ($loop->index == 1 ? 'emerald' : ($loop->index == 2 ? 'purple' : 'amber')) }}-50/50 p-4 rounded-xl border border-{{ $loop->index == 0 ? 'blue' : ($loop->index == 1 ? 'emerald' : ($loop->index == 2 ? 'purple' : 'amber')) }}-100">
                                <h4 class="font-bold text-{{ $loop->index == 0 ? 'blue' : ($loop->index == 1 ? 'emerald' : ($loop->index == 2 ? 'purple' : 'amber')) }}-700 mb-3 text-sm flex items-center gap-2">
                                    <span class="material-icons text-base">calendar_today</span>
                                    سنة {{ $year }}
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
                                        @for($q = 1; $q <= 4; $q++)
                                        <tr class="border-t border-{{ $loop->index == 0 ? 'blue' : ($loop->index == 1 ? 'emerald' : ($loop->index == 2 ? 'purple' : 'amber')) }}-100">
                                            <td class="py-1.5 text-xs font-medium text-slate-600">الربع {{ $q }}</td>
                                            <td class="py-1.5 px-1">
                                                <input type="number" 
                                                    x-model.number="kpi.data{{ $year }}.target[{{ $q - 1 }}]" 
                                                    @change="saveValue(kpi.id, {{ $year }}, {{ $q }}, 'target', kpi.data{{ $year }}.target[{{ $q - 1 }}])"
                                                    class="w-full border border-{{ $loop->index == 0 ? 'blue' : ($loop->index == 1 ? 'emerald' : ($loop->index == 2 ? 'purple' : 'amber')) }}-200 rounded p-1.5 text-center text-xs">
                                            </td>
                                            <td class="py-1.5 px-1">
                                                <input type="number" 
                                                    x-model.number="kpi.data{{ $year }}.actual[{{ $q - 1 }}]" 
                                                    @change="saveValue(kpi.id, {{ $year }}, {{ $q }}, 'actual', kpi.data{{ $year }}.actual[{{ $q - 1 }}])"
                                                    class="w-full border border-{{ $loop->index == 0 ? 'blue' : ($loop->index == 1 ? 'emerald' : ($loop->index == 2 ? 'purple' : 'amber')) }}-200 rounded p-1.5 text-center text-xs">
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            @endforeach
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
                loading: true,
                saveStatus: null,
                settings: {
                    years: null,
                    quarters: null,
                    indicators: []
                },
                kpis: @json($kpis ?? []),
                availableYears: @json($years ?? [2023, 2024, 2025]),
                
                init() {
                    this.loading = false;
                    // Ensure data arrays exist for all years
                    const availableYears = @json($years ?? [2023, 2024, 2025]);
                    this.kpis = this.kpis.map(kpi => {
                        const newKpi = { ...kpi, justification: kpi.justification || '' };
                        availableYears.forEach(year => {
                            const key = `data${year}`;
                            if (!newKpi[key]) {
                                newKpi[key] = { target: [0,0,0,0], actual: [0,0,0,0] };
                            }
                        });
                        return newKpi;
                    });
                    // Fetch settings from API
                    this.fetchSettings();
                },

                async fetchSettings() {
                    try {
                        const response = await fetch('{{ route("statistic.settings.data") }}');
                        const result = await response.json();
                        console.log('Settings API response:', result);
                        if (result.success && result.data) {
                            this.settings = result.data;
                            console.log('Loaded settings:', this.settings);
                            console.log('Original KPIs count:', this.kpis.length);
                            console.log('Indicators value:', this.settings.indicators);
                            console.log('Indicators type:', typeof this.settings.indicators);
                            console.log('Indicators isArray:', Array.isArray(this.settings.indicators));
                            
                            // Check if indicators is a valid non-empty array
                            const hasIndicators = this.settings.indicators && 
                                Array.isArray(this.settings.indicators) && 
                                this.settings.indicators.length > 0;
                            
                            console.log('hasIndicators:', hasIndicators);
                            
                            if (hasIndicators) {
                                console.log('Filtering by indicators:', this.settings.indicators);
                                // Convert selected indicator IDs to strings for comparison (handles type mismatch)
                                const selectedIndicatorIds = this.settings.indicators.map(id => String(id));
                                this.kpis = this.kpis.filter(kpi => 
                                    selectedIndicatorIds.includes(String(kpi.id))
                                );
                                console.log('Filtered KPIs count:', this.kpis.length);
                                console.log('Filtered KPI IDs:', this.kpis.map(k => k.id));
                            } else {
                                console.log('No indicators selected, clearing all KPIs from edit page');
                                this.kpis = [];
                                console.log('KPIs after clearing:', this.kpis.length);
                            }
                        } else {
                            console.log('No settings data, keeping all KPIs');
                        }
                    } catch (error) {
                        console.error('Error fetching settings:', error);
                    }
                },

                printReport() {
                    // Redirect to report page for printing
                    window.location.href = '{{ route("statistic.bsc.report") }}';
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
                }
            }
        }
    </script>
</body>
</html>
