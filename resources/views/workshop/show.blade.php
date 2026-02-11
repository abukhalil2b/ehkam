<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $workshop->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-xl p-8 border border-gray-100">

                <h3 class="text-2xl font-bold text-blue-700 mb-6 border-b pb-2">
                    البيانات الأساسية للورشة
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-gray-700">

                    {{-- 1. Date Range --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> الفترة:</strong>
                        من {{ $workshop->starts_at?->format('Y-m-d') }} إلى {{ $workshop->ends_at?->format('Y-m-d') }}
                    </p>

                    {{-- 2. Place --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> المكان:</strong>
                        {{ $workshop->location ?? 'غير محدد' }}
                    </p>

                    {{-- 3. Written By --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> كتب بواسطة:</strong>
                        <span class="text-blue-600">{{ $workshop->createdBy->name ?? '—' }}</span>
                    </p>

                    {{-- 4. Status --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> الحالة:</strong>
                        <span class="{{ $workshop->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $workshop->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </p>

                </div>

                {{-- WORKSHOP TOOLS SECTION --}}
                <div class="mt-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 ml-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        أدوات الورشة التفاعلية
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                        {{-- 1. SWOT Analysis --}}
                        <a href="{{ url('/swot') }}" target="_blank"
                            class="group block bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                            <div class="p-5">
                                <div
                                    class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-4 group-hover:bg-purple-600 transition-colors duration-300">
                                    <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <h4
                                    class="font-bold text-gray-800 text-lg mb-1 group-hover:text-purple-700 transition-colors">
                                    تحليل SWOT</h4>
                                <p class="text-sm text-gray-500 line-clamp-2">تحليل نقاط القوة والضعف والفرص والتهديدات.
                                </p>
                            </div>
                            <div
                                class="bg-purple-50 px-5 py-2 text-xs font-semibold text-purple-700 flex justify-between items-center">
                                <span>ابدأ التحليل</span>
                                <svg class="w-4 h-4 transform rotate-180 group-hover:-translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </a>

                        {{-- 2. PESTLE Analysis --}}
                        <a href="{{ url('/pestle/index') }}" target="_blank"
                            class="group block bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                            <div class="p-5">
                                <div
                                    class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center mb-4 group-hover:bg-teal-600 transition-colors duration-300">
                                    <svg class="w-6 h-6 text-teal-600 group-hover:text-white transition-colors duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4
                                    class="font-bold text-gray-800 text-lg mb-1 group-hover:text-teal-700 transition-colors">
                                    تحليل PESTLE</h4>
                                <p class="text-sm text-gray-500 line-clamp-2">تحليل العوامل السياسية والاقتصادية
                                    والاجتماعية.</p>
                            </div>
                            <div
                                class="bg-teal-50 px-5 py-2 text-xs font-semibold text-teal-700 flex justify-between items-center">
                                <span>استعراض العوامل</span>
                                <svg class="w-4 h-4 transform rotate-180 group-hover:-translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </a>

                        {{-- 3. Questionnaires --}}
                        <a href="{{ url('/questionnaire/index') }}" target="_blank"
                            class="group block bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                            <div class="p-5">
                                <div
                                    class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center mb-4 group-hover:bg-amber-500 transition-colors duration-300">
                                    <svg class="w-6 h-6 text-amber-600 group-hover:text-white transition-colors duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <h4
                                    class="font-bold text-gray-800 text-lg mb-1 group-hover:text-amber-700 transition-colors">
                                    الاستبيانات</h4>
                                <p class="text-sm text-gray-500 line-clamp-2">قياس الرضا وجمع آراء المشاركين في الورشة.
                                </p>
                            </div>
                            <div
                                class="bg-amber-50 px-5 py-2 text-xs font-semibold text-amber-700 flex justify-between items-center">
                                <span>إنشاء استبيان</span>
                                <svg class="w-4 h-4 transform rotate-180 group-hover:-translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </a>

                        {{-- 4. Competitions --}}
                        <a href="{{ url('/admin/competitions') }}" target="_blank"
                            class="group block bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                            <div class="p-5">
                                <div
                                    class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center mb-4 group-hover:bg-rose-500 transition-colors duration-300">
                                    <svg class="w-6 h-6 text-rose-600 group-hover:text-white transition-colors duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </div>
                                <h4
                                    class="font-bold text-gray-800 text-lg mb-1 group-hover:text-rose-700 transition-colors">
                                    المسابقات</h4>
                                <p class="text-sm text-gray-500 line-clamp-2">زيادة التفاعل والحماس بين المشاركين.</p>
                            </div>
                            <div
                                class="bg-rose-50 px-5 py-2 text-xs font-semibold text-rose-700 flex justify-between items-center">
                                <span>إدارة المسابقات</span>
                                <svg class="w-4 h-4 transform rotate-180 group-hover:-translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </a>

                    </div>
                </div>

                {{-- Days List with Attendance Links --}}
                <div class="mt-8 mb-8 bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-gray-900 text-lg flex items-center">
                            <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            جدول أيام الورشة وروابط الحضور
                        </h4>
                        <a href="{{ route('workshop.edit', $workshop->id) }}#days-section"
                            class="text-sm bg-white border border-blue-300 text-blue-700 hover:bg-blue-50 px-3 py-1.5 rounded-lg shadow-sm font-medium transition flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            إضافة يوم / تعديل الجدول
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($workshop->days as $day)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                {{-- Day Header --}}
                                <div
                                    class="flex items-center justify-between p-4 border-b border-gray-100 {{ $day->is_active ? 'bg-green-50' : 'bg-gray-50' }}">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-2 h-10 rounded {{ $day->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                        </div>
                                        <div>
                                            <span
                                                class="font-bold text-gray-800 text-lg block">{{ $day->label ?? 'يوم ' . $loop->iteration }}</span>
                                            <span class="text-gray-500 text-sm">{{ $day->day_date->format('Y-m-d') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{-- Toggle Status --}}
                                        <form action="{{ route('workshop.day.toggle', $day->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1.5 rounded-lg text-sm font-medium transition {{ $day->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                                {{ $day->is_active ? 'مفعّل' : 'معطّل' }}
                                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="{{ $day->is_active ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' }}" />
                                                </svg>
                                            </button>
                                        </form>
                                        {{-- Checkins Count --}}
                                        <span class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium">
                                            {{ $day->checkins->count() }} حاضر
                                        </span>
                                    </div>
                                </div>

                                {{-- Attendance Link Section --}}
                                <div class="p-4">
                                    <div class="flex flex-col md:flex-row gap-4">
                                        {{-- QR Code --}}
                                        <div class="flex-shrink-0 text-center">
                                            <div
                                                class="inline-block bg-white p-2 rounded-lg border border-gray-200 shadow-sm">
                                                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($day->attendance_url) !!}
                                            </div>
                                            <p class="text-sm font-bold text-gray-800 mt-2">تسجيل الحضور</p>
                                            <p class="text-xs text-gray-500">امسح الكود</p>
                                        </div>

                                        {{-- Link Info --}}
                                        <div class="flex-1 space-y-3">
                                            <div>
                                                <label class="text-xs font-medium text-gray-500 block mb-1">رابط تسجيل
                                                    الحضور:</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="text" readonly value="{{ $day->attendance_url }}"
                                                        class="flex-1 text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-gray-700 font-mono"
                                                        id="link-{{ $day->id }}">
                                                    <button onclick="copyToClipboard('link-{{ $day->id }}')"
                                                        class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2 text-sm">
                                                <span class="text-gray-500">الرمز:</span>
                                                <code
                                                    class="bg-gray-100 px-2 py-1 rounded text-xs font-mono text-gray-600">{{ Str::limit($day->attendance_hash, 20) }}...</code>
                                                {{-- Regenerate Hash --}}
                                                <form action="{{ route('workshop.day.regenerate', $day->id) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('هل أنت متأكد؟ سيتغير الرابط بشكل دائم.');">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-amber-600 hover:text-amber-700 text-xs flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                        توليد رابط جديد
                                                    </button>
                                                </form>
                                            </div>

                                            @if(!$day->is_active)
                                                <div
                                                    class="flex items-center gap-2 text-amber-600 bg-amber-50 rounded-lg p-2 text-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    <span>الرابط معطل حالياً - فعّله للسماح بالتسجيل</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div
                                class="w-full text-center py-8 text-gray-500 bg-white rounded-lg border border-dashed border-gray-300">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                لا توجد أيام محددة لهذه الورشة بعد.
                                <br>
                                <a href="{{ route('workshop.edit', $workshop->id) }}"
                                    class="text-blue-600 font-bold hover:underline mt-2 inline-block">
                                    اضغط هنا لإضافة أيام
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Copy to Clipboard Script --}}
                <script>
                    function copyToClipboard(elementId) {
                        var copyText = document.getElementById(elementId);
                        copyText.select();
                        copyText.setSelectionRange(0, 99999);
                        navigator.clipboard.writeText(copyText.value);
                        alert('تم نسخ الرابط!');
                    }
                </script>

                <hr class="my-8 border-gray-200">

                {{-- In your show.blade.php --}}
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">قائمة الحضور</h3>

                        {{-- Filter Buttons --}}
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('workshop.show', $workshop->id) }}"
                                class="px-3 py-1.5 rounded-lg text-sm font-medium transition {{ !request('day_id') ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                الكل
                            </a>
                            @foreach($workshop->days as $day)
                                <a href="{{ route('workshop.show', ['workshop' => $workshop->id, 'day_id' => $day->id]) }}"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition {{ request('day_id') == $day->id ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                    {{ $day->label ?? $day->day_date->format('Y-m-d') }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">#</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">اسم الحاضر</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">المسمى الوظيفي
                                    </th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">القسم</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">أيام الحضور
                                    </th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">حذف</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($attendances as $attendance)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">
                                            <div class="font-semibold">{{ $attendance->attendee_name }}</div>
                                            <div class="text-[10px] text-gray-400 mt-0.5" dir="ltr">
                                                {{ $attendance->created_at->format('Y-m-d H:i A') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $attendance->job_title ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $attendance->department ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($attendance->checkins as $checkin)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800"
                                                        title="{{ $checkin->workshopDay->label }}">
                                                        {{ $checkin->workshopDay->day_date->format('Y-m-d') }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <form action="{{ route('workshop.attendance.destroy', $attendance) }}"
                                                method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    onclick="confirmDeleteAttendance('{{ $attendance->attendee_name }}', this.form)"
                                                    class="text-red-500 hover:text-red-700 p-1 hover:bg-red-50 rounded transition"
                                                    title="حذف الحضور">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                            لا يوجد حضور مسجل
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8 pt-4 border-t flex justify-end">
                    <a href="{{ route('workshop.edit', $workshop->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        تعديل الورشة
                    </a>
                </div>

            </div>

        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmDeleteAttendance(name, form) {
                Swal.fire({
                    title: 'حذف الحضور؟',
                    text: `سيتم حذف سجل الحضور للمشارك "${name}" بشكل نهائي.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            }
        </script>
    @endpush
</x-app-layout>