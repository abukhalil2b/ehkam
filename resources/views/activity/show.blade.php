<x-app-layout>
    <x-slot name="header">
        تفاصيل النشاط
    </x-slot>

    <div class="container py-8 mx-auto px-4">

        <h2 class="text-3xl font-bold mb-2">{{ $activity->title }}</h2>

        <p class="text-gray-600 mb-2">
            المشروع: {{ $activity->project->title ?? '—' }}
        </p>

        <p class="text-sm text-gray-500 mb-6">
            <span class="font-semibold">سنة التقييم:</span> {{ $currentYear }}
        </p>

        <!-- Action Buttons -->
        <div class="mb-8 flex flex-wrap gap-3">
            <a href="{{ route('activity.index', ['year' => $currentYear]) }}"
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

        <!-- Summary -->
        <h3 class="text-2xl font-semibold mt-8 mb-4 border-b pb-2">
            ملخص التقييم الخاص بك
        </h3>

        @if ($userSummary['results']->isEmpty())
            <p class="text-gray-500">
                لم تقم بتقديم أي تقييم لهذا النشاط بعد.
            </p>
        @else
            <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">
                                المُقيم
                            </th>

                            @if ($hasRangeResults)
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">
                                    النقاط
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500">
                                    النسبة
                                </th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="px-6 py-4 font-medium">
                                {{ $userSummary['user_name'] }}
                            </td>

                            @if ($hasRangeResults)
                                <td class="px-6 py-4 text-center font-bold text-blue-600">
                                    {{ $userSummary['total_score'] }} / {{ $userSummary['max_score'] }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $userSummary['percentage'] >= 75 ? 'bg-green-100 text-green-800'
                                            : ($userSummary['percentage'] >= 50 ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-800') }}">
                                        %{{ $userSummary['percentage'] }}
                                    </span>
                                </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Detailed Results -->
        <h3 class="text-2xl font-semibold mt-10 mb-4 border-b pb-2">
            النتائج التفصيلية ({{ $allQuestions->count() }} سؤال)
        </h3>

        <div class="space-y-6">
            @foreach ($allQuestions as $question)
                @php
                    $result = $userSummary['results']->get($question->id);
                @endphp

                <div class="p-5 border rounded bg-gray-50">
                    <p class="font-bold text-lg mb-2">
                        {{ $loop->iteration }}. {{ $question->content }}
                        <span class="text-sm text-gray-500">
                            ({{ $question->type === 'range' ? 'نقاط' : 'نص' }})
                        </span>
                    </p>

                    @if ($result)
                        @if ($question->type === 'range')
                            <p class="text-blue-700">
                                النقاط:
                                <strong>{{ $result->range_answer }}</strong>
                                / {{ $question->max_point }}
                            </p>
                        @else
                            <div class="bg-white p-3 rounded text-sm">
                                {!! nl2br(e($result->text_answer)) !!}
                            </div>
                        @endif

                        @if ($result->note)
                            <p class="mt-2 text-sm italic text-gray-600">
                                ملحوظة: {{ $result->note }}
                            </p>
                        @endif

                        <p class="text-xs text-gray-500 mt-2">
                            آخر تحديث: {{ $result->updated_at->format('Y-m-d H:i') }}
                        </p>
                    @else
                        <p class="text-sm text-gray-500">
                            لم يتم تسجيل إجابة لهذا السؤال.
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
