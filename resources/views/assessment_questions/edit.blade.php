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

            <div class="mb-8" id="max_point_field">
                <label for="max_point" class="block text-sm font-medium text-gray-700 mb-2">الحد الأقصى للنقاط (1-20):</label>
                <input type="range" name="max_point" id="max_point" min="1" max="20" 
                       value="{{ old('max_point', $question->max_point) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3">
                @error('max_point') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition duration-150 shadow-md">
                    حفظ التعديلات
                </button>
            </div>
        </form>

        <script>
            // Client-side JS to conditionally show/hide the max_point field
            function toggleMaxPointField(type) {
                const field = document.getElementById('max_point_field');
                const input = document.getElementById('max_point');
                
                if (type === 'range') {
                    field.style.display = 'block';
                    // We only require it here, server-side validation handles the real check
                    input.required = true; 
                } else {
                    field.style.display = 'none';
                    input.required = false;
                }
            }
            
            // Initialize on load with the current value
            document.addEventListener('DOMContentLoaded', () => {
                toggleMaxPointField(document.getElementById('type').value);
            });
        </script>
    </div>
</x-app-layout>