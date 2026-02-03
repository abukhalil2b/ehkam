<x-app-layout title="إدارة نماذج الاحتياجات المالية">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <div class="max-w-7xl mx-auto p-4 md:p-6" x-data="financeFormManager()">

        <!-- Header -->
        <header class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-t-lg shadow-md border-b dark:border-gray-700 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    إدارة نماذج الاحتياجات المالية
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">إنشاء وإدارة نماذج طلبات الاحتياجات المالية للمشاريع</p>
            </div>
            <button 
                @click="showCreateForm = !showCreateForm"
                class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition duration-150 ease-in-out">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="showCreateForm ? 'rotate-45' : ''" style="transition: transform 0.3s">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span x-text="showCreateForm ? 'إغلاق' : 'إنشاء نموذج جديد'"></span>
            </button>
        </header>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm">إجمالي النماذج</p>
                        <p class="text-3xl font-bold mt-1">{{ $forms->count() }}</p>
                    </div>
                    <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">إجمالي التكاليف</p>
                        <p class="text-2xl font-bold mt-1" dir="ltr">{{ number_format($forms->sum('total_cost'), 3) }} ر.ع</p>
                    </div>
                    <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">الاحتياجات المتاحة</p>
                        <p class="text-3xl font-bold mt-1">{{ $needs->count() }}</p>
                    </div>
                    <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="mr-3">
                        <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Create Form Section (Collapsible) -->
        <div x-show="showCreateForm" x-collapse class="mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border dark:border-gray-700">
                <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-white flex items-center gap-3">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    إنشاء نموذج جديد
                </h2>
                
                <!-- Form Header -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6 border-2 border-dashed border-gray-300 dark:border-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                اسم المشروع *
                            </label>
                            <input 
                                type="text" 
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                x-model="title"
                                placeholder="أدخل اسم المشروع">
                            <span x-show="errors.title" x-text="errors.title" class="text-red-500 text-sm mt-1"></span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                وصف المشروع (اختياري)
                            </label>
                            <input 
                                type="text" 
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                x-model="description"
                                placeholder="أدخل وصف المشروع">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- LEFT: Available needs -->
                    <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/30">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                قائمة الاحتياجات المتاحة
                            </h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded-full" x-text="`${allNeeds.length} عنصر`"></span>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="mb-4 relative">
                            <input 
                                type="text" 
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 pr-10 focus:ring-2 focus:ring-indigo-500 text-sm"
                                x-model="searchTerm"
                                placeholder="بحث عن احتياج...">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <ul id="needsList" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-3 min-h-[300px] max-h-[400px] overflow-y-auto space-y-2 bg-white dark:bg-gray-800">
                            <template x-for="need in filteredNeeds" :key="need.id + '-' + selectedItems.length">
                                <li 
                                    class="p-3 rounded-lg cursor-move hover:shadow-md transition-all border"
                                    :class="selectedItemIds.includes(need.id) ? 'bg-indigo-100 dark:bg-indigo-900/50 border-indigo-400 dark:border-indigo-600 opacity-60' : 'bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 border-gray-200 dark:border-gray-600'"
                                    :data-id="need.id"
                                    :data-name="need.name"
                                    :data-category="need.category || ''">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-800 dark:text-white flex items-center gap-2">
                                                <span x-text="need.name"></span>
                                                <span x-show="selectedItemIds.includes(need.id)" class="text-xs bg-indigo-600 text-white px-2 py-0.5 rounded-full">مضاف</span>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-show="need.category" x-text="need.category"></div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </li>
                            </template>
                            <li x-show="filteredNeeds.length === 0" class="text-center text-gray-500 dark:text-gray-400 py-8">
                                لا توجد احتياجات متاحة
                            </li>
                        </ul>

                        <div class="mt-3 text-sm text-gray-600 dark:text-gray-300 bg-indigo-50 dark:bg-indigo-900/30 p-3 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>اسحب الاحتياجات من هنا وأفلتها في قائمة احتياجات المشروع</span>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: Items added to this form -->
                    <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/30">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                احتياجات المشروع
                            </h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400 bg-green-200 dark:bg-green-900 px-2 py-1 rounded-full" x-text="`${selectedItems.length} عنصر`"></span>
                        </div>

                        <div id="formItems" class="border-2 border-dashed border-green-300 dark:border-green-700 rounded-lg p-3 min-h-[300px] max-h-[400px] overflow-y-auto space-y-3 bg-white dark:bg-gray-800">
                            <template x-for="(item, index) in selectedItems" :key="item.id">
                                <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <div class="font-medium text-gray-800 dark:text-white" x-text="item.name"></div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-show="item.category" x-text="item.category"></div>
                                        </div>
                                        <button 
                                            @click="removeItem(index)"
                                            class="text-red-500 hover:text-white hover:bg-red-500 p-1.5 rounded-lg transition-all duration-200 group"
                                            title="إزالة من القائمة">
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3 mb-2">
                                        <div>
                                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">الكمية *</label>
                                            <input 
                                                type="number" 
                                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded p-2 text-sm focus:ring-2 focus:ring-indigo-500"
                                                x-model.number="item.quantity"
                                                @input="calculateItemTotal(index)"
                                                min="1"
                                                placeholder="0">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">سعر الوحدة *</label>
                                            <input 
                                                type="number" 
                                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded p-2 text-sm focus:ring-2 focus:ring-indigo-500"
                                                x-model.number="item.unit_price"
                                                @input="calculateItemTotal(index)"
                                                min="0"
                                                step="0.001"
                                                placeholder="0.000">
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center pt-2 border-t border-green-200 dark:border-green-700">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">المجموع:</span>
                                        <span class="font-bold text-green-600 dark:text-green-400" x-text="formatCurrency(item.total_price)"></span>
                                    </div>
                                </div>
                            </template>
                            
                            <div x-show="selectedItems.length === 0" class="text-center text-gray-500 dark:text-gray-400 py-12">
                                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p>اسحب الاحتياجات من القائمة إلى هنا</p>
                            </div>
                        </div>

                        <!-- Total Summary -->
                        <div class="mt-4 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg p-4 text-white shadow-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-medium">المجموع الكلي:</span>
                                <span class="text-2xl font-bold" x-text="formatCurrency(totalCost)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex gap-3 justify-end">
                    <button 
                        class="px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors"
                        @click="resetForm">
                        إعادة تعيين
                    </button>
                    <button 
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                        @click="saveForm"
                        :disabled="loading || selectedItems.length === 0 || !title">
                        <svg x-show="loading" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'جاري الحفظ...' : 'حفظ النموذج'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Forms List Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700">
            <div class="p-6 border-b dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    قائمة النماذج المحفوظة
                </h2>
            </div>

            @if($forms->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">لا توجد نماذج</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">ابدأ بإنشاء نموذج جديد</p>
                    <button 
                        @click="showCreateForm = true"
                        class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        إنشاء نموذج جديد
                    </button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">اسم المشروع</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">عدد العناصر</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">التكلفة الإجمالية</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">المنشئ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">تاريخ الإنشاء</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($forms as $form)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $form->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $form->title }}</div>
                                                @if($form->description)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($form->description, 40) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                            {{ $form->items->count() }} عنصر
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            {{ number_format($form->total_cost, 3) }} ر.ع
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $form->creator->name ?? 'غير معروف' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $form->created_at->format('Y-m-d') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center gap-2 justify-end">
                                            <button 
                                                @click="viewForm({{ $form->id }})"
                                                class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition p-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg"
                                                title="عرض التفاصيل">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <form action="{{ route('admin.finance_form.destroy', $form) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا النموذج؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit"
                                                    class="inline-flex items-center gap-1 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition p-2 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg"
                                                    title="حذف">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- View Form Modal -->
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>
                
                <div class="relative bg-white dark:bg-gray-800 rounded-xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-2xl sm:w-full">
                    <div class="px-6 py-4 border-b dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="modalForm?.title || 'تفاصيل النموذج'"></h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="px-6 py-4 max-h-96 overflow-y-auto">
                        <div x-show="modalLoading" class="text-center py-8">
                            <svg class="animate-spin h-8 w-8 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        
                        <div x-show="!modalLoading && modalForm">
                            <p class="text-gray-600 dark:text-gray-400 mb-4" x-text="modalForm?.description || 'لا يوجد وصف'"></p>
                            
                            <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300">الاحتياج</th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300">الكمية</th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300">سعر الوحدة</th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300">المجموع</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        <template x-for="item in modalForm?.items || []" :key="item.id">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-white" x-text="item.need?.name || '-'"></td>
                                                <td class="px-4 py-2 text-sm text-center text-gray-600 dark:text-gray-300" x-text="item.quantity"></td>
                                                <td class="px-4 py-2 text-sm text-center text-gray-600 dark:text-gray-300" x-text="formatCurrency(item.unit_price)"></td>
                                                <td class="px-4 py-2 text-sm text-center font-medium text-green-600 dark:text-green-400" x-text="formatCurrency(item.total_price)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg p-4 text-white">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">المجموع الكلي:</span>
                                    <span class="text-xl font-bold" x-text="formatCurrency(modalForm?.total_cost)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t dark:border-gray-700">
                        <button @click="showModal = false" class="w-full px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition">
                            إغلاق
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function financeFormManager() {
            return {
                title: "",
                description: "",
                selectedItems: [],
                searchTerm: "",
                loading: false,
                errors: {},
                showCreateForm: false,
                showModal: false,
                modalForm: null,
                modalLoading: false,
                
                // All needs from backend
                allNeeds: @json($needs),

                get filteredNeeds() {
                    if (!this.searchTerm) return this.allNeeds;
                    
                    const term = this.searchTerm.toLowerCase();
                    return this.allNeeds.filter(need => 
                        need.name.toLowerCase().includes(term) ||
                        (need.category && need.category.toLowerCase().includes(term))
                    );
                },

                get totalCost() {
                    return this.selectedItems.reduce((sum, item) => sum + (item.total_price || 0), 0);
                },

                get selectedItemIds() {
                    return this.selectedItems.map(item => item.id);
                },

                isSelected(needId) {
                    return this.selectedItemIds.includes(needId);
                },

                init() {
                    this.$nextTick(() => {
                        const needsList = document.getElementById('needsList');
                        const formItems = document.getElementById('formItems');
                        
                        if (needsList && formItems) {
                            // Make available needs list draggable (clone mode - items stay in source)
                            new Sortable(needsList, {
                                group: {
                                    name: 'shared',
                                    pull: 'clone',
                                    put: false
                                },
                                sort: false,
                                animation: 150,
                                onEnd: (evt) => {
                                    // Remove the cloned element from source if it wasn't added
                                    if (evt.pullMode === 'clone' && evt.to === evt.from) {
                                        evt.item.remove();
                                    }
                                }
                            });

                            // Make selected items list droppable
                            new Sortable(formItems, {
                                group: 'shared',
                                animation: 150,
                                onAdd: (evt) => {
                                    const needId = parseInt(evt.item.dataset.id);
                                    const needName = evt.item.dataset.name;
                                    const needCategory = evt.item.dataset.category;
                                    
                                    // Always remove the dragged clone element
                                    evt.item.remove();
                                    
                                    // Add to selected items
                                    this.addNeed(needId, needName, needCategory);
                                }
                            });
                        }
                    });
                },

                addNeed(needId, needName, needCategory) {
                    // Prevent duplicates
                    if (this.selectedItems.find(i => i.id === needId)) {
                        alert('هذا العنصر مضاف بالفعل');
                        return;
                    }

                    this.selectedItems.push({
                        id: needId,
                        name: needName,
                        category: needCategory || '',
                        quantity: 1,
                        unit_price: 0,
                        total_price: 0
                    });
                },

                removeItem(index) {
                    // Remove item directly without confirmation for better UX
                    this.selectedItems.splice(index, 1);
                    // Force Alpine to recognize the change
                    this.selectedItems = [...this.selectedItems];
                },

                calculateItemTotal(index) {
                    const item = this.selectedItems[index];
                    item.total_price = (item.quantity || 0) * (item.unit_price || 0);
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('ar-OM', {
                        style: 'currency',
                        currency: 'OMR',
                        minimumFractionDigits: 3,
                        maximumFractionDigits: 3
                    }).format(amount || 0);
                },

                validate() {
                    this.errors = {};
                    
                    if (!this.title || this.title.trim() === '') {
                        this.errors.title = 'اسم المشروع مطلوب';
                        return false;
                    }
                    
                    if (this.selectedItems.length === 0) {
                        alert('الرجاء إضافة احتياج واحد على الأقل');
                        return false;
                    }

                    // Validate each item has quantity and price
                    for (let item of this.selectedItems) {
                        if (!item.quantity || item.quantity <= 0) {
                            alert(`الرجاء إدخال الكمية لـ: ${item.name}`);
                            return false;
                        }
                        if (item.unit_price === null || item.unit_price === undefined || item.unit_price < 0) {
                            alert(`الرجاء إدخال سعر الوحدة لـ: ${item.name}`);
                            return false;
                        }
                    }

                    return Object.keys(this.errors).length === 0;
                },

                async saveForm() {
                    if (!this.validate()) return;

                    this.loading = true;

                    try {
                        const response = await fetch("{{ route('admin.finance_form.store') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                title: this.title,
                                description: this.description,
                                items: this.selectedItems.map(item => ({
                                    id: item.id,
                                    quantity: item.quantity,
                                    unit_price: item.unit_price
                                }))
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            alert("تم حفظ النموذج بنجاح!");
                            window.location.reload();
                        } else {
                            alert("حدث خطأ أثناء الحفظ: " + (data.message || 'خطأ غير معروف'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert("حدث خطأ في الاتصال بالخادم");
                    } finally {
                        this.loading = false;
                    }
                },

                resetForm() {
                    if (confirm('هل أنت متأكد من إعادة تعيين النموذج؟ سيتم حذف جميع البيانات المدخلة.')) {
                        this.title = "";
                        this.description = "";
                        this.selectedItems = [];
                        this.searchTerm = "";
                        this.errors = {};
                    }
                },

                async viewForm(formId) {
                    this.showModal = true;
                    this.modalLoading = true;
                    this.modalForm = null;

                    try {
                        const response = await fetch(`/admin/finance_form/${formId}`);
                        const data = await response.json();
                        this.modalForm = data;
                    } catch (error) {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء جلب البيانات');
                        this.showModal = false;
                    } finally {
                        this.modalLoading = false;
                    }
                }
            }
        }
    </script>

    <style>
        /* RTL Support */
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar */
        #needsList::-webkit-scrollbar,
        #formItems::-webkit-scrollbar {
            width: 8px;
        }
        
        #needsList::-webkit-scrollbar-track,
        #formItems::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        #needsList::-webkit-scrollbar-thumb,
        #formItems::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        #needsList::-webkit-scrollbar-thumb:hover,
        #formItems::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Drag ghost styling */
        .sortable-ghost {
            opacity: 0.3;
            background: #e0e7ff !important;
        }

        /* Drag chosen styling */
        .sortable-chosen {
            cursor: grabbing !important;
        }

        /* Dark mode scrollbar */
        .dark #needsList::-webkit-scrollbar-track,
        .dark #formItems::-webkit-scrollbar-track {
            background: #374151;
        }
        
        .dark #needsList::-webkit-scrollbar-thumb,
        .dark #formItems::-webkit-scrollbar-thumb {
            background: #6b7280;
        }
    </style>
</x-app-layout>
