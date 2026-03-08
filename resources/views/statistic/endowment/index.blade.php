<x-app-layout>
    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">سجل المؤسسات الوقفية</h2>
                    <a href="{{ route('endowments.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded shadow hover:bg-emerald-700">تسجيل مؤسسة وقفية</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3">#</th>
                                <th scope="col" class="px-6 py-3">اسم المؤسسة</th>
                                <th scope="col" class="px-6 py-3">المحافظة</th>
                                <th scope="col" class="px-6 py-3">النوع</th>
                                <th scope="col" class="px-6 py-3">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($endowments as $endowment)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $endowment->id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $endowment->name }}</td>
                                <td class="px-6 py-4">{{ $endowment->governorate->name ?? 'غير محدد' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $endowment->type == 'عامة' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $endowment->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex gap-3">
                                    <a href="{{ route('endowments.statistics.index', $endowment->id) }}" class="text-emerald-600 hover:underline">عرض الإحصائيات المالية</a>
                                    <a href="#" class="text-blue-600 hover:underline">تعديل</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $endowments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>