<x-app-layout>
    <x-slot name="header">
        تقرير شامل لتقييم المشاريع
    </x-slot>

    <div class="container py-8 mx-auto px-4 max-w-6xl">
        <h2 class="text-3xl font-bold mb-8 text-gray-800">تقرير أداء التقييم حسب المشروع</h2>

        @if ($reportData->isEmpty())
            <p class="text-gray-500">لا توجد بيانات مشاريع متاحة لعرض التقرير.</p>
        @else
            <div class="space-y-10">
                @foreach ($reportData as $project)
                    <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">
                        <div class="bg-purple-600 text-white p-5 flex justify-between items-center">
                            <h3 class="text-xl font-bold">{{ $project['project_title'] }}</h3>
                            <div class="text-2xl font-extrabold">
                                الأداء الكلي: 
                                <span class="bg-white text-purple-600 px-3 py-1 rounded-full">
                                    %{{ $project['total_percentage'] }}
                                </span>
                            </div>
                        </div>

                        <div class="p-5">
                            <div class="mb-4 text-sm text-gray-700">
                                <p>النقاط الإجمالية المُسجلة: <span class="font-semibold">{{ $project['total_score'] }}</span> من <span class="font-semibold">{{ $project['max_score'] }}</span></p>
                                <p class="mt-1 text-xs text-gray-500">ملاحظة: يتم حساب النسبة المئوية من إجمالي النقاط الممكنة لجميع الأنشطة المقيمة.</p>
                            </div>

                            <h4 class="text-lg font-semibold mt-6 mb-3 border-b pb-2">الأنشطة المرتبطة ونسبة أدائها:</h4>
                            
                            @if ($project['activities']->isEmpty())
                                <p class="text-gray-500 text-sm">لا توجد أنشطة مقيمة مرتبطة بهذا المشروع.</p>
                            @else
                                <ul class="space-y-3">
                                    @foreach ($project['activities'] as $activity)
                                        <li class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                                            <span class="text-gray-800 font-medium">{{ $activity['title'] }}</span>
                                            <div class="flex items-center space-x-2 space-x-reverse">
                                                <span class="text-sm text-gray-600">
                                                    ({{ $activity['total_score'] }} / {{ $activity['max_score'] }})
                                                </span>
                                                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                                    {{ $activity['percentage'] >= 75 ? 'bg-green-100 text-green-800' : ($activity['percentage'] >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    %{{ $activity['percentage'] }}
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>