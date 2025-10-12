<x-app-layout>
    <x-slot name="header">
        تفاصيل النشاط
    </x-slot>

    <div class="container py-8 mx-auto px-4">
        <h2 class="text-3xl font-bold mb-4">{{ $activity->title }}</h2>

        <p class="text-gray-600 mb-6">المشروع: {{ $activity->project->title ?? 'N/A' }}</p>

        <!-- Action Button for Current User -->
        <div class="mb-8">
            <a href="{{ route('activity.index') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                الأنشطة
            </a>
            @if ($userSubmitted)
                <a href="{{ route('assessment_result.edit', $activity->id) }}"
                    class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 inline-block">
                    تعديل تقييمي الحالي
                </a>
            @else
                <a href="{{ route('assessment_result.create', $activity->id) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 inline-block">
                     تقييم جديد
                </a>
            @endif
        </div>

        <!-- ASSESSMENT SUMMARY TABLE (Total Score & Percentage) -->
        <h3 class="text-2xl font-semibold mt-8 mb-4 border-b pb-2">ملخص التقييمات المسجلة</h3>

        @if (empty($summary))
            <p class="text-gray-500">لم يتم تسجيل أي تقييمات لهذا النشاط بعد.</p>
        @else
            <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المُقيم</th>
                            @if ($hasRangeResults)
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    النقاط الكلية</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    النسبة المئوية</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($summary as $userId => $data)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $data['user_name'] }}</td>
                                @if ($hasRangeResults)
                                    <td class="px-6 py-4 text-center text-lg font-bold text-blue-600">
                                        {{ $data['total_score'] }} / {{ $data['max_score'] }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-3 py-1 inline-flex text-lg leading-5 font-semibold rounded-full 
                                            {{ $data['percentage'] >= 75 ? 'bg-green-100 text-green-800' : ($data['percentage'] >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            %{{ $data['percentage'] }}
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif


        <!-- DETAILED RESULTS PER QUESTION -->
        <h3 class="text-2xl font-semibold mt-10 mb-4 border-b pb-2">النتائج التفصيلية لكل سؤال</h3>

        @if ($allQuestions->isEmpty())
            <p class="text-gray-500">لا توجد أسئلة تقييم متاحة بعد. <a
                    href="{{ route('assessment_questions.create') }}"
                    class="text-purple-600 hover:text-purple-800">أنشئ سؤالاً جديداً</a>.</p>
        @else
            <div class="space-y-8">
                @foreach ($allQuestions as $question)
                    <div class="p-5 border rounded shadow-sm bg-gray-50">

                        <p class="font-bold text-xl mb-3 text-gray-800">
                            {{ $loop->iteration }}. {{ $question->content }}
                            <span
                                class="text-sm font-normal text-gray-500">({{ $question->type == 'range' ? 'نقاط' : 'نص' }})</span>
                        </p>

                        <!-- List all answers for this specific question -->
                        <div class="space-y-3 mt-4 border-t pt-4">
                            @php
                                // Filter results for this specific question across all users
                                $questionResults = $assessmentResultsByUser
                                    ->flatten()
                                    ->where('assessment_question_id', $question->id);
                            @endphp

                            @forelse ($questionResults as $result)
                                <div class="p-3 rounded border bg-white shadow-sm">
                                    <p class="text-sm font-semibold text-gray-800">المُقيم:
                                        {{ $result->user->name ?? 'مستخدم غير معروف' }}</p>

                                    @if ($question->type === 'range')
                                        <p class="text-blue-700 font-medium">النقاط:
                                            <span class="font-bold text-lg">{{ $result->range_answer ?? '—' }}</span>
                                            / {{ $question->max_point ?? '—' }}

                                            @if ($result->range_answer !== null && $question->max_point > 0)
                                                @php
                                                    // Calculate percentage: (user score / max score) * 100
                                                    $answerPercentage =
                                                        ($result->range_answer / $question->max_point) * 100;
                                                @endphp

                                                <span class="text-sm text-gray-600 mr-2">
                                                    (%{{ round($answerPercentage, 0) }})
                                                </span>
                                            @endif
                                        </p>
                                    @elseif ($question->type === 'text')
                                        <p class="text-blue-700 font-medium">الإجابة:</p>
                                        <p class="bg-gray-100 p-2 rounded mt-1 text-sm whitespace-pre-wrap">
                                            {{ $result->text_answer ?? '—' }}</p>
                                    @endif

                                    @if ($result->note)
                                        <p class="mt-2 text-gray-700 text-sm border-t pt-2 italic">ملاحظة:
                                            {{ $result->note }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-2">بتاريخ:
                                        {{ $result->updated_at->format('Y-m-d H:i') }}</p>
                                </div>
                            @empty
                                <div class="text-sm text-gray-500 p-2 rounded bg-white">
                                    لم يتم تسجيل إجابات أو ملاحظات لهذا السؤال بعد.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
