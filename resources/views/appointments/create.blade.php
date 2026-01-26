<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إنشاء طلب موعد جديد') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('appointments.store') }}" method="POST">
                @csrf

                <!-- Minister Selection -->
                <div class="mb-4">
                    <label for="minister_id" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('الوزير') }} <span class="text-red-500">*</span> {{ $minister->name }}
                    </label>
                    <input type="hidden" name="minister_id" value="{{ $minister->id }}">
                </div>

                <!-- Subject -->
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('الموضوع') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                        placeholder="{{ __('أدخل موضوع طلب الموعد') }}">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('الوصف') }}
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                        placeholder="{{ __('أدخل وصف تفصيلي لطلب الموعد (اختياري)') }}">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div class="mb-6">
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('الأولوية') }}
                    </label>
                    <select name="priority" id="priority"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>{{ __('عادية') }}</option>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>{{ __('منخفضة') }}</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>{{ __('عالية') }}</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>{{ __('عاجلة') }}</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('appointments.index') }}"
                        class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('إلغاء') }}
                    </a>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        {{ __('إنشاء الطلب') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
