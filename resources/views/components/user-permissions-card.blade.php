@props(['user'])

{{-- Assigned Roles Card --}}
<div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4 text-blue-600 border-b pb-2 dark:text-blue-400">
        الأدوار الممنوحة (Roles)
    </h2>
    <div class="flex flex-wrap gap-2">
        @forelse ($user->roles as $role)
            <span
                class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300">
                {{ $role->title }}
            </span>
        @empty
            <span class="text-gray-500 dark:text-gray-400">لا توجد أدوار مخصصة.</span>
        @endforelse
    </div>
</div>

{{-- Effective Permissions Card (through roles) --}}
<div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4 text-green-600 border-b pb-2 dark:text-green-400">
        الصلاحيات الفعلية (من خلال الأدوار)
    </h2>
    @php
        // Get all permissions from all assigned roles
        $effectivePermissions = $user->roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique('id');
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @forelse ($effectivePermissions as $permission)
            <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-md">
                <span class="font-medium text-gray-800 dark:text-gray-100">{{ $permission->title }}</span>
                <code class="block text-xs text-gray-500 dark:text-gray-400">{{ $permission->slug }}</code>
            </div>
        @empty
            <span class="text-gray-500 dark:text-gray-400 md:col-span-2">لا توجد صلاحيات مخصصة لهذا المستخدم.</span>
        @endforelse
    </div>
    @if($effectivePermissions->isNotEmpty())
        <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd"></path>
            </svg>
            نظام RBAC: المستخدمون يحصلون على الصلاحيات فقط من خلال الأدوار المسندة إليهم.
        </p>
    @endif
</div>