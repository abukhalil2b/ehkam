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
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ __('لا توجد أنشطة معلقة') }}</h3>
                <p class="text-gray-500 mt-1">{{ __('ليس لديك أي أنشطة تتطلب اتخاذ إجراء حالياً') }}</p>
            </div>
        @else
            <div class="bg-white shadow rounded overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('النشاط') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('المشروع') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('المرحلة') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('تاريخ الاستحقاق') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الإجراء') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($activities as $activity)
                            <tr>
                                <td class="px-6 py-4">{{ $activity->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->project->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->currentStage->name ?? '-' }}</td>
                                <td
                                    class="px-6 py-4 text-sm {{ $activity->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                    {{ $activity->stage_due_at ? $activity->stage_due_at->format('Y-m-d') : '-' }}
                                    @if($activity->isOverdue()) <span class="text-xs">(متأخر)</span> @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <a href="{{ route('activity.show', $activity) }}"
                                        class="text-indigo-600 hover:text-indigo-900">{{ __('معاينة') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>