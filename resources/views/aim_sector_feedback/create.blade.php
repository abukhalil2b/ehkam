<x-sect-layout>
    <div class="mt-6 p-6 max-w-xl mx-auto bg-white rounded shadow">

        {{-- Display a clear title indicating if it's CREATE or EDIT --}}
        @if(isset($feedbackValue))
            <h1 class="text-2xl font-extrabold text-blue-700 mb-4">تعديل </h1>
        @else
            <h1 class="text-2xl font-extrabold text-green-700 mb-4">إضافة </h1>
        @endif
        
        <h2 class="text-xl font-bold mb-2">{{ $aim->title }}</h2>
        <h3 class="text-lg text-gray-600 mb-4">{{ $sector->short_name }}</h3>


        <form method="POST" enctype="multipart/form-data"
            action="{{ route('aim_sector_feedback.store', $aim) }}">
            @csrf

            <div class="flex justify-between items-end mb-4">
                {{-- Year is now a hidden field to ensure consistency, displayed for user context --}}
                <div>
                    <label class="font-bold block">السنة</label>
                    <div class="text-2xl font-mono text-gray-800">{{ $current_year }}</div>
                    <input type="hidden" name="current_year" value="{{ $current_year }}">
                </div>

                <div>
                    <label class="font-bold">القيمة المحققة</label>
                    <input type="number" 
                           name="achieved" 
                           class="w-full p-2 border rounded @error('achieved') border-red-500 @enderror"
                           {{-- Use old() for validation errors, fallback to existing data --}}
                           value="{{ old('achieved', $feedbackValue->achieved ?? '') }}"
                           required>
                    @error('achieved')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="font-bold">ملاحظات</label>
                <textarea name="note" 
                          class="w-full p-2 border rounded @error('note') border-red-500 @enderror">{{ old('note', $feedbackValue->note ?? '') }}</textarea>
                @error('note')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="p-3 border rounded mb-4">
                
                <div class="mb-3">
                    <label class="font-bold">عنوان الدليل</label>
                    <input type="text" 
                           name="evidence_title" 
                           class="w-full p-2 border rounded @error('evidence_title') border-red-500 @enderror"
                           value="{{ old('evidence_title', $feedbackValue->evidence_title ?? '') }}">
                    @error('evidence_title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="font-bold block mb-1">ملف الدليل (PDF/JPG/PNG)</label>
                    
                    {{-- File Input --}}
                    <input type="file" name="evidence_file" class="mb-2">
                    
                    @error('evidence_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                    {{-- Display existing file information (for update) --}}
                    @if(isset($feedbackValue) && $feedbackValue->evidence_url)
                        <p class="text-sm mt-1 p-2 bg-yellow-100 rounded">
                            ✅ الملف الحالي مرفق. لرفعه قم بتحميل ملف جديد.
                            <a href="{{ Storage::url($feedbackValue->evidence_url) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline mr-2">
                                (عرض الدليل)
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            <button class="px-6 py-2 bg-green-600 text-white font-bold rounded hover:bg-green-700 transition">
                {{ isset($feedbackValue) ? 'تحديث وحفظ' : 'حفظ التغذية الراجعة' }}
            </button>
        </form>

    </div>
</x-sect-layout>