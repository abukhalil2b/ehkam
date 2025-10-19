{{-- 1. Organizational Units Tab --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Hierarchy View (2/3 width on large screens) --}}
    <div class="lg:col-span-2 bg-gray-50 p-6 rounded-lg border">
        <h3 class="text-xl font-bold mb-4 text-blue-700 flex items-center space-x-2 rtl:space-x-reverse">
            <span class="material-icons">apartment</span>
            الهيكل التنظيمي الحالي
        </h3>
        <div class="border border-dashed border-blue-300 p-4 rounded-lg bg-white">
            @forelse ($topLevelUnits as $unit)
                {{-- استدعاء الجزئية التكرارية --}}
                @include('admin_structure.partials._unit-hierarchy-item', [
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
                <label for="parent_unit_id" class="block text-sm font-medium text-gray-700">تنتمي إلى (الوحدة الأم)</label>
                <select id="parent_unit_id" name="parent_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                    <option value="">(لا يوجد / وحدة عليا)</option>
                    @foreach ($organizationalUnits as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->type }})</option>
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