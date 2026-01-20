@php
    // Display phase instead of status
    $phases = ['planning' => 'تخطيط', 'implementation' => 'تنفيذ', 'review' => 'مراجعة', 'close' => 'إغلاق'];
    $phaseLabel = $phases[$step->phase] ?? 'غير محدد';
@endphp

<span class="inline-flex items-center bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
    {{ $phaseLabel }}
</span>