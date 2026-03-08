<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة ذكاء الأعمال - مؤشرات الأوقاف والشؤون الدينية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f3f4f6;
        }

        /* تخصيص الـ Scrollbar لتشبه تطبيقات BI */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>

<body class="text-gray-800 antialiased overflow-hidden" x-data="dashboardController()">

    <div class="flex h-screen w-full">

        <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-xl z-20 hidden md:flex">
            <div class="p-6 flex items-center gap-3 border-b border-slate-700">
                <i class="fa-solid fa-chart-pie text-emerald-400 text-2xl"></i>
                <h1 class="text-xl font-bold tracking-wide">منصة اتقان <span
                        class="text-xs text-emerald-400 block font-normal">للتخطيط والإحصاء</span></h1>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('dashboard_show') }}"
                    class="flex items-center gap-3 bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-md transition-all">
                    <i class="fa-solid fa-home"></i> الملخص التنفيذي
                </a>
                <a href="{{ url('statistic/index') }}"
                    class="flex items-center gap-3 text-slate-300 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-lg transition-all">
                    <i class="fa-solid fa-chart-line"></i> لوحة الإحصاءات القديمة
                </a>
                <a href="{{ url('endowments/1/statistics') }}"
                    class="flex items-center gap-3 text-slate-300 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-lg transition-all">
                    <i class="fa-solid fa-mosque"></i> الشؤون الوقفية
                </a>
                <a href="{{ route('quran-schools.index') }}"
                    class="flex items-center gap-3 text-slate-300 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-lg transition-all">
                    <i class="fa-solid fa-book-quran"></i> مدارس القرآن
                </a>
                <a href="{{ route('guidance-statistics.index') }}"
                    class="flex items-center gap-3 text-slate-300 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-lg transition-all">
                    <i class="fa-solid fa-users"></i> الكوادر الدينية
                </a>
                <a href="{{ url('statistic/zakah/1') }}"
                    class="flex items-center gap-3 text-slate-300 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-lg transition-all">
                    <i class="fa-solid fa-hand-holding-dollar"></i> مؤشرات الزكاة
                </a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-hidden">

            <header class="bg-white shadow-sm z-10 p-4 flex flex-col md:flex-row justify-between items-center gap-4">
                <h2 class="text-2xl font-bold text-slate-800">الملخص التنفيذي للقطاعات</h2>

                <div class="flex flex-wrap items-center gap-3 bg-slate-50 p-2 rounded-lg border border-slate-200">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-filter text-slate-400"></i>
                        <span class="text-sm font-semibold text-slate-600">الفلاتر:</span>
                    </div>

                    <select x-model="filters.year" @change="applyFilters()"
                        class="text-sm border-slate-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white py-1.5 pl-8 pr-3">
                        <option value="all">كل السنوات</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>

                    <select x-model="filters.governorate" @change="applyFilters()"
                        class="text-sm border-slate-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white py-1.5 pl-8 pr-3">
                        <option value="all">كل المحافظات</option>
                        <option value="مسقط">مسقط</option>
                        <option value="ظفار">ظفار</option>
                        <option value="الداخلية">الداخلية</option>
                    </select>

                    <button @click="resetFilters()"
                        class="text-xs bg-slate-200 text-slate-600 hover:bg-slate-300 px-3 py-1.5 rounded-md transition">إعادة
                        ضبط</button>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div
                        class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-r-4 border-r-emerald-500 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-semibold text-slate-500 mb-1">إجمالي إيرادات الأوقاف</p>
                                <h3 class="text-2xl font-bold text-slate-800" x-text="formatCurrency(kpis.revenues)">0
                                </h3>
                            </div>
                            <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600"><i
                                    class="fa-solid fa-money-bill-trend-up"></i></div>
                        </div>
                        <p class="text-xs text-emerald-600 mt-3 font-medium"><i
                                class="fa-solid fa-arrow-trend-up ml-1"></i> +12.5% عن العام السابق</p>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-r-4 border-r-blue-500 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-semibold text-slate-500 mb-1">الطلبة الدارسين (قرآن)</p>
                                <h3 class="text-2xl font-bold text-slate-800" x-text="formatNumber(kpis.students)">0
                                </h3>
                            </div>
                            <div class="bg-blue-100 p-2 rounded-lg text-blue-600"><i
                                    class="fa-solid fa-graduation-cap"></i></div>
                        </div>
                        <p class="text-xs text-blue-600 mt-3 font-medium"><i
                                class="fa-solid fa-arrow-trend-up ml-1"></i> +5.2% عن العام السابق</p>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-r-4 border-r-amber-500 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-semibold text-slate-500 mb-1">الكوادر الدينية (أئمة وخطباء)</p>
                                <h3 class="text-2xl font-bold text-slate-800" x-text="formatNumber(kpis.imams)">0</h3>
                            </div>
                            <div class="bg-amber-100 p-2 rounded-lg text-amber-600"><i
                                    class="fa-solid fa-microphone"></i></div>
                        </div>
                        <p class="text-xs text-slate-500 mt-3">يغطي 85% من احتياج الجوامع</p>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-r-4 border-r-purple-500 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-semibold text-slate-500 mb-1">مؤسسات وقفية مسجلة</p>
                                <h3 class="text-2xl font-bold text-slate-800" x-text="formatNumber(kpis.awqafCount)">0
                                </h3>
                            </div>
                            <div class="bg-purple-100 p-2 rounded-lg text-purple-600"><i
                                    class="fa-solid fa-building-columns"></i></div>
                        </div>
                        <p class="text-xs text-purple-600 mt-3 font-medium"><i
                                class="fa-solid fa-arrow-trend-up ml-1"></i> تم تسجيل 14 مؤسسة جديدة</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 lg:col-span-2">
                        <h3 class="text-base font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">الأداء المالي
                            السنوي للأوقاف (إيرادات vs مصروفات)</h3>
                        <div class="relative h-72">
                            <canvas id="financeChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                        <h3 class="text-base font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">طلبة القرآن
                            (ذكور / إناث)</h3>
                        <div class="relative h-72 flex justify-center items-center">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-10">

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                        <h3 class="text-base font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">التوزيع
                            الجغرافي للكوادر الدينية</h3>
                        <div class="relative h-80">
                            <canvas id="geoChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                        <h3 class="text-base font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">أعلى
                            المحافظات في الإيرادات الوقفية</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-right">
                                <thead class="bg-slate-50 text-slate-500">
                                    <tr>
                                        <th class="py-3 px-4 font-semibold rounded-r-lg">المحافظة</th>
                                        <th class="py-3 px-4 font-semibold">الإيرادات (ر.ع)</th>
                                        <th class="py-3 px-4 font-semibold">نسبة تحقيق الهدف</th>
                                        <th class="py-3 px-4 font-semibold rounded-l-lg">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                                        <td class="py-3 px-4 font-bold text-slate-700">مسقط</td>
                                        <td class="py-3 px-4">450,200</td>
                                        <td class="py-3 px-4">
                                            <div class="w-full bg-slate-200 rounded-full h-2">
                                                <div class="bg-emerald-500 h-2 rounded-full" style="width: 95%"></div>
                                            </div>
                                            <span class="text-xs text-slate-500 mt-1 block">95%</span>
                                        </td>
                                        <td class="py-3 px-4"><span
                                                class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-bold">ممتاز</span>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                                        <td class="py-3 px-4 font-bold text-slate-700">الداخلية</td>
                                        <td class="py-3 px-4">280,000</td>
                                        <td class="py-3 px-4">
                                            <div class="w-full bg-slate-200 rounded-full h-2">
                                                <div class="bg-blue-500 h-2 rounded-full" style="width: 80%"></div>
                                            </div>
                                            <span class="text-xs text-slate-500 mt-1 block">80%</span>
                                        </td>
                                        <td class="py-3 px-4"><span
                                                class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold">جيد
                                                جداً</span></td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-3 px-4 font-bold text-slate-700">ظفار</td>
                                        <td class="py-3 px-4">150,500</td>
                                        <td class="py-3 px-4">
                                            <div class="w-full bg-slate-200 rounded-full h-2">
                                                <div class="bg-amber-500 h-2 rounded-full" style="width: 65%"></div>
                                            </div>
                                            <span class="text-xs text-slate-500 mt-1 block">65%</span>
                                        </td>
                                        <td class="py-3 px-4"><span
                                                class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold">يحتاج
                                                تحسين</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        // --- 1. بيانات تجريبية (Mock Database) ---
        // في الواقع يتم جلب هذه البيانات عبر API (مثلاً Axios/Livewire) وتتأثر بالفلاتر من السيرفر.
        const DB = @json($dbData);

        // تعريف المكون في Alpine.js
        function dashboardController() {
            return {
                // الحالة (State)
                filters: { year: 'all', governorate: 'all' },
                kpis: { revenues: 0, students: 0, imams: 0, awqafCount: 0 },

                // متغيرات لحفظ كائنات الرسوم البيانية لتدميرها وتحديثها
                chartsInstances: { finance: null, gender: null, geo: null },

                // دالة التهيئة (عند بدء الصفحة)
                init() {
                    Chart.defaults.font.family = "'Tajawal', sans-serif";
                    Chart.defaults.color = '#64748b'; // slate-500
                    this.applyFilters();
                },

                // تطبيق الفلاتر (محاكاة لاستدعاء البيانات من السيرفر)
                applyFilters() {
                    // 1. تحديث أرقام KPIs بناءً على السنة
                    let yearKey = this.filters.year === 'all' ? 'all' : this.filters.year;
                    // إضافة بعض العشوائية لمحاكاة الفلترة بالمحافظة إذا تم اختيارها
                    let modifier = this.filters.governorate === 'all' ? 1 : 0.3;

                    this.kpis.revenues = DB.kpis[yearKey].revenues * modifier;
                    this.kpis.students = DB.kpis[yearKey].students * modifier;
                    this.kpis.imams = DB.kpis[yearKey].imams * modifier;
                    this.kpis.awqafCount = Math.floor(DB.kpis[yearKey].awqafCount * modifier);

                    // 2. تحديث الرسوم البيانية
                    this.renderCharts(modifier);
                },

                resetFilters() {
                    this.filters.year = 'all';
                    this.filters.governorate = 'all';
                    this.applyFilters();
                },

                // دوال المساعدة للتنسيق (Formatters)
                formatCurrency(num) {
                    return new Intl.NumberFormat('ar-OM', { style: 'currency', currency: 'OMR', maximumFractionDigits: 0 }).format(num);
                },
                formatNumber(num) {
                    return new Intl.NumberFormat('ar-OM').format(num);
                },

                // رسم وإعادة رسم المخططات (Core BI logic)
                renderCharts(modifier) {
                    // تدمير المخططات القديمة إذا كانت موجودة لتجنب تداخل الـ Canvas
                    Object.keys(this.chartsInstances).forEach(key => {
                        if (this.chartsInstances[key]) this.chartsInstances[key].destroy();
                    });

                    // 1. Finance Chart (Bar/Line combo is very BI-style)
                    const ctxFinance = document.getElementById('financeChart').getContext('2d');
                    this.chartsInstances.finance = new Chart(ctxFinance, {
                        type: 'bar',
                        data: {
                            labels: DB.charts.finance.labels,
                            datasets: [
                                {
                                    label: 'الإيرادات',
                                    data: DB.charts.finance.revenues.map(v => v * modifier),
                                    backgroundColor: '#10b981', // emerald
                                    borderRadius: 4,
                                    order: 2
                                },
                                {
                                    label: 'المصروفات',
                                    data: DB.charts.finance.expenses.map(v => v * modifier),
                                    backgroundColor: '#f43f5e', // rose
                                    borderRadius: 4,
                                    order: 3
                                },
                                {
                                    label: 'خط الاتجاه (Trend)',
                                    type: 'line',
                                    data: DB.charts.finance.revenues.map(v => v * modifier),
                                    borderColor: '#0f766e', // teal
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: false,
                                    order: 1
                                }
                            ]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
                    });

                    // 2. Gender Chart (Doughnut)
                    const ctxGender = document.getElementById('genderChart').getContext('2d');
                    this.chartsInstances.gender = new Chart(ctxGender, {
                        type: 'doughnut',
                        data: {
                            labels: ['ذكور', 'إناث'],
                            datasets: [{
                                data: [DB.charts.gender.male, DB.charts.gender.female],
                                backgroundColor: ['#3b82f6', '#ec4899'], // blue, pink
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false, cutout: '70%',
                            plugins: { legend: { position: 'bottom' } }
                        }
                    });

                    // 3. Geo Chart (Horizontal Bar)
                    const ctxGeo = document.getElementById('geoChart').getContext('2d');
                    this.chartsInstances.geo = new Chart(ctxGeo, {
                        type: 'bar',
                        data: {
                            labels: DB.charts.geo.labels,
                            datasets: [{
                                label: 'عدد الكوادر',
                                data: DB.charts.geo.data.map(v => v * modifier),
                                backgroundColor: '#6366f1', // indigo
                                borderRadius: 4,
                            }]
                        },
                        options: {
                            indexAxis: 'y', // يجعل الأعمدة أفقية (ممتاز للمقارنات الجغرافية)
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { display: false } }
                        }
                    });
                }
            }
        }
    </script>
</body>

</html>