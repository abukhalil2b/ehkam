<x-app-layout title="إدارة الهيكل التنظيمي">

    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <span class="material-icons text-4xl text-indigo-600">account_tree</span>
            إدارة الهيكل التنظيمي
        </h1>
    </x-slot>

    <div class="p-2 md:p-4 bg-gray-50 min-h-screen">
        <h3 class="text-xl font-bold mb-4 text-gray-700">إضافة وحدة تنظيمية جديدة</h3>
        
        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('org_unit.store') }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            
            {{-- Name --}}
            <div>
                <label for="unit_name" class="block text-sm font-medium text-gray-700">اسم الوحدة</label>
                <input type="text" id="unit_name" name="name" value="{{ old('name') }}" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            {{-- Type (Updated List) --}}
            <div>
                <label for="unit_type" class="block text-sm font-medium text-gray-700">النوع</label>
                <select id="unit_type" name="type" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>اختر النوع...</option>
                    <option value="Minister" {{ old('type') == 'Minister' ? 'selected' : '' }}>وزير / مفتي (Minister)</option>
                    <option value="Undersecretary" {{ old('type') == 'Undersecretary' ? 'selected' : '' }}>وكيل / أمين عام (Undersecretary)</option>
                    <option value="Expert" {{ old('type') == 'Expert' ? 'selected' : '' }}>مستشار / خبير (Expert)</option>
                    <option value="Directorate" {{ old('type') == 'Directorate' ? 'selected' : '' }}>مديرية عامة (Directorate)</option>
                    <option value="Department" {{ old('type') == 'Department' ? 'selected' : '' }}>دائرة (Department)</option>
                    <option value="Section" {{ old('type') == 'Section' ? 'selected' : '' }}>قسم (Section)</option>
                </select>
            </div>

            {{-- Parent Unit --}}
            <div>
                <label for="parent_unit_id" class="block text-sm font-medium text-gray-700">تنتمي إلى (الوحدة الأم)</label>
                <select id="parent_unit_id" name="parent_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">(لا يوجد / وحدة عليا)</option>
                    @foreach ($OrgUnits as $unit)
                        <option value="{{ $unit->id }}" {{ old('parent_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->unit_code }} - {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition flex justify-center items-center">
                <span class="material-icons text-lg ml-2">add_circle</span>
                إضافة الوحدة
            </button>
        </form>
    </div>

</x-app-layout>