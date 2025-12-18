<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشروع نور القرآن - التقويم السنوي 2026</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap');

        body {
            font-family: 'Amiri', serif;
        }

        .event-dot {
            width: 8px;
            height: 8px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .month-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-gray-600" x-data="quranCalendar()">
    <!-- Header -->
    <header class="gradient-bg islamic-pattern text-white py-8 mb-8">
        <div class="container mx-auto px-4 text-center">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-quran-book text-4xl ml-3"></i>
                <h1 class="text-4xl font-bold">مشروع نور القرآن</h1>
            </div>
            <p class="text-xl opacity-90">التقويم السنوي للأنشطة والفعاليات 2026</p>
            <div class="mt-4 flex justify-center gap-4">
                <span class="bg-white bg-opacity-20 px-4 py-2 rounded-lg">
                    <i class="fas fa-calendar-alt ml-2"></i>
                    <span x-text="totalEvents"></span> فعالية
                </span>
                <span class="bg-white bg-opacity-20 px-4 py-2 rounded-lg">
                    <i class="fas fa-clock ml-2"></i>
                    العام الهجري 1447-1448
                </span>
            </div>
        </div>
    </header>

    <!-- Navigation Controls -->
    <div class="container mx-auto px-4 mb-6">
        <div class="flex justify-between items-center bg-white rounded-lg shadow-md p-4">
            <div class="flex gap-2">
                <button @click="viewType = 'year'"
                    :class="viewType === 'year' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                    class="px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-calendar ml-2"></i>السنة كاملة
                </button>
                <button @click="viewType = 'month'"
                    :class="viewType === 'month' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                    class="px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-calendar-day ml-2"></i>شهر واحد
                </button>
                <button @click="viewType = 'timeline'"
                    :class="viewType === 'timeline' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                    class="px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-timeline ml-2"></i>الجدول الزمني
                </button>
            </div>

            <div x-show="viewType === 'month'" class="flex items-center space-x-2">
                <button @click="previousMonth()" class="p-2 text-gray-600 hover:text-blue-600">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <span class="font-semibold text-lg" x-text="monthNames[currentMonth] + ' ' + currentYear"></span>
                <button @click="nextMonth()" class="p-2 text-gray-600 hover:text-blue-600">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Year View -->
    <div x-show="viewType === 'year'" class="container mx-auto px-4">
        <div class="month-grid gap-6">
            <template x-for="(month, index) in monthNames" :key="index">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-center mb-3 text-blue-800" x-text="month + ' 2026'"></h3>
                    <div class="calendar-grid gap-1 text-sm">
                        <template x-for="day in ['ح', 'ن', 'ث', 'ر', 'خ', 'ج', 'س']">
                            <div class="text-center font-semibold text-gray-600 p-1" x-text="day"></div>
                        </template>
                        <template x-for="day in getMonthDays(index)" :key="day.date">
                            <div class="relative p-1 text-center cursor-pointer hover:bg-blue-50 rounded"
                                :class="day.hasEvent ? 'bg-blue-100' : ''" @click="showEventDetails(day.events)">
                                <span x-text="day.day" class="text-gray-800"></span>
                                <template x-if="day.hasEvent">
                                    <div class="flex justify-center mt-1">
                                        <div class="event-dot bg-green-500 rounded-full"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Month View -->
    <div x-show="viewType === 'month'" class="container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="calendar-grid gap-2 mb-4">
                <template x-for="day in ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت']">
                    <div class="text-center font-bold text-gray-700 p-3 bg-gray-100 rounded" x-text="day"></div>
                </template>
            </div>
            <div class="calendar-grid gap-2">
                <template x-for="day in getCurrentMonthDays()" :key="day.date">
                    <div class="min-h-24 p-2 border rounded-lg hover:shadow-md transition-shadow"
                        :class="day.isCurrentMonth ? 'bg-white' : 'bg-gray-50'" @click="showEventDetails(day.events)">
                        <div class="font-semibold mb-1" :class="day.isToday ? 'text-blue-600' : 'text-gray-800'"
                            x-text="day.day"></div>
                        <template x-for="event in day.events.slice(0, 2)">
                            <div class="text-xs p-1 mb-1 rounded text-white" :class="getEventColor(event.type)"
                                x-text="event.title.substring(0, 20) + '...'"></div>
                        </template>
                        <template x-if="day.events.length > 2">
                            <div class="text-xs text-gray-500" x-text="'+' + (day.events.length - 2) + ' أخرى'"></div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Timeline View -->
    <div x-show="viewType === 'timeline'" class="container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6 text-center text-blue-800">الجدول الزمني للأنشطة</h2>
            <div class="space-y-4">
                <template x-for="(event, index) in events" :key="index">
                    <div class="flex items-start space-x-4 p-4 border-r-4 border-blue-500 bg-blue-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold"
                                x-text="index + 1"></div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-blue-800 mb-2" x-text="event.title"></h3>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar-start ml-2 text-green-600"></i>
                                    البداية: <span x-text="formatDate(event.startDate)"></span>
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-calendar-check ml-2 text-red-600"></i>
                                    النهاية: <span x-text="formatDate(event.endDate)"></span>
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-clock ml-2 text-blue-600"></i>
                                    المدة: <span x-text="calculateDuration(event.startDate, event.endDate)"></span> يوم
                                </span>
                            </div>
                            <div class="mt-2">
                                <span class="inline-block px-3 py-1 text-xs rounded-full text-white"
                                    :class="getEventColor(event.type)" x-text="getEventTypeLabel(event.type)"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click="showModal = false">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4" @click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-blue-800">تفاصيل الأنشطة</h3>
                <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-3">
                <template x-for="event in selectedEvents" :key="event.title">
                    <div class="p-3 border rounded-lg">
                        <h4 class="font-semibold mb-2" x-text="event.title"></h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div>البداية: <span x-text="formatDate(event.startDate)"></span></div>
                            <div>النهاية: <span x-text="formatDate(event.endDate)"></span></div>
                            <div>النوع: <span x-text="getEventTypeLabel(event.type)"></span></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Statistics Panel -->
    <div class="container mx-auto px-4 mt-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg text-center">
                <i class="fas fa-calendar-alt text-3xl mb-2"></i>
                <div class="text-2xl font-bold" x-text="totalEvents"></div>
                <div class="text-sm opacity-90">إجمالي الأنشطة</div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg text-center">
                <i class="fas fa-play-circle text-3xl mb-2"></i>
                <div class="text-2xl font-bold" x-text="getActiveEvents().length"></div>
                <div class="text-sm opacity-90">الأنشطة الجارية</div>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg text-center">
                <i class="fas fa-clock text-3xl mb-2"></i>
                <div class="text-2xl font-bold" x-text="getUpcomingEvents().length"></div>
                <div class="text-sm opacity-90">الأنشطة القادمة</div>
            </div>
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 rounded-lg text-center">
                <i class="fas fa-check-circle text-3xl mb-2"></i>
                <div class="text-2xl font-bold" x-text="getCompletedEvents().length"></div>
                <div class="text-sm opacity-90">الأنشطة المكتملة</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-16 bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <div class="mb-4">
                <i class="fas fa-mosque text-3xl mb-2"></i>
                <h3 class="text-xl font-bold">مشروع نور القرآن</h3>
            </div>
            <p class="text-gray-300">نسأل الله التوفيق والسداد في خدمة كتاب الله العزيز</p>
            <div class="mt-4 text-sm text-gray-400">
                © 2026 - جميع الحقوق محفوظة
            </div>
        </div>
    </footer>

    <script>
        function quranCalendar() {
            return {
                viewType: 'year',
                currentMonth: new Date().getMonth(),
                currentYear: 2026,
                showModal: false,
                selectedEvents: [],

                monthNames: [
                    'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
                    'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
                ],

                events: [{
                        title: 'عقد الاجتماع الأول لمشروع نور القرآن لوضع الرؤية العامة وخطة العمل',
                        startDate: new Date(2026, 0, 26),
                        endDate: new Date(2026, 1, 27),
                        type: 'meeting'
                    },
                    {
                        title: 'وضع خطة مفصلة لكل نشاط، وتحديد آلية التنفيذ وإجراءات التشغيل',
                        startDate: new Date(2026, 1, 2),
                        endDate: new Date(2026, 1, 27),
                        type: 'planning'
                    },
                    {
                        title: 'إعداد التصور المبدئي حول إقامة المؤتمر',
                        startDate: new Date(2026, 3, 1),
                        endDate: new Date(2026, 6, 31),
                        type: 'conference'
                    },
                    {
                        title: 'الإعلان والتسجيل في نشاط برنامج (الإجازة القرآنية)',
                        startDate: new Date(2026, 1, 9),
                        endDate: new Date(2026, 3, 13),
                        type: 'registration'
                    },
                    {
                        title: 'يوم السرد القرآني لبرنامج (الإجازة القرآنية)',
                        startDate: new Date(2026, 7, 17),
                        endDate: new Date(2026, 8, 11),
                        type: 'event'
                    },
                    {
                        title: 'الإعلان والتسجيل في نشاط (برنامج تعليم القرآن الكريم عن بعد)',
                        startDate: new Date(2026, 1, 2),
                        endDate: new Date(2026, 1, 20),
                        type: 'registration'
                    },
                    {
                        title: 'الملتقى القرآني لمدارس القرآن الكريم',
                        startDate: new Date(2026, 8, 15),
                        endDate: new Date(2026, 9, 12),
                        type: 'conference'
                    },
                    {
                        title: 'الإعلان والتسجيل في نشاط (مسابقة فاستمسك القرآنية)',
                        startDate: new Date(2026, 1, 2),
                        endDate: new Date(2026, 1, 27),
                        type: 'registration'
                    },
                    {
                        title: 'إقامة التصفيات الأولية في نشاط (مسابقة فاستمسك القرآنية)',
                        startDate: new Date(2026, 1, 2),
                        endDate: new Date(2026, 3, 27),
                        type: 'competition'
                    },
                    {
                        title: 'إعلان أسماء المتأهلين للتصفيات النهائية في المسابقة',
                        startDate: new Date(2026, 5, 1),
                        endDate: new Date(2026, 7, 5),
                        type: 'announcement'
                    },
                    {
                        title: 'إقامة التصفيات النهائية للمسابقة',
                        startDate: new Date(2026, 8, 1),
                        endDate: new Date(2026, 10, 1),
                        type: 'competition'
                    },
                    {
                        title: 'مخاطبة المؤسسات الداعمة والراعية للمؤتمر',
                        startDate: new Date(2026, 3, 1),
                        endDate: new Date(2026, 3, 2),
                        type: 'communication'
                    },
                    {
                        title: 'مخاطبة المحاضرين والمدربين لإرسال المادة العلمية',
                        startDate: new Date(2026, 7, 1),
                        endDate: new Date(2026, 9, 31),
                        type: 'communication'
                    },
                    {
                        title: 'مخاطبة مدارس القرآن الكريم ومدارس القرآن الكريم الوقفية حول إقامة المؤتمر',
                        startDate: new Date(2026, 7, 1),
                        endDate: new Date(2026, 9, 31),
                        type: 'communication'
                    },
                    {
                        title: 'تنفيذ المؤتمر',
                        startDate: new Date(2026, 7, 1),
                        endDate: new Date(2026, 9, 31),
                        type: 'conference'
                    },
                    {
                        title: 'إعداد إحصائيات الدارسين في نشاط (تعليم القرآن الكريم عن بعد) للنصف الأول',
                        startDate: new Date(2026, 0, 1),
                        endDate: new Date(2026, 0, 2),
                        type: 'statistics'
                    },
                    {
                        title: 'إعداد إحصائيات الدارسين في نشاط (الإجازة القرآنية) للنصف الأول',
                        startDate: new Date(2026, 0, 7),
                        endDate: new Date(2026, 1, 24),
                        type: 'statistics'
                    },
                    {
                        title: 'إعداد إحصائيات الدارسين في نشاط (تعليم القرآن الكريم عن بعد) للنصف الثاني',
                        startDate: new Date(2026, 10, 23),
                        endDate: new Date(2026, 11, 11),
                        type: 'statistics'
                    },
                    {
                        title: 'إعداد إحصائيات الدارسين في نشاط (الإجازة القرآنية) للنصف الثاني',
                        startDate: new Date(2026, 10, 23),
                        endDate: new Date(2026, 11, 11),
                        type: 'statistics'
                    },
                    {
                        title: 'إعداد إحصائيات الدارسين في نشاط (مسابقة فاستمسك القرآنية) للنصف الثاني',
                        startDate: new Date(2026, 10, 23),
                        endDate: new Date(2026, 11, 11),
                        type: 'statistics'
                    },
                    {
                        title: 'إقامة الحفل النهائي لمسابقة فاستمسك لحفظ القرآن الكريم',
                        startDate: new Date(2026, 10, 1),
                        endDate: new Date(2026, 11, 25),
                        type: 'ceremony'
                    },
                    {
                        title: 'إعداد استبانة لتقييم رضا المشاركين في المؤتمر',
                        startDate: new Date(2026, 10, 1),
                        endDate: new Date(2026, 10, 30),
                        type: 'evaluation'
                    },
                    {
                        title: 'تجميع المادة العلمية والتوثيق الإعلامي',
                        startDate: new Date(2026, 10, 1),
                        endDate: new Date(2026, 10, 30),
                        type: 'documentation'
                    },
                    {
                        title: 'إعداد تقرير نهائي للأنشطة وذكر التحديات والحلول والتوصيات',
                        startDate: new Date(2026, 11, 7),
                        endDate: new Date(2026, 11, 18),
                        type: 'report'
                    },
                    {
                        title: 'رفع توصيات المؤتمر والخطة المقترحة لتنفيذ التوصيات',
                        startDate: new Date(2026, 11, 1),
                        endDate: new Date(2026, 11, 31),
                        type: 'recommendations'
                    }
                ],

                get totalEvents() {
                    return this.events.length;
                },

                previousMonth() {
                    if (this.currentMonth === 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                },

                nextMonth() {
                    if (this.currentMonth === 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                },

                getMonthDays(monthIndex) {
                    const year = 2026;
                    const month = monthIndex;
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);
                    const daysInMonth = lastDay.getDate();
                    const startingDayOfWeek = firstDay.getDay();

                    const days = [];

                    // Add empty cells for days before the first day of the month
                    for (let i = 0; i < startingDayOfWeek; i++) {
                        days.push({
                            day: '',
                            date: null,
                            hasEvent: false,
                            events: []
                        });
                    }

                    // Add days of the month
                    for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(year, month, day);
                        const dayEvents = this.getEventsForDate(date);
                        days.push({
                            day: day,
                            date: date,
                            hasEvent: dayEvents.length > 0,
                            events: dayEvents
                        });
                    }

                    return days;
                },

                getCurrentMonthDays() {
                    const year = this.currentYear;
                    const month = this.currentMonth;
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);
                    const daysInMonth = lastDay.getDate();
                    const startingDayOfWeek = firstDay.getDay();

                    const days = [];

                    // Add days from previous month
                    const prevMonth = month === 0 ? 11 : month - 1;
                    const prevYear = month === 0 ? year - 1 : year;
                    const prevMonthLastDay = new Date(prevYear, prevMonth + 1, 0).getDate();

                    for (let i = startingDayOfWeek - 1; i >= 0; i--) {
                        const day = prevMonthLastDay - i;
                        const date = new Date(prevYear, prevMonth, day);
                        const dayEvents = this.getEventsForDate(date);
                        days.push({
                            day: day,
                            date: date,
                            isCurrentMonth: false,
                            isToday: false,
                            hasEvent: dayEvents.length > 0,
                            events: dayEvents
                        });
                    }

                    // Add days of current month
                    for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(year, month, day);
                        const dayEvents = this.getEventsForDate(date);
                        const today = new Date();
                        const isToday = date.toDateString() === today.toDateString();

                        days.push({
                            day: day,
                            date: date,
                            isCurrentMonth: true,
                            isToday: isToday,
                            hasEvent: dayEvents.length > 0,
                            events: dayEvents
                        });
                    }

                    // Add days from next month to fill the grid
                    const remainingDays = 42 - days.length; // 6 rows × 7 days = 42
                    const nextMonth = month === 11 ? 0 : month + 1;
                    const nextYear = month === 11 ? year + 1 : year;

                    for (let day = 1; day <= remainingDays; day++) {
                        const date = new Date(nextYear, nextMonth, day);
                        const dayEvents = this.getEventsForDate(date);
                        days.push({
                            day: day,
                            date: date,
                            isCurrentMonth: false,
                            isToday: false,
                            hasEvent: dayEvents.length > 0,
                            events: dayEvents
                        });
                    }

                    return days;
                },

                getEventsForDate(date) {
                    return this.events.filter(event => {
                        return date >= event.startDate && date <= event.endDate;
                    });
                },

                getActiveEvents() {
                    const today = new Date();
                    return this.events.filter(event => {
                        return today >= event.startDate && today <= event.endDate;
                    });
                },

                getUpcomingEvents() {
                    const today = new Date();
                    return this.events.filter(event => {
                        return event.startDate > today;
                    });
                },

                getCompletedEvents() {
                    const today = new Date();
                    return this.events.filter(event => {
                        return event.endDate < today;
                    });
                },

                showEventDetails(events) {
                    if (events.length > 0) {
                        this.selectedEvents = events;
                        this.showModal = true;
                    }
                },

                getEventColor(type) {
                    const colors = {
                        'meeting': 'bg-blue-500',
                        'planning': 'bg-green-500',
                        'conference': 'bg-purple-500',
                        'registration': 'bg-orange-500',
                        'event': 'bg-red-500',
                        'competition': 'bg-yellow-500',
                        'announcement': 'bg-indigo-500',
                        'communication': 'bg-pink-500',
                        'statistics': 'bg-teal-500',
                        'ceremony': 'bg-rose-500',
                        'evaluation': 'bg-cyan-500',
                        'documentation': 'bg-lime-500',
                        'report': 'bg-amber-500',
                        'recommendations': 'bg-violet-500'
                    };
                    return colors[type] || 'bg-gray-500';
                },

                getEventTypeLabel(type) {
                    const labels = {
                        'meeting': 'اجتماع',
                        'planning': 'تخطيط',
                        'conference': 'مؤتمر',
                        'registration': 'تسجيل',
                        'event': 'فعالية',
                        'competition': 'مسابقة',
                        'announcement': 'إعلان',
                        'communication': 'مراسلة',
                        'statistics': 'إحصائيات',
                        'ceremony': 'حفل',
                        'evaluation': 'تقييم',
                        'documentation': 'توثيق',
                        'report': 'تقرير',
                        'recommendations': 'توصيات'
                    };
                    return labels[type] || 'نشاط';
                },

                formatDate(date) {
                    return date.toLocaleDateString('ar-SA', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                },

                calculateDuration(startDate, endDate) {
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    return Math.ceil(timeDiff / (1000 * 3600 * 24));
                }
            }
        }
    </script>
</body>

</html>
