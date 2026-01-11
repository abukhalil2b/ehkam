{{-- 2. Positions Tab --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Hierarchy View (2/3 width on large screens) --}}
    <div class="lg:col-span-2 bg-gray-50 p-6 rounded-lg border">
        <h3 class="text-xl font-bold mb-4 text-purple-700 flex items-center space-x-2 rtl:space-x-reverse">
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
                <label for="position_title" class="block text-sm font-medium text-gray-700">عنوان الوظيفة</label>
                <input type="text" id="position_title" name="title" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border @error('title') border-red-500 @enderror"
                    value="{{ old('title') }}">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="org_unit_id" class="block text-sm font-medium text-gray-700">
                    الوحدة التنظيمية التي تنتمي إليها هذه الوظيفة
                </label>
                <select id="org_unit_id" name="org_unit_id" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border @error('org_unit_id') border-red-500 @enderror">
                    <option value="">-- اختر وحدة تنظيمية --</option>
                    @foreach ($OrgUnits as $unit)
                        <option value="{{ $unit->id }}"
                            {{ old('org_unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }} ({{ $unit->type }})
                        </option>
                    @endforeach
                </select>
                @error('org_unit_id')
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