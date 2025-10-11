<x-app-layout>
   <x-slot name="header">
    إنشاء سؤال تقييم جديد
   </x-slot>

    <div class="container py-8 mx-auto px-4 max-w-2xl">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">إضافة سؤال تقييم</h2>
        
        <form method="POST" action="{{ route('assessment_questions.store') }}" class="bg-white shadow-xl rounded-lg p-6 md:p-8">
            @csrf
            
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">محتوى السؤال:</label>
                <div class="text-xs">مثال:دقة تنفيذ مراحل النشاط وفق الخطة المعتمدة</div>
                <input type="text" name="content" id="content" value="{{ old('content') }}" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 text-lg" 
                       required>
                @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">نوع السؤال:</label>
                <select name="type" id="type" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 appearance-none bg-white cursor-pointer" 
                        required onchange="toggleMaxPointField(this.value)">
                    <option value="range" {{ old('type') == 'range' ? 'selected' : '' }}>Range (تقييم بالنقاط)</option>
                    <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text (إجابة نصية مفتوحة)</option>
                </select>
                @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-8" id="max_point_field">
                <label for="max_point" class="block text-sm font-medium text-gray-700 mb-2">الحد الأقصى للنقاط (1-10):</label>
                <input type="number" name="max_point" id="max_point" min="1" max="10" value="{{ old('max_point',5) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3" 
                        >
                @error('max_point') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition duration-150 shadow-md">
                    حفظ السؤال
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
                    input.required = true;
                } else {
                    field.style.display = 'none';
                    input.required = false;
                    input.value = null; // Clear value if hidden
                }
            }
            
            // Initialize on load with the current or old value
            document.addEventListener('DOMContentLoaded', () => {
                toggleMaxPointField(document.getElementById('type').value);
            });
        </script>
    </div>
</x-app-layout>
