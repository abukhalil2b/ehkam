<x-app-layout>
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8" dir="rtl">
    <div class="mb-6 text-right">
        <h1 class="text-3xl font-bold text-gray-900">إنشاء مسابقة جديدة</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.competitions.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2 text-right">
                    عنوان المسابقة
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       required 
                       value="{{ old('title') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-right"
                       placeholder="مثال: مسابقة المعلومات العامة">
                @error('title')
                    <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-right">
                <h3 class="font-medium text-blue-900 mb-2">ماذا يحدث بعد ذلك؟</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• سيتم إنشاء المسابقة بحالة "مغلقة"</li>
                    <li>• سيتم إنشاء رمز انضمام فريد ورمز QR</li>
                    <li>• يمكنك إضافة الأسئلة قبل بدء المسابقة</li>
                    <li>• يمكن للمشاركين الانضمام حتى تقوم ببدء المسابقة</li>
                </ul>
            </div>

            <div class="flex gap-3 justify-start">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                    إنشاء المسابقة
                </button>
                <a href="{{ route('admin.competitions.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>