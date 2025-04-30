<x-app-layout>
    <div class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-white font-bold p-4 shadow-lg">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl md:text-4xl">إدارة المؤشرات</h1>
        </div>
    </div>

    <div x-data="indicatorManagement()" class="container py-8 mx-auto px-4">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-gray-200 mb-8">
            <table class="min-w-full divide-y divide-gray-200 text-right" dir="rtl">
                <tbody class="divide-y divide-gray-200">
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            المعيار الرئيسي</th>
                        <td class="p-4 text-base text-gray-900">الوعظ والإرشاد (دائرة الزكاة)</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            المعيار الفرعي</th>
                        <td class="p-4 text-base text-gray-900">(دائرة الزكاة)</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            رمز المؤشر</th>
                        <td class="p-4 text-base text-gray-900">5</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            المؤشر</th>
                        <td class="p-4 text-base text-gray-900">رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            مالك المؤشر</th>
                        <td class="p-4 text-gray-900">
                            <div class="flex flex-wrap gap-6">
                                <label class="inline-flex items-center text-gray-800">
                                    <input type="checkbox"
                                        class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 focus:border-blue-500 border-gray-300 transition duration-150 ease-in-out"
                                        checked>
                                    <span class="mr-2 text-sm md:text-base">دائرة الزكاة</span>
                                </label>
                                <label class="inline-flex items-center text-gray-800">
                                    <input type="checkbox"
                                        class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 focus:border-blue-500 border-gray-300 transition duration-150 ease-in-out">
                                    <span class="mr-2 text-sm md:text-base">مسقط</span>
                                </label>
                                <label class="inline-flex items-center text-gray-800">
                                    <input type="checkbox"
                                        class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 focus:border-blue-500 border-gray-300 transition duration-150 ease-in-out">
                                    <span class="mr-2 text-sm md:text-base">صلالة</span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            وصف المؤشر</th>
                        <td class="p-4 text-base text-gray-900">مؤشر يقيس زيادة مبلغ إيرادات الزكاة</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            أداة القياس</th>
                        <td class="p-4 text-base text-gray-900">البيانات المتوفرة في برنامج الزكاة والحسابات المصرفية
                            تقارير لجان الزكاة.</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            نوع الدليل الداعم</th>
                        <td class="p-4 text-base text-gray-900">
                            <table class="min-w-full text-right rtl text-sm border border-gray-200 rounded-lg shadow">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 border-b">المستند</th>
                                        <th class="px-4 py-3 border-b">القالب</th>
                                        <th class="px-4 py-3 border-b">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <template x-for="(evidence, index) in supportingEvidences" :key="index">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3" x-text="evidence.document"></td>
                                            <td class="px-4 py-3">
                                                <a href="#"
                                                    class="inline-flex items-center text-blue-600 hover:underline">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                                    </svg>
                                                    تحميل القالب
                                                </a>
                                            </td>
                                            <td class="px-4 py-3">
                                                <button @click="removeSupportingEvidence(index)"
                                                    class="text-red-600 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-md transition duration-150 ease-in-out"
                                                    aria-label="Remove evidence">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                            <button @click="openEvidenceModal()" type="button"
                                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                <i class="fas fa-plus ml-2"></i> إضافة دليل داعم
                            </button>
                        </td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            دورية القياس</th>
                        <td class="p-4 text-base text-gray-900">
                            <select id="measurement_frequency"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-1/3 p-2.5 transition duration-150 ease-in-out">
                                <option value="monthly">شهري</option>
                                <option value="quarterly" selected>ربع سنوي</option>
                                <option value="half-yearly">نصف سنوي</option>
                                <option value="annually">سنوي</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            قطبية القياس</th>
                        <td class="p-4 text-base text-gray-900">موجبة</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            شرح قطبية القياس</th>
                        <td class="p-4 text-base text-gray-900">موجبة حيث يرتفع المؤشر بارتفاع إيرادات الزكاة.</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            وحدة القياس</th>
                        <td class="p-4 text-base text-gray-900">رقم</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            معادلة القياس</th>
                        <td class="p-4 text-base text-gray-900">رقم</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            تاريخ الرصد الأول</th>
                        <td class="p-4 text-base text-gray-900">يناير</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            معادلة احتساب خط الأساس للربع الأول في السنة الأولى من التطبيق</th>
                        <td class="p-4 text-base text-gray-900">خط الأساس (العوائد في العام السابق) * نسبة المستهدف
                            للعام الحالي + قيمة العام السابق</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            خط الأساس بعد التطبيق</th>
                        <td class="p-4 text-base text-gray-900">1.5% (80,000,000)</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            اسئلة الاستبيان (سؤال للتحقق)</th>
                        <td class="p-4 text-base text-gray-900">-</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            مبادرات ومشاريع مقترحة</th>
                        <td class="p-4 text-base text-gray-900">رفع الوعي المجتمعي بالزكاة، رفع مستوى فاعلية لجان
                            الزكاة.</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            المؤشرات الفرعية</th>
                        <td class="p-4 text-gray-900">
                            <div class="space-y-3">
                                <template x-if="subIndicators.length === 0">
                                    <p class="text-gray-500 text-sm">لا توجد مؤشرات فرعية مضافة.</p>
                                </template>
                                <template x-for="(subIndicator, index) in subIndicators" :key="index">
                                    <div
                                        class="flex items-center justify-between bg-gray-50 p-3 rounded-md border border-gray-200 shadow-sm">
                                        <span class="text-gray-800 text-sm md:text-base"
                                            x-text="subIndicator.name"></span>
                                        <button @click="removeSubIndicator(index)"
                                            class="text-red-600 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded-md transition duration-150 ease-in-out"
                                            aria-label="Remove sub-indicator">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </template>

                                <button @click="openModal()" type="button"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                    <i class="fas fa-plus ml-2"></i> إضافة مؤشر فرعي
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            صلاحيات تغذية المؤشر</th>
                        <td class="p-4 text-gray-900">
                            <div class="space-y-2 text-base">
                                <div>المستخدم1</div>
                                <div>المستخدم2</div>
                                <div>المستخدم3</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div x-data="targetTable()" class="p-6 bg-white rounded-xl shadow-xl space-y-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 text-right" dir="rtl">بيانات المستهدف</h2>
            <div
                class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 space-x-0 sm:space-x-4 rtl:space-x-reverse">
                <label for="measurement_mode" class="font-semibold text-gray-700">دورية قياس المستهدف:</label>
                <select id="measurement_mode" x-model="mode"
                    class="border border-gray-300 rounded-md px-4 py-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-gray-800 transition duration-150 ease-in-out">
                    <option value="quarterly">ربعي</option>
                    <option value="annual">سنوي</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 text-center text-sm divide-y divide-gray-300"
                    dir="rtl">
                    <thead class="bg-gray-100 font-bold">
                        <tr>
                            <th scope="col"
                                class="border border-gray-300 p-3 text-sm font-semibold text-gray-600 uppercase tracking-wider"
                                x-text="mode === 'quarterly' ? 'الربع' : 'السنة'"></th>
                            <th scope="col"
                                class="border border-gray-300 p-3 text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                القيمة المحققة</th>
                            <th scope="col"
                                class="border border-gray-300 p-3 text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                المستهدف</th>
                            <th scope="col"
                                class="border border-gray-300 p-3 text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                نسبة الإنجاز</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="(row, index) in currentData" :key="index">
                            <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                                <td class="p-3 border border-gray-300 text-sm font-medium text-gray-700"
                                    x-text="row.label"></td>
                                <td class="p-3 border border-gray-300 text-sm text-gray-700">
                                    <input type="number" x-model.number="row.value"
                                        class="w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white transition duration-150 ease-in-out"
                                        placeholder="أدخل القيمة" dir="rtl">
                                </td>
                                <td class="p-3 border border-gray-300 text-sm text-gray-700" x-text="row.target"></td>
                                <td class="p-3 border border-gray-300 text-sm font-semibold"
                                    :class="{
                                        'text-green-600': getPercentageValue(row) >= 100,
                                        'text-orange-600': getPercentageValue(row) > 50 && getPercentageValue(row) <
                                            100,
                                        'text-red-600': getPercentageValue(row) <= 50 && getPercentageValue(row) !== 0,
                                        'text-gray-500': getPercentageValue(row) === 0
                                    }"
                                    x-text="getPercentage(row)">
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="isModalOpen" @keydown.escape.window="closeModal()"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
            style="display: none;" role="dialog" aria-modal="true" aria-labelledby="modal-title">

            <div class="bg-white w-full max-w-md rounded-lg shadow-2xl overflow-hidden" x-show="isModalOpen"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                <div class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-white p-4">
                    <h3 id="modal-title" class="text-xl font-bold">إضافة مؤشر فرعي جديد</h3>
                </div>

                <div class="p-6 space-y-5 text-right" dir="rtl">
                    <div>
                        <label for="sub-indicator-name" class="block text-sm font-medium text-gray-700 mb-1">اسم
                            المؤشر الفرعي</label>
                        <input id="sub-indicator-name" x-model="newSubIndicator.name" type="text"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition duration-150 ease-in-out"
                            placeholder="أدخل اسم المؤشر الفرعي">
                    </div>

                    <div>
                        <label for="sub-indicator-description"
                            class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                        <textarea id="sub-indicator-description" x-model="newSubIndicator.description" rows="3"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition duration-150 ease-in-out"
                            placeholder="أدخل وصف المؤشر"></textarea>
                    </div>

                    <div>
                        <label for="sub-indicator-unit" class="block text-sm font-medium text-gray-700 mb-1">وحدة
                            القياس</label>
                        <select id="sub-indicator-unit" x-model="newSubIndicator.unit"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition duration-150 ease-in-out">
                            <option value="">اختر وحدة القياس</option>
                            <option value="number">رقم</option>
                            <option value="percentage">نسبة مئوية</option>
                            <option value="currency">عملة</option>
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200">
                    <button @click="closeModal()" type="button"
                        class="px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 ml-3 transition duration-150 ease-in-out">

                        إلغاء
                    </button>
                    <button @click="addSubIndicator()" type="button"
                        class="px-5 py-2.5 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">

                        حفظ
                    </button>
                </div>
            </div>
        </div>
        <div x-show="isEvidenceModalOpen" @keydown.escape.window="closeEvidenceModal()"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" style="display: none;"
        role="dialog" aria-modal="true" aria-labelledby="evidence-modal-title">

        <div class="bg-white w-full max-w-md rounded-lg shadow-2xl overflow-hidden" x-show="isEvidenceModalOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-white p-4">
                <h3 id="evidence-modal-title" class="text-xl font-bold">إضافة دليل داعم جديد</h3>
            </div>

            <div class="p-6 space-y-5 text-right" dir="rtl">
                <div>
                    <label for="evidence-document" class="block text-sm font-medium text-gray-700 mb-1">اسم
                        المستند</label>
                    <input id="evidence-document" x-model="newSupportingEvidence.document" type="text"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition duration-150 ease-in-out"
                        placeholder="أدخل اسم المستند">
                </div>

                <div>
                    <label for="evidence-template" class="block text-sm font-medium text-gray-700 mb-1">رابط
                        القالب</label>
                    <input id="evidence-template" x-model="newSupportingEvidence.template" type="file"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition duration-150 ease-in-out"
                        >
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200">
                <button @click="closeEvidenceModal()" type="button"
                    class="px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 ml-3 transition duration-150 ease-in-out">
                    إلغاء
                </button>
                <button @click="addSupportingEvidence()" type="button"
                    class="px-5 py-2.5 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                    حفظ
                </button>
            </div>
        </div>
    </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('indicatorManagement', () => ({
                isModalOpen: false,
                isEvidenceModalOpen: false,
                subIndicators: [{
                    name: 'متوسط قيمة المبالغ الموزعة للمستحقين',
                    description: 'يقيس متوسط المبلغ الذي يحصل عليه كل مستحق من أموال الزكاة.',
                    unit: 'currency'
                }],
                supportingEvidences: [{
                        document: 'كشوف الحسابات المصرفية'
                    },
                    {
                        document: 'تقرير من نظام الزكاة'
                    },
                    {
                        document: 'تقارير وإحصائيات لجان الزكاة'
                    }
                ],
                newSubIndicator: {
                    name: '',
                    description: '',
                    unit: ''
                },
                newSupportingEvidence: {
                    document: '',
                    template: ''
                },

                openModal() {
                    this.isModalOpen = true;
                },

                openEvidenceModal() {
                    this.isEvidenceModalOpen = true;
                },

                closeModal() {
                    this.isModalOpen = false;
                    this.resetForm();
                },

                closeEvidenceModal() {
                    this.isEvidenceModalOpen = false;
                    this.resetEvidenceForm();
                },

                addSubIndicator() {
                    if (this.newSubIndicator.name.trim() === '') {
                        alert('اسم المؤشر الفرعي لا يمكن أن يكون فارغاً.');
                        return;
                    }
                    this.subIndicators.push({
                        name: this.newSubIndicator.name.trim(),
                        description: this.newSubIndicator.description.trim(),
                        unit: this.newSubIndicator.unit
                    });

                    this.closeModal();
                },

                addSupportingEvidence() {
                    if (this.newSupportingEvidence.document.trim() === '') {
                        alert('اسم المستند لا يمكن أن يكون فارغاً.');
                        return;
                    }
                    this.supportingEvidences.push({
                        document: this.newSupportingEvidence.document.trim(),
                        template: this.newSupportingEvidence.template.trim()
                    });

                    this.closeEvidenceModal();
                },

                removeSubIndicator(index) {
                    if (confirm(
                            `هل أنت متأكد من حذف المؤشر الفرعي "${this.subIndicators[index].name}"؟`)) {
                        this.subIndicators.splice(index, 1);
                    }
                },

                removeSupportingEvidence(index) {
                    if (confirm(
                            `هل أنت متأكد من حذف المستند "${this.supportingEvidences[index].document}"؟`
                        )) {
                        this.supportingEvidences.splice(index, 1);
                    }
                },

                resetForm() {
                    this.newSubIndicator = {
                        name: '',
                        description: '',
                        unit: ''
                    };
                },

                resetEvidenceForm() {
                    this.newSupportingEvidence = {
                        document: '',
                        template: ''
                    };
                }
            }));
            Alpine.data('targetTable', () => ({
                mode: 'quarterly',
                quarterlyData: [{
                        label: 'الربع الأول',
                        value: '',
                        target: 20
                    },
                    {
                        label: 'الربع الثاني',
                        value: '',
                        target: 30
                    },
                    {
                        label: 'الربع الثالث',
                        value: '',
                        target: 40
                    },
                    {
                        label: 'الربع الرابع',
                        value: '',
                        target: 50
                    },
                ],
                annualData: [{
                        label: 'سنة 2024',
                        value: '',
                        target: 100
                    },
                    {
                        label: 'سنة 2025',
                        value: '',
                        target: 120
                    },
                ],
                get currentData() {
                    return this.mode === 'quarterly' ? this.quarterlyData : this.annualData;
                },
                getPercentage(row) {
                    if (typeof row.value !== 'number' || typeof row.target !== 'number' || row.target <=
                        0) {
                        return '0٪';
                    }
                    const percentage = ((row.value / row.target) * 100).toFixed(
                        1);
                    return `${percentage}٪`;
                },
                getPercentageValue(row) {
                    if (typeof row.value !== 'number' || typeof row.target !== 'number' || row.target <=
                        0) {
                        return 0;
                    }
                    return ((row.value / row.target) * 100);
                }
            }));
        });
    </script>
</x-app-layout>
