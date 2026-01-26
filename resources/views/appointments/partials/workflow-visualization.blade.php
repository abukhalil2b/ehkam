@if($appointmentRequest->workflowInstance && $appointmentRequest->workflowInstance->workflow)
    @php
        $workflow = $appointmentRequest->workflowInstance->workflow;
        // Load stages with team and team users for superadmin view
        $stages = $workflow->stages()->with(['team.users'])->orderBy('order')->get();
        $currentStageId = $appointmentRequest->workflowInstance->current_stage_id;
        $transitions = $appointmentRequest->transitions()->with('actor', 'toStage')->orderBy('created_at')->get();
        $isSuperAdmin = auth()->id() === 1;
    @endphp

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-project-diagram text-blue-600"></i>
            {{ __('مسار سير العمل') }}
        </h3>

        <!-- Workflow Stages Timeline -->
        <div class="relative">
            <!-- Progress Line -->
            <div class="absolute top-8 right-0 left-0 h-1 bg-gray-200 rounded-full" style="margin: 0 2rem;">
                @php
                    $currentStageIndex = $stages->search(function($stage) use ($currentStageId) {
                        return $stage->id === $currentStageId;
                    });
                    $progressPercent = $currentStageIndex !== false && $stages->count() > 0 
                        ? (($currentStageIndex + 1) / $stages->count()) * 100 
                        : 0;
                @endphp
                <div class="h-full bg-blue-500 rounded-full transition-all duration-500" 
                     style="width: {{ $progressPercent }}%"></div>
            </div>

            <!-- Stages -->
            <div class="flex justify-between items-start relative z-10">
                @foreach($stages as $index => $stage)
                    @php
                        $isCurrent = $stage->id === $currentStageId;
                        $isCompleted = $transitions->where('to_stage_id', $stage->id)->isNotEmpty();
                        $isPending = !$isCurrent && !$isCompleted && $index < ($currentStageIndex ?? -1);
                        
                        // Calculate time spent in this stage
                        $stageTransition = $transitions->where('to_stage_id', $stage->id)->first();
                        $timeSpent = null;
                        if ($stageTransition) {
                            $nextTransition = $transitions->where('from_stage_id', $stage->id)->first();
                            $endTime = $nextTransition ? $nextTransition->created_at : now();
                            $timeSpent = $stageTransition->created_at->diffInDays($endTime);
                        }
                    @endphp

                    <div class="flex flex-col items-center" style="flex: 1;">
                        <!-- Stage Icon -->
                        <div class="relative">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center text-white font-bold text-sm
                                @if($isCompleted) bg-green-500
                                @elseif($isCurrent) bg-blue-500 animate-pulse
                                @else bg-gray-300
                                @endif
                                shadow-lg">
                                @if($isCompleted)
                                    <i class="fas fa-check text-xl"></i>
                                @elseif($isCurrent)
                                    <i class="fas fa-clock text-xl"></i>
                                @else
                                    <i class="fas fa-circle text-xl"></i>
                                @endif
                            </div>
                            
                            @if($isCurrent)
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation text-xs text-yellow-900"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Stage Info -->
                        <div class="mt-3 text-center max-w-[120px]">
                            <p class="text-sm font-semibold text-gray-900 {{ $isCurrent ? 'text-blue-600' : '' }}">
                                {{ $stage->name }}
                            </p>
                            
                            @if($stage->allowed_days)
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="far fa-clock"></i> 
                                    {{ $stage->allowed_days }} {{ __('يوم') }}
                                </p>
                            @endif

                            @if($timeSpent !== null)
                                <p class="text-xs {{ $timeSpent > $stage->allowed_days ? 'text-red-600' : 'text-green-600' }} mt-1">
                                    <i class="fas fa-hourglass-half"></i>
                                    {{ $timeSpent }} {{ __('يوم') }}
                                </p>
                            @endif

                            @if($isCurrent && $appointmentRequest->workflowInstance->stage_due_at)
                                @php
                                    $daysRemaining = (int) now()->diffInDays($appointmentRequest->workflowInstance->stage_due_at, false);
                                    $isOverdue = $daysRemaining < 0;
                                    $daysCount = abs($daysRemaining);
                                @endphp
                                <p class="text-xs {{ $isOverdue ? 'text-red-600' : ($daysRemaining <= 1 ? 'text-yellow-600' : 'text-gray-600') }} mt-1">
                                    @if($isOverdue)
                                        <i class="fas fa-exclamation-triangle"></i> {{ $daysCount }} {{ $daysCount == 1 ? __('يوم متأخر') : __('أيام متأخرة') }}
                                    @elseif($daysRemaining == 0)
                                        <i class="far fa-calendar-check"></i> {{ __('اليوم') }}
                                    @elseif($daysRemaining == 1)
                                        <i class="far fa-calendar-check"></i> {{ __('يوم واحد متبقي') }}
                                    @else
                                        <i class="far fa-calendar-check"></i> {{ $daysCount }} {{ __('أيام متبقية') }}
                                    @endif
                                </p>
                            @endif
                        </div>

                        <!-- Team Assignment -->
                        @if($stage->team)
                            <p class="text-xs text-gray-400 mt-1 text-center">
                                {{ $stage->team->name }}
                            </p>
                            
                            {{-- Show team members for superadmin (id=1) --}}
                            @if($isSuperAdmin && $stage->team->users->count() > 0)
                                <div class="mt-2 text-center">
                                    <details class="text-[10px]">
                                        <summary class="cursor-pointer text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-users"></i> {{ $stage->team->users->count() }} {{ __('أعضاء') }}
                                        </summary>
                                        <div class="mt-1 flex flex-wrap justify-center gap-1 max-w-[150px]">
                                            @foreach($stage->team->users as $teamMember)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium
                                                    @if($isCurrent) bg-blue-100 text-blue-700 border border-blue-200
                                                    @elseif($isCompleted) bg-green-100 text-green-700 border border-green-200
                                                    @else bg-gray-100 text-gray-600 border border-gray-200
                                                    @endif">
                                                    {{ $teamMember->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </details>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Arrow between stages -->
                    @if(!$loop->last)
                        <div class="flex-1 flex items-center justify-center mt-8">
                            <i class="fas fa-arrow-left text-gray-400"></i>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="mt-6 grid grid-cols-3 gap-4 pt-4 border-t border-gray-200">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $stages->count() }}</p>
                <p class="text-xs text-gray-500">{{ __('إجمالي المراحل') }}</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">
                    {{ $transitions->where('action', 'approve')->count() }}
                </p>
                <p class="text-xs text-gray-500">{{ __('الموافقات') }}</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-600">
                    {{ $appointmentRequest->workflowInstance->created_at->diffInDays(now()) }}
                </p>
                <p class="text-xs text-gray-500">{{ __('أيام في النظام') }}</p>
            </div>
        </div>
    </div>
@endif
