<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">
            إدارة صلاحيات المستخدم: {{ $user->name }}
        </h1>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <form action="{{ route('admin_users.permissions.update', $user) }}" method="POST">
                @csrf
                @method('PUT') {{-- Use PUT method for updates --}}

                {{-- ---------------------------------------------------- --}}
                {{-- 1. Profiles/Roles Assignment --}}
                {{-- ---------------------------------------------------- --}}
                <div class="mb-8 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4 text-blue-600 dark:text-blue-400 border-b pb-2">
                        الصلاحيات الرئيسية (Profiles)
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        اختر الملف (البروفايل) الذي يحدد مجموعة الصلاحيات الأساسية للمستخدم.
                    </p>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @forelse ($allProfiles as $profile)
                            <label
                                class="flex items-center space-x-3 bg-gray-50 dark:bg-gray-700 p-3 rounded-md cursor-pointer hover:shadow-lg transition">
                                <input type="checkbox" name="profiles[]" value="{{ $profile->id }}"
                                    @checked(in_array($profile->id, $assignedProfileIds))
                                    class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 dark:border-gray-500 dark:bg-gray-600">
                                <span class="text-gray-700 dark:text-gray-200 font-medium">
                                    {{ $profile->title }}
                                </span>
                            </label>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 col-span-4">
                                لا توجد ملفات صلاحيات (Profiles) متاحة حاليًا.
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- ---------------------------------------------------- --}}
                {{-- 2. Direct Permissions Assignment --}}
                {{-- ---------------------------------------------------- --}}
                <div class="mb-8 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4 text-green-600 dark:text-green-400 border-b pb-2">
                        الصلاحيات الفردية (Individual Permissions)
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        امنح أو اسحب صلاحيات محددة لا تتبع الملف الرئيسي.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse ($allPermissions as $permission)
                            <label
                                class="flex items-center space-x-3 bg-white dark:bg-gray-900 p-2 rounded-md border border-gray-100 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    @checked(in_array($permission->id, $assignedPermissionIds))
                                    class="form-checkbox h-5 w-5 text-green-600 rounded border-gray-300 dark:border-gray-500 dark:bg-gray-600">
                                <div>
                                    <span
                                        class="text-gray-800 dark:text-gray-100 font-medium">{{ $permission->title }}</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 rtl:text-right">
                                        ({{ $permission->slug }})</p>
                                </div>
                            </label>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 col-span-3">
                                لا توجد صلاحيات فردية (Permissions) متاحة حاليًا.
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end justify-between mt-6">
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        تحديث الصلاحيات
                    </button>
                    <a href="{{ url()->previous() }}"
                        class="px-6 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        إلغاء
                    </a>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
