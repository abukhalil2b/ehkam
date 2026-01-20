<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('جميع الخطوات في سير العمل') }} <span class="text-sm text-gray-500">({{ __('عرض المشرف العام') }})</span>
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if($steps->isEmpty())
            <div class="bg-white shadow rounded text-center py-12">
                <svg class="mx-auto h-16 w-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ __('لا توجد خطوات في سير العمل') }}</h3>
                <p class="text-gray-500 mt-1">{{ __('لا توجد أي خطوات نشطة في سير العمل حالياً') }}</p>
            </div>
        @else
            {{-- Summary Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white shadow rounded p-4">
                    <div class="text-sm text-gray-500">{{ __('إجمالي الخطوات') }}</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $steps->count() }}</div>
                </div>
                <div class="bg-white shadow rounded p-4">
                    <div class="text-sm text-gray-500">{{ __('قيد المراجعة') }}</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ $steps->where('status', 'in_progress')->count() }}</div>
                </div>
                <div class="bg-white shadow rounded p-4">
                    <div class="text-sm text-gray-500">{{ __('معادة') }}</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $steps->where('status', 'returned')->count() }}</div>
                </div>
                <div class="bg-white shadow rounded p-4">
                    <div class="text-sm text-gray-500">{{ __('معلقة') }}</div>
                    <div class="text-2xl font-bold text-gray-600">{{ $steps->where('status', 'pending')->count() }}</div>
                </div>
            </div>

            {{-- Table View for Admin --}}
            <div class="bg-white shadow rounded overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الخطوة') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('المشروع') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('سير العمل') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('المرحلة الحالية') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الفريق المسؤول') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الحالة') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('منشئ الخطوة') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('تاريخ الاستحقاق') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($steps as $step)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <a href="{{ route('step.show', $step) }}" class="text-indigo-600 hover:underline font-medium">
                                            {{ $step->name }}
                                        </a>
                                        @if($step->priority && $step->priority <= 2)
                                            <span class="mr-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs">{{ __('عاجل') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $step->project?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $step->workflow?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $step->currentStage?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $step->currentStage?->team?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs {{ 
                                        $step->status === 'returned' ? 'bg-yellow-100 text-yellow-800' : 
                                        ($step->status === 'in_progress' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800') 
                                    }}">
                                        {{ $step->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $step->creator?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm {{ $step->due_date?->isPast() ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                    {{ $step->due_date?->format('Y-m-d') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Legend --}}
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded p-4">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-1">{{ __('وضع العرض للمشرف العام') }}</h4>
                        <p class="text-sm text-blue-800">
                            {{ __('هذه الصفحة للعرض فقط. يمكنك مشاهدة جميع الخطوات في سير العمل عبر جميع الفرق والمشاريع. لاتخاذ إجراء على خطوة معينة، يرجى استخدام صفحة تفاصيل الخطوة.') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
