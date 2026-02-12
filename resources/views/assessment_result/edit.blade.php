<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span class="font-semibold text-xl text-gray-800 leading-tight">
                تعديل تقييم النشاط: {{ $activity->title }}
            </span>
            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase">
                دورة: {{ $currentStage->title }}
            </span>
        </div>
    </x-slot>

    <div class="container py-8 mx-auto px-4 max-w-4xl">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">تعديل نتائج التقييم</h2>
            <p class="text-lg text-gray-600 mt-1">
                المرحلة: <span class="font-bold text-blue-600">{{ $currentStage->title }}</span>
            </p>
        </div>

        <div class="bg-blue-50 border-r-4 border-blue-400 p-4 mb-6 rounded-l-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="mr-3">
                    <p class="text-sm text-blue-700">
                        يمكنك حفظ الاستمارة والعودة لتعديلها لاحقاً طالما أن فترة التقييم لعام {{ $currentStage->title }} لا تزال مفتوحة.
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('assessment_result.update', $activity) }}"
            class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            @csrf
            @method('PATCH')

            {{-- حقول مخفية هامة لعملية الربط --}}
            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
            <input type="hidden" name="assessment_stage_id" value="{{ $currentStage->id }}">

            <div class="p-6 md:p-8 space-y-8">
                @forelse ($allQuestions as $question)
                    @php
                        $result = $userResults->get($question->id);
                        $oldRange = $result ? $result->range_answer : null;
                        $oldText = $result ? $result->text_answer : null;
                        $oldNote = $result ? $result->note : null;

                        // القيمة الافتراضية تعتمد على الإجابة السابقة أو 1
                        $current_value = old('question_' . $question->id, $oldRange ?? 1);
                    @endphp

                    <div class="p-6 rounded-xl border {{ $question->type == 'range' ? 'border-blue-100 bg-blue-50/30' : 'border-emerald-100 bg-emerald-50/30' }}">
                        <div class="flex items-start justify-between mb-4">
                            <p class="text-lg font-bold text-gray-800">
                                <span class="bg-white w-8 h-8 inline-flex items-center justify-center rounded-full shadow-sm text-sm ml-2">{{ $loop->iteration }}</span>
                                {{ $question->content }}
                            </p>
                            @if($question->type == 'range')
                                <span class="text-[10px] font-bold text-blue-500 uppercase">مقياس رقمي</span>
                            @else
                                <span class="text-[10px] font-bold text-emerald-500 uppercase">نصي</span>
                            @endif
                        </div>

                        <div class="bg-white p-5 rounded-lg border border-gray-100 shadow-sm">
                            @if ($question->type === 'range')
                                <label class="block text-xs font-bold text-gray-400 mb-3 uppercase tracking-wider">اختر الدرجة المناسبة:</label>
                                <div class="flex flex-wrap gap-3" id="button_group_{{ $question->id }}">
                                    @for ($i = 1; $i <= $question->max_point; $i++)
                                        <button type="button" data-value="{{ $i }}"
                                            onclick="selectValue({{ $i }}, {{ $question->id }})"
                                            class="w-12 h-12 flex items-center justify-center text-lg font-bold border-2 rounded-xl transition-all duration-200
                                                {{ $i == $current_value ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg scale-110' : 'bg-white text-gray-500 border-gray-100 hover:border-indigo-300 hover:bg-indigo-50' }}">
                                            {{ $i }}
                                        </button>
                                    @endfor
                                    <input type="hidden" name="question_{{ $question->id }}" id="question_input_{{ $question->id }}" value="{{ $current_value }}">
                                </div>

                                <div class="mt-6 pt-4 border-t border-gray-50">
                                    <label for="note_{{ $question->id }}" class="block text-sm font-semibold text-gray-700 mb-2">ملاحظات إضافية:</label>
                                    <textarea name="note_{{ $question->id }}" id="note_{{ $question->id }}" rows="2"
                                        placeholder="اكتب ملاحظاتك هنا..."
                                        class="block w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">{{ old('note_' . $question->id, $oldNote) }}</textarea>
                                </div>
                            @elseif ($question->type === 'text')
                                <textarea name="question_{{ $question->id }}" id="question_{{ $question->id }}" rows="4"
                                    placeholder="ادخل الإجابة التفصيلية هنا..."
                                    class="block w-full border-gray-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 p-3 shadow-inner bg-gray-50/50">{{ old('question_' . $question->id, $oldText) }}</textarea>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-gray-400">لا توجد أسئلة تقييم متاحة حالياً.</p>
                    </div>
                @endforelse
            </div>

            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-gray-700 font-medium transition">إلغاء التعديل</a>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-10 rounded-xl transition-all duration-200 shadow-indigo-200 shadow-xl hover:-translate-y-0.5">
                    تحديث التقييم لعام {{ $currentStage->title }}
                </button>
            </div>
        </form>
    </div>

    <script>
        function selectValue(value, questionId) {
            document.getElementById('question_input_' + questionId).value = value;
            const buttonGroup = document.getElementById('button_group_' + questionId);
            const buttons = buttonGroup.querySelectorAll('button');

            buttons.forEach(button => {
                const buttonValue = parseInt(button.getAttribute('data-value'));
                if (buttonValue === value) {
                    button.className = "w-12 h-12 flex items-center justify-center text-lg font-bold border-2 rounded-xl transition-all duration-200 bg-indigo-600 text-white border-indigo-600 shadow-lg scale-110";
                } else {
                    button.className = "w-12 h-12 flex items-center justify-center text-lg font-bold border-2 rounded-xl transition-all duration-200 bg-white text-gray-500 border-gray-100 hover:border-indigo-300 hover:bg-indigo-50";
                }
            });
        }
    </script>
</x-app-layout>