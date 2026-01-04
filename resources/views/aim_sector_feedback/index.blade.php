<x-sect-layout>
    <div class="p-6">

        {{-- Section Header: Indicator and Sector Info --}}
        <div class="bg-white shadow-lg rounded-lg p-6 mb-8 border-r-4 border-indigo-500">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                مؤشر: {{ $aim->title }}
            </h1>
            <h2 class="text-xl font-semibold text-indigo-600">
                القطاع: {{ $userSector->short_name }}
            </h2>
            <p class="text-gray-500 mt-2">قائمة القيم المحققة والمقدمة للمؤشر حسب السنوات.</p>
        </div>

        {{-- Action Buttons: Add Feedback for each year --}}
        <div class="flex flex-wrap gap-3 mb-8 justify-start">
            <h3 class="text-lg font-semibold text-gray-700 w-full mb-2">إضافة قيمة محققة لسنة:</h3>
            @foreach($years as $year)
                <a href="{{ route('aim_sector_feedback.create', ['aim'=>$aim->id,'current_year'=>$year]) }}"
                    class="
                        px-6 py-2 rounded-lg 
                        bg-green-600 text-white font-medium 
                        hover:bg-green-700 transition duration-300 
                        shadow-md hover:shadow-lg
                    ">
                    إضافة لعام {{ $year }}
                </a>
            @endforeach
        </div>

        {{-- Feedback Data Table --}}
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <table class="min-w-full text-right divide-y divide-gray-200">
                <thead class="">
                    <tr>
                        <th class="p-3 font-semibold text-sm tracking-wider w-1/12">السنة</th>
                        <th class="p-3 font-semibold text-sm tracking-wider w-2/12">القيمة المحققة</th>
                        <th class="p-3 font-semibold text-sm tracking-wider w-4/12">عنوان الدليل / ملاحظات</th>
                        <th class="p-3 font-semibold text-sm tracking-wider w-2/12">الدليل (الملف)</th>
                        <th class="p-3 font-semibold text-sm tracking-wider w-2/12">العمليات</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($feedbacks as $fb)
                        <tr class="hover:bg-indigo-50 transition duration-150">
                            {{-- السنة --}}
                            <td class="p-3 text-sm font-medium text-gray-900">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $fb->current_year }}
                                </span>
                            </td>
                            
                            {{-- القيمة المحققة --}}
                            <td class="p-3 text-lg font-bold text-green-700">
                                {{ $fb->achieved }}
                            </td>
                            
                            {{-- عنوان الدليل / ملاحظات (Added based on previous context) --}}
                            <td class="p-3 text-sm text-gray-600">
                                @if($fb->evidence_title)
                                    <p class="font-semibold">{{ $fb->evidence_title }}</p>
                                @endif
                                <p class="text-xs truncate max-w-xs">{{ $fb->note ? Str::limit($fb->note, 50) : '—' }}</p>
                            </td>

                            {{-- الملف --}}
                            <td class="p-3 text-sm">
                                @if ($fb->evidence_url)
                                    <a class="text-blue-600 hover:text-blue-800 underline flex items-center" 
                                       href="{{ asset('storage/' . $fb->evidence_url) }}"
                                       target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        عرض الدليل
                                    </a>
                                @else
                                    <span class="text-red-500 font-medium">غير مرفق</span>
                                @endif
                            </td>

                            {{-- عمليات --}}
                            <td class="p-3 whitespace-nowrap">
                                <a href="{{ route('aim_sector_feedback.show', $fb) }}" 
                                   class="text-green-600 hover:text-green-800 font-medium ml-3 transition">
                                    عرض
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500 text-lg bg-gray-50">
                                🙁 لا توجد أي قيم محققة مدخلة لهذا المؤشر بعد.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- You might need pagination links here if $feedbacks is a paginated collection --}}
        @if ($feedbacks instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="mt-4">
                {{ $feedbacks->links() }}
            </div>
        @endif

    </div>
</x-sect-layout>