<x-app-layout title="الهيكل التنظيمي">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
                <span class="material-icons text-3xl text-slate-600">domain</span>
                <span class="border-r pr-3 mr-3 border-gray-300">إدارة الهيكل التنظيمي</span>
            </h1>
            <div class="flex gap-2">
                <a href="{{ route('org_unit.create') }}"
                    class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 rounded-lg shadow-sm transition flex items-center gap-2 text-sm font-bold">
                    <span class="material-icons text-base">add</span>
                    <span>وحدة جديدة</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6 bg-slate-50 min-h-screen">

        {{-- View Controls --}}
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">نظرة عامة</h2>
                <p class="text-gray-500 text-sm">عرض شجري للوحدات التنظيمية وعلاقاتها</p>
            </div>

            <div x-data="{ view: 'tree' }"
                class="bg-white p-1 rounded-lg border border-gray-200 shadow-sm flex items-center">
                <button @click="$dispatch('view-switch', 'tree'); view = 'tree'"
                    :class="view === 'tree' ? 'bg-slate-100 text-slate-800 font-bold' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-3 py-1.5 rounded-md transition text-sm flex items-center gap-2">
                    <span class="material-icons text-sm">account_tree</span> الهيكل
                </button>
                <div class="w-px h-4 bg-gray-200 mx-1"></div>
                <button @click="$dispatch('view-switch', 'list'); view = 'list'"
                    :class="view === 'list' ? 'bg-slate-100 text-slate-800 font-bold' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-3 py-1.5 rounded-md transition text-sm flex items-center gap-2">
                    <span class="material-icons text-sm">table_rows</span> القائمة
                </button>
            </div>
        </div>

        <div x-data="{ currentView: 'tree' }" @view-switch.window="currentView = $event.detail">

            {{-- Tree View Container --}}
            <div x-show="currentView === 'tree'" x-transition
                class="overflow-x-auto pb-12 pt-4 cursor-grab active:cursor-grabbing" id="treeContainer">

                @if($rootUnit)
                    <div class="min-w-max flex justify-center">
                        @include('org_units.partials.tree-node-modern', ['unit' => $rootUnit, 'level' => 0])
                    </div>
                @else
                    <div class="text-center py-24 bg-white rounded-xl border border-dashed border-gray-300">
                        <span class="material-icons text-6xl text-gray-200 mb-4">account_tree</span>
                        <p class="text-gray-500 text-lg">لا يوجد هيكل تنظيمي متاح.</p>
                        <a href="{{ route('org_unit.create') }}"
                            class="text-slate-600 hover:underline mt-2 inline-block">ابدأ بإضافة وحدة جذرية</a>
                    </div>
                @endif
            </div>

            {{-- List View Container --}}
            <div x-show="currentView === 'list'" x-cloak
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-sm text-right">
                    <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">الرمز</th>
                            <th class="px-6 py-4">اسم الوحدة</th>
                            <th class="px-6 py-4">النوع</th>
                            <th class="px-6 py-4">الأم</th>
                            <th class="px-6 py-4 text-center">الوظائف</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($allUnits as $unit)
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="px-6 py-4 font-mono text-gray-400">{{ $unit->unit_code }}</td>
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $unit->name }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">{{ $unit->type }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $unit->parent->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-full text-xs font-bold">
                                        {{ $unit->positions->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-left">
                                    <button class="text-gray-400 hover:text-slate-600">
                                        <span class="material-icons text-lg">more_horiz</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Drag Scroll Script --}}
    @push('scripts')
        <script>
            const slider = document.querySelector('#treeContainer');
            let isDown = false;
            let startX;
            let scrollLeft;

            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                slider.classList.add('active');
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });
            slider.addEventListener('mouseleave', () => {
                isDown = false;
                slider.classList.remove('active');
            });
            slider.addEventListener('mouseup', () => {
                isDown = false;
                slider.classList.remove('active');
            });
            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = (x - startX) * 2; //scroll-fast
                slider.scrollLeft = scrollLeft - walk;
            });
        </script>
    @endpush
</x-app-layout>