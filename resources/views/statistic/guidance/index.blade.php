<x-app-layout>
    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">إحصائيات الوعظ والإرشاد</h2>
                    <a href="{{ route('guidance-statistics.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">إضافة إحصائية</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3">السنة</th>
                                <th scope="col" class="px-6 py-3">المحافظة / الولاية</th>
                                <th scope="col" class="px-6 py-3">الأئمة والخطباء</th>
                                <th scope="col" class="px-6 py-3">المؤذنون</th>
                                <th scope="col" class="px-6 py-3">الوعاظ (ذ/إ)</th>
                                <th scope="col" class="px-6 py-3">المرشدون (ذ/إ)</th>
                                <th scope="col" class="px-6 py-3">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statistics as $stat)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $stat->year }}</td>
                                <td class="px-6 py-4">
                                    {{ $stat->governorate->name ?? 'غير محدد' }} 
                                    @if($stat->wilayat) - {{ $stat->wilayat->name }} @endif
                                </td>
                                <td class="px-6 py-4">{{ $stat->imams_and_preachers_count }}</td>
                                <td class="px-6 py-4">{{ $stat->muezzins_count }}</td>
                                <td class="px-6 py-4">{{ $stat->preachers_male }} / {{ $stat->preachers_female }}</td>
                                <td class="px-6 py-4">{{ $stat->religious_guides_male }} / {{ $stat->religious_guides_female }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="#" class="text-blue-600 hover:underline">تعديل</a>
                                </td>
                            </tr>
                            @endforeach
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