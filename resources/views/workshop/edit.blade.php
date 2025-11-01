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
                <form action="{{ route('workshop.update', $workshop->id) }}" 
                    method="POST" 
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div class="mb-5">
                        <label for="title" class="block text-gray-700 font-bold mb-2">العنوان</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $workshop->title) }}" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror" 
                            required 
                            placeholder="عنوان الورشة">
                        @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Date --}}
                    <div class="mb-5">
                        <label for="date" class="block text-gray-700 font-bold mb-2">التاريخ</label>
                        <input type="date" id="date" name="date" value="{{ old('date', \Carbon\Carbon::parse($workshop->date)->format('Y-m-d')) }}" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('date') border-red-500 @enderror" 
                            required>
                        @error('date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Place (Added Field) --}}
                    <div class="mb-5">
                        <label for="place" class="block text-gray-700 font-bold mb-2">المكان</label>
                        <input type="text" id="place" name="place" value="{{ old('place', $workshop->place) }}" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('place') border-red-500 @enderror" 
                            placeholder="اسم القاعة أو المكان">
                        @error('place') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Written By --}}
                    <div class="mb-5">
                        <label for="written_by" class="block text-gray-700 font-bold mb-2">كتب بواسطة</label>
                        <select id="written_by" name="written_by" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('written_by') border-red-500 @enderror" 
                            required>
                            <option value="">اختر المستخدم</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ old('written_by', $workshop->written_by) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('written_by') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Attendances --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">الحضور</label>
                        <div id="attendances-container">
                            @php
                                $attendeeNames = old('attendances') 
                                    ? old('attendances') 
                                    : $workshop->attendances->pluck('name')->toArray();
                            @endphp
                            
                            @forelse($attendeeNames as $attendance)
                                <input type="text" name="attendances[]" value="{{ $attendance }}" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 mb-2" 
                                    placeholder="اسم الحاضر">
                            @empty
                                <input type="text" name="attendances[]" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 mb-2" 
                                    placeholder="اسم الحاضر الأول">
                            @endforelse
                        </div>
                        <button type="button" 
                                class="text-blue-600 hover:text-blue-800 font-semibold transition duration-150 ease-in-out mt-1" 
                                onclick="addAttendance()">
                            + إضافة اسم آخر
                        </button>
                        @error('attendances.*') <p class="text-red-500 text-sm mt-1">يرجى التحقق من صحة الأسماء المدخلة.</p> @enderror
                    </div>

                    {{-- Submit & Cancel --}}
                    <div class="pt-4 border-t border-gray-200 mt-4 flex justify-between items-center">
                        <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-2 rounded-lg shadow-md transition duration-150 ease-in-out">
                            تحديث محضر الورشة
                        </button>
                        <a href="{{ route('workshop.index') }}" class="text-gray-600 hover:text-gray-800 underline transition duration-150">إلغاء</a>
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
