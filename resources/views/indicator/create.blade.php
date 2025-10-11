<x-app-layout>
    <x-slot name="header">
        إضافة مؤشر جديد
    </x-slot>
    
    <form method="POST" action="{{ route('indicator.store') }}">
        @csrf
        <div x-data="indicatorManagement" class="container py-2 mx-auto px-4" dir="rtl">
            <div class="bg-white shadow-md rounded-lg p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Main & Sub Criteria --}}
                    <div>
                        <label for="main_criteria" class="block text-gray-700 font-medium mb-1">المعيار الرئيسي</label>
                        <input id="main_criteria" name="main_criteria" value="{{ old('main_criteria') }}"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="المعيار الرئيسي" required>
                        @error('main_criteria')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sub_criteria" class="block text-gray-700 font-medium mb-1">المعيار الفرعي</label>
                        <input id="sub_criteria" name="sub_criteria" value="{{ old('sub_criteria') }}"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="المعيار الفرعي">
                        @error('sub_criteria')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Code & Title --}}
                    <div>
                        <label for="code" class="block text-gray-700 font-medium mb-1">رمز المؤشر</label>
                        <input id="code" name="code" value="{{ old('code') }}"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="MARA 5">
                        @error('code')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="title" class="block text-gray-700 font-medium mb-1">المؤشر</label>
                        <input id="title" name="title" value="{{ old('title') }}"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="عنوان المؤشر">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Owner & First Observation Date --}}
                    <div>
                        <label for="owner" class="block text-gray-700 font-medium mb-1">مالك المؤشر</label>
                        <input id="owner" name="owner" value="{{ old('owner') }}"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="دائرة الزكاة">
                        @error('owner')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="first_observation_date" class="block text-gray-700 font-medium mb-1">تاريخ الرصد
                            الأول</label>
                        <input id="first_observation_date" name="first_observation_date" type="month"
                            value="{{ old('first_observation_date') }}"
                            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500">
                        @error('first_observation_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-1 md:col-span-2">
                        <label for="description" class="block text-gray-700 font-medium mb-1">وصف المؤشر</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="مؤشر يقيس زيادة مبلغ إيرادات الزكاة">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label for="measurement_tool" class="block text-gray-700 font-medium mb-1">أداة القياس</label>
                        <input id="measurement_tool" name="measurement_tool" value="{{ old('measurement_tool') }}"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="التقارير البنكية ولجان الزكاة">
                        @error('measurement_tool')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="polarity" class="block text-gray-700 font-medium mb-1">قطبية القياس</label>
                        <select id="polarity" name="polarity"
                            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500">
                            <option value="">اختر...</option>
                            <option value="positive" {{ old('polarity') == 'positive' ? 'selected' : '' }}>موجبة
                            </option>
                            <option value="negative" {{ old('polarity') == 'negative' ? 'selected' : '' }}>سالبة
                            </option>
                        </select>
                        @error('polarity')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unit" class="block text-gray-700 font-medium mb-1">وحدة القياس</label>
                        <input id="unit" name="unit" value="{{ old('unit') }}"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="رقم">
                        @error('unit')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="formula" class="block text-gray-700 font-medium mb-1">معادلة القياس</label>
                        <input id="formula" name="formula" value="{{ old('formula') }}"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="(X / Y) × 100%">
                        @error('formula')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="polarity_description" class="block text-gray-700 font-medium mb-1">شرح قطبية
                            القياس</label>
                        <input id="polarity_description" name="polarity_description"
                            value="{{ old('polarity_description') }}"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="تزداد القيمة بارتفاع الإيرادات">
                        @error('polarity_description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="baseline_formula" class="block text-gray-700 font-medium mb-1">معادلة خط
                            الأساس</label>
                        <input id="baseline_formula" name="baseline_formula" value="{{ old('baseline_formula') }}"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="(Previous Year × Target %) + Previous Year">
                        @error('baseline_formula')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <div>
                        <label for="baseline_after_application" class="block text-gray-700 font-medium mb-1">خط الأساس
                            بعد التطبيق</label>
                        <input id="baseline_after_application" name="baseline_after_application"
                            value="{{ old('baseline_after_application') }}"
                            class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                            placeholder="1.5% (80,000,000)">
                        @error('baseline_after_application')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="survey_question" class="block text-gray-700 font-medium mb-1">سؤال
                        الاستبيان</label>

                    <input id="survey_question" name="survey_question" value="{{ old('survey_question') }}"
                        class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                        placeholder="سؤال للتحقق">
                    @error('survey_question')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="proposed_initiatives" class="block text-gray-700 font-medium mb-1">مبادرات ومشاريع
                        مقترحة</label>
                    <input id="proposed_initiatives" name="proposed_initiatives"
                        value="{{ old('proposed_initiatives') }}"
                        class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                        placeholder="مثال: حملات توعوية">
                    @error('proposed_initiatives')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="supporting_evidences" class="block text-gray-700 font-medium mb-1">
                        الدليل الداعم
                    </label>

                    <input id="supporting_evidences" name="supporting_evidences"
                        value="{{ old('supporting_evidences') }}"
                        class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                        placeholder="مثال: كشوف الحسابات المصرفية,تقارير وإحصائيات لجان الزكاة">
                    @error('supporting_evidences')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="target_for_indicator" class="block text-gray-700 font-medium mb-1">
                        المستهدف للمؤشر لهذا العام
                        <span x-text="current_year" class="text-blue-700"></span>:
                    </label>
                    <input id="target_for_indicator" name="target_for_indicator"
                        value="{{ old('target_for_indicator') }}"
                        class="w-full border rounded px-3 py-2 placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 focus:border-blue-500"
                        placeholder="1.5% (80,000,000)">
                    @error('target_for_indicator')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4 flex items-center">
                    <label for="measurementFrequencySelect" class="font-semibold text-gray-700">دورية قياس
                        المستهدف:</label>
                    <select id="measurementFrequencySelect"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        <option value="annually">سنوي</option>
                        <option value="half_yearly">نصف سنوي</option>
                        <option value="quarterly">ربع سنوي</option>
                        <option value="monthly">شهري</option>
                    </select>
                </div>

            </div>
            <div class="mt-6 text-center">
                <button type="submit"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded transition duration-150">
                    حفظ وتابع
                </button>
            </div>
        </div>


    </form>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('indicatorManagement', () => ({
                // --- General State ---
                current_year: new Date().getFullYear(),

            }));
        });
    </script>
</x-app-layout>
