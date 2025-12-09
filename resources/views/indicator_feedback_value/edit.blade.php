<x-sect-layout>
<div class="p-6 max-w-xl mx-auto bg-white rounded shadow">

    <h2 class="text-xl font-bold mb-4">تعديل التغذية الراجعة</h2>

    <form method="POST" enctype="multipart/form-data"
          action="{{ route('indicator_feedback_value.update', $feedback) }}">
        @csrf

        <div class="mb-3">
            <label class="font-bold">السنة</label>
            <select name="current_year" class="w-full p-2 border rounded">
                @foreach($years as $y)
                    <option value="{{ $y }}" 
                        @if($feedback->current_year == $y) selected @endif>
                        {{ $y }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="font-bold">القيمة المحققة</label>
            <input type="number" value="{{ $feedback->achieved }}" 
                   name="achieved" class="w-full p-2 border rounded">
        </div>

        <div class="mb-3">
            <label class="font-bold">عنوان الإثبات</label>
            <input type="text" name="evidence_title" 
                   value="{{ $feedback->evidence_title }}"
                   class="w-full p-2 border rounded">
        </div>

        <div class="mb-3">
            <label class="font-bold">ملاحظات</label>
            <textarea name="note" class="w-full p-2 border rounded">{{ $feedback->note }}</textarea>
        </div>

        <div class="mb-3">
            <label class="font-bold">استبدال ملف الإثبات</label>
            <input type="file" name="evidence_file">
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded">تحديث</button>
    </form>

</div>
</x-sect-layout>
