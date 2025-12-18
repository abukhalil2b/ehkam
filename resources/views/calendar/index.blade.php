<x-app-layout>
    <div dir="rtl" class="bg-gray-600 min-h-screen" x-data="quranCalendar(@js($events))">

        <!-- HEADER -->
        <header class="gradient-bg islamic-pattern text-white py-8 mb-6 print:hidden">
            <div class="container mx-auto px-4 text-center">
                <p class="text-xl opacity-90">التقويم السنوي للأنشطة {{ $year }}</p>

                <div class="mt-4 flex flex-wrap justify-center gap-3">
                    <span class="bg-white bg-opacity-20 px-4 py-2 rounded-lg">
                        إجمالي الأنشطة: <span x-text="filteredEvents.length"></span>
                    </span>
                </div>

                <div class="flex justify-end mb-4 print:hidden gap-2">
                    <button @click="window.print()" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">
                        طباعة
                    </button>
                    <button @click="exportCSV()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        تصدير CSV
                    </button>
                </div>
            </div>
        </header>

        <!-- YEAR SWITCHER -->
        <div class="flex justify-center items-center gap-4 mb-6">
            <button @click="changeYear(-1)" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">‹ السابق</button>
            <span class="font-semibold text-lg" x-text="year"></span>
            <button @click="changeYear(1)" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">التالي ›</button>
        </div>

        <!-- FILTERS -->
        <div class="container mx-auto px-4 mb-6 print:hidden">
            <div class="bg-white rounded-lg shadow p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
                <select x-model="filters.type" @change="updateURL()" class="border rounded px-3 py-2">
                    <option value="">كل الأنواع</option>
                    <template x-for="t in types" :key="t">
                        <option :value="t" x-text="t"></option>
                    </template>
                </select>

                <select x-model="filters.program" @change="updateURL()" class="border rounded px-3 py-2">
                    <option value="">كل البرامج</option>
                    <template x-for="p in programs" :key="p">
                        <option :value="p" x-text="p"></option>
                    </template>
                </select>

                <select x-model="filters.status" @change="updateURL()" class="border rounded px-3 py-2">
                    <option value="">كل الحالات</option>
                    <option value="upcoming">قادم</option>
                    <option value="active">جاري</option>
                    <option value="completed">مكتمل</option>
                </select>

                <select x-model="currentMonth" @change="viewType='month'" class="border rounded px-3 py-2">
                    <option value="">اختر شهراً</option>
                    <template x-for="(m,i) in monthNames" :key="i">
                        <option :value="i" x-text="m"></option>
                    </template>
                </select>
            </div>
        </div>

        <!-- VIEW SWITCH -->
        <div class="container mx-auto px-4 mb-6 print:hidden">
            <div class="bg-white rounded-lg shadow p-4 flex gap-2 justify-center">
                <button @click="viewType='year'" :class="btnClass('year')">عرض سنوي</button>
                <button @click="viewType='month'" :class="btnClass('month')">عرض شهري</button>
                <button @click="viewType='timeline'" :class="btnClass('timeline')">عرض زمني</button>
            </div>
        </div>

        <!-- LEGEND -->
        <div class="container mx-auto px-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 flex flex-wrap gap-4 justify-center">
                <span class="flex items-center gap-2"><span class="w-4 h-4 bg-green-600 rounded"></span> جاري</span>
                <span class="flex items-center gap-2"><span class="w-4 h-4 bg-gray-600 rounded"></span> قادم</span>
                <span class="flex items-center gap-2"><span class="w-4 h-4 bg-blue-600 rounded"></span> مكتمل</span>
            </div>
        </div>

        <!-- YEAR VIEW -->
        <div x-show="viewType==='year'" class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <template x-for="(month,mIndex) in monthNames" :key="mIndex">
                    <div class="bg-white rounded-lg shadow p-4 cursor-pointer"
                        @click="currentMonth=mIndex; viewType='month'">
                        <h3 class="text-center font-bold mb-3" x-text="month"></h3>
                        <div class="grid grid-cols-7 gap-1 text-sm mb-3">
                            <template x-for="day in getMonthDays(mIndex)" :key="day.day">
                                <div class="p-2 text-center rounded"
                                    :class="day.hasEvent ? 'bg-blue-100' : 'bg-gray-50'">
                                    <span x-text="day.day"></span>
                                    <!-- Tooltip -->
                                    <template x-if="day.events.length">
                                        <div
                                            class="absolute z-10 left-1/2 transform -translate-x-1/2 mt-1 w-max bg-black text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                            <template x-for="e in day.events" :key="e.id">
                                                <div x-text="e.title"></div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div class="space-y-1">
                            <template x-for="event in getMonthEvents(mIndex)" :key="event.id">
                                <div class="text-xs text-white px-2 py-1 rounded"
                                    :style="`background:${event.bg_color || '#2563eb'}`" x-text="event.title"></div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- MONTH VIEW -->
        <div x-show="viewType==='month'" class="container mx-auto px-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4" x-text="monthNames[currentMonth]"></h2>
                <div class="grid grid-cols-7 gap-2">
                    <template x-for="day in getCurrentMonthDays()" :key="day.day">
                        <div class="border rounded p-2 min-h-24">
                            <div class="font-semibold mb-1" x-text="day.day"></div>
                            <template x-for="event in day.events" :key="event.id">
                                <div class="text-xs text-white p-1 rounded mb-1"
                                    :style="`background:${event.bg_color || '#2563eb'}`" x-text="event.title"></div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- TIMELINE VIEW -->
        <div x-show="viewType==='timeline'" class="container mx-auto px-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-center mb-6">الجدول الزمني</h2>
                <template x-for="event in filteredEvents" :key="event.id">
                    <div class="border-r-4 p-4 mb-4 rounded bg-blue-50" :class="statusBorder(event.status)">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold mb-2" x-text="event.title"></h3>
                            <button @click="openEdit(event)" :disabled="event.status === 'completed'"
                                :class="event.status === 'completed' ?
                                    'bg-gray-300 text-gray-500 cursor-not-allowed px-3 py-1 rounded text-sm' :
                                    'bg-blue-600 text-white hover:bg-blue-700 px-3 py-1 rounded text-sm'">
                                تعديل
                            </button>
                        </div>
                        <div class="text-sm flex flex-wrap gap-4">
                            <span>من <span x-text="formatDate(event.startDate)"></span></span>
                            <span>إلى <span x-text="formatDate(event.endDate)"></span></span>
                            <span>المدة: <span x-text="event.duration"></span> يوم</span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- EDIT MODAL -->
        <div x-show="showEditModal" x-transition
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg w-full max-w-xl p-6" @click.outside="closeEdit">
                <h3 class="text-xl font-bold mb-4">تعديل الحدث</h3>
                <template x-if="editingEvent">
                    <form method="POST" :action="`/calendar/${editingEvent?.id}`">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">

                            <div>
                                <label class="block text-sm font-medium mb-1">العنوان</label>
                                <input type="text" name="title" x-model="editingEvent.title"
                                    class="w-full border rounded px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">نوع النشاط</label>
                                <select name="type" x-model="editingEvent.type"
                                    class="w-full border rounded px-3 py-2">
                                    <option value="meeting">اجتماع</option>
                                    <option value="conference">مؤتمر</option>
                                    <option value="competition">مسابقة</option>
                                    <option value="program">برنامج</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm mb-1">تاريخ البداية</label>
                                    <input type="date" name="start_date" x-model="editingEvent.startDate"
                                        class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">تاريخ النهاية</label>
                                    <input type="date" name="end_date" x-model="editingEvent.endDate"
                                        class="w-full border rounded px-3 py-2">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm mb-1">البرنامج</label>
                                <input type="text" name="program" x-model="editingEvent.program"
                                    class="w-full border rounded px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm mb-1">لون الحدث</label>
                                <input type="color" name="bg_color" x-model="editingEvent.bg_color"
                                    class="w-20 h-10 border rounded">
                            </div>

                            <div>
                                <label class="block text-sm mb-1">ملاحظات</label>
                                <textarea name="notes" x-model="editingEvent.notes" class="w-full border rounded px-3 py-2"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="closeEdit"
                                class="px-4 py-2 rounded bg-gray-300">إلغاء</button>
                            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">حفظ
                                التعديلات</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

    </div>

    <script>
        function isSameOrBetween(date, start, end) {
            const d = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            const s = new Date(start.getFullYear(), start.getMonth(), start.getDate());
            const e = new Date(end.getFullYear(), end.getMonth(), end.getDate());
            return d >= s && d <= e;
        }

        function quranCalendar(eventsFromServer) {
            return {
                year: {{ $year }},
                viewType: 'year',
                currentMonth: new Date().getMonth(),
                filters: {
                    type: new URLSearchParams(window.location.search).get('type') || '',
                    program: new URLSearchParams(window.location.search).get('program') || '',
                    status: new URLSearchParams(window.location.search).get('status') || '',
                },
                monthNames: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر',
                    'نوفمبر', 'ديسمبر'
                ],
                events: eventsFromServer.map(e => ({
                    ...e,
                    startDate: new Date(e.startDate),
                    endDate: new Date(e.endDate),
                    bg_color: e.bg_color || '#2563eb'
                })),
                showEditModal: false,
                editingEvent: null,

                get filteredEvents() {
                    return this.events.filter(e =>
                        (!this.filters.type || e.type === this.filters.type) &&
                        (!this.filters.program || e.program === this.filters.program) &&
                        (!this.filters.status || e.status === this.filters.status)
                    );
                },
                get types() {
                    return [...new Set(this.events.map(e => e.type).filter(Boolean))];
                },
                get programs() {
                    return [...new Set(this.events.map(e => e.program).filter(Boolean))];
                },

                updateURL() {
                    const params = new URLSearchParams(window.location.search);
                    params.set('year', this.year);
                    if (this.filters.type) params.set('type', this.filters.type);
                    else params.delete('type');
                    if (this.filters.program) params.set('program', this.filters.program);
                    else params.delete('program');
                    if (this.filters.status) params.set('status', this.filters.status);
                    else params.delete('status');
                    history.replaceState(null, '', `${window.location.pathname}?${params.toString()}`);
                },

                changeYear(delta) {
                    this.year += delta;
                    const params = new URLSearchParams(window.location.search);
                    params.set('year', this.year);
                    if (this.filters.type) params.set('type', this.filters.type);
                    if (this.filters.program) params.set('program', this.filters.program);
                    if (this.filters.status) params.set('status', this.filters.status);
                    window.location.href = `${window.location.pathname}?${params.toString()}`;
                },

                exportCSV() {
                    const rows = [
                        ['Title', 'Type', 'Program', 'Start', 'End', 'Duration', 'Status'],
                        ...this.filteredEvents.map(e => [e.title, e.type, e.program || '', e.startDate.toISOString()
                            .split('T')[0], e.endDate.toISOString().split('T')[0], e.duration, e.status
                        ])
                    ];
                    const csvContent = "data:text/csv;charset=utf-8," + rows.map(r => r.join(",")).join("\n");
                    const link = document.createElement('a');
                    link.setAttribute('href', csvContent);
                    link.setAttribute('download', `calendar-${this.year}.csv`);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },

                openEdit(event) {
                    if (event.status === 'completed') return;
                    this.editingEvent = JSON.parse(JSON.stringify(event));
                    this.showEditModal = true;
                },
                closeEdit() {
                    this.showEditModal = false;
                    this.editingEvent = null;
                },

                btnClass(t) {
                    return this.viewType === t ? 'bg-blue-600 text-white px-4 py-2 rounded' :
                        'bg-gray-200 px-4 py-2 rounded';
                },

                getMonthDays(month) {
                    const days = [];
                    const last = new Date(this.year, month + 1, 0).getDate();
                    for (let d = 1; d <= last; d++) {
                        const date = new Date(this.year, month, d);
                        const events = this.filteredEvents.filter(e => isSameOrBetween(date, e.startDate, e.endDate));
                        days.push({
                            day: d,
                            events,
                            hasEvent: events.length
                        });
                    }
                    return days;
                },
                getMonthEvents(month) {
                    const map = {};
                    this.getMonthDays(month).forEach(d => d.events.forEach(e => map[e.id] = e));
                    return Object.values(map);
                },
                getCurrentMonthDays() {
                    return this.getMonthDays(this.currentMonth);
                },
                formatDate(d) {
                    return d.toLocaleDateString('ar-SA', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                },
                statusBorder(s) {
                    return {
                        active: 'border-green-600',
                        upcoming: 'border-gray-400',
                        completed: 'border-blue-600'
                    } [s];
                }
            }
        }
    </script>

    <style>
        @media print {
            body {
                background: white !important;
            }

            .print\:hidden {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
