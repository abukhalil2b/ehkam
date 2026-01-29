<x-app-layout title="إدارة المسميات الوظيفية">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
                <span class="material-icons text-3xl text-purple-600">badge</span>
                <span>إدارة المسميات الوظيفية</span>
            </h1>
            <a href="{{ route('positions.create') }}" 
                class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition flex items-center gap-2 font-bold transform hover:-translate-y-0.5">
                <span class="material-icons text-base">add</span>
                <span>وظيفة جديدة</span>
            </a>
        </div>
    </x-slot>

    <div class="p-6 bg-slate-50 min-h-screen" x-data="positionsFilter()">

        {{-- Guide Section --}}
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-5 mb-6">
            <div class="flex items-start gap-4">
                <div class="bg-purple-100 text-purple-600 p-3 rounded-full">
                    <span class="material-icons text-2xl">help_outline</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-purple-800 text-lg mb-2">ما هي المسميات الوظيفية؟</h3>
                    <p class="text-gray-700 text-sm leading-relaxed mb-3">
                        المسميات الوظيفية هي الأسماء الرسمية للوظائف في المؤسسة مثل: <strong>مدير دائرة</strong>، <strong>رئيس قسم</strong>، <strong>باحث قانوني</strong>، <strong>منسق</strong>.
                        كل مسمى وظيفي يُربط بوحدة تنظيمية واحدة أو أكثر.
                    </p>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-purple-100">
                            <span class="material-icons text-purple-500 text-lg">looks_one</span>
                            <span>أنشئ المسمى الوظيفي</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-purple-100">
                            <span class="material-icons text-purple-500 text-lg">looks_two</span>
                            <span>اربطه بالوحدة التنظيمية</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-purple-100">
                            <span class="material-icons text-purple-500 text-lg">looks_3</span>
                            <span>عيّن الموظفين من صفحة المستخدمين</span>
                        </div>
                    </div>
                </div>
                <button @click="$el.closest('.bg-gradient-to-r').remove()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
        </div>

        {{-- Stats Section --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-purple-100 text-purple-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">badge</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">إجمالي الوظائف</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $allPositions->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-green-100 text-green-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">person</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">وظائف مشغولة</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $allPositions->filter(fn($p) => !$p->is_vacant)->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-red-100 text-red-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">person_off</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">وظائف شاغرة</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $allPositions->filter(fn($p) => $p->is_vacant)->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <div class="bg-blue-100 text-blue-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <span class="material-icons">domain</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold">الوحدات التنظيمية</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $allUnits->count() }}</p>
                </div>
            </div>
        </div>

        {{-- Filter Controls --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-600 mb-2">
                        <span class="material-icons text-sm align-middle">search</span>
                        بحث
                    </label>
                    <input type="text" x-model="searchQuery" @input="filterTable()"
                        placeholder="ابحث بالمسمى الوظيفي أو الرمز..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                </div>

                {{-- Filter by Unit --}}
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">
                        <span class="material-icons text-sm align-middle">domain</span>
                        الوحدة التنظيمية
                    </label>
                    <select x-model="unitFilter" @change="filterTable()"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition bg-white">
                        <option value="">الكل</option>
                        @foreach($allUnits as $unit)
                            <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter by Status --}}
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">
                        <span class="material-icons text-sm align-middle">toggle_on</span>
                        الحالة
                    </label>
                    <select x-model="statusFilter" @change="filterTable()"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition bg-white">
                        <option value="">الكل</option>
                        <option value="occupied">مشغولة</option>
                        <option value="vacant">شاغرة</option>
                    </select>
                </div>
            </div>

            {{-- Active Filters & Reset --}}
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="material-icons text-lg">filter_list</span>
                    <span>النتائج: <strong x-text="visibleCount"></strong> من {{ $allPositions->count() }}</span>
                </div>
                <button @click="resetFilters()" x-show="searchQuery || unitFilter || statusFilter"
                    class="text-red-500 hover:text-red-700 text-sm font-bold flex items-center gap-1 transition">
                    <span class="material-icons text-sm">clear</span>
                    مسح الفلاتر
                </button>
            </div>
        </div>

        {{-- Positions Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm text-right">
                <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">الرمز</th>
                        <th class="px-6 py-4">المسمى الوظيفي</th>
                        <th class="px-6 py-4">الوحدة التنظيمية</th>
                        <th class="px-6 py-4 text-center">الحالة</th>
                        <th class="px-6 py-4 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($allPositions as $position)
                        @php
                            $unitNames = $position->orgUnits->pluck('name')->implode(', ') ?: 'غير مرتبط';
                        @endphp
                        <tr class="hover:bg-slate-50 transition group position-row"
                            data-title="{{ strtolower($position->title) }}"
                            data-code="{{ strtolower($position->job_code ?? '') }}"
                            data-unit="{{ $unitNames }}"
                            data-status="{{ $position->is_vacant ? 'vacant' : 'occupied' }}">
                            <td class="px-6 py-4 font-mono text-gray-500">{{ $position->job_code ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $position->title }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($position->orgUnits->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($position->orgUnits->take(2) as $unit)
                                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs border border-blue-100">
                                                {{ $unit->name }}
                                            </span>
                                        @endforeach
                                        @if($position->orgUnits->count() > 2)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">
                                                +{{ $position->orgUnits->count() - 2 }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">غير مرتبط بوحدة</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($position->is_vacant)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-50 text-red-600 rounded-full text-xs font-bold border border-red-100">
                                        <span class="material-icons text-xs">person_off</span>
                                        شاغرة
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-50 text-green-600 rounded-full text-xs font-bold border border-green-100">
                                        <span class="material-icons text-xs">person</span>
                                        مشغولة
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('positions.edit', $position->id) }}"
                                        class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded-lg hover:bg-blue-100 transition"
                                        title="تعديل">
                                        <span class="material-icons text-sm">edit</span>
                                    </a>
                                    <form action="{{ route('positions.destroy', $position->id) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا المسمى الوظيفي؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg hover:bg-red-100 transition"
                                            title="حذف">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <span class="material-icons text-5xl text-gray-300 mb-3 block">badge</span>
                                <h3 class="text-lg font-bold text-gray-600 mb-1">لا توجد مسميات وظيفية</h3>
                                <p class="text-gray-500 text-sm mb-4">ابدأ بإضافة أول مسمى وظيفي</p>
                                <a href="{{ route('positions.create') }}" class="text-purple-600 font-bold hover:underline">
                                    إضافة مسمى وظيفي جديد
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Empty State for Filter --}}
            <div x-show="visibleCount === 0 && {{ $allPositions->count() }} > 0" x-cloak class="text-center py-12">
                <span class="material-icons text-5xl text-gray-300 mb-3">search_off</span>
                <h3 class="text-lg font-bold text-gray-600 mb-1">لا توجد نتائج</h3>
                <p class="text-gray-500 text-sm">جرب تغيير معايير البحث أو الفلترة</p>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function positionsFilter() {
            return {
                searchQuery: '',
                unitFilter: '',
                statusFilter: '',
                visibleCount: {{ $allPositions->count() }},

                filterTable() {
                    const rows = document.querySelectorAll('.position-row');
                    let count = 0;

                    rows.forEach(row => {
                        const title = row.dataset.title || '';
                        const code = row.dataset.code || '';
                        const unit = row.dataset.unit || '';
                        const status = row.dataset.status || '';

                        const searchLower = this.searchQuery.toLowerCase();

                        const matchesSearch = !this.searchQuery ||
                            title.includes(searchLower) ||
                            code.includes(searchLower);

                        const matchesUnit = !this.unitFilter || unit.includes(this.unitFilter);
                        const matchesStatus = !this.statusFilter || status === this.statusFilter;

                        if (matchesSearch && matchesUnit && matchesStatus) {
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
                    this.unitFilter = '';
                    this.statusFilter = '';
                    this.filterTable();
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
