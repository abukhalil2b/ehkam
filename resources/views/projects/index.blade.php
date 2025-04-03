<x-app-layout>
    <!-- Header -->
    <div class="bg-[#1e3d4f] text-2xl text-white font-bold p-4">
        <h1 class="container mx-auto">إدارة المؤشرات</h1>
    </div>

    <!-- Main Container with Alpine.js state -->
    <div class="container py-8 mx-auto" x-data="modalComponent()" x-init="init()">
        <!-- Search Form -->
        <div class="mb-4 p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex gap-2">
                <select
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">اختر</option>
                    <option value="197">دائرة القران الكريم</option>
                    <option value="198">دائرة رسالة الإسلام والمؤتلف الإنساني</option>
                    <option value="199">دائرة التخطيط والاحصاء</option>
                    <option value="200">دائرة الحوكمة والأداء المؤسسي</option>
                </select>
                <button type="button"
                    class="w-32 text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                    بحث
                </button>
            </div>
        </div>

        <!-- Trigger Button -->
        <button @click="open = true" type="button"
            class="w-32 mb-1 text-white bg-green-700 hover:bg-green-800 focus:ring-2 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
            + جديد
        </button>

        <!-- Data Table -->
        <div class="p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-1 relative overflow-x-auto">
                <div
                    class="flex flex-col sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
                    <div>
                        <span>الخطة السنوية:</span>
                        <select name=""
                            class="inline-block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option selected value="">2025</option>
                            <option value="">2024</option>
                            <option value="">2023</option>
                        </select>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-1 flex items-center ps-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input
                            class="block p-2 ps-5 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50">
                    </div>
                </div>

                <table class="w-full text-sm text-right text-gray-500">
                    <thead class="text-xs text-gray-700 bg-gray-50">
                        <tr>
                            <th scope="col" class="p-4">#</th>
                            <th scope="col" class="px-6 py-3">نوع المؤشر</th>
                            <th scope="col" class="px-6 py-3">الاسم</th>
                            <th scope="col" class="px-6 py-3">البرنامج،الوحدة،القطاع</th>
                            <th scope="col" class="px-6 py-3">الفترة الزمنية</th>
                            <th scope="col" class="px-6 py-3">المراجعة</th>
                            <th scope="col" class="px-6 py-3">الحالة</th>
                            <th scope="col" class="px-6 py-3">عمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                            <td class="w-4 p-4">1</td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                مبادرة تمكينية
                            </th>
                            <td class="px-6 py-4">رضاكم</td>
                            <td class="px-6 py-4">
                                <p>لوحة مؤشرات أداء وزارة الأوقاف</p>
                                <p>وزارة الأوقاف والشؤون الدينية</p>
                                <p>المديرية العامة للتخطيط</p>
                                <p>دائرة التخطيط والاحصاء</p>
                                <p>دائرة التخطيط والاحصاء</p>
                            </td>
                            <td class="px-6 py-4">
                                <div>من 1-1-2025</div>
                                <div>إلى 31-12-2025</div>
                            </td>
                            <td class="px-6 py-4">تم الأعتماد النهائي (فريق تقييم الأداء)</td>
                            <td class="px-6 py-4">
                                في الإجراء
                                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100">0%</div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="/projects/show"
                                    class="w-16 block text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs p-1 text-center mb-1">
                                    عرض
                                </a>

                            </td>
                        </tr>

                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                            <td class="w-4 p-4">2</td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                مبادرة تمكينية
                            </th>
                            <td class="px-6 py-4">مبادرة ورش تخطيط الوزارة</td>
                            <td class="px-6 py-4">
                                <p>لوحة مؤشرات أداء وزارة الأوقاف</p>
                                <p>وزارة الأوقاف والشؤون الدينية</p>
                                <p>المديرية العامة للتخطيط</p>
                                <p>دائرة التخطيط والاحصاء</p>
                                <p>دائرة التخطيط والاحصاء</p>
                            </td>
                            <td class="px-6 py-4">من 1-1-2025 إلى 31-12-2025</td>
                            <td class="px-6 py-4">تم الأعتماد النهائي (فريق تقييم الأداء)</td>
                            <td class="px-6 py-4">
                                مكتمل
                                <div class="progress-bar" role="progressbar" style="width: 100.01%"
                                    aria-valuenow="100.01" aria-valuemin="0" aria-valuemax="100">100%</div>
                            </td>
                            <td class="px-6 py-4">
                                <a
                                    class="w-16 block text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs p-1 text-center mb-1">
                                    عرض
                                </a>
                                <a
                                    class="w-16 block text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-xs p-1 text-center mb-1">
                                    الخطوات
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="open" @keydown.escape.window="open = false" @click.away="open = false"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="display: none;">
            <div class="bg-white w-full max-w-3xl p-6 rounded-lg shadow-lg transition-transform transform scale-95"
                x-show="open" x-transition.scale>
                <!-- Tab Navigation -->
                <div class="flex border-b pb-2 space-x-4">
                    <button @click="activeTab = 'tab1'"
                        :class="{ 'border-b-2 border-green-700 text-green-700': activeTab === 'tab1' }"
                        class="py-2 px-4 focus:outline-none hover:text-green-500">
                        البيانات الأساسية
                    </button>
                    <button @click="activeTab = 'tab2'"
                        :class="{ 'border-b-2 border-green-700 text-green-700': activeTab === 'tab2' }"
                        class="py-2 px-4 focus:outline-none hover:text-green-500">
                        بطاقة المؤشرات
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="mt-4">
                    <!-- البيانات الأساسية -->
                    <div x-show="activeTab === 'tab1'">
                        <h3 class="mb-2 flex justify-center text-green-800 text-lg font-bold">وزارة الأوقاف والشؤون
                            الدينية</h3>
                        <div class="mb-2 flex gap-1">
                            <label class="flex-1">
                                <span class="block text-xs text-blue-800">القطاع</span>
                                <select class="form-select">
                                    <option value="" disabled selected></option>
                                    <option value="">المديرية العامة للتخطيط</option>
                                    <option value="">المديرية العامة للأوقاف</option>
                                </select>
                            </label>
                            <label class="flex-1">
                                <span class="block text-xs text-blue-800">قطاع الفرعي</span>
                                <select class="form-select">
                                    <option value="" disabled selected></option>
                                    <option value="">دائرة التخطيط</option>
                                    <option value="">دائرة الحوكمة</option>
                                </select>
                            </label>
                        </div>
                        <label class="block mb-1 text-xs text-blue-800">
                            <span class="block">نوع المؤشر</span>
                            <select class="form-select">
                                <option value="" disabled selected></option>
                                <option value="">مشروع</option>
                                <option value="">تمكينية</option>
                            </select>
                        </label>
                        <label class="block mb-1 text-xs text-blue-800">
                            <span class="block">الاسم</span>
                            <input type="text" class="form-input w-full" placeholder="أدخل الاسم">
                        </label>
                        <label class="block mb-1 text-xs text-blue-800">
                            <span class="block">الوصف</span>
                            <textarea class="form-input w-full" rows="4" placeholder="أدخل الوصف"></textarea>
                        </label>
                    </div>

                    <!-- بطاقة المؤشرات -->
                    <div x-show="activeTab === 'tab2'">
                        <label class="block mb-1 text-xs text-blue-800">
                            <span class="block">المؤشرات</span>
                            <select class="form-select">
                                <option value="" disabled selected></option>
                                <option value="زكاة">رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي</option>
                                <option value="مساجد">عدد الجوامع والمساجد ومدارس القرآن الكريم التي تغطي مصاريف
                                    الخدمات الأساسية</option>
                                <option value="تعليم">عدد متعلمي القرآن الكريم</option>
                                <option value="أصول_وقفية">قيمة الأصول الوقفية الجديدة سنويًا</option>
                                <option value="أنشطة_دينية">زيادة نسبة المستفيدين من الأنشطة الدينية وخدمات الإفتاء
                                </option>
                                <option value="رضا">رفع نسبة رضا المستفيدين عن الخدمات المقدمة من الوزارة</option>
                                <option value="تسامح">عدد المستفيدين من برامج تعزيز قيم التسامح والتعايش والمؤتلف
                                    الإنساني (دولياً)</option>
                                <option value="هوية_وطنية">عدد المستفيدين من برامج تعزيز الهوية الوطنية (محلياً)
                                </option>
                                <option value="بيت_المال">زيادة نسبة إيرادات أصول بيت المال</option>
                                <option value="أصول">زيادة نسبة إيرادات الأصول</option>
                            </select>
                        </label>
                    </div>
                </div>

                <!-- Close Button -->
                <div class="mt-4 text-right">
                    <button @click="open = false" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        حفظ
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Alpine.js Initialization Script -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('modalComponent', () => ({
            open: false,
            activeTab: 'tab1',
            init() {
                // Watch for changes to activeTab and focus the input within the newly active tab after a short delay.
                this.$watch('activeTab', value => {
                    setTimeout(() => {
                        const input = this.$root.querySelector(
                            `[x-show="activeTab === '${value}'"] input`);
                        if (input) {
                            input.focus();
                        }
                    }, 100);
                });
            }
        }));
    });
</script>
