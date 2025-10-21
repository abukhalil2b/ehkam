<x-app-layout title="تصحيح بيانات السجل الوظيفي">
    <div class="p-4 md:p-6 max-w-2xl mx-auto">
        
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
            تصحيح السجل الوظيفي للمستخدم: {{ $user->name }}
        </h1>
        <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            أنت تقوم بتعديل السجل النشط حالياً مباشرة. استخدم هذه الميزة لتصحيح الأخطاء المطبعية أو التواريخ.
        </p>

        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">
            <form method="POST" action="{{ route('admin_users.update_correction', $user) }}">
                @csrf
                @method('PUT')

                {{-- Organizational Unit ID --}}
                <div class="mb-4">
                    <label for="organizational_unit_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">الوحدة التنظيمية:</label>
                    <select id="organizational_unit_id" name="organizational_unit_id" required>
                        @foreach ($units as $u)
                            <option value="{{ $u->id }}"
                                {{ old('organizational_unit_id', $activeRecord->organizational_unit_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('organizational_unit_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Position ID --}}
                <div class="mb-4">
                    <label for="position_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">المسمى
                        الوظيفي:</label>
                    <select id="position_id" name="position_id" required>
                        @foreach ($positions as $pos)
                            <option value="{{ $pos->id }}"
                                {{ old('position_id', $activeRecord->position_id) == $pos->id ? 'selected' : '' }}>
                                {{ $pos->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('position_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Start Date Field --}}
                <div class="mb-4">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">تاريخ
                        البدء:</label>
                    <input type="date" id="start_date" name="start_date"
                        value="{{ old('start_date', $activeRecord->start_date) }}" required>
                    @error('start_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- End Date Field (Optional for manually closing the record) --}}
                <div class="mb-6">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">تاريخ
                        الانتهاء (اختياري):</label>
                    <input type="date" id="end_date" name="end_date"
                        value="{{ old('end_date', $activeRecord->end_date) }}">
                    <p class="text-xs text-gray-500 mt-1">تجاهل هذا الحقل إذا كان المسمى الوظيفي لا يزال نشطاً.</p>
                    @error('end_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="flex justify-end space-x-2 space-x-reverse">
                    <a href="{{ route('admin_users.show', $user) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                        إلغاء
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg shadow-md hover:bg-red-700 transition">
                        حفظ التصحيح
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>