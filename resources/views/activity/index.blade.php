<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white-light leading-tight">
                قائمة الأنشطة
            </h2>
            <a href="{{ url()->previous() }}"
                class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                <span>العودة للخلف</span>
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="container py-8 mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($activities as $activity)
                <div
                    class="group bg-white dark:bg-[#1b2e4b] rounded-2xl shadow-sm border border-gray-100 dark:border-[#191e3a] overflow-hidden hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center group-hover:bg-blue-600 transition-colors duration-300">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 group-hover:text-white transition-colors duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span
                                class="px-3 py-1 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">نشط</span>
                        </div>

                        <h4 class="text-lg font-bold text-gray-800 dark:text-white-light mb-2 line-clamp-2 min-h-[3.5rem]">
                            {{ $activity->title }}
                        </h4>
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            {{ $activity->project->title }}
                        </div>

                        @if($activity->employees->count() > 0)
                            <div class="mt-2 pt-2 border-t border-gray-100 dark:border-[#191e3a]">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">الموظفين المعينين:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($activity->employees as $employee)
                                        <span
                                            class="px-2 py-0.5 bg-gray-100 dark:bg-[#0e1726] text-gray-600 dark:text-gray-400 text-[10px] rounded-md">{{ $employee->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div
                        class="px-5 py-3 bg-gray-50 dark:bg-[#0e1726]/50 border-t border-gray-100 dark:border-[#191e3a] flex justify-between items-center">

                        @if ($currentStage)
                            @php
                                // نتحقق هل توجد نتائج مسجلة لهذا النشاط في هذه المرحلة
                                // بما أننا استخدمنا eager loading، التحقق سيكون سريعاً من المجموعة (Collection)
                                $assessment = $activity->assessmentResults->first();
                            @endphp

                            @if ($assessment)
                                <a href="{{ route('assessment_result.edit', $activity->id) }}"
                                    class="px-3 py-1.5 bg-white dark:bg-[#1b2e4b] border border-amber-500 text-amber-600 dark:text-amber-400 rounded-lg text-sm hover:bg-amber-50 dark:hover:bg-amber-900/20 transition">
                                    تعديل التقييم
                                </a>
                                <span class="text-[10px] text-green-600 dark:text-green-400 font-bold">
                                    {{ $currentStage->title }}
                                </span>
                            @else
                                <a href="{{ route('assessment_result.create', $activity->id) }}"
                                    class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition shadow-sm">
                                    بدء التقييم
                                </a>
                                <span class="text-[10px] text-gray-400 font-bold">
                                    {{ $currentStage->title }}</span>
                            @endif
                        @else
                            <span class="text-xs text-red-500">لا توجد دورة تقييم مفتوحة حالياً</span>
                        @endif

                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-12 text-center bg-white dark:bg-[#1b2e4b] rounded-2xl border-2 border-dashed border-gray-200 dark:border-[#191e3a]">
                    <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white-light">لا توجد أنشطة</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">لم يتم إضافة أي أنشطة لهذا المشروع بعد.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>