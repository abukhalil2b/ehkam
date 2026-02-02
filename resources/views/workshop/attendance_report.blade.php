<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">تقرير حضور ورش العمل</h1>
                <p class="text-gray-500 mt-1">سجل الحضور التفصيلي لكل يوم من أيام الورش</p>
            </div>
            <a href="{{ route('workshop.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                <svg class="w-5 h-5 ml-2 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                عودة للقائمة
            </a>
        </div>

        @if($workshops->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">لا توجد ورش عمل</h3>
                <p class="text-gray-500 mt-1">لم يتم إنشاء أي ورش عمل حتى الآن.</p>
            </div>
        @else
            <div class="space-y-12">
                @foreach($workshops as $workshop)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Workshop Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $workshop->title }}</h2>
                                <p class="text-sm text-gray-500">
                                    {{ $workshop->days->count() }} أيام | {{ $workshop->attendances->count() }} مشترك
                                </p>
                            </div>
                            <div class="text-sm text-gray-400">
                                {{ $workshop->starts_at?->format('Y-m-d') }}
                            </div>
                        </div>

                        <!-- Statistics / Summary -->
                        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 bg-white">
                            <!-- You can add summary stats here if needed -->
                        </div>

                        <!-- Matrix Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider sticky right-0 bg-gray-50 z-10 w-64 border-l">
                                            المشترك
                                        </th>
                                        <!-- Days Columns -->
                                        @foreach($workshop->days as $day)
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[120px]">
                                                <div class="flex flex-col">
                                                    <span>{{ $day->day_date->format('Y-m-d') }}</span>
                                                    <span class="text-[10px] text-gray-400 font-normal">{{ $day->label ?? 'يوم ' . $loop->iteration }}</span>
                                                </div>
                                            </th>
                                        @endforeach
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider border-r">
                                            المجموع
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($workshop->attendances as $attendance)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <!-- Participant Name -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky right-0 bg-white z-10 border-l group-hover:bg-gray-50">
                                                <div class="flex flex-col">
                                                    <span>{{ $attendance->attendee_name }}</span>
                                                    <span class="text-xs text-gray-400 font-normal">{{ $attendance->job_title ?? '-' }}</span>
                                                </div>
                                            </td>

                                            <!-- Check-in Status for each Day -->
                                            @php $presentCount = 0; @endphp
                                            @foreach($workshop->days as $day)
                                                @php
                                                    // Find checkin for this day
                                                    $checkin = $attendance->checkins->where('workshop_day_id', $day->id)->first();
                                                    $isPresent = $checkin ? true : false;
                                                    if ($isPresent) $presentCount++;
                                                @endphp
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if($isPresent)
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600" title="حاضر: {{ $checkin->checkin_time->format('H:i') }}">
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

                                            <!-- Total -->
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700 border-r bg-gray-50">
                                                {{ $presentCount }} / {{ $workshop->days->count() }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $workshop->days->count() + 2 }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                                لا يوجد مشتركين مسجلين في هذه الورشة
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
