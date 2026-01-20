<x-app-layout title="لوحة إدارة القطاع">
    <div class="container py-6 mx-auto px-4 sm:px-6">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">
                    لوحة إدارة القطاع
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    @if($sector)
                        <span class="inline-flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            {{ $sector->name }}
                        </span>
                    @else
                        <span class="text-amber-600">لم يتم تحديد قطاع</span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if(isset($activeRole))
                    <span
                        class="px-3 py-1.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-lg text-sm font-medium">
                        {{ $activeRole->title }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-5 rounded-xl text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">إجمالي المهام</p>
                        <p class="text-3xl font-bold mt-1">{{ $tasks->count() }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-5 rounded-xl text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 text-sm">صحة المشروع</p>
                        <p class="text-3xl font-bold mt-1">{{ $healthPercentage }}%</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-500 to-amber-600 p-5 rounded-xl text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-100 text-sm">تنبيهات نشطة</p>
                        <p class="text-3xl font-bold mt-1">{{ $activeAlerts }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-5 rounded-xl text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">عدد الأهداف</p>
                        <p class="text-3xl font-bold mt-1">{{ $aims->count() }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Workflows --}}
        @if(isset($myWorkflows) && $myWorkflows->count() > 0)
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
                <h3 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                    مهامي المعلقة
                </h3>
                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($myWorkflows as $workflow)
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-2">
                                <span
                                    class="text-xs font-semibold px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">
                                    {{ __('step_stages.' . $workflow->stage) }}
                                </span>
                                <span
                                    class="text-[10px] text-gray-400">{{ $workflow->created_at?->diffForHumans() ?? '' }}</span>
                            </div>
                            <h4 class="font-bold text-gray-800 dark:text-white text-sm mb-1 truncate">
                                {{ $workflow->step->name ?? 'خطوة بدون عنوان' }}
                            </h4>
                            <a href="{{ route('step.show', $workflow->step_id) }}"
                                class="inline-flex items-center justify-center w-full mt-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 rounded-lg transition">
                                معالجة
                                <svg class="w-3 h-3 mr-1 rtl:ml-1 rtl:mr-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Aims Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    إسهامات القطاع في المؤشرات الرئيسية
                </h2>
            </div>

            @if($aims->isEmpty())
                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p>لا توجد أهداف مسجلة حالياً</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($aims as $aim)
                        <div
                            class="group bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 p-5 rounded-xl border border-gray-200 dark:border-gray-600 hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-300">
                            <div class="flex items-start gap-3 mb-4">
                                <div
                                    class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white leading-tight">
                                    {{ $aim->title }}
                                </h3>
                            </div>
                            <a href="{{ route('aim_sector_feedback.index', $aim->id) }}"
                                class="flex items-center justify-center w-full py-2.5 text-sm font-medium rounded-lg border-2 border-blue-500 text-blue-600 dark:text-blue-400 hover:bg-blue-500 hover:text-white dark:hover:text-white transition-all duration-300">
                                <span>بيانات المؤشر</span>
                                <svg class="w-4 h-4 mr-2 rtl:ml-2 rtl:mr-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Notifications --}}
        @if(isset($recentNotifications) && $recentNotifications->count() > 0)
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mt-8">
                <h3 class="font-bold text-gray-800 dark:text-white mb-4">آخر التحديثات</h3>
                <div class="space-y-3">
                    @foreach($recentNotifications as $notification)
                        <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $notification->data['message'] ?? 'إشعار جديد' }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>