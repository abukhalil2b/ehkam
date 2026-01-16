<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('سجل التحولات') }} - {{ $step->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Step Info --}}
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded overflow-hidden">
                    <div class="bg-indigo-600 text-white px-4 py-3">
                        <h3 class="font-semibold">{{ __('معلومات الخطوة') }}</h3>
                    </div>
                    <div class="p-4">
                        <h4 class="text-lg font-medium">{{ $step->name }}</h4>
                        <hr class="my-4">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('سير العمل') }}</span>
                                <span>{{ $step->workflow?->name ?? __('غير محدد') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">{{ __('المرحلة الحالية') }}</span>
                                <span>{{ $step->currentStage?->name ?? __('غير محدد') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">{{ __('الحالة') }}</span>
                                <span
                                    class="px-2 py-1 rounded text-xs {{ $step->status === 'completed' ? 'bg-green-100 text-green-800' : ($step->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-indigo-100 text-indigo-800') }}">
                                    {{ $step->status_label ?? $step->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transition History --}}
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded overflow-hidden">
                    <div class="bg-blue-500 text-white px-4 py-3">
                        <h3 class="font-semibold">{{ __('سجل التحولات') }}</h3>
                    </div>
                    <div class="p-4">
                        @if($step->transitions->isEmpty())
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-500 mt-2">{{ __('لا توجد تحولات مسجلة') }}</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($step->transitions as $transition)
                                                    <div class="border-r-4 p-4 rounded-lg {{ 
                                                                $transition->action === 'submit' ? 'border-indigo-500 bg-indigo-50' :
                                    ($transition->action === 'approve' ? 'border-green-500 bg-green-50' :
                                        ($transition->action === 'return' ? 'border-yellow-500 bg-yellow-50' : 'border-red-500 bg-red-50'))
                                                            }}">
                                                        <div class="flex justify-between items-start">
                                                            <div class="flex items-start gap-3">
                                                                <div class="p-2 rounded-full {{ 
                                                                            $transition->action === 'submit' ? 'bg-indigo-500' :
                                    ($transition->action === 'approve' ? 'bg-green-500' :
                                        ($transition->action === 'return' ? 'bg-yellow-500' : 'bg-red-500'))
                                                                        }}">
                                                                    @if($transition->action === 'submit')
                                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                                        </svg>
                                                                    @elseif($transition->action === 'approve')
                                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M5 13l4 4L19 7"></path>
                                                                        </svg>
                                                                    @elseif($transition->action === 'return')
                                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                                        </svg>
                                                                    @else
                                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M6 18L18 6M6 6l12 12"></path>
                                                                        </svg>
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    <h4 class="font-semibold">{{ $transition->action_label }}</h4>
                                                                    <p class="text-sm text-gray-600">{{ $transition->actor->name }}</p>
                                                                </div>
                                                            </div>
                                                            <span class="text-sm text-gray-500">
                                                                {{ $transition->created_at->format('Y-m-d H:i') }}
                                                            </span>
                                                        </div>
                                                        <div class="mt-3 text-sm">
                                                            <span class="text-gray-500">
                                                                {{ $transition->fromStage?->name ?? __('بداية') }}
                                                            </span>
                                                            <svg class="inline w-4 h-4 mx-2 text-gray-400 rotate-180" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                            </svg>
                                                            <span class="text-gray-500">
                                                                {{ $transition->toStage?->name ?? __('نهاية') }}
                                                            </span>
                                                        </div>
                                                        @if($transition->comments)
                                                            <div class="mt-3 bg-white p-3 rounded">
                                                                <p class="text-sm text-gray-500 mb-1">{{ __('ملاحظات') }}:</p>
                                                                <p class="text-sm">{{ $transition->comments }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>