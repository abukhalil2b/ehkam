<x-app-layout>
    <div class="max-w-4xl mx-auto mt-8 bg-white shadow rounded-2xl p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">إنشاء استبيان جديد</h2>

        {{-- Error Display --}}
        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('questionnaire.store') }}" method="POST" class="space-y-6" x-data="questionnaireForm()">
            @csrf

            {{-- 1. Questionnaire Settings --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-2">عنوان الاستبيان</label>
                    <input type="text" name="title" value="{{ old('title') }}" 
                           class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="md:pt-8">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="form-checkbox h-5 w-5 text-blue-600 rounded">
                        <span class="mr-2 text-gray-700 font-semibold">تفعيل الاستبيان فوراً</span>
                    </label>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-2">من يمكنه المشاركة؟</label>
                    <select name="target_response" class="w-full border border-gray-300 rounded-lg p-3">
                        <option value="open_for_all">الجميع (رابط عام)</option>
                        <option value="registerd_only">المسجلين فقط (يتطلب دخول)</option>
                    </select>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- 2. Questions Builder --}}
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">أسئلة الاستبيان</h3>
                    <button type="button" @click="addQuestion()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                        <span>+ إضافة سؤال</span>
                    </button>
                </div>

                <div class="space-y-6">
                    <template x-for="(question, qIndex) in questions" :key="question.id">
                        <div class="border border-gray-200 rounded-xl p-5 bg-gray-50 relative transition hover:shadow-md">
                            
                            {{-- Header --}}
                            <div class="flex justify-between items-start mb-4 border-b border-gray-200 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full" x-text="qIndex + 1"></span>
                                    <span class="text-sm font-semibold text-gray-500">نوع السؤال:</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="moveQuestion(qIndex, -1)" :disabled="qIndex === 0" class="p-1 hover:bg-gray-200 rounded disabled:opacity-30">⬆️</button>
                                    <button type="button" @click="moveQuestion(qIndex, 1)" :disabled="qIndex === questions.length - 1" class="p-1 hover:bg-gray-200 rounded disabled:opacity-30">⬇️</button>
                                    <button type="button" @click="removeQuestion(qIndex)" class="text-red-500 hover:bg-red-50 p-1 rounded px-2 text-sm font-bold">حذف</button>
                                </div>
                            </div>

                            {{-- Inputs --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">نص السؤال</label>
                                    <input type="text" x-model="question.question_text" 
                                           class="w-full border border-gray-300 rounded-lg p-2 focus:border-blue-500" placeholder="اكتب السؤال هنا..." required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">النوع</label>
                                    <select x-model="question.type" @change="handleTypeChange(qIndex)" 
                                            class="w-full border border-gray-300 rounded-lg p-2">
                                        <option value="text">نصي (إجابة مفتوحة)</option>
                                        <option value="single">اختيار من متعدد (Radio)</option>
                                        <option value="multiple">صناديق اختيار (Checkbox)</option>
                                        <option value="dropdown">قائمة منسدلة (Dropdown)</option>
                                        <option value="range">مقياس رقمي (Range)</option>
                                        <option value="date">تاريخ</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Range Options --}}
                            <div x-show="question.type === 'range'" class="bg-white p-3 rounded border border-gray-200 mb-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs">الحد الأدنى</label>
                                    <input type="number" x-model="question.min_value" class="w-full border rounded p-1">
                                </div>
                                <div>
                                    <label class="text-xs">الحد الأقصى</label>
                                    <input type="number" x-model="question.max_value" class="w-full border rounded p-1">
                                </div>
                            </div>

                            {{-- Parent Linkage (Dropdown only) --}}
                            <div x-show="question.type === 'dropdown'" class="mb-4 bg-blue-50 p-3 rounded border border-blue-100">
                                <label class="block text-sm font-medium text-blue-800 mb-1">🔗 ربط بسؤال سابق (قوائم مرتبطة)</label>
                                <select x-model.number="question.parent_question_index" @change="handleParentChange(qIndex)" 
                                        class="w-full border border-blue-200 rounded p-2 text-sm">
                                    <option :value="null">بدون ربط (قائمة مستقلة)</option>
                                    <template x-for="(possibleParent, pIdx) in questions" :key="possibleParent.id">
                                        <option x-show="pIdx < qIndex && ['dropdown', 'single'].includes(possibleParent.type)" 
                                                :value="pIdx" 
                                                x-text="`سؤال ${pIdx + 1}: ${possibleParent.question_text}`"></option>
                                    </template>
                                </select>
                            </div>

                            {{-- Choices Section --}}
                            <div x-show="['single','multiple','dropdown'].includes(question.type)" class="bg-gray-100 p-4 rounded-lg">
                                
                                {{-- A: Independent Choices --}}
                                <div x-show="question.parent_question_index === null">
                                    <div class="space-y-2">
                                        <template x-for="(choice, cIndex) in question.static_choices" :key="choice.id">
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-400">⚪</span>
                                                <input type="text" x-model="choice.text" placeholder="خيار جديد..." 
                                                       class="flex-1 border border-gray-300 rounded p-2 text-sm">
                                                <button type="button" @click="removeStaticChoice(qIndex, cIndex)" class="text-red-500 hover:text-red-700">✖</button>
                                            </div>
                                        </template>
                                    </div>
                                    <button type="button" @click="addStaticChoice(qIndex)" class="mt-2 text-sm text-blue-600 hover:underline">+ إضافة خيار</button>
                                </div>

                                {{-- B: Dependent Choices --}}
                                <div x-show="question.parent_question_index !== null">
                                    <template x-if="questions[question.parent_question_index]">
                                        <div class="space-y-4">
                                            <p class="text-sm text-gray-600">قم بتعريف الخيارات التي ستظهر بناءً على إجابة السؤال الأب:</p>
                                            
                                            <template x-for="(parentChoice, pcIdx) in getParentChoices(question.parent_question_index)" :key="parentChoice.id">
                                                <div class="bg-white border border-gray-200 rounded p-3">
                                                    <div class="text-sm font-bold text-gray-800 mb-2 border-b pb-1">
                                                        عند اختيار: <span class="text-blue-600" x-text="parentChoice.text || `(خيار ${pcIdx+1})`"></span>
                                                    </div>

                                                    <div class="space-y-2 pl-4 border-r-2 border-gray-100">
                                                        <template x-for="(childChoice, ccIdx) in (question.mapped_choices[pcIdx] || [])" :key="ccIdx">
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-gray-300">↳</span>
                                                                <input type="text" x-model="childChoice.text" placeholder="خيار فرعي..." 
                                                                    class="flex-1 border border-gray-200 rounded p-1 text-sm bg-gray-50">
                                                                <button type="button" @click="removeMappedChoice(qIndex, pcIdx, ccIdx)" class="text-red-400">✖</button>
                                                            </div>
                                                        </template>
                                                        
                                                        <button type="button" @click="addMappedChoice(qIndex, pcIdx)" class="text-xs text-green-600 font-bold hover:underline">
                                                            + إضافة خيار فرعي
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>

                            </div>

                            {{-- 
                                3. HIDDEN INPUT GENERATOR (THE CRITICAL PART) 
                                This converts the JS objects into PHP Array format for Laravel
                            --}}
                            <input type="hidden" :name="`questions[${qIndex}][question_text]`" :value="question.question_text">
                            <input type="hidden" :name="`questions[${qIndex}][type]`" :value="question.type">
                            <input type="hidden" :name="`questions[${qIndex}][ordered]`" :value="qIndex">
                            <input type="hidden" :name="`questions[${qIndex}][parent_question_index]`" :value="question.parent_question_index">
                            
                            <template x-if="question.type === 'range'">
                                <div>
                                    <input type="hidden" :name="`questions[${qIndex}][min_value]`" :value="question.min_value">
                                    <input type="hidden" :name="`questions[${qIndex}][max_value]`" :value="question.max_value">
                                </div>
                            </template>

                            {{-- Flatten Choices for Submission --}}
                            <template x-for="(fc, fcIdx) in getFlatChoices(qIndex)" :key="fcIdx">
                                <div>
                                    <input type="hidden" :name="`questions[${qIndex}][choices][${fcIdx}][choice_text]`" :value="fc.text">
                                    <input type="hidden" :name="`questions[${qIndex}][choices][${fcIdx}][parent_choice_index]`" :value="fc.parent_choice_index">
                                </div>
                            </template>

                        </div>
                    </template>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow transition">
                    حفظ ونشر الاستبيان
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('questionnaireForm', () => ({
                questions: [{
                    id: Date.now(),
                    question_text: '',
                    type: 'text',
                    min_value: 1,
                    max_value: 5,
                    parent_question_index: null,
                    static_choices: [{ id: Date.now() + 1, text: '' }],
                    mapped_choices: {}, 
                }],

                addQuestion() {
                    this.questions.push({
                        id: Date.now(),
                        question_text: '',
                        type: 'text',
                        min_value: 1,
                        max_value: 5,
                        parent_question_index: null,
                        static_choices: [{ id: Date.now() + 1, text: '' }],
                        mapped_choices: {}
                    });
                },

                removeQuestion(index) {
                    if (!confirm('هل أنت متأكد من حذف هذا السؤال؟')) return;
                    
                    const isParent = this.questions.some(q => q.parent_question_index === index);
                    if(isParent) {
                        alert('لا يمكن حذف هذا السؤال لأنه مرتبط بأسئلة أخرى تابعة له. يرجى فك الارتباط أولاً.');
                        return;
                    }

                    this.questions.splice(index, 1);
                    // Shift parent indices for questions below the deleted one
                    this.questions.forEach(q => {
                        if (q.parent_question_index !== null && q.parent_question_index > index) {
                            q.parent_question_index--;
                        }
                    });
                },

                moveQuestion(index, direction) {
                    const newIndex = index + direction;
                    if (newIndex < 0 || newIndex >= this.questions.length) return;

                    const temp = this.questions[index];
                    this.questions[index] = this.questions[newIndex];
                    this.questions[newIndex] = temp;

                    // Safety: reset dependencies if moved to avoid logic complexity
                    this.questions.forEach(q => {
                        if (q.parent_question_index === index || q.parent_question_index === newIndex) {
                            q.parent_question_index = null;
                            q.mapped_choices = {};
                        }
                    });
                },

                handleTypeChange(qIndex) {
                    const q = this.questions[qIndex];
                    if (q.type !== 'dropdown') {
                        q.parent_question_index = null;
                        q.mapped_choices = {};
                    }
                    if (!['single','multiple','dropdown'].includes(q.type)) {
                        q.static_choices = [];
                    } else if (q.static_choices.length === 0) {
                        q.static_choices = [{ id: Date.now(), text: '' }];
                    }
                },

                handleParentChange(qIndex) {
                    const q = this.questions[qIndex];
                    q.mapped_choices = {}; 
                    if (q.parent_question_index === null && q.static_choices.length === 0) {
                        q.static_choices = [{ id: Date.now(), text: '' }];
                    }
                },

                // --- Choice Logic ---
                addStaticChoice(qIndex) {
                    this.questions[qIndex].static_choices.push({ id: Date.now(), text: '' });
                },

                removeStaticChoice(qIndex, cIndex) {
                    if(!confirm('حذف الخيار؟')) return;
                    
                    this.questions[qIndex].static_choices.splice(cIndex, 1);

                    // UPDATE DEPENDENTS: If this question is a parent, we must shift the child mappings
                    this.questions.forEach(q => {
                        if (q.parent_question_index === qIndex) {
                            const newMap = {};
                            // Loop through existing map and shift keys
                            Object.keys(q.mapped_choices).forEach(key => {
                                const kIdx = parseInt(key);
                                if (kIdx < cIndex) {
                                    newMap[kIdx] = q.mapped_choices[kIdx]; // Keep as is
                                } else if (kIdx > cIndex) {
                                    newMap[kIdx - 1] = q.mapped_choices[kIdx]; // Shift down
                                }
                                // If kIdx == cIndex, it is deleted (skipped)
                            });
                            q.mapped_choices = newMap;
                        }
                    });
                },

                getParentChoices(parentIndex) {
                    if (parentIndex === null || !this.questions[parentIndex]) return [];
                    return this.questions[parentIndex].static_choices;
                },

                addMappedChoice(qIndex, parentChoiceIdx) {
                    const q = this.questions[qIndex];
                    if (!q.mapped_choices[parentChoiceIdx]) {
                        q.mapped_choices[parentChoiceIdx] = [];
                    }
                    q.mapped_choices[parentChoiceIdx].push({ text: '' });
                },

                removeMappedChoice(qIndex, parentChoiceIdx, childIdx) {
                    this.questions[qIndex].mapped_choices[parentChoiceIdx].splice(childIdx, 1);
                },

                // --- FLATTENER: Converts JS structure to PHP Array ---
                getFlatChoices(qIndex) {
                    const q = this.questions[qIndex];
                    let flat = [];

                    if (q.parent_question_index === null) {
                        // Independent
                        if (q.static_choices && q.static_choices.length) {
                            q.static_choices.forEach(c => {
                                flat.push({ text: c.text, parent_choice_index: null });
                            });
                        }
                    } else {
                        // Dependent
                        if (q.mapped_choices) {
                            // We must maintain the order of keys to match parent indices
                            Object.keys(q.mapped_choices).forEach(pChoiceKey => {
                                const children = q.mapped_choices[pChoiceKey];
                                if (Array.isArray(children)) {
                                    children.forEach(child => {
                                        flat.push({ 
                                            text: child.text, 
                                            parent_choice_index: pChoiceKey 
                                        });
                                    });
                                }
                            });
                        }
                    }
                    return flat;
                }
            }));
        });
    </script>
</x-app-layout>