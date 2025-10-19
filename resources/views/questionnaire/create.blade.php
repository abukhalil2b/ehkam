<x-app-layout>
    <div class="max-w-4xl mx-auto mt-8 bg-white shadow rounded-2xl p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">إنشاء استبيان جديد (دعم القوائم المرتبطة)</h2>

        <form action="{{ route('questionnaire.store') }}" method="POST" class="space-y-6" x-data="questionnaireForm()">
            @csrf

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-2">العنوان</label>
                    <input type="text" name="title" x-model="title"
                        class="w-full border border-gray-300 rounded-lg p-3" required>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" :value="1" checked class="rounded">
                    <span class="text-gray-700">مفعل</span>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-2">الاستجابة المستهدفة</label>
                    <select name="target_response" class="w-full border border-gray-300 rounded-lg p-3">
                        <option value="open_for_all">مفتوح للكل</option>
                        <option value="registerd_only">فقط المسجلين</option>
                    </select>
                </div>
            </div>

            ---
            {{-- Questions list --}}
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">الأسئلة</h3>
                    <div class="text-sm text-gray-600" x-text="'إجمالي: ' + questions.length"></div>
                </div>

                <div class="space-y-6">
                    <template x-for="(question, qIndex) in questions" :key="qIndex">
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-semibold text-gray-700" x-text="'سؤال ' + (qIndex + 1)"></h4>
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="moveUp(qIndex)" :disabled="qIndex === 0" class="text-sm px-2 py-1 bg-gray-100 rounded disabled:opacity-50">▲</button>
                                    <button type="button" @click="moveDown(qIndex)" :disabled="qIndex === questions.length - 1" class="text-sm px-2 py-1 bg-gray-100 rounded disabled:opacity-50">▼</button>
                                    <button type="button" @click="removeQuestion(qIndex)" class="text-red-600">حذف</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">نص السؤال</label>
                                    <input type="text" x-model="question.question_text"
                                        :name="`questions[${qIndex}][question_text]`"
                                        class="w-full border border-gray-300 rounded-lg p-2" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">نوع السؤال</label>
                                    <select x-model="question.type" :name="`questions[${qIndex}][type]`"
                                        @change="onTypeChange(question)"
                                        class="w-full border border-gray-300 rounded-lg p-2">
                                        <option value="text">نصي</option>
                                        <option value="single">اختيار فردي</option>
                                        <option value="multiple">اختيار متعدد</option>
                                        <option value="dropdown">قائمة منسدلة</option>
                                        <option value="range">مقياس</option>
                                        <option value="date">تاريخ</option>
                                    </select>
                                </div>

                                <input type="hidden" :name="`questions[${qIndex}][ordered]`" :value="qIndex">
                            </div>

                            {{-- Link to parent question if dropdown --}}
                            <div x-show="question.type === 'dropdown'" class="mb-4 p-3 border border-blue-200 rounded-lg bg-blue-50">
                                <label class="block text-sm font-medium mb-1">ربط هذه القائمة بسؤال سابق (اختياري)</label>
                                <select x-model="question.parent_question_index"
                                        :name="`questions[${qIndex}][parent_question_index]`"
                                        class="w-full border rounded p-2"
                                        @change="onParentChange(qIndex)">
                                    <option :value="null">بدون ربط</option>
                                    <template x-for="(pq, pIdx) in questions" :key="pIdx">
                                        <option x-show="pIdx < qIndex && pq.type === 'dropdown'"
                                                :value="pIdx"
                                                x-text="(pIdx + 1) + '. ' + (pq.question_text || 'سؤال بدون نص')"></option>
                                    </template>
                                </select>
                                <p class="text-xs text-gray-700 mt-1">
                                    إذا تم الربط، يجب تعريف الخيارات في القسم الخاص بالخيارات المرتبطة أدناه.
                                </p>
                            </div>

                            {{-- For single/multiple/NON-DEPENDENT dropdown - static choices --}}
                            <div x-show="['single','multiple'].includes(question.type) || (question.type === 'dropdown' && question.parent_question_index === null)" class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-medium">الخيارات</label>
                                    <button type="button" @click="addChoice(qIndex)" class="text-sm bg-green-600 text-white px-3 py-1 rounded">إضافة خيار</button>
                                </div>

                                <div class="space-y-2">
                                    <template x-for="(choice, cIndex) in question.choices" :key="cIndex">
                                        <div class="flex items-center gap-2">
                                            <input type="text" x-model="choice.choice_text"
                                                :name="`questions[${qIndex}][choices][${cIndex}][choice_text]`"
                                                class="flex-1 border rounded p-2" placeholder="نص الخيار" required>
                                            <input type="hidden" :name="`questions[${qIndex}][choices][${cIndex}][ordered]`" :value="cIndex">
                                            <button type="button" @click="removeChoice(qIndex, cIndex)" class="text-red-600">حذف</button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Dependent child mapping UI: show when this question IS linked to a parent --}}
                            <div x-show="question.type === 'dropdown' && question.parent_question_index !== null" class="mb-4 p-3 border border-red-200 rounded-lg bg-red-50">
                                <div class="mb-2">
                                    <span class="block text-sm font-medium text-red-800">تعريف خيارات مرتبطة (للسؤال الأب)</span>
                                    <p class="text-xs text-gray-700">
                                        يجب أن يكون السؤال الأب **السؤال رقم <span x-text="question.parent_question_index + 1"></span>** يحتوي على خيارات معرفة في قسم الخيارات لديه.
                                    </p>
                                </div>
                                
                                <template x-if="questions[question.parent_question_index] && questions[question.parent_question_index].choices.length > 0">
                                    <div class="space-y-4 mt-3">
                                        <template x-for="(parentChoice, pcIdx) in questions[question.parent_question_index].choices" :key="pcIdx">
                                            <div class="border border-gray-200 rounded p-3 bg-white">
                                                <div class="flex justify-between items-center mb-2">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-800" x-text="'خيار الأب: ' + (parentChoice.choice_text || ('خيار ' + (pcIdx+1)))"></div>
                                                    </div>
                                                    <button type="button" @click="addChildOption(qIndex, pcIdx)" class="text-sm bg-green-600 text-white px-3 py-1 rounded">إضافة خيار تابع</button>
                                                </div>

                                                <div class="space-y-2">
                                                    <template x-for="(opt, ci) in getChildOptions(qIndex, pcIdx)" :key="ci">
                                                        <div class="flex items-center gap-2">
                                                            <input type="text" x-model="opt.choice_text"
                                                                :name="`questions[${qIndex}][choices][${getFlattenedIndex(qIndex, pcIdx, ci)}][choice_text]`"
                                                                class="flex-1 border rounded p-2" placeholder="نص الخيار التابع" required>
                                                            
                                                            <input type="hidden" :name="`questions[${qIndex}][choices][${getFlattenedIndex(qIndex, pcIdx, ci)}][ordered]`" :value="getFlattenedIndex(qIndex, pcIdx, ci)">
                                                            <input type="hidden" :name="`questions[${qIndex}][choices][${getFlattenedIndex(qIndex, pcIdx, ci)}][parent_question_index]`" :value="question.parent_question_index">
                                                            <input type="hidden" :name="`questions[${qIndex}][choices][${getFlattenedIndex(qIndex, pcIdx, ci)}][parent_choice_index]`" :value="pcIdx">

                                                            <button type="button" @click="removeChildOption(qIndex, pcIdx, ci)" class="text-red-600">حذف</button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!questions[question.parent_question_index] || questions[question.parent_question_index].choices.length === 0">
                                    <p class="text-sm text-red-600">
                                        <i class="fa fa-warning"></i> يجب تعريف خيارات للسؤال الأب (السؤال رقم <span x-text="question.parent_question_index + 1"></span>) أولاً قبل تحديد الخيارات التابعة له.
                                    </p>
                                </template>
                            </div>

                            {{-- Range inputs --}}
                            <div x-show="question.type === 'range'">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label>أقل قيمة</label>
                                        <input type="number" x-model.number="question.min_value" :name="`questions[${qIndex}][min_value]`" class="w-full border rounded p-2">
                                    </div>
                                    <div>
                                        <label>أعلى قيمة</label>
                                        <input type="number" x-model.number="question.max_value" :name="`questions[${qIndex}][max_value]`" class="w-full border rounded p-2">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </template>
                </div>

                <div class="mt-4">
                    <button type="button" @click="addQuestion()" class="bg-blue-600 text-white px-4 py-2 rounded">إضافة سؤال</button>
                </div>
            </div>

            ---
            <div class="text-left mt-6">
                <x-primary-button>حفظ الاستبيان</x-primary-button>
            </div>
        </form>
    </div>

    <script>
        function questionnaireForm() {
            return {
                title: '',
                questions: [
                    {
                        question_text: '',
                        type: 'text',
                        ordered: 0,
                        min_value: null,
                        max_value: null,
                        choices: [], 
                        parent_question_index: null, // null means no link, 0 or greater means linked to that index
                        _childOptions: {}, // { parentChoiceIndex: [ { choice_text } ] }
                    }
                ],

                addQuestion() {
                    this.questions.push({
                        question_text: '',
                        type: 'text',
                        ordered: this.questions.length,
                        min_value: null,
                        max_value: null,
                        choices: [],
                        parent_question_index: null,
                        _childOptions: {},
                    });
                },

                removeQuestion(i) {
                    if (!confirm('هل تريد حذف هذا السؤال؟')) return;
                    this.questions.splice(i, 1);
                    // Reset dependency links pointing to the removed question index
                    this.questions.forEach((q, idx) => {
                        if (q.parent_question_index === i) {
                            q.parent_question_index = null;
                            q._childOptions = {};
                        } else if (q.parent_question_index > i) {
                            // shift parent index if it points to a question after the removed one
                            q.parent_question_index -= 1;
                        }
                    });
                },

                moveUp(i) {
                    if (i === 0) return;
                    [this.questions[i-1], this.questions[i]] = [this.questions[i], this.questions[i-1]];
                    this.updateQuestionOrders();
                },

                moveDown(i) {
                    if (i === this.questions.length - 1) return;
                    [this.questions[i+1], this.questions[i]] = [this.questions[i], this.questions[i+1]];
                    this.updateQuestionOrders();
                },
                
                updateQuestionOrders() {
                    // This function is crucial after any move/remove to fix parent indices
                    this.questions.forEach((q, i) => {
                        q.ordered = i;
                        // Check if parent_question_index is still valid
                        if (q.parent_question_index !== null && q.parent_question_index >= this.questions.length) {
                             q.parent_question_index = null;
                             q._childOptions = {};
                        }
                    });
                },

                addChoice(qIndex) {
                    if (!this.questions[qIndex].choices) this.questions[qIndex].choices = [];
                    this.questions[qIndex].choices.push({ choice_text: '' });
                },

                removeChoice(qIndex, cIndex) {
                    if (!confirm('حذف هذا الخيار؟')) return;
                    
                    // 1. Remove the choice from the current question's static list
                    this.questions[qIndex].choices.splice(cIndex, 1);

                    // 2. If this question is a parent, update child mappings in dependent questions
                    this.questions.forEach((q, idx) => {
                        if (q.parent_question_index === qIndex) {
                            if (q._childOptions && Object.keys(q._childOptions).length) {
                                // shift indexes greater than removed choice's index
                                for (const key of Object.keys(q._childOptions)) {
                                    const kIndex = Number(key);
                                    if (kIndex > cIndex) {
                                        q._childOptions[kIndex - 1] = q._childOptions[kIndex];
                                        delete q._childOptions[kIndex];
                                    } else if (kIndex === cIndex) {
                                        // delete mapping for removed parent choice
                                        delete q._childOptions[kIndex];
                                    }
                                }
                            }
                        }
                    });
                },

                onTypeChange(question) {
                    // Reset dependency data when type changes away from dropdown
                    if (question.type !== 'dropdown') {
                        question.parent_question_index = null;
                        question._childOptions = {};
                    }

                    if (['single','multiple','dropdown'].includes(question.type)) {
                        if (!question.choices) question.choices = [];
                        // Ensure at least one empty option for non-dependent questions
                        if (question.choices.length === 0 && question.parent_question_index === null) {
                            question.choices.push({ choice_text: '' });
                        }
                    } else {
                        question.choices = [];
                        question.parent_question_index = null;
                        question._childOptions = {};
                        question.min_value = null;
                        question.max_value = null;
                    }
                },

                onParentChange(qIndex) {
                    const q = this.questions[qIndex];
                    
                    // If a link is established (pIdx is not null) AND the question is a dropdown
                    if (q.parent_question_index !== null && q.type === 'dropdown') {
                        // 1. Clear static choices as they will be defined via _childOptions now
                        q.choices = [];
                        // 2. Initialize _childOptions structure
                        if (!q._childOptions) q._childOptions = {};
                        
                        const parent = this.questions[q.parent_question_index];
                        if (parent) {
                            // Ensure all parent choices have an entry in _childOptions
                            parent.choices.forEach((pc, i) => {
                                if (!q._childOptions[i]) q._childOptions[i] = [];
                            });
                            // Clean up any stale parent indexes if the new parent has fewer choices
                            const maxIndex = parent.choices.length - 1;
                            for (const key of Object.keys(q._childOptions)) {
                                if (Number(key) > maxIndex) {
                                    delete q._childOptions[key];
                                }
                            }
                        }
                    } else {
                        // If link is removed (parent_question_index is null), reset dependency data
                        q._childOptions = {};
                        // Re-initialize static choices for the now non-dependent dropdown
                        if (q.choices.length === 0) q.choices.push({ choice_text: '' });
                    }
                },

                // Add a child option to question qIndex under parentChoiceIndex
                addChildOption(qIndex, parentChoiceIndex) {
                    const q = this.questions[qIndex];
                    if (!q._childOptions) q._childOptions = {};
                    if (!q._childOptions[parentChoiceIndex]) q._childOptions[parentChoiceIndex] = [];
                    q._childOptions[parentChoiceIndex].push({ choice_text: '' });
                },

                // Remove specific child option
                removeChildOption(qIndex, parentChoiceIndex, childIndex) {
                    const q = this.questions[qIndex];
                    if (!q._childOptions || !q._childOptions[parentChoiceIndex]) return;
                    q._childOptions[parentChoiceIndex].splice(childIndex, 1);
                },

                // Return array of child options for a given parentChoiceIndex (ensures array exists)
                getChildOptions(qIndex, parentChoiceIndex) {
                    const q = this.questions[qIndex];
                    if (!q._childOptions) q._childOptions = {};
                    if (!q._childOptions[parentChoiceIndex]) q._childOptions[parentChoiceIndex] = [];
                    return q._childOptions[parentChoiceIndex];
                },

                // For template binding: compute flattened index
                getFlattenedIndex(qIndex, parentChoiceIndex, childLocalIndex) {
                    const q = this.questions[qIndex];
                    let idx = 0;
                    for (let p = 0; p < parentChoiceIndex; p++) {
                        const arr = (q._childOptions && q._childOptions[p]) ? q._childOptions[p] : [];
                        idx += arr.length;
                    }
                    idx += childLocalIndex;
                    return idx;
                },
            }
        }
    </script>
</x-app-layout>