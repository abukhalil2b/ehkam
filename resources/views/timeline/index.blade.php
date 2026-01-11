<x-app-layout>
    <div class="p-6" dir="rtl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-emerald-900">
                الخطة السنوية – {{ $year }}
            </h1>
            <a href="{{ route('calendar.index', ['year' => $year]) }}"
                class="text-emerald-600 font-bold hover:underline">
                العودة للتقويم →
            </a>
        </div>

        <div x-data="annualPlan2026(@js($events))"
            class="bg-white dark:bg-[#1b2e4b] border dark:border-[#191e3a] rounded-xl shadow-sm overflow-hidden">

            <div class="overflow-x-auto">
                <div class="flex border-b dark:border-[#191e3a] min-w-[1200px] bg-gray-50 dark:bg-[#0e1726]">
                    <div
                        class="w-80 shrink-0 border-l dark:border-[#191e3a] font-bold text-center py-3 dark:text-white-light">
                        النشاط / البرنامج</div>

                    <template x-for="month in months" :key="month.index">
                        <div
                            class="flex-1 text-center py-3 text-sm font-semibold border-l dark:border-[#191e3a] last:border-l-0 dark:text-white-dark">
                            <span x-text="month.label"></span>
                        </div>
                    </template>
                </div>

                <div class="min-w-[1200px]">
                    <template x-for="activity in activities" :key="activity.id">
                        <div
                            class="flex items-center border-b dark:border-[#191e3a] last:border-b-0 min-h-[64px] hover:bg-gray-50/50 dark:hover:bg-[#0e1726]/50 transition">

                            <a :href="'/timeline/show/' + activity.id"
                                class="w-80 px-4 py-2 shrink-0 border-l dark:border-[#191e3a] hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition block">
                                <div class="font-bold text-gray-800 dark:text-white-light text-sm mb-1 hover:text-emerald-700 dark:hover:text-emerald-400"
                                    x-text="activity.title"></div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-gray-500 dark:text-gray-400"
                                        x-text="'المدة: ' + durationInDays(activity) + ' يوم'"></span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold"
                                        :class="statusClasses(status(activity))" x-text="status(activity)"></span>
                                </div>
                            </a>

                            <div class="relative flex-1 h-10 flex items-center px-0">
                                <div class="absolute inset-0 flex">
                                    <template x-for="i in 12">
                                        <div class="flex-1 border-l border-gray-100 dark:border-[#191e3a] h-full"></div>
                                    </template>
                                </div>

                                <a :href="'/timeline/show/' + activity.id"
                                    class="absolute h-6 rounded-md shadow-sm border border-black/10 transition-transform hover:scale-[1.05] hover:shadow-lg z-10"
                                    :style="barStyle(activity)" :title="'عرض تفاصيل: ' + activity.title">
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        function annualPlan2026(eventsFromServer) {
            return {
                year: {{ $year }},
                daysInYear: 365,
                months: [],
                activities: eventsFromServer,

                init() {
                    this.buildMonths();
                    // Check for leap year
                    this.daysInYear = (this.year % 4 === 0 && this.year % 100 !== 0) || (this.year % 400 === 0) ? 366 : 365;
                },

                buildMonths() {
                    const labels = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر',
                        'أكتوبر', 'نوفمبر', 'ديسمبر'
                    ];
                    this.months = labels.map((label, index) => ({
                        label,
                        index
                    }));
                },

                barStyle(activity) {
                    const startDay = this.dayOfYear(activity.start_date);
                    const endDay = this.dayOfYear(activity.end_date);

                    const left = (startDay / this.daysInYear) * 100;
                    const width = ((endDay - startDay + 1) / this.daysInYear) * 100;

                    // Support both Tailwind bg classes or Hex codes from DB
                    const isHex = activity.bg_color.startsWith('#');
                    return `
                        left: ${left}%; 
                        width: ${width}%; 
                        background-color: ${isHex ? activity.bg_color : ''};
                    `;
                },

                durationInDays(activity) {
                    const start = new Date(activity.start_date);
                    const end = new Date(activity.end_date);
                    return Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
                },

                status(activity) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const start = new Date(activity.start_date);
                    const end = new Date(activity.end_date);

                    if (today < start) return 'قادم';
                    if (today > end) return 'مكتمل';
                    return 'جاري التنفيذ';
                },

                statusClasses(status) {
                    return {
                        'قادم': 'bg-gray-100 text-gray-600',
                        'جاري التنفيذ': 'bg-emerald-100 text-emerald-700',
                        'مكتمل': 'bg-blue-100 text-blue-700'
                    }[status];
                },

                dayOfYear(dateString) {
                    const date = new Date(dateString);
                    const startOfYear = new Date(this.year, 0, 1);
                    const diff = date - startOfYear;
                    return Math.floor(diff / (1000 * 60 * 60 * 24));
                }
            }
        }
    </script>
</x-app-layout>