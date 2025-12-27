<x-app-layout>
    <div dir="rtl" class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">

            <!-- Header with Status -->
            <div class="mb-8">
                <nav class="flex mb-4" aria-label="مسار التنقل">
                    <ol class="inline-flex items-center space-x-1 space-x-reverse rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 text-sm">
                                الرئيسية
                            </a>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('swot.index') }}" class="text-gray-600 hover:text-blue-600 text-sm">
                                مشاريع SWOT
                            </a>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-900 text-sm font-medium">{{ $project->title }}</span>
                        </li>
                    </ol>
                </nav>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $project->title }}</h1>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $project->creator->name ?? 'غير معروف' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $project->created_at->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @if($project->is_active && !$project->is_finalized)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full ml-2"></span>
                                جاري التحليل
                            </span>
                        @elseif($project->is_finalized)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                مكتمل
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                <span class="w-2 h-2 bg-gray-500 rounded-full ml-2"></span>
                                غير نشط
                            </span>
                        @endif
             
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

    <!-- Display Screen -->
    <a href="{{ route('swot.display', $project->id) }}"
       target="_blank"
       class="group bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-900 group-hover:text-green-600 transition">
                    شاشة العرض
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    عرض QR واللوحة المباشرة
                </p>
            </div>

            <div class="w-11 h-11 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10
                             a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </div>
        </div>
    </a>

    <!-- Project Status -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900">
                حالة المشروع
            </h3>

            @if($project->is_finalized)
                <span class="px-2.5 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                    منتهي
                </span>
            @else
                <span class="px-2.5 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                    نشط
                </span>
            @endif
        </div>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">تاريخ الإنشاء</span>
                <span class="font-medium text-gray-900">
                    {{ $project->created_at->format('Y/m/d') }}
                </span>
            </div>

            @if($project->finalized_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">تاريخ الإنهاء</span>
                    <span class="font-medium text-gray-900">
                        {{ $project->finalized_at->format('Y/m/d') }}
                    </span>
                </div>
            @else
                <div class="flex justify-between">
                    <span class="text-gray-600">آخر تحديث</span>
                    <span class="font-medium text-gray-900">
                        {{ $project->updated_at->diffForHumans() }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Open Data / Export -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-1">
            البيانات المفتوحة
        </h3>
        <p class="text-sm text-gray-500 mb-4">
            تصدير نتائج التحليل
        </p>

        <div class="flex gap-2">
            <a href="{{ route('swot.export.excel', $project->id) }}"
               class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-700 text-sm rounded-lg hover:bg-blue-100 transition">
                Excel
            </a>
        </div>
    </div>

</div>


            <!-- Stats Dashboard -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
               <!-- Total Stats -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-semibold text-gray-900">إحصائيات عامة</h3>

        @if($project->is_finalized)
            <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                تم إنهاء المشروع
            </span>
        @endif
    </div>

    <div class="space-y-5 divide-y divide-gray-100">

        <!-- Total Items -->
        <div class="flex items-center justify-between pt-1">
            <div class="flex items-center">
                <div class="w-11 h-11 bg-blue-100 rounded-lg flex items-center justify-center ml-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-sm text-gray-600">إجمالي العناصر</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $project->boards->count() }}
                    </p>
                </div>
            </div>

            <span class="text-xs text-gray-400">
                بجميع الفئات
            </span>
        </div>

        <!-- Participants -->
        <div class="flex items-center justify-between pt-5">
            <div class="flex items-center">
                <div class="w-11 h-11 bg-green-100 rounded-lg flex items-center justify-center ml-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2
                                 c0-.656-.126-1.283-.356-1.857M7 20H2v-2
                                 a3 3 0 015.356-1.857M7 20v-2
                                 c0-.656.126-1.283.356-1.857m0 0
                                 a5.002 5.002 0 019.288 0M15 7
                                 a3 3 0 11-6 0 3 3 0 016 0zm6 3
                                 a2 2 0 11-4 0 2 2 0 014 0zM7 10
                                 a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>

                <div>
                    <p class="text-sm text-gray-600">عدد المشاركين</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $project->boards->groupBy('session_id')->count() }}
                    </p>
                </div>
            </div>

            <span class="text-xs text-gray-400">
                جلسات فريدة
            </span>
        </div>

        <!-- Last Activity (Optional but valuable) -->
        @if($project->boards->isNotEmpty())
            <div class="pt-5">
                <p class="text-xs text-gray-500">
                    آخر إضافة:
                    <span class="font-medium text-gray-700">
                        {{ $project->boards->sortByDesc('created_at')->first()->created_at->diffForHumans() }}
                    </span>
                </p>
            </div>
        @endif

    </div>
