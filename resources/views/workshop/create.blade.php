<x-app-layout>
    <!-- Header Slot -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إنشاء ورشة عمل جديد
        </h2>
    </x-slot>

    <!-- Main Content -->
    <form action="{{ route('workshop.store') }}" method="POST">
        @csrf

        <div class="py-6">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <form action="{{ route('workshop.store') }}" method="POST">
                        @csrf

                        {{-- Title --}}
                        <div class="mb-5">
                            <label for="title" class="block text-gray-700 font-bold mb-2">عنوان الورشة</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                                required placeholder="أدخل عنوان الورشة">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-5">
                            <label for="description" class="block text-gray-700 font-bold mb-2">وصف الورشة (اختياري)</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                placeholder="أدخل وصفاً مختصراً للورشة">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Workshop Days --}}
                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2">أيام الورشة</label>
                            <div id="days-container" class="space-y-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3 day-item">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">التاريخ</label>
                                        <input type="date" name="days[0][date]"
                                            value="{{ old('days.0.date', date('Y-m-d')) }}"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">الوصف (مثال: اليوم الأول - اختياري)</label>
                                        <input type="text" name="days[0][label]" value="{{ old('days.0.label') }}"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="مثال: اليوم الأول">
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button"
                                class="text-blue-600 hover:text-blue-800 font-semibold transition duration-150 ease-in-out mt-1"
                                onclick="addDay()">
                                + إضافة يوم آخر
                            </button>
                            
                             @error('days.*.date')
                                <p class="text-red-500 text-sm mt-1">يرجى إدخال تاريخ صحيح لكل يوم.</p>
                            @enderror
                        </div>

                        {{-- Location --}}
                        <div class="mb-5">
                            <label for="location" class="block text-gray-700 font-bold mb-2">مكان الورشة</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('location') border-red-500 @enderror"
                                placeholder="أدخل مكان انعقاد الورشة">
                            @error('location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>



                        {{-- Active Status --}}
                        <div class="mb-5">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="mr-2 text-gray-700 font-bold">تفعيل الورشة</span>
                            </label>
                            @error('is_active')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Created By (Read-only) --}}
                        <div class="mb-5 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <label class="block text-gray-700 font-bold mb-1">ينشئ بواسطة</label>
                            <div class="font-semibold text-blue-800 text-lg">{{ auth()->user()->name }}</div>
                        </div>

                        {{-- Submit --}}
                        <div class="pt-4 border-t border-gray-200 mt-4">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded-lg shadow-md transition duration-150 ease-in-out">
                                إنشاء الورشة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </form>

</x-app-layout>

<script>
    let dayIndex = 1; // Start from 1 as 0 is already present

    function addDay() {
        const container = document.getElementById('days-container');
        const newItem = document.createElement('div');
        newItem.className = 'grid grid-cols-1 md:grid-cols-2 gap-3 mb-3 day-item relative';
        
        newItem.innerHTML = `
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
                <button type="button" onclick="removeDay(this)"
                    class="text-red-600 hover:text-red-800 font-semibold p-2 transition duration-150 ease-in-out whitespace-nowrap mb-1">
                    حذف
                </button>
            </div>
        `;
        container.appendChild(newItem);
        dayIndex++;
    }

    function removeDay(button) {
        const item = button.closest('.day-item');
        item.remove();
        reindexDays();
    }

    function reindexDays() {
        const items = document.querySelectorAll('.day-item');
        items.forEach((item, index) => {
            const inputs = item.querySelectorAll('input');
            // Assuming first input is date, second is label
             if(inputs[0]) inputs[0].name = `days[${index}][date]`;
             if(inputs[1]) inputs[1].name = `days[${index}][label]`;
        });
        dayIndex = items.length;
    }
</script>
