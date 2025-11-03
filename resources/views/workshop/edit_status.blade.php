<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تفعيل وتعطيل الورشة
        </h2>
    </x-slot>

    <!-- Main Content -->
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                <form action="{{ route('workshop.update_status', $workshop->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Active Status -->
                    <div class="mb-6">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $workshop->is_active) ? 'checked' : '' }} class="sr-only peer">
                            <div
                                class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                            </div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                {{ old('is_active', $workshop->is_active) ? 'مفعلة' : 'غير مفعلة' }} </span>
                        </label>
                    </div>
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                    <div class="pt-4 border-t border-gray-200 mt-4 flex justify-between items-center">
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-2 rounded-lg shadow-md transition duration-150 ease-in-out">
                            تفعيل وتعطيل
                        </button>
                        <a href="{{ route('workshop.index') }}"
                            class="text-gray-600 hover:text-gray-800 underline transition duration-150">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
