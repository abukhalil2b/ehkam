<x-app-layout>
    <div class="py-12" dir="rtl">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">تسجيل مؤسسة وقفية جديدة</h2>
                    <a href="{{ route('endowments.index') }}" class="text-gray-600 hover:underline">العودة للقائمة</a>
                </div>

                <form method="POST" action="{{ route('endowments.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">اسم المؤسسة الوقفية</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="مثال: مؤسسة وقف نزوى الأهلية" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">المحافظة</label>
                            <select name="governorate_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                <option value="">اختر المحافظة...</option>
                                @foreach($governorates as $gov)
                                    <option value="{{ $gov->id }}" {{ old('governorate_id') == $gov->id ? 'selected' : '' }}>
                                        {{ $gov->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('governorate_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">نوع الوقف</label>
                            <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                                <option value="">اختر النوع...</option>
                                <option value="عامة" {{ old('type') == 'عامة' ? 'selected' : '' }}>عامة</option>
                                <option value="خاصة" {{ old('type') == 'خاصة' ? 'selected' : '' }}>خاصة</option>
                            </select>
                            @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-8 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded shadow hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            حفظ المؤسسة
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>