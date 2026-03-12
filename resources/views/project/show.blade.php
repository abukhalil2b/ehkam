<x-app-layout>
<div class="min-h-screen bg-gray-50" dir="rtl">
<div class="max-w-7xl mx-auto px-4 py-8 space-y-6">

    {{-- ── Shared project header + timeline partial ── --}}
    @include('project.partials.project-header', [
        'backRoute' => route('project.index', $project->indicator_id),
        'backLabel' => 'المشاريع',
    ])

    {{-- ================================================================
         ACTION BAR
         ================================================================ --}}
    @if(auth()->check())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider ml-2">الإجراءات:</span>

            {{-- Creator: add activity --}}
            @if(in_array($project->status, ['draft', 'returned']))
                <a href="{{ route('activity.create', $project->id) }}"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2 px-4 rounded-xl transition">
                    <i class="fas fa-plus text-xs"></i> إضافة نشاط
                </a>
            @endif

            {{-- Approver: approve / return / reject --}}
            @can('approve', $project)
                <form id="review-form" method="POST" class="contents">
                    @csrf
                    <button type="submit" formaction="{{ route('project.approve', $project->id) }}"
                        onclick="return confirm('اعتماد المشروع؟')"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2 px-4 rounded-xl transition shadow-sm">
                        <i class="fas fa-check text-xs"></i> اعتماد
                    </button>
                    <button type="submit" formaction="{{ route('project.return', $project->id) }}"
                        onclick="return confirm('إعادة المشروع للتعديل؟ سيتم إرسال الملاحظات.')"
                        class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold py-2 px-4 rounded-xl transition shadow-sm">
                        <i class="fas fa-undo text-xs"></i> إعادة للتعديل
                    </button>
                    <button type="submit" formaction="{{ route('project.reject', $project->id) }}"
                        onclick="return confirm('رفض المشروع نهائياً؟ هذا الإجراء لا يمكن التراجع عنه.')"
                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded-xl transition shadow-sm">
                        <i class="fas fa-times text-xs"></i> رفض
                    </button>
                </form>
            @endcan

            {{-- Edit project --}}
            @if(in_array($project->status, ['draft','returned']))
                <a href="{{ route('project.edit', $project->id) }}"
                    class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold py-2 px-4 rounded-xl transition mr-auto">
                    <i class="fas fa-pen text-xs"></i> تعديل المشروع
                </a>
            @endif
        </div>
    </div>
    @endif

    {{-- ================================================================
         ACTIVITIES & STEPS
         ================================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-layer-group text-indigo-400"></i> الأنشطة والخطوات
            </h2>
            <div class="flex items-center gap-3 text-sm">
                <span class="text-gray-400">{{ $project->activities->count() }} نشاط</span>
                <span class="text-gray-300">|</span>
                <span class="text-blue-600 font-semibold">{{ $completionPercentage }}% إنجاز</span>
                <span class="text-gray-400 text-xs">({{ $stepsDoneCount }}/{{ $totalSteps }} خطوة)</span>
            </div>
        </div>

        <div class="p-4 space-y-4">
            @forelse($project->activities as $activity)
                <div class="border border-gray-100 rounded-xl overflow-hidden">
                    {{-- Activity header --}}
                    <div class="bg-gray-50 px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
                            <h3 class="font-bold text-gray-800 text-sm">{{ $activity->title }}</h3>
                            @if ($activity->is_feed_indicator)
                                <span class="text-xs text-green-700 bg-green-50 border border-green-100 px-2 py-0.5 rounded-full flex items-center gap-1">
                                    <i class="fas fa-link text-[10px]"></i> يغذي المؤشر
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400">{{ $activity->steps->count() }} خطوة</span>
                    </div>

                    {{-- Steps --}}
                    @if($activity->steps->count() > 0)
                        <div class="divide-y divide-gray-50">
                            @foreach($activity->steps as $step)
                                @php
                                    $stepStatusColors = [
                                        'draft'       => 'bg-gray-100 text-gray-500',
                                        'review'      => 'bg-blue-100 text-blue-600',
                                        'approved'    => 'bg-green-100 text-green-700',
                                        'in_progress' => 'bg-indigo-100 text-indigo-700',
                                        'completed'   => 'bg-emerald-100 text-emerald-700',
                                        'returned'    => 'bg-yellow-100 text-yellow-700',
                                    ];
                                    $stepColor = $stepStatusColors[$step->status] ?? 'bg-gray-100 text-gray-500';
                                @endphp
                                <div class="px-4 py-3 hover:bg-gray-50/80 transition flex flex-col md:flex-row md:items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <a href="{{ route('step.show', $step->id) }}"
                                                class="font-semibold text-gray-800 text-sm hover:text-indigo-600 transition truncate">
                                                {{ $step->name }}
                                            </a>
                                            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $stepColor }}">
                                                {{ __($step->status) }}
                                            </span>
                                            @if($step->is_need_evidence_file)
                                                <span class="text-xs text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">📎دليل داعم</span>
                                            @endif
                                        </div>
                                        <div class="mt-1.5 flex flex-wrap gap-3 text-xs text-gray-400">
                                            @if($step->start_date || $step->end_date)
                                                <span class="flex items-center gap-1">
                                                    <i class="far fa-calendar text-[10px]"></i>
                                                    {{ $step->start_date?->format('Y-m-d') ?? '—' }}
                                                    ←
                                                    {{ $step->end_date?->format('Y-m-d') ?? '—' }}
                                                </span>
                                            @endif
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-bullseye text-[10px]"></i>
                                                مستهدف: {{ $step->target_percentage }}%
                                            </span>
                                            <span class="bg-gray-100 px-1.5 rounded text-gray-500">{{ __($step->phase) }}</span>
                                        </div>

                                        {{-- Previous feedbacks --}}
                                        @if($step->feedbacks && $step->feedbacks->count() > 0)
                                            <div class="mt-2 bg-red-50 border border-red-100 rounded-lg px-3 py-2 text-xs">
                                                <span class="font-bold text-red-600">ملاحظات سابقة:</span>
                                                <ul class="mt-1 space-y-0.5 text-gray-600 list-disc list-inside">
                                                    @foreach($step->feedbacks as $fb)
                                                        <li>{{ $fb->notes }}
                                                            <span class="text-gray-400">({{ $fb->created_at->format('Y-m-d') }})</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Reviewer notes textarea --}}
                                    @can('approve', $project)
                                        @if($project->status === 'submitted')
                                            <div class="w-full md:w-56 shrink-0">
                                                <label class="block text-xs text-gray-500 mb-1">ملاحظة على هذه الخطوة:</label>
                                                <textarea name="step_feedbacks[{{ $step->id }}]" form="review-form" rows="2"
                                                    class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:ring-1 focus:ring-blue-400 resize-none"
                                                    placeholder="ملاحظة (اختياري)..."></textarea>
                                            </div>
                                        @endif
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-4 py-3 text-xs text-gray-400 italic">لا توجد خطوات لهذا النشاط.</div>
                    @endif
                </div>
            @empty
                <div class="py-16 text-center">
                    <div class="text-5xl mb-3 opacity-30">📋</div>
                    <p class="text-gray-400 italic text-sm">لا توجد أنشطة مضافة لهذا المشروع.</p>
                    @if(in_array($project->status, ['draft', 'returned']))
                        <a href="{{ route('activity.create', $project->id) }}"
                            class="mt-4 inline-flex items-center gap-2 bg-indigo-600 text-white text-sm font-semibold py-2 px-5 rounded-xl hover:bg-indigo-700 transition">
                            <i class="fas fa-plus text-xs"></i> إضافة أول نشاط
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

</div>
</div>
</x-app-layout>