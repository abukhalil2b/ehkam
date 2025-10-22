<x-app-layout>
    <x-slot name="header">{{ $meeting_minute->title }}</x-slot>

    <div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
        <p><strong>التاريخ:</strong> {{ $meeting_minute->date }}</p>
        <p><strong>كتب بواسطة:</strong> {{ $meeting_minute->writtenBy->name ?? '—' }}</p>

        <div class="mt-4">
            <h3 class="font-semibold">المحتوى:</h3>
            <div class="prose text-gray-800">{!! $meeting_minute->content !!}</div>
        </div>

        @if ($meeting_minute->file_upload_link)
            <div class="mt-4">
                <a href="{{ asset('storage/' . $meeting_minute->file_upload_link) }}" target="_blank" class="text-blue-600 underline">
                    عرض المرفق
                </a>
            </div>
        @endif

        <div class="mt-6">
            <h3 class="font-semibold">الحضور:</h3>
            <ul class="list-disc pr-6">
                @foreach ($meeting_minute->attendances as $att)
                    <li>{{ $att->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
