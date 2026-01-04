<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                تعديل الهدف
            </h2>

            <a href="{{ route('admin.aim.index') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                رجوع
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded p-6">

                <form method="POST"
                      action="{{ route('admin.aim.update', $aim->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            عنوان الهدف
                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $aim->title) }}"
                            required
                            class="w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('title')
                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('admin.aim.index') }}"
                           class="px-4 py-2 border rounded text-gray-700">
                            إلغاء
                        </a>

                        <button
                            type="submit"
                            class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700"
                        >
                            حفظ التغييرات
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
