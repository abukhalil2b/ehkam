<x-app-layout>
    <x-slot name="header">
       {{ $currentStage->title }} - {{ $activity->title }}  
    </x-slot>

    <div class="container py-8 mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold mb-2 text-gray-800">إضافة نتائج تقييم</h2>
        <p class="text-xl text-gray-600 mb-6">النشاط: <span
                class="font-medium text-purple-700">{{ $activity->title }}</span></p>
        <p class="py-1 text-xs text-blue-500">
            يمكنك حفظ الإستمارة وتعديل الإستمارة بعد الحفظ
        </p>
        <form method="POST" action="{{ route('assessment_result.store') }}"
            class="bg-white shadow-xl rounded-lg p-6 md:p-8">
            @csrf

            <input type="hidden" name="activity_id" value="{{ $activity->id }}">

            <div class="space-y-8">
                @forelse ($questions as $question)
                    @php
                        // On a create page, the initial value is 1 or the old value from a failed submission
                        $current_value = old('question_' . $question->id, 1);
                    @endphp

                    <div
                        class="p-5 border border-gray-200 rounded-lg {{ $question->type == 'range' ? 'bg-blue-50' : 'bg-green-50' }}">

                        <p class="text-lg font-semibold mb-3">
                            <span class="text-gray-800">{{ $loop->iteration }}. </span>
                            {{ $question->content }}
                        <div class="py-1 text-xs text-gray-400">
                            {{ $question->description }}
                        </div>
                        </p>

                        <div class="mb-4 p-4 border rounded bg-white shadow-sm">
                            <label class="block text-sm font-medium text-gray-700 mb-2">الإجابة:</label>

                            @if ($question->type === 'range')
                                <div class="flex flex-wrap gap-2" id="button_group_{{ $question->id }}">

                                    @for ($i = 1; $i <= $question->max_point; $i++)
                                        <button type="button" data-value="{{ $i }}"
                                            onclick="selectValue({{ $i }}, {{ $question->id }})"
                                            class="
                                                    w-10 h-10 flex items-center justify-center 
                                                    text-base font-semibold border rounded-lg transition-colors duration-150
                                                    {{ $i == $current_value ? 'bg-purple-600 text-white border-purple-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-purple-50 hover:border-purple-400' }}
                                                ">
                                            {{ $i }}
                                        </button>
                                    @endfor

                                    {{-- Hidden input to store the selected value for form submission --}}
                                    <input type="hidden" name="question_{{ $question->id }}"
                                        id="question_input_{{ $question->id }}" value="{{ $current_value }}">

                                </div>

                                <p class="text-xs text-gray-500 mt-2">
                                    (الحد الأقصى: {{ $question->max_point }}. تم تعيين القيمة إلى 1 تلقائيًا عند إنشاء
                                    التقييم.)
                                </p>

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <label for="note_{{ $question->id }}"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        ملحوظة خاصة بهذا السؤال (اختياري):
                                    </label>
                                    <textarea name="note_{{ $question->id }}" id="note_{{ $question->id }}" rows="2"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-2 text-sm">{{ old('note_' . $question->id) }}</textarea>
                                    @error('note_' . $question->id)
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @elseif ($question->type === 'text')
                                <textarea name="question_{{ $question->id }}" id="question_{{ $question->id }}" rows="3"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3">{{ old('question_' . $question->id) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    (يمكن ترك الحقل فارغاً لتجاهل السؤال.)
                                </p>
                            @endif
                            @error('question_' . $question->id)
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">لا توجد أسئلة تقييم متاحة لإنشاء تقييم.</p>
                @endforelse
            </div>

            <div class="flex justify-end mt-8">
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg transition duration-150 shadow-lg text-lg">
                    حفظ نتائج التقييم
                </button>
            </div>
        </form>
    </div>

    {{-- REQUIRED JAVASCRIPT FOR BUTTON FUNCTIONALITY --}}
    <script>
        /**
         * Updates the hidden input and button styles when a score button is clicked.
         * @param {number} value - The score selected (e.g., 1, 5, 20).
         * @param {number} questionId - The ID of the question the button belongs to.
         */
        function selectValue(value, questionId) {
            // 1. Update the hidden input field's value, which submits to the form
            document.getElementById('question_input_' + questionId).value = value;

            // 2. Get the container of all score buttons for this question
            const buttonGroup = document.getElementById('button_group_' + questionId);
            const buttons = buttonGroup.querySelectorAll('button');

            // 3. Iterate through all buttons to apply selection styles
            buttons.forEach(button => {
                const buttonValue = parseInt(button.getAttribute('data-value'));

                // Define Tailwind classes for selected and unselected states
                const selectedClasses =
                    'w-10 h-10 flex items-center justify-center text-base font-semibold border rounded-lg transition-colors duration-150 bg-purple-600 text-white border-purple-600 shadow-md';
                const unselectedClasses =
                    'w-10 h-10 flex items-center justify-center text-base font-semibold border rounded-lg transition-colors duration-150 bg-white text-gray-700 border-gray-300 hover:bg-purple-50 hover:border-purple-400';

                // Apply the correct style based on the clicked value
                if (buttonValue === value) {
                    button.className = selectedClasses;
                } else {
                    button.className = unselectedClasses;
                }
            });
        }
    </script>
</x-app-layout>
