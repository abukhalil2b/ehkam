<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات التقويم السنوي</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .islamic-pattern {
            background-color: #064e3b;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 30L15 45L0 30L15 15L30 30zm30 0L45 45L30 30L45 15L60 30zM15 15L0 0h30L15 15zm30 0L30 0h30L45 15zM15 45L0 60h30L15 45zm30 0L30 60h30L45 45z' fill='%23065f46' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

    <header class="islamic-pattern text-white py-10 shadow-xl">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h1 class="text-3xl font-bold mb-2">إعدادات التقويم السنوي</h1>
                    <p class="text-xl opacity-90">إدارة وتخصيص نظام التقويم</p>
                </div>
                <a href="{{ route('calendar.index') }}" 
                   class="bg-yellow-400 text-emerald-900 px-6 py-3 rounded-xl font-bold hover:bg-yellow-300 transition shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    عرض التقويم
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-10">
        
        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 rounded-2xl shadow-lg text-white">
                <div class="flex items-center justify-between mb-4">
                    <svg class="w-10 h-10 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-3xl font-bold">{{ $stats['total_events'] }}</span>
                </div>
                <h3 class="text-sm opacity-90 font-bold">إجمالي الأنشطة</h3>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-700 p-6 rounded-2xl shadow-lg text-white">
                <div class="flex items-center justify-between mb-4">
                    <svg class="w-10 h-10 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="text-3xl font-bold">{{ $stats['active_users'] }}</span>
                </div>
                <h3 class="text-sm opacity-90 font-bold">المستخدمون النشطون</h3>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-700 p-6 rounded-2xl shadow-lg text-white">
                <div class="flex items-center justify-between mb-4">
                    <svg class="w-10 h-10 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-3xl font-bold">{{ $stats['upcoming_this_month'] }}</span>
                </div>
                <h3 class="text-sm opacity-90 font-bold">أنشطة هذا الشهر</h3>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-700 p-6 rounded-2xl shadow-lg text-white">
                <div class="flex items-center justify-between mb-4">
                    <svg class="w-10 h-10 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-3xl font-bold">{{ $stats['active_now'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm opacity-90 font-bold">أنشطة نشطة الآن</h3>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            
            {{-- Permission Management Card --}}
            <a href="{{ route('calendar.delegations.index') }}"
                class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-blue-500 hover:shadow-md transition group">
                <div class="text-blue-600 mb-3 group-hover:scale-110 transition">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 text-lg mb-2">صلاحيات الإدارة</h3>
                <p class="text-sm text-gray-500 mb-3">تحديد من يمكنه الكتابة في تقويم الآخرين</p>
                @if(isset($delegations) && $delegations->count() > 0)
                <span class="inline-block bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-bold">
                    {{ $delegations->count() }} تفويض نشط
                </span>
                @endif
            </a>

            <a href="#export-section"
                class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-amber-500 hover:shadow-md transition group">
                <div class="text-amber-600 mb-3 group-hover:scale-110 transition">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 text-lg mb-2">تصدير التقارير</h3>
                <p class="text-sm text-gray-500 mb-3">تحميل جميع الأنشطة بصيغة Excel أو PDF</p>
                <span class="inline-block bg-amber-100 text-amber-700 text-xs px-3 py-1 rounded-full font-bold">
                    قريباً
                </span>
            </a>

            <a href="#types-section"
                class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-emerald-500 hover:shadow-md transition group">
                <div class="text-emerald-600 mb-3 group-hover:scale-110 transition">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 text-lg mb-2">أنواع الأنشطة</h3>
                <p class="text-sm text-gray-500 mb-3">تعديل تصنيفات الأنشطة وألوانها</p>
                <span class="inline-block bg-emerald-100 text-emerald-700 text-xs px-3 py-1 rounded-full font-bold">
                    4 أنواع
                </span>
            </a>
        </div>

        {{-- User Activity Table --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-10">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-6 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold">نشاط المستخدمين</h2>
                    <p class="text-sm opacity-90 mt-1">إحصائيات استخدام التقويم لكل مستخدم</p>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-50 text-gray-600 text-sm border-b-2 border-gray-200">
                        <tr>
                            <th class="p-4 font-bold">#</th>
                            <th class="p-4 font-bold">المستخدم</th>
                            <th class="p-4 font-bold">القسم</th>
                            <th class="p-4 font-bold text-center">عدد الأنشطة</th>
                            <th class="p-4 font-bold">آخر نشاط</th>
                            <th class="p-4 font-bold text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($userActivity as $index => $user)
                        <tr class="hover:bg-emerald-50 transition">
                            <td class="p-4">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email ?? 'لا يوجد بريد' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="text-sm text-gray-600">{{ $user->department_name ?? 'غير محدد' }}</span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full text-sm font-bold inline-flex items-center gap-2">
                                    {{ $user->events_count }}
                                </span>
                            </td>
                            <td class="p-4">
                                <p class="text-sm text-gray-600">
                                    {{ $user->calendarEvents->max('created_at')?->diffForHumans() ?? 'لا يوجد' }}
                                </p>
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('calendar.index', ['user_id' => $user->id]) }}"
                                    class="inline-flex items-center gap-2 text-emerald-600 hover:bg-emerald-50 px-4 py-2 rounded-lg transition text-sm font-bold">
                                    عرض
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-gray-500">لا توجد بيانات نشاط</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Activity Types Section (Static for now) --}}
        <div id="types-section" class="bg-white rounded-2xl shadow-lg overflow-hidden mb-10">
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 p-6 text-white">
                <h2 class="text-2xl font-bold">أنواع الأنشطة المتاحة</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach([
                    ['type' => 'program', 'label' => 'برنامج', 'color' => '#16a34a'],
                    ['type' => 'meeting', 'label' => 'اجتماع', 'color' => '#2563eb'],
                    ['type' => 'conference', 'label' => 'مؤتمر', 'color' => '#7c3aed'],
                    ['type' => 'competition', 'label' => 'مسابقة', 'color' => '#db2777']
                ] as $activityType)
                <div class="flex items-center gap-4 p-4 border-2 border-gray-100 rounded-xl hover:border-gray-200 transition">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: {{ $activityType['color'] }}20;">
                        <span class="w-4 h-4 rounded-full" style="background-color: {{ $activityType['color'] }}"></span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800">{{ $activityType['label'] }}</h3>
                        <p class="text-xs text-gray-500">{{ $activityType['type'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Back to Dashboard --}}
        <div class="flex justify-center py-6">
            <a href="/dashboard" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 px-6 py-3 rounded-xl font-bold hover:bg-white transition">
                العودة للوحة التحكم
            </a>
        </div>
    </main>
</body>
</html>