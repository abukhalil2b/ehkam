<x-app-layout>
    <x-slot name="header">
        تفاصيل النشاط
    </x-slot>

    <div class="container py-8 mx-auto px-4">
        <h2 class="text-3xl font-bold mb-2">{{ $activity->title }}</h2>
        <p class="text-gray-600 mb-4">
            المشروع: {{ $activity->project->title ?? 'N/A' }}
        </p>
        <p class="text-sm text-gray-500 mb-6">
            <span class="font-semibold">سنة التقييم:</span> {{ $currentYear }}
        </p>

        <!-- Action Buttons -->
        <div class="mb-8 flex flex-wrap gap-3">
            <a href="{{ route('activity.index') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                الأنشطة
            </a>

            @if ($canSubmitNew)
                <a href="{{ route('assessment_result.create', $activity->id) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    تقييم جديد
                </a>
            @elseif ($canUpdate)
                <a href="{{ route('assessment_result.edit', $activity->id) }}"
                    class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    تعديل التقييم الحالي
                </a>
            @endif
        </div>

        <!-- Summary Section -->
        <h3 class="text-2xl font-semibold mt-8 mb-4 border-b pb-2">
            ملخص التقييم الخاص بك ({{ $currentYear }})
        </h3>

        @if (empty($userSummary))
            <p class="text-gray-500">لم تقم بتقديم أي تقييم لهذا النشاط بعد.</p>
        @else
            <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المُقيم
                            </th>
                            @if ($hasRangeResults)
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    النقاط الكلية
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    النسبة المئوية
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $userSummary['user_name'] }}
                            </td>
                            @if ($hasRangeResults)
                                <td class="px-6 py-4 text-center text-lg font-bold text-blue-600">
                                    {{ $userSummary['total_score'] }} / {{ $userSummary['max_score'] }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-3 py-1 inline-flex text-lg leading-5 font-semibold rounded-full
                                        {{ $userSummary['percentage'] >= 75 ? 'bg-green-100 text-green-800' : ($userSummary['percentage'] >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        %{{ $userSummary['percentage'] }}
                                    </span>
                                </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Detailed Question Results -->
        <h3 class="text-2xl font-semibold mt-10 mb-4 border-b pb-2">
            النتائج التفصيلية لكل سؤال. عدد الأسئلة: {{ count($allQuestions) }}
        </h3>

        @if ($allQuestions->isEmpty())
            <p class="text-gray-500">
                لا توجد أسئلة تقييم متاحة بعد.
                <a href="{{ route('assessment_questions.create') }}" class="text-purple-600 hover:text-purple-800">
                    أنشئ سؤالاً جديداً
                </a>.
            </p>
        @else
            <div class="space-y-8">
                @foreach ($allQuestions as $question)
                    <div class="p-5 border rounded shadow-sm bg-gray-50">
                        <p class="font-bold text-xl mb-3 text-gray-800">
                            {{ $loop->iteration }}. {{ $question->content }}
                            <span class="text-sm font-normal text-gray-500">
                                ({{ $question->type == 'range' ? 'نقاط' : 'نص' }})
                            </span>
                        </p>

                        @php
                            $result = $userSummary['results']->get($question->id);
                        @endphp

                        <div class="space-y-3 mt-4 border-t pt-4">
                            @if ($result)
                                <div class="p-3 rounded border bg-white shadow-sm">
                                    @if ($question->type === 'range')
                                        <p class="text-blue-700 font-medium">
                                            النقاط:
                                            <span class="font-bold text-lg">
                                                {{ $result->range_answer ?? '—' }}
                                            </span>
                                            / {{ $question->max_point ?? '—' }}

                                            @if ($result->range_answer !== null && $question->max_point > 0)
                                                @php
                                                    $answerPercentage =
                                                        ($result->range_answer / $question->max_point) * 100;
                                                @endphp
                                                <span class="text-sm text-gray-600 mr-2">
                                                    (%{{ round($answerPercentage, 0) }})
                                                </span>
                                            @endif
                                        </p>
                                    @elseif ($question->type === 'text')
                                        <p class="bg-gray-100 p-2 rounded mt-1 text-sm">
                                            {!! nl2br(e(trim($result->text_answer))) ?: '—' !!}
                                        </p>
                                    @endif

                                    @if ($result->note)
                                        <p class="mt-2 text-gray-700 text-sm border-t pt-2 italic">
                                            ملحوظة: {{ $result->note }}
                                        </p>
                                    @endif

                                    <p class="text-xs text-gray-500 mt-2">
                                        بتاريخ: {{ $result->updated_at->format('Y-m-d H:i') }}
                                    </p>
                                </div>
                            @else
                                <div class="text-sm text-gray-500 p-2 rounded bg-white">
                                    لم يتم تسجيل إجابة أو ملحوظة لهذا السؤال بعد.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
