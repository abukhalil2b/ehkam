<x-app-layout>
    <div dir="rtl" class="container mx-auto px-4 py-8" x-data="pestleFinalize()" x-init="init()">
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
                            <a href="{{ route('pestle.admin', $project->id) }}"
                                class="text-gray-600 hover:text-blue-600 text-sm">
                                إدارة PESTLE
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
                        <p class="text-gray-600 mt-1">صفحة تلخيص واستنتاجات PESTLE</p>
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
                                <p class="text-gray-500 text-sm mt-1">ملخص شامل لتحليل PESTLE</p>
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
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">إحصائيات سريعة</h3>
                        <div class="space-y-4">
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
                                <span>حلل كل بعد بدقة وشمولية</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>اربط النتائج باستراتيجيات فعلية</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- PESTLE Reference Panel -->
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
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">مرجع تحليل PESTLE</h2>
                                    <p class="text-sm text-gray-600">راجع العناصر المدخلة قبل صياغة الاستراتيجيات</p>
                                </div>
                            </div>
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

                    <!-- Content -->
                    <div x-show="showReference" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6">
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                            @php
                                $categories = [
                                    'political' => ['title' => 'السياسية', 'color' => 'red'],
                                    'economic' => ['title' => 'الاقتصادية', 'color' => 'blue'],
                                    'social' => ['title' => 'الاجتماعية', 'color' => 'yellow'],
                                    'technological' => ['title' => 'التقنية', 'color' => 'purple'],
                                    'legal' => ['title' => 'القانونية', 'color' => 'gray'],
                                    'environmental' => ['title' => 'البيئية', 'color' => 'emerald'],
                                ];
                            @endphp

                            @foreach ($categories as $type => $meta)
                                <div
                                    class="bg-{{ $meta['color'] }}-50 border-2 border-{{ $meta['color'] }}-100 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="font-bold text-{{ $meta['color'] }}-900">{{ $meta['title'] }}</h3>
                                        <span
                                            class="px-2 py-0.5 bg-{{ $meta['color'] }}-200 text-{{ $meta['color'] }}-800 text-xs font-bold rounded-full">
                                            {{ isset($data[$type]) ? $data[$type]->count() : 0 }}
                                        </span>
                                    </div>
                                    <div class="space-y-2 max-h-48 overflow-y-auto">
                                        @forelse($data[$type] ?? [] as $item)
                                            <div
                                                class="bg-white/80 rounded p-2 text-sm text-gray-800 border border-{{ $meta['color'] }}-100">
                                                {{ $item->content }}
                                            </div>
                                        @empty
                                            <div class="text-center py-2 text-{{ $meta['color'] }}-600 text-xs">
                                                لا توجد عناصر
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

            <!-- Strategies Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">صياغة الاستراتيجيات</h2>
                        <p class="text-gray-500 text-sm mt-1">حدد الأهداف والمؤشرات والمبادرات لكل بعد</p>
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 gap-6">
                    @foreach ($categories as $type => $meta)
                        <div
                            class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-gray-100">
                                <div
                                    class="w-10 h-10 rounded-lg bg-{{ $meta['color'] }}-100 flex items-center justify-center">
                                    <span
                                        class="text-{{ $meta['color'] }}-700 font-bold text-lg">{{ strtoupper(substr($type, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg">{{ $meta['title'] }}</h3>
                                    <p class="text-xs text-gray-500 uppercase">{{ $type }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">الهدف الاستراتيجي</label>
                                    <input type="text" x-model="strategies.{{ $type }}.strategic_goal"
                                        class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ $meta['color'] }}-500 focus:border-{{ $meta['color'] }}-500 transition duration-200"
                                        placeholder="ما الذي نريد تحقيقه؟">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">مؤشر الأداء (KPI)</label>
                                    <input type="text" x-model="strategies.{{ $type }}.performance_indicator"
                                        class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ $meta['color'] }}-500 focus:border-{{ $meta['color'] }}-500 transition duration-200"
                                        placeholder="كيف نقيس النجاح؟">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">المبادرات</label>
                                    <div class="space-y-2">
                                        <template x-for="(initiative, index) in strategies.{{ $type }}.initiatives"
                                            :key="index">
                                            <div class="flex items-center gap-2">
                                                <input type="text" x-model="strategies.{{ $type }}.initiatives[index]"
                                                    class="flex-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ $meta['color'] }}-500 focus:border-{{ $meta['color'] }}-500 transition duration-200"
                                                    placeholder="أدخل المبادرة">
                                                <button type="button"
                                                    @click="strategies.{{ $type }}.initiatives.splice(index, 1)"
                                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                        <button type="button" @click="strategies.{{ $type }}.initiatives.push('')"
                                            class="w-full py-2 border-2 border-dashed border-gray-300 text-gray-500 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            إضافة مبادرة
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200 print:hidden">
                <div>
                    <a href="{{ route('pestle.admin', $project->id) }}"
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
                        class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-all duration-200 inline-flex items-center disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg transform hover:-translate-y-0.5">
                        <template x-if="isSaving">
                            <svg class="animate-spin -mr-1 ml-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </template>
                        <span x-text="isSaving ? 'جاري الحفظ...' : 'حفظ التلخيص'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pestleFinalize() {
            return {
                summary: @json($finalize->summary ?? ''),
                strategies: {
                    @foreach ($categories as $type => $meta)
                               '{{ $type }}': {
                            strategic_goal: @json($strategies[$type]['strategic_goal'] ?? ''),
                            performance_indicator: @json($strategies[$type]['performance_indicator'] ?? ''),
                            initiatives: @json($strategies[$type]['initiatives'] ?? [])
                        },
                    @endforeach
                },
        summaryWordCount: 0,
            isSaving: false,
                message: {
            type: '',
                text: ''
        },

        init() {
            // Ensure initiatives are arrays
            const types = ['political', 'economic', 'social', 'technological', 'legal', 'environmental'];
            types.forEach(type => {
                if (!this.strategies[type]) {
                    this.strategies[type] = { strategic_goal: '', performance_indicator: '', initiatives: [] };
                }
                if (!Array.isArray(this.strategies[type].initiatives)) {
                    this.strategies[type].initiatives = [];
                }
            });
            this.updateWordCount('summary');
        },

        updateWordCount(field) {
            const text = this[field] || '';
            this.summaryWordCount = text.trim().split(/\s+/).filter(word => word.length > 0).length;
        },

                async save() {
            try {
                this.isSaving = true;

                const csrf = document.querySelector('meta[name="csrf-token"]').content;
                const url = `{{ route('pestle.finalize.save', $project->id) }}`;

                // Filter out empty initiatives
                const cleanedStrategies = {};
                const types = ['political', 'economic', 'social', 'technological', 'legal', 'environmental'];

                types.forEach(type => {
                    cleanedStrategies[type] = {
                        strategic_goal: this.strategies[type].strategic_goal,
                        performance_indicator: this.strategies[type].performance_indicator,
                        initiatives: this.strategies[type].initiatives.filter(i => i && i.trim() !== '')
                    };
                });

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        summary: this.summary,
                        strategies: cleanedStrategies
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.showMessage('success', data.message || 'تم الحفظ بنجاح');
                    setTimeout(() => {
                        window.location.href = `{{ route('pestle.admin', $project->id) }}`;
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
        }
            }
        }
    </script>
</x-app-layout>