<x-app-layout title="صلاحيات">
    <div class="p-4 md:p-6 max-w-4xl mx-auto">

 
        {{-- Permissions Table/List --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-b-lg shadow-xl border border-t-0 dark:border-gray-700">

            @if ($permissions->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 text-center py-10">
                    لا توجد صلاحيات مسجلة حاليًا. اضغط "صلاحية جديدة" لإضافة أول صلاحية.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">
                                    العنوان
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/4">
                                    الرمز (Slug)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($permissions as $permission)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    {{-- Title --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $permission->title }}
                                    </td>
                                    {{-- Slug --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        <code>{{ $permission->slug }}</code>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>