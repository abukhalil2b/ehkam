<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h1 class="text-2xl font-bold text-gray-900">إدارة المؤشرات {{ $current_year }}</h1>
            <div class="mt-2 md:mt-0 flex items-center">
                <span id="indicators-count" class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                    {{ count($indicators) }} مؤشر
                </span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8" dir="rtl">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <a href="{{ route('indicator.create') }}"
                class="inline-flex items-center justify-center px-5 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-150 ease-in-out shadow-sm">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة مؤشر جديد
            </a>

            <!-- Filters and Search -->
            <div class="flex flex-wrap gap-3">
                <div class="relative">
                    <input type="text" id="search-input" placeholder="بحث في المؤشرات..."
                        class="pr-10 pl-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 w-64">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <select id="type-filter"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    <option value="all">جميع الأنواع</option>
                    <option value="main">رئيسي</option>
                    <option value="sub">فرعي</option>
                </select>

                <button id="clear-filters"
                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-150">
                    مسح الفلاتر
                </button>
            </div>
        </div>

        <!-- No Results Message (Hidden by default) -->
        <div id="no-results" class="hidden text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">لا توجد نتائج</h3>
            <p class="mt-2 text-gray-500">جرب تغيير كلمات البحث أو الفلاتر.</p>
        </div>

        <!-- Indicators Grid -->
        <div id="indicators-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($indicators as $indicator)
                <div class="indicator-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md"
                    data-type="{{ $indicator->period ? 'sub' : 'main' }}"
                    data-title="{{ strtolower($indicator->title) }}"
                    data-owner="{{ strtolower($indicator->owner ?? '') }}"
                    data-code="{{ strtolower($indicator->code ?? '') }}">
                    <!-- Card Header -->
                    <div class="p-5 border-b border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg text-gray-800 line-clamp-2">{{ $indicator->title }}</h3>
                                <div class="flex items-center mt-2">
                                    <span
                                        class="indicator-type inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $indicator->period ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $indicator->is_main ? 'رئيسي' :  'فرعي'  }}
                                    </span>
                                    <span class="text-xs text-gray-500 mr-2">{{ $indicator->code ?? 'بدون رمز' }}</span>
                                </div>
                            </div>
                            <div class="text-gray-400 hover:text-gray-600 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5">
                        <div class="text-sm text-gray-600 mb-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-gray-800 font-medium">مالك المؤشر:</span>
                                <span class="mr-2">{{ $indicator->owner ?? 'غير محدد' }}</span>
                            </div>

                            @if ($indicator->first_observation_date)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-gray-800 font-medium">تاريخ الرصد الأول:</span>
                                    <span class="mr-2">{{ $indicator->first_observation_date }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Progress Bar (if applicable) -->
                        <div class="mb-4">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>التقدم</span>
                                <span>65%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>

                        <div class="text-xs">المستهدف:{{ $indicator->target_for_indicator }} لسنة:{{ $indicator->current_year }}</div>
                    </div>

                    <!-- Card Footer - Action Buttons (3-column layout) -->
                    <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                        <div class="grid grid-cols-3 gap-2">
                            <a href="{{ route('indicator.edit', $indicator->id) }}"
                                class="flex items-center justify-center px-3 py-2 bg-white border border-indigo-500 text-indigo-600 hover:bg-indigo-50 text-sm font-medium rounded-lg transition duration-150">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                تعديل
                            </a>

                            <a href="{{ route('indicator.show', $indicator->id) }}"
                                class="flex items-center justify-center px-3 py-2 bg-white border border-blue-500 text-blue-600 hover:bg-blue-50 text-sm font-medium rounded-lg transition duration-150">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                تفاصيل
                            </a>

                            <a href="{{ route('indicator.target', $indicator->id) }}"
                                class="flex items-center justify-center px-3 py-2 bg-white border border-green-500 text-green-600 hover:bg-green-50 text-sm font-medium rounded-lg transition duration-150">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                                المستهدف
                            </a>

                            <a href="{{ route('indicator.achieved', $indicator->id) }}"
                                class="flex items-center justify-center px-3 py-2 bg-white border border-purple-500 text-purple-600 hover:bg-purple-50 text-sm font-medium rounded-lg transition duration-150">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                المحقق
                            </a>

                            <a href="{{ route('project.index',$indicator->id) }}"
                                class="flex items-center justify-center px-3 py-2 bg-white border border-orange-500 text-orange-600 hover:bg-orange-50 text-sm font-medium rounded-lg transition duration-150 col-span-2">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                المشاريع
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if (count($indicators) === 0)
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">لا توجد مؤشرات</h3>
                <p class="mt-2 text-gray-500">ابدأ بإضافة أول مؤشر لك.</p>
                <div class="mt-6">
                    <a href="{{ route('indicator.create') }}"
                        class="inline-flex items-center px-5 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-150">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        إضافة مؤشر جديد
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const typeFilter = document.getElementById('type-filter');
            const clearFilters = document.getElementById('clear-filters');
            const indicatorsGrid = document.getElementById('indicators-grid');
            const noResults = document.getElementById('no-results');
            const indicatorsCount = document.getElementById('indicators-count');

            const indicatorCards = document.querySelectorAll('.indicator-card');
            const totalIndicators = indicatorCards.length;

            // Update indicators count
            function updateIndicatorsCount(visibleCount) {
                indicatorsCount.textContent = `${visibleCount} مؤشر`;
            }

            // Filter indicators function
            function filterIndicators() {
                const searchTerm = searchInput.value.toLowerCase();
                const typeValue = typeFilter.value;

                let visibleCount = 0;

                indicatorCards.forEach(card => {
                    const title = card.getAttribute('data-title');
                    const owner = card.getAttribute('data-owner');
                    const code = card.getAttribute('data-code');
                    const type = card.getAttribute('data-type');

                    const matchesSearch = searchTerm === '' ||
                        title.includes(searchTerm) ||
                        owner.includes(searchTerm) ||
                        code.includes(searchTerm);

                    const matchesType = typeValue === 'all' || type === typeValue;

                    if (matchesSearch && matchesType) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                    indicatorsGrid.classList.add('hidden');
                } else {
                    noResults.classList.add('hidden');
                    indicatorsGrid.classList.remove('hidden');
                }

                updateIndicatorsCount(visibleCount);
            }

            // Event listeners
            searchInput.addEventListener('input', filterIndicators);
            typeFilter.addEventListener('change', filterIndicators);

            clearFilters.addEventListener('click', function() {
                searchInput.value = '';
                typeFilter.value = 'all';
                filterIndicators();
            });

            // Initialize
            updateIndicatorsCount(totalIndicators);
        });
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .indicator-card {
            transition: all 0.3s ease;
        }
    </style>
</x-app-layout>
