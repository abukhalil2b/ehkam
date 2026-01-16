<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('إنشاء فريق جديد') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('admin.workflow.teams.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('اسم الفريق') }} <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('الوصف') }}</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('أعضاء الفريق') }}</label>
                    <div class="border rounded p-3 max-h-64 overflow-y-auto">
                        @foreach($users as $user)
                            <label class="flex items-center gap-2 py-1">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                       {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300">
                                <span>{{ $user->name }}</span>
                                @if($user->email)
                                    <span class="text-gray-400 text-sm">({{ $user->email }})</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                    @error('user_ids')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        {{ __('إنشاء الفريق') }}
                    </button>
                    <a href="{{ route('admin.workflow.teams.index') }}" class="px-4 py-2 border rounded hover:bg-gray-50">
                        {{ __('إلغاء') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
