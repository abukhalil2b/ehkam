<x-app-layout title="تفاصيل الخطوة">

    <div class="py-6" dir="rtl">
        <div class="max-w-5xl mx-auto px-4">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-bold text-[#1b5e20]">
                    {{ $step->name }}
                </h1>

                <a href="{{ route('step.index', $step->project_id) }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg shadow">
                    ⟵ رجوع إلى الخطوات
                </a>
            </div>

            {{-- Info Card --}}
            <div class="bg-white shadow rounded-2xl border border-gray-100 p-6 space-y-4">

                <div class="grid grid-cols-2 gap-6 text-sm text-gray-700">
                    <div>
                        <p class="font-semibold text-[#1b5e20]">تاريخ البداية:</p>
                        <p>{{ $step->start_date }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-[#1b5e20]">تاريخ النهاية:</p>
                        <p>{{ $step->end_date }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-[#1b5e20]">النسبة المستهدفة:</p>
                        <p>{{ $step->target_percentage }}%</p>
                    </div>
                    <div>
                        <p class="font-semibold text-[#1b5e20]">المرحلة:</p>
                        <p>{{  $phases[$step->phase]['title'] }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-[#1b5e20]">الحالة:</p>
                        <span
                            class="px-3 py-1 text-xs font-semibold rounded-full
                            @if ($step->status == 'completed') bg-green-100 text-green-800
                            @elseif ($step->status == 'delayed') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            @switch($step->status)
                                @case('completed')
                                    منجز
                                @break

                                @case('delayed')
                                    متأخر
                                @break

                                @default
                                    قيد التنفيذ
                            @endswitch
                        </span>
                    </div>
                </div>



                <div>
                    <p class="font-semibold text-[#1b5e20] mb-1">الجهات المسندة:</p>
                    @if ($step->assigned_divisions)
                        <ul class="list-disc pr-4 space-y-1 text-gray-700">
                            @foreach (json_decode($step->assigned_divisions, true) as $division)
                                <li>{{ $division }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">—</p>
                    @endif
                </div>
            </div>

            {{-- Evidence Files --}}
            @if ($step->is_need_evidence_file)
                <div class="bg-white mt-6 rounded-2xl shadow border border-gray-100 p-6">
                    <div>
                        <p class="font-semibold text-[#1b5e20] mb-1">الوثائق الداعمة:</p>
                        <p class="bg-gray-50 p-3 rounded-lg text-gray-700 whitespace-pre-line">
                            {{ $step->supporting_documents ?: '—' }}
                        </p>
                    </div>
                    <h2 class="text-xl font-bold text-[#1b5e20] mb-4">الملفات المرفقة</h2>

                    {{-- Upload form --}}
                    <form action="{{ route('step.uploadEvidence', $step->id) }}" method="POST"
                        enctype="multipart/form-data" class="flex items-center gap-4 mb-6">
                        @csrf
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg"
                            class="block w-full border-gray-300 rounded-lg shadow-sm">
                        <button type="submit"
                            class="bg-[#1b5e20] text-white px-4 py-2 rounded-lg hover:bg-[#2e7d32] shadow">
                            رفع الملف
                        </button>
                    </form>

                    {{-- List of evidence files --}}
                    <div class="space-y-2">
                        @forelse ($step->stepEvidenceFiles as $file)
                            <div class="flex justify-between items-center bg-gray-50 border rounded-lg px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#1b5e20]"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-1 1v1H5.5A1.5 1.5 0 004 5.5v11A1.5 1.5 0 005.5 18h9a1.5 1.5 0 001.5-1.5V9l-4.5-4.5H9V3a1 1 0 00-1-1zM8 7h4v2H8V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                        class="text-[#1b5e20] hover:underline">
                                        {{ $file->file_name }}
                                    </a>
                                </div>
                                <span class="text-sm text-gray-500">{{ strtoupper($file->file_type) }}</span>
                            </div>
                        @empty
                            <p class="text-gray-500">لا توجد ملفات مرفقة بعد.</p>
                        @endforelse
                    </div>
                </div>
            @endif


        <div class="bg-white mt-6 rounded-2xl shadow border border-gray-100 p-6">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3">

        {{-- Edit Button --}}
        <a href="{{ route('step.edit', $step->id) }}"
            class="flex items-center justify-center gap-1 w-full sm:w-auto
                   bg-[#1b5e20] text-white font-medium rounded-lg
                   px-4 py-2 shadow hover:bg-[#2e7d32] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 4h10M4 20h16M4 4h4m-4 8h16M4 16h8" />
            </svg>
            تعديل
        </a>

        {{-- Delete Form --}}
        <form action="{{ route('step.destroy', $step->id) }}" method="POST"
            onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذه الخطوة؟');"
            class="w-full sm:w-auto">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="flex items-center justify-center gap-1 w-full sm:w-auto
                       text-red-700 border border-red-700 font-medium rounded-lg
                       px-4 py-2 hover:bg-red-700 hover:text-white transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0v4m0-4h4m0 4h-4m1 4v6m4-6v6" />
                </svg>
                حذف
            </button>
        </form>
    </div>
</div>


        </div>
    </div>

</x-app-layout>
