<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    {{ substr($indicator->title, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $indicator->title }}</h2>
                    <p class="text-sm text-gray-500">تفاصيل المؤشر</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500">السنة الحالية</span>
                <span
                    class="text-sm bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-1.5 px-4 rounded-full shadow-md font-semibold">
                    {{ $indicator->current_year }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div
                    class="bg-white rounded-xl shadow-sm p-6 border-r-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">رمز المؤشر</p>
                            <p class="text-lg font-mono font-bold text-gray-800" dir="ltr">
                                {{ $indicator->code ?? 'N/A' }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm p-6 border-r-4 border-indigo-500 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">خط الأساس</p>
                            <p class="text-lg font-bold text-gray-800">
                                {{ $indicator->baseline_after_application ?? 'N/A' }}
                                <span class="text-xs text-gray-400 font-normal">
                                    ({{ $indicator->first_observation_date ? \Carbon\Carbon::parse($indicator->first_observation_date)->year : 'N/A' }})
                                </span>
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm p-6 border-r-4 border-emerald-500 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">مالك المؤشر</p>
                            <p class="text-lg font-bold text-gray-800">{{ $indicator->owner ?? 'N/A' }}</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Main Criteria -->
                <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">المعيار الرئيسي</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg">
                        {{ $indicator->main_criteria ?? 'غير محدد' }}
                    </p>
                </div>

                <!-- Formula -->
                <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">معادلة القياس</h3>
                    </div>
                    <div>
                       {{ $indicator->formula ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">وصف المؤشر</h3>
                </div>
                <div
                    class="prose max-w-none text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50 p-4 rounded-lg">
                    {{ $indicator->description ?? 'لا يوجد وصف متاح' }}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <button onclick="window.print()"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    طباعة
                </button>
                <a href="{{ route('indicator.edit', $indicator) }}"
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2 font-medium shadow-lg shadow-indigo-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    تعديل المؤشر
                </a>
            </div>


            <!-- Targets Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <div>
                            <h3 class="text-lg font-bold text-white">المستهدفات السنوية</h3>
                            <p class="text-indigo-200 text-sm">الفترة من 2025 إلى 2040</p>
                        </div>
                    </div>
                    <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm backdrop-blur-sm">
                        {{ $targets->count() }} سنة
                    </span>
                    <a href="{{ route('indicator_target.edit', $indicator) }}"
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    تعديل المستهدف
                </a>
                    
                </div>

                <div class="p-6">
                    @if ($targets->count() > 0)
                        @php
                            $previousTarget = $indicator->baseline_after_application;
                        @endphp

                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                            @foreach ($targets as $target)
                                @php
                                    $percentage = $target->target_value / 100; 
                                    $currentTarget = $previousTarget * (1 + $percentage);
                                    $previousTarget = $currentTarget;
                                @endphp

                                <div
                                    class="group relative bg-gradient-to-br from-indigo-50 to-white border border-indigo-100 rounded-lg p-4 text-center hover:shadow-lg hover:border-indigo-300 transition-all duration-300 cursor-pointer">
                                    <div class="text-xs text-gray-500 mb-1 font-medium">
                                        {{ $target->year }}
                                    </div>

                                    <div
                                        class="text-lg font-bold text-indigo-700 group-hover:scale-110 transition-transform">
                                        {{ number_format($target->target_value) }}<span class="text-xs">%</span>
                                    </div>

                                    <div class="text-sm font-semibold text-gray-700 mt-1">
                                        {{ number_format($currentTarget) }}
                                    </div>

                                    <div
                                        class="absolute inset-0 bg-indigo-600 opacity-0 group-hover:opacity-5 rounded-lg transition-opacity">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">لا توجد مستهدفات مسجلة</p>
                            <p class="text-gray-400 text-sm mt-1">الفترة المستهدفة (2025 - 2040)</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
