<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">

        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-xl font-bold mb-6">تعديل الحدث</h1>

            <form method="POST"
                  action="{{ route('calendar.update', $event) }}"
                  class="space-y-6">

                @csrf
                @method('PUT')

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium mb-1">عنوان الحدث</label>
                    <input type="text" name="title"
                           value="{{ old('title', $event->title) }}"
                           class="w-full border rounded px-3 py-2" required>
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-medium mb-1">نوع الحدث</label>
                    <select name="type" class="w-full border rounded px-3 py-2" required>
                        <option value="meeting" @selected($event->type === 'meeting')>اجتماع</option>
                        <option value="program" @selected($event->type === 'program')>برنامج</option>
                        <option value="conference" @selected($event->type === 'conference')>مؤتمر</option>
                        <option value="competition" @selected($event->type === 'competition')>مسابقة</option>
                    </select>
                </div>

                {{-- Program --}}
                <div>
                    <label class="block text-sm font-medium mb-1">البرنامج</label>
                    <input type="text" name="program"
                           value="{{ old('program', $event->program) }}"
                           class="w-full border rounded px-3 py-2">
                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">تاريخ البداية</label>
                        <input type="date" name="start_date"
                               value="{{ old('start_date', $event->start_date->toDateString()) }}"
                               class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">تاريخ النهاية</label>
                        <input type="date" name="end_date"
                               value="{{ old('end_date', $event->end_date->toDateString()) }}"
                               class="w-full border rounded px-3 py-2" required>
                    </div>
                </div>

                {{-- Color --}}
                <div>
                    <label class="block text-sm font-medium mb-1">لون الحدث</label>
                    <input type="color" name="bg_color"
                           value="{{ old('bg_color', $event->bg_color) }}"
                           class="h-10 w-24 border rounded">
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-medium mb-1">ملاحظات</label>
                    <textarea name="notes" rows="3"
                              class="w-full border rounded px-3 py-2">{{ old('notes', $event->notes) }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('calendar.index', ['year' => $event->year]) }}"
                       class="px-4 py-2 border rounded">إلغاء</a>

                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded">
                        تحديث الحدث
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
