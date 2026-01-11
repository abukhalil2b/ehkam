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
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('swot.index') }}" class="text-gray-600 hover:text-blue-600 text-sm">
                                مشاريع SWOT
                            </a>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
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
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>{{ $project->creator->name ?? 'غير معروف' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>{{ $project->created_at->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @if ($project->is_active && !$project->is_finalized)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full ml-2"></span>
                                جاري التحليل
                            </span>
                        @elseif($project->is_finalized)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                مكتمل
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
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
                <a href="{{ route('swot.display', $project->id) }}" target="_blank"
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
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10
                             a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
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

                        @if ($project->is_finalized)
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

                        @if ($project->finalized_at)
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

                        @if ($project->is_finalized)
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
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2
                                 c0-.656-.126-1.283-.356-1.857M7 20H2v-2
                                 a3 3 0 015.356-1.857M7 20v-2
                                 c0-.656.126-1.283.356-1.857m0 0
                                 a5.002 5.002 0 019.288 0M15 7
                                 a3 3 0 11-6 0 3 3 0 016 0zm6 3
                                 a2 2 0 11-4 0 2 2 0 014 0zM7 10
                                 a2 2 0 11-4 0 2 2 0 014 0z" />
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

                        <!-- Last Activity -->
                        @if ($project->boards->isNotEmpty())
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
                                'strength' => [
                                    'title' => 'نقاط القوة',
                                    'color' => 'green',
                                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                ],
                                'weakness' => [
                                    'title' => 'نقاط الضعف',
                                    'color' => 'red',
                                    'icon' =>
                                        'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.73 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z',
                                ],
                                'opportunity' => [
                                    'title' => 'الفرص',
                                    'color' => 'blue',
                                    'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
                                ],
                                'threat' => [
                                    'title' => 'التهديدات',
                                    'color' => 'yellow',
                                    'icon' =>
                                        'M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                                ],
                            ];
                        @endphp

                        @foreach ($categories as $type => $meta)
                            @php
                                $count = $project->boards->where('type', $type)->count();
                                $percentage =
                                    $project->boards->count() > 0 ? ($count / $project->boards->count()) * 100 : 0;
                            @endphp
                            <div
                                class="bg-{{ $meta['color'] }}-50 border border-{{ $meta['color'] }}-200 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div
                                        class="w-8 h-8 bg-{{ $meta['color'] }}-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-{{ $meta['color'] }}-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $meta['icon'] }}" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-{{ $meta['color'] }}-700 font-bold text-lg">{{ $count }}</span>
                                </div>
                                <h4 class="font-semibold text-{{ $meta['color'] }}-800 mb-1">{{ $meta['title'] }}
                                </h4>
                                <div class="w-full bg-{{ $meta['color'] }}-200 rounded-full h-2">
                                    <div class="bg-{{ $meta['color'] }}-600 h-2 rounded-full transition-all duration-500"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                                <p class="text-xs text-{{ $meta['color'] }}-700 mt-1">
                                    {{ number_format($percentage, 1) }}%</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Live Board Preview -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900">اللوحة التفاعلية</h2>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            <span>اسحب وأفلت لإعادة التصنيف</span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($categories as $type => $meta)
                            <div
                                class="border-2 border-{{ $meta['color'] }}-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-lg">
                                <div
                                    class="bg-{{ $meta['color'] }}-100 p-4 border-b border-{{ $meta['color'] }}-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-bold text-{{ $meta['color'] }}-900">{{ $meta['title'] }}</h3>
                                        <span
                                            class="px-2 py-1 bg-{{ $meta['color'] }}-200 text-{{ $meta['color'] }}-800 text-xs font-medium rounded transition-all duration-300"
                                            id="count-{{ $type }}">
                                            {{ $project->boards->where('type', $type)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="max-h-80 overflow-y-auto p-4 drop-zone transition-all duration-200 min-h-[200px]"
                                    data-type="{{ $type }}" ondragover="onDragOver(event)"
                                    ondrop="onDrop(event)" ondragenter="onDragEnter(event)"
                                    ondragleave="onDragLeave(event)">
                                    <div class="space-y-3" id="items-{{ $type }}">
                                        @forelse($project->boards->where('type', $type) as $item)
                                            <div class="bg-white border-2 border-gray-200 rounded-lg p-3 cursor-move hover:shadow-lg hover:border-{{ $meta['color'] }}-300 transition-all duration-200 drag-item group"
                                                draggable="true" data-id="{{ $item->id }}"
                                                data-type="{{ $type }}" ondragstart="onDragStart(event)"
                                                ondragend="onDragEnd(event)">
                                                <div class="flex justify-between items-start gap-2">
                                                    <div class="flex items-start gap-2 flex-1">
                                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 group-hover:text-{{ $meta['color'] }}-500 transition-colors flex-shrink-0"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M4 8h16M4 16h16" />
                                                        </svg>
                                                        <p class="text-sm text-gray-800 flex-1 leading-relaxed"
                                                            id="content-{{ $item->id }}">{{ $item->content }}</p>
                                                    </div>
                                                    <div class="flex items-center gap-1 flex-shrink-0">
                                                        <button
                                                            onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->content) }}')"
                                                            class="text-gray-400 hover:text-blue-600 transition-colors"
                                                            title="تعديل">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </button>
                                                        <button onclick="copyText('{{ addslashes($item->content) }}')"
                                                            class="text-gray-400 hover:text-{{ $meta['color'] }}-600 transition-colors"
                                                            title="نسخ النص">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
                                                    <span
                                                        class="text-xs text-gray-600 font-medium">{{ $item->participant_name }}</span>
                                                    <span
                                                        class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-12 empty-state">
                                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p class="text-gray-500 text-sm font-medium">لا توجد عناصر</p>
                                                <p class="text-gray-400 text-xs mt-1">اسحب العناصر هنا</p>
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
            @if ($project->finalize)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-25">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-purple-900">ملخص واستراتيجيات المشروع</h2>
                                <p class="text-purple-600 text-sm mt-1">تم إنهاء المشروع في
                                    @if ($project->finalized_at)
                                        {{ $project->finalized_at->format('Y/m/d') }}
                                </p>
            @endif
        </div>
        <div class="flex items-center">
            <a href="{{ route('swot.finalize', $project->id) }}"
                class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 flex items-center">
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">ملخص التحليل</h3>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <p class="text-gray-800 leading-relaxed whitespace-pre-line">
                    {{ $project->finalize->summary ?? 'لم يتم إضافة ملخص' }}</p>
            </div>
        </div>

        <!-- Strategies -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">الاستراتيجيات المقترحة</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-green-50 to-green-25 border border-green-200 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold text-green-900">نقاط القوة</h4>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-line">
                        {{ $project->finalize->strength_strategy ?? '-' }}</p>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-red-25 border border-red-200 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.73 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold text-red-900">نقاط الضعف</h4>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-line">
                        {{ $project->finalize->weakness_strategy ?? '-' }}</p>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-25 border border-yellow-200 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold text-yellow-900">التهديدات</h4>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-line">
                        {{ $project->finalize->threat_strategy ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Action Plan -->
        @if ($project->finalize->action_items && count($project->finalize->action_items))
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    المهمة</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    المسؤول</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    الأولوية</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    موعد التسليم</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($project->finalize->action_items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item['title'] }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700">{{ $item['owner'] ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $priorityColors = [
                                                'High' => 'red',
                                                'Medium' => 'yellow',
                                                'Low' => 'green',
                                            ];
                                            $color = $priorityColors[$item['priority'] ?? ''] ?? 'gray';
                                        @endphp
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                            {{ $item['priority'] ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700">
                                            {{ $item['deadline'] ?? '-' }}</div>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                العودة للمشاريع
            </a>
        </div>

        <div class="flex gap-3">
            @if (!$project->is_finalized)
                <a href="{{ route('swot.finalize', $project->id) }}"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 inline-flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    إنهاء المشروع
                </a>
            @endif

            @if ($project->is_active)
                <form action="#" method="POST" onsubmit="return confirm('هل تريد تعطيل هذا المشروع؟');">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200 inline-flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        تعطيل المشروع
                    </button>
                </form>
            @endif
        </div>
    </div>
    </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast"
        class="fixed bottom-6 left-6 bg-gray-900 text-white px-6 py-4 rounded-xl shadow-2xl transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-3">
        <svg id="toast-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
        <span id="toast-message" class="font-medium"></span>
    </div>

    <style>
        .bg-green-25 {
            background-color: rgba(240, 253, 244, 0.5);
        }

        .bg-red-25 {
            background-color: rgba(254, 242, 242, 0.5);
        }

        .bg-blue-25 {
            background-color: rgba(239, 246, 255, 0.5);
        }

        .bg-yellow-25 {
            background-color: rgba(254, 252, 232, 0.5);
        }

        .bg-purple-25 {
            background-color: rgba(250, 245, 255, 0.5);
        }

        /* Custom scrollbar */
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

        /* Drag and Drop Styles */
        .drag-item {
            transform-origin: center;
            transition: transform 0.2s ease, opacity 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .drag-item.dragging {
            opacity: 0.6;
            transform: scale(1.05) rotate(3deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: #3b82f6;
            z-index: 1000;
        }

        .drop-zone {
            transition: all 0.3s ease;
            position: relative;
        }

        .drop-zone.drag-over {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(59, 130, 246, 0.03) 100%);
            border: 2px dashed #3b82f6 !important;
            border-radius: 0.75rem;
        }

        .drop-zone.drag-over::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
            pointer-events: none;
            animation: pulse-glow 1.5s ease-in-out infinite;
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }
        }

        /* Empty state animation */
        .empty-state {
            animation: fade-in 0.5s ease-out;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Copy success animation */
        @keyframes pulse-success {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }
        }

        .copy-success {
            animation: pulse-success 0.4s ease;
            color: #10b981 !important;
        }

        /* Toast show animation */
        .toast-show {
            transform: translateY(0) !important;
            opacity: 1 !important;
        }

        /* Item move animation */
        @keyframes item-move {
            0% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.3;
                transform: scale(0.9);
            }

            100% {
                opacity: 0;
                transform: scale(0.7) translateY(-20px);
            }
        }

        .item-moving {
            animation: item-move 0.4s ease-out forwards;
        }

        .modal-show #editModalContent {
            transform: scale(1);
            opacity: 1;
        }

        #editModal.modal-show {
            display: flex !important;
        }

        /* Smooth textarea resize */
        #editContent {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        #editContent:focus {
            outline: none;
        }

        /* Button loading state */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.6s linear infinite;
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        let currentEditId = null;
        let draggedElement = null;
        let draggedId = null;
        let draggedType = null;

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');

            toastMessage.textContent = message;

            if (type === 'success') {
                toast.className = toast.className.replace('bg-gray-900', 'bg-green-600');
                toastIcon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
            } else if (type === 'error') {
                toast.className = toast.className.replace('bg-gray-900', 'bg-red-600');
                toastIcon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
            }

            toast.classList.add('toast-show');

            setTimeout(() => {
                toast.classList.remove('toast-show');
                setTimeout(() => {
                    toast.className =
                        'fixed bottom-6 left-6 bg-gray-900 text-white px-6 py-4 rounded-xl shadow-2xl transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-3';
                }, 300);
            }, 3000);
        }

        function onDragStart(event) {
            draggedElement = event.target;
            draggedId = event.target.dataset.id;
            draggedType = event.target.dataset.type;

            setTimeout(() => {
                event.target.classList.add('dragging');
            }, 0);

            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/html', event.target.innerHTML);
        }

        function onDragEnd(event) {
            event.target.classList.remove('dragging');

            document.querySelectorAll('.drag-over').forEach(el => {
                el.classList.remove('drag-over');
            });
        }

        function onDragOver(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            return false;
        }

        function onDragEnter(event) {
            const dropZone = event.target.closest('.drop-zone');
            if (dropZone && dropZone.dataset.type !== draggedType) {
                dropZone.classList.add('drag-over');
            }
        }

        function onDragLeave(event) {
            const dropZone = event.target.closest('.drop-zone');
            if (dropZone && !dropZone.contains(event.relatedTarget)) {
                dropZone.classList.remove('drag-over');
            }
        }

        function onDrop(event) {
            event.preventDefault();
            event.stopPropagation();

            const dropZone = event.target.closest('.drop-zone');
            if (!dropZone || !draggedElement) return;

            const newType = dropZone.dataset.type;
            const oldType = draggedType;

            dropZone.classList.remove('drag-over');

            if (newType === oldType) {
                return;
            }

            // Add moving animation
            draggedElement.classList.add('item-moving');

            setTimeout(() => {
                fetch(`/swot/admin/board/${draggedId}/move`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            type: newType
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update counts
                            const oldCount = document.getElementById(`count-${oldType}`);
                            const newCount = document.getElementById(`count-${newType}`);

                            if (oldCount) oldCount.textContent = parseInt(oldCount.textContent) - 1;
                            if (newCount) newCount.textContent = parseInt(newCount.textContent) + 1;

                            // Move element to new column
                            const oldContainer = document.getElementById(`items-${oldType}`);
                            const newContainer = document.getElementById(`items-${newType}`);

                            // Remove empty state if exists
                            const emptyState = newContainer.querySelector('.empty-state');
                            if (emptyState) {
                                emptyState.remove();
                            }

                            // Update element data
                            draggedElement.dataset.type = newType;
                            draggedElement.classList.remove('item-moving', 'dragging');

                            // Animate into new position
                            draggedElement.style.opacity = '0';
                            draggedElement.style.transform = 'scale(0.8)';

                            newContainer.appendChild(draggedElement);

                            setTimeout(() => {
                                draggedElement.style.transition = 'all 0.3s ease';
                                draggedElement.style.opacity = '1';
                                draggedElement.style.transform = 'scale(1)';

                                setTimeout(() => {
                                    draggedElement.style.transition = '';
                                    draggedElement.style.opacity = '';
                                    draggedElement.style.transform = '';
                                }, 300);
                            }, 50);

                            // Check if old container is now empty
                            if (oldContainer.children.length === 0) {
                                oldContainer.innerHTML = `
                                <div class="text-center py-12 empty-state">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm font-medium">لا توجد عناصر</p>
                                    <p class="text-gray-400 text-xs mt-1">اسحب العناصر هنا</p>
                                </div>
                            `;
                            }

                            showToast('تم نقل العنصر بنجاح', 'success');
                        } else {
                            throw new Error('Failed to move item');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        draggedElement.classList.remove('item-moving', 'dragging');
                        showToast('فشل نقل العنصر. حاول مرة أخرى', 'error');
                    });
            }, 400);
        }

        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                const button = event.target.closest('button');
                const icon = button.querySelector('svg');

                icon.classList.add('copy-success');

                const originalTitle = button.title;
                button.title = 'تم النسخ!';

                showToast('تم نسخ النص بنجاح', 'success');

                setTimeout(() => {
                    icon.classList.remove('copy-success');
                    button.title = originalTitle;
                }, 1000);
            }).catch(err => {
                console.error('Failed to copy text:', err);
                showToast('فشل نسخ النص', 'error');
            });
        }

        function openEditModal(id, content) {
            currentEditId = id;
            const modal = document.getElementById('editModal');
            const textarea = document.getElementById('editContent');
            const charCount = document.getElementById('charCount');

            textarea.value = content;
            updateCharCount();

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('modal-show');
                textarea.focus();
            }, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('modal-show');
            setTimeout(() => {
                modal.classList.add('hidden');
                currentEditId = null;
                document.getElementById('editContent').value = '';
            }, 300);
        }

        function updateCharCount() {
            const textarea = document.getElementById('editContent');
            const charCount = document.getElementById('charCount');
            const count = textarea.value.length;
            charCount.textContent = `${count} / 1000`;

            if (count > 950) {
                charCount.classList.add('text-red-600', 'font-semibold');
            } else {
                charCount.classList.remove('text-red-600', 'font-semibold');
            }
        }

        // Character counter
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('editContent');
            if (textarea) {
                textarea.addEventListener('input', updateCharCount);
            }

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('editModal');
                if (modal && e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeEditModal();
                }
            });

            // Close modal on backdrop click
            const modal = document.getElementById('editModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeEditModal();
                    }
                });
            }

            // Handle form submission
            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const content = document.getElementById('editContent').value.trim();

                    if (!content) {
                        showToast('الرجاء إدخال المحتوى', 'error');
                        return;
                    }

                    if (content.length > 1000) {
                        showToast('المحتوى يتجاوز الحد الأقصى المسموح', 'error');
                        return;
                    }

                    const submitBtn = e.target.querySelector('button[type="submit"]');
                    submitBtn.classList.add('btn-loading');

                    fetch(`/swot/admin/board/${currentEditId}/update-content`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            },
                            body: JSON.stringify({
                                content
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update the content in the UI
                                const contentElement = document.getElementById(
                                    `content-${currentEditId}`);
                                if (contentElement) {
                                    contentElement.textContent = data.content;

                                    // Add highlight effect
                                    contentElement.classList.add('bg-yellow-100');
                                    setTimeout(() => {
                                        contentElement.classList.remove('bg-yellow-100');
                                    }, 1000);
                                }

                                showToast('تم تحديث المحتوى بنجاح', 'success');
                                closeEditModal();
                            } else {
                                throw new Error('Failed to update content');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('فشل تحديث المحتوى. حاول مرة أخرى', 'error');
                        })
                        .finally(() => {
                            submitBtn.classList.remove('btn-loading');
                        });
                });
            }
        });
    </script>

    <!-- Edit Modal-->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full transform transition-all duration-300 scale-95 opacity-0"
            id="editModalContent">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">تعديل المحتوى</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="editForm" class="p-6">
                <div class="mb-6">
                    <label for="editContent" class="block text-sm font-medium text-gray-700 mb-2">
                        المحتوى
                    </label>
                    <textarea id="editContent" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                        placeholder="أدخل المحتوى هنا..." maxlength="1000"></textarea>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-xs text-gray-500">الحد الأقصى: 1000 حرف</span>
                        <span id="charCount" class="text-xs text-gray-500">0 / 1000</span>
                    </div>
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeEditModal()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
