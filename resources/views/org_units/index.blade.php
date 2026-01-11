<x-app-layout title="الهيكل التنظيمي">
    @push('styles')
        <style>
            /* Professional Org Chart CSS */
            .org-tree ul {
                padding-top: 20px;
                position: relative;
                transition: all 0.5s;
                display: flex;
                justify-content: center;
            }

            .org-tree li {
                float: left;
                text-align: center;
                list-style-type: none;
                position: relative;
                padding: 20px 5px 0 5px;
                transition: all 0.5s;
            }

            /* Connectors */
            .org-tree li::before,
            .org-tree li::after {
                content: '';
                position: absolute;
                top: 0;
                right: 50%;
                border-top: 2px solid #cbd5e1;
                width: 50%;
                height: 20px;
            }

            .org-tree li::after {
                right: auto;
                left: 50%;
                border-left: 2px solid #cbd5e1;
            }

            /* Remove left-top connector from first child and right-top from last child */
            .org-tree li:first-child::before,
            .org-tree li:last-child::after {
                border: 0 none;
            }

            /* Adding back the vertical line for the only child */
            .org-tree li:only-child::after {
                display: none;
            }

            .org-tree li:only-child::before {
                display: none;
            }

            /* Downward connector from parent */
            .org-tree li:only-child {
                padding-top: 0;
            }

            /* Remove space from the top of single children */
            .org-tree ul ul::before {
                content: '';
                position: absolute;
                top: 0;
                left: 50%;
                border-left: 2px solid #cbd5e1;
                width: 0;
                height: 20px;
            }

            /* The Card */
            .org-node {
                display: inline-block;
                background: white;
                padding: 15px;
                border-radius: 12px;
                text-decoration: none;
                transition: all 0.3s;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
                border: 1px solid #e2e8f0;
                position: relative;
                min-width: 280px;
                z-index: 10;
            }

            .org-node:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                transform: translateY(-2px);
                border-color: #10b981;
            }
        </style>
    @endpush

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

    <div class="p-6 bg-slate-50 min-h-screen overflow-x-auto" x-data="{ view: 'tree' }">

        {{-- View Switcher & Stats --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div class="flex gap-4">
                <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                    <div class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center">
                        <span class="material-icons">domain</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-bold">إجمالي الوحدات</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['total_units'] ?? 0 }}</p>
                    </div>
                </div>
                <!-- Add more stats if needed -->
            </div>

            <div class="bg-white p-1 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <button @click="view = 'tree'"
                    :class="view === 'tree' ? 'bg-emerald-50 text-emerald-700 font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                    <span class="material-icons text-sm">account_tree</span> الهيكل
                </button>
                <div class="w-px h-4 bg-gray-200 mx-1"></div>
                <button @click="view = 'list'"
                    :class="view === 'list' ? 'bg-emerald-50 text-emerald-700 font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                    <span class="material-icons text-sm">table_rows</span> القائمة
                </button>
            </div>
        </div>

        {{-- TREE VIEW --}}
        <div x-show="view === 'tree'" x-transition class="min-w-max pb-20">
            @if($rootUnit)
                <div class="org-tree flex justify-center">
                    <ul>
                        @include('org_units.partials.tree-recursive', ['unit' => $rootUnit])
                    </ul>
                </div>
            @else
                <div class="text-center py-24 bg-white rounded-xl border-2 border-dashed border-gray-300">
                    <span class="material-icons text-6xl text-gray-200 mb-4">account_tree</span>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">الهيكل التنظيمي فارغ</h3>
                    <p class="text-gray-500 mb-6">لم يتم إضافة أي وحدات تنظيمية بعد.</p>
                    <a href="{{ route('org_unit.create') }}" class="text-emerald-600 font-bold hover:underline">إضافة الوحدة
                        الجذرية الأولى</a>
                </div>
            @endif
        </div>

        {{-- LIST VIEW --}}
        <div x-show="view === 'list'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-sm text-right">
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
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="px-6 py-4 font-mono text-gray-500">{{ $unit->unit_code }}</td>
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $unit->name }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs border border-gray-200">{{ $unit->type }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $unit->parent->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-xs font-bold">
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
            </div>
        </div>

    </div>
</x-app-layout>