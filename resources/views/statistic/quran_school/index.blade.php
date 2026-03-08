<x-app-layout> <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">إحصائيات مدارس القرآن الكريم</h2>
                    <a href="{{ route('quran-schools.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">إضافة إحصائية جديدة</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3">السنة</th>
                                <th scope="col" class="px-6 py-3">المحافظة / الولاية</th>
                                <th scope="col" class="px-6 py-3">عدد المدارس</th>
                                <th scope="col" class="px-6 py-3">الطلبة (ذكور/إناث)</th>
                                <th scope="col" class="px-6 py-3">المعلمين (ذكور/إناث)</th>
                                <th scope="col" class="px-6 py-3">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quranSchools as $school)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $school->year }}</td>
                                <td class="px-6 py-4">
                                    {{ $school->governorate->name ?? 'غير محدد' }} 
                                    @if($school->wilayat) / {{ $school->wilayat->name }} @endif
                                </td>
                                <td class="px-6 py-4">{{ $school->schools_count }}</td>
                                <td class="px-6 py-4">{{ $school->students_male }} / {{ $school->students_female }}</td>
                                <td class="px-6 py-4">{{ $school->teachers_male }} / {{ $school->teachers_female }}</td>
                                <td class="px-6 py-4">
                                    <a href="#" class="text-blue-600 hover:underline">تعديل</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $quranSchools->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>