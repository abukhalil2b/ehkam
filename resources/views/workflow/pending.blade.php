<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('العناصر المعلقة') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($activities->isEmpty())
            <div class="bg-white shadow rounded text-center py-12">
                <svg class="mx-auto h-16 w-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ __('لا توجد عناصر معلقة') }}</h3>
                <p class="text-gray-500 mt-1">{{ __('ليس لديك أي عناصر تتطلب اتخاذ إجراء حالياً') }}</p>
            </div>
        @else
            <div class="bg-white shadow rounded overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('النوع') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('العنوان') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('التفاصيل') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('المرحلة') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('تاريخ الاستحقاق') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الإجراء') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($activities as $item)
                            <tr>
                                {{-- Type Badge --}}
                                <td class="px-6 py-4">
                                    @if($item->workflow_item_type === 'appointment')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ __('موعد') }}
                                        </span>
                                    @elseif($item->workflow_item_type === 'step')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ __('خطوة') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            {{ __('نشاط') }}
                                        </span>
                                    @endif
                                </td>
                                
                                {{-- Title --}}
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    @if($item->workflow_item_type === 'appointment')
                                        {{ $item->subject }}
                                    @elseif($item->workflow_item_type === 'step')
                                        {{ $item->name }}
                                    @else
                                        {{ $item->title }}
                                    @endif
                                </td>
                                
                                {{-- Details --}}
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if($item->workflow_item_type === 'appointment')
                                        {{ __('الطالب') }}: {{ $item->requester->name ?? '-' }}
                                    @elseif($item->workflow_item_type === 'step')
                                        {{ __('النشاط') }}: {{ $item->activity->title ?? '-' }}
                                    @else
                                        {{ $item->project->name ?? '-' }}
                                    @endif
                                </td>
                                
                                {{-- Current Stage --}}
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $item->workflowInstance->currentStage->name ?? '-' }}
                                </td>
                                
                                {{-- Due Date --}}
                                <td class="px-6 py-4 text-sm">
                                    @if($item->workflowInstance->stage_due_at)
                                        @php
                                            $daysRemaining = (int) now()->diffInDays($item->workflowInstance->stage_due_at, false);
                                            $isOverdue = $daysRemaining < 0;
                                            $daysCount = abs($daysRemaining);
                                        @endphp
                                        <div class="{{ $isOverdue ? 'text-red-600 font-bold' : ($daysRemaining <= 1 ? 'text-yellow-600' : 'text-gray-500') }}">
                                            {{ $item->workflowInstance->stage_due_at->format('Y-m-d') }}
                                            @if($isOverdue)
                                                <span class="text-xs block">(متأخر {{ $daysCount }} {{ $daysCount == 1 ? 'يوم' : 'أيام' }})</span>
                                            @elseif($daysRemaining == 0)
                                                <span class="text-xs block">(اليوم)</span>
                                            @elseif($daysRemaining == 1)
                                                <span class="text-xs block">(يوم واحد متبقي)</span>
                                            @else
                                                <span class="text-xs block">({{ $daysCount }} {{ $daysCount == 2 ? 'يومان' : 'أيام' }} متبقية)</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                
                                {{-- Action Link --}}
                                <td class="px-6 py-4 text-sm font-medium">
                                    @if($item->workflow_item_type === 'appointment')
                                        <a href="{{ route('appointments.show', $item) }}"
                                            class="text-green-600 hover:text-green-900">{{ __('معاينة') }}</a>
                                    @elseif($item->workflow_item_type === 'step')
                                        <a href="{{ route('step.show', $item) }}"
                                            class="text-purple-600 hover:text-purple-900">{{ __('معاينة') }}</a>
                                    @else
                                        <a href="{{ route('activity.show', $item) }}"
                                            class="text-indigo-600 hover:text-indigo-900">{{ __('معاينة') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>