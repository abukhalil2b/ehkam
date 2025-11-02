
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
    <!-- Header -->
    <div class="bg-blue-800 text-white py-3 shadow-lg">
        <div class="max-w-4xl mx-auto px-2 text-center">
            <h1 class="text-2xl font-extrabold">
                المديرية العامة للتخطيط والدراسات
            </h1>
            <h2 class="text-xl font-extrabold"> وزارة الأوقاف والشؤون الدينية</h2>
        </div>
    </div>

    <div class="max-w-6xl mx-auto p-4">
        {{-- QR Code Section --}}
        <div class="hidden md:block">
            @if ($qrImage)
                <div class="p-2 mb-4 text-center">
                    <div class="flex flex-col items-center">
                        <div class="bg-white p-4 rounded-xl shadow-inner border-2 border-blue-200 inline-block">
                            {!! $qrImage !!}
                        </div>
                        <p class="mt-4 text-gray-600 font-medium flex items-center justify-center">
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            مسح الكود للإجابة
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

        <div class="max-w-2xl mx-auto py-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ $questionnaire->title }}</h2>

        @php
            $submitRoute = $questionnaire->public_hash
                ? route('questionnaire.public_submit', $questionnaire->public_hash)
                : route('questionnaire.submit', $questionnaire->id);
        @endphp
        
        <form method="POST" action="{{ $submitRoute }}" class="bg-white p-6 rounded-2xl shadow space-y-6">
            @csrf

            @foreach ($questionnaire->questions as $index => $question)
                <div class="border-b pb-4">
                    <h3 class="font-bold text-lg mb-2">{{ $index + 1 }}. {{ $question->question_text }}</h3>
                    @if ($question->description)
                        <p class="text-sm text-gray-600 mb-3">{{ $question->description }}</p>
                    @endif

                    {{-- Question Types --}}
                    @switch($question->type)
                        @case('text')
                            <textarea name="question_{{ $question->id }}" class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200" required></textarea>
                        @break

                        @case('date')
                            <input type="date" name="question_{{ $question->id }}"
                                class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200" required>
                        @break

                        @case('single')
                            @foreach ($question->choices as $choice)
                                <label class="flex items-center gap-2 mb-1">
                                    <input type="radio" name="question_{{ $question->id }}" value="{{ $choice->id }}"
                                        class="text-green-600" required>
                                    <span>{{ $choice->choice_text }}</span>
                                </label>
                            @endforeach
                        @break
                        
                        {{-- ✅ ADDED: Dropdown Case --}}
                        @case('dropdown')
                            <div class="relative">
                                <select name="question_{{ $question->id }}" 
                                        class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200 appearance-none bg-white"
                                        required>
                                    <option value="" disabled selected>-- اختر من القائمة --</option>
                                    @foreach ($question->choices as $choice)
                                        <option value="{{ $choice->id }}">{{ $choice->choice_text }}</option>
                                    @endforeach
                                </select>
                                {{-- Simple dropdown arrow replacement (Tailwind friendly) --}}
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-700">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        @break

                        @case('multiple')
                            @foreach ($question->choices as $choice)
                                <label class="flex items-center gap-2 mb-1">
                                    <input type="checkbox" name="question_{{ $question->id }}[]" value="{{ $choice->id }}"
                                        class="text-green-600">
                                    <span>{{ $choice->choice_text }}</span>
                                </label>
                            @endforeach
                        @break

                        @case('range')
                            @php
                                $min = $question->min_value ?? 1;
                                $max = $question->max_value ?? 10;
                                $mid = intval(($min + $max) / 2);
                            @endphp
                            <div class="flex items-center gap-3">
                                <input type="range" name="question_{{ $question->id }}" min="{{ $min }}"
                                    max="{{ $max }}" value="{{ $mid }}" class="w-full accent-green-600"
                                    oninput="this.nextElementSibling.value=this.value" required>
                                <output class="text-sm text-gray-700">{{ $mid }}</output>
                            </div>
                        @break
                    @endswitch

                    @if ($question->note_attachment)
                        <input type="text" name="note_{{ $question->id }}" placeholder="ملحوظة (اختياري)"
                            class="w-full mt-3 border border-gray-300 rounded-lg p-2 text-sm focus:ring focus:ring-green-200">
                    @endif

                </div>
            @endforeach

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    إرسال الإجابات
                </button>
            </div>
        </form>
    </div>

</body>

</html>
