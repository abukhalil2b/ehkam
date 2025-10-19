<x-app-layout title="قائمة الموظفين">
    <div class="p-4 md:p-6 max-w-6xl mx-auto">

        {{-- Header and Create Button --}}
        <header class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-t-lg shadow-md border-b dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                إدارة الموظفين
            </h1>
            <a href="{{ route('admin_users.create') }}"
               class="flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition duration-150 ease-in-out gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                <span>مستخدم جديد</span>
            </a>
        </header>

        {{-- Users Table --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-b-lg shadow-xl border border-t-0 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                   <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                الاسم
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                                البريد الإلكتروني
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">
                               المسمى الوظيفي
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">
                                الملفات (Profiles)
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                    @if($user->id == 1)
                                        <span class="text-xs font-bold text-red-500">(Super Admin)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 hidden sm:table-cell">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 hidden md:table-cell">
                                    {{ 
                                        $user->latestHistory?->position->title?? 'غير محدد'
                                    }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 hidden lg:table-cell">
                                    {{ $user->profiles->pluck('title')->implode(', ') ?: 'بدون ملف' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3 rtl:space-x-reverse">
                                    
                                    {{-- View Details --}}
                                    <a href="{{ route('admin_users.show', $user) }}"
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-150">
                                        عرض
                                    </a>

                                    {{-- Edit Permissions --}}
                                    @can('admin_users.assign')
                                    <a href="{{ route('admin_users.permissions.edit', $user) }}"
                                       class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition duration-150">
                                        صلاحيات
                                    </a>
                                    @endcan

                                    {{-- Additional actions (e.g., Delete, Edit User Info) --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    لم يتم العثور على أي مستخدمين.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
