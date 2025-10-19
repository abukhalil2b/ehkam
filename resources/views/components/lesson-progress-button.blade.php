@props([
    'lessonId',
    'status', 
    'label' => null,
    'icon' => null,
    'class' => '',
])

@php
    $statusLabelMap = [
        'completed' => 'تم الإنجاز',
        'in_progress' => 'قيد التنفيذ',
        'not_started' => 'لم يبدأ',
    ];

    $statusColorMap = [
        'completed' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
        'in_progress' => 'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-500',
        'not_started' => 'bg-gray-400 hover:bg-gray-500 focus:ring-gray-500',
    ];

    $colorClass = $statusColorMap[$status] ?? 'bg-gray-400';
    $text = $label ?? ($statusLabelMap[$status] ?? 'غير معروف');
@endphp

<form method="POST" action="{{ route('trainee.update_lesson_progress', $lessonId) }}">
    @csrf
    <input type="hidden" name="status" value="{{ $status }}">
    
    <button type="submit"
        class="inline-flex w-full text-center justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-150 {{ $colorClass }} {{ $class }}">
        @if ($icon)
            <svg class="w-4 h-4 ml-1 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                    clip-rule="evenodd" />
            </svg>
        @endif
        {{ $text }}
    </button>
</form>
