<x-sect-layout>
    <div class="mt-6 p-6 max-w-xl mx-auto bg-white rounded shadow">

        <h2 class="text-xl font-bold mb-4">{{ $indicator->title }}</h2>
        <h2 class="text-xl font-bold mb-4">{{ $sector->short_name }}</h2>


        <form method="POST" enctype="multipart/form-data"
            action="{{ route('indicator_feedback_value.store', $indicator) }}">
            @csrf

            <div class="flex justify-between">
                <div>
                    <label class="font-bold">السنة</label>
                    <select name="current_year" class="w-44 p-2 border rounded">
                        @foreach ($years as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="font-bold">القيمة المحققة</label>
                    <input type="number" name="achieved" class="w-full p-2 border rounded">
                </div>
            </div>

            <div class="mb-3">
                <label class="font-bold">ملاحظات</label>
                <textarea name="note" class="w-full p-2 border rounded"></textarea>
            </div>

            <div class="flex items-center justify-between mb-3 bg-gray-100 rounded p-1">
                <div>
                    <label class="font-bold">عنوان الدليل</label>
                    <input type="text" name="evidence_title" class="w-full p-2 border rounded">
                </div>

                <div>
                    <label class="font-bold">ملف الدليل (PDF/JPG/PNG)</label>
                    <input type="file" name="evidence_file">
                </div>
            </div>

            <button class="px-4 py-2 bg-green-600 text-white rounded">حفظ</button>
        </form>

    </div>
</x-sect-layout>
