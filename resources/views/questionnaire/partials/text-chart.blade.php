@if($statistics['total'] > 0)
<div class="space-y-6">
    <!-- Word Count Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <h4 class="font-semibold text-gray-900 mb-3">إحصائيات النص</h4>
            <div class="space-y-3">
               
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">إجمالي الإجابات النصية:</span>
                    <span class="font-semibold">{{ $statistics['total'] }}</span>
                </div>
            </div>
        </div>

        <!-- Sample Answers -->
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <h4 class="font-semibold text-gray-900 mb-3">نماذج من الإجابات</h4>
            <div class="space-y-2 max-h-40 overflow-y-auto">
                @foreach($statistics['sample_answers'] as $answer)
                <div class="text-sm text-gray-700 p-2 bg-gray-50 rounded">
                    "{{ Str::limit($answer, 100) }}"
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@else
<div class="text-center py-8 text-gray-500">
    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    <p>لا توجد إجابات حتى الآن</p>
</div>
@endif