<x-app-layout>
    <!-- Enhanced Header with Gradient -->
    <header class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-2xl text-white font-bold p-4 shadow-md">
        <h1 class="container mx-auto px-4">تفاصيل المؤشر</h1>
    </header>

    <div x-data="modalComponent()" class="container py-8 mx-auto px-4">
        <!-- Indicator Details Card -->
        <section class="p-6 bg-white rounded-lg shadow-md border border-gray-200 mb-6">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-xl font-bold text-green-700">رفع نسبة رضا المستفيدين عن الخدمات المقدمة من الوزارة</h2>
                <a href="#" class="flex items-center text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                    <i class="fas fa-edit ml-2"></i> تعديل البيانات
                </a>
            </div>

            <!-- Grid Layout for Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Indicator Type -->
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">نوع المشروع</h3>
                    <p class="font-medium text-gray-900">مبادرة تمكينية</p>
                </div>

                <!-- Name -->
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">الاسم</h3>
                    <p class="font-medium text-gray-900"></p>
                </div>

                <!-- Description -->
                <div class="md:col-span-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">الوصف</h3>
                    <p class="font-medium text-gray-900">
                        مشروع يهدف إلى تحسين تجربة المستفيدين من خلال توعيتهم بالخدمات المقدمة ، وتطوير استمارات تقييم
                        شاملة كما يتم تحليل مؤشرات الرضا بناء على التقييمات بما يعزز جودة الخدمات يشمل المشروع ايضا
                        دراسة لتحديد نقاط التحسين وتقديم توصيات استراتيجية لرفع مستوى الرضا.
                    </p>
                </div>

                <!-- Unit -->
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">الوحدة</h3>
                    <p class="font-medium text-gray-900">وزارة الأوقاف والشؤون الدينية</p>
                </div>

                <!-- Sector -->
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">القطاع</h3>
                    <p class="font-medium text-gray-900">المديرية العامة للتخطيط</p>
                </div>

                <!-- Sub-sector -->
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">قطاع فرعي</h3>
                    <p class="font-medium text-gray-900">دائرة التخطيط والاحصاء</p>
                </div>

                <!-- Time Period -->
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-1 flex items-center">
                        <i class="far fa-clock ml-1"></i> الفترة الزمنية
                    </h3>
                    <div class="font-medium text-gray-900 space-y-1">
                        <div>من 1-1-2025</div>
                        <div>إلى 31-12-2025</div>
                    </div>
                </div>

                <!-- Review -->
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">المراجعة</h3>
                    <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        <i class="fas fa-check-circle mr-1"></i>
                        تم الأعتماد النهائي (فريق تقييم الأداء)
                    </span>
                </div>

                <!-- Status -->
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">الحالة</h3>
                    <div class="space-y-2">
                        <span class="text-yellow-600 font-medium">في الإجراء</span>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Completion -->
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">نسبة الإنجاز</h3>
                    <div class="space-y-2">
                        <span class="font-medium text-gray-900">5٪</span>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 5%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Work Steps Section -->
        <section class="p-6 bg-white rounded-lg shadow-md border border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <h2 class="text-xl font-bold text-green-700 mb-4 sm:mb-0">خطوات العمل</h2>
                <button @click="open = true" type="button"
                    class="flex items-center text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 px-4 py-2 rounded-lg text-sm transition-colors duration-200 shadow-sm">
                    <i class="fas fa-plus ml-2"></i> إضافة خطوة جديدة
                </button>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right text-gray-600">
                    <thead class="text-xs text-gray-700 bg-gray-100">
                        <tr>
                            <th scope="col" class="p-4 w-12">#</th>
                            <th scope="col" class="px-6 py-3 min-w-[200px]">الاسم</th>
                            <th scope="col" class="px-6 py-3 min-w-[100px]">من</th>
                            <th scope="col" class="px-6 py-3 min-w-[100px]">إلى</th>
                            <th scope="col" class="px-6 py-3 min-w-[120px]">المستهدف</th>
                            <th scope="col" class="px-6 py-3 min-w-[150px]">الحالة</th>
                            <th scope="col" class="px-6 py-3 min-w-[100px]">عدد المهام المسندة</th>
                            <th scope="col" class="px-6 py-3 min-w-[100px]">عمليات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- Preparation Phase -->
                        <tr>
                            <td colspan="8" class="p-0">
                                <div class="w-full p-3 text-white text-lg font-medium bg-blue-600 flex justify-between items-center">
                                    <span>التحضير</span>
                                    <span class="bg-white text-blue-600 px-2 py-1 rounded text-xs font-bold">
                                        الوزن: 15%
                                    </span>
                                </div>
                            </td>
                        </tr>

                        <!-- Step 1 -->
                        <tr class="bg-red-50 hover:bg-gray-50 transition-colors">
                            <td class="p-4">1</td>
                            <td class="px-6 py-4 font-medium text-gray-900">إعداد قاعدة بيانات الأنظمة الألكترونية والخدمات</td>
                            <td class="px-6 py-4 whitespace-nowrap">01-01-2025</td>
                            <td class="px-6 py-4 whitespace-nowrap">29-03-2025</td>
                            <td class="px-6 py-4">12.54%</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    متأخر
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                0
                            </td>
                            <td class="px-6 py-4">
                                <a href="#" class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                    عرض
                                </a>
                            </td>
                        </tr>

                        <!-- Step 2 -->
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="p-4">2</td>
                            <td class="px-6 py-4 font-medium text-gray-900">تشكيل فريق عمل مشروع رضاكم</td>
                            <td class="px-6 py-4 whitespace-nowrap">01-01-2025</td>
                            <td class="px-6 py-4 whitespace-nowrap">29-03-2025</td>
                            <td class="px-6 py-4">12.54%</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    متأخر
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                0
                            </td>
                            <td class="px-6 py-4">
                                <a href="#" class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                    عرض
                                </a>
                            </td>
                        </tr>

                        <!-- Planning Phase -->
                        <tr>
                            <td colspan="8" class="p-0">
                                <div class="w-full p-3 text-white text-lg font-medium bg-blue-600 flex justify-between items-center">
                                    <span>التخطيط والتطوير</span>
                                    <span class="bg-white text-blue-600 px-2 py-1 rounded text-xs font-bold">
                                        الوزن: 20%
                                    </span>
                                </div>
                            </td>
                        </tr>

                        <!-- Step 3 -->
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="p-4">3</td>
                            <td class="px-6 py-4 font-medium text-gray-900">إعداد خطة عمل مشروع رضاكم</td>
                            <td class="px-6 py-4 whitespace-nowrap">01-01-2025</td>
                            <td class="px-6 py-4 whitespace-nowrap">29-03-2025</td>
                            <td class="px-6 py-4">12.54%</td>
                            <td class="px-6 py-4 space-y-1">
                                <span class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    متأخر
                                </span>
                                <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    معتمد (ممثل القطاع)
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                0
                            </td>
                            <td class="px-6 py-4">
                                <a href="#" class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                    عرض
                                </a>
                            </td>
                        </tr>

                        <!-- Step 4 -->
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="p-4">4</td>
                            <td class="px-6 py-4 font-medium text-gray-900">جمع البيانات والاحصائيات عبر بيانات الخدمات وتجربة المستخدم</td>
                            <td class="px-6 py-4 whitespace-nowrap">01-01-2025</td>
                            <td class="px-6 py-4 whitespace-nowrap">29-03-2025</td>
                            <td class="px-6 py-4">12.54%</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-spinner mr-1"></i>
                                    في الإجراء
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                0
                            </td>
                            <td class="px-6 py-4">
                                <a href="#" class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                    عرض
                                </a>
                            </td>
                        </tr>

                        <!-- Implementation Phase -->
                        <tr>
                            <td colspan="8" class="p-0">
                                <div class="w-full p-3 text-white text-lg font-medium bg-blue-600 flex justify-between items-center">
                                    <span>التنفيذ</span>
                                    <span class="bg-white text-blue-600 px-2 py-1 rounded text-xs font-bold">
                                        الوزن: 30%
                                    </span>
                                </div>
                            </td>
                        </tr>

                        <!-- Step 5 -->
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="p-4">5</td>
                            <td class="px-6 py-4 font-medium text-gray-900">متابعة فريق التحول الرقمي عملية تبسيط الإجراءات</td>
                            <td class="px-6 py-4 whitespace-nowrap">01-01-2025</td>
                            <td class="px-6 py-4 whitespace-nowrap">29-03-2025</td>
                            <td class="px-6 py-4">12.54%</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="far fa-clock mr-1"></i>
                                    لم يبدأ
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                0
                            </td>
                            <td class="px-6 py-4">
                                <a href="#" class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                    عرض
                                </a>
                            </td>
                        </tr>

                        <!-- Review Phase -->
                        <tr>
                            <td colspan="8" class="p-0">
                                <div class="w-full p-3 text-white text-lg font-medium bg-blue-600 flex justify-between items-center">
                                    <span>المراجعة</span>
                                    <span class="bg-white text-blue-600 px-2 py-1 rounded text-xs font-bold">
                                        الوزن: 20%
                                    </span>
                                </div>
                            </td>
                        </tr>

                        <!-- Approval Phase -->
                        <tr>
                            <td colspan="8" class="p-0">
                                <div class="w-full p-3 text-white text-lg font-medium bg-blue-600 flex justify-between items-center">
                                    <span>الاعتماد والإغلاق</span>
                                    <span class="bg-white text-blue-600 px-2 py-1 rounded text-xs font-bold">
                                        الوزن: 15%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Enhanced Modal -->
        <div x-show="open" @keydown.escape.window="open = false" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
            style="display: none;">
            
            <div class="bg-white w-full max-w-2xl rounded-lg shadow-xl overflow-hidden" x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-white p-4">
                    <h3 class="text-lg font-bold">إضافة خطوة عمل جديدة</h3>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                        <input type="text" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5" placeholder="أدخل اسم الخطوة">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">من</label>
                            <input type="date" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">إلى</label>
                            <input type="date" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">المرحلة</label>
                        <select class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                            <option value="" disabled selected>اختر المرحلة</option>
                            <option value="1">التحضير</option>
                            <option value="2">التخطيط والتطوير</option>
                            <option value="3">التنفيذ</option>
                            <option value="4">المراجعة</option>
                            <option value="5">الإعتماد والاغلاق</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الوثائق الداعمة</label>
                        <textarea rows="3" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5" placeholder="أدخل الوثائق الداعمة"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-3 flex gap-2 justify-end border-t border-gray-200">
                    <button @click="open = false" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2">
                        إلغاء
                    </button>
                    <button @click="open = false" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        حفظ الخطوة
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
            open: false
        }));
    });
</script>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">