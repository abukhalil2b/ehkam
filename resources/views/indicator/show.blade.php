<x-app-layout>
    <div class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-white font-bold p-4 shadow-lg">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl md:text-4xl">إدارة المؤشر</h1>
        </div>
    </div>

    <div x-data="indicatorManagement()" class="container py-2 mx-auto px-4">


        <!-- Year Target & Frequency Selector -->
        <div class="p-6 bg-white rounded-xl shadow space-y-4 border mb-8">
            <h2 class="text-xl font-bold text-gray-700">
                بيانات المستهدف للمؤشر لهذا العام
                <span x-text="current_year" class="text-blue-700"></span>:
                <span class="text-red-800" x-text="current_year_target.toLocaleString()"></span>
            </h2>

            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <label for="measurementFrequencySelect" class="font-semibold text-gray-700">دورية قياس
                    المستهدف:</label>
                <select id="measurementFrequencySelect" x-model="measurementFrequency"
                    @change="updateNewContributePeriods()"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    <option value="annually">سنوي</option>
                    <option value="half_yearly">نصف سنوي</option>
                    <option value="quarterly">ربع سنوي</option>
                    <option value="monthly">شهري</option>
                </select>
            </div>
        </div>

        <!-- Contributing Sectors Section -->
        <div class="p-6 bg-white rounded-xl shadow space-y-4 border">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-700">الجهات المساندة:</h2>
                <button @click="openContributeModal()"
                    class="inline-flex items-center gap-2 px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out"
                    aria-label="إضافة جهة مساندة">
                    <i class="fas fa-plus"></i>
                    <span>إضافة</span>
                </button>
            </div>

            <template x-if="contributes.length > 0">
                <div class="space-y-4">
                    <template x-for="(c, idx) in contributes" :key="idx">
                        <div class="p-4 bg-gray-50 rounded-xl shadow space-y-3 border border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-gray-800" x-text="c.name"></div>
                                <button @click="removeContribute(idx)" aria-label="إزالة مساهمة"
                                    class="px-3 py-1 bg-red-600 text-white text-xs rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                    <i class="fas fa-trash-alt">إزالة</i>
                                    <span class="sr-only"></span>
                                </button>
                            </div>
                            <div class="flex gap-2 text-sm text-gray-600">
                                <div>المستهدف للمساهمة:</div>
                                <span class="font-medium text-gray-800" x-text="c.target.toLocaleString()"></span>
                            </div>
                            <div class="overflow-x-auto">
                                <table role="table"
                                    class="min-w-full border text-right border-gray-300 rounded-md overflow-hidden"
                                    dir="rtl">
                                    <thead class="bg-gray-200 text-xs text-gray-700">
                                        <tr>
                                            <template x-for="(p, pidx) in c.periods" :key="pidx">
                                                <th class="p-2 border-l border-gray-300" x-text="p.name"></th>
                                            </template>
                                            <th class="p-2 bg-gray-300">إجمالي النسبة من الهدف السنوي</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                        <tr>
                                            <template x-for="(p, pidx) in c.periods" :key="pidx">
                                                <td class="p-2 border-l border-gray-300 align-top">
                                                    <div>المستهدف: <span class="font-semibold"
                                                            x-text="p.target.toLocaleString()"></span></div>
                                                    <div x-show="c.target > 0">بنسبة: <span class="font-semibold"
                                                            x-text="((p.target / c.target) * 100).toFixed(1) + '%'"></span>
                                                    </div>
                                                </td>
                                            </template>
                                            <td class="p-2 bg-gray-100 align-top font-semibold">
                                                <div x-show="c.target > 0">
                                                    <span
                                                        x-text="((c.target / current_year_target) * 100).toFixed(2) + '%'"></span>
                                                </div>
                                                <div x-show="c.target === 0">-</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            <template x-if="contributes.length === 0">
                <p class="text-gray-500 text-sm text-center py-4">لا توجد جهات مساندة مضافة.</p>
            </template>
        </div>

        <!-- Contribute Modal -->
        <div x-show="isContributeModalOpen" @keydown.escape.window="closeContributeModal()"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50" role="dialog"
            aria-modal="true" aria-labelledby="contribute-modal-title">
            <div @click.away="closeContributeModal()"
                class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 space-y-4">
                <h3 id="contribute-modal-title" class="text-lg font-bold text-gray-800">إضافة جهة مساندة</h3>

                <div class="space-y-1">
                    <label for="contribute-name" class="text-sm font-medium text-gray-700">الجهة</label>
                    <select id="contribute-name" x-model="newContribute.name"
                        :class="{ 'border-red-500': formErrors.newContributeName }"
                        class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled>اختر الجهة</option>
                        <template x-for="(s, i) in sectorNames" :key="i">
                            <option :value="s" x-text="s"></option>
                        </template>
                    </select>
                    <p x-show="formErrors.newContributeName" x-text="formErrors.newContributeName"
                        class="text-xs text-red-600"></p>
                </div>

                <div class="space-y-1">
                    <label for="contribute-target" class="text-sm font-medium text-gray-700">المستهدف الإجمالي
                        للمساهمة</label>
                    <input id="contribute-target" type="number" x-model.number="newContribute.target"
                        @input="validateNewContributeTarget()"
                        :class="{ 'border-red-500': formErrors.newContributeTarget }"
                        class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500"
                        min="0" />
                    <p x-show="formErrors.newContributeTarget" x-text="formErrors.newContributeTarget"
                        class="text-xs text-red-600"></p>
                </div>

                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-700">دورية المستهدف</label>
                    <input type="text" :value="measurementFrequencyLabel" readonly
                        class="w-full border rounded p-2 bg-gray-100 text-gray-600" />
                </div>

                <template x-if="newContribute.periods.length > 0">
                    <div class="space-y-3 border p-3 rounded-md bg-gray-50">
                        <h4 class="text-sm font-semibold text-gray-700">توزيع المستهدف على الفترات:</h4>
                        <template x-for="(p, i) in newContribute.periods" :key="i">
                            <div class="space-y-1">
                                <label :for="'period-target-' + i" class="text-sm font-medium text-gray-600"
                                    x-text="p.name"></label>
                                <input :id="'period-target-' + i" type="number"
                                    x-model.number="newContribute.periods[i].target"
                                    @input="validateNewContributeTarget()"
                                    :class="{ 'border-red-500': formErrors.newContributePeriodsSum }"
                                    class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500"
                                    min="0" />
                            </div>
                        </template>
                        <p x-show="formErrors.newContributePeriodsSum" x-text="formErrors.newContributePeriodsSum"
                            class="text-xs text-red-600"></p>
                    </div>
                </template>

                <div class="flex justify-end space-x-2 rtl:space-x-reverse pt-4">
                    <button @click="closeContributeModal()" type="button"
                        class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        إلغاء
                    </button>
                    <button @click="addContribute()" :disabled="!isNewContributeValid" type="button"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        إضافة
                    </button>
                </div>
            </div>
        </div>

        <!-- Evidence Modal -->
        <div x-cloak x-show="isEvidenceModalOpen" @keydown.escape.window="closeEvidenceModal()"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50" role="dialog"
            aria-modal="true" aria-labelledby="evidence-modal-title">
            <div class="bg-white w-full max-w-md rounded-lg shadow-2xl overflow-hidden"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                <div
                    class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-white p-4 flex justify-between items-center">
                    <h3 id="evidence-modal-title" class="text-xl font-bold">إضافة دليل داعم جديد</h3>
                    <button @click="closeEvidenceModal()" aria-label="إغلاق" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-5 text-right" dir="rtl">
                    <div>
                        <label for="evidence-document" class="block text-sm font-medium text-gray-700 mb-1">اسم
                            المستند</label>
                        <input id="evidence-document" x-model.trim="newSupportingEvidence.document" type="text"
                            :class="{ 'border-red-500': formErrors.newEvidenceDocument }"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition duration-150 ease-in-out"
                            placeholder="أدخل اسم المستند">
                        <p x-show="formErrors.newEvidenceDocument" x-text="formErrors.newEvidenceDocument"
                            class="text-xs text-red-600 mt-1"></p>
                    </div>

                    <div>
                        <label for="evidence-template-url" class="block text-sm font-medium text-gray-700 mb-1">رابط
                            القالب (اختياري)</label>
                        <input id="evidence-template-url" x-model.trim="newSupportingEvidence.template"
                            type="url"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition duration-150 ease-in-out"
                            placeholder="https://example.com/template.pdf">
                    </div>
                </div>

                <div
                    class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 rtl:space-x-reverse border-t border-gray-200">
                    <button @click="closeEvidenceModal()" type="button"
                        class="px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        إلغاء
                    </button>
                    <button @click="addSupportingEvidence()" type="button"
                        class="px-5 py-2.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        حفظ
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('indicatorManagement', () => ({
                // --- General State ---
                current_year: new Date().getFullYear(),

                // --- Modal States ---
                isContributeModalOpen: false,
                isEvidenceModalOpen: false,

                // --- Form Errors ---
                formErrors: {
                    newContributeName: '',
                    newContributeTarget: '',
                    newContributePeriodsSum: '',
                    newEvidenceDocument: ''
                },


                sectorNames: [
                    'ديوان عام الوزارة',
                    'إدارة الأوقاف والشؤون الدينة بمحافظة جنوب الباطنة',
                    'إدارة الأوقاف والشؤون الدينة بمحافظة شمال الباطنة',
                    'إدارة الأوقاف والشؤون الدينة بمحافظة الداخلية',
                    'إدارة الأوقاف والشؤون الدينة بمحافظة الظاهرة',
                    'إدارة الأوقاف والشؤون الدينة الوسطى',
                    'إدارة الأوقاف والشؤون بمحافظة ظفار',
                    'لجنة الزكاة بولاية السيب',
                    'لجنة الزكاة بولاية العوابي',
                    'المؤسسة الوقفية بولاية بوشر',
                    'مؤسسة جابر بن زيد الوقفية',
                ],
                measurementFrequency: 'quarterly', // Default
                last_year_target: 80000000, // Example data
                current_year_target: 80500000, // Example data
                contributes: [{
                    name: 'ديوان عام الوزارة',
                    target: 40000,
                    periods: [{
                            name: 'الربع الأول',
                            target: 0
                        },
                        {
                            name: 'الربع الثاني',
                            target: 10000
                        },
                        {
                            name: 'الربع الثالث',
                            target: 10000
                        },
                        {
                            name: 'الربع الرابع',
                            target: 20000
                        },
                    ]
                }],
                newContribute: {
                    name: '',
                    target: 0,
                    periods: []
                },
                periodTemplates: {
                    annually: [{
                        name: 'السنة'
                    }],
                    half_yearly: [{
                        name: 'النصف الأول'
                    }, {
                        name: 'النصف الثاني'
                    }],
                    quarterly: [{
                            name: 'الربع الأول'
                        }, {
                            name: 'الربع الثاني'
                        },
                        {
                            name: 'الربع الثالث'
                        }, {
                            name: 'الربع الرابع'
                        }
                    ],
                    monthly: Array.from({
                        length: 12
                    }, (_, i) => ({
                        name: `شهر ${i + 1}`
                    }))
                },

                // --- Supporting Evidence ("الأدلة الداعمة") ---
                supportingEvidences: [{
                        document: 'كشوف الحسابات المصرفية',
                        template: 'https://example.com/bank_statement_template.pdf'
                    },
                    {
                        document: 'تقرير من نظام الزكاة',
                        template: ''
                    },
                    {
                        document: 'تقارير وإحصائيات لجان الزكاة',
                        template: 'https://example.com/zakat_stats_template.docx'
                    }
                ],
                newSupportingEvidence: {
                    document: '',
                    template: '' // Will store URL for the template
                },
                // --- Computed Properties ---
                get measurementFrequencyLabel() {
                    const map = {
                        annually: 'سنوي',
                        half_yearly: 'نصف سنوي',
                        quarterly: 'ربع سنوي',
                        monthly: 'شهري'
                    };
                    return map[this.measurementFrequency] || this.measurementFrequency;
                },

                get isNewContributeValid() {
                    this.validateNewContributeName();
                    this.validateNewContributeTarget(); // This also validates period sum

                    return !this.formErrors.newContributeName &&
                        !this.formErrors.newContributeTarget &&
                        !this.formErrors.newContributePeriodsSum;
                },

                // --- Methods for Contributing Sectors Modal ---
                initNewContribute() { // Call this to initialize or reset the form
                    this.newContribute = {
                        name: '',
                        target: 0,
                        periods: []
                    };
                    this.updateNewContributePeriods();
                    this.clearContributeFormErrors();
                },
                clearContributeFormErrors() {
                    this.formErrors.newContributeName = '';
                    this.formErrors.newContributeTarget = '';
                    this.formErrors.newContributePeriodsSum = '';
                },
                validateNewContributeName() {
                    if (!this.newContribute.name) {
                        this.formErrors.newContributeName = 'الرجاء اختيار الجهة.';
                    } else {
                        this.formErrors.newContributeName = '';
                    }
                },
                validateNewContributeTarget() {
                    if (this.newContribute.target <= 0) {
                        this.formErrors.newContributeTarget =
                            'المستهدف الإجمالي يجب أن يكون أكبر من صفر.';
                    } else {
                        this.formErrors.newContributeTarget = '';
                    }
                    this.validateNewContributePeriodsSum();
                },
                validateNewContributePeriodsSum() {
                    if (this.newContribute.target > 0 && this.newContribute.periods.length > 0) {
                        const periodsSum = this.newContribute.periods.reduce((sum, p) => sum + (Number(p
                            .target) || 0), 0);
                        if (periodsSum !== this.newContribute.target) {
                            this.formErrors.newContributePeriodsSum =
                                `مجموع مستهدفات الفترات (${periodsSum.toLocaleString()}) لا يساوي المستهدف الإجمالي (${this.newContribute.target.toLocaleString()}).`;
                        } else {
                            this.formErrors.newContributePeriodsSum = '';
                        }
                    } else {
                        this.formErrors.newContributePeriodsSum =
                            ''; // Clear if no target or no periods
                    }
                },
                openContributeModal() {
                    this.initNewContribute();
                    this.isContributeModalOpen = true;
                },
                closeContributeModal() {
                    this.isContributeModalOpen = false;
                },
                updateNewContributePeriods() { // Called when measurementFrequency changes or modal opens
                    const template = this.periodTemplates[this.measurementFrequency] || [];
                    this.newContribute.periods = template.map(p => ({
                        name: p.name,
                        target: 0
                    }));
                    this.validateNewContributeTarget(); // Re-validate sums if periods structure changes
                },
                addContribute() {
                    if (!this.isNewContributeValid) return;

                    this.contributes.push(JSON.parse(JSON.stringify(this.newContribute)));
                    this.closeContributeModal();
                },
                removeContribute(index) {
                    if (confirm(`هل أنت متأكد من حذف مساهمة "${this.contributes[index].name}"؟`)) {
                        this.contributes.splice(index, 1);
                    }
                },

                // --- Methods for Supporting Evidence Modal ---
                initNewSupportingEvidence() {
                    this.newSupportingEvidence = {
                        document: '',
                        template: ''
                    };
                    this.formErrors.newEvidenceDocument = '';
                },
                validateNewEvidenceDocument() {
                    if (this.newSupportingEvidence.document.trim() === '') {
                        this.formErrors.newEvidenceDocument = 'اسم المستند لا يمكن أن يكون فارغاً.';
                        return false;
                    }
                    this.formErrors.newEvidenceDocument = '';
                    return true;
                },
                openEvidenceModal() {
                    this.initNewSupportingEvidence();
                    this.isEvidenceModalOpen = true;
                },
                closeEvidenceModal() {
                    this.isEvidenceModalOpen = false;
                },
                addSupportingEvidence() {
                    if (!this.validateNewEvidenceDocument()) return;

                    this.supportingEvidences.push({
                        document: this.newSupportingEvidence.document.trim(),
                        template: this.newSupportingEvidence.template
                            .trim() // URL for the template
                    });
                    this.closeEvidenceModal();
                },
                removeSupportingEvidence(index) {
                    if (confirm(
                            `هل أنت متأكد من حذف المستند "${this.supportingEvidences[index].document}"؟`
                        )) {
                        this.supportingEvidences.splice(index, 1);
                    }
                },
            }));
        });
    </script>
</x-app-layout>
