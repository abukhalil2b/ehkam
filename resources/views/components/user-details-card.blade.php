@props(['user'])

<div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4 text-indigo-600 border-b pb-2 dark:text-indigo-400">
        معلومات أساسية
    </h2>
    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="py-3 flex justify-between text-sm">
            <dt class="font-medium text-gray-500 dark:text-gray-400">الاسم:</dt>
            <dd class="text-gray-900 dark:text-white">{{ $user->name }}</dd>
        </div>
        <div class="py-3 flex justify-between text-sm">
            <dt class="font-medium text-gray-500 dark:text-gray-400">البريد الإلكتروني:</dt>
            <dd class="text-gray-900 dark:text-white">{{ $user->email }}</dd>
        </div>
        <div class="py-3 flex justify-between text-sm">
            <dt class="font-medium text-gray-500 dark:text-gray-400">تاريخ الانضمام:</dt>
            <dd class="text-gray-900 dark:text-white">{{ $user->created_at->format('Y-m-d') }}</dd>
        </div>
    </dl>
</div>