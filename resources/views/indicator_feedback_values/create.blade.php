<x-sect-layout>
<div class="p-6 max-w-xl mx-auto bg-white rounded shadow">

    <h2 class="text-xl font-bold mb-4">إضافة تغذية راجعة جديدة</h2>

    <form method="POST" enctype="multipart/form-data"
        action="{{ route('indicator_feedback_values.store', $indicator) }}">
        @csrf

        <div class="mb-3">
            <label class="font-bold">السنة</label>
            <select name="current_year" class="w-full p-2 border rounded">
                @foreach($years as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="font-bold">القيمة المحققة</label>
            <input type="number" name="achieved" class="w-full p-2 border rounded">
        </div>

         <div class="mb-3">
            <label class="font-bold">ملاحظات</label>
            <textarea name="note" class="w-full p-2 border rounded"></textarea>
        </div>

        <div class="mb-3">
            <label class="font-bold">عنوان الدليل</label>
            <input type="text" name="evidence_title" class="w-full p-2 border rounded">
        </div>

        <div class="mb-3">
            <label class="font-bold">ملف الدليل (PDF/JPG/PNG)</label>
            <input type="file" name="evidence_file">
        </div>

        <button class="px-4 py-2 bg-green-600 text-white rounded">حفظ</button>
    </form>

</div>
</x-sect-layout>
