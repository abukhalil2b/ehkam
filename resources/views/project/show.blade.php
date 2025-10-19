<x-app-layout>
    <!-- Enhanced Header with Gradient -->
    <header class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b7a] text-2xl text-white font-bold p-4 shadow-md">
        <h1 class="container mx-auto px-4">تفاصيل المشروع</h1>
    </header>

    <div x-data="modalComponent()" class="container py-8 mx-auto px-4">
        <!-- Indicator Details Card -->
        <section class="p-6 bg-white rounded-lg shadow-md border border-gray-200 mb-6">
            <div class="flex justify-between items-start mb-4">
                 {{ $project->title }}
                 <p class="font-medium text-gray-900">مبادرة تمكينية</p>
            </div>

            <!-- Grid Layout for Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            

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
                    <span
                        class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
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



    </div>
</x-app-layout>
