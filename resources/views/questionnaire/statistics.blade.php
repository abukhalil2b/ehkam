<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white shadow rounded-2xl p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">إحصائيات الاستبيان</h1>
                    <p class="text-gray-600 mt-2">{{ $questionnaire->title }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('questionnaire.show', $questionnaire) }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        العودة للتفاصيل
                    </a>
                    <a href="{{ route('questionnaire.index') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        جميع الاستبيانات
                    </a>
                </div>
            </div>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white shadow rounded-2xl p-6 text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ $totalParticipants }}</div>
                <div class="text-gray-600">عدد المشاركين</div>
            </div>

            <div class="bg-white shadow rounded-2xl p-6 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ $totalAnswers }}</div>
                <div class="text-gray-600">إجمالي الإجابات</div>
            </div>

            <div class="bg-white shadow rounded-2xl p-6 text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($completionRate, 1) }}%</div>
                <div class="text-gray-600">معدل الإكمال</div>
            </div>

            <div class="bg-white shadow rounded-2xl p-6 text-center">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                        <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ $questionnaire->questions_count }}</div>
                <div class="text-gray-600">عدد الأسئلة</div>
            </div>
        </div>

        <!-- Response Over Time Chart -->
        <div class="bg-white shadow rounded-2xl p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">الإجابات عبر الزمن</h2>
            <div class="h-64">
                <canvas id="responseOverTimeChart"></canvas>
            </div>
        </div>

        <!-- Questions Statistics -->
        <div class="space-y-6">
            @foreach($questionStats as $questionStat)
                <div class="bg-white shadow rounded-2xl p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $loop->iteration }}. {{ $questionStat['question']->question_text }}
                            </h3>
                            @if($questionStat['question']->description)
                                <p class="text-gray-600 text-sm">{{ $questionStat['question']->description }}</p>
                            @endif
                            <div class="flex items-center gap-2 mt-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @switch($questionStat['question']->type)
                                        @case('single') bg-purple-100 text-purple-700 @break
                                        @case('multiple') bg-indigo-100 text-indigo-700 @break
                                        @case('range') bg-orange-100 text-orange-700 @break
                                        @case('text') bg-blue-100 text-blue-700 @break
                                        @case('date') bg-green-100 text-green-700 @break
                                    @endswitch">
                                    @switch($questionStat['question']->type)
                                        @case('single') اختيار فردي @break
                                        @case('multiple') اختيار متعدد @break
                                        @case('range') مقياس @break
                                        @case('text') نصي @break
                                        @case('date') تاريخ @break
                                    @endswitch
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $questionStat['statistics']['total'] }} إجابة
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Charts based on question type -->
                    @switch($questionStat['question']->type)
                        @case('single')
                        @case('multiple')
                            @include('questionnaire.partials.choice-chart', ['statistics' => $questionStat['statistics']])
                            @break
                        @case('range')
                            @include('questionnaire.partials.range-chart', [
                                'statistics' => $questionStat['statistics'],
                                'question' => $questionStat['question']
                            ])
                            @break
                        @case('text')
                            @include('questionnaire.partials.text-chart', ['statistics' => $questionStat['statistics']])
                            @break
                        @case('date')
                            @include('questionnaire.partials.date-chart', ['statistics' => $questionStat['statistics']])
                            @break
                    @endswitch
                </div>
            @endforeach
        </div>
    </div>

   
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Response Over Time Chart
        const timeCtx = document.getElementById('responseOverTimeChart').getContext('2d');
        new Chart(timeCtx, {
            type: 'line',
            data: {
                labels: @json($responseOverTime['labels']),
                datasets: [{
                    label: 'عدد الإجابات',
                    data: @json($responseOverTime['data']),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Initialize question charts
        document.querySelectorAll('.question-chart').forEach(canvas => {
            const ctx = canvas.getContext('2d');
            const chartData = JSON.parse(canvas.dataset.chart);
            
            new Chart(ctx, {
                type: chartData.type,
                data: chartData.data,
                options: chartData.options
            });
        });
    </script>

</x-app-layout>