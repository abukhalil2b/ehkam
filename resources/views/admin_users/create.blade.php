<x-app-layout title="إنشاء مستخدم جديد">
    <div class="p-4 md:p-6 max-w-4xl mx-auto">

        {{-- Header --}}
        <header class="p-4 border-b border-gray-100 bg-white rounded-t-lg">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-2">
                <span class="material-icons text-3xl text-indigo-600">person_add</span>
                إنشاء موظف جديد وتعيينه
            </h1>
            <p class="text-gray-500 mt-1 text-sm">أدخل بيانات الموظف الأساسية وتعيينه الأولي في الهيكل التنظيمي.</p>
        </header>

        {{-- Form --}}
        <div class="bg-white p-6 rounded-b-lg shadow-xl border border-t-0">
            <form action="{{ route('admin_users.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Section 1: Basic Data --}}
                <div class="space-y-4 border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700">1. البيانات الشخصية وبيانات الدخول</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">الاسم الكامل</label>
                            <input type="text" name="name" id="name" required
                                class="form-input w-full border-gray-300 rounded-md shadow-sm p-2"
                                value="{{ old('name') }}">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                البريد الإلكتروني (يستخدم لتسجيل الدخول)
                            </label>
                            <input type="email" name="email" id="email" required
                                class="form-input w-full border-gray-300 rounded-md shadow-sm p-2"
                                value="{{ old('email') }}">
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور</label>
                            <div class="p-2 bg-gray-50 border border-gray-200 rounded-md text-gray-600 text-sm">
                                افتراضياً البريد الإلكتروني
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Section 2: Initial Assignment --}}
                <div class="space-y-4" x-data="positionSelector()">
                    <h2 class="text-lg font-semibold text-gray-700">2. التعيين الأولي (السجل الوظيفي)</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- UserType --}}
                        <div>
                            <label for="user_type" class="block text-sm font-medium text-gray-700">
                               نوع الحساب
                            </label>
                            <select name="user_type" id="user_type" class="form-select w-full border-gray-300 rounded-md shadow-sm p-2">
                                <option value="staff">حساب الموظف</option>
                            </select>
                        </div>

                        {{-- Org Unit (Custom Searchable Select) --}}
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700">الوحدة التنظيمية</label>
                            <input type="hidden" name="org_unit_id" x-model="selectedUnit" required>

                            <div class="relative mt-1">
                                <input type="text"
                                    class="form-input w-full border-gray-300 rounded-md shadow-sm p-2 pl-10 cursor-pointer"
                                    placeholder="-- ابحث أو اختر وحدة --"
                                    x-model="searchUnit"
                                    @focus="showUnitDropdown = true; searchUnit = ''"
                                    @click.away="showUnitDropdown = false; searchUnit = selectedUnitName"
                                    autocomplete="off"
                                >
                                <span class="absolute inset-y-0 left-0 flex items-center pl-2 cursor-pointer" @click="showUnitDropdown = !showUnitDropdown">
                                    <span class="material-icons text-gray-400">arrow_drop_down</span>
                                </span>
                            </div>

                            <ul x-show="showUnitDropdown" x-transition.opacity
                                class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                                <template x-for="unit in filteredUnits" :key="unit.id">
                                    <li class="px-3 py-2 cursor-pointer hover:bg-indigo-50 border-b border-gray-50 last:border-b-0"
                                        @click="selectUnit(unit)">
                                        <div class="font-medium text-sm text-gray-800" x-text="unit.name"></div>
                                        <div class="text-xs text-gray-500" x-text="'(' + unit.type + ')'"></div>
                                    </li>
                                </template>
                                <li x-show="filteredUnits.length === 0" class="px-3 py-3 text-sm text-gray-500 text-center">لا توجد نتائج مطابقة</li>
                            </ul>
                        </div>


                        {{-- Position (Custom Searchable Select) --}}
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700">المسمى الوظيفي</label>
                            <input type="hidden" name="position_id" x-model="selectedPosition" required>

                            <div class="relative mt-1">
                                <input type="text"
                                    class="form-input w-full border-gray-300 rounded-md shadow-sm p-2 pl-10 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                    placeholder="-- ابحث أو اختر مسمى --"
                                    x-model="searchPosition"
                                    @focus="showPositionDropdown = true; searchPosition = ''"
                                    @click.away="showPositionDropdown = false; searchPosition = selectedPositionName"
                                    :disabled="!selectedUnit || positions.length === 0"
                                    autocomplete="off"
                                >
                                <span class="absolute inset-y-0 left-0 flex items-center pl-2 cursor-pointer" @click="if(selectedUnit && positions.length > 0) showPositionDropdown = !showPositionDropdown">
                                    <span class="material-icons text-gray-400">arrow_drop_down</span>
                                </span>
                            </div>

                            <ul x-show="showPositionDropdown && positions.length > 0" x-transition.opacity
                                class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                                <template x-for="pos in filteredPositions" :key="pos.id">
                                    <li class="px-3 py-2 cursor-pointer hover:bg-indigo-50 border-b border-gray-50 text-sm text-gray-800 last:border-b-0"
                                        @click="selectPosition(pos)"
                                        x-text="pos.title">
                                    </li>
                                </template>
                                <li x-show="filteredPositions.length === 0" class="px-3 py-3 text-sm text-gray-500 text-center">لا توجد نتائج مطابقة</li>
                            </ul>

                            <div x-show="isLoading" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-sm p-2 text-sm text-indigo-500 flex items-center" style="display: none;">
                                <span class="material-icons text-sm rtl:ml-1 animate-spin">autorenew</span> جاري التحميل...
                            </div>
                        </div>

                        {{-- Start Date --}}
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">
                                تاريخ بدء العمل
                            </label>
                            <input type="date" name="start_date" id="start_date" required
                                class="form-input w-full border-gray-300 rounded-md shadow-sm p-2"
                                value="{{ old('start_date', now()->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="pt-4 border-t">
                    <button type="submit"
                        class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition flex items-center justify-center">
                        <span class="material-icons text-lg -mt-1 rtl:ml-1">save</span>
                        حفظ وإنشاء المستخدم
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Alpine.js script for Dynamic Searchable Selection --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('positionSelector', () => ({
                // Data
                units: @json($OrgUnits),
                positions: [],
                isLoading: false,

                // Hidden Values (For form submission)
                selectedUnit: '{{ old('org_unit_id') }}',
                selectedPosition: '{{ old('position_id') }}',

                // Display Names
                selectedUnitName: '',
                selectedPositionName: '',

                // Search Inputs & Dropdown States
                searchUnit: '',
                showUnitDropdown: false,
                searchPosition: '',
                showPositionDropdown: false,

                async init() {
                    // Load initial Unit state if 'old()' value exists (Validation Error fallback)
                    if (this.selectedUnit) {
                        const unit = this.units.find(u => u.id == this.selectedUnit);
                        if (unit) {
                            this.selectedUnitName = unit.name;
                            this.searchUnit = unit.name;
                        }
                        await this.loadPositions();
                    }

                    // Watch for Unit ID changes
                    this.$watch('selectedUnit', async (value) => {
                        if (value) {
                            // Reset position when unit changes
                            this.selectedPosition = '';
                            this.selectedPositionName = '';
                            this.searchPosition = '';
                            await this.loadPositions();
                        } else {
                            this.positions = [];
                        }
                    });
                },

                // Filters
                get filteredUnits() {
                    if (this.searchUnit === '') return this.units;
                    const term = this.searchUnit.toLowerCase();
                    return this.units.filter(u => 
                        u.name.toLowerCase().includes(term) || 
                        u.type.toLowerCase().includes(term)
                    );
                },

                get filteredPositions() {
                    if (this.searchPosition === '') return this.positions;
                    const term = this.searchPosition.toLowerCase();
                    return this.positions.filter(p => p.title.toLowerCase().includes(term));
                },

                // Selection Events
                selectUnit(unit) {
                    this.selectedUnit = unit.id;
                    this.selectedUnitName = unit.name;
                    this.searchUnit = unit.name;
                    this.showUnitDropdown = false;
                },

                selectPosition(pos) {
                    this.selectedPosition = pos.id;
                    this.selectedPositionName = pos.title;
                    this.searchPosition = pos.title;
                    this.showPositionDropdown = false;
                },

                // API Call
                async loadPositions() {
                    if (!this.selectedUnit) {
                        this.positions = [];
                        return;
                    }

                    this.isLoading = true;
                    try {
                        const response = await fetch(`{{ route('admin.api.positions_by_unit') }}?unit_id=${this.selectedUnit}`);
                        const data = await response.json();
                        this.positions = data;

                        // Keep old position selection if it's still valid in the new unit
                        if (this.selectedPosition) {
                            const pos = data.find(p => p.id == this.selectedPosition);
                            if (pos) {
                                this.selectedPositionName = pos.title;
                                this.searchPosition = pos.title;
                            } else {
                                this.selectedPosition = '';
                                this.selectedPositionName = '';
                                this.searchPosition = '';
                            }
                        }
                    } catch (error) {
                        console.error('Error fetching positions:', error);
                        this.positions = [];
                    } finally {
                        this.isLoading = false;
                    }
                },
            }));
        });
    </script>
</x-app-layout>