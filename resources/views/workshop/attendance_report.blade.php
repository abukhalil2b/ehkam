<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">تقرير حضور ورش العمل</h1>
                <p class="text-gray-500 mt-1">عرض وتحليل حضور المشاركين في ورش العمل</p>
            </div>
            <a href="{{ route('workshop.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                <svg class="w-5 h-5 ml-2 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                عودة للقائمة
            </a>
        </div>

        <!-- Workshop Selector -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="{{ route('workshop.attendance_report') }}" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label for="workshop_id" class="block text-sm font-medium text-gray-700 mb-2">اختر الورشة</label>
                    <select name="workshop_id" id="workshop_id" class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-3">
                        <option value="">-- اختر ورشة عمل --</option>
                        @foreach($workshops as $workshop)
                            <option value="{{ $workshop->id }}" {{ $selectedWorkshop && $selectedWorkshop->id == $workshop->id ? 'selected' : '' }}>
                                {{ $workshop->title }} ({{ $workshop->days->count() }} أيام | {{ $workshop->attendances->count() }} مشارك)
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                    عرض التقرير
                </button>
                @if($selectedWorkshop)
                    <a href="{{ route('workshop.attendance_report') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        مسح
                    </a>
                @endif
            </form>
        </div>

        @if($selectedWorkshop && $reportData)
            <!-- Workshop Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $selectedWorkshop->title }}</h2>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $selectedWorkshop->location ?? 'غير محدد' }} | 
                                {{ $selectedWorkshop->starts_at?->format('Y-m-d') }} 
                                @if($selectedWorkshop->ends_at && $selectedWorkshop->ends_at != $selectedWorkshop->starts_at)
                                    إلى {{ $selectedWorkshop->ends_at->format('Y-m-d') }}
                                @endif
                            </p>
                        </div>
                        @if($selectedWorkshop->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                نشطة
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-white">
                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $reportData['summary']['total_days'] }}</div>
                        <div class="text-sm text-blue-800 mt-1">أيام الورشة</div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $reportData['summary']['total_participants'] }}</div>
                        <div class="text-sm text-green-800 mt-1">المشاركين</div>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4 text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ $reportData['summary']['overall_attendance_rate'] }}%</div>
                        <div class="text-sm text-purple-800 mt-1">معدل الحضور</div>
                    </div>
                    <div class="bg-orange-50 rounded-xl p-4 text-center">
                        <div class="text-3xl font-bold text-orange-600">{{ $reportData['summary']['total_checkins'] }}</div>
                        <div class="text-sm text-orange-800 mt-1">إجمالي تسجيلات الحضور</div>
                    </div>
                </div>

                <!-- Attendance Breakdown -->
                <div class="border-t border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">توزيع الحضور</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center gap-3 p-4 bg-green-50 rounded-lg">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ $reportData['summary']['full_attendance_count'] }}</div>
                                <div class="text-sm text-gray-600">حضور كامل (100%)</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-yellow-50 rounded-lg">
                            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ $reportData['summary']['partial_attendance_count'] }}</div>
                                <div class="text-sm text-gray-600">حضور جزئي</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-red-50 rounded-lg">
                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ $reportData['summary']['no_attendance_count'] }}</div>
                                <div class="text-sm text-gray-600">بدون حضور</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Day-wise Statistics -->
                <div class="border-t border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">الحضور اليومي</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">اليوم</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">التاريخ</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">الحضور</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">معدل الحضور</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">نسبة</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reportData['day_stats'] as $dayStat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $dayStat['label'] ?? 'اليوم ' . ($loop->iteration) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ $dayStat['date']->format('Y-m-d') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-center font-semibold">{{ $dayStat['checkins_count'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ $dayStat['attendance_rate'] }}%</td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $dayStat['attendance_rate'] }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Participant Attendance Matrix -->
                <div class="border-t border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">مصفوفة حضور المشاركين</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider sticky right-0 bg-gray-50 z-10 border-l">
                                        المشارك
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">الحضور</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">معدل الحضور</th>
                                    <!-- Days Columns -->
                                    @foreach($reportData['day_stats'] as $dayStat)
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[100px]">
                                            <div class="flex flex-col">
                                                <span>{{ $dayStat['date']->format('m/d') }}</span>
                                                <span class="text-[10px] text-gray-400 font-normal">{{ $dayStat['label'] ?? 'يوم ' . $loop->iteration }}</span>
                                            </div>
                                        </th>
                                    @endforeach
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider border-r">
                                        أيام الحضور
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reportData['participant_stats'] as $participant)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <!-- Participant Name -->
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 sticky right-0 bg-white z-10 border-l">
                                            <div class="flex flex-col">
                                                <span>{{ $participant['name'] }}</span>
                                                @if($participant['job_title'] || $participant['department'])
                                                    <span class="text-xs text-gray-400 font-normal">
                                                        {{ $participant['job_title'] ?? '' }}@if($participant['job_title'] && $participant['department']) | @endif{{ $participant['department'] ?? '' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        
                                        <!-- Attendance Badge -->
                                        <td class="px-4 py-3 text-center">
                                            @if($participant['is_full_attendance'])
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    حضور كامل
                                                </span>
                                            @elseif($participant['attendance_rate'] > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    حضور جزئي
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    غياب
                                                </span>
                                            @endif
                                        </td>
                                        
                                        <!-- Attendance Rate -->
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                                    <div class="h-2 rounded-full {{ $participant['attendance_rate'] >= 75 ? 'bg-green-500' : ($participant['attendance_rate'] >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $participant['attendance_rate'] }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-600">{{ $participant['attendance_rate'] }}%</span>
                                            </div>
                                        </td>

                                        <!-- Check-in Status for each Day -->
                                        @php $presentDays = $participant['days']; @endphp
                                        @foreach($reportData['day_stats'] as $dayStat)
                                            <td class="px-4 py-3 text-center">
                                                @if(in_array($dayStat['id'], $presentDays))
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600" title="حاضر">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-400" title="غائب">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </span>
                                                @endif
                                            </td>
                                        @endforeach

                                        <!-- Total Days Attended -->
                                        <td class="px-4 py-3 text-center text-sm font-bold text-gray-700 border-r bg-gray-50">
                                            {{ $participant['days_attended'] }} / {{ $reportData['summary']['total_days'] }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($reportData['day_stats']) + 4 }}" class="px-6 py-8 text-center text-sm text-gray-500">
                                            لا يوجد مشتركين مسجلين في هذه الورشة
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @elseif(!$selectedWorkshop)
            <!-- No workshop selected - show all workshops list -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">ورش العمل المتاحة</h2>
                    <p class="text-sm text-gray-500 mt-1">اختر ورشة عمل لعرض تقرير الحضور التفصيلي</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($workshops as $workshop)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $workshop->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $workshop->days->count() }} أيام | {{ $workshop->attendances->count() }} مشارك
                                        @if($workshop->location)
                                            | {{ $workshop->location }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 mt-2">
                                        {{ $workshop->starts_at?->format('Y-m-d') }}
                                        @if($workshop->ends_at && $workshop->ends_at != $workshop->starts_at)
                                            إلى {{ $workshop->ends_at->format('Y-m-d') }}
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('workshop.attendance_report', ['workshop_id' => $workshop->id]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                    عرض التقرير
                                    <svg class="w-5 h-5 ml-2 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">لا توجد ورش عمل</h3>
                            <p class="text-gray-500 mt-1">لم يتم إنشاء أي ورش عمل حتى الآن.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">الورشة غير موجودة</h3>
                <p class="text-gray-500 mt-1">الورشة المطلوبة غير موجودة أو تم حذفها.</p>
            </div>
        @endif
    </div>
</x-app-layout>
