<x-app-layout>

    <!-- Main Container -->
    <div class="container py-2 mx-auto px-4">
       
        <div class="p-6 bg-white rounded-xl shadow space-y-4 border mb-8">
                <h1 class="text-xl md:text-2xl font-bold">
                    {{ $indicator->title }}
                </h1>


                <h2 class="text-xl font-bold text-gray-700">
                    بيانات المستهدف للمؤشر لعام
                    <span class="text-blue-700">{{ $currentYear }}</span>:
                    <span class="text-red-800">{{ number_format($indicator->target_for_indicator) }}</span>
                </h2>

                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <label class="font-semibold text-gray-700">دورية قياس
                        المستهدف:</label>
                    <span class="text-blue-700">{{ __($indicator->period) }}</span>
                </div>
            </div>

        <!-- Action Bar with better buttons -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <a href="{{ route('project.create',$indicator->id) }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                إضافة مشروع جديد
            </a>
            <a href="{{ route('activity.index',$currentYear) }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                الأنشطة
            </a>
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
                                <td class="px-6 py-4">
                                    {{ $project->title }}
                                    {{ $project->current_year }}
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
                                    <a href="{{ route('project.show', $project->id) }}"
                                        class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                        عرض
                                    </a>
                                    <a href="{{ route('step.index', $project->id) }}"
                                        class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                        الخطوات
                                    </a>
                                    <a href="{{ route('project.edit', $project->id) }}"
                                        class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                        تعديل
                                    </a>
                                    <a href="{{ route('admin.steps.import',$project->id) }}"
                                        class="block w-full text-center text-purple-700 hover:text-white border border-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-xs px-2 py-1 transition-colors duration-200">
                                        upload
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
