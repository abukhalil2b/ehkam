<x-app-layout>
    <x-slot name="header">
        تعديل المؤشر: {{ $indicator->title }}
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-xl" dir="rtl">
        <form method="POST" action="{{ route('indicator.update', $indicator) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                {{-- Row 1 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <label for="main_criteria" class="block font-medium text-sm text-gray-700">المعيار الرئيسي</label>
                        <input id="main_criteria" name="main_criteria" type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('main_criteria', $indicator->main_criteria) }}">
                        @error('main_criteria')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sub_criteria" class="block font-medium text-sm text-gray-700">المعيار الفرعي</label>
                        <input id="sub_criteria" name="sub_criteria" type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('sub_criteria', $indicator->sub_criteria) }}">
                        @error('sub_criteria')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="code" class="block font-medium text-sm text-gray-700">رمز المؤشر</label>
                        <input id="code" name="code" type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('code', $indicator->code) }}">
                        @error('code')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- is_main --}}
                <div>
                    <label for="is_main" class="block text-gray-700 font-medium mb-2">هل رئيسي</label>
                    <select name="is_main" id="is_main" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        <option value="1" {{ old('is_main', $indicator->is_main) == 1 ? 'selected' : '' }}>نعم
                        </option>
                        <option value="0" {{ old('is_main', $indicator->is_main) == 0 ? 'selected' : '' }}>لا
                        </option>
                    </select>
                    @error('is_main')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Row 2 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <label for="title" class="block font-medium text-sm text-gray-700">عنوان المؤشر</label>
                        <input id="title" name="title" type="text" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('title', $indicator->title) }}">
                        @error('title')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="owner" class="block font-medium text-sm text-gray-700">مالك المؤشر</label>
                        <input id="owner" name="owner" type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('owner', $indicator->owner) }}">
                        @error('owner')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="period" class="block font-medium text-sm text-gray-700">دورة القياس</label>
                        <select id="period" name="period"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- اختر دورة --</option>
                            @foreach ($periodOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('period', $indicator->period) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('period')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block font-medium text-sm text-gray-700">وصف المؤشر</label>
                    <textarea id="description" name="description" rows="3"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $indicator->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Measurement --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="measurement_tool" class="block font-medium text-sm text-gray-700">أداة
                            القياس</label>
                        <textarea id="measurement_tool" name="measurement_tool" rows="2"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('measurement_tool', $indicator->measurement_tool) }}</textarea>
                        @error('measurement_tool')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="formula" class="block font-medium text-sm text-gray-700">معادلة القياس</label>
                        <input id="formula" name="formula" type="text" dir="ltr"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('formula', $indicator->formula) }}">
                        @error('formula')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Formula --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    <div>
                        <label for="unit" class="block font-medium text-sm text-gray-700">وحدة القياس</label>

                        <select id="unit" name="unit"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- اختر وحدة القياس --</option>

                            <option value="percentage"
                                {{ old('unit', $indicator->unit) === 'percentage' ? 'selected' : '' }}>
                                نسبة مئوية (%)
                            </option>

                            <option value="number" {{ old('unit', $indicator->unit) === 'number' ? 'selected' : '' }}>
                                رقم
                            </option>
                        </select>

                        @error('unit')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="first_observation_date" class="block font-medium text-sm text-gray-700">تاريخ الرصد
                            الأول</label>
                        <input id="first_observation_date" name="first_observation_date" type="date"
                            value="{{ old('first_observation_date', $indicator->first_observation_date) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('first_observation_date')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Target --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="baseline_numeric" class="block font-medium text-sm text-gray-700">
                            خط الأساس</label>
                        <input id="baseline_numeric" name="baseline_numeric" type="number"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('baseline_numeric', $indicator->baseline_numeric) }}">
                        @error('baseline_numeric')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="baseline_year" class="block font-medium text-sm text-gray-700">سنة خط الأساس</label>
                        <input id="baseline_year" name="baseline_year" type="number"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('baseline_year', $indicator->baseline_year) }}">
                        @error('baseline_year')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="parent_id" class="block font-medium text-sm text-gray-700">مؤشر رئيسي (إن
                            وجد)</label>
                        <input id="parent_id" name="parent_id" type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            value="{{ old('parent_id', $indicator->parent_id) }}">
                        @error('parent_id')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="flex justify-start mt-6">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    تحديث المؤشر
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
