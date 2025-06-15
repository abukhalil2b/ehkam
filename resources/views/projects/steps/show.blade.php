<x-app-layout>
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
                            <div
                                class="w-full p-3 text-white text-lg font-medium bg-blue-600 flex justify-between items-center">
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
                        <td class="px-6 py-4 font-medium text-gray-900">إعداد قاعدة بيانات الأنظمة الألكترونية والخدمات
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">01-01-2025</td>
                        <td class="px-6 py-4 whitespace-nowrap">29-03-2025</td>
                        <td class="px-6 py-4">12.54%</td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                متأخر
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            0
                        </td>
                        <td class="px-6 py-4">
                            <a href="#"
                                class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
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
                            <span
                                class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                متأخر
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            0
                        </td>
                        <td class="px-6 py-4">
                            <a href="#"
                                class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                عرض
                            </a>
                        </td>
                    </tr>

                    <!-- Planning Phase -->
                    <tr>
                        <td colspan="8" class="p-0">
                            <div
                                class="w-full p-3 text-white text-lg font-medium bg-blue-600 flex justify-between items-center">
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
                            <span
                                class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                متأخر
                            </span>
                            <span
                                class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i>
                                معتمد (ممثل القطاع)
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            0
                        </td>
                        <td class="px-6 py-4">
                            <a href="#"
                                class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                عرض
                            </a>
                        </td>
                    </tr>

                    <!-- Step 4 -->
                    <tr class="bg-white hover:bg-gray-50 transition-colors">
                        <td class="p-4">4</td>
                        <td class="px-6 py-4 font-medium text-gray-900">جمع البيانات والاحصائيات عبر بيانات الخدمات
                            وتجربة المستخدم</td>
                        <td class="px-6 py-4 whitespace-nowrap">01-01-2025</td>
                        <td class="px-6 py-4 whitespace-nowrap">29-03-2025</td>
                        <td class="px-6 py-4">12.54%</td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <i class="fas fa-spinner mr-1"></i>
                                في الإجراء
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            0
                        </td>
                        <td class="px-6 py-4">
                            <a href="#"
                                class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                عرض
                            </a>
                        </td>
                    </tr>

                    <!-- Implementation Phase -->
                    <tr>
                        <td colspan="8" class="p-0">
                            <div
                                class="w-full p-3 text-white text-lg font-medium bg-blue-600 flex justify-between items-center">
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
                        <td class="px-6 py-4 font-medium text-gray-900">متابعة فريق التحول الرقمي عملية تبسيط الإجراءات
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">01-01-2025</td>
                        <td class="px-6 py-4 whitespace-nowrap">29-03-2025</td>
                        <td class="px-6 py-4">12.54%</td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <i class="far fa-clock mr-1"></i>
                                لم يبدأ
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            0
                        </td>
                        <td class="px-6 py-4">
                            <a href="#"
                                class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                عرض
                            </a>
                        </td>
                    </tr>

                    <!-- Review Phase -->
                    <tr>
                        <td colspan="8" class="p-0">
                            <div
                                class="w-full p-3 text-white text-lg font-medium bg-blue-600 flex justify-between items-center">
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
                            <div
                                class="w-full p-3 text-white text-lg font-medium bg-blue-600 flex justify-between items-center">
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
</x-app-layout>


<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
