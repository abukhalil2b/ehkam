<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->title }} - تقرير التحليل</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: white;
        }

        @page {
            margin: 10mm 8mm;
            size: A4 portrait;
        }

        @media print {
            @page {
                margin: 8mm 6mm;
                size: A4 portrait;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            html, body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
            }

            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            /* Remove centering and use full width */
            .max-w-4xl {
                max-width: 100% !important;
                width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                min-height: auto !important;
            }

            /* Ensure RTL layout respects full width */
            [dir="rtl"] .max-w-4xl {
                margin-right: 0 !important;
                margin-left: 0 !important;
            }

            .break-inside-avoid {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            tr {
                page-break-inside: avoid;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            /* Prevent URL printing after links */
            a[href]:after {
                content: none !important;
            }

            /* Ensure images don't overflow */
            img {
                max-width: 100% !important;
            }

            /* Background colors preservation */
            .bg-emerald-50, .bg-blue-50, .bg-purple-50, .bg-amber-50,
            .bg-gray-50, .bg-white, .bg-red-100, .bg-yellow-100, .bg-green-100 {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Reduce spacing for compact print */
            .mb-10 {
                margin-bottom: 6mm !important;
            }

            .p-6 {
                padding: 4mm !important;
            }

            .p-4 {
                padding: 3mm !important;
            }

            .gap-6 {
                gap: 4mm !important;
            }

            .pb-6 {
                padding-bottom: 4mm !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="max-w-4xl mx-auto p-8 bg-white min-h-screen shadow-sm print:shadow-none">

        <!-- Header -->
        <div class="flex justify-between items-center mb-10 pb-6 border-b border-gray-200 break-inside-avoid">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $project->title }}</h1>
                <p class="text-gray-500 mt-2">تقرير تحليل SWOT والخطة الاستراتيجية</p>
            </div>
            <div class="text-left">
                <p class="text-sm text-gray-500">تاريخ التقرير</p>
                <p class="font-semibold">{{ now()->format('Y/m/d') }}</p>
                <button onclick="window.print()"
                    class="no-print mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    طباعة التقرير
                </button>
            </div>
        </div>

        <!-- 1. Proposed Strategies -->
        @if (isset($bscStrategies) && collect($bscStrategies)->filter()->isNotEmpty())
            <div class="mb-10 break-inside-avoid">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    1. الاستراتيجيات المقترحة (BSC)
                </h2>

                <div class="grid grid-cols-1 gap-6">
                    @php
                        $bscMeta = [
                            'financial' => [
                                'title' => 'البعد المالي',
                                'color' => 'emerald',
                                'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                            ],
                            'beneficiaries' => [
                                'title' => 'بعد المستفيدين',
                                'color' => 'blue',
                                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
                            ],
                            'internal_processes' => [
                                'title' => 'العمليات الداخلية',
                                'color' => 'purple',
                                'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
                            ],
                            'learning_growth' => [
                                'title' => 'التعلم والنمو',
                                'color' => 'amber',
                                'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
                            ]
                        ];
                    @endphp

                    @foreach($bscMeta as $type => $meta)
                        @php $strategies = $bscStrategies[$type] ?? collect(); @endphp
                        @if($strategies->isNotEmpty())
                            <div
                                class="bg-white border border-{{ $meta['color'] }}-200 rounded-xl overflow-hidden shadow-sm break-inside-avoid">
                                <div
                                    class="bg-{{ $meta['color'] }}-50 px-6 py-4 border-b border-{{ $meta['color'] }}-100 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-{{ $meta['color'] }}-100 shadow-sm">
                                        <svg class="w-5 h-5 text-{{ $meta['color'] }}-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $meta['icon'] }}" />
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-lg text-{{ $meta['color'] }}-900">{{ $meta['title'] }}</h3>
                                </div>

                                <div class="p-6 grid gap-6">
                                    @foreach($strategies as $index => $strategy)
                                        @if($strategy->strategic_goal || $strategy->performance_indicator || ($strategy->initiatives && count($strategy->initiatives) > 0))
                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 relative break-inside-avoid">
                                                @if ($strategies->count() > 1)
                                                    <div class="absolute top-2 left-2">
                                                        <span
                                                            class="text-xs font-bold text-{{ $meta['color'] }}-800 bg-{{ $meta['color'] }}-100 px-2 py-0.5 rounded-full">#{{ $loop->iteration }}</span>
                                                    </div>
                                                @endif

                                                @if($strategy->strategic_goal)
                                                    <div class="mb-3">
                                                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">الهدف
                                                            الاستراتيجي</h4>
                                                        <p class="text-gray-900 font-medium">{{ $strategy->strategic_goal }}</p>
                                                    </div>
                                                @endif

                                                @if($strategy->performance_indicator)
                                                    <div class="mb-3">
                                                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">مؤشر الأداء
                                                            (KPI)</h4>
                                                        <p class="text-gray-900 font-medium">{{ $strategy->performance_indicator }}</p>
                                                    </div>
                                                @endif

                                                @if($strategy->initiatives && count($strategy->initiatives) > 0)
                                                    <div>
                                                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">المبادرات</h4>
                                                        <ul class="space-y-2">
                                                            @foreach($strategy->initiatives as $initiative)
                                                                @if($initiative)
                                                                    <li class="flex items-start gap-2 text-sm text-gray-800">
                                                                        <span class="text-{{ $meta['color'] }}-500 mt-1">•</span>
                                                                        <span>{{ $initiative }}</span>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 2. Action Plan -->
        @if ($project->finalize->action_items && count($project->finalize->action_items))
            <div class="mb-10 break-inside-avoid">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    2. خطة العمل
                </h2>

                <div class="overflow-hidden border border-gray-200 rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right font-semibold text-gray-600">المهمة</th>
                                <th scope="col" class="px-6 py-3 text-right font-semibold text-gray-600">المسؤول</th>
                                <th scope="col" class="px-6 py-3 text-right font-semibold text-gray-600">الأولوية</th>
                                <th scope="col" class="px-6 py-3 text-right font-semibold text-gray-600">موعد التسليم</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($project->finalize->action_items as $item)
                                <tr>
                                    <td class="px-6 py-4 text-gray-900 font-medium">{{ $item['title'] }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $item['owner'] ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $priorityColors = [
                                                'High' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'مرتفع'],
                                                'Medium' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'متوسط'],
                                                'Low' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'منخفض'],
                                            ];
                                            $prio = $priorityColors[$item['priority'] ?? ''] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => $item['priority'] ?? 'غير محدد'];
                                        @endphp
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prio['bg'] }} {{ $prio['text'] }}">
                                            {{ $prio['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $item['deadline'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="mt-12 pt-6 border-t border-gray-100 text-center text-gray-400 text-xs break-inside-avoid">
            <p>تم استخراج هذا التقرير من نظام إتقان &copy; {{ date('Y') }}</p>
        </div>

    </div>

    <script>
        // Optional: Auto print when page loads
        // window.print();
    </script>
</body>

</html>