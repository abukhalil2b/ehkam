<x-app-layout>
    <div class="bg-[#1e3d4f] text-2xl text-white font-bold p-4">
        <h1 class="container mx-auto">الصفحة الرئيسية</h1>
    </div>

    <div class="container py-8 mx-auto">

        <!-- Statistics Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

            <!-- General Statistics -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">إحصائيات عامة</h2>
                <div id="generalStatistics" class="flex flex-wrap gap-4">
                    <div class="text-center">
                        <span class="text-green-500 font-bold">المشاريع</span>
                        <h3 class="text-2xl font-bold">12</h3>
                    </div>
                    <div class="text-center">
                        <span class="text-yellow-500 font-bold">تحت الإجراء</span>
                        <h3 class="text-2xl font-bold">5</h3>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">إشعارات</h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li>تم تحديث تقرير الأداء الشهري</li>
                    <li>موعد اجتماع الفريق الفني يوم الإثنين</li>
                    <li>تقديم المشاريع الجديدة قبل نهاية الشهر</li>
                </ul>
            </div>

        </div>

        <!-- Project Status and Progress -->
        <div class="bg-white p-4 rounded-lg shadow-md mt-6">
            <h2 class="text-xl font-semibold mb-4">لمحة سريعة عن الأداء</h2>

            <div class="flex justify-between items-center">
                <div id="projectStatusContainer" class="w-1/3">
                    <!-- Pie Chart for Project Status -->
                </div>
                <div id="flagContainer" class="w-1/3">
                    <!-- Pie Chart for Flag Status -->
                </div>
                <div class="w-1/3">
                    <table class="table-auto border-collapse border border-gray-200 w-full">
                        <tbody>
                            <tr>
                                <td class="bg-red-500 w-6">&nbsp;</td>
                                <td>تأخر المشروع أكثر من 8 أسابيع</td>
                            </tr>
                            <tr>
                                <td class="bg-yellow-500 w-6">&nbsp;</td>
                                <td>تأخر المشروع من 6-8 أسابيع</td>
                            </tr>
                            <tr>
                                <td class="bg-green-500 w-6">&nbsp;</td>
                                <td>وفقا لمسار الخطة</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Updates Section -->
        <div class="bg-white p-4 rounded-lg shadow-md mt-6">
            <h2 class="text-xl font-semibold mb-4">المستجدات</h2>
            <ul class="space-y-2">
                <li>إطلاق منصة جديدة لتقديم التقارير</li>
                <li>توسيع فرق العمل في المشاريع المعتمدة</li>
                <li>إعلان جديد بشأن الاستراتيجية الوطنية</li>
            </ul>
        </div>
    </div>

</x-app-layout>
