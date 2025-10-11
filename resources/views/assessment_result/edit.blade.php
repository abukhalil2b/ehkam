<x-app-layout>
   <x-slot name="header">
    تعديل تقييم النشاط: {{ $activity->title }}
   </x-slot>

    <div class="container py-8 mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold mb-2 text-gray-800">تعديل نتائج التقييم</h2>
        <p class="text-xl text-gray-600 mb-6">النشاط: <span class="font-medium text-purple-700">{{ $activity->title }}</span></p>


        <!-- Form action points to the update route, using PATCH method -->
        <form method="POST" action="{{ route('assessment_result.update', $activity) }}" class="bg-white shadow-xl rounded-lg p-6 md:p-8">
            @csrf
            @method('PATCH')
            
            <input type="hidden" name="activity_id" value="{{ $activity->id }}">

            <div class="space-y-8">
                @forelse ($allQuestions as $question)
                    @php
                        // Retrieve existing result for this specific question (if it exists)
                        $result = $userResults->get($question->id);
                        $oldRange = $result ? $result->range_answer : null;
                        $oldText = $result ? $result->text_answer : null;
                        $oldNote = $result ? $result->note : null;
                    @endphp

                    <div class="p-5 border border-gray-200 rounded-lg {{ $question->type == 'range' ? 'bg-blue-50' : 'bg-green-50' }}">
                        
                        <p class="text-lg font-semibold mb-3">
                            <span class="text-gray-800">{{ $loop->iteration }}. </span>
                            {{ $question->content }}
                        </p>
                        
                        <!-- Main Answer Input -->
                        <div class="mb-4 p-4 border rounded bg-white shadow-sm">
                            <label class="block text-sm font-medium text-gray-700 mb-2">الإجابة:</label>

                            @if ($question->type === 'range')
                                <div class="flex items-center space-x-4">
                                    <input type="range" 
                                           name="question_{{ $question->id }}" 
                                           id="question_{{ $question->id }}" 
                                           min="1" 
                                           max="{{ $question->max_point }}"
                                           value="{{ old('question_' . $question->id, $oldRange ?? 1) }}"
                                           oninput="document.getElementById('range_value_{{ $question->id }}').innerText = this.value"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer range-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-opacity-50">
                                           
                                    <span id="range_value_{{ $question->id }}" class="text-xl font-bold text-purple-600 w-8 text-center">
                                        {{ old('question_' . $question->id, $oldRange ?? 1) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    (الحد الأقصى: {{ $question->max_point }}. يمكنك مسح القيمة لتجاهل السؤال.)
                                </p>

                            @elseif ($question->type === 'text')
                                <textarea name="question_{{ $question->id }}" 
                                          id="question_{{ $question->id }}" 
                                          rows="3"
                                          class="block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3">{{ old('question_' . $question->id, $oldText) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    (يمكن مسح النص لتجاهل السؤال.)
                                </p>
                            @endif
                            @error('question_' . $question->id) 
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Dedicated Note Field for the Current Question -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <label for="note_{{ $question->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                ملاحظة خاصة بهذا السؤال (اختياري):
                            </label>
                            <textarea name="note_{{ $question->id }}" 
                                      id="note_{{ $question->id }}" 
                                      rows="2"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-2 text-sm">{{ old('note_' . $question->id, $oldNote) }}</textarea>
                            @error('note_' . $question->id) 
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                    </div>
                @empty
                    <p class="text-center text-gray-500">لا توجد أسئلة تقييم متاحة.</p>
                @endforelse
            </div>

            <div class="flex justify-end mt-8">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg transition duration-150 shadow-lg text-lg">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
