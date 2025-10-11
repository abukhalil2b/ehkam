<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <x-slot name="header">
        تعديل المشروع: {{ $project->title }}
    </x-slot>
    
    {{-- Form submits to the 'update' route with the project's ID and uses the PUT method --}}
    <form action="{{ route('project.update', $project) }}" method="POST"> 
        @csrf
        @method('PUT') {{-- Required for Laravel to handle the update as a PUT request --}}

        <div class="container py-8 mx-auto px-4">
            <h3 class="mb-4 text-center text-green-700 text-lg font-bold">وزارة الأوقاف والشؤون الدينية</h3>

            <div class="p-3">
                
                {{-- Sector, Department, and Section Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">القطاع</label>
                        {{-- 'selected' is added to the option that matches the project's current sector_id --}}
                        <select name="sector_id"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                            <option value="" disabled>اختر القطاع</option>
                            @foreach ($sectors as $sector)
                                <option value="{{ $sector->id }}" 
                                    @if ($sector->id == old('sector_id', $project->sector_id)) selected @endif>
                                    {{ $sector->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sector_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- DYNAMIC FIELDS (DEPARTMENT AND SECTION) --}}
                    {{-- These are left as simple selects for now, as in your create view --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الدائرة</label>
                        <select
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                            <option value="" disabled selected>اختر الدائرة</option>
                            <option @if (false) selected @endif> الحوكمة </option>
                            <option @if (false) selected @endif> التخطيط </option>
                            <option @if (false) selected @endif> القرآن</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">القسم</label>
                        <select
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                            <option value="" disabled selected>اختر القسم</option>
                            <option @if (false) selected @endif>قسم التخطيط </option>
                            <option @if (false) selected @endif>قسم الإحصاء </option>
                            <option @if (false) selected @endif>قسم الجودة</option>
                        </select>
                    </div>
                </div>

                {{-- Project Type Field --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">نوع المشروع</label>
                    <select
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        <option value="" disabled selected>اختر نوع المشروع</option>
                        {{-- You would check against a 'type' field in $project here --}}
                        <option @if (false) selected @endif>مشروع</option>
                        <option @if (false) selected @endif>مبادرة تمكينية</option>
                    </select>
                </div>

                {{-- Title Field --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                    <input type="text"
                    name="title"
                        {{-- 'old()' helper retains previous input on validation failure. '->title' is the default value. --}}
                        value="{{ old('title', $project->title) }}" 
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                        placeholder="أدخل اسم المشروع">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description Field --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                    <textarea rows="4"
                        name="description"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                        placeholder="أدخل وصف المشروع">{{ old('description', $project->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit"
                class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                حفظ التعديلات
            </button>
        </div>
    </form>
</x-app-layout>