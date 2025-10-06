<x-app-layout>
    <div class="max-w-4xl mx-auto mt-8 bg-white shadow rounded-2xl p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">تعديل الاستبيان</h2>

        <form action="{{ route('questionnaire.update', $questionnaire->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-2">العنوان</label>
                    <input type="text" name="title" value="{{ old('title', $questionnaire->title) }}"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <!-- is_active checkbox -->
                <input type="hidden" name="is_active" value="0">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1"
                        {{ $questionnaire->is_active ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-700">مفعل</span>
                </div>
            </div>

            {{-- Questions Section --}}
            <div x-data="questionnaireForm({{ $questionnaire->questions->toJson() }})">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">الأسئلة</h3>
                    <div x-text="questions.length"></div>
                </div>

                <div class="space-y-6">
                    <template x-for="(question, qIndex) in questions" :key="qIndex">
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-semibold text-gray-700" x-text="'سؤال ' + (qIndex + 1)"></h4>
                                <button type="button" @click="removeQuestion(qIndex)"
                                    class="text-red-600 hover:text-red-800">حذف</button>
                            </div>

                            <div class="grid grid-cols-1 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">نص السؤال</label>
                                    <input type="text" x-model="question.question_text"
                                        :name="`questions[${qIndex}][question_text]`"
                                        class="w-full border border-gray-300 rounded-lg p-2" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">الوصف (اختياري)</label>
                                    <textarea x-model="question.description" :name="`questions[${qIndex}][description]`"
                                        class="w-full border border-gray-300 rounded-lg p-2" rows="2"></textarea>
                                </div>

                                <div class="flex items-center gap-2 mt-2">
                                    <input type="hidden" :name="`questions[${qIndex}][note_attachment]`"
                                        :value="question.note_attachment ? 1 : 0">
                                    <input type="checkbox" x-model="question.note_attachment" class="h-4 w-4">
                                    <label class="text-sm text-gray-700">السماح بكتابة ملحوظة </label>
                                </div>


                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">نوع السؤال</label>
                                    <select x-model="question.type" :name="`questions[${qIndex}][type]`"
                                        @change="onTypeChange(question)"
                                        class="w-full border border-gray-300 rounded-lg p-2">
                                        <option value="single">اختيار فردي</option>
                                        <option value="multiple">اختيار متعدد</option>
                                        <option value="range">مقياس</option>
                                        <option value="text">نصي</option>
                                        <option value="date">تاريخ</option>
                                    </select>
                                </div>

                                <input type="hidden" :name="`questions[${qIndex}][ordered]`" :value="qIndex">
                            </div>

                            {{-- Choices for single/multiple --}}
                            <div x-show="['single','multiple'].includes(question.type)" class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-medium text-gray-700">الخيارات</label>
                                    <button type="button" @click="addChoice(qIndex)"
                                        class="text-sm bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                        إضافة خيار
                                    </button>
                                </div>

                                <div class="space-y-2">
                                    <template x-for="(choice, cIndex) in question.choices" :key="cIndex">
                                        <div class="flex items-center gap-2">
                                            <input type="text" x-model="choice.choice_text"
                                                :name="`questions[${qIndex}][choices][${cIndex}][choice_text]`"
                                                class="flex-1 border border-gray-300 rounded-lg p-2"
                                                placeholder="خيار جديد">

                                            <input type="hidden"
                                                :name="`questions[${qIndex}][choices][${cIndex}][ordered]`"
                                                :value="cIndex">

                                            <button type="button" @click="removeChoice(qIndex, cIndex)"
                                                class="text-red-600 hover:text-red-800">
                                                حذف
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Range inputs --}}
                            <div x-show="question.type === 'range'" class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">أقل قيمة</label>
                                    <input type="number" x-model.number="question.min_value"
                                        :name="`questions[${qIndex}][min_value]`"
                                        class="w-full border border-gray-300 rounded-lg p-2" min="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">أعلى قيمة</label>
                                    <input type="number" x-model.number="question.max_value"
                                        :name="`questions[${qIndex}][max_value]`"
                                        class="w-full border border-gray-300 rounded-lg p-2" min="0">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <button type="button" @click="addQuestion()"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex mt-4">
                    إضافة سؤال
                </button>
            </div>

            <div class="text-left">
                <x-primary-button>تحديث الاستبيان</x-primary-button>
            </div>
        </form>
    </div>

    <script>
        function questionnaireForm(initialQuestions = []) {
            return {
                questions: initialQuestions.length ? initialQuestions : [{
                    question_text: '',
                    type: 'single',
                    description: '',
                    note_attachment: false,
                    ordered: 0,
                    choices: [{
                        choice_text: '',
                    }]
                }],

                addQuestion() {
                    this.questions.push({
                        question_text: '',
                        type: 'single',
                        description: '',
                        note_attachment: false,
                        ordered: this.questions.length,
                        choices: [{
                            choice_text: '',
                        }]
                    });
                },

                removeQuestion(index) {
                    if (this.questions.length > 1) {
                        this.questions.splice(index, 1);
                    }
                },

                addChoice(qIndex) {
                    if (!this.questions[qIndex].choices) this.questions[qIndex].choices = [];
                    this.questions[qIndex].choices.push({
                        choice_text: ''
                    });
                },

                removeChoice(qIndex, cIndex) {
                    this.questions[qIndex].choices.splice(cIndex, 1);
                },

                onTypeChange(question) {
                    if (['single', 'multiple'].includes(question.type)) {
                        if (!question.choices || question.choices.length === 0) {
                            question.choices = [{
                                choice_text: ''
                            }];
                        }
                    } else if (question.type === 'range') {
                        question.choices = [];
                        question.min_value = 1;
                        question.max_value = 5;
                    } else {
                        question.choices = [];
                    }
                }
            }
        }
    </script>
</x-app-layout>
