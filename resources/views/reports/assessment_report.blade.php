<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800">تقرير الأداء السنوي</h2>
                <div
                    class="flex items-center gap-2 text-xs font-medium text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    المرحلة المختارة: {{ $currentAssessmentStage->title }}
                </div>
            </div>

            <div class="flex flex-wrap gap-2 items-center border-b border-slate-100 pb-4">
                <span class="text-[10px] font-bold text-slate-400 ml-2 uppercase tracking-tighter">اختر الدورة:</span>
                @foreach ($assessmentStages as $stage)
                    <a href="{{ route('project_assessment_report', $stage->id) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 
                       {{ $currentAssessmentStage->id == $stage->id
                           ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100'
                           : 'bg-white text-slate-600 border border-slate-200 hover:border-indigo-300 hover:text-indigo-600' }}">
                        {{ $stage->title }}
                    </a>
                @endforeach
            </div>
        </div>
    </x-slot>

    <div class="container py-8 mx-auto px-4 max-w-5xl">
        @if ($reportData->isEmpty() || $reportData->sum('total_score') == 0)
            <div class="bg-white rounded-2xl p-16 text-center border border-slate-100 shadow-sm">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-slate-800 font-bold">لا توجد تقييمات مسجلة</h3>
                <p class="text-slate-400 text-sm mt-1">لم يتم العثور على أي نتائج تقييم لهذه المرحلة:
                    {{ $currentAssessmentStage->title }}</p>
            </div>
        @else
            <div class="space-y-10">
                @foreach ($reportData as $project)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">

                        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">{{ $project['project_title'] }}</h3>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">النقاط الكلية:
                                        {{ $project['total_score'] }} / {{ $project['max_score'] }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-3xl font-light text-indigo-600">%{{ $project['total_percentage'] }}</span>
                            </div>
                        </div>

                        <div class="px-6 py-1 bg-slate-100/30">
                            <div class="w-full bg-slate-200 h-1 rounded-full overflow-hidden">
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
                                                class="text-sm text-slate-600 font-medium">{{ $activity['title'] }}</span>

                                            <span
                                                class="text-sm font-bold text-slate-800">%{{ $activity['percentage'] }}</span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                                            <div class="h-full bg-slate-400"
                                                style="width: {{ $activity['percentage'] }}%"></div>
                                        </div>
                                        <a href="{{ route('assessment_result.show', [$activity['id'], $currentAssessmentStage->id]) }}"
                                            class="text-[10px] font-bold text-indigo-500 hover:text-indigo-700 transition uppercase tracking-tighter">
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
