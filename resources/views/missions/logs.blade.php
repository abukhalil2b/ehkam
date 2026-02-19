@php
    function formatValue($key, $value) {
        $labels = [
            'status' => [
                'pending' => 'قيد الانتظار',
                'in_progress' => 'جاري العمل',
                'completed' => 'مكتمل',
                'cancelled' => 'ملغى',
            ],
            'priority' => [
                'high' => 'عالي',
                'medium' => 'متوسط',
                'low' => 'منخفض',
            ],
            'is_private' => [
                '1' => 'خاص',
                '0' => 'عام',
                true => 'خاص',
                false => 'عام',
            ],
        ];

        if (isset($labels[$key])) {
            return $labels[$key][$value] ?? $value;
        }

        if ($key == 'due_date' && $value) {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        
         if ($key == 'assigned_to') {
            $user = \App\Models\User::find($value);
            return $user ? $user->name : 'غير محدد';
        }

        return $value;
    }

    function getFieldLabel($key) {
        $labels = [
            'status' => 'الحالة',
            'priority' => 'الأولوية',
            'title' => 'العنوان',
            'description' => 'الوصف',
            'due_date' => 'تاريخ الاستحقاق',
            'assigned_to' => 'المكلف',
            'is_private' => 'الخصوصية',
        ];
        return $labels[$key] ?? $key;
    }
@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل حركات المهام</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body @class(['bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200', 'min-h-screen']) x-data :class="{ 'dark': $store.app.theme === 'dark' || $store.app.isDarkMode }">

    <!-- HEADER -->
    <nav @class(['bg-white dark:bg-gray-800 border-b dark:border-gray-700 shadow-sm transition-colors sticky top-0 z-30'])>
        <div @class([
            'max-w-3xl',
            'mx-auto',
            'px-6',
            'py-4',
            'flex',
            'justify-between',
            'items-center',
        ])>
            <h1 @class(['text-xl', 'font-bold'])>سجل حركات المهام</h1>

            <div class="flex items-center gap-4">
                {{-- Dark Mode Toggle --}}
                <button type="button"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300"
                    @click="$store.app.toggleTheme($store.app.theme === 'dark' ? 'light' : 'dark')">
                    <svg x-show="$store.app.theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                    <svg x-show="$store.app.theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </button>

                <a href="{{ route('mission.index') }}"
                    class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <x-svgicon.back_arrow />
                    <span class="hidden sm:inline">عودة</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <main class="max-w-3xl mx-auto px-6 py-10">
        
        <div class="relative border-r border-gray-200 dark:border-gray-700 mr-4 space-y-8">
            @forelse ($logs as $log)
                <div class="mb-10 mr-6 relative group">
                    <!-- Icon -->
                    <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -mr-4 ring-4 ring-slate-50 dark:ring-gray-900 
                        @if ($log->action == 'created') bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-300
                        @elseif($log->action == 'updated_status') bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-300
                        @elseif($log->action == 'updated') bg-yellow-100 text-yellow-600 dark:bg-yellow-900/50 dark:text-yellow-300
                        @elseif($log->action == 'deleted') bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-300
                        @else bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 @endif">
                        
                        @if ($log->action == 'created')
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        @elseif($log->action == 'updated_status')
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @elseif($log->action == 'updated')
                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        @elseif($log->action == 'deleted')
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @endif
                    </span>
                    
                    <!-- Content Card -->
                    <div class="px-5 py-4 bg-white border border-gray-100 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3">
                            <div class="flex items-center gap-2 mb-2 sm:mb-0">
                                <span class="font-bold text-gray-900 dark:text-white text-base">{{ $log->user->name ?? 'مستخدم غير معروف' }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    @if ($log->action == 'created') قام بإنشاء مهمة
                                    @elseif($log->action == 'updated_status') قام بتغيير حالة مهمة
                                    @elseif($log->action == 'updated') قام بتحديث مهمة
                                    @elseif($log->action == 'deleted') قام بحذف مهمة
                                    @elseif($log->action == 'reassigned') قام بنقل مهمة
                                    @else {{ $log->action }} @endif
                                </span>
                            </div>
                            <time class="text-xs text-gray-400 whitespace-nowrap" title="{{ $log->created_at }}">
                                {{ $log->created_at->diffForHumans() }}
                            </time>
                        </div>

                        <!-- Task & Mission Info -->
                        <div class="mb-4">
                            <h3 class="font-semibold text-lg text-indigo-600 dark:text-indigo-400">
                                {{ $log->task->title ?? 'مهمة محذوفة' }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                {{ $log->task->mission->title ?? '-' }}
                            </p>
                        </div>

                        <!-- Changes Diff -->
                        @if ($log->old_values || $log->new_values)
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3 text-sm border border-gray-100 dark:border-gray-700/50">
                            
                            @if ($log->action == 'updated_status' && isset($log->old_values['status']) && isset($log->new_values['status']))
                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {{ formatValue('status', $log->old_values['status']) }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                    <span class="px-2 py-1 rounded text-xs font-medium 
                                        @if($log->new_values['status'] == 'completed') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                                        @elseif($log->new_values['status'] == 'in_progress') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300
                                        @else bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ formatValue('status', $log->new_values['status']) }}
                                    </span>
                                </div>
                            
                            @elseif ($log->action == 'updated')
                                <ul class="space-y-2">
                                    @foreach ($log->new_values as $key => $newValue)
                                        @if (in_array($key, ['updated_at', 'created_at', 'id', 'mission_id', 'completed_at'])) @continue @endif
                                        @php 
                                            $oldValue = $log->old_values[$key] ?? null; 
                                            if($oldValue == $newValue) continue;
                                        @endphp
                                        <li class="grid grid-cols-[auto_1fr] gap-2 items-baseline">
                                            <span class="font-medium text-gray-700 dark:text-gray-300 text-xs w-24">{{ getFieldLabel($key) }}:</span>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                @if($oldValue)
                                                    <span class="line-through text-gray-400 text-xs">{{ formatValue($key, $oldValue) }}</span>
                                                    <svg class="w-3 h-3 text-gray-400 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                                @endif
                                                <span class="text-gray-900 dark:text-gray-100 font-medium">{{ formatValue($key, $newValue) }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                            @elseif ($log->action == 'created')
                                <div class="text-xs text-gray-500">تم إنشاء المهمة ببياناتها الأولية.</div>
                            
                            @elseif ($log->action == 'deleted')
                                <div class="text-xs text-red-500">تم حذف المهمة.</div>

                            @elseif ($log->action == 'reassigned' && isset($log->old_values['assigned_to']) && isset($log->new_values['assigned_to']))
                                <div class="flex items-center gap-2">
                                     <span class="text-gray-500">من:</span>
                                     <span class="font-medium">{{ formatValue('assigned_to', $log->old_values['assigned_to']) }}</span>
                                     <svg class="w-3 h-3 text-gray-400 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                     <span class="text-gray-500">إلى:</span>
                                     <span class="font-medium">{{ formatValue('assigned_to', $log->new_values['assigned_to']) }}</span>
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 mr-10">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium text-lg">لا توجد سجلات لعرضها حالياً</p>
                </div>
            @endforelse
        </div>
        
        @if ($logs->hasPages())
            <div class="mt-8">
                {{ $logs->links() }}
            </div>
        @endif

    </main>

    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>

</html>