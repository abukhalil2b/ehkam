<x-app-layout>
    <!-- Header Slot -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إنشاء محضر ورشة عمل جديد
        </h2>
    </x-slot>

    <!-- Main Content -->
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                <form action="{{ route('workshop.store') }}" method="POST">
                    @csrf

                    {{-- Title --}}
                    <div class="mb-5">
                        <label for="title" class="block text-gray-700 font-bold mb-2">العنوان</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror" 
                            required 
                            placeholder="عنوان الورشة">
                        @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Date --}}
                    <div class="mb-5">
                        <label for="date" class="block text-gray-700 font-bold mb-2">التاريخ</label>
                        <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('date') border-red-500 @enderror" 
                            required>
                        @error('date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Place (New Field) --}}
                    <div class="mb-5">
                        <label for="place" class="block text-gray-700 font-bold mb-2">المكان</label>
                        <input type="text" id="place" name="place" value="{{ old('place') }}" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('place') border-red-500 @enderror" 
                            placeholder="اسم القاعة أو المكان">
                        @error('place') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Written By (Read-only display) --}}
                    <div class="mb-5 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <label class="block text-gray-700 font-bold mb-1">كتب بواسطة</label>
                        <div class="font-semibold text-blue-800 text-lg"> {{ auth()->user()->name }} </div>
                    </div>

                    {{-- Attendances --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">الحضور</label>
                        <div id="attendances-container">
                            <!-- Initial input -->
                            <input type="text" name="attendances[]" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 mb-2" placeholder="اسم الحاضر الأول" value="{{ old('attendances.0') }}">
                            
                            <!-- Display old inputs in case of validation failure -->
                            @if (old('attendances') && count(old('attendances')) > 1)
                                @foreach (array_slice(old('attendances'), 1) as $index => $attendance)
                                    <input type="text" name="attendances[]" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 mb-2" placeholder="اسم الحاضر" value="{{ $attendance }}">
                                @endforeach
                            @endif
                        </div>
                        <button type="button" 
                                class="text-blue-600 hover:text-blue-800 font-semibold transition duration-150 ease-in-out mt-1" 
                                onclick="addAttendance()">
                            + إضافة اسم آخر
                        </button>
                        @error('attendances.*') <p class="text-red-500 text-sm mt-1">يرجى التحقق من صحة الأسماء المدخلة.</p> @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded-lg shadow-md transition duration-150 ease-in-out">
                            حفظ محضر الورشة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for adding attendance fields -->
    <script>
        function addAttendance() {
            const container = document.getElementById('attendances-container');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'attendances[]';
            // Use Tailwind classes for consistency
            input.className = 'w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 mb-2';
            input.placeholder = 'اسم الحاضر';
            container.appendChild(input);
            input.focus(); // Focus on the new input field
        }
    </script>
</x-app-layout>
