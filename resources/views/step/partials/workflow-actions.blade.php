@props(['step'])

@php
    $instance = $step->workflowInstance;
    $currentStage = $instance?->currentStage;
    $canAct = false;
    
    // Check if current user can act on this step
    if ($currentStage && auth()->check()) {
        $user = auth()->user();
        $canAct = match ($currentStage->assignment_type ?? 'team') {
            'team' => $user->workflowTeams()->where('workflow_teams.id', $currentStage->team_id)->exists(),
            'user' => $user->id === $currentStage->assigned_user_id,
            'role' => $user->roles()->where('roles.id', $currentStage->assigned_role_id)->exists(),
            default => false,
        };
    }
@endphp

{{-- Step is in Draft - Not yet submitted --}}
@if(!$instance || $instance->isDraft())
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
        <h3 class="text-xl font-bold text-amber-900 mb-2 flex items-center gap-2">
            <span class="material-icons">edit_note</span>
            مسودة - في انتظار الإرسال
        </h3>
        <p class="text-amber-800 mb-4">
            هذه الخطوة لم يتم إرسالها بعد لسير العمل. اضغط على "إرسال للمراجعة" لبدء عملية الموافقة.
        </p>
        
        <form action="{{ route('step.submit') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="step_id" value="{{ $step->id }}">
            <button type="submit" 
                class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-2 rounded-lg transition flex items-center gap-2">
                <span class="material-icons text-sm">send</span>
                إرسال للمراجعة
            </button>
        </form>
    </div>

{{-- Step is Completed --}}
@elseif($instance->status === 'completed')
    <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
        <h3 class="text-xl font-bold text-green-900 mb-2 flex items-center gap-2">
            <span class="material-icons">check_circle</span>
            مكتمل
        </h3>
        <p class="text-green-800">
            تمت الموافقة على هذه الخطوة واكتمل سير العمل بنجاح.
        </p>
    </div>

{{-- Step is Rejected --}}
@elseif($instance->status === 'rejected')
    <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
        <h3 class="text-xl font-bold text-red-900 mb-2 flex items-center gap-2">
            <span class="material-icons">cancel</span>
            مرفوض
        </h3>
        <p class="text-red-800">
            تم رفض هذه الخطوة ولا يمكن إجراء تعديلات عليها.
        </p>
        
        {{-- Show rejection reason --}}
        @if($step->transitions->where('action', 'reject')->first())
            @php $rejection = $step->transitions->where('action', 'reject')->first(); @endphp
            <div class="mt-3 bg-red-100 p-3 rounded-lg">
                <p class="text-sm text-red-900">
                    <strong>سبب الرفض:</strong> {{ $rejection->comments ?? 'لم يتم تحديد سبب' }}
                </p>
                <p class="text-xs text-red-700 mt-1">
                    بواسطة: {{ $rejection->actor->name ?? '—' }} - {{ $rejection->created_at->format('Y-m-d H:i') }}
                </p>
            </div>
        @endif
    </div>

