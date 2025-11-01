<x-app-layout>
    <!-- Header Slot -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إنشاء محضر ورشة عمل جديد
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

                        {{-- Start Date and Time --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                            {{-- Start Date --}}
                            <div>
                                <label for="start_date" class="block text-gray-700 font-bold mb-2">تاريخ البدء</label>
                                <input type="date" id="start_date" name="start_date"
                                    value="{{ old('start_date', date('Y-m-d')) }}"
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
                                    value="{{ old('start_time', '09:00') }}"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('start_time') border-red-500 @enderror"
                                    required>
                                @error('start_time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- End Date and Time (Optional) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                            {{-- End Date --}}
                            <div>
                                <label for="end_date" class="block text-gray-700 font-bold mb-2">تاريخ الانتهاء
                                    (اختياري)</label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- End Time --}}
                            <div>
                                <label for="end_time" class="block text-gray-700 font-bold mb-2">وقت الانتهاء
                                    (اختياري)</label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('end_time') border-red-500 @enderror">
                                @error('end_time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
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
