<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" dir="rtl">
        
        <div class="flex justify-between items-center mb-6 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">الموقف التنفيذي للخطة السنوية - {{ $year ?? '2026' }}</h1>
                <p class="text-gray-500 mt-2">عرض حالة المبادرات والمشاريع المرتبطة بمؤشرات الأداء ورؤية عُمان 2040.</p>
            </div>
            <div>
                <a href="#" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    تصدير إكسل
                </a>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg border border-gray-200" x-data="{ expandedRow: null }">
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 text-sm">
                            <th class="p-4 font-semibold w-12 text-center">التفاصيل</th>
                            <th class="p-4 font-semibold min-w-[200px]">المشروع / المبادرة</th>
                            <th class="p-4 font-semibold min-w-[200px]">محور الرؤية / الأولوية</th>
                            <th class="p-4 font-semibold">الهدف (المؤشر)</th>
                            <th class="p-4 font-semibold text-center">المستهدف</th>
                            <th class="p-4 font-semibold text-center">المتحقق</th>
                            <th class="p-4 font-semibold text-center">نسبة الإنجاز</th>
                            <th class="p-4 font-semibold text-center">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($projects as $project)
                            @php
                                $indicator = $project->indicator;
                                $priority = $indicator?->visionItem;
                                $pillar = $priority?->parent;
                                
                                $targetValue = $indicator?->targets->first()?->target_value ?? 0;
                                $achievedValue = $indicator?->achieved->first()?->achieved_value ?? 0;
                                $unit = $indicator?->unit === 'percentage' ? '%' : '';
                            @endphp
                            
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition duration-150">
                                <td class="p-4 text-center">
                                    <button @click="expandedRow === {{ $project->id }} ? expandedRow = null : expandedRow = {{ $project->id }}" 
                                            class="text-indigo-500 hover:text-indigo-700 transition transform focus:outline-none"
                                            :class="expandedRow === {{ $project->id }} ? 'rotate-180' : ''">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="p-4">
                                    <span class="font-bold text-gray-800 block">{{ $project->title }}</span>
                                    <span class="text-xs text-gray-500 mt-1">{{ $project->cate ?? 'غير مصنف' }}</span>
                                </td>
                                <td class="p-4 text-gray-600">
                                    <strong class="block text-gray-800">{{ $pillar->name ?? '---' }}</strong>
                                    <span class="text-xs">{{ $priority->name ?? '---' }}</span>
                                </td>
                                <td class="p-4 text-gray-600">
                                    {{ $indicator?->title ?? '---' }}
                                </td>
                                <td class="p-4 text-center font-semibold text-indigo-600" dir="ltr">
                                    {{ number_format($targetValue, 2) }}{{ $unit }}
                                </td>
                                <td class="p-4 text-center font-semibold text-emerald-600" dir="ltr">
                                    {{ number_format($achievedValue, 2) }}{{ $unit }}
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold" dir="ltr">
                                        {{ $project->completion_percentage ?? 0 }}%
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    @if($project->status == 'approved')
                                        <span class="text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded text-xs font-medium border border-emerald-200">معتمد</span>
                                    @else
                                        <span class="text-amber-700 bg-amber-50 px-2.5 py-1 rounded text-xs font-medium border border-amber-200">قيد التنفيذ</span>
                                    @endif
                                </td>
                            </tr>

                            <tr x-show="expandedRow === {{ $project->id }}" x-collapse x-cloak>
                                <td colspan="8" class="bg-gray-50/50 p-6 border-b border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="bg-white p-5 rounded-lg border border-gray-100 shadow-sm">
                                            <h4 class="font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2 flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                تفاصيل إضافية
                                            </h4>
                                            <div class="space-y-3">
                                                <p class="text-sm text-gray-600 flex justify-between"><span>الموقع الجغرافي:</span> <span class="font-medium text-gray-800">{{ $project->location ?? 'غير محدد' }}</span></p>
                                                <p class="text-sm text-gray-600 flex justify-between"><span>تاريخ البدء:</span> <span class="font-medium text-gray-800">{{ $project->start_date ?? 'غير محدد' }}</span></p>
                                                <p class="text-sm text-gray-600 flex justify-between"><span>الموازنة المصروفة:</span> <span class="font-medium text-gray-800" dir="ltr">{{ number_format($project->spent_budget, 2) }} ر.ع</span></p>
                                                <p class="text-sm text-gray-600 flex justify-between"><span>نوع الموازنة:</span> <span class="font-medium text-gray-800">{{ $project->budget_type ?? '---' }}</span></p>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-white p-5 rounded-lg border border-gray-100 shadow-sm md:col-span-2">
                                            <h4 class="font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2 flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                التقييم النوعي وملاحظات الجهة
                                            </h4>
                                            <div class="space-y-4">
                                                <div>
                                                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">التقييم النوعي</span>
                                                    <p class="text-sm text-gray-700 leading-relaxed text-justify mt-1">
                                                        {{ $project->qualitative_evaluation ?? 'لا يوجد تقييم نوعي مسجل.' }}
                                                    </p>
                                                </div>
                                                @if($project->evaluation_notes)
                                                <div class="pt-3 border-t border-gray-50">
                                                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">ملاحظات إضافية</span>
                                                    <p class="text-sm text-gray-700 leading-relaxed mt-1">
                                                        {{ $project->evaluation_notes }}
                                                    </p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-12 text-center text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    لا توجد مشاريع أو مبادرات مسجلة للخطة السنوية المحددة.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>