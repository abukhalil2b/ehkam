<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقويم السنوي | {{ $year }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @vite('resources/js/app.js')
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        .islamic-pattern {
            background-color: #064e3b;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 30L15 45L0 30L15 15L30 30zm30 0L45 45L30 30L45 15L60 30zM15 15L0 0h30L15 15zm30 0L30 0h30L45 15zM15 45L0 60h30L15 45zm30 0L30 60h30L45 45z' fill='%23065f46' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        [x-cloak] {
            display: none !important;
        }

        @media print {

            /* 1. Force Landscape orientation */
            @page {
                size: landscape;
                margin: 1cm;
            }

            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print\:hidden {
                display: none !important;
            }

            /* 2. Force Year View visibility */
            [x-show="viewType === 'year'"] {
                display: block !important;
            }

            /* 3. The Grid Fix */
            /* We target the specific div containing the months to ensure it uses the full width */
            .grid.grid-cols-1.md:grid-cols-2.lg:grid-cols-3.xl:grid-cols-4 {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
                /* Force 4 columns */
                gap: 12px !important;
                width: 100% !important;
            }

            /* 4. Override Tailwind Container widths */
            .container,
            .max-w-4xl,
            .mx-auto {
                max-width: none !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* 5. Visual Cleanup for Print */
            .bg-white {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }

            /* Ensure headers inside month boxes stay visible */
            .bg-emerald-50 {
                background-color: #f0fdf4 !important;
                border-bottom: 1px solid #dcfce7 !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen" x-data="quranCalendar(@js($events))">

    <header class="islamic-pattern text-white py-10 shadow-xl print:hidden">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-right">
                    <h1 class="text-3xl font-bold mb-2">التقويم السنوي للأنشطة والبرامج</h1>
                    <p class="text-xl opacity-90">لعام {{ $year }} م</p>
                </div>

                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('calendar.create', ['year' => $year]) }}"
                        class="bg-yellow-400 text-emerald-900 px-6 py-3 rounded-xl font-bold hover:bg-yellow-300 transition shadow-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        إضافة حدث جديد
                    </a>
                    <button @click="window.print()"
                        class="bg-white/20 backdrop-blur-md border border-white/30 text-white px-6 py-3 rounded-xl font-bold hover:bg-white/30 transition">
                        طباعة التقويم
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 -mt-8">

        <div
            class="bg-white rounded-xl shadow-lg p-4 mb-6 flex flex-wrap items-center justify-between gap-4 print:hidden">
            <div class="flex items-center gap-4">
                <button @click="changeYear(-1)" class="p-2 hover:bg-gray-100 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <span class="text-2xl font-bold text-emerald-900" x-text="year"></span>
                <button @click="changeYear(1)" class="p-2 hover:bg-gray-100 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <div class="flex bg-gray-100 p-1 rounded-lg">
                <button @click="viewType='year'"
                    :class="viewType === 'year' ? 'bg-white shadow text-emerald-700' : 'text-gray-500'"
                    class="px-4 py-2 rounded-md font-bold transition">سنوي</button>
                <button @click="viewType='month'"
                    :class="viewType === 'month' ? 'bg-white shadow text-emerald-700' : 'text-gray-500'"
                    class="px-4 py-2 rounded-md font-bold transition">شهري</button>
                <button @click="viewType='timeline'"
                    :class="viewType === 'timeline' ? 'bg-white shadow text-emerald-700' : 'text-gray-500'"
                    class="px-4 py-2 rounded-md font-bold transition">زمني</button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div class="lg:col-span-3 bg-white p-4 rounded-xl shadow-sm flex flex-wrap gap-4 print:hidden">
                <select x-model="filters.status"
                    class="bg-gray-50 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500">
                    <option value="">جميع الحالات</option>
                    <option value="upcoming">قادم</option>
                    <option value="active">جاري الآن</option>
                    <option value="completed">مكتمل</option>
                </select>
                <select x-model="filters.type" class="bg-gray-50 border-none rounded-lg px-4 py-2">
                    <option value="">جميع الأنواع</option>
                    <template x-for="t in types" :key="t">
                        <option :value="t" x-text="t"></option>
                    </template>
                </select>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm flex items-center justify-around">
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-600"></span> <span
                        class="text-xs">جاري</span></div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-gray-500"></span> <span
                        class="text-xs">قادم</span></div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-600"></span> <span
                        class="text-xs">مكتمل</span></div>
            </div>
        </div>

        <div x-show="viewType === 'timeline'" x-cloak class="space-y-4 mb-10">
            <template x-for="event in filteredEvents" :key="event.id">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border-r-4 transition hover:shadow-md"
                    :style="`border-color: ${event.status_color}`">
                    <div class="p-5 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-xs font-bold px-2 py-0.5 rounded uppercase tracking-wider text-white"
                                    :style="`background: ${event.status_color}`"
                                    x-text="event.status === 'active' ? 'جاري' : (event.status === 'upcoming' ? 'قادم' : 'مكتمل')"></span>
                                <span class="text-gray-500 text-sm" x-text="event.type"></span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800" x-text="event.title"></h3>
                            <p class="text-sm text-gray-600 mt-1" x-text="event.program"></p>
                        </div>

                        <div class="text-right flex flex-col items-end gap-2">
                            <div class="text-sm font-semibold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-lg">
                                <span x-text="formatDate(event.startDate)"></span>
                                <span class="mx-1">←</span>
                                <span x-text="formatDate(event.endDate)"></span>
                            </div>
                            <div class="flex gap-2 print:hidden">
                                <button @click="openEdit(event)"
                                    class="text-blue-600 hover:bg-blue-50 px-4 py-1 rounded-lg text-sm font-bold border border-blue-200 transition">تعديل</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="viewType === 'year'" x-cloak class="container mx-auto px-0 mb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <template x-for="(monthName, mIndex) in monthNames" :key="mIndex">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-emerald-50 p-3 border-b border-emerald-100 flex justify-between items-center">
                            <h3 class="font-bold text-emerald-900" x-text="monthName"></h3>
                            <span class="text-xs text-emerald-600 font-medium"
                                x-text="getMonthEvents(mIndex).length + ' أنشطة'"></span>
                        </div>

                        <div class="p-3">
                            <div class="grid grid-cols-7 gap-1 mb-2 text-center">
                                <template x-for="day in ['ح', 'ن', 'ث', 'ر', 'خ', 'ج', 'س']">
                                    <span class="text-[10px] font-bold text-gray-400" x-text="day"></span>
                                </template>
                            </div>

                            <div class="grid grid-cols-7 gap-1">
                                <template x-for="blank in getBlankDays(mIndex)">
                                    <div class="h-8"></div>
                                </template>

                                <template x-for="dayData in getMonthDays(mIndex)" :key="dayData.day">
                                    <div class="group relative h-8 flex items-center justify-center rounded-lg text-sm transition-all"
                                        :class="dayData.hasEvent ? 'font-bold' : 'text-gray-500 hover:bg-gray-50'">

                                        <template x-if="dayData.hasEvent">
                                            <span class="absolute bottom-1 w-1 h-1 rounded-full bg-emerald-500"></span>
                                        </template>

                                        <span x-text="dayData.day"
                                            :class="dayData.hasEvent ? 'text-emerald-700' : ''"></span>

                                        <template x-if="dayData.hasEvent">
                                            <div
                                                class="absolute z-30 bottom-full mb-2 hidden group-hover:block w-48 bg-gray-900 text-white text-xs rounded-lg p-2 shadow-xl pointer-events-none">
                                                <template x-for="e in dayData.events" :key="e.id">
                                                    <div class="mb-1 border-b border-gray-700 last:border-0 pb-1">
                                                        <div x-text="e.title"></div>
                                                        <div class="text-[10px] opacity-70" x-text="e.type"></div>
                                                    </div>
                                                </template>
                                                <div
                                                    class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-gray-900">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="p-2 bg-gray-50/50 border-t border-gray-100 space-y-1">
                            <template x-for="event in getMonthEvents(mIndex).slice(0, 2)" :key="event.id">
                                <div class="text-[10px] flex items-center gap-1 px-2 py-0.5 rounded truncate"
                                    :style="`background: ${event.bg_color}20; color: ${event.bg_color}`">
                                    <span class="w-1.5 h-1.5 rounded-full"
                                        :style="`background: ${event.bg_color}`"></span>
                                    <span x-text="event.title" class="truncate"></span>
                                </div>
                            </template>
                            <template x-if="getMonthEvents(mIndex).length > 2">
                                <div class="text-[9px] text-center text-gray-400 font-bold"
                                    x-text="'+ ' + (getMonthEvents(mIndex).length - 2) + ' المزيد'"></div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div x-show="viewType === 'month'" x-cloak class="container mx-auto px-0 mb-10">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
                <div class="bg-emerald-800 p-6 text-white flex justify-between items-center">
                    <button @click="prevMonth()" class="p-2 hover:bg-emerald-700 rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <h2 class="text-2xl font-bold" x-text="monthNames[currentMonth] + ' ' + year"></h2>

                    <button @click="nextMonth()" class="p-2 hover:bg-emerald-700 rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <div
                    class="grid grid-cols-7 bg-emerald-50 border-b border-emerald-100 text-center py-3 font-bold text-emerald-900">
                    <template x-for="day in ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت']">
                        <div x-text="day"></div>
                    </template>
                </div>

                <div class="grid grid-cols-7 border-r border-b border-gray-100">
                    <template x-for="blank in getBlankDays(currentMonth)">
                        <div class="h-32 bg-gray-50 border-l border-t border-gray-100"></div>
                    </template>

                    <template x-for="dayData in getMonthDays(currentMonth)" :key="dayData.day">
                        <div
                            class="h-32 border-l border-t border-gray-100 p-2 hover:bg-emerald-50/30 transition-colors overflow-y-auto">
                            <div class="font-bold text-gray-400 mb-1" x-text="dayData.day"></div>

                            <div class="space-y-1">
                                <template x-for="e in dayData.events" :key="e.id">
                                    <div @click="openEdit(e)"
                                        class="text-[10px] p-1 rounded border border-white/20 text-white cursor-pointer hover:brightness-110 truncate"
                                        :style="`background-color: ${e.bg_color}`" :title="e.title">
                                        <span x-text="e.title"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </main>

    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showEditModal" @click="closeEdit" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:leave="ease-in duration-200"
            class="fixed inset-0 bg-emerald-900/60 backdrop-blur-sm"></div>

        <div x-show="showEditModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative z-10">
            <div class="bg-emerald-800 p-6 text-white">
                <h3 class="text-xl font-bold text-center">تعديل بيانات النشاط</h3>
            </div>

            <form :action="`/calendar/${originalEvent?.id}`" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">عنوان النشاط</label>
                        <input type="text" name="title" x-model="editingEvent.title"
                            class="w-full border-2 border-gray-300 rounded-xl px-4 py-2 focus:border-emerald-500 focus:ring-0 transition"
                            required>
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نوع النشاط</label>
                        <select name="type" x-model="editingEvent.type"
                            class="w-full border-2 border-gray-300 rounded-xl px-4 py-2 focus:border-emerald-500 focus:ring-0 bg-white">
                            <option value="meeting">اجتماع</option>
                            <option value="conference">مؤتمر</option>
                            <option value="competition">مسابقة</option>
                            <option value="program">برنامج</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">تاريخ البدء</label>
                            <input type="date" name="start_date" x-model="editingEvent.startDate"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-2 focus:border-emerald-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">تاريخ الانتهاء</label>
                            <input type="date" name="end_date" x-model="editingEvent.endDate"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-2 focus:border-emerald-500 focus:ring-0">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">البرنامج</label>
                            <input type="text" name="program" x-model="editingEvent.program"
                                class="w-full border-2 border-gray-300 rounded-xl px-4 py-2 focus:border-emerald-500 focus:ring-0">
                        </div>
                        <div
                            class="flex items-center gap-3 bg-gray-50 border-2 border-gray-300 rounded-xl px-3 py-1.5">
                            <label class="text-sm font-bold text-gray-700">اللون:</label>
                            <input type="color" name="bg_color" x-model="editingEvent.bg_color"
                                class="w-full h-8 cursor-pointer border-0 bg-transparent">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="submit"
                        class="flex-1 bg-emerald-700 text-white font-bold py-3 rounded-xl hover:bg-emerald-800 transition shadow-md">
                        حفظ التغييرات
                    </button>
                    <button type="button" @click="closeEdit"
                        class="flex-1 bg-gray-100 text-gray-600 font-bold py-3 rounded-xl hover:bg-gray-200 transition">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function quranCalendar(eventsFromServer) {
            return {
                year: {{ $year }},
                viewType: 'year',
                showEditModal: false,
                originalEvent: null, // Keep reference for ID and ID only
                editingEvent: {}, // Mutable object for the form
                currentMonth: new Date().getMonth(),
                filters: {
                    type: '',
                    status: ''
                },
                events: eventsFromServer,

                get filteredEvents() {
                    return this.events.filter(e =>
                        (!this.filters.type || e.type === this.filters.type) &&
                        (!this.filters.status || e.status === this.filters.status)
                    );
                },

                get types() {
                    return [...new Set(this.events.map(e => e.type))];
                },

                openEdit(event) {
                    this.originalEvent = event;
                    // Clone the data into editingEvent to prevent "live" changes in the background
                    this.editingEvent = {
                        ...event
                    };
                    this.showEditModal = true;
                },

                closeEdit() {
                    this.showEditModal = false;
                    setTimeout(() => {
                        this.editingEvent = {};
                        this.originalEvent = null;
                    }, 300);
                },

                changeYear(delta) {
                    const newYear = parseInt(this.year) + delta;
                    window.location.href = `{{ route('calendar.index') }}?year=${newYear}`;
                },

                formatDate(dateStr) {
                    return new Date(dateStr).toLocaleDateString('ar-EG', {
                        day: 'numeric',
                        month: 'short'
                    });
                },
                // Add these inside the return object of quranCalendar(eventsFromServer)
                monthNames: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر',
                    'نوفمبر', 'ديسمبر'
                ],

                getBlankDays(monthIndex) {
                    // Calculates the day of the week the month starts on (0=Sunday)
                    return new Array(new Date(this.year, monthIndex, 1).getDay()).fill(null);
                },

                getMonthDays(monthIndex) {
                    const days = [];
                    const daysInMonth = new Date(this.year, monthIndex + 1, 0).getDate();

                    for (let d = 1; d <= daysInMonth; d++) {
                        const currentDate = new Date(this.year, monthIndex, d);
                        // Logic to find events that span over this specific day
                        const dayEvents = this.filteredEvents.filter(e => {
                            const start = new Date(e.startDate);
                            const end = new Date(e.endDate);
                            const current = new Date(this.year, monthIndex, d);
                            return current >= start && current <= end;
                        });

                        days.push({
                            day: d,
                            hasEvent: dayEvents.length > 0,
                            events: dayEvents
                        });
                    }
                    return days;
                },

                getMonthEvents(monthIndex) {
                    // Unique events occurring in this month
                    const events = [];
                    const seenIds = new Set();

                    this.getMonthDays(monthIndex).forEach(day => {
                        day.events.forEach(e => {
                            if (!seenIds.has(e.id)) {
                                seenIds.add(e.id);
                                events.push(e);
                            }
                        });
                    });
                    return events;
                }, // Navigation for Month View
                nextMonth() {
                    if (this.currentMonth === 11) {
                        this.currentMonth = 0;
                        this.changeYear(1);
                    } else {
                        this.currentMonth++;
                    }
                },

                prevMonth() {
                    if (this.currentMonth === 0) {
                        this.currentMonth = 11;
                        this.changeYear(-1);
                    } else {
                        this.currentMonth--;
                    }
                },

                // Update changeYear to handle URL redirect
                changeYear(delta) {
                    const newYear = parseInt(this.year) + delta;
                    // Redirect to reload events for the new year
                    window.location.href = `{{ route('calendar.index') }}?year=${newYear}`;
                }
            }
        }
    </script>

    <div class="flex py-6">
        <a href="/dashboard" class="text-orange-600 px-6 py-3 rounded-xl font-bold">خروج</a>
    </div>
</body>

</html>
