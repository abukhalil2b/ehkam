<x-app-layout>
    <div class="container py-8 mx-auto px-4 max-w-7xl">
        
        <div class="p-6 bg-gradient-to-r from-blue-900 to-blue-700 rounded-xl shadow-lg text-white mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div>
                    <span class="text-blue-200 text-sm font-medium">المؤشر الاستراتيجي</span>
                    <h1 class="text-2xl md:text-3xl font-bold mt-1">{{ $project->indicator->title }}</h1>
                </div>
                <div class="mt-4 md:mt-0 text-center md:text-left bg-white/10 p-4 rounded-lg backdrop-blur-md">
                    <p class="text-sm opacity-80">مستهدف عام {{ $current_year }}</p>
                    <p class="text-3xl font-black">{{ number_format($project->indicator->target_for_indicator) }}</p>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-100">
                <i class="far fa-calendar-alt ml-2"></i>
                <span>دورية القياس: {{ __($project->indicator->period) }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-800">{{ $project->title }}</h2>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">مبادرة تمكينية</span>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">وصف المشروع</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $project->description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <span class="text-xs text-gray-500 block">نسبة الإنجاز</span>
                                <div class="flex items-center mt-1">
                                    <span class="text-lg font-bold text-blue-600">{{ $completionPercentage }}%</span>
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full mr-3">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $completionPercentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <span class="text-xs text-gray-500 block">الحالة التشغيلية</span>
                                <span class="text-yellow-600 font-bold mt-1 inline-block">في الإجراء</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700"><i class="fas fa-tasks ml-2 text-blue-500"></i>أنشطة المشروع</h3>
                        <span class="text-xs bg-gray-200 px-2 py-1 rounded text-gray-600">{{ $project->activities->count() }} نشاط</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase">
                                    <th class="p-4 font-semibold">النشاط</th>
                                    <th class="p-4 font-semibold">تغذية المؤشر</th>
                                    <th class="p-4 font-semibold">الحالة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($project->activities as $activity)
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="p-4 font-medium text-gray-800">{{ $activity->title }}</td>
                                    <td class="p-4">
                                        @if($activity->is_feed_indicator)
                                            <span class="text-green-600 text-xs flex items-center">
                                                <i class="fas fa-link ml-1"></i> مباشر
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs flex items-center">
                                                <i class="fas fa-info-circle ml-1"></i> دعم
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-xs text-gray-500 italic">بانتظار التقارير</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="p-8 text-center text-gray-400 italic">لا توجد أنشطة مضافة لهذا المشروع</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="space-y-6">
                <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-800 border-b pb-4 mb-4 flex items-center">
                        <i class="fas fa-id-badge ml-2 text-blue-600"></i> الجهات المالكة
                    </h3>
                    <div class="space-y-3">
                        @forelse($project->owners as $owner)
                            <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs ml-3">
                                    <i class="fas fa-building"></i>
                                </div>
                                <span class="text-sm font-semibold text-blue-900 leading-tight">
                                    {{ $owner->organizational_unit_id ?? 'وحدة غير معرفة' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-400 text-sm italic">لم يتم تعيين ملاك</p>
                        @endforelse
                    </div>
                </section>

                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start">
                    <i class="fas fa-certificate text-green-600 mt-1 ml-3"></i>
                    <div>
                        <p class="text-xs font-bold text-green-800">حالة الاعتماد</p>
                        <p class="text-xs text-green-700">تم الأعتماد النهائي من قبل فريق تقييم الأداء الاستراتيجي</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>