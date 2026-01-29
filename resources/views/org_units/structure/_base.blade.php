{{-- 
    Base template for structure management pages
    Variables: $units, $allowedParents, $stats, $type, $typeLabel, $typeLabelPlural
--}}
<x-app-layout title="إدارة {{ $typeLabelPlural }}">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
                <span class="material-icons text-3xl text-{{ $color ?? 'emerald' }}-600">{{ $icon ?? 'account_tree' }}</span>
                <span class="border-r pr-3 mr-3 border-gray-300">إدارة {{ $typeLabelPlural }}</span>
            </h1>
            <div class="flex gap-2">
                <a href="{{ route('org_unit.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition flex items-center gap-2 font-medium">
                    <span class="material-icons text-base">arrow_forward</span>
                    <span>الهيكل الكامل</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6 bg-slate-50 min-h-screen" x-data="structureManager()">

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-{{ $color ?? 'emerald' }}-100 text-{{ $color ?? 'emerald' }}-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">{{ $icon ?? 'account_tree' }}</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">{{ $typeLabelPlural }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $units->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-blue-100 text-blue-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">domain</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">إجمالي الوحدات</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_units'] ?? 0 }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-green-100 text-green-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">people</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">الموظفون</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $units->sum(fn($u) => $u->employeeAssignments->count()) }}
                    </p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-purple-100 text-purple-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">work</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">الوظائف المرتبطة</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $units->sum(fn($u) => $u->positions->count()) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Quick Add Form --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                        <span class="material-icons text-{{ $color ?? 'emerald' }}-600">add_circle</span>
                        إضافة {{ $typeLabel }} جديدة
                    </h3>
                    
                    <form action="{{ route('org_unit.store_quick') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                اسم {{ $typeLabel }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-{{ $color ?? 'emerald' }}-500 focus:ring-{{ $color ?? 'emerald' }}-500 p-3"
                                placeholder="أدخل اسم {{ $typeLabel }}...">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                {{ $parentLabel ?? 'تتبع لـ' }} <span class="text-red-500">*</span>
                            </label>
                            <select name="parent_id" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-{{ $color ?? 'emerald' }}-500 focus:ring-{{ $color ?? 'emerald' }}-500 p-3 bg-white">
                                <option value="">-- اختر --</option>
                                @foreach($allowedParents as $parent)
                                    <option value="{{ $parent->id }}">
                                        {{ $parent->name }} ({{ $parent->unit_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                ترتيب العرض
                            </label>
                            <input type="number" name="hierarchy_order" value="0" min="0" max="255"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-{{ $color ?? 'emerald' }}-500 focus:ring-{{ $color ?? 'emerald' }}-500 p-3">
                            <p class="text-xs text-gray-400 mt-1">رقم أقل = يظهر أولاً</p>
                        </div>

                        <button type="submit"
                            class="w-full bg-{{ $color ?? 'emerald' }}-600 hover:bg-{{ $color ?? 'emerald' }}-700 text-white py-3 rounded-lg font-bold transition flex justify-center items-center gap-2 shadow-md">
                            <span class="material-icons text-sm">add</span>
                            إضافة {{ $typeLabel }}
                        </button>
                    </form>

                    {{-- Hierarchy Rules Info --}}
                    <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <h4 class="font-bold text-amber-800 text-sm mb-2 flex items-center gap-2">
                            <span class="material-icons text-sm">info</span>
                            قواعد الربط
                        </h4>
                        <p class="text-xs text-amber-700">
                            {{ $hierarchyRule ?? '' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Units List --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="material-icons text-{{ $color ?? 'emerald' }}-600">list</span>
                            قائمة {{ $typeLabelPlural }}
                        </h3>
                        <div class="flex items-center gap-2">
                            <input type="text" x-model="searchQuery" @input="filterUnits()"
                                placeholder="بحث..."
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-{{ $color ?? 'emerald' }}-500 focus:border-{{ $color ?? 'emerald' }}-500">
                        </div>
                    </div>

                    @if($units->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($units as $unit)
                                <div class="p-4 hover:bg-gray-50 transition unit-item"
                                     data-name="{{ strtolower($unit->name) }}"
                                     data-code="{{ strtolower($unit->unit_code) }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="font-bold text-gray-800 text-lg">{{ $unit->name }}</h4>
                                                <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded font-mono">
                                                    {{ $unit->unit_code }}
                                                </span>
                                                @if($unit->hierarchy_order > 0)
                                                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded">
                                                        ترتيب: {{ $unit->hierarchy_order }}
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Parent Info --}}
                                            @if($unit->parent)
                                                <p class="text-sm text-gray-500 mb-2 flex items-center gap-1">
                                                    <span class="material-icons text-sm">subdirectory_arrow_left</span>
                                                    يتبع: <span class="font-medium">{{ $unit->parent->name }}</span>
                                                </p>
                                            @endif

                                            {{-- Children (Sub-units) --}}
                                            @if($unit->children->count() > 0)
                                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                                    <p class="text-xs font-bold text-gray-600 mb-2 flex items-center gap-1">
                                                        <span class="material-icons text-xs">account_tree</span>
                                                        الوحدات الفرعية ({{ $unit->children->count() }})
                                                    </p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($unit->children->take(5) as $child)
                                                            <a href="{{ route('org_unit.edit', $child->id) }}"
                                                               class="text-xs bg-white border border-gray-200 px-2 py-1 rounded hover:bg-{{ $color ?? 'emerald' }}-50 hover:border-{{ $color ?? 'emerald' }}-300 transition">
                                                                {{ $child->name }}
                                                            </a>
                                                        @endforeach
                                                        @if($unit->children->count() > 5)
                                                            <span class="text-xs text-gray-400">+{{ $unit->children->count() - 5 }} أخرى</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Employees --}}
                                            @if($unit->employeeAssignments->count() > 0)
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    @foreach($unit->employeeAssignments->take(3) as $assignment)
                                                        <span class="inline-flex items-center gap-1 text-xs bg-green-50 text-green-700 px-2 py-1 rounded border border-green-200">
                                                            <span class="material-icons text-xs">person</span>
                                                            {{ $assignment->user->name ?? 'موظف' }}
                                                            @if($assignment->position)
                                                                <span class="text-green-500">({{ $assignment->position->title }})</span>
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                    @if($unit->employeeAssignments->count() > 3)
                                                        <span class="text-xs text-gray-400">+{{ $unit->employeeAssignments->count() - 3 }} آخرين</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex items-center gap-2 mr-4">
                                            <a href="{{ route('org_unit.edit', $unit->id) }}"
                                               class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded-lg hover:bg-blue-100 transition"
                                               title="تعديل">
                                                <span class="material-icons text-sm">edit</span>
                                            </a>
                                            @if($unit->children->count() == 0 && $unit->employeeAssignments->count() == 0)
                                                <form action="{{ route('org_unit.destroy', $unit->id) }}" method="POST"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف {{ $unit->name }}؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg hover:bg-red-100 transition"
                                                            title="حذف">
                                                        <span class="material-icons text-sm">delete</span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-300 bg-gray-50 p-2 rounded-lg cursor-not-allowed"
                                                      title="لا يمكن الحذف - يوجد وحدات فرعية أو موظفون">
                                                    <span class="material-icons text-sm">delete</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <span class="material-icons text-6xl text-gray-300 mb-4 block">folder_off</span>
                            <h3 class="text-gray-600 font-bold text-lg mb-2">لا توجد {{ $typeLabelPlural }}</h3>
                            <p class="text-gray-500 text-sm">استخدم النموذج على اليسار لإضافة {{ $typeLabel }} جديدة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function structureManager() {
            return {
                searchQuery: '',
                filterUnits() {
                    const items = document.querySelectorAll('.unit-item');
                    const query = this.searchQuery.toLowerCase();
                    
                    items.forEach(item => {
                        const name = item.dataset.name || '';
                        const code = item.dataset.code || '';
                        const matches = !query || name.includes(query) || code.includes(query);
                        item.style.display = matches ? '' : 'none';
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
