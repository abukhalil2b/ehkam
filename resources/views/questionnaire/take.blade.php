<x-app-layout>
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
                            <textarea name="question_{{ $question->id }}" class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200"></textarea>
                        @break

                        @case('date')
                            <input type="date" name="question_{{ $question->id }}" value="{{ now()->format('Y-m-d') }}"
                                class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200">
                        @break

                        @case('single')
                            @foreach ($question->choices as $choice)
                                <label class="flex items-center gap-2 mb-1">
                                    <input type="radio" name="question_{{ $question->id }}" value="{{ $choice->id }}"
                                        class="text-green-600">
                                    <span>{{ $choice->choice_text }}</span>
                                </label>
                            @endforeach
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
                                    oninput="this.nextElementSibling.value=this.value">
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
</x-app-layout>
