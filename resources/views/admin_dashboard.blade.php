<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الهيكل التنظيمي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f7f9;
        }

        .tab-button {
            transition: all 0.2s;
        }

        .tab-active {
            border-color: #4361ee;
            color: #4361ee;
            background-color: #eff3fe;
        }

        .card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>

<body x-data="{ activeTab: 'units' }" class="p-4 md:p-8">

    <div class="max-w-7xl mx-auto bg-white rounded-xl card overflow-hidden">

        <header class="p-6 border-b border-gray-100 bg-gray-50">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-2">
                <span class="material-icons text-4xl text-indigo-600">business</span>
                إدارة الهيكل التنظيمي
            </h1>
            <p class="text-gray-500 mt-1 text-sm md:text-base">لوحة تحكم موحدة لإدارة الوحدات التنظيمية، المسميات
                الوظيفية، وسجلات الموظفين.
            </p>
        </header>

        {{-- **NOTE:** This assumes Laravel's session() helper is available --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="p-4 mx-4 mt-4 md:mx-6 md:mt-6 rounded-lg bg-green-100 text-green-700 font-medium flex justify-between items-center shadow-md">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="material-icons text-lg">close</button>
            </div>
        @endif

        <nav
            class="p-4 md:p-6 border-b border-gray-100 flex overflow-x-auto whitespace-nowrap space-x-4 rtl:space-x-reverse">
            <button @click="activeTab = 'units'" :class="{ 'tab-active': activeTab === 'units' }"
                class="tab-button px-3 py-2 md:px-4 md:py-2 border-b-2 border-transparent font-semibold text-gray-600 flex items-center space-x-2 rtl:space-x-reverse">
                <span class="material-icons">account_tree</span>
                <span>الوحدات التنظيمية</span>
            </button>
            <button @click="activeTab = 'positions'" :class="{ 'tab-active': activeTab === 'positions' }"
                class="tab-button px-3 py-2 md:px-4 md:py-2 border-b-2 border-transparent font-semibold text-gray-600 flex items-center space-x-2 rtl:space-x-reverse">
                <span class="material-icons">work</span>
                <span>المسميات الوظيفية</span>
            </button>
            <button @click="activeTab = 'assignments'" :class="{ 'tab-active': activeTab === 'assignments' }"
                class="tab-button px-3 py-2 md:px-4 md:py-2 border-b-2 border-transparent font-semibold text-gray-600 flex items-center space-x-2 rtl:space-x-reverse">
                <span class="material-icons">group</span>
                <span>توزيع الموظفين والتاريخ الوظيفي</span>
            </button>
        </nav>

        <div class="p-4 md:p-6">

            {{-- 1. Organizational Units Tab --}}
            <div x-show="activeTab === 'units'" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Hierarchy View (2/3 width on large screens) --}}
                <div class="lg:col-span-2 bg-gray-50 p-6 rounded-lg border">
                    <h3 class="text-xl font-bold mb-4 text-blue-700 flex items-center space-x-2 rtl:space-x-reverse">
                        <span class="material-icons">apartment</span>
                        الهيكل التنظيمي
                    </h3>
                    <div class="border border-dashed border-blue-300 p-4 rounded-lg bg-white">
                        @forelse ($topLevelUnits as $unit)
                            @include('partials.unit_hierarchy_item', [
                                'unit' => $unit,
                                'users' => $users,
                                'depth' => 0,
                            ])
                        @empty
                            <p class="text-center text-gray-500">الرجاء إضافة أول مديرية عامة.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Unit Creation Form (1/3 width on large screens) --}}
                <div class="bg-white p-6 rounded-lg border shadow-lg">
                    <h3 class="text-xl font-bold mb-4 text-gray-700">إضافة وحدة تنظيمية جديدة</h3>
                    <form action="{{ route('admin.unit.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="unit_name" class="block text-sm font-medium text-gray-700">اسم الوحدة</label>
                            <input type="text" id="unit_name" name="name" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                        </div>
                        <div>
                            <label for="unit_type" class="block text-sm font-medium text-gray-700">النوع</label>
                            <select id="unit_type" name="type" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                <option value="Directorate">مديرية عامة (Directorate)</option>
                                <option value="Department">دائرة (Department)</option>
                                <option value="Section">قسم (Section)</option>
                            </select>
                        </div>
                        <div>
                            <label for="parent_unit_id" class="block text-sm font-medium text-gray-700">تنتمي إلى
                                (الوحدة الأم)</label>
                            <select id="parent_unit_id" name="parent_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                <option value="">(لا يوجد / وحدة عليا)</option>
                                @foreach ($organizationalUnits as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">
                            <span class="material-icons text-lg -mt-1 rtl:ml-1">add</span>
                            إضافة الوحدة
                        </button>
                    </form>
                </div>
            </div>

            {{-- 2. Positions Tab --}}
            <div x-show="activeTab === 'positions'" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Hierarchy View (2/3 width on large screens) --}}
                <div class="lg:col-span-2 bg-gray-50 p-6 rounded-lg border">
                    <h3 class="text-xl font-bold mb-4 text-purple-700 flex items-center space-x-2 rtl:space-x-reverse">
                        <span class="material-icons">format_list_numbered</span>
                        سلسلة المسميات الوظيفية
                    </h3>
                    <div class="border border-dashed border-purple-300 p-4 rounded-lg bg-white">
                        @forelse ($topLevelPositions as $position)
                            @include('partials.position_hierarchy_item', [
                                'position' => $position,
                                'users' => $users,
                                'depth' => 0,
                            ])
                        @empty
                            <p class="text-center text-gray-500">الرجاء إضافة أول مسمى وظيفي.</p>
                        @endforelse
                    </div>
                </div>

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
                            <label for="organizational_units" class="block text-sm font-medium text-gray-700">الوحدات
                                التنظيمية التي يمكن أن تحتوي على هذه الوظيفة</label>
                            <select id="organizational_units" name="organizational_unit_ids[]" multiple required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border h-32 @error('organizational_unit_ids') border-red-500 @enderror">

                                @foreach ($organizationalUnits as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ in_array($unit->id, old('organizational_unit_ids', [])) ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->type }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">اختر واحدة أو أكثر من الوحدات. (يمكنك استخدام Ctrl/Cmd
                                للاختيار المتعدد)</p>
                            @error('organizational_unit_ids')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reports_to_position_id" class="block text-sm font-medium text-gray-700">
                                يتبع مباشرةً إلى (الرئيس المباشر)</label>
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

            {{-- 3. Assignments Tab --}}
            <div x-show="activeTab === 'assignments'" class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="bg-white p-6 rounded-lg border shadow-lg lg:col-span-2">
                    <h3 class="text-xl font-bold mb-4 text-green-700 flex items-center space-x-2 rtl:space-x-reverse">
                        <span class="material-icons">history</span>
                        سجل التعيينات والترقيات
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الموظف</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الحالة الحالية</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الوحدة التنظيمية</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        تاريخ البدء/الانتهاء</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 min-w-[150px]">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 min-w-[150px]">
                                            @if ($user->currentPosition)
                                                <span
                                                    class="font-bold text-indigo-600">{{ $user->currentPosition->title }}</span>
                                            @else
                                                <span class="text-red-500">غير معين</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 min-w-[150px]">
                                            @if ($user->currentUnit)
                                                {{ $user->currentUnit->name }} ({{ $user->currentUnit->type }})
                                            @else
                                                <span class="text-red-500">غير محدد</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 min-w-[150px]">
                                            @if ($user->currentHistory)
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    حالي منذ:
                                                    {{ \Carbon\Carbon::parse($user->currentHistory->start_date)->format('Y-m-d') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td colspan="4" class="px-6 pt-2 pb-4 text-xs text-gray-600">
                                            <p class="font-bold mb-1">السجل الكامل:</p>
                                            <ul class="list-disc list-inside space-y-0.5">
                                                @foreach ($user->positionHistory->sortByDesc('start_date') as $history)
                                                    @php
                                                        $pos =
                                                            $allPositions->firstWhere('id', $history->position_id)
                                                                ->title ?? 'N/A';
                                                        $unitName =
                                                            $organizationalUnits->firstWhere(
                                                                'id',
                                                                $history->organizational_unit_id,
                                                            )->name ?? 'N/A';
                                                    @endphp
                                                    <li>
                                                        {{ $pos }} في {{ $unitName }} من
                                                        {{ \Carbon\Carbon::parse($history->start_date)->format('Y/m/d') }}
                                                        إلى
                                                        {{ $history->end_date ? \Carbon\Carbon::parse($history->end_date)->format('Y/m/d') : 'الآن' }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg border shadow-lg lg:col-span-2">
                    <h3 class="text-xl font-bold mb-4 text-gray-700">تعيين/ترقية موظف</h3>
                    <form action="{{ route('admin.assign.store') }}" method="POST"
                        class="space-y-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700">الموظف</label>
                            <select id="user_id" name="user_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="new_position_id" class="block text-sm font-medium text-gray-700">المسمى
                                الوظيفي
                                الجديد</label>
                            <select id="new_position_id" name="new_position_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                @foreach ($allPositions as $position)
                                    <option value="{{ $position->id }}">{{ $position->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="new_unit_id" class="block text-sm font-medium text-gray-700">الوحدة
                                التنظيمية</label>
                            <select id="new_unit_id" name="new_unit_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                @foreach ($organizationalUnits as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">تاريخ بدء العمل
                                الجديد</label>
                            <input type="date" id="start_date" name="start_date"
                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                        </div>
                        <button type="submit"
                            class="md:col-span-2 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition">
                            <span class="material-icons text-lg -mt-1 rtl:ml-1">send</span>
                            حفظ التعيين/الترقية
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>

</html>
