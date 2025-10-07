@if($statistics['total'] > 0)
@php
$chartData = [
    'type' => 'bar',
    'data' => [
        'labels' => collect($statistics['distribution'])->pluck('value')->toArray(),
        'datasets' => [[
            'label' => 'عدد الإجابات',
            'data' => collect($statistics['distribution'])->pluck('count')->toArray(),
            'backgroundColor' => '#10b981',
            'borderColor' => '#059669',
            'borderWidth' => 1
        ]]
    ],
    'options' => [
        'responsive' => true,
        'maintainAspectRatio' => false,
        'plugins' => [
            'legend' => [
                'display' => false
            ]
        ],
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'ticks' => [
                    'stepSize' => 1
                ]
            ]
        ]
    ]
];
@endphp

<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg text-center">
            <div class="text-2xl font-bold text-blue-600">{{ number_format($statistics['average'], 1) }}</div>
            <div class="text-sm text-blue-500">المتوسط</div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg text-center">
            <div class="text-2xl font-bold text-green-600">{{ $statistics['min'] }}</div>
            <div class="text-sm text-green-500">أقل قيمة</div>
        </div>
        <div class="bg-red-50 p-4 rounded-lg text-center">
            <div class="text-2xl font-bold text-red-600">{{ $statistics['max'] }}</div>
            <div class="text-sm text-red-500">أعلى قيمة</div>
        </div>
    </div>

    <!-- Distribution Chart -->
    <div class="h-64">
        <canvas class="question-chart" data-chart="{{ json_encode($chartData) }}"></canvas>
    </div>
</div>
@else
<div class="text-center py-8 text-gray-500">
    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    <p>لا توجد إجابات حتى الآن</p>
</div>
@endif