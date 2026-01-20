<x-app-layout>
    <div class="container py-8 mx-auto px-4 max-w-7xl">

        <div class="p-6 bg-gradient-to-r from-blue-900 to-blue-700 rounded-xl shadow-lg text-white mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold mt-1">{{ $project->indicator->title }}</h1>
                </div>
                <div class="mt-4 md:mt-0 text-center md:text-left bg-white/10 p-4 rounded-lg backdrop-blur-md">
                    <p class="text-sm opacity-80">مستهدف عام {{ $project->indicator->current_year }}</p>
                    <p class="text-3xl font-black">{{ number_format($project->indicator->target_for_indicator) }}</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-100">
                <i class="far fa-calendar-alt ml-2"></i>
                <span>دورية القياس: {{ __($project->indicator->period) }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">

                <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-800">{{ $project->title }}</h2>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">
                            مبادرة
                            تمكينية
                        </span>
                    </div>

                    <div class="p-6 space-y-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">وصف المشروع
                            </h3>
                            <p class="text-gray-700 leading-relaxed">{{ $project->description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <span class="text-xs text-gray-500 block">نسبة الإنجاز</span>
                                <div class="flex items-center mt-1">
                                    <span class="text-lg font-bold text-blue-600">{{ $completionPercentage }}%</span>
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full mr-3">
                                        <div class="bg-blue-600 h-2 rounded-full"
                                            style="width: {{ $completionPercentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <span class="text-xs text-gray-500 block">الحالة التشغيلية</span>
                                <span class="text-yellow-600 font-bold mt-1 inline-block">في الإجراء</span>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="flex gap-4">
                    <a href="{{ route('activity.create', $project->id) }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                        إضافة نشاط جديد
                    </a>
                    <a href="{{ route('project.index', $project->indicator_id) }}"
                        class="flex items-center justify-center py-2 px-4 bg-white border border-orange-500 text-orange-600 hover:bg-orange-50 text-sm font-bold rounded transition duration-150">
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        المشاريع
                    </a>
                </div>
                <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700"><i class="fas fa-tasks ml-2 text-blue-500"></i>أنشطة المشروع
                        </h3>
                        <span
                            class="text-xs bg-gray-200 px-2 py-1 rounded text-gray-600">{{ $project->activities->count() }}
                            نشاط</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase">
                                    <th class="p-4 font-semibold">النشاط</th>
                                    <th class="p-4 font-semibold">تغذية المؤشر</th>
                                    <th class="p-4 font-semibold">الحالة</th>
                                    <th class="p-4 font-semibold">{{ __('العمليات') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($project->activities as $activity)
                                    <tr class="hover:bg-blue-50/30 transition-colors">
                                        <td class="p-4 font-medium text-gray-800">{{ $activity->title }}</td>
                                        <td class="p-4">
                                            @if ($activity->is_feed_indicator)
                                                <span class="text-green-600 text-xs flex items-center">
                                                    <i class="fas fa-link ml-1"></i> يغذي المؤشر بشكل مباشر
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs flex items-center">
                                                    <i class="fas fa-info-circle ml-1"></i> لايغذي المؤشر بشكل مباشر
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-xs text-gray-500 italic">
                                            @if($activity->workflowInstance)
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                    {{ $activity->workflowInstance->workflow->name }}
                                                    {{ $activity->currentStage ? ' - ' . $activity->currentStage->name : '' }}
                                                </span>
                                            @else
                                                <span
                                                    class="bg-gray-100 text-gray-800 px-2 py-1 rounded">{{ __('not_started') }}</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if($activity->workflowInstance)
                                                <div class="flex items-center gap-2">
                                                    @if($activity->workflowInstance->status === 'draft')
                                                        <form action="{{ route('workflow.submit', $activity->id) }}" method="POST"
                                                            class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded flex items-center"
                                                                onclick="return confirm('{{ __('هل أنت متأكد من إرسال النشاط؟') }}')">
                                                                <i class="fas fa-paper-plane ml-1"></i>
                                                                {{ __('إرسال') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('workflow.history', $activity->id) }}"
                                                        class="text-indigo-600 hover:text-indigo-800 text-xs underline">
                                                        {{ __('عرض السجل') }}
                                                    </a>
                                                </div>
                                            @else
                                                <form action="{{ route('workflow.start', $activity->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-3 py-1 rounded flex items-center">
                                                        <i class="fas fa-play ml-1"></i>
                                                        {{ __('بدء سير العمل') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-8 text-center text-gray-400 italic">لا توجد أنشطة
                                            مضافة لهذا المشروع</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="space-y-6">
                <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-800 border-b pb-4 mb-4 flex items-center">
                        <i class="fas fa-id-badge ml-2 text-blue-600"></i> الجهات المالكة
                    </h3>
                    <div class="space-y-3">
                        {{ $project->executor->title }}
                    </div>
                </section>


            </div>
        </div>
    </div>
</x-app-layout>