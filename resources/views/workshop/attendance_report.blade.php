<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        
        <!-- Hero Header with Gradient -->
        <div class="bg-gradient-to-r from-blue-900 to-teal-800 text-white shadow-lg mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                             <a href="{{ route('workshop.index') }}" class="text-blue-100 hover:text-white transition bg-white/10 p-2 rounded-lg backdrop-blur-sm">
                                <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-500/20 text-teal-100 border border-teal-500/30">
                                تقرير تفصيلي
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold tracking-tight">تقرير حضور ورش العمل</h1>
                        <p class="text-blue-100 mt-2 text-lg opacity-90">تحليل بيانات الحضور والمشاركين والالتزام اليومي</p>
                    </div>
                    
                    <!-- Workshop Selector in Header -->
                    <div class="bg-white/10 backdrop-blur-md p-1 rounded-xl border border-white/20">
                         <form method="GET" action="{{ route('workshop.attendance_report') }}" class="flex">
                            <select name="workshop_id" onchange="this.form.submit()" class="block w-64 border-0 bg-transparent text-white placeholder-gray-300 focus:ring-0 text-sm font-medium [&>option]:text-gray-900 cursor-pointer py-2.5 px-4">
                                <option value="" class="text-gray-500">-- اختر ورشة لعرض التقرير --</option>
                                @foreach($workshops as $workshop)
                                    <option value="{{ $workshop->id }}" {{ $selectedWorkshop && $selectedWorkshop->id == $workshop->id ? 'selected' : '' }}>
                                        {{ $workshop->title }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="flex items-center justify-center px-3 text-white/50 border-r border-white/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            @if($selectedWorkshop && $reportData)
                <!-- Workshop Context Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x md:divide-x-reverse divide-gray-100">
                        <div class="p-6">
                            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">الورشة المختارة</h2>
                            <div class="text-xl font-bold text-gray-900">{{ $selectedWorkshop->title }}</div>
                            <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $selectedWorkshop->location ?? 'غير محدد' }}
                            </div>
                        </div>
                        <div class="p-6">
                            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">الفترة الزمنية</h2>
                            <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                <span>{{ $selectedWorkshop->starts_at?->format('Y-m-d') }}</span>
                                <span class="text-gray-300">➜</span>
                                <span>{{ $selectedWorkshop->ends_at?->format('Y-m-d') }}</span>
                            </div>
                            <div class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $reportData['summary']['total_days'] }} أيام عمل
                            </div>
                        </div>
                         <div class="p-6 flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">الحالة</h2>
                                @if($selectedWorkshop->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                        <span class="w-2 h-2 rounded-full bg-green-500 ml-2 animate-pulse"></span>
                                        نشطة حالياً
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">
                                        مكتملة
                                    </span>
                                @endif
                            </div>
                             <div class="text-right">
                                <div class="text-3xl font-bold text-blue-600">{{ $reportData['summary']['overall_attendance_rate'] }}%</div>
                                <div class="text-xs text-blue-800">معدل الحضور العام</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                     <!-- Card 1 -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center text-center hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div class="text-3xl font-bold text-gray-900">{{ $reportData['summary']['total_participants'] }}</div>
                        <div class="text-sm font-medium text-gray-500 mt-1">إجمالي المشاركين</div>
                    </div>
                    
                     <!-- Card 2 -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center text-center hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center mb-4">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="text-3xl font-bold text-gray-900">{{ $reportData['summary']['full_attendance_count'] }}</div>
                        <div class="text-sm font-medium text-gray-500 mt-1">حضور كامل (100%)</div>
                    </div>

                      <!-- Card 3 -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center text-center hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-4">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="text-3xl font-bold text-gray-900">{{ $reportData['summary']['partial_attendance_count'] }}</div>
                        <div class="text-sm font-medium text-gray-500 mt-1">حضور جزئي</div>
                    </div>

                     <!-- Card 4 -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center text-center hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mb-4">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <div class="text-3xl font-bold text-gray-900">{{ $reportData['summary']['total_checkins'] }}</div>
                        <div class="text-sm font-medium text-gray-500 mt-1">إجمالي الحضور المسجل</div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Left Column: Daily Stats -->
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                                <h3 class="font-bold text-gray-900">الأداء اليومي</h3>
                                <span class="text-xs text-gray-500 font-medium bg-white px-2 py-1 rounded border border-gray-200">الأيام: {{ count($reportData['day_stats']) }}</span>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach($reportData['day_stats'] as $dayStat)
                                    <div class="p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="font-medium text-gray-900">{{ $dayStat['label'] ?? 'اليوم ' . ($loop->iteration) }}</div>
                                            <span class="text-xs text-gray-500">{{ $dayStat['date']->format('Y-m-d') }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $dayStat['attendance_rate'] }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-blue-700 w-8 text-left">{{ $dayStat['attendance_rate'] }}%</span>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-500 flex justify-between">
                                            <span>
                                                <strong>{{ $dayStat['checkins_count'] }}</strong> حضور
                                            </span>
                                            <span class="text-red-400">
                                                {{ $reportData['summary']['total_participants'] - $dayStat['checkins_count'] }} غياب
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Participants Table -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                             <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                                <h3 class="font-bold text-gray-900">سجل الحضور التفصيلي</h3>
                                <button class="text-sm text-blue-600 hover:text-blue-800 font-medium transition flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    تصدير (Excel)
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead>
                                        <tr class="bg-gray-50/50">
                                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider sticky right-0 bg-gray-50 z-10">المشارك</th>
                                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">الحالة</th>
                                             <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">أيام الحضور</th>
                                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">النسبة</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @forelse($reportData['participant_stats'] as $participant)
                                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                                <td class="px-6 py-4 whitespace-nowrap sticky right-0 bg-white group-hover:bg-blue-50/30 z-10 transition-colors">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-sm">
                                                            {{ substr($participant['name'], 0, 2) }}
                                                        </div>
                                                        <div class="mr-4">
                                                            <div class="text-sm font-bold text-gray-900">{{ $participant['name'] }}</div>
                                                            <div class="text-xs text-gray-500">{{ $participant['job_title'] ?? 'مشارك' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if($participant['is_full_attendance'])
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                            كامل
                                                        </span>
                                                    @elseif($participant['attendance_rate'] > 0)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                            جزئي
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                            غياب
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <div class="flex items-center justify-center gap-1">
                                                        @foreach($reportData['day_stats'] as $dayStat)
                                                            @if(in_array($dayStat['id'], $participant['days']))
                                                                <div class="w-2.5 h-8 rounded-full bg-green-500" title="{{ $dayStat['label'] }}: حضر"></div>
                                                            @else
                                                                <div class="w-2.5 h-6 rounded-full bg-gray-200 opacity-50" title="{{ $dayStat['label'] }}: غائب"></div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                     <div class="text-sm font-bold text-gray-700">{{ $participant['days_attended'] }}/{{ $reportData['summary']['total_days'] }}</div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                                    لا توجد بيانات حضور لعرضها
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-24 bg-white rounded-3xl border border-dashed border-gray-300">
                    <div class="w-20 h-20 bg-blue-50 text-blue-400 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">اختر ورشة عمل</h2>
                    <p class="text-gray-500 text-center max-w-md">الرجاء اختيار ورشة عمل من القائمة في الأعلى لعرض تقرير الحضور التفصيلي والإحصائيات.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
