<x-sect-layout>
    <div class="p-6">
        <h1 class="text-xl font-bold mb-4">قائمة التغذية الراجعة للمؤشر</h1>

        <a href="{{ route('indicator_feedback_values.create', $indicator) }}"
            class="px-4 py-2 bg-green-600 text-white rounded">
            إضافة تغذية راجعة جديدة
        </a>

        <table class="w-full mt-4 text-right">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">السنة</th>
                    <th class="p-2">القيمة المحققة</th>
                    <th class="p-2">الملف</th>
                    <th class="p-2">عمليات</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($feedbacks as $fb)
                <tr class="border-b">
                    <td class="p-2">{{ $fb->current_year }}</td>
                    <td class="p-2">{{ $fb->achieved }}</td>

                    <td class="p-2">
                        @if($fb->evidence_url)
                            <a class="text-blue-700 underline"
                               href="{{ asset('storage/'.$fb->evidence_url) }}" target="_blank">عرض</a>
                        @else
                            —
                        @endif
                    </td>

                    <td class="p-2">
                        <a href="{{ route('indicator_feedback_values.show', $fb) }}"
                           class="text-green-600">عرض</a>

                        <a href="{{ route('indicator_feedback_values.edit', $fb) }}"
                           class="text-blue-600 mr-3">تعديل</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-3 text-center">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-sect-layout>
