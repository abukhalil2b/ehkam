<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات تقرير المؤشرات الاستراتيجية</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.75rem;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 font-sans text-gray-900 min-h-screen">

    <div x-data="">
        {{-- Navigation --}}
        <nav class="no-print bg-white border-b sticky top-0 z-50 shadow-sm">
            <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white p-2.5 rounded-xl font-bold shadow-md">
                        <span class="material-icons">tune</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">إعدادات التقرير</h1>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}" 
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">home</span>
                        <span>Dashboard</span>
                    </a>
                    {{-- Report Settings (Active) --}}
                    <a href="{{ route('statistic.settings') }}" 
                        class="bg-blue-100 text-blue-700 shadow-inner px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2">
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
                    {{-- View Report --}}
                    <a href="{{ route('statistic.bsc') }}" 
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">assessment</span>
                        <span>عرض التقرير</span>
                    </a>
                    {{-- Print --}}
                    <button onclick="window.print()"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 shadow-md transition flex items-center gap-2 cursor-pointer">
                        <span class="material-icons text-lg">print</span>
                        <span>Print</span>
                    </button>
                </div>
            </div>
        </nav>

        <main class="container mx-auto py-8 px-6">
            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                    <span class="material-icons text-green-500">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('statistic.settings.save') }}" method="POST" class="container mx-auto">
                @csrf

                <div class="space-y-6">
                    {{-- Years Selection --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 text-white flex justify-between items-center">
                            <div>
                                <h2 class="text-lg font-bold flex items-center gap-2">
                                    <span class="material-icons">calendar_today</span>
                                    السنوات المعروضة
                                </h2>
                                <p class="text-sm text-blue-200 mt-1">حدد السنوات التي تريد عرضها في التقرير</p>
                            </div>
                            @can('admin.kpi-years.index')
                                <a href="{{ route('kpi-years.index') }}" 
                                    class="bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition flex items-center gap-1">
                                    <span class="material-icons text-sm">settings</span>
                                    <span>إدارة السنوات</span>
                                </a>
                            @endcan
                        </div>
                        <div class="p-6">
                            @if($years->count() > 0)
                            <div class="checkbox-grid">
                                @foreach($years as $kpiYear)
                                    <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer hover:bg-slate-50 transition {{ $kpiYear->is_active ? 'border-slate-200' : 'border-red-200 bg-red-50' }}">
                                        <input type="checkbox" 
                                            name="years[]" 
                                            value="{{ $kpiYear->year }}"
                                            {{ in_array($kpiYear->year, $selectedYears) ? 'checked' : '' }}
                                            {{ !$kpiYear->is_active ? 'disabled' : '' }}
                                            class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                        <div class="flex-1">
                                            <span class="font-medium text-slate-700 block">{{ $kpiYear->name }}</span>
                                            @if(!$kpiYear->is_active)
                                                <span class="text-xs text-red-500">غير نشط - يرجى التفعيل من إدارة السنوات</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @else
                                <div class="text-center py-8">
                                    <span class="material-icons text-5xl text-slate-300">calendar_today</span>
                                    <p class="text-slate-500 mt-2">لا توجد سنوات متاحة</p>
                                    <p class="text-sm text-slate-400 mt-1">يرجى إضافة سنوات من صفحة إدارة السنوات</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quarters Selection --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4 text-white">
                            <h2 class="text-lg font-bold flex items-center gap-2">
                                <span class="material-icons">date_range</span>
                                الأرباع المعروضة
                            </h2>
                            <p class="text-sm text-emerald-200 mt-1">حدد الأرباع التي تريد عرضها في التقرير</p>
                        </div>
                        <div class="p-6">
                            <div class="checkbox-grid">
                                @foreach($quarters as $quarter)
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-50 transition">
                                        <input type="checkbox" 
                                            name="quarters[]" 
                                            value="{{ $quarter }}"
                                            {{ in_array($quarter, $selectedQuarters) ? 'checked' : '' }}
                                            class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                                        <span class="font-medium text-slate-700">الربع {{ $quarter }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Indicators Selection --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 text-white">
                            <h2 class="text-lg font-bold flex items-center gap-2">
                                <span class="material-icons">analytics</span>
                                المؤشرات المعروضة
                            </h2>
                            <p class="text-sm text-purple-200 mt-1">حدد المؤشرات التي تريد عرضها في التقرير (اتركها فارغة لعرض جميع المؤشرات)</p>
                        </div>
                        <div class="p-6">
                            @if($indicators->count() > 0)
                                <div class="space-y-2">
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-purple-200 bg-purple-50 cursor-pointer">
                                        <input type="checkbox" 
                                            id="select-all-indicators"
                                            class="w-5 h-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500"
                                            onclick="toggleAllIndicators(this)">
                                        <span class="font-medium text-purple-700">تحديد الكل / إلغاء التحديد</span>
                                    </label>
                                    <div class="checkbox-grid mt-3">
                                        @foreach($indicators as $indicator)
                                            <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-50 transition">
                                                <input type="checkbox" 
                                                    name="indicators[]" 
                                                    value="{{ $indicator->id }}"
                                                    {{ in_array($indicator->id, $selectedIndicators) ? 'checked' : '' }}
                                                    class="indicator-checkbox w-5 h-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                                                <div>
                                                    <span class="font-medium text-slate-700 block">{{ $indicator->title }}</span>
                                                    <span class="text-xs text-slate-500">{{ $indicator->code }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <span class="material-icons text-5xl text-slate-300">inbox</span>
                                    <p class="text-slate-500 mt-2">لا توجد مؤشرات متاحة</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('statistic.bsc') }}" 
                            class="px-6 py-3 rounded-lg font-medium text-slate-600 hover:bg-slate-200 transition">
                            إلغاء
                        </a>
                        <button type="submit" 
                            class="px-6 py-3 rounded-lg font-medium text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-2 shadow-md">
                            <span class="material-icons">save</span>
                            <span>حفظ الإعدادات</span>
                        </button>
                    </div>
                </div>
            </form>
        </main>

        <footer class="bg-white border-t mt-12 py-6">
            <div class="container mx-auto px-6 text-center text-sm text-slate-500">
                <p>نظام إدارة المؤشرات الاستراتيجية</p>
            </div>
        </footer>
    </div>

    <script>
        function toggleAllIndicators(source) {
            const checkboxes = document.querySelectorAll('.indicator-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }
    </script>
</body>
</html>
