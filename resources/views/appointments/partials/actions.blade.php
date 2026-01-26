<!-- Workflow Actions Card -->
@if($appointmentRequest->canBeActedUpon())
    <div class="bg-white shadow rounded-lg p-6 sticky top-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('الإجراءات') }}</h3>
        
        <div class="space-y-3">
            <!-- Approve Action -->
            @if($appointmentRequest->workflowInstance && $appointmentRequest->workflowInstance->currentStage)
                <form action="{{ route('appointments.approve', $appointmentRequest) }}" method="POST" x-data="{ showComments: false }">
                    @csrf
                    
                    <div class="mb-3">
                        <button type="button" @click="showComments = !showComments"
                            class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">
                            {{ __('الموافقة') }}
                        </button>
                    </div>

                    <div x-show="showComments" x-transition class="mb-3">
                        <label for="approve_comments" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('تعليقات (اختياري)') }}
                        </label>
                        <textarea name="comments" id="approve_comments" rows="3"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                            placeholder="{{ __('أدخل تعليقاتك هنا...') }}"></textarea>
                        <button type="submit"
                            class="mt-2 w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">
                            {{ __('تأكيد الموافقة') }}
                        </button>
                    </div>
                </form>
            @endif

            <!-- Info Message -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                <p class="font-medium mb-1">{{ __('المرحلة الحالية') }}:</p>
                <p>{{ $appointmentRequest->workflowInstance->currentStage->name ?? '—' }}</p>
                @if($appointmentRequest->workflowInstance->currentStage && $appointmentRequest->workflowInstance->currentStage->team)
                    <p class="mt-2 font-medium">{{ __('الفريق المسؤول') }}:</p>
                    <p>{{ $appointmentRequest->workflowInstance->currentStage->team->name }}</p>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('حالة الطلب') }}</h3>
        
        <div class="space-y-3">
            @if($appointmentRequest->isTerminal())
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-gray-700">
                    <p class="font-medium mb-1">{{ __('تم إكمال سير العمل') }}</p>
                    <p>
                        @if($appointmentRequest->workflowInstance->status == 'completed')
                            {{ __('تمت الموافقة على الطلب بنجاح') }}
                        @elseif($appointmentRequest->workflowInstance->status == 'rejected')
                            {{ __('تم رفض الطلب') }}
                        @endif
                    </p>
                </div>
            @elseif($appointmentRequest->isDraft())
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm text-yellow-800">
                    <p>{{ __('الطلب في حالة مسودة') }}</p>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-gray-700">
                    <p>{{ __('لا يمكن اتخاذ إجراءات حالياً') }}</p>
                </div>
            @endif
        </div>
    </div>
@endif