</div>

                <!-- SWOT Distribution -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-6">توزيع SWOT</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $categories = [
                                'strength' => ['title' => 'نقاط القوة', 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'weakness' => ['title' => 'نقاط الضعف', 'color' => 'red', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.73 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z'],
                                'opportunity' => ['title' => 'الفرص', 'color' => 'blue', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                                'threat' => ['title' => 'التهديدات', 'color' => 'yellow', 'icon' => 'M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                            ];
                        @endphp

                        @foreach($categories as $type => $meta)
                            @php
                                $count = $project->boards->where('type', $type)->count();
                                $percentage = $project->boards->count() > 0 ? ($count / $project->boards->count() * 100) : 0;
                            @endphp
                            <div class="bg-{{ $meta['color'] }}-50 border border-{{ $meta['color'] }}-200 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="w-8 h-8 bg-{{ $meta['color'] }}-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-{{ $meta['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}" />
                                        </svg>
                                    </div>
                                    <span class="text-{{ $meta['color'] }}-700 font-bold text-lg">{{ $count }}</span>
                                </div>
                                <h4 class="font-semibold text-{{ $meta['color'] }}-800 mb-1">{{ $meta['title'] }}</h4>
                                <div class="w-full bg-{{ $meta['color'] }}-200 rounded-full h-2">
                                    <div class="bg-{{ $meta['color'] }}-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <p class="text-xs text-{{ $meta['color'] }}-700 mt-1">{{ number_format($percentage, 1) }}%</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Live Board Preview -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900">  اللوحة  </h2>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($categories as $type => $meta)
                            <div class="border-2 border-{{ $meta['color'] }}-200 rounded-xl overflow-hidden">
                                <div class="bg-{{ $meta['color'] }}-100 p-4 border-b border-{{ $meta['color'] }}-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-bold text-{{ $meta['color'] }}-900">{{ $meta['title'] }}</h3>
                                        <span class="px-2 py-1 bg-{{ $meta['color'] }}-200 text-{{ $meta['color'] }}-800 text-xs font-medium rounded">
                                            {{ $project->boards->where('type', $type)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="max-h-80 overflow-y-auto p-4">
                                    <div class="space-y-3">
                                        @forelse($project->boards->where('type', $type)->take(10) as $item)
                                            <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-sm transition-shadow duration-200">
                                                <div class="flex justify-between items-start">
                                                    <p class="text-sm text-gray-800 flex-1">{{ $item->content }}</p>
                                                    <button onclick="copyText('{{ $item->content }}')"
                                                        class="text-gray-400 hover:text-gray-600 ml-2"
                                                        title="نسخ النص">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="flex items-center justify-between mt-2">
                                                    <span class="text-xs text-gray-500">{{ $item->participant_name }}</span>
                                                    <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-8">
                                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p class="text-gray-500 text-sm">لا توجد عناصر بعد</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Finalized Content -->
            @if($project->finalize)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-25">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-purple-900">ملخص واستراتيجيات المشروع</h2>
                                <p class="text-purple-600 text-sm mt-1">تم إنهاء المشروع في {{ $project->finalized_at->format('Y/m/d') }}</p>
                            </div>
                            <div class="flex items-center">
                                <a href="{{ route('swot.finalize', $project->id) }}"
                                    class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    تعديل
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Summary -->
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900">ملخص التحليل</h3>
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                                <p class="text-gray-800 leading-relaxed whitespace-pre-line">{{ $project->finalize->summary ?? 'لم يتم إضافة ملخص' }}</p>
                            </div>
                        </div>

                        <!-- Strategies -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">الاستراتيجيات المقترحة</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-gradient-to-br from-green-50 to-green-25 border border-green-200 rounded-xl p-5">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-semibold text-green-900">نقاط القوة</h4>
                                    </div>
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $project->finalize->strength_strategy ?? '-' }}</p>
                                </div>

                                <div class="bg-gradient-to-br from-red-50 to-red-25 border border-red-200 rounded-xl p-5">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.73 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-semibold text-red-900">نقاط الضعف</h4>
                                    </div>
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $project->finalize->weakness_strategy ?? '-' }}</p>
                                </div>

                                <div class="bg-gradient-to-br from-yellow-50 to-yellow-25 border border-yellow-200 rounded-xl p-5">
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-semibold text-yellow-900">التهديدات</h4>
                                    </div>
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $project->finalize->threat_strategy ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Plan -->
                        @if($project->finalize->action_items && count($project->finalize->action_items))
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <h3 class="text-lg font-semibold text-gray-900">خطة العمل</h3>
                                    </div>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                                        {{ count($project->finalize->action_items) }} مهمة
                                    </span>
                                </div>

                                <div class="overflow-hidden border border-gray-200 rounded-xl">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المهمة</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المسؤول</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأولوية</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">موعد التسليم</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($project->finalize->action_items as $item)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $item['title'] }}</div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm text-gray-700">{{ $item['owner'] ?? '-' }}</div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        @php
                                                            $priorityColors = [
                                                                'High' => 'red',
                                                                'Medium' => 'yellow',
                                                                'Low' => 'green'
                                                            ];
                                                            $color = $priorityColors[$item['priority'] ?? ''] ?? 'gray';
                                                        @endphp
                                                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                                            {{ $item['priority'] ?? 'غير محدد' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm text-gray-700">{{ $item['deadline'] ?? '-' }}</div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Bottom Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                <div class="flex gap-3">
                    <a href="{{ route('swot.index') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200 inline-flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        العودة للمشاريع
                    </a>
                </div>
                
                <div class="flex gap-3">
                    @if(!$project->is_finalized)
                        <a href="{{ route('swot.finalize', $project->id) }}"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 inline-flex items-center">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            إنهاء المشروع
                        </a>
                    @endif
                    
                    @if($project->is_active)
                        <form action="#" method="POST" onsubmit="return confirm('هل تريد تعطيل هذا المشروع؟');">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200 inline-flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                تعطيل المشروع
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    function copyLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('تم نسخ الرابط إلى الحافظة');
        });
    }

    function copyText(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Optional: Show a small toast notification
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 left-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg';
            toast.textContent = 'تم نسخ النص';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        });
    }

    </script>

    <style>
    .bg-green-25 { background-color: rgba(240, 253, 244, 0.5); }
    .bg-red-25 { background-color: rgba(254, 242, 242, 0.5); }
    .bg-blue-25 { background-color: rgba(239, 246, 255, 0.5); }
    .bg-yellow-25 { background-color: rgba(254, 252, 232, 0.5); }
    .bg-purple-25 { background-color: rgba(250, 245, 255, 0.5); }
    
    /* Custom scrollbar for board preview */
    .max-h-80::-webkit-scrollbar {
        width: 6px;
    }
    
    .max-h-80::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .max-h-80::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .max-h-80::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    </style>
</x-app-layout>