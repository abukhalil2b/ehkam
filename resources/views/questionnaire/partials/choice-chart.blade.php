@if($statistics['total'] > 0)
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="h-64">
        <canvas class="question-chart" data-chart="@json([
            'type' => 'bar',
            'data' => [
                'labels' => collect($statistics['data'])->pluck('choice'),
                'datasets' => [[
                    'label' => 'عدد الإجابات',
                    'data' => collect($statistics['data'])->pluck('count'),
                    'backgroundColor' => [
                        '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
                        '#ec4899', '#06b6d4', '#84cc16', '#f97316', '#6366f1'
                    ],
                    'borderWidth' => 0
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
                        'beginAtZero' => true
                    ]
                ]
            ]
        ])"></canvas>
    </div>
    <div class="space-y-3">
        @foreach($statistics['data'] as $item)
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-sm font-medium text-gray-700">{{ $item['choice'] }}</span>
            <div class="flex items-center gap-3">
                <div class="w-24 bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" 
                         style="width: {{ $item['percentage'] }}%"></div>
                </div>
                <span class="text-sm font-semibold text-gray-900 w-12 text-left">
                    {{ $item['percentage'] }}%
                </span>
                <span class="text-sm text-gray-500 w-8 text-left">({{ $item['count'] }})</span>
            </div>
        </div>
        @endforeach
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