<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تعديل محضر الورشة
        </h2>
    </x-slot>

    <!-- Main Content -->
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                <form action="{{ route('workshop.update', $workshop->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div class="mb-5">
                        <label for="title" class="block text-gray-700 font-bold mb-2">العنوان</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $workshop->title) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                            required placeholder="عنوان الورشة">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="description" class="block text-gray-700 font-bold mb-2">وصف الورشة</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                            placeholder="أدخل وصفاً مختصراً للورشة">{{ old('description', $workshop->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Start Date and Time --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        {{-- Start Date --}}
                        <div>
                            <label for="start_date" class="block text-gray-700 font-bold mb-2">تاريخ البدء</label>
                            <input type="date" id="start_date" name="start_date"
                                value="{{ old('start_date', $workshop->starts_at->format('Y-m-d')) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('start_date') border-red-500 @enderror"
                                required>
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Start Time --}}
                        <div>
                            <label for="start_time" class="block text-gray-700 font-bold mb-2">وقت البدء</label>
                            <input type="time" id="start_time" name="start_time"
                                value="{{ old('start_time', $workshop->starts_at->format('H:i')) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('start_time') border-red-500 @enderror"
                                required>
                            @error('start_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- End Date and Time --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        {{-- End Date --}}
                        <div>
                            <label for="end_date" class="block text-gray-700 font-bold mb-2">تاريخ الانتهاء</label>
                            <input type="date" id="end_date" name="end_date"
                                value="{{ old('end_date', $workshop->ends_at?->format('Y-m-d')) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- End Time --}}
                        <div>
                            <label for="end_time" class="block text-gray-700 font-bold mb-2">وقت الانتهاء</label>
                            <input type="time" id="end_time" name="end_time"
                                value="{{ old('end_time', $workshop->ends_at?->format('H:i')) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('end_time') border-red-500 @enderror">
                            @error('end_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- Location (Added Field) --}}
                    <div class="mb-5">
                        <label for="location" class="block text-gray-700 font-bold mb-2">المكان</label>
                        <input type="text" id="location" name="location"
                            value="{{ old('location', $workshop->location) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('location') border-red-500 @enderror"
                            placeholder="اسم القاعة أو المكان">
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Written By --}}
                    <div class="mb-5">
                        <label for="created_by" class="block text-gray-700 font-bold mb-2">كتب بواسطة</label>
                        <select id="created_by" name="created_by"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('created_by') border-red-500 @enderror"
                            required>
                            <option value="">اختر المستخدم</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('created_by', $workshop->created_by) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('created_by')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Attendances --}}
                    {{-- Enhanced Attendances Section --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">الحضور</label>
                        <div id="attendances-container">
                            @php
                                $oldAttendances = old('attendances', []);
                                $attendances = !empty($oldAttendances)
                                    ? $oldAttendances
                                    : $workshop->attendances->toArray();
                            @endphp

                            @forelse($attendances as $index => $attendance)
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3 attendance-item">
                                    <div>
                                        <input type="text" name="attendances[{{ $index }}][name]"
                                            value="{{ $attendance['attendee_name'] ?? ($attendance['name'] ?? '') }}"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="اسم الحاضر" required>
                                    </div>
                                    <div>
                                        <input type="text" name="attendances[{{ $index }}][job_title]"
                                            value="{{ $attendance['job_title'] ?? '' }}"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="المسمى الوظيفي">
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="text" name="attendances[{{ $index }}][department]"
                                            value="{{ $attendance['department'] ?? '' }}"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="القسم">
                                        @if ($index > 0)
                                            <button type="button" onclick="removeAttendance(this)"
                                                class="text-red-600 hover:text-red-800 font-semibold transition duration-150 ease-in-out whitespace-nowrap">
                                                حذف
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3 attendance-item">
                                    <div>
                                        <input type="text" name="attendances[0][name]"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="اسم الحاضر" required>
                                    </div>
                                    <div>
                                        <input type="text" name="attendances[0][job_title]"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="المسمى الوظيفي">
                                    </div>
                                    <div>
                                        <input type="text" name="attendances[0][department]"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="القسم">
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <button type="button"
                            class="text-blue-600 hover:text-blue-800 font-semibold transition duration-150 ease-in-out mt-1"
                            onclick="addAttendance()">
                            + إضافة اسم آخر
                        </button>

                        @error('attendances.*.name')
                            <p class="text-red-500 text-sm mt-1">يرجى إدخال اسم الحاضر</p>
                        @enderror
                    </div>

                    {{-- Submit & Cancel --}}
                    <div class="pt-4 border-t border-gray-200 mt-4 flex justify-between items-center">
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-2 rounded-lg shadow-md transition duration-150 ease-in-out">
                            تحديث محضر الورشة
                        </button>
                        <a href="{{ route('workshop.index') }}"
                            class="text-gray-600 hover:text-gray-800 underline transition duration-150">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for adding attendance fields -->
    <script>
        let attendanceIndex = {{ count($attendances) }};

        function addAttendance() {
            const container = document.getElementById('attendances-container');
            const newItem = document.createElement('div');
            newItem.className = 'grid grid-cols-1 md:grid-cols-3 gap-3 mb-3 attendance-item';
            newItem.innerHTML = `
        <div>
            <input type="text" name="attendances[${attendanceIndex}][name]"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="اسم الحاضر" required>
        </div>
        <div>
            <input type="text" name="attendances[${attendanceIndex}][job_title]"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="المسمى الوظيفي">
        </div>
        <div class="flex gap-2">
            <input type="text" name="attendances[${attendanceIndex}][department]"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="القسم">
            <button type="button" onclick="removeAttendance(this)"
                class="text-red-600 hover:text-red-800 font-semibold transition duration-150 ease-in-out whitespace-nowrap">
                حذف
            </button>
        </div>
    `;
            container.appendChild(newItem);
            attendanceIndex++;
        }

        function removeAttendance(button) {
            const item = button.closest('.attendance-item');
            item.remove();
            // Reindex remaining items if needed
            reindexAttendances();
        }

        function reindexAttendances() {
            const items = document.querySelectorAll('.attendance-item');
            items.forEach((item, index) => {
                const inputs = item.querySelectorAll('input');
                inputs[0].name = `attendances[${index}][name]`;
                inputs[1].name = `attendances[${index}][job_title]`;
                inputs[2].name = `attendances[${index}][department]`;
            });
            attendanceIndex = items.length;
        }
    </script>
</x-app-layout>
