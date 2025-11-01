<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>شرح وتوضيح استمارة التقييم</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tajawal Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f7f9fb 0%, #eef2f7 100%);
            min-height: 100vh;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <div class="max-w-5xl mx-auto py-10 px-6">

        <!-- 🔹 Section 1: Explanation -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-green-700 mb-2">شرح استمارة التقييم</h1>
            <p class="text-gray-600 text-lg">
                توضح هذه الصفحة كيفية تنفيذ عملية التقييم والمعايير التي يتم من خلالها قياس جودة الأنشطة.
            </p>
        </div>

        <div class="space-y-6 mb-16">
            @foreach ($questions as $question)
                <div class="bg-white shadow-md rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition">
                    <div class="flex justify-between items-start mb-3">
                        <h2 class="text-xl font-semibold text-green-800">
                            {{ $question->content }}
                        </h2>
                        @if ($question->type === 'range')
                            <div class="flex items-center gap-2">

                            </div>
                            <div class="text-xs text-gray-500 mt-1 text-center">التقييم من 1 إلى
                                {{ $question->max_point }}</div>
                        @else
                            <textarea disabled class="w-full border border-gray-200 rounded-xl p-3 mt-2 text-gray-400 bg-gray-50 text-sm"
                                placeholder="سيُكتب هنا نص التقييم"></textarea>
                        @endif

                    </div>

                    <p class="text-gray-600 text-sm mb-4">{{ $question->description }}</p>


                </div>
            @endforeach
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-300 my-10"></div>

        <!-- 🔹 Section 2: Actual Results -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-green-700 mb-2">(مثال:تثمير)</h2>
        </div>

        @if ($results->isEmpty())
            <div class="text-center text-gray-500 py-10">
                لا توجد نتائج تقييم لهذا النشاط حالياً.
            </div>
        @else
            <div class="space-y-6">
                @foreach ($results as $result)
                    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                        <div class="flex justify-between items-start mb-3">
                            <h2 class="text-lg font-semibold text-green-800">
                                {{ $result->assessmentQuestion->content }}
                            </h2>
                            <span class="text-sm bg-gray-100 px-3 py-1 rounded-full">
                                {{ $result->assessmentQuestion->type === 'range' ? 'تقييم رقمي' : 'إجابة نصية' }}
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm mb-4">{{ $result->assessmentQuestion->description }}</p>

                        @if ($result->assessmentQuestion->type === 'range')
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-3 bg-gray-200 rounded-full">
                                    <div class="h-3 bg-green-500 rounded-full"
                                        style="width: {{ ($result->range_answer / $result->assessmentQuestion->max_point) * 100 }}%">
                                    </div>
                                </div>
                                <span class="text-green-700 font-semibold text-sm">
                                    {{ $result->range_answer }} / {{ $result->assessmentQuestion->max_point }}
                                </span>
                            </div>
                        @else
                            <div class="border border-gray-200 bg-gray-50 rounded-xl p-3 text-sm text-gray-700">
                                {{ $result->text_answer ?? '— لا توجد إجابة نصية —' }}
                            </div>
                        @endif

                        @if ($result->note)
                            <div
                                class="mt-3 text-xs text-gray-500 bg-yellow-50 border border-yellow-100 rounded-lg p-2">
                                <strong>ملاحظة:</strong> {{ $result->note }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

    

        <div class="mt-12 text-center text-gray-500 text-sm">
            <h2 class="text-2xl font-bold text-purple-700 mb-2">(مثال:الأداء الكلي لمشروع الوقف حياة)</h2>
            <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">
                <div class="bg-purple-600 text-white p-5 flex justify-between items-center">
                    <h3 class="text-xl font-bold">الوقف حياة</h3>
                    <div class="text-2xl font-extrabold">
                        الأداء الكلي:
                        <span class="bg-white text-purple-600 px-3 py-1 rounded-full">
                            %86.7
                        </span>
                    </div>
                </div>

                <div class="p-5">
                    <div class="mb-4 text-sm text-gray-700">
                        <p>النقاط الإجمالية المُسجلة: <span class="font-semibold">260</span> من <span
                                class="font-semibold">300</span></p>
                        <p class="mt-1 text-xs text-gray-500">ملحوظة: يتم حساب النسبة المئوية من إجمالي النقاط
                            الممكنة لجميع الأنشطة المقيمة.</p>
                    </div>

                    <h4 class="text-lg font-semibold mt-6 mb-3 border-b pb-2">الأنشطة المرتبطة ونسبة أدائها:
                    </h4>

                    <ul class="space-y-3">
                        <li class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                            <span class="text-gray-800 font-medium">اسبوع الوقف الخليجي</span>
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <span class="text-sm text-gray-600">
                                    (88 / 100)
                                </span>
                                <span
                                    class="px-3 py-1 text-sm font-semibold rounded-full 
                                                    bg-green-100 text-green-800">
                                    %88
                                </span>
                            </div>
                        </li>
                        <li class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                            <span class="text-gray-800 font-medium">اللقاء السنوي الوقفي</span>
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <span class="text-sm text-gray-600">
                                    (84 / 100)
                                </span>
                                <span
                                    class="px-3 py-1 text-sm font-semibold rounded-full 
                                                    bg-green-100 text-green-800">
                                    %84
                                </span>
                            </div>
                        </li>
                        <li class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                            <span class="text-gray-800 font-medium">الوقف وعي وعطاء</span>
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <span class="text-sm text-gray-600">
                                    (88 / 100)
                                </span>
                                <span
                                    class="px-3 py-1 text-sm font-semibold rounded-full 
                                                    bg-green-100 text-green-800">
                                    %88
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>



</body>

</html>
