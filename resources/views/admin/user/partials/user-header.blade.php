<header class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-t-lg shadow-md border-b dark:border-gray-700 mb-4">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
        تفاصيل الموظف: {{ $user->name }}
    </h1>
    <div class="flex space-x-2 rtl:space-x-reverse">
        <a href="{{ route('admin_users.permissions.edit', $user) }}"
           class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
            تعديل الصلاحيات
        </a>
        <a href="{{ route('admin_users.index') }}"
           class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300">
            العودة للقائمة
        </a>
    </div>
</header>