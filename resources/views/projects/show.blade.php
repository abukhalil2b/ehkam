<x-app-layout>
    <!-- Header -->
    <header class="bg-[#1e3d4f] text-2xl text-white font-bold p-4">
        <h1 class="container mx-auto">المؤشر</h1>
    </header>

    <div x-data="modalComponent()" class="container py-8 mx-auto">
        <!-- Card Container -->
        <section class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h2 class="text-lg font-semibold text-indigo-600 mb-2"> رفع نسبة رضا المستفيدين عن الخدمات المقدمة من الوزارة
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Indicator Type -->
                <div>
                    <span class="text-indigo-600">نوع المشروع:</span>
                    <span class="font-medium text-gray-900">مبادرة تمكينية</span>
                </div>
                <!-- Name -->
                <div>
                    <span class="text-indigo-600">الاسم:</span>
                    <span class="font-medium text-gray-900">رضاكم</span>
                </div>
                <!-- Description -->
                <div>
                    <span class="text-indigo-600">الوصف:</span>
                    <span class="font-medium text-gray-900">
                        مشروع يهدف إلى تحسين تجربة المستفيدين من خلال توعيتهم بالخدمات المقدمة ، وتطوير استمارات تقييم
                        شاملة كما يتم تحليل مؤشرات الرضا بناء على التقييمات بما يعزز جودة الخدمات يشمل المشروع ايضا
                        دراسة لتحديد نقاط التحسين وتقديم توصيات استراتيجية لرفع مستوى الرضا.
                    </span>
                </div>
                <!-- Unit -->
                <div>
                    <span class="text-indigo-600">الوحدة:</span>
                    <span class="font-medium text-gray-900">وزارة الأوقاف والشؤون الدينية</span>
                </div>
                <!-- Sector -->
                <div>
                    <span class="text-indigo-600">القطاع:</span>
                    <span class="font-medium text-gray-900">المديرية العامة للتخطيط</span>
                </div>
                <!-- Sub-sector -->
                <div>
                    <span class="text-indigo-600">قطاع فرعي:</span>
                    <span class="font-medium text-gray-900">دائرة التخطيط والاحصاء</span>
                </div>
                <!-- Time Period with Icon -->
                <div class="flex items-center">
                    <span class="text-indigo-600 flex items-center">
                        <!-- Clock Icon -->
                        <svg class="w-4 h-4 mr-1 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 9H9V7h2v4z" />
                        </svg>
                        الفترة الزمنية:
                    </span>
                    <div class="font-medium text-gray-900 ml-2">
                        <div>من 1-1-2025</div>
                        <div>إلى 31-12-2025</div>
                    </div>
                </div>
                <!-- Review -->
                <div>
                    <span class="text-indigo-600">المراجعة:</span>
                    <span class="font-medium bg-green-100 text-green-900">
                        تم الأعتماد النهائي (فريق تقييم الأداء)
                    </span>
                </div>
                <!-- Status with Progress Bar -->
                <div>
                    <span class="text-indigo-600">الحالة:</span>
                    <span class="font-medium text-gray-900">في الإجراء</span>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-1">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div>
                    <span class="text-indigo-600">نسبة الإنجاز:</span>
                    <span class="font-medium text-gray-900">5٪</span>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-1">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 5%"></div>
                    </div>
                </div>
            </div>
            <a class="mt-4 block w-32 text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                href="">تعديل البيانات</a>
        </section>

        <section class="mt-4 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex justify-between">
                <h2 class="text-lg font-semibold text-indigo-600 mb-2"> خطوات العمل </h2>
                <!-- Trigger Button -->
                <button @click="open = true" type="button"
                    class="w-32 mb-1 text-white bg-green-700 hover:bg-green-800 focus:ring-2 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                    + جديد
                </button>
            </div>


            <table class="w-full text-sm text-right text-gray-500">
                <thead class="text-xs text-gray-700 bg-gray-50">
                    <tr>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="px-6 py-3">الاسم</th>
                        <th scope="col" class="px-6 py-3">المدة</th>
                        <th scope="col" class="px-6 py-3">المستهدف</th>
                        <th scope="col" class="px-6 py-3">المراجعة</th>
                        <th scope="col" class="px-6 py-3">الحالة</th>
                        <th scope="col" class="px-6 py-3">عمليات</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7">
                            <div class="w-full p-3 text-blue-600 text-xl bg-blue-100 flex justify-between">
                                التحضير
                                <span class="bg-blue-600 text-white p-1 rounded text-xs">
                                    الوزن 15
                                </span>
                            </div>
                        </td>
                    </tr>

                    <tr class="bg-red-100 border-b border-gray-200 hover:bg-gray-50">
                        <td class="w-4 p-4">1</td>
                        <td class="px-6 py-4">إعدد قاعدة بيانات الأنظمة الألكترونية والخدمات</td>
                        <td class="px-6 py-4">
                            01-01-2025
                        </td>
                        <td class="px-6 py-4">
                            29-03-2025
                        </td>
                        <td class="px-6 py-4">
                            المستهدف : 12.54 %
                        </td>
                        <td class="px-6 py-4">
                            <p class="badge-red">متأخر</p>
                        </td>
                        <td class="px-6 py-4">
                            <a href=""
                                class="w-16 block text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs p-1 text-center mb-1">
                                عرض
                            </a>
                        </td>
                    </tr>

                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="w-4 p-4">1</td>
                        <td class="px-6 py-4">تشكيل فريق عمل مشروع رضاكم</td>
                        <td class="px-6 py-4">
                            01-01-2025
                        </td>
                        <td class="px-6 py-4">
                            29-03-2025
                        </td>
                        <td class="px-6 py-4">
                            المستهدف : 12.54 %
                        </td>
                        <td class="px-6 py-4">
                            متأخر
                        </td>
                        <td class="px-6 py-4">
                            <a href=""
                                class="w-16 block text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs p-1 text-center mb-1">
                                عرض
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="7">
                            <div class="w-full p-3 text-blue-600 text-xl bg-blue-100 flex justify-between">
                                التخطيط والتطوير
                                <span class="bg-blue-600 text-white p-1 rounded text-xs">
                                    الوزن 20
                                </span>
                            </div>
                        </td>
                    </tr>

                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="w-4 p-4">1</td>
                        <td class="px-6 py-4">إعداد خطة عمل مشروع رضاكم</td>
                        <td class="px-6 py-4">
                            01-01-2025
                        </td>
                        <td class="px-6 py-4">
                            29-03-2025
                        </td>
                        <td class="px-6 py-4">
                            المستهدف : 12.54 %
                        </td>
                        <td class="px-6 py-4">
                            <p class="badge-red">متأخر</p>
                            <p class="badge-green"> معتمد (ممثل القطاع)</p>
                        </td>
                        <td class="px-6 py-4">
                            <a href=""
                                class="w-16 block text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs p-1 text-center mb-1">
                                عرض
                            </a>
                        </td>
                    </tr>

                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="w-4 p-4">1</td>
                        <td class="px-6 py-4"> جمع البيانات والاحصائيات عبر بيانات الخدمات وتجربة المستخدم </td>
                        <td class="px-6 py-4">
                            01-01-2025
                        </td>
                        <td class="px-6 py-4">
                            29-03-2025
                        </td>
                        <td class="px-6 py-4">
                            المستهدف : 12.54 %
                        </td>
                        <td class="px-6 py-4">
                            <p class="badge-yellow">في الإجراء</p>
                        </td>
                        <td class="px-6 py-4">
                            <a href=""
                                class="w-16 block text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs p-1 text-center mb-1">
                                عرض
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="7">
                            <div class="w-full p-3 text-blue-600 text-xl bg-blue-100 flex justify-between">
                                التنفيذ
                                <span class="bg-blue-600 text-white p-1 rounded text-xs">
                                    الوزن 30
                                </span>
                            </div>
                        </td>
                    </tr>

                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="w-4 p-4">1</td>
                        <td class="px-6 py-4"> متابعة فريق التحول الرقمي عملية تبسيط الإجراءات </td>
                        <td class="px-6 py-4">
                            01-01-2025
                        </td>
                        <td class="px-6 py-4">
                            29-03-2025
                        </td>
                        <td class="px-6 py-4">
                            المستهدف : 12.54 %
                        </td>
                        <td class="px-6 py-4">
                            <p class="badge-default">لم يبدأ</p>
                        </td>
                        <td class="px-6 py-4">
                            <a href=""
                                class="w-16 block text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs p-1 text-center mb-1">
                                عرض
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="7">
                            <div class="w-full p-3 text-blue-600 text-xl bg-blue-100 flex justify-between">
                                المراجعة
                                <span class="bg-blue-600 text-white p-1 rounded text-xs">
                                    الوزن 20
                                </span>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="7">
                            <div class="w-full p-3 text-blue-600 text-xl bg-blue-100 flex justify-between">
                                الاعتماد والإغلاق
                                <span class="bg-blue-600 text-white p-1 rounded text-xs">
                                    الوزن 15
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
   

    <!-- Modal -->
    <div x-show="open" @keydown.escape.window="open = false" @click.away="open = false"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="display: none;">
        <div class="bg-white w-full max-w-3xl p-6 rounded-lg shadow-lg transition-transform transform scale-95"
            x-show="open" x-transition.scale>
  
            <!-- Content -->
           <div>
            <label class="block mb-1 text-xs text-blue-800">
                <span class="block">الاسم</span>
                <input type="text" class="form-input w-full" placeholder="أدخل الاسم">
            </label>
            المدة
            <div class="mb-2 flex gap-1">
                <label class="flex-1">
                    <span class="block text-xs text-blue-800">من</span>
                    <input type="date" class="form-input w-full">
                </label>
                <label class="flex-1">
                    <span class="block text-xs text-blue-800">إلى</span>
                    <input type="date" class="form-input w-full">
                </label>
            </div>
            <label class="block mb-1 text-xs text-blue-800">
                <span class="block">المرحلة</span>
                <select class="form-select">
                    <option value=""></option>
                    <option value="1">التحضير</option>
                    <option value="2">التخطيط والتطوير</option>
                    <option value="3">التنفيذ</option>
                    <option value="4">المراجعة</option>
                    <option value="5">الإعتماد والاغلاق</option>
                </select>
            </label>
            <label class="block mb-1 text-xs text-blue-800">
                <span class="block">الوثائق الداعمة</span>
                <textarea class="form-input w-full" rows="4"></textarea>
            </label>
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
            open: false
        }));
    });
</script>
