<x-app-layout title="تعديل الخطوة">

    <div class="py-6" dir="rtl">
        <div class="max-w-4xl mx-auto px-4 bg-white rounded-2xl shadow p-6">

            <h1 class="text-xl font-bold text-[#1b5e20] mb-6">تعديل الخطوة</h1>

            <form action="{{ route('step.update', $step->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-semibold mb-1 text-[#1b5e20]">اسم الخطوة</label>
                    <input type="text" name="name" value="{{ old('name', $step->name) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-1 text-[#1b5e20]">تاريخ البداية</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $step->start_date) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1 text-[#1b5e20]">تاريخ النهاية</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $step->end_date) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-1 text-[#1b5e20]">النسبة المستهدفة</label>
                        <input type="number" name="target_percentage"
                            value="{{ old('target_percentage', $step->target_percentage) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm" min="0" max="100">
                    </div>

                    <div>
                        <label class="block font-semibold mb-1 text-[#1b5e20]">المرحلة</label>
                        <input type="text" name="phase" value="{{ old('phase', $step->phase) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold mb-1 text-[#1b5e20]">الحالة</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm">
                        <option value="in_progress" @selected($step->status == 'in_progress')>قيد التنفيذ</option>
                        <option value="completed" @selected($step->status == 'completed')>منجز</option>
                        <option value="delayed" @selected($step->status == 'delayed')>متأخر</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1 text-[#1b5e20]">الجهات المسندة</label>
                    <textarea name="assigned_divisions[]" rows="3"
                        class="w-full border-gray-300 rounded-lg shadow-sm">{{ implode("\n", json_decode($step->assigned_divisions ?? '[]', true)) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">اكتب كل جهة في سطر منفصل.</p>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_need_evidence_file" value="1"
                        @checked($step->is_need_evidence_file)
                        class="rounded border-gray-300 text-[#1b5e20]">
                    <label class="font-semibold text-[#1b5e20]">هل تتطلب ملفات داعمة؟</label>
                </div>

                <div>
                    <label class="block font-semibold mb-1 text-[#1b5e20]">الوثائق الداعمة</label>
                    <textarea name="supporting_documents" rows="3"
                        class="w-full border-gray-300 rounded-lg shadow-sm">{{ old('supporting_documents', $step->supporting_documents) }}</textarea>
                </div>

                <div class="flex justify-between mt-6">
                    <a href="{{ route('step.show', $step->id) }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700">
                        إلغاء
                    </a>

                    <button type="submit"
                        class="px-4 py-2 bg-[#1b5e20] text-white rounded-lg hover:bg-[#2e7d32] shadow">
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
