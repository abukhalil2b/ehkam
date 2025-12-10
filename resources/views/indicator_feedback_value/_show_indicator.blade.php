<div class="p-6 max-w-2xl mx-auto"> {{-- Increased max-width slightly for better card look --}}

        {{-- Card Container with Shadow and Rounded Corners --}}
        <div class="bg-white shadow-xl rounded-xl overflow-hidden p-8 border-t-4 border-indigo-500">

            {{-- Header Details Section --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 pb-4 border-b border-dashed">
                
                {{-- Indicator --}}
                <div class="bg-gray-50 p-3 rounded-lg">
                    <strong class="text-sm font-semibold text-gray-600 block mb-1">المؤشر:</strong>
                    <span class="text-lg font-bold text-indigo-700">{{ $feedback->indicator->title ?? '—' }}</span>
                </div>

                {{-- Sector --}}
                <div class="bg-gray-50 p-3 rounded-lg">
                    <strong class="text-sm font-semibold text-gray-600 block mb-1">القطاع:</strong>
                    <span class="text-lg font-bold text-teal-700">{{ $feedback->sector->name ?? '—' }}</span>
                </div>
            </div>

            {{-- Core Feedback Data Section --}}
            <div class="space-y-4 mb-6">
                
                {{-- Year and Achieved Value --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-3 border rounded-lg bg-blue-50">
                        <strong class="text-sm font-semibold text-gray-600 block mb-1">السنة:</strong>
                        <span class="text-xl font-mono text-blue-800">{{ $feedback->current_year }}</span>
                    </div>

                    <div class="p-3 border rounded-lg bg-green-50">
                        <strong class="text-sm font-semibold text-gray-600 block mb-1">القيمة المحققة:</strong>
                        <span class="text-2xl font-extrabold text-green-700">{{ $feedback->achieved }}</span>
                    </div>
                </div>

                {{-- Evidence Title --}}
                <div class="p-3 border rounded-lg bg-gray-100">
                    <strong class="text-sm font-semibold text-gray-600 block mb-1">عنوان الدليل:</strong>
                    <p class="text-base text-gray-800">{{ $feedback->evidence_title ?? 'لا يوجد عنوان' }}</p>
                </div>

                {{-- Notes --}}
                <div class="p-3 border rounded-lg bg-gray-100">
                    <strong class="text-sm font-semibold text-gray-600 block mb-1">ملاحظات:</strong>
                    <p class="text-base text-gray-800 whitespace-pre-line">{{ $feedback->note ?? '—' }}</p>
                </div>

                {{-- File Evidence --}}
                <div class="p-3 border rounded-lg bg-yellow-50 flex justify-between items-center">
                    <strong class="text-sm font-semibold text-gray-600 block">الملف المرفق:</strong>
                    
                    @if($feedback->evidence_url)
                        <a href="{{ asset('storage/'.$feedback->evidence_url) }}"
                           target="_blank" 
                           class="px-4 py-2 bg-yellow-600 text-white rounded-lg font-semibold hover:bg-yellow-700 transition flex items-center shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            عرض الملف
                        </a>
                    @else
                        <span class="text-red-600 font-medium">لا يوجد ملف دليل مرفق</span>
                    @endif
                </div>

            </div>

            {{-- Actions --}}
            <div class="mt-6 pt-4 border-t flex justify-end">

                
                {{-- Example back button --}}
                <a href="{{ route('indicator_feedback_value.index', $feedback->indicator) }}"
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition mr-3">
                    العودة للقائمة
                </a>
            </div>

        </div>
    </div>