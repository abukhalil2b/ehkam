<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <x-slot name="header">
        إدارة المشروعات لمؤشر:رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي
    </x-slot>

    <!-- Main Container -->
    <div class="container py-8 mx-auto px-4">
        <!-- Search Card with better spacing -->
        <div class="mb-6 p-4 bg-white rounded-lg shadow-md border border-gray-100">
            <div class="flex flex-col md:flex-row gap-4">
                <select
                    class="flex-grow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
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
            <a href="{{ route('project.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                إضافة مشروع جديد
            </a>
            <a href="{{ route('activity.index') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                الأنشطة
            </a>
            <div class="flex items-center gap-3 bg-white p-2 rounded-lg shadow-sm border border-gray-200">
                <span class="text-sm text-gray-600">الخطة السنوية:</span>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1">
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
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input placeholder="بحث..."
                        class="block w-full pr-10 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right text-gray-600">
                    <thead class="text-xs text-gray-700 bg-gray-100">
                        <tr>
                            <th scope="col" class="p-4 w-12">#</th>
                            <th scope="col" class="px-6 py-3 min-w-[120px]">نوع المشروع</th>
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
                        @foreach ($projects as $project)
                            <tr class="bg-white hover:bg-gray-50 transition-colors">
                                <td class="p-4">1</td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    مبادرة تمكينية
                                </td>
                                <td class="px-6 py-4">{{ $project->title }}</td>
                                <td class="px-6 py-4 space-y-1">
                                    <p class="text-gray-900 font-medium">لوحة مشروعات أداء وزارة الأوقاف</p>
                                    <p class="text-sm">وزارة الأوقاف والشؤون الدينية</p>
                                    <p class="text-xs text-gray-500">المديرية العامة للتخطيط</p>
                                    <p class="text-xs text-gray-500">دائرة التخطيط والاحصاء</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>من 1-1-2025</div>
                                    <div>إلى 31-12-2025</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">تم
                                        الأعتماد النهائي</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-yellow-600">في الإجراء</span>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 100%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500">10%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 space-y-1">
                                    <a href="{{ route('project.steps.show', $project->id) }}"
                                        class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                        عرض
                                    </a>
                                    <a href="{{ route('project.edit', $project->id) }}"
                                        class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                        تعديل
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>


    </div>
</x-app-layout>
