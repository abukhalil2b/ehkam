<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">إضافة مؤشر جديد</h1>
            <div class="text-sm text-gray-500 bg-blue-50 px-3 py-1 rounded-full">
                جميع الحقول ذات العلامة <span class="text-red-500">*</span> إلزامية
            </div>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('indicator.store') }}" class="bg-gray-50 min-h-screen py-6">
        @csrf
        <div x-data="indicatorManagement" class="container mx-auto px-4 max-w-5xl" dir="rtl">
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-between relative">
                    <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-200 -translate-y-1/2 z-0"></div>
                    <template x-for="(step, index) in steps" :key="index">
                        <div class="flex flex-col items-center relative z-10">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300"
                                :class="{
                                    'bg-blue-600 text-white': currentStep > index,
                                    'bg-white border-2 border-blue-600 text-blue-600': currentStep === index,
                                    'bg-white border-2 border-gray-300 text-gray-400': currentStep < index
                                }">
                                <span x-text="index + 1" class="font-medium"></span>
                            </div>
                            <span class="mt-2 text-sm font-medium"
                                :class="{
                                    'text-blue-600': currentStep >= index,
                                    'text-gray-500': currentStep < index
                                }"
                                x-text="step"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Form Sections -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <!-- Basic Information Section -->
                <div x-show="currentStep === 0" class="p-6 transition-all duration-300">
                    <div class="mb-6 pb-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            المعلومات الأساسية
                        </h2>
                        <p class="text-gray-600 mt-1">يرجى إدخال المعلومات الأساسية للمؤشر</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Main & Sub Criteria -->
                        <div>
                            <label for="main_criteria" class="block text-gray-700 font-medium mb-2">
                                المعيار الرئيسي <span class="text-red-500">*</span>
                            </label>
                            <input id="main_criteria" name="main_criteria" value="{{ old('main_criteria') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="أدخل المعيار الرئيسي" required>
                            @error('main_criteria')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="sub_criteria" class="block text-gray-700 font-medium mb-2">المعيار
                                الفرعي</label>
                            <input id="sub_criteria" name="sub_criteria" value="{{ old('sub_criteria') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="أدخل المعيار الفرعي">
                            @error('sub_criteria')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Code & Title -->
                        <div>
                            <label for="code" class="block text-gray-700 font-medium mb-2">رمز المؤشر</label>
                            <input id="code" name="code" value="{{ old('code') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="MARA 5">
                            @error('code')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="title" class="block text-gray-700 font-medium mb-2">المؤشر</label>
                            <input id="title" name="title" value="{{ old('title') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="عنوان المؤشر">
                            @error('title')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Owner & First Observation Date -->
                        <div>
                            <label for="owner" class="block text-gray-700 font-medium mb-2">مالك المؤشر</label>
                            <input id="owner" name="owner" value="{{ old('owner') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="دائرة الزكاة">
                            @error('owner')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="first_observation_date" class="block text-gray-700 font-medium mb-2">تاريخ
                                الرصد الأول</label>
                            <div class="relative">
                                <input id="first_observation_date" name="first_observation_date" type="date"
                                    value="{{ old('first_observation_date') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            @error('first_observation_date')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="description" class="block text-gray-700 font-medium mb-2">وصف المؤشر</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="مؤشر يقيس زيادة مبلغ إيرادات الزكاة">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Measurement Details Section -->
                <div x-show="currentStep === 1" class="p-6 transition-all duration-300">
                    <div class="mb-6 pb-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            تفاصيل القياس
                        </h2>
                        <p class="text-gray-600 mt-1">يرجى تحديد تفاصيل قياس المؤشر</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="polarity" class="block text-gray-700 font-medium mb-2">قطبية القياس</label>
                            <select id="polarity" name="polarity"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">اختر...</option>
                                <option value="positive" {{ old('polarity') == 'positive' ? 'selected' : '' }}>موجبة
                                </option>
                                <option value="negative" {{ old('polarity') == 'negative' ? 'selected' : '' }}>سالبة
                                </option>
                            </select>
                            @error('polarity')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit" class="block text-gray-700 font-medium mb-2">وحدة القياس</label>
                            <input id="unit" name="unit" value="{{ old('unit') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="رقم">
                            @error('unit')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="formula" class="block text-gray-700 font-medium mb-2">معادلة القياس</label>
                            <input id="formula" name="formula" value="{{ old('formula') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="(X / Y) × 100%">
                            @error('formula')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="measurement_tool" class="block text-gray-700 font-medium mb-2">أداة القياس</label>
                        <input id="measurement_tool" name="measurement_tool" value="{{ old('measurement_tool') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="التقارير البنكية ولجان الزكاة">
                        @error('measurement_tool')
                            <p class="text-red-600 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <label for="polarity_description" class="block text-gray-700 font-medium mb-2">شرح قطبية
                            القياس</label>
                        <input id="polarity_description" name="polarity_description"
                            value="{{ old('polarity_description') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="تزداد القيمة بارتفاع الإيرادات">
                        @error('polarity_description')
                            <p class="text-red-600 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Baseline & Targets Section -->
                <div x-show="currentStep === 2" class="p-6 transition-all duration-300">
                    <div class="mb-6 pb-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            خط الأساس والأهداف
                        </h2>
                        <p class="text-gray-600 mt-1">يرجى تحديد خط الأساس والأهداف للمؤشر</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="baseline_formula" class="block text-gray-700 font-medium mb-2">معادلة خط
                                الأساس</label>
                            <input id="baseline_formula" name="baseline_formula"
                                value="{{ old('baseline_formula') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="(Previous Year × Target %) + Previous Year">
                            @error('baseline_formula')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="baseline_after_application" class="block text-gray-700 font-medium mb-2">خط
                                الأساس بعد التطبيق</label>
                            <input id="baseline_after_application" name="baseline_after_application"
                                value="{{ old('baseline_after_application') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="1.5% (80,000,000)">
                            @error('baseline_after_application')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="target_for_indicator" class="block text-gray-700 font-medium mb-2">
                                المستهدف للمؤشر لهذا العام
                                <span x-text="current_year" class="text-blue-700 font-bold"></span>:
                            </label>
                            <input id="target_for_indicator" name="target_for_indicator"
                                value="{{ old('target_for_indicator') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="1.5% (80,000,000)">
                            @error('target_for_indicator')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="period" class="block text-gray-700 font-medium mb-2">دورة القياس
                                (الفترة)</label>
                            <select id="period" name="period"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="annually">سنوي</option>
                                <option value="half_yearly">نصف سنوي</option>
                                <option value="quarterly">ربع سنوي</option>
                                <option value="monthly">شهري</option>
                            </select>
                            @error('period')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div x-show="currentStep === 3" class="p-6 transition-all duration-300">
                    <div class="mb-6 pb-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            معلومات إضافية
                        </h2>
                        <p class="text-gray-600 mt-1">يرجى إدخال المعلومات الإضافية للمؤشر</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="survey_question" class="block text-gray-700 font-medium mb-2">سؤال
                                الاستبيان</label>
                            <input id="survey_question" name="survey_question" value="{{ old('survey_question') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="سؤال للتحقق">
                            @error('survey_question')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="proposed_initiatives" class="block text-gray-700 font-medium mb-2">مبادرات
                                ومشاريع مقترحة</label>
                            <input id="proposed_initiatives" name="proposed_initiatives"
                                value="{{ old('proposed_initiatives') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="مثال: حملات توعوية">
                            @error('proposed_initiatives')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="supporting_evidences" class="block text-gray-700 font-medium mb-2">الدليل
                                الداعم</label>
                            <input id="supporting_evidences" name="supporting_evidences"
                                value="{{ old('supporting_evidences') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="مثال: كشوف الحسابات المصرفية,تقارير وإحصائيات لجان الزكاة">
                            @error('supporting_evidences')
                                <p class="text-red-600 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-8 flex justify-between">
                <button type="button" x-show="currentStep > 0" @click="currentStep--"
                    class="bg-white text-blue-600 border border-blue-600 hover:bg-blue-50 font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                    السابق
                </button>

                <button type="button" x-show="currentStep < steps.length - 1" @click="currentStep++"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center">
                    التالي
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>

                <button type="submit" x-show="currentStep === steps.length - 1"
                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    حفظ المؤشر
                </button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('indicatorManagement', () => ({
                // --- General State ---
                current_year: new Date().getFullYear(),

                // --- Multi-step Form ---
                currentStep: 0,
                steps: [
                    'المعلومات الأساسية',
                    'تفاصيل القياس',
                    'خط الأساس والأهداف',
                    'معلومات إضافية'
                ],
            }));
        });
    </script>
</x-app-layout>
