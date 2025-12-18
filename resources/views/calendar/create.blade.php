<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">

        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-xl font-bold mb-6">إضافة حدث جديد</h1>

            <form method="POST" action="{{ route('calendar.store') }}" class="space-y-6">
                @csrf

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium mb-1">عنوان الحدث</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-medium mb-1">نوع الحدث</label>
                    <select name="type" class="w-full border rounded px-3 py-2" required>
                        <option value="">— اختر —</option>
                        <option value="meeting">اجتماع</option>
                        <option value="program">برنامج</option>
                        <option value="conference">مؤتمر</option>
                        <option value="competition">مسابقة</option>
                    </select>
                </div>

                {{-- Program --}}
                <div>
                    <label class="block text-sm font-medium mb-1">البرنامج (اختياري)</label>
                    <input type="text" name="program" value="{{ old('program') }}"
                        class="w-full border rounded px-3 py-2">
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">تاريخ البداية</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">تاريخ النهاية</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                </div>

                {{-- Color --}}
                <div>
                    <label class="block text-sm font-medium mb-1">لون الحدث</label>
                    <input type="color" name="bg_color" value="{{ old('bg_color', '#16a34a') }}"
                        class="h-10 w-24 border rounded">
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-medium mb-1">ملاحظات</label>
                    <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('calendar.index') }}" class="px-4 py-2 border rounded">إلغاء</a>

                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded">
                        حفظ الحدث
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
