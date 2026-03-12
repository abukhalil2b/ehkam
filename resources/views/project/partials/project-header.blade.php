{{--
Partial: project/partials/project-header.blade.php
Required variables: $project (with ->indicator loaded)
Optional: $completionPercentage, $stepsDoneCount, $totalSteps
Optional: $backRoute (string, default: project.index), $backLabel
--}}
@php
    $statusConfig = [
        'draft' => ['label' => 'مسودة', 'bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'dot' => 'bg-gray-400', 'bar' => 'from-gray-300 to-gray-400'],
        'submitted' => ['label' => 'قيد المراجعة', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500', 'bar' => 'from-blue-400 to-indigo-500'],
        'approved' => ['label' => 'معتمد', 'bg' => 'bg-green-100', 'text' => 'text-green-700', 'dot' => 'bg-green-500', 'bar' => 'from-green-400 to-emerald-500'],
        'returned' => ['label' => 'معاد للتعديل', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'dot' => 'bg-yellow-500', 'bar' => 'from-yellow-400 to-orange-400'],
        'rejected' => ['label' => 'مرفوض', 'bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-500', 'bar' => 'from-red-400 to-rose-500'],
    ];
    $sc = $statusConfig[$project->status] ?? $statusConfig['draft'];

    $completionPercentage = $completionPercentage ?? 0;
    $stepsDoneCount = $stepsDoneCount ?? 0;
    $totalSteps = $totalSteps ?? 0;

    $backRoute = $backRoute ?? route('project.index', $project->indicator_id);
    $backLabel = $backLabel ?? 'المشاريع';
@endphp

{{-- ── PROJECT HEADER CARD ── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    {{-- Status colour bar --}}
    <div class="h-1.5 w-full bg-gradient-to-l {{ $sc['bar'] }}"></div>

    <div class="p-6">
        <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
            {{-- Title + status badge --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span
                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full {{ $sc['bg'] }} {{ $sc['text'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }} inline-block"></span>
                        {{ $sc['label'] }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $project->title }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    <span class="text-gray-400">المؤشر:</span>
                    {{ $project->indicator->title }}
                </p>
            </div>

            {{-- Back button --}}
            <a href="{{ $backRoute }}"
                class="shrink-0 flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 border border-gray-200 rounded-xl px-4 py-2 hover:bg-gray-50 transition">
                <i class="fas fa-arrow-right text-xs"></i> {{ $backLabel }}
            </a>
        </div>

        {{-- Meta chips --}}
        <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600 border-t border-gray-100 pt-4">
            <span class="flex items-center gap-1.5">
                <i class="far fa-calendar-alt text-blue-400 text-xs"></i>
                <span class="text-gray-400">دورية القياس:</span> {{ __($project->indicator->period) }}
            </span>
            <span class="flex items-center gap-1.5">
                <i class="fas fa-bullseye text-blue-400 text-xs"></i>
                <span class="text-gray-400">المستهدف {{ $project->indicator->current_year }}:</span>
                <strong class="text-gray-800">{{ number_format($project->indicator->target_for_indicator) }}</strong>
            </span>
            @if(optional($project->executor)->title)
                <span class="flex items-center gap-1.5">
                    <i class="fas fa-building text-blue-400 text-xs"></i>
                    <span class="text-gray-400">الجهة المنفّذة:</span> {{ $project->executor->title }}
                </span>
            @endif
            <span class="flex items-center gap-1.5">
                <i class="fas fa-tasks text-blue-400 text-xs"></i>
                <span class="text-gray-400">الإنجاز:</span>
                <strong class="text-blue-600">{{ $completionPercentage }}%</strong>
                <span class="text-gray-400 text-xs">({{ $stepsDoneCount }}/{{ $totalSteps }} خطوة)</span>
                <div class="w-20 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ $completionPercentage }}%"></div>
                </div>
            </span>
        </div>

        @if($project->description)
            <p class="mt-3 text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                {{ $project->description }}
            </p>
        @endif
    </div>
</div>

{{-- ── WORKFLOW TIMELINE ── --}}
@php
    $stages = [
        [
            'number' => 1,
            'user' => 'مدير المشروع',
            'label' => 'إنشاء وتقديم',
            'active' => in_array($project->status, ['draft', 'submitted']),
            'done' => in_array($project->status, ['approved', 'returned', 'rejected']),
            'status_values' => 'مسودة ← قيد المراجعة',
        ],
        [
            'number' => 2,
            'user' => 'دائرة التخطيط',
            'label' => 'مراجعة واعتماد أو إعادة',
            'active' => $project->status === 'submitted',
            'done' => in_array($project->status, ['approved', 'rejected']),
            'status_values' => 'قيد المراجعة ← معتمد / معاد / مرفوض',
        ],
        [
            'number' => 3,
            'user' => 'منفذ المشروع',
            'label' => 'تنفيذ ورفع الأدلة الداعمة',
            'active' => $project->status === 'approved',
            'done' => false,
            'status_values' => 'معتمد (رفع الأدلة الداعمة)',
        ],
    ];
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-5">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider">مسار سير العمل</h2>
        <span class="text-xs text-gray-400">
            الحالة الحالية:
            <span
                class="font-semibold {{ $sc['text'] }} {{ $sc['bg'] }} px-2 py-0.5 rounded-full">{{ $sc['label'] }}</span>
        </span>
    </div>

    <div class="relative flex items-stretch gap-0">
        <div class="absolute top-5 right-5 left-5 h-0.5 bg-gray-200 z-0"></div>

        @foreach($stages as $i => $stage)
            @php
                if ($stage['done']) {
                    $circle = 'bg-green-500 border-green-500 text-white';
                    $line = 'text-green-700';
                    $card = 'border-green-100 bg-green-50/60';
                    $badge = 'bg-green-100 text-green-700';
                    $bdg = 'مكتمل ✓';
                } elseif ($stage['active']) {
                    $circle = 'bg-blue-600 border-blue-500 text-white ring-4 ring-blue-100';
                    $line = 'text-blue-700 font-bold';
                    $card = 'border-blue-200 bg-blue-50';
                    $badge = 'bg-blue-600 text-white';
                    $bdg = '← الآن هنا';
                } else {
                    $circle = 'bg-white border-gray-300 text-gray-400';
                    $line = 'text-gray-400';
                    $card = 'border-gray-100 bg-gray-50/50';
                    $badge = '';
                    $bdg = '';
                }
            @endphp
            <div class="relative z-10 flex flex-col items-center flex-1 gap-2">
                <div
                    class="w-10 h-10 rounded-full border-2 flex items-center justify-center font-bold text-sm {{ $circle }}">
                    @if($stage['done']) ✓ @else {{ $stage['number'] }} @endif
                </div>
                <div class="border rounded-xl p-3 w-full text-center {{ $card }}">
                    <div class="text-xs font-bold {{ $line }}">{{ $stage['user'] }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ $stage['label'] }}</div>
                    <div class="text-[10px] text-gray-400 font-mono mt-1">{{ $stage['status_values'] }}</div>
                </div>
                @if($bdg)
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $badge }}">{{ $bdg }}</span>
                @endif
            </div>
        @endforeach
    </div>
</div>