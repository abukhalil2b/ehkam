<div class="space-y-6">
    {{-- Row 1: Criteria & Code --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <x-input-label for="main_criteria" :value="__('المعيار الرئيسي')" />
            <x-text-input id="main_criteria" name="main_criteria" type="text" class="mt-1 block w-full"
                :value="old('main_criteria', $indicator->main_criteria)" />
            <x-input-error class="mt-2" :messages="$errors->get('main_criteria')" />
        </div>
        <div>
            <x-input-label for="sub_criteria" :value="__('المعيار الفرعي')" />
            <x-text-input id="sub_criteria" name="sub_criteria" type="text" class="mt-1 block w-full"
                :value="old('sub_criteria', $indicator->sub_criteria)" />
            <x-input-error class="mt-2" :messages="$errors->get('sub_criteria')" />
        </div>
        <div>
            <x-input-label for="code" :value="__('رمز المؤشر')" />
            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" :value="old('code', $indicator->code)" />
            <x-input-error class="mt-2" :messages="$errors->get('code')" />
        </div>
    </div>
    <div>
        <label for="is_main" class="block text-gray-700 font-medium mb-2">هل رئيسي
            <span class="text-red-500">*</span>
        </label>
        <select name="is_main" id="is_main"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            <option value="1">نعم</option>
            <option value="0">لا</option>
        </select>
        @error('is_main')
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
    {{-- Row 2: Title, Owner, Period --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <x-input-label for="title" :value="__('عنوان المؤشر')" />
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required
                :value="old('title', $indicator->title)" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>
        <div>
            <x-input-label for="owner" :value="__('مالك المؤشر')" />
            <x-text-input id="owner" name="owner" type="text" class="mt-1 block w-full" :value="old('owner', $indicator->owner)" />
            <x-input-error class="mt-2" :messages="$errors->get('owner')" />
        </div>

        <div>
            <x-input-label for="period" :value="__('دورة القياس (الفترة)')" />
            <select id="period" name="period"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                required>
                <option value="">-- اختر دورة --</option>
                {{-- Loop through the array passed from the controller --}}
                @foreach ($periodOptions as $value => $label)
                    <option value="{{ $value }}"
                        {{ old('period', $indicator->period) == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('period')" />
        </div>
    </div>

    {{-- - START OF NEW SECTORS CHECKBOX BLOCK - --}}
    <div>
        <x-input-label :value="__('الجهات المساندة (القطاعات)')" class="mb-2" />
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 border border-gray-200 rounded-md">
            @foreach ($sectors as $sector)
                @php
                    // Determine if this sector ID should be checked (from old input or database)
                    $isChecked = in_array($sector->id, old('sectors', $selectedSectorIds));
                @endphp

                <div class="flex items-center">
                    {{-- The name="sectors[]" array ensures all checked values are sent to the controller --}}
                    <input id="sector_{{ $sector->id }}" name="sectors[]" type="checkbox" value="{{ $sector->id }}"
                        {{ $isChecked ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="sector_{{ $sector->id }}"
                        class="mr-2 text-sm font-medium text-gray-700 cursor-pointer">
                        {{ $sector->name }}
                    </label>
                </div>
            @endforeach
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('sectors')" />
    </div>
    {{-- - END OF NEW SECTORS CHECKBOX BLOCK - --}}

    {{-- Row 4: Description --}}
    <div>
        <x-input-label for="description" :value="__('وصف المؤشر')" />
        <textarea id="description" name="description" rows="3"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $indicator->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    {{-- Row 5: Measurement Tool & Unit --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="measurement_tool" :value="__('أداة القياس')" />
            <textarea id="measurement_tool" name="measurement_tool" rows="2"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('measurement_tool', $indicator->measurement_tool) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('measurement_tool')" />
        </div>
        <div>
            <x-input-label for="unit" :value="__('وحدة القياس')" />
            <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" :value="old('unit', $indicator->unit)" />
            <x-input-error class="mt-2" :messages="$errors->get('unit')" />
        </div>
    </div>

    {{-- Row 6: Polarity & Description --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="polarity" :value="__('قطبية القياس')" />
            <x-text-input id="polarity" name="polarity" type="text" class="mt-1 block w-full" :value="old('polarity', $indicator->polarity)" />
            <x-input-error class="mt-2" :messages="$errors->get('polarity')" />
        </div>
        <div>
            <x-input-label for="polarity_description" :value="__('شرح قطبية القياس')" />
            <textarea id="polarity_description" name="polarity_description" rows="2"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('polarity_description', $indicator->polarity_description) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('polarity_description')" />
        </div>
    </div>

    {{-- Row 7: Formula & First Observation Date --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="formula" :value="__('معادلة القياس')" />
            <x-text-input id="formula" name="formula" type="text" class="mt-1 block w-full font-mono text-left"
                dir="ltr" :value="old('formula', $indicator->formula)" />
            <x-input-error class="mt-2" :messages="$errors->get('formula')" />
        </div>
        <div>
            {{-- **FINAL DATE INPUT BLOCK** --}}
            <x-input-label for="first_observation_date" :value="__('تاريخ الرصد الأول')" />

            {{-- Using type="date" and binding to the YYYY-MM-DD value --}}
            <input id="first_observation_date" name="first_observation_date" type="date"
                value="{{ old('first_observation_date', $cleanDate ?? $indicator->first_observation_date) }}"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                style="padding-top: 0.5rem; padding-bottom: 0.5rem;" />

            <x-input-error class="mt-2" :messages="$errors->get('first_observation_date')" />
            <small class="text-gray-500">سيتم حفظ التاريخ بصيغة السنة-الشهر-اليوم تلقائياً باستخدام منتقي
                التاريخ.</small>
            {{-- **END OF FINAL DATE INPUT BLOCK** --}}
        </div>
    </div>

    {{-- Row 8: Baseline Formulas and Value --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="baseline_formula" :value="__('معادلة احتساب خط الأساس')" />
            <textarea id="baseline_formula" name="baseline_formula" rows="2"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('baseline_formula', $indicator->baseline_formula) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('baseline_formula')" />
        </div>
        <div>
            <x-input-label for="baseline_after_application" :value="__('خط الأساس بعد التطبيق')" />
            <x-text-input id="baseline_after_application" name="baseline_after_application" type="text"
                class="mt-1 block w-full" :value="old('baseline_after_application', $indicator->baseline_after_application)" />
            <x-input-error class="mt-2" :messages="$errors->get('baseline_after_application')" />
        </div>
    </div>

    {{-- Row 9: Survey Question & Proposed Initiatives --}}
    <div>
        <x-input-label for="survey_question" :value="__('اسئلة الاستبيان (سؤال للتحقق)')" />
        <textarea id="survey_question" name="survey_question" rows="2"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('survey_question', $indicator->survey_question) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('survey_question')" />
    </div>

    <div>
        <x-input-label for="proposed_initiatives" :value="__('مبادرات ومشاريع مقترحة')" />
        <textarea id="proposed_initiatives" name="proposed_initiatives" rows="3"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('proposed_initiatives', $indicator->proposed_initiatives) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('proposed_initiatives')" />
    </div>
    <div>
        <x-input-label for="evidence_type" value="الأدلة ا لداعمة" />
        <textarea id="evidence_type" name="evidence_type" rows="3"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('evidence_type', $indicator->evidence_type) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('evidence_type')" />
    </div>

    {{-- Hidden field for target_for_indicator --}}
    {{-- Note: target_for_indicator was missed in the initial form, adding it here. Assuming it is a number input --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="target_for_indicator" :value="__('المستهدف للمؤشر')" />
            <x-text-input id="target_for_indicator" name="target_for_indicator" type="number"
                class="mt-1 block w-full" :value="old('target_for_indicator', $indicator->target_for_indicator)" />
            <x-input-error class="mt-2" :messages="$errors->get('target_for_indicator')" />
        </div>
        <div>
            <x-input-label for="parent_id" :value="__('مؤشر رئيسي (إن وجد)')" />
            {{-- Assuming a simple text input or select for parent_id for now --}}
            <x-text-input id="parent_id" name="parent_id" type="text" class="mt-1 block w-full"
                :value="old('parent_id', $indicator->parent_id)" placeholder="أدخل رمز المؤشر الرئيسي" />
            <x-input-error class="mt-2" :messages="$errors->get('parent_id')" />
        </div>
    </div>
</div>
