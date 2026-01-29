<x-app-layout title="الهيكل التنظيمي">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
                <span class="material-icons text-3xl text-emerald-600">account_tree</span>
                <span class="border-r pr-3 mr-3 border-gray-300">إدارة الهيكل التنظيمي</span>
            </h1>
            <div class="flex gap-2">
                <a href="{{ route('org_unit.create') }}"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2 font-bold transform hover:-translate-y-0.5">
                    <span class="material-icons text-base">add</span>
                    <span>وحدة جديدة</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6 bg-slate-50 min-h-screen" x-data="orgUnitFilter()">

        {{-- Guide Section --}}
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl p-5 mb-6">
            <div class="flex items-start gap-4">
                <div class="bg-emerald-100 text-emerald-600 p-3 rounded-full">
                    <span class="material-icons text-2xl">help_outline</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-emerald-800 text-lg mb-2">ما هو الهيكل التنظيمي؟</h3>
                    <p class="text-gray-700 text-sm leading-relaxed mb-3">
                        الهيكل التنظيمي يمثل التقسيم الإداري للمؤسسة. يتكون من <strong>وحدات تنظيمية</strong> مثل: 
                        الوزارة، الوكالة، المديرية، الدائرة، القسم. كل وحدة يمكن أن تحتوي على <strong>وظائف</strong> مرتبطة بها.
                    </p>
                    <div class="flex flex-wrap gap-3 text-sm">
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-emerald-100">
                            <span class="material-icons text-emerald-500 text-lg">looks_one</span>
                            <span>أنشئ الوحدات التنظيمية</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-emerald-100">
                            <span class="material-icons text-emerald-500 text-lg">looks_two</span>
                            <span>حدد التبعية (يتبع لـ)</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-emerald-100">
                            <span class="material-icons text-emerald-500 text-lg">looks_3</span>
                            <span>اربط الوظائف من صفحة التعديل</span>
                        </div>
                    </div>
                    <div class="mt-3 p-3 bg-white/50 rounded-lg border border-emerald-100">
                        <p class="text-xs text-gray-600">
                            <span class="material-icons text-sm align-middle text-amber-500">lightbulb</span>
                            <strong>مثال:</strong> الدائرة القانونية (Department) ← يتبع: الوكالة ← تحتوي على أقسام مثل: قسم البحوث القانونية، قسم الشكاوى
                        </p>
                    </div>
                </div>
                <button @click="$el.closest('.bg-gradient-to-r').remove()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
        </div>

        {{-- Stats Section --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
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
                <div class="bg-purple-100 text-purple-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">business</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">المديريات</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['directorates'] ?? 0 }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-amber-100 text-amber-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">account_balance</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">الدوائر</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['departments'] ?? 0 }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-emerald-100 text-emerald-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">folder</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">الأقسام</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['sections'] ?? 0 }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-red-100 text-red-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">psychology</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">الخبراء</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['experts'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- Filter Controls --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search by Name/Code --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-600 mb-2">
                        <span class="material-icons text-sm align-middle">search</span>
                        بحث
                    </label>
                    <input type="text" x-model="searchQuery" @input="filterTable()"
                        placeholder="ابحث بالاسم أو الرمز..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>

                {{-- Filter by Type --}}
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">
                        <span class="material-icons text-sm align-middle">category</span>
                        النوع
                    </label>
                    <select x-model="typeFilter" @change="filterTable()"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white">
                        <option value="">الكل</option>
                        @php
                            $types = $allUnits->pluck('type')->unique()->filter();
                        @endphp
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter by Parent --}}
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">
                        <span class="material-icons text-sm align-middle">account_tree</span>
                        يتبع
                    </label>
                    <select x-model="parentFilter" @change="filterTable()"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white">
                        <option value="">الكل</option>
                        @foreach($allUnits as $unit)
                            <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Active Filters & Reset --}}
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="material-icons text-lg">filter_list</span>
                    <span>النتائج: <strong x-text="visibleCount"></strong> من {{ $allUnits->count() }}</span>
                </div>
                <button @click="resetFilters()" x-show="searchQuery || typeFilter || parentFilter"
                    class="text-red-500 hover:text-red-700 text-sm font-bold flex items-center gap-1 transition">
                    <span class="material-icons text-sm">clear</span>
                    مسح الفلاتر
                </button>
            </div>
        </div>

        {{-- LIST VIEW --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm text-right" id="orgUnitsTable">
                <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">الرمز</th>
                        <th class="px-6 py-4">اسم الوحدة</th>
                        <th class="px-6 py-4">النوع</th>
                        <th class="px-6 py-4">يتبع</th>
                        <th class="px-6 py-4 text-center">الوظائف</th>
                        <th class="px-6 py-4 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($allUnits as $unit)
                        <tr class="hover:bg-slate-50 transition group table-row"
                            data-code="{{ strtolower($unit->unit_code) }}"
                            data-name="{{ strtolower($unit->name) }}"
                            data-type="{{ $unit->type }}"
                            data-parent="{{ $unit->parent->name ?? '' }}">
                            <td class="px-6 py-4 font-mono text-gray-500">{{ $unit->unit_code }}</td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $unit->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs border border-gray-200">{{ $unit->type }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $unit->parent->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-xs font-bold">
                                    {{ $unit->positions->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex justify-center gap-2">
                                <a href="{{ route('org_unit.edit', $unit->id) }}"
                                    class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded-lg hover:bg-blue-100 transition"
                                    title="تعديل">
                                    <span class="material-icons text-sm">edit</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Empty State --}}
            <div x-show="visibleCount === 0" class="text-center py-12">
                <span class="material-icons text-5xl text-gray-300 mb-3">search_off</span>
                <h3 class="text-lg font-bold text-gray-600 mb-1">لا توجد نتائج</h3>
                <p class="text-gray-500 text-sm">جرب تغيير معايير البحث أو الفلترة</p>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function orgUnitFilter() {
            return {
                searchQuery: '',
                typeFilter: '',
                parentFilter: '',
                visibleCount: {{ $allUnits->count() }},

                filterTable() {
                    const rows = document.querySelectorAll('.table-row');
                    let count = 0;

                    rows.forEach(row => {
                        const code = row.dataset.code || '';
                        const name = row.dataset.name || '';
                        const type = row.dataset.type || '';
                        const parent = row.dataset.parent || '';

                        const searchLower = this.searchQuery.toLowerCase();

                        const matchesSearch = !this.searchQuery ||
                            code.includes(searchLower) ||
                            name.includes(searchLower);

                        const matchesType = !this.typeFilter || type === this.typeFilter;
                        const matchesParent = !this.parentFilter || parent === this.parentFilter;

                        if (matchesSearch && matchesType && matchesParent) {
                            row.style.display = '';
                            count++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    this.visibleCount = count;
                },

                resetFilters() {
                    this.searchQuery = '';
                    this.typeFilter = '';
                    this.parentFilter = '';
                    this.filterTable();
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
