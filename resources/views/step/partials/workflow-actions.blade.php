@props(['step'])

@php
    $workflow = $step->currentWorkflow;
    $status = $workflow->status ?? 'pending';
    $stage = $workflow->stage ?? 'not_started';
@endphp

<div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 p-4 opacity-10">
        <span class="material-icons text-9xl text-blue-900">settings_suggest</span>
    </div>

    <h3 class="text-xl font-bold text-blue-900 mb-4 flex items-center gap-2">
        <span class="material-icons">work_history</span>
        حالة سير العمل
    </h3>

    <div class="flex items-center gap-4 text-sm text-blue-800 mb-6">
        <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-blue-100">
            <span class="font-bold">المرحلة الحالية:</span>
            <span class="text-blue-600 font-bold mx-1">
                {{ match ($stage) {
    'not_started' => 'لم تبدأ',
    'target_setting' => 'تحديد المستهدفات',
    'execution' => 'التنفيذ',
    'verification' => 'التحقق والمراجعة',
    'approval' => 'الاعتماد النهائي',
    'completed' => 'مكتمل',
    default => $stage
} }}
            </span>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-blue-100">
            <span class="font-bold">الحالة:</span>
            <span class="mx-1 px-2 py-0.5 rounded text-xs font-bold
                {{ match ($status) {
    'pending' => 'bg-yellow-100 text-yellow-700',
    'approved' => 'bg-green-100 text-green-700',
    'rejected' => 'bg-red-100 text-red-700',
    default => 'bg-gray-100 text-gray-700'
} }}">
                {{ match ($status) {
    'pending' => 'قيد الانتظار',
    'approved' => 'معتمد',
    'rejected' => 'مرفوض/معاد',
    default => $status
} }}
            </span>
        </div>
    </div>

    {{-- Workflow Actions Form --}}
    @if(in_array($stage, ['target_setting', 'execution', 'verification', 'approval']))
        <form action="{{ route('step.workflow.transition', $step->id) }}" method="POST" class="space-y-4 relative z-10">
            @csrf

            <div class="flex flex-wrap gap-3">
                @if($stage === 'target_setting' || $stage === 'execution')
                    <button type="submit" name="action" value="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition flex items-center gap-2">
                        <span class="material-icons">send</span>
                        إرسال للمرحلة التالية
                    </button>
                @endif

                @if($stage === 'verification' || $stage === 'approval')
                    <button type="submit" name="action" value="approve"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow transition flex items-center gap-2">
                        <span class="material-icons">check_circle</span>
                        اعتماد
                    </button>

                    <button type="button" onclick="document.getElementById('reject-comment-box').classList.toggle('hidden')"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow transition flex items-center gap-2">
                        <span class="material-icons">cancel</span>
                        رفض / إعادة
                    </button>
                @endif
            </div>

            {{-- Rejection Comment Box --}}
            <div id="reject-comment-box" class="hidden mt-4 bg-white p-4 rounded-lg border border-red-200">
                <label class="block text-sm font-bold text-gray-700 mb-2">سبب الرفض / ملاحظات:</label>
                <textarea name="comments" rows="3"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                <div class="mt-2 flex justify-end">
                    <button type="submit" name="action" value="reject"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                        تأكيد الرفض
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>