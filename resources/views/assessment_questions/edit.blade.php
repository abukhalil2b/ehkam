<x-app-layout>
   <x-slot name="header">
    تعديل سؤال التقييم
   </x-slot>

    <div class="container py-8 mx-auto px-4 max-w-2xl">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">تعديل السؤال: {{ $question->id }}</h2>
        
        <form method="POST" action="{{ route('assessment_questions.update', $question) }}" class="bg-white shadow-xl rounded-lg p-6 md:p-8">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">محتوى السؤال:</label>
                <input type="text" name="content" id="content" 
                       value="{{ old('content', $question->content) }}" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 text-lg" 
                       required>
                @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">شرح السؤال:</label>
                <input type="text" name="description" id="description" 
                       value="{{ old('description', $question->description) }}" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 text-lg" 
                       >
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">نوع السؤال:</label>
                <select name="type" id="type" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 appearance-none bg-white cursor-pointer" 
                        required onchange="toggleMaxPointField(this.value)">
                    <option value="range" {{ old('type', $question->type) == 'range' ? 'selected' : '' }}> (تقييم بالنقاط)</option>
                    <option value="text" {{ old('type', $question->type) == 'text' ? 'selected' : '' }}> (إجابة نصية مفتوحة)</option>
                </select>
                @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- START OF MODIFIED MAX POINT INPUT --}}
            <div class="mb-8" id="max_point_field">
                <label for="max_point_input" class="block text-sm font-medium text-gray-700 mb-2">الحد الأقصى للنقاط (1-20):</label>
                
                <div class="flex flex-wrap gap-2" id="max_point_button_group">
                    @php
                        // The maximum setting point is 20
                        $max_setting_point = 20;
                        // Get the current value from the old input or the database
                        $current_max_point_value = old('max_point', $question->max_point ?? 10);
                    @endphp

                    @for ($i = 1; $i <= $max_setting_point; $i++)
                        <button type="button" data-value="{{ $i }}"
                            onclick="selectMaxPointValue({{ $i }})"
                            class="
                                w-10 h-10 flex items-center justify-center 
                                text-base font-semibold border rounded-lg transition-colors duration-150
                                {{ $i == $current_max_point_value ? 'bg-purple-600 text-white border-purple-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-purple-50 hover:border-purple-400' }}
                            ">
                            {{ $i }}
                        </button>
                    @endfor

                    {{-- Hidden input to store the selected value for form submission --}}
                    <input type="hidden" name="max_point" id="max_point_input" value="{{ $current_max_point_value }}">

                </div>
                
                @error('max_point')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- END OF MODIFIED MAX POINT INPUT --}}

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition duration-150 shadow-md">
                    حفظ التعديلات
                </button>
            </div>
        </form>

        <script>
            // NEW JAVASCRIPT FUNCTION TO HANDLE BUTTON SELECTION
            function selectMaxPointValue(selectedValue) {
                // 1. Update the hidden input field's value
                const hiddenInput = document.getElementById('max_point_input');
                hiddenInput.value = selectedValue;

                // 2. Get the button group container
                const buttonGroup = document.getElementById('max_point_button_group');

                // 3. Loop through all buttons to update their classes (active/inactive styling)
                buttonGroup.querySelectorAll('button').forEach(button => {
                    const buttonValue = parseInt(button.getAttribute('data-value'));

                    const activeClasses = ['bg-purple-600', 'text-white', 'border-purple-600', 'shadow-md'];
                    const inactiveClasses = ['bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-purple-50', 'hover:border-purple-400'];
                    
                    // Simple class manipulation
                    if (buttonValue === selectedValue) {
                        button.classList.add(...activeClasses);
                        button.classList.remove(...inactiveClasses);
                    } else {
                        button.classList.add(...inactiveClasses);
                        button.classList.remove(...activeClasses);
                    }
                });
            }

            // Client-side JS to conditionally show/hide the max_point field
            function toggleMaxPointField(type) {
                const field = document.getElementById('max_point_field');
                // The input to check is now the HIDDEN input
                const input = document.getElementById('max_point_input'); 

                if (type === 'range') {
                    field.style.display = 'block';
                    input.required = true; 
                    
                    // If the input value is null (e.g., when switching from text), set a default and style the button
                    if (!input.value) {
                         selectMaxPointValue(10); 
                    }
                } else {
                    field.style.display = 'none';
                    input.required = false;
                    input.value = null; // Clear value if hidden
                }
            }

            // Initialize on load with the current or old value
            document.addEventListener('DOMContentLoaded', () => {
                const initialValue = parseInt(document.getElementById('max_point_input').value);
                // Ensure correct button is styled on load if a value exists
                if (initialValue) {
                     selectMaxPointValue(initialValue); 
                }

                toggleMaxPointField(document.getElementById('type').value);
            });
        </script>
    </div>
</x-app-layout>