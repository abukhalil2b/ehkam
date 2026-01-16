<x-app-layout title="إضافة دور جديد">
    <div class="p-4 md:p-6 max-w-xl mx-auto">
        {{-- Header --}}
        <header
            class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-t-lg shadow-md border-b dark:border-gray-700">
            <div>
                <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    <a href="{{ route('admin.roles.index') }}" class="hover:text-indigo-600">الأدوار</a>
                    <span class="mx-2">/</span>
                    <span>إضافة جديد</span>
                </nav>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">إضافة دور جديد</h1>
            </div>
        </header>

        {{-- Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-b-lg shadow-xl border border-t-0 dark:border-gray-700 p-6">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf

                {{-- Role Title --}}
                <div class="mb-5">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        اسم الدور <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="مثال: مدير المشاريع" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role Slug --}}
                <div class="mb-5">
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        الرمز (Slug)
                        <span class="text-gray-400 font-normal">- اختياري</span>
                    </label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="project_manager">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">سيتم إنشاؤه تلقائياً إذا تُرك فارغاً</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role Description --}}
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        الوصف
                        <span class="text-gray-400 font-normal">- اختياري</span>
                    </label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="وصف مختصر لهذا الدور والصلاحيات المتوقعة...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-between pt-4 border-t dark:border-gray-700">
                    <a href="{{ route('admin.roles.index') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        حفظ الدور
                    </button>
                </div>
            </form>
        </div>

        {{-- Help Notice --}}
        <div
            class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
                <div class="mr-3">
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                        بعد إنشاء الدور، يمكنك تعيين الصلاحيات له من صفحة إدارة الأدوار.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>