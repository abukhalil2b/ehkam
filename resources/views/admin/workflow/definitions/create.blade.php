<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('إنشاء سير عمل جديد') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('admin.workflow.definitions.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('اسم سير العمل') }}
                        <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        placeholder="{{ __('مثال: سير عمل الموافقة على المشاريع') }}"
                        class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description"
                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('الوصف') }}</label>
                    <textarea id="description" name="description" rows="3"
                        placeholder="{{ __('وصف مختصر لسير العمل والغرض منه') }}"
                        class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="entity_type"
                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('نوع الكيان') }}</label>
                    <select id="entity_type" name="entity_type" class="w-full border rounded px-3 py-2">
                        <option value="App\Models\Activity" {{ old('entity_type') == 'App\Models\Activity' ? 'selected' : '' }}>{{ __('نشاط') }}</option>
                        <option value="App\Models\Project" {{ old('entity_type') == 'App\Models\Project' ? 'selected' : '' }}>{{ __('مشروع') }}</option>
                    </select>
                    <p class="text-gray-500 text-sm mt-1">{{ __('حدد نوع العنصر الذي سيتم تطبيق سير العمل عليه') }}</p>
                </div>

                <div class="mb-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="rounded border-gray-300">
                        <span class="text-sm text-gray-700">{{ __('نشط') }}</span>
                    </label>
                    <p class="text-gray-500 text-sm mt-1">{{ __('سير العمل النشط يمكن تعيينه للخطوات الجديدة') }}</p>
                </div>

                <div class="bg-blue-50 text-blue-700 px-4 py-3 rounded mb-6">
                    <svg class="inline w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ __('بعد إنشاء سير العمل، ستتمكن من إضافة المراحل (الفرق) إليه') }}
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        {{ __('إنشاء سير العمل') }}
                    </button>
                    <a href="{{ route('admin.workflow.definitions.index') }}"
                        class="px-4 py-2 border rounded hover:bg-gray-50">
                        {{ __('إلغاء') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>