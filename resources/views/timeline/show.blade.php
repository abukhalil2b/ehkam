<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4" dir="rtl">
        <nav class="mb-6">
            <a href="{{ route('calendar.index', ['year' => $event->year]) }}" class="text-emerald-700 hover:text-emerald-900 flex items-center gap-2 font-bold">
                <span>← العودة إلى التقويم السنوي</span>
            </a>
        </nav>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <div class="h-4" :style="`background-color: {{ $event->bg_color }}`"></div>
            
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold mb-2 inline-block
                            {{ $status == 'active' ? 'bg-emerald-100 text-emerald-700' : ($status == 'upcoming' ? 'bg-gray-100 text-gray-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ $status == 'active' ? 'جاري الآن' : ($status == 'upcoming' ? 'قادم' : 'مكتمل') }}
                        </span>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
                        <p class="text-gray-500 mt-1">تصنيف: {{ $event->type }} | البرنامج: {{ $event->program ?? 'عام' }}</p>
                    </div>

                    
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">تاريخ البداية</p>
                        <p class="text-xl font-bold text-emerald-900">{{ $event->start_date->format('Y/m/d') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">تاريخ النهاية</p>
                        <p class="text-xl font-bold text-emerald-900">{{ $event->end_date->format('Y/m/d') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">المدة الإجمالية</p>
                        <p class="text-xl font-bold text-emerald-900">{{ $event->duration }} يوم</p>
                    </div>
                </div>

                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        ملاحظات وتفاصيل
                    </h3>
                    <div class="bg-emerald-50/50 p-6 rounded-2xl text-gray-700 leading-relaxed border border-emerald-100 italic">
                        {{ $event->notes ?? 'لا توجد ملاحظات إضافية لهذا النشاط.' }}
                    </div>
                </div>

                @if($status == 'upcoming')
                    <div class="text-center p-6 bg-yellow-50 rounded-2xl border border-yellow-100">
                        <p class="text-yellow-800 font-bold italic">متبقي على بداية هذا النشاط: {{ $daysRemaining }} يوم</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>