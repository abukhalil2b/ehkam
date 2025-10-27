<x-app-layout title="إدارة الهيكل التنظيمي">

    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <span class="material-icons text-4xl text-indigo-600">account_tree</span>
            إدارة الهيكل التنظيمي
        </h1>
    </x-slot>

    <div class="p-2 md:p-4 bg-gray-50 min-h-screen">
        <h3 class="text-xl font-bold mb-4 text-gray-700">إضافة وحدة تنظيمية جديدة</h3>
        <form action="{{ route('organizational_unit.store') }}" method="POST" class="space-y-4">
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

</x-app-layout>
