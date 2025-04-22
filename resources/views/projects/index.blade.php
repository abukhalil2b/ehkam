<x-app-layout>
    <!-- Header with improved styling -->
    <div class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-2xl text-white font-bold p-4 shadow-md">
        <h1 class="container mx-auto px-4">إدارة المؤشرات</h1>
    </div>

    <!-- Main Container -->
    <div class="container py-8 mx-auto px-4" x-data="modalComponent()" x-init="init()">
        <!-- Search Card with better spacing -->
        <div class="mb-6 p-4 bg-white rounded-lg shadow-md border border-gray-100">
            <div class="flex flex-col md:flex-row gap-4">
                <select class="flex-grow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    <option value="">اختر الدائرة</option>
                    <option value="197">دائرة القران الكريم</option>
                    <option value="198">دائرة رسالة الإسلام والمؤتلف الإنساني</option>
                    <option value="199">دائرة التخطيط والاحصاء</option>
                    <option value="200">دائرة الحوكمة والأداء المؤسسي</option>
                </select>
                <button type="button"
                    class="md:w-32 text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
                    بحث <i class="fas fa-search mr-1"></i>
                </button>
            </div>
        </div>

        <!-- Action Bar with better buttons -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <button @click="open = true" type="button"
                class="flex items-center text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:ring-2 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200 shadow-sm">
                <i class="fas fa-plus ml-2"></i> إضافة مؤشر جديد
            </button>
            
            <div class="flex items-center gap-3 bg-white p-2 rounded-lg shadow-sm border border-gray-200">
                <span class="text-sm text-gray-600">الخطة السنوية:</span>
                <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1">
                    <option selected value="">2025</option>
                    <option value="">2024</option>
                    <option value="">2023</option>
                </select>
            </div>
        </div>

        <!-- Data Table Container -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <!-- Table Search -->
            <div class="p-4 border-b border-gray-200">
                <div class="relative max-w-xs">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input placeholder="بحث..." class="block w-full pr-10 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right text-gray-600">
                    <thead class="text-xs text-gray-700 bg-gray-100">
                        <tr>
                            <th scope="col" class="p-4 w-12">#</th>
                            <th scope="col" class="px-6 py-3 min-w-[120px]">نوع المؤشر</th>
                            <th scope="col" class="px-6 py-3 min-w-[150px]">الاسم</th>
                            <th scope="col" class="px-6 py-3 min-w-[200px]">البرنامج/الوحدة/القطاع</th>
                            <th scope="col" class="px-6 py-3 min-w-[120px]">الفترة الزمنية</th>
                            <th scope="col" class="px-6 py-3 min-w-[150px]">المراجعة</th>
                            <th scope="col" class="px-6 py-3 min-w-[150px]">الحالة</th>
                            <th scope="col" class="px-6 py-3 min-w-[100px]">عمليات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- Row 1 -->
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="p-4">1</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                مبادرة تمكينية
                            </td>
                            <td class="px-6 py-4">رضاكم</td>
                            <td class="px-6 py-4 space-y-1">
                                <p class="text-gray-900 font-medium">لوحة مؤشرات أداء وزارة الأوقاف</p>
                                <p class="text-sm">وزارة الأوقاف والشؤون الدينية</p>
                                <p class="text-xs text-gray-500">المديرية العامة للتخطيط</p>
                                <p class="text-xs text-gray-500">دائرة التخطيط والاحصاء</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>من 1-1-2025</div>
                                <div>إلى 31-12-2025</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">تم الأعتماد النهائي</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-yellow-600">في الإجراء</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500">0%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 space-y-1">
                                <a href="/projects/show" class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                    عرض
                                </a>
                            </td>
                        </tr>

                        <!-- Row 2 -->
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="p-4">2</td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                مبادرة تمكينية
                            </td>
                            <td class="px-6 py-4">مبادرة ورش تخطيط الوزارة</td>
                            <td class="px-6 py-4 space-y-1">
                                <p class="text-gray-900 font-medium">لوحة مؤشرات أداء وزارة الأوقاف</p>
                                <p class="text-sm">وزارة الأوقاف والشؤون الدينية</p>
                                <p class="text-xs text-gray-500">المديرية العامة للتخطيط</p>
                                <p class="text-xs text-gray-500">دائرة التخطيط والاحصاء</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">من 1-1-2025 إلى 31-12-2025</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">تم الأعتماد النهائي</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-green-600">مكتمل</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500">100%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 space-y-1">
                                <a href="#" class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                    عرض
                                </a>
                                <a href="#" class="block w-full text-center text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                    الخطوات
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    عرض <span class="font-medium">1</span> إلى <span class="font-medium">2</span> من <span class="font-medium">2</span> نتائج
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        السابق
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        1
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        التالي
                    </button>
                </div>
            </div>
        </div>

        <!-- Enhanced Modal -->
        <div x-show="open" @keydown.escape.window="open = false" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
            style="display: none;">
            
            <div class="bg-white w-full max-w-3xl rounded-lg shadow-xl overflow-hidden" x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-white p-4">
                    <h3 class="text-lg font-bold">إضافة مؤشر جديد</h3>
                </div>
                
                <!-- Tab Navigation -->
                <div class="flex border-b">
                    <button @click="activeTab = 'tab1'"
                        :class="{ 'border-b-2 border-green-600 text-green-600': activeTab === 'tab1', 'text-gray-600 hover:text-gray-800': activeTab !== 'tab1' }"
                        class="py-3 px-6 focus:outline-none transition-colors duration-200 font-medium">
                        البيانات الأساسية
                    </button>
                    <button @click="activeTab = 'tab2'"
                        :class="{ 'border-b-2 border-green-600 text-green-600': activeTab === 'tab2', 'text-gray-600 hover:text-gray-800': activeTab !== 'tab2' }"
                        class="py-3 px-6 focus:outline-none transition-colors duration-200 font-medium">
                        بطاقة المؤشرات
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="p-6 max-h-[70vh] overflow-y-auto">
                    <!-- البيانات الأساسية -->
                    <div x-show="activeTab === 'tab1'" x-transition>
                        <h3 class="mb-4 text-center text-green-700 text-lg font-bold">وزارة الأوقاف والشؤون الدينية</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">القطاع</label>
                                <select class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                    <option value="" disabled selected>اختر القطاع</option>
                                    <option>المديرية العامة للتخطيط</option>
                                    <option>المديرية العامة للأوقاف</option>
                                </select>
                            </div>
                            
                            <div x-data="{ open: false, selected: [] }" class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">الدائرة</label>
                                <div @click="open = !open" class="w-full cursor-pointer flex flex-wrap items-center gap-1 border border-gray-300 rounded px-3 py-2 min-h-[42px] bg-gray-50">
                                    <template x-if="selected.length === 0">
                                        <span class="text-gray-400 text-sm">اختر الدوائر</span>
                                    </template>
                                    <template x-for="item in selected" :key="item">
                                        <span class="bg-blue-100 text-blue-800 text-xs rounded-full px-2 py-1 flex items-center">
                                            <span x-text="item"></span>
                                            <button @click.stop="selected = selected.filter(i => i !== item)" class="ml-1 text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </span>
                                    </template>
                                    <span class="ml-auto text-gray-400">
                                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'transform rotate-180': open }"></i>
                                    </span>
                                </div>
                                
                                <div x-show="open" @click.outside="open = false" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto">
                                    <div class="p-2 border-b border-gray-200 bg-gray-50">
                                        <div class="relative">
                                            <input type="text" placeholder="ابحث..." class="w-full pl-8 pr-3 py-1 text-sm border border-gray-300 rounded">
                                            <i class="fas fa-search absolute left-2 top-2 text-gray-400"></i>
                                        </div>
                                    </div>
                                    <div class="divide-y divide-gray-200">
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-50 cursor-pointer">
                                            <input type="checkbox" value="دائرة التخطيط" @change="(e) => {
                                                if(e.target.checked) selected.push(e.target.value);
                                                else selected = selected.filter(i => i !== e.target.value);
                                            }" :checked="selected.includes('دائرة التخطيط')" class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">دائرة التخطيط</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-50 cursor-pointer">
                                            <input type="checkbox" value="دائرة الحوكمة" @change="(e) => {
                                                if(e.target.checked) selected.push(e.target.value);
                                                else selected = selected.filter(i => i !== e.target.value);
                                            }" :checked="selected.includes('دائرة الحوكمة')" class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">دائرة الحوكمة</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">القسم</label>
                                <select class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                    <option value="" disabled selected>اختر القسم</option>
                                    <option>قسم التخطيط الاستراتيجي</option>
                                    <option>قسم الإحصاء</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">نوع المشروع</label>
                            <select class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                <option value="" disabled selected>اختر نوع المشروع</option>
                                <option>مشروع</option>
                                <option>مبادرة تمكينية</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                            <input type="text" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5" placeholder="أدخل اسم المؤشر">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                            <textarea rows="4" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5" placeholder="أدخل وصف المؤشر"></textarea>
                        </div>
                    </div>

                    <!-- بطاقة المؤشرات -->
                    <div x-show="activeTab === 'tab2'" x-transition>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">المؤشرات</label>
                            <select class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                <option value="" disabled selected>اختر المؤشر</option>
                                <option value="زكاة">رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي</option>
                                <option value="مساجد">عدد الجوامع والمساجد ومدارس القرآن الكريم التي تغطي مصاريف الخدمات الأساسية</option>
                                <option value="تعليم">عدد متعلمي القرآن الكريم</option>
                                <option value="أصول_وقفية">قيمة الأصول الوقفية الجديدة سنويًا</option>
                                <option value="أنشطة_دينية">زيادة نسبة المستفيدين من الأنشطة الدينية وخدمات الإفتاء</option>
                                <option value="رضا">رفع نسبة رضا المستفيدين عن الخدمات المقدمة من الوزارة</option>
                                <option value="تسامح">عدد المستفيدين من برامج تعزيز قيم التسامح والتعايش والمؤتلف الإنساني (دولياً)</option>
                                <option value="هوية_وطنية">عدد المستفيدين من برامج تعزيز الهوية الوطنية (محلياً)</option>
                                <option value="بيت_المال">زيادة نسبة إيرادات أصول بيت المال</option>
                                <option value="أصول">زيادة نسبة إيرادات الأصول</option>
                            </select>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">تفاصيل المؤشر</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">نوع المؤشر</label>
                                    <p class="text-sm font-medium">كمي</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">وحدة القياس</label>
                                    <p class="text-sm font-medium">نسبة مئوية</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">الاتجاه</label>
                                    <p class="text-sm font-medium">تصاعدي</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">الوزن النسبي</label>
                                    <p class="text-sm font-medium">15%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-3 flex justify-end border-t border-gray-200">
                    <button @click="open = false" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2">
                        إلغاء
                    </button>
                    <button @click="open = false" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        حفظ المؤشر
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
                this.$watch('activeTab', value => {
                    setTimeout(() => {
                        const input = this.$root.querySelector(`[x-show="activeTab === '${value}'"] input`);
                        if (input) {
                            input.focus();
                        }
                    }, 100);
                });
            }
        }));
    });
</script>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">