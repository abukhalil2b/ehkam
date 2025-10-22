<x-app-layout>
    <x-slot name="header">تعديل محضر الاجتماع</x-slot>

    <div class="max-w-3xl mx-auto mt-6 bg-white p-6 rounded-lg shadow">
        <form action="{{ route('meeting_minute.update', $meeting_minute->id) }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">العنوان</label>
                <input type="text" name="title" value="{{ old('title', $meeting_minute->title) }}" class="form-input w-full" required>
                @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Date --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">التاريخ</label>
                <input type="date" name="date" value="{{ old('date', $meeting_minute->date) }}" class="form-input w-full" required>
                @error('date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Content --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">المحتوى</label>
                <textarea name="content" rows="5" class="form-textarea w-full" placeholder="اكتب تفاصيل الاجتماع...">{{ old('content', strip_tags(str_replace('<br />', "\n", $meeting_minute->content))) }}</textarea>
                @error('content') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Existing File --}}
            @if($meeting_minute->file_upload_link)
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1">الملف الحالي</label>
                    <a href="{{ asset('storage/' . $meeting_minute->file_upload_link) }}" 
                       target="_blank" 
                       class="text-blue-600 underline">
                        عرض الملف الحالي
                    </a>
                </div>
            @endif

            {{-- File Upload --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">استبدال الملف</label>
                <input type="file" name="file_upload" class="form-input w-full" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg">
                <p class="text-gray-500 text-sm">اترك هذا الحقل فارغًا إذا لم ترغب في استبدال الملف.</p>
                @error('file_upload') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Written By --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">كتب بواسطة</label>
                <select name="written_by" class="form-select w-full" required>
                    <option value="">اختر المستخدم</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" 
                            {{ old('written_by', $meeting_minute->written_by) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('written_by') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Attendances --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">الحضور</label>
                <div id="attendances">
                    @foreach(old('attendances', $meeting_minute->attendances->pluck('name')->toArray()) as $attendance)
                        <input type="text" name="attendances[]" value="{{ $attendance }}" class="form-input w-full mb-2" placeholder="اسم الحاضر">
                    @endforeach
                </div>
                <button type="button" class="text-blue-500 mt-1" onclick="addAttendance()">+ إضافة اسم</button>
                @error('attendances.*') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <div class="mt-6 flex justify-between items-center">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">تحديث</button>
                <a href="{{ route('meeting_minute.index') }}" class="text-gray-600 underline">إلغاء</a>
            </div>
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
