<x-app-layout>
    <x-slot name="header">
        إنشاء سؤال تقييم جديد
    </x-slot>

    <div class="container py-8 mx-auto px-4 max-w-2xl">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">إضافة سؤال تقييم</h2>

        <form method="POST" action="{{ route('assessment_questions.store') }}"
            class="bg-white shadow-xl rounded-lg p-6 md:p-8">
            @csrf

            {{-- Content --}}
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">محتوى السؤال:</label>
                <div class="text-xs text-gray-500">مثال: دقة تنفيذ مراحل النشاط وفق الخطة المعتمدة</div>
                <input type="text" name="content" id="content" value="{{ old('content') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 text-lg"
                    required>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">شرح السؤال:</label>
                <input type="text" name="description" id="description" value="{{ old('description') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 text-lg">
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Type --}}
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">نوع السؤال:</label>
                <select name="type" id="type"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 appearance-none bg-white cursor-pointer"
                    required onchange="toggleMaxPointField(this.value)">
                    <option value="range" {{ old('type') == 'range' ? 'selected' : '' }}>تقييم بالنقاط</option>
                    <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>إجابة نصية مفتوحة</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Max Point (Only for range) --}}
            <div class="mb-8" id="max_point_field">
                <label for="max_point_input" class="block text-sm font-medium text-gray-700 mb-2">الحد الأقصى للنقاط (1-20):</label>
                <div class="flex flex-wrap gap-2" id="max_point_button_group">
                    @php
                        $max_setting_point = 20;
                        $current_max_point_value = old('max_point', 10);
                    @endphp

                    @for ($i = 1; $i <= $max_setting_point; $i++)
                        <button type="button" data-value="{{ $i }}"
                            onclick="selectMaxPointValue({{ $i }})"
                            class="w-10 h-10 flex items-center justify-center text-base font-semibold border rounded-lg transition-colors duration-150
                                {{ $i == $current_max_point_value ? 'bg-purple-600 text-white border-purple-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-purple-50 hover:border-purple-400' }}">
                            {{ $i }}
                        </button>
                    @endfor

                    <input type="hidden" name="max_point" id="max_point_input" value="{{ $current_max_point_value }}">
                </div>
                @error('max_point')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end">
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition duration-150 shadow-md">
                    حفظ السؤال
                </button>
            </div>
        </form>
    </div>

    {{-- JS --}}
    <script>
        function selectMaxPointValue(selectedValue) {
            const hiddenInput = document.getElementById('max_point_input');
            hiddenInput.value = selectedValue;

            const buttonGroup = document.getElementById('max_point_button_group');
            buttonGroup.querySelectorAll('button').forEach(button => {
                const buttonValue = parseInt(button.getAttribute('data-value'));
                const active = 'bg-purple-600 text-white border-purple-600 shadow-md';
                const inactive = 'bg-white text-gray-700 border-gray-300 hover:bg-purple-50 hover:border-purple-400';

                if (buttonValue === selectedValue) {
                    button.classList.add(...active.split(' '));
                    button.classList.remove(...inactive.split(' '));
                } else {
                    button.classList.add(...inactive.split(' '));
                    button.classList.remove(...active.split(' '));
                }
            });
        }

        function toggleMaxPointField(type) {
            const field = document.getElementById('max_point_field');
            const input = document.getElementById('max_point_input');

            if (type === 'range') {
                field.style.display = 'block';
                input.required = true;
                if (!input.value) selectMaxPointValue(10);
            } else {
                field.style.display = 'none';
                input.required = false;
                input.value = null;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const initialValue = parseInt(document.getElementById('max_point_input').value);
            if (initialValue) selectMaxPointValue(initialValue);
            toggleMaxPointField(document.getElementById('type').value);
        });
    </script>
</x-app-layout>
