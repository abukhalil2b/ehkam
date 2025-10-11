<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800">
            عرض وتعديل إجابة - {{ $answer->questionnaire->title }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6 bg-white p-6 rounded-2xl shadow space-y-6">

        {{-- Question Info --}}
        <div>
            <p class="text-gray-700 font-semibold mb-1">السؤال:</p>
            <p class="text-lg text-gray-900">{{ $answer->question->question_text }}</p>

            @if ($answer->question->description)
                <p class="text-sm text-gray-500 mt-1">{{ $answer->question->description }}</p>
            @endif
        </div>

        {{-- Edit Form --}}
        <form action="{{ route('questionnaire.answer_update', $answer->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Answer Input by Type --}}
            <div>
                <label class="block text-gray-700 font-semibold mb-1">إجابة المستخدم:</label>

                @switch($answer->question->type)
                    @case('text')
                        <textarea name="text_answer" rows="3"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200">{{ old('text_answer', $answer->text_answer) }}</textarea>
                        @break

                    @case('date')
                        <input type="date" name="text_answer"
                            value="{{ old('text_answer', $answer->text_answer) }}"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200">
                        @break

                    @case('range')
                        @php
                            $choice = $answer->question->choices->first();
                            $min = $choice->min_value ?? 1;
                            $max = $choice->max_value ?? 10;
                            $value = old('range_value', $answer->range_value ?? intval(($min + $max) / 2));
                        @endphp
                        <div class="flex items-center gap-3">
                            <input type="range" name="range_value" min="{{ $min }}" max="{{ $max }}" value="{{ $value }}"
                                class="w-full accent-green-600"
                                oninput="this.nextElementSibling.value=this.value">
                            <output class="text-sm text-gray-700">{{ $value }}</output>
                        </div>
                        @break

                    @case('single')
                        @php
                            $selected = collect($answer->choice_ids)->first() ?? null;
                        @endphp
                        <div class="space-y-2">
                            @foreach ($answer->question->choices as $choice)
                                <label class="flex items-center gap-2">
                                    <input type="radio"
                                        name="choice_ids[]"
                                        value="{{ $choice->id }}"
                                        @checked($selected == $choice->id)
                                        class="text-green-600">
                                    <span>{{ $choice->choice_text }}</span>
                                </label>
                            @endforeach
                        </div>
                        @break

                    @case('multiple')
                        @php
                            $selectedChoices = collect(json_decode($answer->choice_ids ?? '[]'));
                        @endphp
                        <div class="space-y-2">
                            @foreach ($answer->question->choices as $choice)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox"
                                        name="choice_ids[]"
                                        value="{{ $choice->id }}"
                                        @checked($selectedChoices->contains($choice->id))
                                        class="text-green-600">
                                    <span>{{ $choice->choice_text }}</span>
                                </label>
                            @endforeach
                        </div>
                        @break
                @endswitch
            </div>

            {{-- Note --}}
            <div>
                <label class="block text-gray-700 font-semibold mb-1">الملحوظة:</label>
                <textarea name="note" rows="3"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200">{{ old('note', $answer->note) }}</textarea>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-3">
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    تحديث الإجابة
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
