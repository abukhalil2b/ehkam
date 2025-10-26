<x-app-layout>

    <div class="container py-6 mx-auto px-4 sm:px-6">
        <!-- Quick Stats Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

            @foreach ($indicators as $indicator)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="text-purple-600 mb-1"><i class="fas fa-users"></i></div>
                <div class="text-xl text-gray-500">
                    {{ $indicator->title }}
                </div>

                <div class="mt-5 flex gap-2 text-[10px]">
                  
              
                </div>
            </div>
            @endforeach

        </div>

        <!-- Main Dashboard -->
        <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 hover:shadow-sm transition-shadow">
            <!-- Header with Actions -->
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">لمحة سريعة عن الأداء</h2>
                    <p class="text-sm text-gray-400 mt-1">نظرة عامة على تقدم المشروع والتنبيهات</p>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <button
                        class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-download ml-1 rtl:mr-1 rtl:ml-0"></i> تصدير
                    </button>
                    <button
                        class="px-3 py-1.5 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus ml-1 rtl:mr-1 rtl:ml-0"></i> إضافة تقرير
                    </button>
                </div>
            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-green-50 p-4 rounded-xl border border-green-100 hover:shadow-xs transition">
                    <div class="flex justify-between">
                        <span class="text-green-800 text-sm font-medium">صحة المشروع</span>
                        <span class="text-green-600 text-xs">منجز</span>
                    </div>
                    <p class="text-2xl font-bold text-green-900 mt-1">78%</p>
                    <div class="h-1.5 w-full bg-green-200 rounded-full mt-2">
                        <div class="h-1.5 bg-green-500 rounded-full" style="width: 78%"></div>
                    </div>
                </div>

                <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 hover:shadow-xs transition">
                    <div class="flex justify-between">
                        <span class="text-amber-800 text-sm font-medium">تنبيهات نشطة</span>
                        <span class="text-amber-600 text-xs">تصعيد</span>
                    </div>
                    <p class="text-2xl font-bold text-amber-900 mt-1">12</p>
                    <div class="h-1.5 w-full bg-amber-200 rounded-full mt-2">
                        <div class="h-1.5 bg-amber-500 rounded-full" style="width: 60%"></div>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 hover:shadow-xs transition">
                    <div class="flex justify-between">
                        <span class="text-blue-800 text-sm font-medium">الوقت المتبقي للتسليم</span>
                        <span class="text-blue-600 text-xs">على المسار</span>
                    </div>
                    <p class="text-2xl font-bold text-blue-900 mt-1">42 يوم</p>
                    <div class="h-1.5 w-full bg-blue-200 rounded-full mt-2">
                        <div class="h-1.5 bg-blue-500 rounded-full" style="width: 65%"></div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Charts Section -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Project Status Chart -->
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 hover:shadow-xs transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-700 flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full ml-2 rtl:mr-2 rtl:ml-0"></span>
                                الخطوات - الحالة
                            </h3>

                        </div>
                        <div>التحضير</div>
                        <div>التخطيط والتطوير</div>
                        <div>التنفيذ</div>
                        <div>المراجعة</div>
                        <div>الاعتماد والإغلاق</div>
                        <div id="projectStatusContainer" class="h-60 flex items-center justify-center">
                            <!-- Chart.js will render here -->
                            <canvas id="projectStatusChart"></canvas>
                        </div>
                        <div class="flex justify-center mt-3 space-x-4 rtl:space-x-reverse">
                            <button class="text-xs text-blue-600 hover:text-blue-800 hover:underline">عرض
                                التفاصيل</button>
                            <button class="text-xs text-gray-500 hover:text-gray-700 hover:underline">مقارنة بالفترة
                                الماضية</button>
                        </div>
                    </div>

                    <!-- Flag Status Chart -->
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 hover:shadow-xs transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-700 flex items-center">
                                <span class="w-2 h-2 bg-amber-500 rounded-full ml-2 rtl:mr-2 rtl:ml-0"></span>
                                حالة التنبيهات
                            </h3>
                            <select
                                class="text-xs border border-gray-200 rounded-lg px-2 py-1 bg-white hover:border-gray-300 transition">
                                <option>جميع الفئات</option>
                                <option>حرجة</option>
                                <option>متوسطة</option>
                                <option>منخفضة</option>
                            </select>
                        </div>
                        <div id="flagContainer" class="h-60 flex items-center justify-center">
                            <!-- Chart.js will render here -->
                            <canvas id="flagStatusChart"></canvas>
                        </div>
                        <div class="flex justify-center mt-3 space-x-4 rtl:space-x-reverse">
                            <button class="text-xs text-blue-600 hover:text-blue-800 hover:underline">عرض الكل</button>
                            <button class="text-xs text-gray-500 hover:text-gray-700 hover:underline">إدارة
                                التنبيهات</button>
                        </div>
                    </div>
                </div>

                <!-- Legend & Actions -->
                <div class="w-full lg:w-80 xl:w-96 space-y-6">
                    <!-- Color Legend -->
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 hover:shadow-xs transition">
                        <h3 class="text-lg font-medium text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-palette text-gray-400 ml-2 rtl:mr-2 rtl:ml-0 text-sm"></i>
                            مفتاح الألوان
                        </h3>
                        <div class="space-y-3">
                            <div
                                class="flex items-center p-3 hover:bg-white rounded-lg transition-colors cursor-pointer group">
                                <div
                                    class="w-3 h-3 rounded-full bg-red-500 mr-3 rtl:ml-3 rtl:mr-0 group-hover:scale-125 transition-transform">
                                </div>
                                <span class="text-gray-700 flex-1">تأخر > 8 أسابيع</span>
                                <span class="text-red-500 text-xs font-medium bg-red-50 px-2 py-0.5 rounded">12
                                    مشروع</span>
                            </div>
                            <div
                                class="flex items-center p-3 hover:bg-white rounded-lg transition-colors cursor-pointer group">
                                <div
                                    class="w-3 h-3 rounded-full bg-yellow-400 mr-3 rtl:ml-3 rtl:mr-0 group-hover:scale-125 transition-transform">
                                </div>
                                <span class="text-gray-700 flex-1">تأخر 6-8 أسابيع</span>
                                <span class="text-yellow-500 text-xs font-medium bg-yellow-50 px-2 py-0.5 rounded">8
                                    مشاريع</span>
                            </div>
                            <div
                                class="flex items-center p-3 hover:bg-white rounded-lg transition-colors cursor-pointer group">
                                <div
                                    class="w-3 h-3 rounded-full bg-green-500 mr-3 rtl:ml-3 rtl:mr-0 group-hover:scale-125 transition-transform">
                                </div>
                                <span class="text-gray-700 flex-1">وفق الخطة</span>
                                <span class="text-green-500 text-xs font-medium bg-green-50 px-2 py-0.5 rounded">32
                                    مشروع</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-blue-50 p-5 rounded-xl border border-blue-200 hover:shadow-xs transition">
                        <h3 class="text-lg font-medium text-blue-700 mb-4 flex items-center">
                            <i class="fas fa-bolt text-blue-400 ml-2 rtl:mr-2 rtl:ml-0 text-sm"></i>
                            إجراءات سريعة
                        </h3>
                        <div class="space-y-2">
                            <button
                                class="w-full flex items-center justify-between p-3 hover:bg-blue-100 rounded-lg transition-colors text-blue-800">
                                <span>إنشاء تقرير جديد</span>
                                <i class="fas fa-file-alt"></i>
                            </button>
                            <button
                                class="w-full flex items-center justify-between p-3 hover:bg-blue-100 rounded-lg transition-colors text-blue-800">
                                <span>إرسال تنبيه للفريق</span>
                                <i class="fas fa-bell"></i>
                            </button>
                            <button
                                class="w-full flex items-center justify-between p-3 hover:bg-blue-100 rounded-lg transition-colors text-blue-800">
                                <span>جدولة اجتماع</span>
                                <i class="fas fa-calendar-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Performance Summary -->
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-xs hover:shadow-sm transition">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-700">ملخص الأداء</h4>
                            <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            %78 من المشاريع تسير بشكل جيد، مع %20 بحاجة إلى انتباه
                        </p>
                        <div class="flex items-center text-xs text-gray-500">
                            <i class="fas fa-clock ml-1 rtl:mr-1 rtl:ml-0"></i>
                            <span>تم التحديث قبل 2 ساعة</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Updates Section -->
        <div class="grid md:grid-cols-3 gap-6 mt-6">
            <div class="bg-white p-5 rounded-xl border border-gray-200 hover:shadow-xs transition">
                <div class="border-r-4 rtl:border-l-4 rtl:border-r-0 border-blue-500 pr-4 rtl:pl-4 rtl:pr-0 py-1">
                    <h3 class="font-medium text-gray-800">رفع نسبة رضا المستفيدين عن الخدمات المقدمة من الوزارة</h3>
                    <p class="text-sm text-gray-600 mt-2"> تم تشكيل فريق عمل مشروع رضاكم</p>
                    <div class="flex items-center mt-3">
                        <span class="text-xs text-gray-500 mr-auto rtl:ml-auto rtl:mr-0">منذ يومين</span>
                    </div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200 hover:shadow-xs transition">
                <div class="border-r-4 rtl:border-l-4 rtl:border-r-0 border-green-500 pr-4 rtl:pl-4 rtl:pr-0 py-1">
                    <h3 class="font-medium text-gray-800">رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي</h3>
                    <p class="text-sm text-gray-600 mt-2"> تم رفع صور كدليل داعم </p>
                    <div class="flex items-center mt-3">
                        <span class="text-xs text-gray-500 mr-auto rtl:ml-auto rtl:mr-0">منذ أسبوع</span>
                    </div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200 hover:shadow-xs transition">
                <div class="border-r-4 rtl:border-l-4 rtl:border-r-0 border-purple-500 pr-4 rtl:pl-4 rtl:pr-0 py-1">
                    <h3 class="font-medium text-gray-800">عدد الجوامع والمساجد ومدارس القرآن الكريم التي تغطي مصاريف
                        الخدمات الأساسية</h3>
                    <p class="text-sm text-gray-600 mt-2">تم رفع محضر الإجتماع</p>
                    <div class="flex items-center mt-3">
                        <span class="text-xs text-gray-500 mr-auto rtl:ml-auto rtl:mr-0">منذ 3 أيام</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Implementation -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Project Status Chart
        const projectCtx = document.getElementById('projectStatusChart').getContext('2d');
        new Chart(projectCtx, {
            type: 'doughnut',
            data: {
                labels: ['وفق الخطة', 'متأخر 6-8 أسابيع', 'متأخر >8 أسابيع'],
                datasets: [{
                    data: [65, 23, 12],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '75%',
                plugins: {
                    legend: false
                },
                animation: {
                    duration: 1500
                }
            }
        });

        // Flag Status Chart
        const flagCtx = document.getElementById('flagStatusChart').getContext('2d');
        new Chart(flagCtx, {
            type: 'bar',
            data: {
                labels: ['حرجة', 'متوسطة', 'منخفضة'],
                datasets: [{
                    data: [8, 10, 6],
                    backgroundColor: ['#EF4444', '#F59E0B', '#10B981'],
                    borderRadius: 6
                }]
            },
            options: {
                plugins: {
                    legend: false
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                animation: {
                    duration: 1500
                }
            }
        });
    </script>
</x-app-layout>
