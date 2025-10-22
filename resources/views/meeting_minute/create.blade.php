<x-app-layout>
    <x-slot name="header">إنشاء محضر اجتماع جديد</x-slot>

    <div class="max-w-3xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">
        <form action="{{ route('meeting_minute.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Title --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">العنوان</label>
                <input type="text" name="title" value="{{ old('title') }}" class="form-input w-full" required>
                @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Date --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">التاريخ</label>
                <input type="date" name="date" value="{{ old('date') }}" class="form-input w-full" required>
                @error('date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Content --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">المحتوى</label>
                <textarea name="content" rows="4" class="form-textarea w-full" placeholder="اكتب تفاصيل الاجتماع...">{{ old('content') }}</textarea>
                @error('content') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- File Upload --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">رفع ملف المرفق</label>
                <input type="file" name="file_upload" class="form-input w-full" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg">
                @error('file_upload') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Written By --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">كتب بواسطة</label>
               <div class="font-bold text-blue-800"> {{ auth()->user()->name }} </div>
            </div>

            {{-- Attendances --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">الحضور</label>
                <div id="attendances">
                    <input type="text" name="attendances[]" class="form-input w-full mb-2" placeholder="اسم الحاضر">
                </div>
                <button type="button" class="text-blue-500" onclick="addAttendance()">+ إضافة اسم</button>
                @error('attendances.*') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">حفظ</button>
        </form>
    </div>

    <script>
        function addAttendance() {
            const container = document.getElementById('attendances');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'attendances[]';
            input.className = 'form-input w-full mb-2';
            input.placeholder = 'اسم الحاضر';
            container.appendChild(input);
        }
    </script>
</x-app-layout>
