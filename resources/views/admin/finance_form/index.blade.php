<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <div class="max-w-7xl mx-auto p-6" x-data="financeForm()">
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم المشروع *</label>
                    <input 
                        type="text" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        x-model="title"
                        placeholder="أدخل اسم المشروع">
                    <span x-show="errors.title" x-text="errors.title" class="text-red-500 text-sm mt-1"></span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">وصف المشروع (اختياري)</label>
                    <textarea 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        x-model="description"
                        rows="3"
                        placeholder="أدخل وصف المشروع"></textarea>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- LEFT: Available needs -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">قائمة الاحتياجات المتاحة</h3>
                    <span class="text-sm text-gray-500" x-text="`${allNeeds.length} عنصر`"></span>
                </div>
                
                <!-- Search Box -->
                <div class="mb-4">
                    <input 
                        type="text" 
                        class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500"
                        x-model="searchTerm"
                        placeholder="بحث عن احتياج...">
                </div>

                <ul id="needsList" class="border-2 border-dashed border-gray-300 rounded-lg p-3 min-h-[400px] max-h-[500px] overflow-y-auto space-y-2">
                    <template x-for="need in filteredNeeds" :key="need.id">
                        <li 
                            class="p-3 rounded-lg cursor-move hover:shadow-md transition-all border"
                            :class="isSelected(need.id) ? 'bg-blue-100 border-blue-400 opacity-60' : 'bg-gradient-to-r from-gray-50 to-gray-100 border-gray-200'"
                            :data-id="need.id"
                            :data-name="need.name"
                            :data-category="need.category || ''">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800 flex items-center gap-2">
                                        <span x-text="need.name"></span>
                                        <span x-show="isSelected(need.id)" class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full">مضاف</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1" x-show="need.category" x-text="need.category"></div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </li>
                    </template>
                    <li x-show="filteredNeeds.length === 0" class="text-center text-gray-500 py-8">
                        لا توجد احتياجات متاحة
                    </li>
                </ul>

                <div class="mt-3 text-sm text-gray-600 bg-blue-50 p-3 rounded-lg border border-blue-200">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>اسحب الاحتياجات من هنا وأفلتها في قائمة احتياجات المشروع</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Items added to this form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">احتياجات المشروع</h3>
                    <span class="text-sm text-gray-500" x-text="`${selectedItems.length} عنصر`"></span>
                </div>

                <div id="formItems" class="border-2 border-dashed border-blue-300 rounded-lg p-3 min-h-[400px] max-h-[500px] overflow-y-auto space-y-3">
                    <template x-for="(item, index) in selectedItems" :key="item.id">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <div class="font-medium text-gray-800" x-text="item.name"></div>
                                    <div class="text-xs text-gray-500 mt-1" x-show="item.category" x-text="item.category"></div>
                                </div>
                                <button 
                                    @click="removeItem(index)"
                                    class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1 rounded transition-colors"
                                    title="حذف">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 mb-2">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">الكمية *</label>
                                    <input 
                                        type="number" 
                                        class="w-full border border-gray-300 rounded p-2 text-sm focus:ring-2 focus:ring-blue-500"
                                        x-model.number="item.quantity"
                                        @input="calculateItemTotal(index)"
                                        min="1"
                                        placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">سعر الوحدة *</label>
                                    <input 
                                        type="number" 
                                        class="w-full border border-gray-300 rounded p-2 text-sm focus:ring-2 focus:ring-blue-500"
                                        x-model.number="item.unit_price"
                                        @input="calculateItemTotal(index)"
                                        min="0"
                                        step="0.001"
                                        placeholder="0.000">
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center pt-2 border-t border-blue-200">
                                <span class="text-xs text-gray-600">المجموع:</span>
                                <span class="font-bold text-blue-600" x-text="formatCurrency(item.total_price)"></span>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="selectedItems.length === 0" class="text-center text-gray-500 py-12">
                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p>اسحب الاحتياجات من القائمة إلى هنا</p>
                    </div>
                </div>

                <!-- Total Summary -->
                <div class="mt-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white shadow-lg">
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
                class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors"
                @click="resetForm">
                إعادة تعيين
            </button>
            <button 
                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
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

    <script>
        function financeForm() {
            return {
                title: "",
                description: "",
                selectedItems: [],
                searchTerm: "",
                loading: false,
                errors: {},
                
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

                isSelected(needId) {
                    return this.selectedItems.some(item => item.id === needId);
                },

                init() {
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
                    if (confirm('هل تريد حذف هذا العنصر؟')) {
                        this.selectedItems.splice(index, 1);
                    }
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
                            window.location.href = "{{ route('admin.finance_form.index') }}";
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
            background: #e3f2fd !important;
        }

        /* Drag chosen styling */
        .sortable-chosen {
            cursor: grabbing !important;
        }
    </style>
</x-app-layout>