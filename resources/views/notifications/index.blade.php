<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('الإشعارات') }}
            </h2>
            @if($notifications->count() > 0)
                <a href="{{ route('notifications.markAllRead') }}"
                    class="text-sm text-primary dark:text-blue-400 hover:underline">
                    تحديد الكل كمقروء
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="space-y-4">
                        @forelse($notifications as $notification)
                            <div
                                class="flex items-center justify-between p-4 rounded-lg {{ $notification->read_at ? 'bg-gray-50 dark:bg-gray-700/50' : 'bg-blue-50 dark:bg-blue-900/20 border-r-4 border-blue-500 dark:border-blue-400' }}">
                                <div class="flex items-center space-x-4 space-x-reverse">
                                    <div class="flex-shrink-0">
                                        @if($notification->type == 'workflow_assignment')
                                            <span
                                                class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-500 dark:text-blue-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                    </path>
                                                </svg>
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                                    </path>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $notification->data['message'] ?? 'إشعار جديد' }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    @if(isset($notification->data['action_url']) || isset($notification->data['link']))
                                        <a href="{{ route('notifications.readAndRedirect', $notification->id) }}"
                                            class="text-sm text-primary dark:text-blue-400 hover:text-primary-dark dark:hover:text-blue-300 font-medium">
                                            عرض
                                        </a>
                                    @endif
                                    @if(!$notification->read_at)
                                        <a href="{{ route('notifications.markRead', $notification->id) }}"
                                            class="text-xs text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                                            تحديد كمقروء
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                                لا توجد إشعارات حالياً
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>