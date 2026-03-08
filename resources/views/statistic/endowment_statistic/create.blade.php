<x-app-layout>
    <div class="py-12" dir="rtl">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 border-b pb-4">
                    <h2 class="text-2xl font-bold text-gray-800">إضافة إحصائية سنوية</h2>
                    <p class="text-emerald-600 mt-1 font-medium">{{ $endowment->name }}</p>
                </div>

                <form method="POST" action="{{ route('endowments.statistics.store', $endowment->id) }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">السنة المالية</label>
                            <input type="number" name="year" value="{{ old('year', date('Y')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            @error('year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">إجمالي عدد العاملين</label>
                            <input type="number" name="employees_count" value="{{ old('employees_count', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            @error('employees_count') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                            <label class="block text-sm font-bold text-green-800">إجمالي الإيرادات (بالريال)</label>
                            <input type="number" step="0.001" name="revenues" value="{{ old('revenues', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            @error('revenues') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                            <label class="block text-sm font-bold text-red-800">إجمالي المصروفات (بالريال)</label>
                            <input type="number" step="0.001" name="expenses" value="{{ old('expenses', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                            @error('expenses') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-8 pt-4 border-t border-gray-100">
                        <a href="{{ route('endowments.statistics.index', $endowment->id) }}" class="text-gray-500 hover:text-gray-700 hover:underline">إلغاء وعودة</a>
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-2 rounded shadow hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            حفظ الإحصائية
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>