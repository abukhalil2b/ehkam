<x-app-layout>
    <div class="p-6" dir="rtl">

        <!-- Page Title -->
        <h1 class="text-2xl font-bold mb-6">
            الخطة السنوية لمشروع نور القرآن – 2026
        </h1>

        <!-- Annual Timeline -->
        <div x-data="annualPlan2026()" x-init="init()" class="bg-white border rounded-xl overflow-x-auto">

            <!-- Timeline Header (Months) -->
            <div class="flex border-b min-w-[1400px]">
                <div class="w-72 shrink-0 bg-gray-50"></div>

                <template x-for="month in months" :key="month.index">
                    <div class="flex-1 text-center py-3 text-sm font-semibold border-r">
                        <span x-text="month.label"></span>
                    </div>
                </template>
            </div>

            <!-- Activities -->
            <div class="min-w-[1400px]">
                <template x-for="activity in activities" :key="activity.id">
                    <div class="flex items-center border-b min-h-[52px]">

                        <!-- Activity Title -->
                        <div class="w-72 px-4 text-sm text-gray-800">
                            <div class="font-medium" x-text="activity.title"></div>

                            <div class="flex gap-2 mt-1 text-xs">
                                <!-- Duration -->
                                <span class="text-gray-500">
                                    المدة:
                                    <span x-text="durationInDays(activity) + ' يوم'"></span>
                                </span>

                                <!-- Status -->
                                <span class="px-2 rounded" :class="statusColor(status(activity))"
                                    x-text="status(activity)"></span>
                            </div>
                        </div>


                        <!-- Timeline Bar Area -->
                        <div class="relative flex-1 h-6">
                            <div class="absolute h-4 rounded cursor-pointer opacity-90 hover:opacity-100"
                                :class="activity.color" :style="barStyle(activity)"
                                :title="activity.start + ' → ' + activity.end"></div>
                        </div>
                    </div>
                </template>
            </div>

        </div>
    </div>
    <script>
        function annualPlan2026() {
            return {
                year: 2026,
                daysInYear: 365,
                months: [],
                activities: [],

                init() {
                    this.buildMonths()
                    this.loadActivities()
                },

                buildMonths() {
                    const labels = [
                        'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
                        'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
                    ]

                    this.months = labels.map((label, index) => ({
                        label,
                        index
                    }))
                },

                loadActivities() {
                    this.activities = [{
                            id: 1,
                            title: 'عقد الاجتماع الأول لمشروع نور القرآن',
                            start: '2026-01-26',
                            end: '2026-02-27',
                            color: 'bg-blue-500'
                        },
                        {
                            id: 2,
                            title: 'وضع خطة مفصلة لكل نشاط',
                            start: '2026-02-02',
                            end: '2026-02-27',
                            color: 'bg-blue-500'
                        },
                        {
                            id: 3,
                            title: 'الإعلان والتسجيل في برنامج الإجازة القرآنية',
                            start: '2026-02-09',
                            end: '2026-04-13',
                            color: 'bg-green-500'
                        },
                        {
                            id: 4,
                            title: 'الملتقى القرآني لمدارس القرآن الكريم',
                            start: '2026-09-15',
                            end: '2026-10-12',
                            color: 'bg-purple-500'
                        }
                    ]
                },

                barStyle(activity) {
                    const start = this.dayOfYear(activity.start)
                    const end = this.dayOfYear(activity.end)

                    const left = (start / this.daysInYear) * 100
                    const width = ((end - start) / this.daysInYear) * 100

                    return `left:${left}%; width:${width}%;`
                },
                durationInDays(activity) {
                    const start = new Date(activity.start)
                    const end = new Date(activity.end)
                    const diff = end - start
                    return Math.ceil(diff / (1000 * 60 * 60 * 24)) + 1
                },

                status(activity) {
                    const today = new Date()
                    const start = new Date(activity.start)
                    const end = new Date(activity.end)

                    if (today < start) return 'قادم'
                    if (today > end) return 'مكتمل'
                    return 'جاري التنفيذ'
                },

                statusColor(status) {
                    return {
                        'قادم': 'bg-gray-200 text-gray-700',
                        'جاري التنفيذ': 'bg-green-100 text-green-800',
                        'مكتمل': 'bg-blue-100 text-blue-800'
                    } [status]
                },

                dayOfYear(dateString) {
                    const date = new Date(dateString)
                    const start = new Date(this.year, 0, 1)
                    const diff = date - start
                    return Math.floor(diff / (1000 * 60 * 60 * 24))
                }
            }
        }
    </script>

</x-app-layout>
