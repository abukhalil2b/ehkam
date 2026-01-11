<x-calendar-layout>
    <x-slot name="title">{{ __('annual calendar') }} | {{ $year }}</x-slot>

    <div x-data="annualCalendar(@js($events))">

        {{-- Header --}}
        <header class="islamic-pattern text-white py-10 shadow-xl print:hidden mb-8">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-right">
                        <h1 class="text-3xl font-bold mb-2">{{ __('annual calendar') }} <span
                                class="text-emerald-300 text-lg">| {{ auth()->user()->name }}</span></h1>
                        <p class="text-xl opacity-90 flex items-center gap-2">
                            <span>{{ $year }} م</span>
                        </p>
                    </div>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('delegations.index') }}"
                            class="bg-white/10 hover:bg-white/20 p-3 rounded-xl border border-white/30 transition text-white"
                            title="إدارة التفويضات">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </a>
                        <a href="{{ route('calendar.settings') }}"
                            class="bg-white/10 hover:bg-white/20 p-3 rounded-xl border border-white/30 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('calendar.create', ['year' => $year]) }}"
                            class="bg-yellow-400 text-emerald-900 px-6 py-3 rounded-xl font-bold hover:bg-yellow-300 transition shadow-lg flex items-center gap-2">
                            <span class="text-xl font-bold">+</span>
                            {{ __('add new event') }}
                        </a>
                        <button @click="window.print()"
                            class="bg-white/20 backdrop-blur-md border border-white/30 text-white px-6 py-3 rounded-xl font-bold">طباعة</button>
                    </div>
                </div>
            </div>
        </header>

        <main class="container mx-auto px-4">

            {{-- Toolbar --}}
            <div
                class="bg-white rounded-xl shadow-lg p-4 mb-6 flex flex-wrap items-center justify-between gap-4 print:hidden">
                <div class="flex items-center gap-4">
                    <button @click="changeYear(1)"
                        class="p-2 hover:bg-gray-100 rounded-full transition"><x-calendar.icons.chevron-left
                            class="w-6 h-6" /></button>
                    <span class="text-2xl font-bold text-emerald-900" x-text="year"></span>
                    <button @click="changeYear(-1)"
                        class="p-2 hover:bg-gray-100 rounded-full transition"><x-calendar.icons.chevron-right
                            class="w-6 h-6" /></button>

                    <form action="{{ route('calendar.refresh') }}" method="POST" class="inline mr-2">
                        @csrf <input type="hidden" name="year" :value="year">
                        <button type="submit" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                            title="تحديث البيانات">
                            <x-calendar.icons.refresh class="w-6 h-6" />
                        </button>
                    </form>
                </div>

                <div class="flex-1 max-w-md">
                    <input type="text" x-model="filters.search" placeholder="{{ __('search_placeholder') }}..."
                        class="w-full border-none bg-gray-100 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500">
                </div>

                {{-- Views: Only 3 options now (Day, Year, Month) --}}
                <div class="flex bg-gray-100 p-1 rounded-lg">
                    <template x-for="view in ['day', 'year', 'month']">
                        <button @click="viewType = view"
                            :class="viewType === view ? 'bg-white shadow text-emerald-700' : 'text-gray-500'"
                            class="px-4 py-2 rounded-md font-bold transition capitalize"
                            x-text="view === 'day' ? 'يومي' : (view === 'year' ? 'سنوي' : 'شهري')"></button>
                    </template>
                </div>
            </div>

            {{-- Components --}}
            <div x-show="viewType === 'year'" x-cloak>
                <x-calendar.year-view />
            </div>

            <div x-show="viewType === 'month'" x-cloak>
                <x-calendar.month-view />
            </div>

            <div x-show="viewType === 'day'" x-cloak>
                <x-calendar.day-view />
            </div>

        </main>

        {{-- Edit Modal --}}
        @include('calendar.partials.edit-modal')

    </div>

    @push('scripts')
        <script>
            function annualCalendar(eventsFromServer) {
                return {
                    year: {{ $year }},
                    viewType: 'year',
                    showEditModal: false,
                    originalEvent: null,
                    editingEvent: {},
                    currentMonth: new Date().getMonth(),
                    selectedDate: new Date(),
                    showAllHours: false,
                    filters: {
                        type: '',
                        status: '',
                        search: ''
                    },
                    events: eventsFromServer,

                    // --- Vertical Grid Config ---
                    pixelsPerHour: 60, // Height of one hour in pixels

                    // Computed
                    get filteredEvents() {
                        return this.events.filter(e => {
                            const s = this.filters.search.toLowerCase();
                            return (!s || e.title.toLowerCase().includes(s) || e.creator.toLowerCase().includes(s));
                        });
                    },

                    // Logic Functions
                    stripDate(dateStr) {
                        return dateStr ? dateStr.split(' ')[0] : '';
                    },

                    openEdit(event) {
                        this.originalEvent = event;
                        this.editingEvent = {
                            ...event,
                            startDate: this.stripDate(event.startDate),
                            endDate: this.stripDate(event.endDate),
                            startTime: event.startTime,
                            endTime: event.endTime,
                            type: event.type,
                            bg_color: event.bg_color,
                            program: event.program
                        };
                        this.showEditModal = true;
                    },
                    closeEdit() {
                        this.showEditModal = false;
                    },

                    // Navigation Logic
                    goToMonth(m) {
                        this.currentMonth = m;
                        this.viewType = 'month';
                        window.scrollTo(0, 0);
                    },

                    // Click Day -> Go to Day View
                    goToDayView(monthIndex, day) {
                        const newDate = new Date(this.year, monthIndex, day);
                        this.selectedDate = newDate;
                        this.viewType = 'day';
                        window.scrollTo(0, 0);
                    },

                    changeYear(delta) {
                        window.location.href = `{{ route('calendar.index') }}?year=${parseInt(this.year) + delta}`;
                    },

                    // --- Day View Helpers (Vertical Grid) ---
                    changeDay(delta) {
                        const newDate = new Date(this.selectedDate);
                        newDate.setDate(newDate.getDate() + delta);
                        this.selectedDate = newDate;
                    },

                    isToday(date) {
                        const today = new Date();
                        return date.getDate() === today.getDate() &&
                            date.getMonth() === today.getMonth() &&
                            date.getFullYear() === today.getFullYear();
                    },

                    formatDayDate(date) {
                        return new Date(date).toLocaleDateString('ar-EG', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                    },
                    getDayName(date) {
                        return new Date(date).toLocaleDateString('ar-EG', {
                            weekday: 'long'
                        });
                    },

                    // Helper: Get all events for the selected day
                    getEventsForDay(dateObj) {
                        return this.filteredEvents.filter(e => {
                            const d = new Date(e.startDate);
                            return d.getDate() === new Date(dateObj).getDate() &&
                                d.getMonth() === new Date(dateObj).getMonth() &&
                                d.getFullYear() === new Date(dateObj).getFullYear();
                        });
                    },

                    getHijriDateForDay(date) {
                        const e = this.getEventsForDay(date)[0]; // Check first event of the day
                        return e && e.hijriDate ? e.hijriDate : '';
                    },

                    // 1. Calculate CSS Top Position (Time -> Pixels)
                    getEventTop(event) {
                        const date = new Date(event.startDate);
                        const hours = date.getHours();
                        const minutes = date.getMinutes();
                        // (Hours * 60 + Minutes) * (Pixels per minute)
                        return ((hours * 60) + minutes) * (this.pixelsPerHour / 60);
                    },

                    // 2. Calculate CSS Height (Duration -> Pixels)
                    getEventHeight(event) {
                        const start = new Date(event.startDate);
                        const end = new Date(event.endDate);
                        const diffMs = end - start;
                        const durationMinutes = Math.floor(diffMs / 60000);
                        // Minimum height 30px so text is visible
                        return Math.max(durationMinutes * (this.pixelsPerHour / 60), 30);
                    },

                    // 3. Current Time Red Line Position
                    getCurrentTimeTop() {
                        const now = new Date();
                        return ((now.getHours() * 60) + now.getMinutes()) * (this.pixelsPerHour / 60);
                    },

                    // 4. Click Empty Grid -> Redirect to Create Page
                    handleGridClick(e) {
                        // Since this requires complex math on the click event relative to the container,
                        // and you requested "easy", we will skip clicking empty space for now.
                        // Users can use the main "+ Add Event" button.
                    },

                    openCreateAt(dateObj, hour = 8) {
                        const dateStr = dateObj.toLocaleDateString('en-CA'); // YYYY-MM-DD
                        window.location.href = `{{ route('calendar.create') }}?year=${this.year}&date=${dateStr}`;
                    },

                    // Calendar Grid Helpers (Year/Month View)
                    monthNames: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر',
                        'نوفمبر', 'ديسمبر'
                    ],
                    getBlankDays(m) {
                        return new Array(new Date(this.year, m, 1).getDay()).fill(null);
                    },

                    getMonthDays(m) {
                        const days = [];
                        const count = new Date(this.year, m + 1, 0).getDate();
                        for (let d = 1; d <= count; d++) {
                            const cur = new Date(this.year, m, d);
                            cur.setHours(0, 0, 0, 0);
                            // Optimized Filter
                            const evs = this.filteredEvents.filter(e => {
                                const start = new Date(e.startDate);
                                start.setHours(0, 0, 0, 0);
                                const end = new Date(e.endDate);
                                end.setHours(0, 0, 0, 0);
                                return cur >= start && cur <= end;
                            });
                            days.push({
                                day: d,
                                hasEvent: evs.length > 0,
                                events: evs
                            });
                        }
                        return days;
                    },

                    getMonthEvents(m) {
                        const seen = new Set();
                        const out = [];
                        this.getMonthDays(m).forEach(d => d.events.forEach(e => {
                            if (!seen.has(e.id)) {
                                seen.add(e.id);
                                out.push(e);
                            }
                        }));
                        return out;
                    },

                    prevMonth() {
                        if (this.currentMonth === 0) {
                            this.currentMonth = 11;
                            this.changeYear(-1);
                        } else this.currentMonth--;
                    },
                    nextMonth() {
                        if (this.currentMonth === 11) {
                            this.currentMonth = 0;
                            this.changeYear(1);
                        } else this.currentMonth++;
                    },

                    // Delete Action
                    confirmDelete(id) {
                        if (confirm("{{ __('confirm_delete_event') }}")) {
                            let url = "{{ route('calendar.destroy', ':id') }}";
                            url = url.replace(':id', id);

                            const f = document.createElement('form');
                            f.method = 'POST';
                            f.action = url;

                            const t = document.createElement('input');
                            t.type = 'hidden';
                            t.name = '_token';
                            t.value = "{{ csrf_token() }}";
                            const m = document.createElement('input');
                            m.type = 'hidden';
                            m.name = '_method';
                            m.value = 'DELETE';

                            f.appendChild(t);
                            f.appendChild(m);
                            document.body.appendChild(f);
                            f.submit();
                        }
                    }
                }
            }
        </script>
    @endpush
</x-calendar-layout>