<x-sect-layout >
<div class="p-6 max-w-xl mx-auto bg-white rounded shadow">

    <h2 class="text-xl font-bold mb-4">تفاصيل التغذية الراجعة</h2>

    {{-- Indicator --}}
    <div class="mb-3">
        <strong>المؤشر:</strong>
        {{ $feedback->indicator->title ?? '—' }}
    </div>

    {{-- Sector --}}
    <div class="mb-3">
        <strong>القطاع:</strong>
        {{ $feedback->sector->name ?? '—' }}
    </div>

    <hr class="my-4">

    <div class="mb-3">
        <strong>السنة:</strong> {{ $feedback->current_year }}
    </div>

    <div class="mb-3">
        <strong>القيمة المحققة:</strong> {{ $feedback->achieved }}
    </div>

    <div class="mb-3">
        <strong>العنوان:</strong> {{ $feedback->evidence_title ?? '—' }}
    </div>

    <div class="mb-3">
        <strong>ملاحظات:</strong> {{ $feedback->note ?? '—' }}
    </div>

    <div class="mb-3">
        <strong>الملف:</strong>
        @if($feedback->evidence_url)
            <a href="{{ asset('storage/'.$feedback->evidence_url) }}"
               target="_blank" class="text-blue-700 underline">
                عرض الملف
            </a>
        @else
            لا يوجد
        @endif
    </div>

    <a href="{{ route('indicator_feedback_values.edit', $feedback) }}"
       class="px-4 py-2 bg-blue-600 text-white rounded mt-4 inline-block">
       تعديل
    </a>

</div>
</x-sect-layout>
