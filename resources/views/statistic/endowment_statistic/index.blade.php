<x-app-layout>
    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">الإحصائيات السنوية</h2>
                        <p class="text-gray-500 mt-1">مؤسسة: <span class="font-semibold text-emerald-700">{{ $endowment->name }}</span></p>
                    </div>
                    <div class="flex gap-3 mt-4 md:mt-0">
                        <a href="{{ route('endowments.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded shadow hover:bg-gray-200">عودة للقائمة</a>
                        <a href="{{ route('endowments.statistics.create', $endowment->id) }}" class="bg-emerald-600 text-white px-4 py-2 rounded shadow hover:bg-emerald-700">إضافة إحصائية سنة جديدة</a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3">السنة</th>
                                <th scope="col" class="px-6 py-3">عدد العاملين</th>
                                <th scope="col" class="px-6 py-3">الإيرادات (ر.ع)</th>
                                <th scope="col" class="px-6 py-3">المصروفات (ر.ع)</th>
                                <th scope="col" class="px-6 py-3">صافي الدخل (ر.ع)</th>
                                <th scope="col" class="px-6 py-3">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistics as $stat)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $stat->year }}</td>
                                <td class="px-6 py-4">{{ $stat->employees_count }}</td>
                                <td class="px-6 py-4 text-green-600 font-semibold">{{ number_format($stat->revenues, 3) }}</td>
                                <td class="px-6 py-4 text-red-600 font-semibold">{{ number_format($stat->expenses, 3) }}</td>
                                <td class="px-6 py-4 font-bold {{ ($stat->revenues - $stat->expenses) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($stat->revenues - $stat->expenses, 3) }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="#" class="text-blue-600 hover:underline">تعديل</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">لا توجد إحصائيات مسجلة لهذه المؤسسة حتى الآن.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $statistics->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>