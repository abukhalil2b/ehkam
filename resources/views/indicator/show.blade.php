<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    {{ $indicator->code }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $indicator->title }}</h2>
                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($indicator->description, 100) }}</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('indicator.edit', $indicator) }}" class="px-4 py-2 bg-white text-gray-700 border rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    تعديل
                </a>
                <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2 text-sm font-medium shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    طباعة
                </button>
            </div>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-bold text-gray-800">ملخص الأداء لعام {{ $currentYear }}</h3>
                <span class="text-xs font-semibold px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full">وحدة القياس: {{ $indicator->unit == 'percentage' ? 'نسبة مئوية %' : 'رقم' }}</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-5 border-b-4 border-blue-500">
                    <p class="text-sm text-gray-500 mb-1">المستهدف التراكمي للعام</p>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-black text-gray-800">
                            {{ $currentYearKPI ? number_format($currentYearKPI['calculated_target'], 2) : '---' }}
                        </span>
                        <span class="text-sm font-bold text-blue-500 mb-1">{{ $indicator->unit == 'percentage' ? '%' : '' }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-5 border-b-4 border-purple-500">
                    <p class="text-sm text-gray-500 mb-1">إجمالي المحقق الفعلي</p>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-black text-gray-800">
                            {{ $currentYearKPI && $currentYearKPI['actual_value'] !== null ? number_format($currentYearKPI['actual_value'], 2) : '---' }}
                        </span>
                        <span class="text-sm font-bold text-purple-500 mb-1">{{ $indicator->unit == 'percentage' ? '%' : '' }}</span>
                    </div>
                </div>

                @php
                    $perf = $currentYearKPI['performance'] ?? 0;
                    $perfColor = $perf >= 100 ? 'text-green-500' : ($perf >= 80 ? 'text-yellow-500' : 'text-red-500');
                    $perfBorder = $perf >= 100 ? 'border-green-500' : ($perf >= 80 ? 'border-yellow-500' : 'border-red-500');
                @endphp
                <div class="bg-white rounded-xl shadow-sm p-5 border-b-4 {{ $perfBorder }}">
                    <p class="text-sm text-gray-500 mb-1">نسبة الإنجاز (الأداء)</p>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-black {{ $perfColor }}">
                            {{ $currentYearKPI && $currentYearKPI['actual_value'] !== null ? number_format($perf, 1) . '%' : '---' }}
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-5 border-b-4 border-gray-400">
                    <p class="text-sm text-gray-500 mb-1">خط الأساس (نقطة الانطلاق)</p>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-black text-gray-700">
                            {{ $indicator->baseline_numeric ? number_format($indicator->baseline_numeric) : '---' }}
                        </span>
                        <span class="text-xs font-bold text-gray-400 mb-1">سنة {{ $indicator->baseline_year ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-md font-bold text-gray-800 mb-4">مسار الأداء العام (المستهدف مقابل المحقق)</h3>
                    <div class="relative h-72 w-full">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-md font-bold text-gray-800 mb-4">مساهمة القطاعات ({{ $currentYear }})</h3>
                    <div class="relative h-64 w-full flex justify-center">
                        <canvas id="pieChart"></canvas>
                    </div>
                    @if($sectorRanking->sum('actual') == 0)
                        <p class="text-center text-xs text-gray-400 mt-2">لا توجد بيانات محققة لعرضها</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-md font-bold text-gray-800 mb-4">الأداء التفصيلي حسب الفترات ({{ $currentYear }})</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-md font-bold text-gray-800 mb-4">ترتيب أداء القطاعات ({{ $currentYear }})</h3>
                    <div class="space-y-4 max-h-64 overflow-y-auto pr-2">
                        @foreach ($sectorRanking as $index => $sector)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold {{ $index < 3 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-sm font-bold text-gray-700">{{ $sector['name'] }}</span>
                                    </div>
                                    <span class="text-xs font-bold {{ $sector['performance'] >= 100 ? 'text-green-600' : 'text-yellow-600' }}">
                                        {{ number_format($sector['performance'], 1) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-gradient-to-r {{ $sector['performance'] >= 100 ? 'from-green-400 to-green-500' : 'from-yellow-400 to-yellow-500' }} h-1.5 rounded-full transition-all"
                                        style="width: {{ min($sector['performance'], 100) }}%">
                                    </div>
                                </div>
                                <div class="flex justify-between mt-1 text-[9px] text-gray-400">
                                    <span>المستهدف: {{ number_format($sector['target'], 1) }}</span>
                                    <span>المحقق: {{ number_format($sector['actual'] ?? 0, 1) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($notesLog->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-yellow-200 p-5 border-r-4 border-r-yellow-400">
                <h3 class="text-md font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    سجل الملاحظات ومبررات الأداء لعام {{ $currentYear }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($notesLog as $note)
                        <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-100">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-bold text-gray-800">{{ $note->sector->name ?? 'قطاع غير معروف' }}</span>
                                <span class="text-[10px] bg-white px-2 py-0.5 rounded shadow-sm text-gray-500">الفترة {{ $note->period_index }}</span>
                            </div>
                            <p class="text-sm text-gray-700 italic">"{{ $note->notes }}"</p>
                            <div class="text-left mt-2 text-[10px] text-gray-400">
                                القيمة المحققة وقت الملاحظة: <strong class="text-gray-600">{{ $note->achieved_value }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-8">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <div>
                            <h3 class="text-lg font-bold text-white">المستهدفات السنوية التراكمية</h3>
                            <p class="text-indigo-200 text-sm">التفاصيل الكاملة من خط الأساس إلى 2040</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('indicator.sectors.edit', $indicator) }}"
                            class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors flex items-center gap-2 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            ربط قطاعات
                        </a>
                        <a href="{{ route('indicator.baselines.edit', $indicator) }}"
                            class="px-4 py-2 bg-white/10 text-white border border-white/20 rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            أساس القطاعات
                        </a>
                        <a href="{{ route('indicator_target.edit', $indicator) }}"
                            class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-400 transition-colors flex items-center gap-2 text-sm font-medium shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            تعديل نسبة المستهدف
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    @if ($calculatedTargets && $calculatedTargets->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($calculatedTargets as $item)
                                @php
                                    $hasActual = !is_null($item['actual_value']);
                                    $borderColor = 'border-gray-100';
                                    if ($hasActual) {
                                        $borderColor = $item['performance'] >= 100 ? 'border-green-500 shadow-md' : 'border-amber-500 shadow-md';
                                    }
                                @endphp

                                <div class="bg-white border-2 {{ $borderColor }} rounded-xl p-4 transition-all duration-300 hover:scale-105">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-bold text-gray-500">{{ $item['year'] }}</span>
                                        @if ($hasActual)
                                            <span title="{{ $item['data_source'] == 'official' ? 'رقم معتمد من الوزارة' : 'مجموع أداء القطاعات' }}"
                                                class="text-[10px] px-2 py-0.5 rounded {{ $item['data_source'] == 'official' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                                {{ $item['data_source'] == 'official' ? 'رسمي' : 'تجميعي' }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="space-y-2">
                                        <div>
                                            <div class="text-[10px] text-gray-400 leading-none mb-1">المستهدف
                                                <span>بنسبة: {{ $item['target_increment'] }}</span>%</div>

                                            <span class="text-md font-bold text-indigo-700">
                                                {{ number_format($item['calculated_target'], 1) }}{{ $indicator->unit == 'percentage' ? '%' : '' }}
                                            </span>
                                        </div>

                                        <div class="pt-2 border-t border-gray-50">
                                            <span class="text-[10px] block text-gray-400 uppercase leading-none mb-1">المحقق الفعلي</span>
                                            <span class="text-md font-extrabold {{ $hasActual ? 'text-gray-800' : 'text-gray-300' }}">
                                                {{ $hasActual ? number_format($item['actual_value'], 1) : '---' }}{{ $hasActual && $indicator->unit == 'percentage' ? '%' : '' }}
                                            </span>
                                        </div>

                                        @if ($hasActual)
                                            <div class="pt-2 border-t border-gray-50 space-y-1">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-[10px] text-gray-400">الإنجاز:</span>
                                                    <span class="text-[10px] font-bold {{ $item['performance'] >= 100 ? 'text-green-600' : 'text-amber-600' }}">
                                                        {{ number_format($item['performance'], 1) }}%
                                                    </span>
                                                </div>

                                                @php
                                                    $variance = $item['actual_value'] - $item['calculated_target'];
                                                @endphp
                                                <div class="flex justify-between items-center">
                                                    <span class="text-[10px] text-gray-400">الانحراف:</span>
                                                    <span class="text-[10px] font-bold {{ $variance >= 0 ? 'text-green-600' : 'text-rose-600' }}">
                                                        {{ ($variance > 0 ? '+' : '') . number_format($variance, 1) }}{{ $indicator->unit == 'percentage' ? '%' : '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">لا توجد مستهدفات مسجلة</p>
                            <p class="text-gray-400 text-sm mt-1">الفترة المستهدفة (2025 - 2040)</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        دليل احتساب المؤشر والأداء
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8 text-sm text-gray-600">
                    <div>
                        <h4 class="font-bold text-indigo-900 mb-2">1. المحقق الكلي vs محقق القطاع</h4>
                        <ul class="list-disc list-inside space-y-2 mb-6">
                            <li><strong class="text-gray-800">المحقق الكلي:</strong> هو الرقم النهائي الذي تعلنه الوزارة ويمثل حصيلة أداء كافة القطاعات مجتمعة.</li>
                            <li><strong class="text-gray-800">محقق القطاع:</strong> هو مساهمة كل قطاع على حدة. يتم مقارنة محقق القطاع بالمستهدف الخاص به لتحديد كفاءة الأداء التشغيلي.</li>
                        </ul>

                        <h4 class="font-bold text-indigo-900 mb-2">2. المعادلة المعتمدة للنمو التراكمي</h4>
                        <p class="mb-4">يتم حساب المستهدف التراكمي بناءً على خط الأساس باستخدام معادلة النمو المركب لضمان استدامة التطور سنة تلو الأخرى:</p>
                        <div class="bg-white p-4 rounded-lg border border-gray-200 text-center mb-4 overflow-x-auto text-xl font-mono text-indigo-900 flex justify-center items-center gap-2" dir="ltr">
    <span>V<sub>target</sub></span>
    <span>=</span>
    <span>V<sub>previous</sub></span>
    <span>&times;</span>
    <span>( 1 + <span class="inline-flex flex-col text-center align-middle text-sm"><span class="border-b-2 border-indigo-900 pb-0.5">R</span><span class="pt-0.5">100</span></span> )</span>
</div>
                        <ul class="list-disc list-inside space-y-2 text-sm text-gray-600 font-medium">
    <li>
        <span class="text-indigo-600 font-bold font-mono text-base" dir="ltr">V<sub>target</sub></span> : القيمة المستهدفة للعام الحالي.
    </li>
    <li>
        <span class="text-indigo-600 font-bold font-mono text-base" dir="ltr">V<sub>previous</sub></span> : قيمة العام السابق (العوائد في العام السابق أو خط الأساس).
    </li>
    <li>
        <span class="text-indigo-600 font-bold font-mono text-base" dir="ltr">R</span> : نسبة المستهدف للعام الحالي.
    </li>
</ul>
                    </div>

                    <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 flex flex-col justify-center">
                        <h4 class="font-bold text-indigo-900 mb-3 italic">مثال توضيحي عملي (نمو 5%):</h4>
                        <p class="text-gray-700">إذا كانت <strong>قيمة العوائد في العام السابق (أو خط الأساس 2025) = 100,000</strong> ونسبة النمو المستهدفة <strong>5%</strong>:</p>
                        <div class="mt-4 space-y-3 font-mono text-sm bg-white p-4 rounded border border-indigo-200" dir="ltr">
                            <p class="text-left"><span class="text-gray-500">Year 2026:</span><br> 100,000 &times; (1 + 0.05) = <span class="text-indigo-700 font-bold">105,000</span></p>
                            <p class="text-left"><span class="text-gray-500">Year 2027:</span><br> 105,000 &times; (1 + 0.05) = <span class="text-indigo-700 font-bold">110,250</span></p>
                        </div>
                        <p class="mt-4 text-xs text-gray-500 font-bold">* تلاحظ أن النمو في 2027 تم احتسابه بناءً على نتيجة 2026 وليس خط الأساس الأولي، وهذا هو جوهر "النمو التراكمي".</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            Chart.defaults.font.family = 'Tajawal, sans-serif';

            // 1. المخطط الخطي (الزمني)
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            const trendData = @json($calculatedTargets);
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.year),
                    datasets: [
                        {
                            label: 'المستهدف',
                            data: trendData.map(d => d.calculated_target),
                            borderColor: '#3b82f6', // blue-500
                            borderDash: [5, 5],
                            tension: 0.3
                        },
                        {
                            label: 'المحقق',
                            data: trendData.map(d => d.actual_value),
                            borderColor: '#8b5cf6', // purple-500
                            borderWidth: 3,
                            tension: 0.3,
                            spanGaps: true
                        }
                    ]
                },
                options: { maintainAspectRatio: false }
            });

            // 2. المخطط الدائري (مساهمة القطاعات)
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            const sectorsData = @json($sectorRanking->filter(fn($s) => $s['actual'] > 0)->values());
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: sectorsData.map(s => s.name),
                    datasets: [{
                        data: sectorsData.map(s => s.actual),
                        backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                        borderWidth: 0
                    }]
                },
                options: { 
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: { legend: { position: 'right' } }
                }
            });

            // 3. المخطط الشريطي (أداء الفترات)
            const barCtx = document.getElementById('barChart').getContext('2d');
            const periodData = @json($periodBreakdown);
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(periodData),
                    datasets: [{
                        label: 'القيمة المحققة',
                        data: Object.values(periodData),
                        backgroundColor: '#8b5cf6', // purple-500
                        borderRadius: 4
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });

        });
    </script>
</x-app-layout>