{{-- Step is In Progress or Returned --}}
@else
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        
        {{-- Current Stage Info --}}
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <span class="material-icons text-blue-600">account_tree</span>
                سير العمل
            </h3>
            <span class="px-3 py-1 rounded-full text-sm font-bold
                @if($instance->status === 'returned') bg-yellow-100 text-yellow-800
                @else bg-blue-100 text-blue-800
                @endif">
                {{ $instance->status === 'returned' ? 'معاد للتعديل' : 'قيد المراجعة' }}
            </span>
        </div>

        {{-- Stage Progress --}}
        @if($instance->workflow)
            @php
                $stages = $instance->workflow->stages()->orderBy('order')->get();
                $currentIndex = $stages->search(fn($s) => $s->id === $currentStage?->id);
            @endphp
            
            <div class="flex items-center justify-between mb-6 overflow-x-auto pb-2">
                @foreach($stages as $index => $stage)
                    <div class="flex items-center">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold
                                @if($index < $currentIndex) bg-green-500 text-white
                                @elseif($index === $currentIndex) bg-blue-500 text-white animate-pulse
                                @else bg-gray-200 text-gray-500
                                @endif">
                                @if($index < $currentIndex)
                                    <span class="material-icons text-sm">check</span>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </div>
                            <span class="text-xs mt-1 text-center max-w-[80px] truncate
                                @if($index === $currentIndex) text-blue-700 font-bold @else text-gray-500 @endif">
                                {{ $stage->name }}
                            </span>
                        </div>
                        @if(!$loop->last)
                            <div class="w-8 h-1 mx-1 @if($index < $currentIndex) bg-green-500 @else bg-gray-200 @endif"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Current Stage Details --}}
        @if($currentStage)
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <p class="text-sm text-blue-900">
                    <strong>المرحلة الحالية:</strong> {{ $currentStage->name }}
                </p>
                @if($currentStage->team)
                    <p class="text-sm text-blue-700 mt-1">
                        <strong>الفريق المسؤول:</strong> {{ $currentStage->team->name }}
                    </p>
                @endif
                @if($instance->stage_due_at)
                    @php
                        $daysRemaining = (int) now()->diffInDays($instance->stage_due_at, false);
                        $isOverdue = $daysRemaining < 0;
                        $daysCount = abs($daysRemaining);
                    @endphp
                    <p class="text-sm mt-1 @if($isOverdue) text-red-600 @elseif($daysRemaining <= 1) text-yellow-600 @else text-blue-700 @endif">
                        <strong>الموعد النهائي:</strong> 
                        {{ $instance->stage_due_at->format('Y-m-d') }}
                        @if($isOverdue)
                            <span class="font-semibold">(متأخر {{ $daysCount }} {{ $daysCount == 1 ? 'يوم' : 'أيام' }})</span>
                        @elseif($daysRemaining == 0)
                            <span class="font-semibold">(اليوم)</span>
                        @elseif($daysRemaining == 1)
                            <span class="font-semibold">(يوم واحد متبقي)</span>
                        @else
                            <span>({{ $daysCount }} {{ $daysCount == 2 ? 'يومان' : 'أيام' }} متبقية)</span>
                        @endif
                    </p>
                @endif
            </div>
        @endif

        {{-- Return Feedback (if returned) --}}
        @if($instance->status === 'returned')
            @php $returnTransition = $step->transitions->where('action', 'return')->first(); @endphp
            @if($returnTransition)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <p class="text-sm font-bold text-yellow-900 mb-1">ملاحظات الإرجاع:</p>
                    <p class="text-sm text-yellow-800">{{ $returnTransition->comments ?? 'لا توجد ملاحظات' }}</p>
                    <p class="text-xs text-yellow-700 mt-2">
                        بواسطة: {{ $returnTransition->actor->name ?? '—' }} - {{ $returnTransition->created_at->format('Y-m-d H:i') }}
                    </p>
                </div>
                
                {{-- Allow resubmit after fixing --}}
                <form action="{{ route('step.submit') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                    <button type="submit" 
                        class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold px-6 py-3 rounded-lg transition flex items-center justify-center gap-2">
                        <span class="material-icons text-sm">refresh</span>
                        إعادة الإرسال بعد التعديل
                    </button>
                </form>
            @endif
        @endif

        {{-- Action Buttons (Only if user can act) --}}
        @if($canAct && $step->canBeActedUpon())
            <div class="border-t border-gray-200 pt-4 space-y-3">
                <p class="text-sm text-gray-600 mb-3">
                    <span class="material-icons text-green-600 text-sm align-middle">verified_user</span>
                    أنت مخول باتخاذ إجراء على هذه الخطوة
                </p>

                {{-- Approve Button --}}
                @if($currentStage->can_approve)
                    <form action="{{ route('step.approve', $step) }}" method="POST" class="inline-block w-full">
                        @csrf
                        <input type="hidden" name="comments" value="">
                        <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded-lg transition flex items-center justify-center gap-2">
                            <span class="material-icons text-sm">check_circle</span>
                            موافقة
                        </button>
                    </form>
                @endif

                {{-- Return Button --}}
                @if($currentStage->can_return)
                    <div x-data="{ showReturn: false }">
                        <button @click="showReturn = !showReturn" type="button"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-6 py-3 rounded-lg transition flex items-center justify-center gap-2">
                            <span class="material-icons text-sm">undo</span>
                            إرجاع للتعديل
                        </button>
                        
                        <div x-show="showReturn" x-cloak class="mt-3 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <form action="{{ route('step.return', $step) }}" method="POST">
                                @csrf
                                <label class="block text-sm font-bold text-yellow-900 mb-2">سبب الإرجاع (مطلوب):</label>
                                <textarea name="comments" rows="3" required
                                    class="w-full border border-yellow-300 rounded-lg p-2 text-sm"
                                    placeholder="اكتب ملاحظاتك هنا..."></textarea>
                                <button type="submit" 
                                    class="mt-2 bg-yellow-600 hover:bg-yellow-700 text-white font-bold px-4 py-2 rounded-lg text-sm">
                                    تأكيد الإرجاع
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Reject Button --}}
                <div x-data="{ showReject: false }">
                    <button @click="showReject = !showReject" type="button"
                        class="w-full bg-red-500 hover:bg-red-600 text-white font-bold px-6 py-3 rounded-lg transition flex items-center justify-center gap-2">
                        <span class="material-icons text-sm">cancel</span>
                        رفض
                    </button>
                    
                    <div x-show="showReject" x-cloak class="mt-3 bg-red-50 p-4 rounded-lg border border-red-200">
                        <form action="{{ route('step.reject', $step) }}" method="POST">
                            @csrf
                            <label class="block text-sm font-bold text-red-900 mb-2">سبب الرفض (مطلوب):</label>
                            <textarea name="comments" rows="3" required
                                class="w-full border border-red-300 rounded-lg p-2 text-sm"
                                placeholder="اكتب سبب الرفض هنا..."></textarea>
                            <button type="submit" 
                                class="mt-2 bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-lg text-sm">
                                تأكيد الرفض
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @elseif($step->canBeActedUpon())
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <span class="material-icons text-gray-400 text-3xl mb-2">hourglass_empty</span>
                <p class="text-sm text-gray-600">
                    في انتظار إجراء من الفريق المسؤول
                    @if($currentStage && $currentStage->team)
                        ({{ $currentStage->team->name }})
                    @endif
                </p>
            </div>
        @endif

        {{-- Workflow History --}}
        @if($step->transitions->count() > 0)
            <div class="mt-6 border-t border-gray-200 pt-4">
                <h4 class="text-sm font-bold text-gray-700 mb-3">سجل الإجراءات:</h4>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($step->transitions as $transition)
                        <div class="flex items-start gap-2 text-sm">
                            <span class="w-2 h-2 rounded-full mt-1.5
                                @if($transition->action === 'submit') bg-blue-500
                                @elseif($transition->action === 'approve') bg-green-500
                                @elseif($transition->action === 'return') bg-yellow-500
                                @elseif($transition->action === 'reject') bg-red-500
                                @else bg-gray-400
                                @endif"></span>
                            <div>
                                <span class="font-medium text-gray-900">{{ $transition->actor->name ?? '—' }}</span>
                                <span class="text-gray-600">
                                    @if($transition->action === 'submit') أرسل الخطوة
                                    @elseif($transition->action === 'approve') وافق
                                    @elseif($transition->action === 'return') أرجع
                                    @elseif($transition->action === 'reject') رفض
                                    @endif
                                </span>
                                @if($transition->toStage)
                                    <span class="text-gray-500">→ {{ $transition->toStage->name }}</span>
                                @endif
                                <span class="text-gray-400 text-xs block">{{ $transition->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endif
