<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الحضور في الورشة</title>
    <!-- Tailwind CSS CDN for quick setup -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts - Ensure Tajawal is loaded -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f7f9fb 0%, #eef2f7 100%);
            min-height: 100vh;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        @media (max-width: 640px) {

            .attendance-table th,
            .attendance-table td {
                padding: 8px 10px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="bg-blue-800 text-white py-3 shadow-lg">
        <div class="max-w-4xl mx-auto px-2 text-center">
            <h1 class="text-2xl font-extrabold">
                المديرية العامة للتخطيط والدراسات
            </h1>
            <h2 class="text-xl font-extrabold"> وزارة الأوقاف والشؤون الدينية</h2>
        </div>
    </div>
    <div class="bg-gray-50 min-h-screen p-4">
        <div class="max-w-4xl mx-auto mt-8 bg-white shadow-lg rounded-2xl p-6 space-y-8 border border-gray-100">
            <div class="flex justify-between items-center bg-emerald-50 p-5 rounded-xl border border-emerald-100">
                <h2 class="text-lg font-semibold text-emerald-800">إجمالي الردود المُستلمة:</h2>
                <span class="text-4xl font-extrabold text-emerald-600">
                    {{ $totalResponses }}
                </span>
            </div>

            <div class="space-y-7">
                @foreach ($questionnaire->questions as $index => $question)
                    @php
                        $q_id = $question->id;
                        $stats = $results[$q_id] ?? null;
                        $total_q_answers = $stats['total_answers'] ?? 0;
                    @endphp

                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">

                        <div class="flex justify-between items-start mb-4 pb-2 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-700 text-right">
                                <span class="text-primary-teal">{{ $loop->iteration }}.</span>
                                {{ $question->question_text }}
                            </h3>
                            <span
                                class="px-3 py-1 text-xs font-medium tracking-wider rounded-full 
                            @if ($question->type == 'text' || $question->type == 'date') bg-yellow-100 text-yellow-700
                            @elseif ($question->type == 'range') bg-blue-100 text-blue-700
                            @else bg-emerald-100 text-emerald-700 @endif">
                                {{ ['single' => 'اختيار مفرد', 'multiple' => 'اختيار متعدد', 'range' => 'نطاق', 'text' => 'نص حر', 'date' => 'تاريخ', 'dropdown' => 'قائمة منسدلة'][$question->type] ?? $question->type }}
                            </span>
                        </div>

                        @if ($total_q_answers == 0)
                            <p class="text-sm text-red-500 italic text-right">لم يتم جمع أي إجابات لهذا السؤال بعد.</p>
                        @else
                            <p class="text-sm text-gray-500 mb-5 text-right">
                                الردود الإجمالية على هذا السؤال: <span
                                    class="font-semibold text-gray-700">{{ $total_q_answers }}</span>
                            </p>

                            @if (in_array($question->type, ['single', 'multiple', 'dropdown']))
                                <div class="space-y-4">
                                    @foreach ($stats['breakdown'] as $choiceStat)
                                        <div class="flex items-center space-x-4 space-x-reverse">
                                            <div
                                                class="w-1/3 text-sm font-medium text-gray-700 overflow-hidden text-ellipsis whitespace-nowrap text-right">
                                                {{ $choiceStat['text'] }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="h-2 flex rounded-full bg-gray-100 overflow-hidden">
                                                    <div style="width:{{ $choiceStat['percentage'] }}%"
                                                        class="flex flex-col text-center justify-center bg-emerald-500 transition-all duration-500">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-20 flex flex-col items-end">
                                                <span class="text-base font-bold text-emerald-700 leading-none">
                                                    {{ $choiceStat['percentage'] }}%
                                                </span>
                                                <span class="text-xs text-gray-500 mt-0.5">
                                                    ({{ $choiceStat['count'] }} صوت)
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if ($question->type === 'range')
                                @if (!empty($stats['breakdown']))
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 space-y-3 text-right">
                                        <p class="text-lg font-bold text-gray-700">
                                            متوسط التقييم:
                                            <span
                                                class="text-2xl font-extrabold text-emerald-600">{{ $stats['breakdown']['average'] }}</span>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            نطاق القيم المُجابة: {{ $stats['breakdown']['min'] }} إلى
                                            {{ $stats['breakdown']['max'] }} (المقياس الأصلي:
                                            {{ $question->min_value }}
                                            إلى {{ $question->max_value }})
                                        </p>

                                        <h6 class="text-md font-semibold mt-4 text-gray-700 border-t pt-3">توزيع القيم
                                        </h6>
                                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 pt-2">
                                            @foreach ($stats['breakdown']['distribution'] as $value => $count)
                                                <div
                                                    class="p-2 bg-emerald-100 rounded-lg text-center text-sm shadow-sm border border-emerald-200">
                                                    <span
                                                        class="block font-bold text-lg text-emerald-800">{{ $value }}</span>
                                                    <span class="text-xs text-gray-600">({{ $count }}
                                                        مرات)</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if (in_array($question->type, ['text', 'date']))
                                <h6 class="text-md font-semibold mb-2 text-gray-700 text-right">
                                    الإجابات النصية (يظهر أول {{ count($stats['breakdown']) }} إجابة)
                                </h6>
                                <div
                                    class="border border-gray-300 rounded-lg p-3 bg-white overflow-y-auto max-h-60 space-y-2 shadow-inner">
                                    @forelse ($stats['breakdown'] as $answer)
                                        <div
                                            class="p-2 border-b border-gray-100 last:border-b-0 text-sm text-gray-800 text-right">
                                            "{{ $answer }}"
                                        </div>
                                    @empty
                                        <div class="text-sm text-gray-500 italic p-2 text-right">لم يتم تقديم إجابات
                                            نصية.
                                        </div>
                                    @endforelse
                                </div>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>
