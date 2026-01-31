<x-app-layout>
    {{-- Chart.js Local --}}
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-sans" dir="rtl">

        <div
            class="bg-white shadow-sm rounded-2xl p-6 mb-6 border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">إحصائيات: {{ $questionnaire->title }}</h1>
                <p class="text-gray-500 text-sm mt-1">تقرير شامل للردود والتحليلات</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('questionnaire.show', $questionnaire->id) }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-bold">
                    رجوع
                </a>
                <button onclick="window.print()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-bold flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    طباعة
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-gray-800">{{ $totalParticipants }}</div>
                    <div class="text-sm text-gray-500">إجمالي المشاركين</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-gray-800">{{ number_format($completionRate, 1) }}%</div>
                    <div class="text-sm text-gray-500">نسبة الإكمال</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-gray-800">{{ $totalAnswers }}</div>
                    <div class="text-sm text-gray-500">إجمالي نقاط الإجابة</div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">نشاط الاستبيان عبر الزمن</h3>
            <div class="h-64 w-full">
                <canvas id="timelineChart"></canvas>
            </div>
        </div>

        <div class="space-y-6">
            @foreach ($questionStats as $index => $item)
                @php
                    $q = $item['question'];
                    $stats = $item['statistics'];
                    $chartId = 'chart_' . $q->id;
                @endphp

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 break-inside-avoid">

                    {{-- Question Header --}}
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="text-lg font-bold text-gray-800">
                                <span class="text-blue-600 ml-1">{{ $loop->iteration }}.</span>
                                {{ $q->question_text }}
                            </h4>
                            <p class="text-sm text-gray-500 mt-1">
                                النوع: {{ $q->type }} | إجمالي الردود: {{ $stats['total_responses'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Dynamic Body based on Type --}}
                    <div class="mt-4">

                        {{-- A. CHARTS (Single, Multiple, Dropdown, Range) --}}
                        @if (in_array($q->type, ['single', 'multiple', 'dropdown', 'range']))
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="w-full md:w-2/3 h-64 relative">
                                    <canvas id="{{ $chartId }}"></canvas>
                                </div>
                                @if ($q->type == 'range')
                                    <div
                                        class="w-full md:w-1/3 flex flex-col justify-center items-center bg-blue-50 rounded-xl p-4">
                                        <span class="text-gray-500 text-sm">متوسط التقييم</span>
                                        <span
                                            class="text-4xl font-extrabold text-blue-600 my-2">{{ $stats['chart_data']['average'] }}</span>
                                        <span class="text-gray-400 text-xs">من أصل {{ $q->max_value }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- JS to Init This Specific Chart --}}
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
                                    const type = '{{ $q->type }}';

                                    // Configuration for Bar vs Line vs Pie
                                    let chartType = 'bar';
                                    if (type === 'single' || type === 'dropdown') chartType = 'doughnut';

                                    new Chart(ctx, {
                                        type: chartType,
                                        data: {
                                            labels: @json($stats['chart_data']['labels']),
                                            datasets: [{
                                                label: 'عدد الإجابات',
                                                data: @json($stats['chart_data']['data']),
                                                backgroundColor: [
                                                    'rgba(59, 130, 246, 0.7)', // Blue
                                                    'rgba(16, 185, 129, 0.7)', // Green
                                                    'rgba(245, 158, 11, 0.7)', // Orange
                                                    'rgba(239, 68, 68, 0.7)', // Red
                                                    'rgba(139, 92, 246, 0.7)', // Purple
                                                    'rgba(107, 114, 128, 0.7)' // Gray
                                                ],
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            indexAxis: chartType === 'bar' && type !== 'range' ? 'y' :
                                            'x', // Horizontal bars for choices
                                            plugins: {
                                                legend: {
                                                    display: chartType === 'doughnut'
                                                }
                                            }
                                        }
                                    });
                                });
                            </script>

                            {{-- B. TEXT/DATE LISTS --}}
                        @elseif(in_array($q->type, ['text', 'date']))
                            <div class="bg-gray-50 rounded-xl p-4">
                                <h5 class="text-sm font-bold text-gray-600 mb-3">آخر الإجابات المستلمة:</h5>
                                <ul class="space-y-2">
                                    @forelse($stats['latest_answers'] ?? [] as $ans)
                                        <li
                                            class="bg-white border border-gray-200 p-3 rounded-lg text-sm text-gray-700">
                                            "{{ $ans }}"
                                        </li>
                                    @empty
                                        <li class="text-gray-400 text-sm italic">لا توجد إجابات نصية حتى الآن</li>
                                    @endforelse
                                </ul>
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{-- Main Timeline Chart Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Timeline Chart
            const timelineCtx = document.getElementById('timelineChart').getContext('2d');
            new Chart(timelineCtx, {
                type: 'line',
                data: {
                    labels: @json($responseOverTime['labels']),
                    datasets: [{
                        label: 'عدد المشاركات',
                        data: @json($responseOverTime['data']),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4
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
        });
    </script>
</x-app-layout>
