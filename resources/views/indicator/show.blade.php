<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    {{ $indicator->code }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $indicator->title }}</h2>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500">السنة الحالية</span>
                <span
                    class="text-sm bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-1.5 px-4 rounded-full shadow-md font-semibold">
                    {{ now()->year }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">وصف المؤشر</h3>
                </div>
                <div
                    class="prose max-w-none text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50 p-4 rounded-lg">
                    {{ $indicator->description ?? 'لا يوجد وصف متاح' }}
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div
                    class="bg-white rounded-xl shadow-sm p-6 border-r-4 border-indigo-500 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">خط الأساس</p>
                            <p class="text-lg font-bold text-gray-800">
                                {{ $indicator->baseline_numeric ? number_format($indicator->baseline_numeric) . ($indicator->unit == 'percentage' ? '%' : '') : 'N/A' }}
                                <span class="text-xs text-gray-400 font-normal">
                                    ({{ $indicator->first_observation_date ? \Carbon\Carbon::parse($indicator->first_observation_date)->year : 'N/A' }})
                                </span>
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">وحدة القياس</h3>
                    </div>
                    <div>
                        {{ $indicator->unit == 'percentage' ? 'نسبة مئوية' : 'رقم' }}
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <button onclick="window.print()"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    طباعة
                </button>
                <a href="{{ route('indicator.edit', $indicator) }}"
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2 font-medium shadow-lg shadow-indigo-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    تعديل المؤشر
                </a>
            </div>


            <!-- Targets Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <div>
                            <h3 class="text-lg font-bold text-white">المستهدفات السنوية</h3>
                            <p class="text-indigo-200 text-sm">الفترة من 2022 إلى 2040</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('indicator.baselines.edit', $indicator) }}"
                            class="px-4 py-2 bg-white/10 text-white border border-white/20 rounded-lg hover:bg-white/20 transition-colors flex items-center gap-2 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            أساس القطاعات
                        </a>
                        <a href="{{ route('indicator.sectors.edit', $indicator) }}"
                            class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors flex items-center gap-2 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            ربط قطاعات
                        </a>
                        <a href="{{ route('indicator_target.edit', $indicator) }}"
                            class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-400 transition-colors flex items-center gap-2 text-sm font-medium shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            تعديل المستهدف
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if ($calculatedTargets)
                        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach ($calculatedTargets as $item)
                                @php
                                    $hasActual = !is_null($item['actual_value']);
                                    // تحديد لون الحافة بناءً على الأداء والمصدر
                                    $borderColor = 'border-gray-100';
                                    if ($hasActual) {
                                        $borderColor =
                                            $item['performance'] >= 100
                                                ? 'border-green-500 shadow-md'
                                                : 'border-amber-500 shadow-md';
                                    }
                                @endphp

                                <div
                                    class="bg-white border-2 {{ $borderColor }} rounded-xl p-4 transition-all duration-300 hover:scale-105">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-bold text-gray-500">{{ $item['year'] }}</span>
                                        @if ($hasActual)
                                            <span
                                                title="{{ $item['data_source'] == 'official' ? 'رقم معتمد من الوزارة' : 'مجموع أداء القطاعات' }}"
                                                class="text-[10px] px-2 py-0.5 rounded {{ $item['data_source'] == 'official' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                                {{ $item['data_source'] == 'official' ? 'رسمي' : 'تجميعي' }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="space-y-2">
                                        <div>
                                            <span
                                                class="text-[10px] block text-gray-400 uppercase leading-none mb-1">المستهدف</span>
                                            <span class="text-md font-bold text-indigo-700">
                                                {{ number_format($item['calculated_target'], 1) }}{{ $indicator->unit == 'percentage' ? '%' : '' }}
                                            </span>
                                        </div>

                                        <div class="pt-2 border-t border-gray-50">
                                            <span
                                                class="text-[10px] block text-gray-400 uppercase leading-none mb-1">المحقق
                                                الفعلي</span>
                                            <span
                                                class="text-md font-extrabold {{ $hasActual ? 'text-gray-800' : 'text-gray-300' }}">
                                                {{ $hasActual ? number_format($item['actual_value'], 1) : '---' }}{{ $hasActual && $indicator->unit == 'percentage' ? '%' : '' }}
                                            </span>
                                        </div>

                                        @if ($hasActual)
                                            <div class="pt-2 border-t border-gray-50 space-y-1">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-[10px] text-gray-400">الإنجاز:</span>
                                                    <span
                                                        class="text-[10px] font-bold {{ $item['performance'] >= 100 ? 'text-green-600' : 'text-amber-600' }}">
                                                        {{ number_format($item['performance'], 1) }}%
                                                    </span>
                                                </div>

                                                @php
                                                    $variance = $item['actual_value'] - $item['calculated_target'];
                                                @endphp
                                                <div class="flex justify-between items-center">
                                                    <span class="text-[10px] text-gray-400">الانحراف:</span>
                                                    <span
                                                        class="text-[10px] font-bold {{ $variance >= 0 ? 'text-green-600' : 'text-rose-600' }}">
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
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">لا توجد مستهدفات مسجلة</p>
                            <p class="text-gray-400 text-sm mt-1">الفترة المستهدفة (2022 - 2040)</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-8">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">ترتيب أداء القطاعات لعام {{ now()->year }}</h3>
                    <span class="text-xs text-gray-400 font-medium">بناءً على نسبة الإنجاز للمستهدف الخاص</span>
                </div>

                <div class="p-6">
                    <div class="space-y-6">
                        @foreach ($sectorRanking as $index => $sector)
                            <div class="relative">
                                <div class="flex justify-between items-center mb-1">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex items-center justify-center w-6 h-6 rounded-full {{ $index < 3 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }} text-xs font-bold">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="font-bold text-gray-700">{{ $sector['name'] }}</span>
                                    </div>
                                    <span
                                        class="text-sm font-bold {{ $sector['performance'] >= 100 ? 'text-green-600' : 'text-amber-600' }}">
                                        {{ number_format($sector['performance'], 1) }}%
                                    </span>
                                </div>

                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-gradient-to-r {{ $sector['performance'] >= 100 ? 'from-green-400 to-green-600' : 'from-amber-400 to-amber-600' }} h-2 rounded-full transition-all duration-500"
                                        style="width: {{ min($sector['performance'], 100) }}%">
                                    </div>
                                </div>

                                <div class="flex justify-between mt-2 text-[10px] text-gray-400">
                                    <span>المستهدف: {{ number_format($sector['target'], 1) }}</span>
                                    <span>المحقق: {{ number_format($sector['actual'] ?? 0, 1) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-12 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        دليل احتساب المؤشر والأداء
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8 text-sm text-gray-600">
                    <div>
                        <h4 class="font-bold text-indigo-900 mb-2">1. المحقق الكلي vs محقق القطاع</h4>
                        <ul class="list-disc list-inside space-y-2">
                            <li><strong class="text-gray-800">المحقق الكلي:</strong> هو الرقم النهائي الذي
                                تعلنه الوزارة ويمثل حصيلة أداء كافة القطاعات مجتمعة.</li>
                            <li><strong class="text-gray-800">محقق القطاع:</strong> هو مساهمة كل قطاع على
                                حدة (مثلاً: قطاع الأوقاف، قطاع الزكاة). يتم مقارنة محقق القطاع بالمستهدف الخاص به لتحديد
                                كفاءة الأداء التشغيلي.</li>
                        </ul>
                    </div>

                    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                        <h4 class="font-bold text-indigo-900 mb-2 italic">مثال توضيحي (نمو 5%):</h4>
                        <p>إذا كان <strong>خط الأساس (2022) = 100</strong></p>
                        <div class="mt-2 space-y-1 font-mono text-xs">
                            <p>مستهدف 2023: 100 × 1.05 = <span class="text-indigo-700 font-bold">105.00</span></p>
                            <p>مستهدف 2024: 105 × 1.05 = <span class="text-indigo-700 font-bold">110.25</span></p>
                        </div>
                        <p class="mt-3 text-xs text-gray-500">* نستخدم النمو المركب لضمان استدامة التطور السنوي.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
