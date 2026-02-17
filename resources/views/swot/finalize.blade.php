<x-app-layout>
    <div dir="rtl" class="container mx-auto px-4 py-8" x-data="swotFinalize()" x-init="init()">
        <div class="max-w-7xl mx-auto">
            <!-- Header with Breadcrumb -->
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
                            <a href="{{ route('swot.admin', $project->id) }}"
                                class="text-gray-600 hover:text-blue-600 text-sm">
                                إدارة SWOT
                            </a>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-900 text-sm font-medium">التلخيص النهائي</span>
                        </li>
                    </ol>
                </nav>

                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $project->title }}</h1>
                        <p class="text-gray-600 mt-1">صفحة تلخيص واستنتاجات SWOT</p>
                    </div>
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            {{ $project->boards ? $project->boards->count() : 0 }} تحليل
                        </span>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <template x-if="message.type">
                <div class="mb-6 p-4 rounded-lg transition-all duration-300" :class="{
                        'bg-green-50 text-green-800 border border-green-200': message.type === 'success',
                        'bg-red-50 text-red-800 border border-red-200': message.type === 'error'
                    }" x-show="message.text" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2"
                                :class="message.type === 'success' ? 'text-green-500' : 'text-red-500'"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path x-show="message.type === 'success'" fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                                <path x-show="message.type === 'error'" fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span x-text="message.text"></span>
                        </div>
                        <button @click="message.text = ''" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>

            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-3 gap-6 mb-8">
                <!-- Summary Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">ملخص النتائج</h2>
                                <p class="text-gray-500 text-sm mt-1">ملخص شامل لتحليل SWOT</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-sm font-medium rounded-full">
                                {{ str_word_count($finalize->summary ?? '') }} كلمة
                            </span>
                        </div>

                        <div class="relative">
                            <textarea x-model="summary"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 resize-none"
                                rows="6" placeholder="اكتب ملخصاً شاملاً للتحليل يوضح أهم النتائج والاستنتاجات..."
                                @input="updateWordCount('summary')"></textarea>
                            <div class="absolute bottom-2 left-3 text-xs text-gray-400"
                                x-text="summaryWordCount + ' كلمة'"></div>
                        </div>

                        <div class="flex items-center justify-between mt-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>نصيحة: ركز على النتائج الأكثر تأثيراً</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">إحصائيات سريعة</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">العناصر المكتملة</span>
                                <span class="font-semibold text-green-600"
                                    x-text="completedItems + '/' + totalItems"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">الأولوية العالية</span>
                                <span class="font-semibold text-red-600" x-text="highPriorityItems"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">تاريخ الإنشاء</span>
                                <span
                                    class="font-semibold text-gray-700">{{ $project->created_at->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <h3 class="font-semibold text-blue-900 mb-2">نصائح للنجاح</h3>
                        <ul class="space-y-2 text-sm text-blue-800">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>حدد استراتيجيات قابلة للتنفيذ</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>عيّن مسؤولين واضحين لكل مهمة</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>ضع تواريخ واقعية للتسليم</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- SWOT Reference Panel -->
            <div class="mb-8" x-data="{ showReference: true }">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">مرجع تحليل SWOT</h2>
                                    <p class="text-sm text-gray-600">راجع العناصر المدخلة قبل كتابة الاستراتيجيات</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="copyAllSwotToClipboard()"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    نسخ الكل
                                </button>
                                <button @click="showReference = !showReference"
                                    class="px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 rounded-lg transition-colors flex items-center gap-2">
                                    <span x-text="showReference ? 'إخفاء' : 'عرض'"></span>
                                    <svg class="w-4 h-4 transition-transform" :class="showReference ? 'rotate-180' : ''"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div x-show="showReference" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">

                            <!-- Strengths -->
                            <div
                                class="bg-gradient-to-br from-green-50 to-green-25 border-2 border-green-200 rounded-xl p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-bold text-green-900">نقاط القوة</h3>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">
                                        {{ $swotData['strengths']->count() }}
                                    </span>
                                </div>
                                <div class="space-y-2 max-h-64 overflow-y-auto">
                                    @forelse($swotData['strengths'] as $item)
                                        <div
                                            class="bg-white/70 backdrop-blur-sm rounded-lg p-3 text-sm text-gray-800 hover:bg-white transition-colors border border-green-100">
                                            <div class="flex items-start gap-2">
                                                <span class="text-green-500 mt-0.5">•</span>
                                                <span class="flex-1">{{ $item->content }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-green-600 text-sm">
                                            لا توجد نقاط قوة مسجلة
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Weaknesses -->
                            <div class="bg-gradient-to-br from-red-50 to-red-25 border-2 border-red-200 rounded-xl p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.73 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-bold text-red-900">نقاط الضعف</h3>
                                    </div>
                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">
                                        {{ $swotData['weaknesses']->count() }}
                                    </span>
                                </div>
                                <div class="space-y-2 max-h-64 overflow-y-auto">
                                    @forelse($swotData['weaknesses'] as $item)
                                        <div
                                            class="bg-white/70 backdrop-blur-sm rounded-lg p-3 text-sm text-gray-800 hover:bg-white transition-colors border border-red-100">
                                            <div class="flex items-start gap-2">
                                                <span class="text-red-500 mt-0.5">•</span>
                                                <span class="flex-1">{{ $item->content }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-red-600 text-sm">
                                            لا توجد نقاط ضعف مسجلة
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Opportunities -->
                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-25 border-2 border-blue-200 rounded-xl p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                        </div>
                                        <h3 class="font-bold text-blue-900">الفرص</h3>
                                    </div>
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                                        {{ $swotData['opportunities']->count() }}
                                    </span>
                                </div>
                                <div class="space-y-2 max-h-64 overflow-y-auto">
                                    @forelse($swotData['opportunities'] as $item)
                                        <div
                                            class="bg-white/70 backdrop-blur-sm rounded-lg p-3 text-sm text-gray-800 hover:bg-white transition-colors border border-blue-100">
                                            <div class="flex items-start gap-2">
                                                <span class="text-blue-500 mt-0.5">•</span>
                                                <span class="flex-1">{{ $item->content }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-blue-600 text-sm">
                                            لا توجد فرص مسجلة
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Threats -->
                            <div
                                class="bg-gradient-to-br from-yellow-50 to-yellow-25 border-2 border-yellow-200 rounded-xl p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-bold text-yellow-900">التهديدات</h3>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">
                                        {{ $swotData['threats']->count() }}
                                    </span>
                                </div>
                                <div class="space-y-2 max-h-64 overflow-y-auto">
                                    @forelse($swotData['threats'] as $item)
                                        <div
                                            class="bg-white/70 backdrop-blur-sm rounded-lg p-3 text-sm text-gray-800 hover:bg-white transition-colors border border-yellow-100">
                                            <div class="flex items-start gap-2">
                                                <span class="text-yellow-500 mt-0.5">•</span>
                                                <span class="flex-1">{{ $item->content }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-yellow-600 text-sm">
                                            لا توجد تهديدات مسجلة
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="mt-6 flex items-center justify-center gap-8 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-gray-600">{{ $swotData['strengths']->count() }} نقاط قوة</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-gray-600">{{ $swotData['weaknesses']->count() }} نقاط ضعف</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-gray-600">{{ $swotData['opportunities']->count() }} فرص</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-gray-600">{{ $swotData['threats']->count() }} تهديدات</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BSC Dimension Strategies Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">الاستراتيجيات المقترحة</h2>
                        <p class="text-gray-500 text-sm mt-1">بطاقة الأداء المتوازن (BSC)</p>
                    </div>
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-full">
                        4 أبعاد استراتيجية
                    </span>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <template x-for="(config, type) in dimensionConfigs" :key="type">
                        <div class="bg-gradient-to-br border-2 rounded-xl p-6 transition-all duration-200 hover:shadow-lg relative"
                            :class="config.colorClasses">

                            <!-- Header -->
                            <div class="flex items-center justify-between mb-5">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-3"
                                        :class="config.iconBgClass">
                                        <svg class="w-7 h-7" :class="config.iconColorClass" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="config.iconPath" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg" :class="config.titleClass" x-text="config.title">
                                        </h3>
                                        <p class="text-xs" :class="config.subtitleClass" x-text="config.subtitle"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Strategies Loop -->
                            <div class="space-y-6">
                                <template x-for="(instance, index) in dimensionInstances.filter(d => d.type === type)"
                                    :key="instance.id">
                                    <div class="bg-white/50 rounded-lg p-4 border" :class="config.colorClasses">

                                        <!-- Remove Strategy Button -->
                                        <div class="flex justify-end mb-2"
                                            x-show="dimensionInstances.filter(d => d.type === type).length > 1">
                                            <button type="button" @click="removeDimension(instance.id)"
                                                class="text-red-500 hover:text-red-700 text-xs flex items-center gap-1 bg-white/50 px-2 py-1 rounded">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                حذف
                                            </button>
                                        </div>

                                        <!-- Strategic Goal -->
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium mb-1" :class="config.labelClass">
                                                الهدف الاستراتيجي
                                                <span class="text-xs text-gray-500" x-text="'#' + (index + 1)"></span>
                                            </label>
                                            <input type="text" x-model="instance.strategic_goal"
                                                class="w-full px-3 py-2 bg-white/70 border rounded-lg focus:ring-2 transition duration-200 text-sm"
                                                :class="config.inputClasses" placeholder="مثال: أدخل الهدف الاستراتيجي">
                                        </div>

                                        <!-- Performance Indicator -->
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium mb-1" :class="config.labelClass">
                                                مؤشر الأداء
                                            </label>
                                            <input type="text" x-model="instance.performance_indicator"
                                                class="w-full px-3 py-2 bg-white/70 border rounded-lg focus:ring-2 transition duration-200 text-sm"
                                                :class="config.inputClasses" placeholder="مثال: أدخل مؤشر الأداء">
                                        </div>

                                        <!-- Initiatives -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1" :class="config.labelClass">
                                                المبادرات
                                            </label>
                                            <div class="space-y-2">
                                                <template x-for="(initiative, i) in instance.initiatives" :key="i">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-bold"
                                                            :class="config.iconColorClass">•</span>
                                                        <input type="text" x-model="instance.initiatives[i]"
                                                            class="flex-1 px-3 py-1.5 bg-white/70 border rounded-lg focus:ring-2 transition duration-200 text-sm"
                                                            :class="config.inputClasses" placeholder="أدخل المبادرة">
                                                        <button type="button" @click="instance.initiatives.splice(i, 1)"
                                                            class="text-red-400 hover:text-red-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <button type="button" @click="instance.initiatives.push('')"
                                                    class="text-xs hover:underline flex items-center gap-1 mt-1"
                                                    :class="config.iconColorClass">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    إضافة مبادرة
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Add Strategy Button -->
                            <div class="mt-4 pt-4 border-t border-gray-200/50">
                                <button type="button" @click="addDimensionStrategy(type)"
                                    class="w-full py-2 border-2 border-dashed rounded-lg transition-colors flex items-center justify-center gap-2 text-sm font-medium"
                                    :class="config.addButtonClasses">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    إضافة استراتيجية جديدة
                                </button>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

            <!-- Action Plan Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">خطة العمل التنفيذية</h2>
                            <p class="text-gray-500 text-sm mt-1">حدد المهام والمسؤوليات والتواريخ</p>
                        </div>
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <span class="text-sm text-gray-600"
                                x-text="completedItems + ' من ' + totalItems + ' مكتملة'"></span>
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full transition-all duration-500"
                                    :style="`width: ${(completedItems/totalItems)*100}%`" x-show="totalItems > 0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Table Headers -->
                    <div class="grid grid-cols-12 gap-4 mb-4 px-4 text-sm font-medium text-gray-700">
                        <div class="col-span-5">المهمة</div>
                        <div class="col-span-2">المسؤول</div>
                        <div class="col-span-2">الأولوية</div>
                        <div class="col-span-2">تاريخ التسليم</div>
                        <div class="col-span-1">إجراءات</div>
                    </div>

                    <!-- Action Items -->
                    <template x-for="(item, index) in actionItems" :key="index">
                        <div
                            class="grid grid-cols-12 gap-4 mb-3 px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200 items-center">
                            <div class="col-span-5">
                                <input type="text" x-model="item.title" placeholder="أدخل عنوان المهمة"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                    @change="updateProgress()">
                            </div>
                            <div class="col-span-2">
                                <input type="text" x-model="item.owner" placeholder="اسم المسؤول"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            </div>
                            <div class="col-span-2">
                                <select x-model="item.priority"
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 appearance-none">
                                    <option value="">اختر الأولوية</option>
                                    <option value="High" class="text-red-600">عالي</option>
                                    <option value="Medium" class="text-yellow-600">متوسط</option>
                                    <option value="Low" class="text-green-600">منخفض</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <div class="relative">
                                    <input type="date" x-model="item.deadline"
                                        :min="new Date().toISOString().split('T')[0]"
                                        class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    <div class="absolute right-3 top-2.5 text-gray-400 pointer-events-none">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <button type="button" @click="removeAction(index)"
                                    class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors duration-200"
                                    title="حذف المهمة">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <template x-if="actionItems.length === 0">
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-500 mb-2">لا توجد مهام مضافة بعد</p>
                            <p class="text-gray-400 text-sm">انقر على الزر أدناه لإضافة أول مهمة</p>
                        </div>
                    </template>

                    <!-- Add Action Button -->
                    <div class="mt-6 text-center">
                        <button type="button" @click="addAction()"
                            class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 inline-flex items-center hover:shadow-lg transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            إضافة مهمة جديدة
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200 print:hidden">
                <div>
                    <a href="{{ route('swot.admin', $project->id) }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200 inline-flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        العودة للإدارة
                    </a>
                </div>

                <div class="flex space-x-4 space-x-reverse">
                    <button type="button" @click="save()" :disabled="isSaving"
                        class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-200 inline-flex items-center disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg transform hover:-translate-y-0.5">
                        <template x-if="isSaving">
                            <svg class="animate-spin -mr-1 ml-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </template>
                        <template x-if="!isSaving">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <span x-text="isSaving ? 'جاري الحفظ...' : 'حفظ التلخيص'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function swotFinalize() {
            return {
                summary: @json($finalize->summary ?? ''),
                actionItems: @json($finalize->action_items ?? []),

                // Change from single object to array of dimension instances
                dimensionInstances: @json($dimensionInstances ?? []), // From backend

                // Dimension type configurations (unchanged)
                dimensionConfigs: {
                    financial: {
                        title: 'البعد المالي',
                        subtitle: 'Financial Perspective',
                        colorClasses: 'from-emerald-50 to-emerald-25 border-emerald-200',
                        iconBgClass: 'bg-emerald-100',
                        iconColorClass: 'text-emerald-600',
                        titleClass: 'text-emerald-900',
                        subtitleClass: 'text-emerald-700',
                        labelClass: 'text-emerald-800',
                        inputClasses: 'border-emerald-300 focus:ring-emerald-500 focus:border-emerald-500',
                        addButtonClasses: 'border-emerald-300 text-emerald-600 hover:bg-emerald-50',
                        iconPath: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                    },
                    beneficiaries: {
                        title: 'بعد المستفيدين',
                        subtitle: 'Beneficiaries Perspective',
                        colorClasses: 'from-blue-50 to-blue-25 border-blue-200',
                        iconBgClass: 'bg-blue-100',
                        iconColorClass: 'text-blue-600',
                        titleClass: 'text-blue-900',
                        subtitleClass: 'text-blue-700',
                        labelClass: 'text-blue-800',
                        inputClasses: 'border-blue-300 focus:ring-blue-500 focus:border-blue-500',
                        addButtonClasses: 'border-blue-300 text-blue-600 hover:bg-blue-50',
                        iconPath: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
                    },
                    internal_processes: {
                        title: 'العمليات الداخلية',
                        subtitle: 'Internal Processes Perspective',
                        colorClasses: 'from-purple-50 to-purple-25 border-purple-200',
                        iconBgClass: 'bg-purple-100',
                        iconColorClass: 'text-purple-600',
                        titleClass: 'text-purple-900',
                        subtitleClass: 'text-purple-700',
                        labelClass: 'text-purple-800',
                        inputClasses: 'border-purple-300 focus:ring-purple-500 focus:border-purple-500',
                        addButtonClasses: 'border-purple-300 text-purple-600 hover:bg-purple-50',
                        iconPath: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'
                    },
                    learning_growth: {
                        title: 'التعلم والنمو',
                        subtitle: 'Learning & Growth Perspective',
                        colorClasses: 'from-amber-50 to-amber-25 border-amber-200',
                        iconBgClass: 'bg-amber-100',
                        iconColorClass: 'text-amber-600',
                        titleClass: 'text-amber-900',
                        subtitleClass: 'text-amber-700',
                        labelClass: 'text-amber-800',
                        inputClasses: 'border-amber-300 focus:ring-amber-500 focus:border-amber-500',
                        addButtonClasses: 'border-amber-300 text-amber-600 hover:bg-amber-50',
                        iconPath: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
                    }
                },

                summaryWordCount: 0,
                isSaving: false,
                message: {
                    type: '',
                    text: ''
                },
                nextInstanceId: 5, // Start after initial 4 dimensions

                get totalItems() {
                    return this.actionItems.length;
                },

                get completedItems() {
                    return this.actionItems.filter(item => item.title && item.title.trim() !== '').length;
                },

                get highPriorityItems() {
                    return this.actionItems.filter(item => item.priority === 'High').length;
                },

                init() {
                    // Initialize with 4 base dimensions if empty, but as array of instances
                    if (this.dimensionInstances.length === 0) {
                        const types = ['financial', 'beneficiaries', 'internal_processes', 'learning_growth'];
                        this.dimensionInstances = types.map((type, index) => ({
                            id: index + 1,
                            type: type,
                            strategic_goal: '',
                            performance_indicator: '',
                            initiatives: []
                        }));
                        this.nextInstanceId = 5;
                    } else {
                        // Calculate next ID based on existing instances
                        const maxId = Math.max(...this.dimensionInstances.map(d => d.id), 0);
                        this.nextInstanceId = maxId + 1;
                    }

                    if (!Array.isArray(this.actionItems)) {
                        this.actionItems = [];
                    }
                    this.updateWordCount('summary');
                    this.updateProgress();
                },

                // Duplicate a dimension instance
                // Add a new strategy for a specific dimension type
                addDimensionStrategy(type) {
                    this.dimensionInstances.push({
                        id: this.nextInstanceId++,
                        type: type,
                        strategic_goal: '',
                        performance_indicator: '',
                        initiatives: []
                    });
                },

                // Remove a dimension instance
                removeDimension(instanceId) {
                    const instance = this.dimensionInstances.find(d => d.id === instanceId);
                    if (!instance) return;

                    // Count instances of this type
                    const typeCount = this.dimensionInstances.filter(d => d.type === instance.type).length;

                    // Prevent removing if it's the last instance of this type
                    if (typeCount <= 1) {
                        this.showMessage('error', 'يجب أن يبقى استراتيجية واحدة على الأقل لكل بعد');
                        return;
                    }

                    if (!confirm('هل أنت متأكد من حذف هذه الاستراتيجية؟')) {
                        return;
                    }

                    this.dimensionInstances = this.dimensionInstances.filter(d => d.id !== instanceId);
                    this.showMessage('success', 'تم حذف الاستراتيجية بنجاح');
                },

                // Get config for a dimension type
                getConfig(type) {
                    return this.dimensionConfigs[type];
                },

                updateWordCount(field) {
                    const text = this[field] || '';
                    this.summaryWordCount = text.trim().split(/\s+/).filter(word => word.length > 0).length;
                },

                updateProgress() {
                    this.actionItems = [...this.actionItems];
                },

                addAction() {
                    const nextWeek = new Date();
                    nextWeek.setDate(nextWeek.getDate() + 7);
                    const formattedDate = nextWeek.toISOString().split('T')[0];

                    this.actionItems.push({
                        title: '',
                        owner: '',
                        priority: 'Medium',
                        deadline: formattedDate
                    });
                },

                removeAction(index) {
                    if (confirm('هل أنت متأكد من حذف هذه المهمة؟')) {
                        this.actionItems.splice(index, 1);
                    }
                },

                async save() {
                    try {
                        this.isSaving = true;

                        const csrf = document.querySelector('meta[name="csrf-token"]').content;
                        const url = `{{ route('swot.finalize.save', $project->id) }}`;

                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                summary: this.summary,
                                action_items: this.actionItems,
                                dimension_instances: this.dimensionInstances.map(instance => ({
                                    id: instance.id,
                                    type: instance.type,
                                    strategic_goal: instance.strategic_goal,
                                    performance_indicator: instance.performance_indicator,
                                    initiatives: instance.initiatives.filter(i => i && i
                                        .trim() !== '')
                                }))
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.showMessage('success', data.message || 'تم الحفظ بنجاح');
                            setTimeout(() => {
                                window.location.href = `{{ route('swot.admin', $project->id) }}`;
                            }, 1500);
                        } else {
                            this.showMessage('error', data.error || 'حدث خطأ أثناء الحفظ');
                        }
                    } catch (error) {
                        console.error('Save error:', error);
                        this.showMessage('error', 'حدث خطأ في الاتصال بالخادم');
                    } finally {
                        this.isSaving = false;
                    }
                },

                showMessage(type, text) {
                    this.message.type = type;
                    this.message.text = text;

                    if (type === 'success') {
                        setTimeout(() => {
                            if (this.message.type === 'success') {
                                this.message.text = '';
                            }
                        }, 5000);
                    }
                },

                swotData: {
                    strengths: @json($swotData['strengths']->pluck('content')->toArray()),
                    weaknesses: @json($swotData['weaknesses']->pluck('content')->toArray()),
                    opportunities: @json($swotData['opportunities']->pluck('content')->toArray()),
                    threats: @json($swotData['threats']->pluck('content')->toArray())
                },

                copyAllSwotToClipboard() {
                    const formatItems = (title, items) => {
                        if (items.length === 0) return `${title}:\nلا توجد عناصر`;
                        return `${title}:\n${items.map((item, index) => `${index + 1}. ${item}`).join('\n')}`;
                    };

                    const text = [
                        formatItems('نقاط القوة', this.swotData.strengths),
                        '',
                        formatItems('نقاط الضعف', this.swotData.weaknesses),
                        '',
                        formatItems('الفرص', this.swotData.opportunities),
                        '',
                        formatItems('التهديدات', this.swotData.threats)
                    ].join('\n');

                    navigator.clipboard.writeText(text).then(() => {
                        this.showMessage('success', 'تم نسخ جميع عناصر SWOT بنجاح');
                    }).catch(err => {
                        console.error('Failed to copy:', err);
                        this.showMessage('error', 'فشل النسخ إلى الحافظة');
                    });
                }
            }
        }
    </script>

    <style>
        .bg-green-25 {
            background-color: rgba(240, 253, 244, 0.5);
        }

        .bg-red-25 {
            background-color: rgba(254, 242, 242, 0.5);
        }

        .bg-yellow-25 {
            background-color: rgba(254, 252, 232, 0.5);
        }

        /* Custom scrollbar */
        textarea::-webkit-scrollbar {
            width: 6px;
        }

        textarea::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        textarea::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        textarea::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Select dropdown arrow */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .max-h-64::-webkit-scrollbar {
            width: 4px;
        }

        .max-h-64::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 2px;
        }

        .max-h-64::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 2px;
        }

        .max-h-64::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }

        .bg-green-25 {
            background-color: rgba(240, 253, 244, 0.3);
        }

        .bg-red-25 {
            background-color: rgba(254, 242, 242, 0.3);
        }

        .bg-blue-25 {
            background-color: rgba(239, 246, 255, 0.3);
        }

        .bg-yellow-25 {
            background-color: rgba(254, 252, 232, 0.3);
        }
    </style>
</x-app-layout>