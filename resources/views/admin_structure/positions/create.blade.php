<x-app-layout title="إدارة الهيكل التنظيمي والوظائف">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <span class="material-icons text-4xl text-indigo-600">account_tree</span>
            إدارة هيكل الوظائف
        </h1>
    </x-slot>

    <div class="p-2 md:p-4 bg-gray-50 min-h-screen">

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm lg:col-span-2">
            <h3
                class="text-xl font-bold mb-6 text-gray-800 flex items-center space-x-3 rtl:space-x-reverse pb-3 border-b border-gray-200">
                <span class="material-icons text-2xl bg-blue-100 text-blue-600 p-2 rounded-lg">person_add</span>
                <span>تعيين/ترقية موظف</span>
            </h3>
            <form action="{{ route('admin.assign.store') }}" method="POST"
                class="space-y-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf

                <div class="space-y-2">
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 flex items-center">
                        <span class="material-icons text-lg text-blue-500 ml-2">person</span>
                        الموظف
                    </label>
                    <select id="user_id" name="user_id" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 border focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="new_position_id" class="block text-sm font-semibold text-gray-700 flex items-center">
                        <span class="material-icons text-lg text-purple-500 ml-2">work</span>
                        المسمى الوظيفي الجديد
                    </label>
                    <select id="new_position_id" name="new_position_id" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 border focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                        @foreach ($allPositions as $position)
                            <option value="{{ $position->id }}">{{ $position->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="new_unit_id" class="block text-sm font-semibold text-gray-700 flex items-center">
                        <span class="material-icons text-lg text-green-500 ml-2">business</span>
                        الوحدة التنظيمية
                    </label>
                    <select id="new_unit_id" name="new_unit_id" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 border focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        @foreach ($organizationalUnits as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->type }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 flex items-center">
                        <span class="material-icons text-lg text-orange-500 ml-2">event</span>
                        تاريخ بدء العمل الجديد
                    </label>
                    <input type="date" id="start_date" name="start_date"
                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 border focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                </div>

                <button type="submit"
                    class="md:col-span-2 py-3 px-6 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 transition-all duration-200 flex items-center justify-center space-x-2 rtl:space-x-reverse">
                    <span class="material-icons text-lg">send</span>
                    <span>حفظ التعيين/الترقية</span>
                </button>
            </form>
        </div>

    </div>
</x-app-layout>
