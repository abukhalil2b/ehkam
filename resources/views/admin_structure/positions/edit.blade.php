<x-app-layout title="تعديل المسمى الوظيفي: {{ $position->title }}">
    <div class="p-4 md:p-6 max-w-xl mx-auto">
        
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
            تعديل المسمى الوظيفي
        </h1>

        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">
            <form method="POST" action="{{ route('admin_structure.positions.update', $position) }}">
                @csrf
                @method('PUT')

                {{-- Position Title --}}
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        اسم المسمى الوظيفي:
                    </label>
                    <input type="text" id="title" name="title" required
                           value="{{ old('title', $position->title) }}"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                    @error('title')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="flex justify-end space-x-2 space-x-reverse">
                    <a href="{{ route('admin_structure.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                        إلغاء
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 transition">
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>