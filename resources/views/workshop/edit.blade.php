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
                <form action="{{ route('workshop.update', $workshop->id) }}" method="POST"
                    enctype="multipart/form-data">
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

                    {{-- Workshop Days --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">أيام الورشة</label>
                        <div id="days-container" class="space-y-3">
                            @foreach($workshop->days as $index => $day)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3 day-item relative">
                                    <input type="hidden" name="days[{{ $index }}][id]" value="{{ $day->id }}">
                                    <input type="hidden" name="days[{{ $index }}][delete]" value="0" class="delete-flag">

                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">التاريخ</label>
                                        <input type="date" name="days[{{ $index }}][date]"
                                            value="{{ old('days.' . $index . '.date', $day->day_date->format('Y-m-d')) }}"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required>
                                    </div>
                                    <div class="flex gap-2 items-end">
                                        <div class="flex-grow">
                                            <label class="block text-sm text-gray-600 mb-1">الوصف</label>
                                            <input type="text" name="days[{{ $index }}][label]"
                                                value="{{ old('days.' . $index . '.label', $day->label) }}"
                                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                placeholder="مثال: اليوم الأول">
                                        </div>
                                        <button type="button" onclick="markDayForDeletion(this)"
                                            class="text-red-600 hover:text-red-800 font-semibold p-2 transition duration-150 ease-in-out whitespace-nowrap mb-1">
                                            حذف
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button"
                            class="text-blue-600 hover:text-blue-800 font-semibold transition duration-150 ease-in-out mt-1"
                            onclick="addDay()">
                            + إضافة يوم 
                        </button>
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
                                <option value="{{ $user->id }}" {{ old('created_by', $workshop->created_by) == $user->id ? 'selected' : '' }}>
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
                                            placeholder="اسم الحاضر">
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

                    <div class="pt-4 border-t border-gray-200 mt-4 flex justify-between items-center">
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-2 rounded-lg shadow-md transition duration-150 ease-in-out">
                            تحديث الورشة
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
        // Days Management
        let dayIndex = {{ count($workshop->days) }};
        if (dayIndex === 0) dayIndex = 0; // Just in case

        function addDay() {
            const container = document.getElementById('days-container');
            const newItem = document.createElement('div');
            newItem.className = 'grid grid-cols-1 md:grid-cols-2 gap-3 mb-3 day-item relative';
            
            newItem.innerHTML = `
                <input type="hidden" name="days[${dayIndex}][delete]" value="0" class="delete-flag">
                <div>
                     <label class="block text-sm text-gray-600 mb-1">التاريخ</label>
                    <input type="date" name="days[${dayIndex}][date]"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                </div>
                <div class="flex gap-2 items-end">
                    <div class="flex-grow">
                         <label class="block text-sm text-gray-600 mb-1">الوصف</label>
                        <input type="text" name="days[${dayIndex}][label]"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="مثال: اليوم الثاني">
                    </div>
                    <button type="button" onclick="markDayForDeletion(this)"
                        class="text-red-600 hover:text-red-800 font-semibold p-2 transition duration-150 ease-in-out whitespace-nowrap mb-1">
                        حذف
                    </button>
                </div>
            `;
            container.appendChild(newItem);
            dayIndex++;
        }

        function markDayForDeletion(button) {
            const item = button.closest('.day-item');
            
            // If it's a new item (no ID input), remove it completely
            if (!item.querySelector('input[name*="[id]"]')) {
                item.remove();
                // reindexDays(); // Not strictly necessary if index is just unique key
                return;
            }

            // If it exists in DB, mark for deletion and hide
            const deleteInput = item.querySelector('.delete-flag');
            if (deleteInput) {
                deleteInput.value = "1";
                item.style.display = 'none';
            }
        }

        // Attendance Management (Existing)
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
            reindexAttendances();
        }

        function reindexAttendances() {
            const items = document.querySelectorAll('.attendance-item');
            items.forEach((item, index) => {
                const inputs = item.querySelectorAll('input');
                if(inputs[0]) inputs[0].name = `attendances[${index}][name]`;
                if(inputs[1]) inputs[1].name = `attendances[${index}][job_title]`;
                if(inputs[2]) inputs[2].name = `attendances[${index}][department]`;
            });
            attendanceIndex = items.length;
        }
    </script>
</x-app-layout>