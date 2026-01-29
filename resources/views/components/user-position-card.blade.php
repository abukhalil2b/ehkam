@props(['user', 'positions', 'units'])

@php
    // Use currentHistory to get the active (end_date is NULL) record.
    $activeHistory = $user->currentHistory;
    $position = $activeHistory?->position;
    $unit = $activeHistory?->OrgUnit;

    $isAssigned = (bool)$activeHistory;
@endphp

<div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6" id="position-card" x-data="positionAssignment()">
    <h2 class="text-xl font-semibold mb-4 text-purple-600 border-b pb-2 dark:text-purple-400 flex items-center gap-2">
        <span class="material-icons">work</span>
        المسمى الوظيفي الحالي
    </h2>

    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
        {{-- Display Current Position/Unit --}}
        <div class="py-3 flex justify-between text-sm">
            <dt class="font-medium text-gray-500 dark:text-gray-400">المسمى وظيفي:</dt>
            <dd class="text-gray-900 dark:text-white font-semibold">
                @if($position)
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-50 text-purple-700 rounded text-xs font-bold border border-purple-100">
                        <span class="material-icons text-xs">badge</span>
                        {{ $position->title }}
                    </span>
                @else
                    <span class="text-gray-400">غير مخصص</span>
                @endif
            </dd>
        </div>
        <div class="py-3 flex justify-between text-sm">
            <dt class="font-medium text-gray-500 dark:text-gray-400">الوحدة التنظيمية:</dt>
            <dd class="text-gray-900 dark:text-white">
                @if($unit)
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-bold border border-blue-100">
                        <span class="material-icons text-xs">domain</span>
                        {{ $unit->name }}
                    </span>
                @else
                    <span class="text-gray-400">غير محدد</span>
                @endif
            </dd>
        </div>
        <div class="py-3 flex justify-between text-sm">
            <dt class="font-medium text-gray-500 dark:text-gray-400">تاريخ البدء:</dt>
            <dd class="text-gray-900 dark:text-white">
                {{ $activeHistory?->start_date ? \Carbon\Carbon::parse($activeHistory->start_date)->format('Y-m-d') : '—' }}
            </dd>
        </div>

        {{-- Status --}}
        <div class="py-3 flex justify-between text-sm">
            <dt class="font-medium text-gray-500 dark:text-gray-400">الحالة:</dt>
            <dd>
                @if($isAssigned)
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 rounded text-xs font-bold border border-green-100">
                        <span class="material-icons text-xs">check_circle</span>
                        نشط حالياً
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-bold border border-gray-200">
                        <span class="material-icons text-xs">pending</span>
                        لم يتم التعيين بعد
                    </span>
                @endif
            </dd>
        </div>
    </dl>

    {{-- New Assignment Form (Always creates a new record, closing the old one if it exists) --}}
    @if (Auth::id() === 1)
        <div class="mt-6 pt-4 border-t dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-4 text-blue-600 flex items-center gap-2">
                <span class="material-icons">person_add</span>
                تسجيل تعيين/ترقية جديد
            </h3>
            
            {{-- Guide --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm">
                <p class="text-blue-800 flex items-start gap-2">
                    <span class="material-icons text-blue-500">info</span>
                    <span>اختر الوحدة التنظيمية أولاً، ثم ستظهر الوظائف المرتبطة بها تلقائياً.</span>
                </p>
            </div>

            <form method="POST" action="{{ route('admin_users.update_position', $user) }}" id="new-assignment-form">
                @csrf
                @method('PUT')

                {{-- Hidden input to ensure we don't accidentally correct --}}
                <input type="hidden" name="correct" value="0">
                <input type="hidden" name="org_unit_id" x-model="selectedUnitId">
                <input type="hidden" name="position_id" x-model="selectedPositionId">

                {{-- Organizational Unit ID Field with Search --}}
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        <span class="material-icons text-sm align-middle">domain</span>
                        الوحدة التنظيمية الجديدة
                    </label>
                    <div class="relative">
                        <input type="text" 
                            x-model="unitSearch" 
                            @focus="unitDropdownOpen = true"
                            @click.away="unitDropdownOpen = false"
                            placeholder="ابحث واختر الوحدة التنظيمية..."
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:text-white">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <span class="material-icons text-sm">search</span>
                        </span>
                        
                        {{-- Dropdown --}}
                        <div x-show="unitDropdownOpen" x-cloak
                            class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <template x-for="unit in filteredUnits" :key="unit.id">
                                <div @click="selectUnit(unit)" 
                                    class="px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-600 cursor-pointer border-b border-gray-100 dark:border-gray-600 last:border-0 flex items-center justify-between">
                                    <div>
                                        <span class="font-semibold text-gray-800 dark:text-white" x-text="unit.name"></span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mr-2" x-text="'(' + unit.type + ')'"></span>
                                    </div>
                                    <span class="material-icons text-gray-300" x-show="selectedUnitId == unit.id">check_circle</span>
                                </div>
                            </template>
                            <div x-show="filteredUnits.length === 0" class="px-4 py-3 text-gray-500 text-center">
                                لا توجد نتائج
                            </div>
                        </div>
                    </div>
                    
                    {{-- Selected Unit Display --}}
                    <div x-show="selectedUnitId" class="mt-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-800 rounded-lg text-sm font-semibold">
                            <span class="material-icons text-sm">domain</span>
                            <span x-text="selectedUnitName"></span>
                            <button type="button" @click="clearUnit()" class="text-blue-600 hover:text-blue-800">
                                <span class="material-icons text-sm">close</span>
                            </button>
                        </span>
                    </div>
                    @error('org_unit_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Position ID Field with Search --}}
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        <span class="material-icons text-sm align-middle">badge</span>
                        المسمى الوظيفي الجديد
                    </label>
                    <div class="relative">
                        <input type="text" 
                            x-model="positionSearch" 
                            @focus="positionDropdownOpen = true"
                            @click.away="positionDropdownOpen = false"
                            :disabled="!selectedUnitId || loadingPositions"
                            :placeholder="!selectedUnitId ? 'اختر الوحدة التنظيمية أولاً' : (loadingPositions ? 'جاري التحميل...' : 'ابحث واختر المسمى الوظيفي...')"
                            :class="{'bg-gray-100 cursor-not-allowed': !selectedUnitId}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:text-white">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <span class="material-icons text-sm" x-text="loadingPositions ? 'hourglass_empty' : 'search'"></span>
                        </span>
                        
                        {{-- Dropdown --}}
                        <div x-show="positionDropdownOpen && selectedUnitId && !loadingPositions" x-cloak
                            class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <template x-for="pos in filteredPositions" :key="pos.id">
                                <div @click="selectPosition(pos)" 
                                    class="px-4 py-3 hover:bg-purple-50 dark:hover:bg-gray-600 cursor-pointer border-b border-gray-100 dark:border-gray-600 last:border-0 flex items-center justify-between">
                                    <div>
                                        <span class="font-semibold text-gray-800 dark:text-white" x-text="pos.title"></span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mr-2" x-text="pos.job_code ? '(' + pos.job_code + ')' : ''"></span>
                                    </div>
                                    <span class="material-icons text-gray-300" x-show="selectedPositionId == pos.id">check_circle</span>
                                </div>
                            </template>
                            <div x-show="filteredPositions.length === 0 && availablePositions.length === 0" class="px-4 py-3 text-gray-500 text-center">
                                <span class="material-icons text-2xl text-gray-300 block mb-1">work_off</span>
                                لا توجد وظائف مرتبطة بهذه الوحدة
                            </div>
                            <div x-show="filteredPositions.length === 0 && availablePositions.length > 0" class="px-4 py-3 text-gray-500 text-center">
                                لا توجد نتائج للبحث
                            </div>
                        </div>
                    </div>
                    
                    {{-- Selected Position Display --}}
                    <div x-show="selectedPositionId" class="mt-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-purple-100 text-purple-800 rounded-lg text-sm font-semibold">
                            <span class="material-icons text-sm">badge</span>
                            <span x-text="selectedPositionName"></span>
                            <button type="button" @click="clearPosition()" class="text-purple-600 hover:text-purple-800">
                                <span class="material-icons text-sm">close</span>
                            </button>
                        </span>
                    </div>
                    @error('position_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Start Date Field --}}
                <div class="mb-4">
                    <label for="start_date_new" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        <span class="material-icons text-sm align-middle">calendar_today</span>
                        تاريخ البدء
                    </label>
                    <input type="date" id="start_date_new" name="start_date"
                        value="{{ old('start_date', now()->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:text-white">
                    @error('start_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    :disabled="!selectedUnitId || !selectedPositionId"
                    :class="{'opacity-50 cursor-not-allowed': !selectedUnitId || !selectedPositionId}"
                    class="w-full px-4 py-3 bg-blue-600 text-white font-bold rounded-lg shadow-md hover:bg-blue-700 transition duration-150 ease-in-out flex items-center justify-center gap-2">
                    <span class="material-icons">save</span>
                    تسجيل التعيين/الترقية
                </button>
            </form>
            
            {{-- Optional: Link to the correction form --}}
            @if ($isAssigned)
                <div class="mt-4 text-center">
                    <a href="{{ route('admin_users.position.edit', $user) }}" class="text-red-500 hover:text-red-700 text-sm font-medium underline flex items-center justify-center gap-1">
                        <span class="material-icons text-sm">edit</span>
                        لتصحيح بيانات السجل الحالي اضغط هنا
                    </a>
                </div>
            @endif
        </div>

        {{-- JAVASCRIPT FOR SEARCHABLE SELECTS --}}
        <script>
            function positionAssignment() {
                return {
                    // Units
                    units: @json($units->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'type' => $u->type])),
                    unitSearch: '',
                    unitDropdownOpen: false,
                    selectedUnitId: null,
                    selectedUnitName: '',
                    
                    // Positions
                    availablePositions: [],
                    positionSearch: '',
                    positionDropdownOpen: false,
                    selectedPositionId: null,
                    selectedPositionName: '',
                    loadingPositions: false,
                    
                    get filteredUnits() {
                        if (!this.unitSearch) return this.units;
                        const search = this.unitSearch.toLowerCase();
                        return this.units.filter(u => 
                            u.name.toLowerCase().includes(search) || 
                            u.type.toLowerCase().includes(search)
                        );
                    },
                    
                    get filteredPositions() {
                        if (!this.positionSearch) return this.availablePositions;
                        const search = this.positionSearch.toLowerCase();
                        return this.availablePositions.filter(p => 
                            p.title.toLowerCase().includes(search) || 
                            (p.job_code && p.job_code.toLowerCase().includes(search))
                        );
                    },
                    
                    selectUnit(unit) {
                        this.selectedUnitId = unit.id;
                        this.selectedUnitName = unit.name;
                        this.unitSearch = '';
                        this.unitDropdownOpen = false;
                        
                        // Clear position selection
                        this.clearPosition();
                        
                        // Load positions for this unit
                        this.loadPositionsForUnit(unit.id);
                    },
                    
                    clearUnit() {
                        this.selectedUnitId = null;
                        this.selectedUnitName = '';
                        this.unitSearch = '';
                        this.availablePositions = [];
                        this.clearPosition();
                    },
                    
                    selectPosition(pos) {
                        this.selectedPositionId = pos.id;
                        this.selectedPositionName = pos.title;
                        this.positionSearch = '';
                        this.positionDropdownOpen = false;
                    },
                    
                    clearPosition() {
                        this.selectedPositionId = null;
                        this.selectedPositionName = '';
                        this.positionSearch = '';
                    },
                    
                    async loadPositionsForUnit(unitId) {
                        this.loadingPositions = true;
                        this.availablePositions = [];
                        
                        try {
                            const response = await fetch(`{{ route('admin.api.positions_by_unit') }}?unit_id=${unitId}`);
                            const positions = await response.json();
                            this.availablePositions = positions;
                        } catch (error) {
                            console.error('Error fetching positions:', error);
                            this.availablePositions = [];
                        } finally {
                            this.loadingPositions = false;
                        }
                    }
                }
            }
        </script>
    @endif
</div>
