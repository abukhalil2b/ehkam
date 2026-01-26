<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تفاصيل طلب الموعد') }}: {{ $appointmentRequest->subject }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('appointments.index') }}"
                class="text-indigo-600 hover:text-indigo-900 text-sm">
                &larr; {{ __('عودة إلى قائمة الطلبات') }}
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Details Card -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('تفاصيل الطلب') }}</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">{{ __('الموضوع') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $appointmentRequest->subject }}</p>
                        </div>

                        @if($appointmentRequest->description)
                            <div>
                                <label class="text-sm font-medium text-gray-500">{{ __('الوصف') }}</label>
                                <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $appointmentRequest->description }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">{{ __('الطالب') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $appointmentRequest->requester->name ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">{{ __('الوزير') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $appointmentRequest->minister->name ?? '—' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">{{ __('الأولوية') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($appointmentRequest->priority == 'low') {{ __('منخفضة') }}
                                    @elseif($appointmentRequest->priority == 'normal') {{ __('عادية') }}
                                    @elseif($appointmentRequest->priority == 'high') {{ __('عالية') }}
                                    @elseif($appointmentRequest->priority == 'urgent') {{ __('عاجلة') }}
                                    @else {{ $appointmentRequest->priority }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">{{ __('تاريخ الإنشاء') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $appointmentRequest->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">{{ __('الحالة') }}</label>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($appointmentRequest->status == 'booked') bg-green-100 text-green-800
                                    @elseif($appointmentRequest->status == 'rejected') bg-red-100 text-red-800
                                    @elseif($appointmentRequest->status == 'in_progress') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($appointmentRequest->status == 'draft') {{ __('مسودة') }}
                                    @elseif($appointmentRequest->status == 'in_progress') {{ __('قيد المعالجة') }}
                                    @elseif($appointmentRequest->status == 'rejected') {{ __('مرفوض') }}
                                    @elseif($appointmentRequest->status == 'booked') {{ __('محجوز') }}
                                    @else {{ $appointmentRequest->status }}
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Workflow Visualization -->
                @include('appointments.partials.workflow-visualization')

                <!-- Workflow Status Card -->
                @if($appointmentRequest->workflowInstance)
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('حالة سير العمل') }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">{{ __('سير العمل') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $appointmentRequest->workflowInstance->workflow->name ?? '—' }}
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">{{ __('المرحلة الحالية') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $appointmentRequest->workflowInstance->currentStage->name ?? '—' }}
                                </p>
                            </div>

                            @if($appointmentRequest->workflowInstance->currentStage && $appointmentRequest->workflowInstance->currentStage->team)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">{{ __('الفريق المسؤول') }}</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $appointmentRequest->workflowInstance->currentStage->team->name ?? '—' }}
                                    </p>
                                </div>
                            @endif

                            <div>
                                <label class="text-sm font-medium text-gray-500">{{ __('حالة سير العمل') }}</label>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($appointmentRequest->workflowInstance->status == 'completed') bg-green-100 text-green-800
                                        @elseif($appointmentRequest->workflowInstance->status == 'rejected') bg-red-100 text-red-800
                                        @elseif($appointmentRequest->workflowInstance->status == 'returned') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        @if($appointmentRequest->workflowInstance->status == 'draft') {{ __('مسودة') }}
                                        @elseif($appointmentRequest->workflowInstance->status == 'in_progress') {{ __('قيد المعالجة') }}
                                        @elseif($appointmentRequest->workflowInstance->status == 'completed') {{ __('مكتمل') }}
                                        @elseif($appointmentRequest->workflowInstance->status == 'rejected') {{ __('مرفوض') }}
                                        @elseif($appointmentRequest->workflowInstance->status == 'returned') {{ __('معاد') }}
                                        @else {{ $appointmentRequest->workflowInstance->status }}
                                        @endif
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Available Slots (for Secretary) -->
                @if($availableSlots->isNotEmpty() && $appointmentRequest->canBeActedUpon())
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('المواعيد المتاحة') }}</h3>
                        
                        <div class="space-y-3">
                            @foreach($availableSlots as $slot)
                                <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $slot->start_date ? \Carbon\Carbon::parse($slot->start_date)->format('Y-m-d H:i') : '—' }}
                                        </p>
                                        @if($slot->end_date)
                                            <p class="text-xs text-gray-500">
                                                حتى: {{ \Carbon\Carbon::parse($slot->end_date)->format('Y-m-d H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                    <form action="{{ route('appointments.select-slot', $appointmentRequest) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="slot_id" value="{{ $slot->id }}">
                                        <button type="submit"
                                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">
                                            {{ __('اختيار هذا الموعد') }}
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Workflow History -->
                @if($appointmentRequest->transitions->isNotEmpty())
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('سجل سير العمل') }}</h3>
                        
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($appointmentRequest->transitions as $transition)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 right-4 -mr-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3 space-x-reverse">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                        @if($transition->action === 'submit') bg-blue-500
                                                        @elseif($transition->action === 'approve') bg-green-500
                                                        @elseif($transition->action === 'reject') bg-red-500
                                                        @elseif($transition->action === 'return') bg-yellow-500
                                                        @else bg-gray-500 @endif
                                                    ">
                                                        <i class="text-white text-xs fas 
                                                            @if($transition->action === 'submit') fa-paper-plane
                                                            @elseif($transition->action === 'approve') fa-check
                                                            @elseif($transition->action === 'reject') fa-times
                                                            @elseif($transition->action === 'return') fa-undo
                                                            @else fa-circle @endif
                                                        "></i>
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div>
                                                        <p class="text-sm text-gray-500">
                                                            <span class="font-medium text-gray-900">{{ $transition->actor->name ?? '—' }}</span>
                                                            
                                                            @if($transition->action === 'submit')
                                                                {{ __('قام بإرسال الطلب') }}
                                                            @elseif($transition->action === 'approve')
                                                                {{ __('وافق على الطلب') }}
                                                            @elseif($transition->action === 'reject')
                                                                {{ __('رفض الطلب') }}
                                                            @elseif($transition->action === 'return')
                                                                {{ __('أعاد الطلب') }}
                                                            @else
                                                                {{ $transition->action }}
                                                            @endif
                                                            
                                                            @if($transition->toStage)
                                                                {{ __('إلى مرحلة') }} <span class="font-medium text-gray-900">{{ $transition->toStage->name }}</span>
                                                            @endif
                                                        </p>
                                                        @if($transition->comments)
                                                            <div class="mt-2 text-sm text-gray-700 bg-gray-50 p-2 rounded border border-gray-100 italic">
                                                                "{{ $transition->comments }}"
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500 mt-1">
                                                        <time datetime="{{ $transition->created_at }}">{{ $transition->created_at->format('Y/m/d H:i') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar: Actions -->
            <div class="lg:col-span-1">
                @include('appointments.partials.actions')
            </div>
        </div>
    </div>
</x-app-layout>
