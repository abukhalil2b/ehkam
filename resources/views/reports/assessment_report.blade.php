<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white-light">تقرير الأداء السنوي</h2>
                <div
                    class="flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-[#1b2e4b] px-3 py-1 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    المرحلة المختارة: {{ $currentAssessmentStage->title }}
                </div>
            </div>

            <div class="flex flex-wrap gap-2 items-center border-b border-slate-100 dark:border-[#191e3a] pb-4">
                <span
                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 ml-2 uppercase tracking-tighter">اختر
                    الدورة:</span>
                @foreach ($assessmentStages as $stage)
                            <a href="{{ route('project_assessment_report', $stage->id) }}"
                                class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 
                                   {{ $currentAssessmentStage->id == $stage->id
                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100 dark:shadow-none'
                    : 'bg-white dark:bg-[#1b2e4b] text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-[#191e3a] hover:border-indigo-300 dark:hover:border-indigo-700 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
                                {{ $stage->title }}
                            </a>
                @endforeach
            </div>
        </div>
    </x-slot>

    <div class="container py-8 mx-auto px-4 max-w-5xl">
        @if ($reportData->isEmpty() || $reportData->sum('total_score') == 0)
            <div
                class="bg-white dark:bg-[#1b2e4b] rounded-2xl p-16 text-center border border-slate-100 dark:border-[#191e3a] shadow-sm">
                <div
                    class="w-16 h-16 bg-slate-50 dark:bg-[#0e1726] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-slate-800 dark:text-white-light font-bold">لا توجد تقييمات مسجلة</h3>
                <p class="text-slate-400 text-sm mt-1">لم يتم العثور على أي نتائج تقييم لهذه المرحلة:
                    {{ $currentAssessmentStage->title }}
                </p>
            </div>
        @else
            <div class="space-y-10">
                @foreach ($reportData as $project)
                    <div
                        class="bg-white dark:bg-[#1b2e4b] rounded-2xl border border-slate-200 dark:border-[#191e3a] overflow-hidden shadow-sm">

                        <div
                            class="p-6 border-b border-slate-50 dark:border-[#191e3a] flex justify-between items-center bg-slate-50/50 dark:bg-[#0e1726]/30">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 dark:text-white-light">
                                    {{ $project['project_title'] }}</h3>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">النقاط الكلية:
                                        {{ $project['total_score'] }} / {{ $project['max_score'] }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-3xl font-light text-indigo-600 dark:text-indigo-400">%{{ $project['total_percentage'] }}</span>
                            </div>
                        </div>

                        <div class="px-6 py-1 bg-slate-100/30 dark:bg-[#0e1726]/50">
                            <div class="w-full bg-slate-200 dark:bg-[#0e1726] h-1 rounded-full overflow-hidden">
                                <div class="bg-indigo-500 h-full" style="width: {{ $project['total_percentage'] }}%">
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                                @foreach ($project['activities'] as $activity)
                                    <div class="flex flex-col gap-1">
                                        <div class="flex justify-between items-center">
                                            <span
                                                class="text-sm text-slate-600 dark:text-slate-400 font-medium">{{ $activity['title'] }}</span>

                                            <span
                                                class="text-sm font-bold text-slate-800 dark:text-white-light">%{{ $activity['percentage'] }}</span>
                                        </div>
                                        <div class="w-full bg-slate-100 dark:bg-[#0e1726] h-1 rounded-full overflow-hidden">
                                            <div class="h-full bg-slate-400 dark:bg-slate-600"
                                                style="width: {{ $activity['percentage'] }}%"></div>
                                        </div>
                                        <a href="{{ route('assessment_result.show', [$activity['id'], $currentAssessmentStage->id]) }}"
                                            class="text-[10px] font-bold text-indigo-500 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition uppercase tracking-tighter">
                                            عرض التفاصيل ←
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>