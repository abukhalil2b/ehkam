<x-app-layout>
    <x-slot name="header">📋 قائمة محاضر الاجتماعات</x-slot>

    <div class="max-w-5xl mx-auto mt-6 bg-white shadow rounded-lg p-6">
        <a href="{{ route('meeting_minute.create') }}" class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">
            + إضافة محضر جديد
        </a>

        <table class="w-full border border-gray-200 text-right">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">#</th>
                    <th class="p-2">العنوان</th>
                    <th class="p-2">التاريخ</th>
                    <th class="p-2">كاتب المحضر</th>
                    <th class="p-2">عدد الحضور</th>
                    <th class="p-2">الخيارات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($meeting_minutes as $minute)
                    <tr class="border-t">
                        <td class="p-2">{{ $minute->id }}</td>
                        <td class="p-2">{!! $minute->title !!}</td>
                        <td class="p-2">{{ $minute->date }}</td>
                        <td class="p-2">{{ $minute->writtenBy->name ?? '-' }}</td>
                        <td class="p-2">{{ $minute->attendances->count() }}</td>
                        <td class="p-2">
                            <a href="{{ route('meeting_minute.show', $minute) }}" class="text-blue-600">عرض</a> |
                            <a href="{{ route('meeting_minute.edit', $minute) }}" class="text-blue-600">تعديل</a> |
                            <form action="{{ route('meeting_minute.destroy', $minute) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('هل أنت متأكد من الحذف؟')" class="text-red-600">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $meeting_minutes->links() }}</div>
    </div>
</x-app-layout>
