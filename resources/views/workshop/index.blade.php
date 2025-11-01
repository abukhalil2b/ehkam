<x-app-layout>
    <x-slot name="header">📋 قائمة محاضر الاجتماعات</x-slot>

    <div class="max-w-5xl mx-auto mt-6 bg-white shadow rounded-lg p-6">
        <a href="{{ route('workshop.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">
            + إضافة ورشة جديد
        </a>

        <table class="w-full border border-gray-200 text-right">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">#</th>
                    <th class="p-2">العنوان</th>
                    <th class="p-2">التاريخ</th>
                    <th class="p-2">كاتب الورشة</th>
                    <th class="p-2">عدد الحضور</th>
                    <th class="p-2">الخيارات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($workshops as $workshop)
                    <tr class="border-t">
                        <td class="p-2">{{ $workshop->id }}</td>
                        <td class="p-2">{!! $workshop->title !!}</td>
                        <td class="p-2">{{ $workshop->date }}</td>
                        <td class="p-2">{{ $workshop->writtenBy->name ?? '-' }}</td>
                        <td class="p-2">{{ $workshop->attendances->count() }}</td>
                        <td class="p-2">
                            <a href="{{ route('workshop.show', $workshop) }}" class="text-blue-600">عرض</a> |
                            <a href="{{ route('workshop.edit', $workshop) }}" class="text-blue-600">تعديل</a> |
                            <form action="{{ route('workshop.destroy', $workshop) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('هل أنت متأكد من الحذف؟')" class="text-red-600">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $workshops->links() }}</div>
    </div>
</x-app-layout>
