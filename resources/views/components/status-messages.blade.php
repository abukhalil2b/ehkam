@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 dark:bg-green-900/50 dark:border-green-700 dark:text-green-300" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 dark:bg-red-900/50 dark:border-red-700 dark:text-red-300" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif