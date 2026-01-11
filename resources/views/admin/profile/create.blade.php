<x-app-layout title="إضافة دور جديد">
    <div class="p-4 md:p-6 max-w-lg mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">إضافة دور جديد</h2>
            <form action="{{ route('admin.profiles.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">اسم الدور</label>
                    <input type="text" name="title"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 text-white"
                        required>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>