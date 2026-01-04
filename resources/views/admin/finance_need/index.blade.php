<x-app-layout>
    <div class="max-w-7xl mx-auto p-6" x-data="needsManager()">
 
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">إجمالي الاحتياجات</p>
                        <p class="text-3xl font-bold mt-1" x-text="allNeeds.length"></p>
                    </div>
                    <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">عدد الفئات</p>
                        <p class="text-3xl font-bold mt-1" x-text="categories.length"></p>
                    </div>
                    <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">تمت الإضافة اليوم</p>
                        <p class="text-3xl font-bold mt-1" x-text="todayCount"></p>
                    </div>
                    <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                إدارة قائمة الاحتياجات
            </h2>

            <!-- Add Form -->
            <form action="{{ route('admin.finance_need.store') }}" method="POST" class="mb-8 bg-gray-50 p-6 rounded-lg border-2 border-dashed border-gray-300">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block mb-2 font-medium text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            اسم الاحتياج *
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                            placeholder="مثال: لابتوب، كرسي، وسيلة نقل"
                            required>
                        @error('name')
                        <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            الفئة
                        </label>
                        <div class="relative" x-data="{ showSuggestions: false, inputValue: '' }">
                            <input 
                                type="text" 
                                name="category" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="اختر من القائمة أو أدخل فئة جديدة"
                                x-model="inputValue"
                                @focus="showSuggestions = true"
                                @blur="setTimeout(() => showSuggestions = false, 200)"
                                list="categories-list"
                                autocomplete="off">
                            
                            <!-- Suggestions Dropdown -->
                            <div 
                                x-show="showSuggestions && categories.length > 0"
                                class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                <template x-for="cat in categories" :key="cat">
                                    <div 
                                        @click="inputValue = cat; showSuggestions = false"
                                        class="p-3 hover:bg-blue-50 cursor-pointer transition flex items-center justify-between group">
                                        <span x-text="cat"></span>
                                        <span 
                                            class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full"
                                            x-text="`${getCategoryCount(cat)} عنصر`"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">اختر فئة موجودة أو أنشئ فئة جديدة</p>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            الوصف
                        </label>
                        <input 
                            type="text" 
                            name="description" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="وصف مختصر (اختياري)">
                    </div>
                </div>

                <button 
                    type="submit"
                    class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    إضافة الاحتياج
                </button>
            </form>

            <!-- Filters and Search -->
            <div class="mb-6 flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input 
                            type="text" 
                            class="w-full border border-gray-300 rounded-lg p-3 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="بحث في الاحتياجات..."
                            x-model="searchTerm">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="flex gap-2">
                    <select 
                        class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        x-model="selectedCategory">
                        <option value="">كل الفئات</option>
                        <option value="uncategorized">بدون فئة</option>
                        <template x-for="cat in categories" :key="cat">
                            <option :value="cat" x-text="cat"></option>
                        </template>
                    </select>

                    <button 
                        @click="selectedCategory = ''; searchTerm = ''"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        إعادة تعيين
                    </button>
                </div>
            </div>

            <!-- Categories Pills -->
            <div class="mb-6 flex flex-wrap gap-2" x-show="categories.length > 0">
                <button 
                    @click="selectedCategory = ''"
                    class="px-3 py-1 rounded-full text-sm transition"
                    :class="selectedCategory === '' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'">
                    الكل (<span x-text="allNeeds.length"></span>)
                </button>
                <button 
                    @click="selectedCategory = 'uncategorized'"
                    class="px-3 py-1 rounded-full text-sm transition"
                    :class="selectedCategory === 'uncategorized' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'">
                    بدون فئة (<span x-text="uncategorizedCount"></span>)
                </button>
                <template x-for="cat in categories" :key="cat">
                    <button 
                        @click="selectedCategory = cat"
                        class="px-3 py-1 rounded-full text-sm transition"
                        :class="selectedCategory === cat ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'">
                        <span x-text="cat"></span> (<span x-text="getCategoryCount(cat)"></span>)
                    </button>
                </template>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="p-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">#</th>
                            <th class="p-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الاحتياج</th>
                            <th class="p-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الفئة</th>
                            <th class="p-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الوصف</th>
                            <th class="p-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">تاريخ الإضافة</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(need, index) in filteredNeeds" :key="need.id">
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="p-4 text-sm text-gray-900" x-text="need.id"></td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900" x-text="need.name"></span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span 
                                        x-show="need.category"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800"
                                        x-text="need.category"></span>
                                    <span 
                                        x-show="!need.category"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        بدون فئة
                                    </span>
                                </td>
                                <td class="p-4 text-sm text-gray-600">
                                    <span x-text="need.description || '-'"></span>
                                </td>
                                <td class="p-4 text-sm text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span x-text="need.created_at"></span>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredNeeds.length === 0">
                            <td colspan="5" class="p-8 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-lg">لا توجد نتائج</p>
                                <p class="text-sm text-gray-400 mt-1">جرب تغيير معايير البحث</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Results Count -->
            <div class="mt-4 text-sm text-gray-600 flex items-center justify-between">
                <span>
                    عرض <span class="font-semibold" x-text="filteredNeeds.length"></span> من أصل 
                    <span class="font-semibold" x-text="allNeeds.length"></span> احتياج
                </span>
            </div>
        </div>
    </div>

    <script>
        function needsManager() {
            return {
                searchTerm: '',
                selectedCategory: '',
                allNeeds: @json($needs),

                get categories() {
                    const cats = [...new Set(this.allNeeds
                        .filter(n => n.category)
                        .map(n => n.category))];
                    return cats.sort();
                },

                get filteredNeeds() {
                    let filtered = this.allNeeds;

                    // Filter by search term
                    if (this.searchTerm) {
                        const term = this.searchTerm.toLowerCase();
                        filtered = filtered.filter(need => 
                            need.name.toLowerCase().includes(term) ||
                            (need.category && need.category.toLowerCase().includes(term)) ||
                            (need.description && need.description.toLowerCase().includes(term))
                        );
                    }

                    // Filter by category
                    if (this.selectedCategory) {
                        if (this.selectedCategory === 'uncategorized') {
                            filtered = filtered.filter(need => !need.category);
                        } else {
                            filtered = filtered.filter(need => need.category === this.selectedCategory);
                        }
                    }

                    return filtered;
                },

                get uncategorizedCount() {
                    return this.allNeeds.filter(n => !n.category).length;
                },

                get todayCount() {
                    const today = new Date().toISOString().split('T')[0];
                    return this.allNeeds.filter(n => n.created_at.startsWith(today)).length;
                },

                getCategoryCount(category) {
                    return this.allNeeds.filter(n => n.category === category).length;
                }
            }
        }
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>