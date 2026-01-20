<x-app-layout title="إدارة الأدوار">
    <div class="p-4 md:p-6 max-w-6xl mx-auto">
        {{-- Header --}}
        <header
            class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-t-lg shadow-md border-b dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">إدارة الأدوار</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">الأدوار تحدد مجموعات الصلاحيات الممنوحة للمستخدمين
                </p>
            </div>
            @can('create_roles')
                <a href="{{ route('admin.roles.create') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>إضافة دور جديد</span>
                </a>
            @endcan
        </header>

        {{-- Roles Table --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-b-lg shadow-xl border border-t-0 dark:border-gray-700">
            @if($roles->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.768-.231-1.48-.634-2.072M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.768.231-1.48.634-2.072m0 0A6.002 6.002 0 0112 12a6.002 6.002 0 014.366 3.928M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">لا توجد أدوار</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">ابدأ بإضافة دور جديد للنظام</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    الدور
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    الرمز
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    عدد الصلاحيات
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    الإجراءات
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($roles as $role)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $role->title }}</div>
                                                @if($role->description)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ Str::limit($role->description, 40) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        <code
                                            class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs">{{ $role->slug ?? '-' }}</code>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                    {{ $role->permissions_count > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                            {{ $role->permissions_count }} صلاحية
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3 rtl:space-x-reverse">
                                        @can('edit_roles')
                                            <a href="{{ route('admin.roles.permissions', $role) }}"
                                                class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                                    </path>
                                                </svg>
                                                الصلاحيات
                                            </a>
                                            <a href="{{ route('admin.roles.users', $role) }}"
                                                class="inline-flex items-center gap-1 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.768-.231-1.48-.634-2.072M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.768.231-1.48.634-2.072m0 0A6.001 6.001 0 0112 14c2.21 0 4.104 1.207 5.152 3">
                                                    </path>
                                                </svg>
                                                المستخدمون
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- RBAC Info Notice --}}
        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="mr-3">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        <strong>نظام RBAC:</strong> المستخدمون يحصلون على الصلاحيات فقط من خلال الأدوار المسندة إليهم.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>