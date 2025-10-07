<x-app-layout>
    <div class="max-w-4xl mx-auto mt-8 bg-white shadow rounded-2xl p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">تعديل الاستبيان</h2>

        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
            <strong class="font-bold">تحذير هام: </strong>
            <span class="block sm:inline">
                سيؤدي تعديل هذا الاستبيان إلى **حذف جميع الإجابات والبيانات** التي تم جمعها مسبقاً بشكل دائم. يرجى
                المتابعة بحذر.
            </span>
        </div>

        <form action="{{ route('questionnaire.question_update', $questionnaire->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Questions Section --}}
            <div x-data="questionnaireForm({{ $questionsJson }})">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">الأسئلة (<span x-text="questions.length"></span>)
                    </h3>
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
                                    <label class="text-sm text-gray-700">السماح بكتابة ملحوظة</label>
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

            // Map the initial questions to ensure correct structure, especially for newly loaded questions
            const loadedQuestions = initialQuestions.map(q => {
                // If the type is not single/multiple, ensure choices is an empty array
                if (!['single', 'multiple'].includes(q.type)) {
                    q.choices = [];
                }
                // Ensure min/max values are numbers, as they are used with x-model.number
                if (q.type === 'range') {
                    q.min_value = q.min_value !== undefined ? Number(q.min_value) : 1;
                    q.max_value = q.max_value !== undefined ? Number(q.max_value) : 5;
                }
                // Convert 'note_attachment' from potential integer (DB) to boolean
                q.note_attachment = !!q.note_attachment;

                return q;
            });


            return {
                // FIX: Use loadedQuestions if available, otherwise use a single default question
                questions: loadedQuestions.length ? loadedQuestions : [{
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
                    // Ensure the 'ordered' property is always correct
                    const newOrder = this.questions.length;
                    this.questions.push({
                        question_text: '',
                        type: 'single',
                        description: '',
                        note_attachment: false,
                        ordered: newOrder,
                        choices: [{
                            choice_text: '',
                        }]
                    });
                },

                removeQuestion(index) {
                    // UX Improvement: Check before allowing removal, though DB should handle minimum
                    if (confirm('هل أنت متأكد من حذف هذا السؤال؟')) {
                        this.questions.splice(index, 1);
                    }
                },

                addChoice(qIndex) {
                    if (!this.questions[qIndex].choices) this.questions[qIndex].choices = [];
                    // Ensure the new choice has the required 'choice_text' property
                    this.questions[qIndex].choices.push({
                        choice_text: '',
                        // You might need an 'ordered' field here if you save choices order
                    });
                },

                removeChoice(qIndex, cIndex) {
                    this.questions[qIndex].choices.splice(cIndex, 1);
                },

                onTypeChange(question) {
                    if (['single', 'multiple'].includes(question.type)) {
                        // Initialize with one choice if none exist
                        if (!question.choices || question.choices.length === 0) {
                            question.choices = [{
                                choice_text: ''
                            }];
                        }
                        // Remove range properties if they were present
                        delete question.min_value;
                        delete question.max_value;

                    } else if (question.type === 'range') {
                        question.choices = [];
                        // Initialize range values
                        question.min_value = question.min_value !== undefined ? question.min_value : 1;
                        question.max_value = question.max_value !== undefined ? question.max_value : 5;
                    } else {
                        // Text/Date type - ensure choices are removed
                        question.choices = [];
                        delete question.min_value;
                        delete question.max_value;
                    }
                }
            }
        }
    </script>
</x-app-layout>
