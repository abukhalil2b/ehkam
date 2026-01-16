@props(['user'])

{{-- Assigned roles Card --}}
<div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4 text-blue-600 border-b pb-2 dark:text-blue-400">
        الملفات الممنوحة (roles)
    </h2>
    <div class="flex flex-wrap gap-2">
        @forelse ($user->roles as $profile)
            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300">
                {{ $profile->title }}
            </span>
        @empty
            <span class="text-gray-500 dark:text-gray-400">لا يوجد ملف أساسي مخصص.</span>
        @endforelse
    </div>
</div>

{{-- Direct Permissions Card --}}
<div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4 text-green-600 border-b pb-2 dark:text-green-400">
        الصلاحيات الفردية المباشرة
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @forelse ($user->permissions as $permission)
            <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-md">
                <span class="font-medium text-gray-800 dark:text-gray-100">{{ $permission->title }}</span>
                <code class="block text-xs text-gray-500 dark:text-gray-400">{{ $permission->slug }}</code>
            </div>
        @empty
            <span class="text-gray-500 dark:text-gray-400 md:col-span-2">لا توجد صلاحيات فردية مخصصة لهذا المستخدم.</span>
        @endforelse
    </div>
</div>