<x-app-layout title="إدارة الهيكل التنظيمي والوظائف">

    <div x-data="{ activeTab: 'units' }">

        <header class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
                <span class="material-icons text-4xl text-indigo-600">account_tree</span>
                إدارة الهيكل التنظيمي والوظائف
            </h1>
            <p class="text-gray-600 mt-2 text-sm md:text-base">لوحة تحكم موحدة لإدارة الوحدات التنظيمية، المسميات
                الوظيفية، وسجلات الموظفين.
            </p>
        </header>

        {{-- Session Success Message --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="p-4 mx-4 mt-4 md:mx-6 md:mt-6 rounded-lg bg-green-50 border border-green-200 text-green-700 font-medium flex justify-between items-center shadow-sm">
                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                    <span class="material-icons text-green-600">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false"
                    class="material-icons text-lg text-green-600 hover:text-green-800">close</button>
            </div>
        @endif

        {{-- Navigation Tabs --}}
        <nav class="p-4 md:p-6 border-b border-gray-200 bg-white flex overflow-x-auto whitespace-nowrap space-x-2 rtl:space-x-reverse">
            <button @click="activeTab = 'units'" :class="{ 'tab-active': activeTab === 'units' }"
                class="tab-button px-4 py-3 rounded-lg font-semibold text-gray-600 flex items-center space-x-2 rtl:space-x-reverse hover:bg-gray-50 transition-all">
                <span class="material-icons text-xl">corporate_fare</span>
                <span>الوحدات التنظيمية</span>
            </button>
            <button @click="activeTab = 'positions'" :class="{ 'tab-active': activeTab === 'positions' }"
                class="tab-button px-4 py-3 rounded-lg font-semibold text-gray-600 flex items-center space-x-2 rtl:space-x-reverse hover:bg-gray-50 transition-all">
                <span class="material-icons text-xl">badge</span>
                <span>المسميات الوظيفية</span>
            </button>
            <button @click="activeTab = 'assignments'" :class="{ 'tab-active': activeTab === 'assignments' }"
                class="tab-button px-4 py-3 rounded-lg font-semibold text-gray-600 flex items-center space-x-2 rtl:space-x-reverse hover:bg-gray-50 transition-all">
                <span class="material-icons text-xl">people_alt</span>
                <span>توزيع الموظفين والتاريخ الوظيفي</span>
            </button>
        </nav>

        {{-- Tab Content --}}
        <div class="p-2 md:p-4 bg-gray-50 min-h-screen">

            <div class="p-1">

                <div x-show="activeTab === 'units'">
                    @forelse ($topLevelUnits as $unit)
                        @include('admin_structure.partials._units-tab', [
                            'unit' => $unit,
                            'users' => $users,
                            'depth' => 0,
                        ])
                    @empty
                        <p class="text-center text-gray-500">الرجاء إضافة أول مديرية عامة.</p>
                    @endforelse
                </div>

                <div x-show="activeTab === 'positions'">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Hierarchy View (2/3 width on large screens) --}}
                        <div class="lg:col-span-2 bg-gray-50 p-6 rounded-lg border">
                            <h3
                                class="text-xl font-bold mb-4 text-purple-700 flex items-center space-x-2 rtl:space-x-reverse">
                                <span class="material-icons">format_list_numbered</span>
                                سلسلة المسميات الوظيفية
                            </h3>
                            <div class="border border-dashed border-purple-300 p-4 rounded-lg bg-white">
                                @forelse ($topLevelPositions as $position)
                                    {{-- استدعاء الجزئية التكرارية --}}
                                    @include('admin_structure.partials._position-hierarchy-item', [
                                        'position' => $position,
                                        'users' => $users,
                                        'depth' => 0,
                                    ])
                                @empty
                                    <p class="text-center text-gray-500">الرجاء إضافة أول مسمى وظيفي.</p>
                                @endforelse
                            </div>
                        </div>
                        {{-- Position Creation Form (1/3 width on large screens) --}}
                        <div class="bg-white p-6 rounded-lg border shadow-lg">
                            <h3 class="text-xl font-bold mb-4 text-gray-700">إضافة مسمى وظيفي جديد</h3>
                            <form action="{{ route('admin.position.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="position_title" class="block text-sm font-medium text-gray-700">عنوان
                                        الوظيفة</label>
                                    <input type="text" id="position_title" name="title" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border @error('title') border-red-500 @enderror"
                                        value="{{ old('title') }}">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="organizational_unit_id" class="block text-sm font-medium text-gray-700">
                                        الوحدة التنظيمية التي تنتمي إليها هذه الوظيفة
                                    </label>
                                    <select id="organizational_unit_id" name="organizational_unit_id" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border @error('organizational_unit_id') border-red-500 @enderror">
                                        <option value="">-- اختر وحدة تنظيمية --</option>
                                        @foreach ($organizationalUnits as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ old('organizational_unit_id') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }} ({{ $unit->type }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('organizational_unit_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="reports_to_position_id" class="block text-sm font-medium text-gray-700">
                                        يتبع مباشرةً إلى (الرئيس المباشر)
                                    </label>
                                    <select id="reports_to_position_id" name="reports_to_position_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                        <option value="">(لا يوجد / وظيفة عليا)</option>
                                        @foreach ($allPositions as $position)
                                            <option value="{{ $position->id }}"
                                                {{ old('reports_to_position_id') == $position->id ? 'selected' : '' }}>
                                                {{ $position->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit"
                                    class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 transition">
                                    <span class="material-icons text-lg -mt-1 rtl:ml-1">add</span>
                                    إضافة المسمى الوظيفي
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 3. Assignments Tab Content --}}
            <div x-show="activeTab === 'assignments'">
                @include('admin_structure.partials._assignments-tab', [
                    'users' => $users,
                    'allPositions' => $allPositions,
                    'organizationalUnits' => $organizationalUnits,
                ])
            </div>

        </div>
    </div>

    <style>
        .tab-active {
            background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
        }

        .tab-active .material-icons {
            color: white;
        }
    </style>
</x-app-layout>
