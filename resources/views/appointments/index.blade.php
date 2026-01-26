<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('طلبات المواعيد') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Action Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <a href="{{ route('appointments.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                {{ __('إضافة طلب موعد جديد') }}
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg p-4 mb-4">
            <form method="GET" action="{{ route('appointments.index') }}" class="flex flex-wrap gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('الحالة') }}</label>
                    <select name="status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                        <option value="">{{ __('الكل') }}</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('مسودة') }}</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('قيد المعالجة') }}</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('مرفوض') }}</option>
                        <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>{{ __('محجوز') }}</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('تصفية') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Appointments Table -->
        @if($appointments->isEmpty())
            <div class="bg-white shadow rounded text-center py-12">
                <svg class="mx-auto h-16 w-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ __('لا توجد طلبات مواعيد') }}</h3>
                <p class="text-gray-500 mt-1">{{ __('لم يتم إنشاء أي طلبات مواعيد حتى الآن') }}</p>
            </div>
        @else
            <div class="bg-white shadow rounded overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الموضوع') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الوزير') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الحالة') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('المرحلة الحالية') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('تاريخ الإنشاء') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الإجراء') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($appointments as $appointment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->subject }}</div>
                                    @if($appointment->description)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($appointment->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appointment->minister->name ?? '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($appointment->status == 'booked') bg-green-100 text-green-800
                                        @elseif($appointment->status == 'rejected') bg-red-100 text-red-800
                                        @elseif($appointment->status == 'in_progress') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @if($appointment->status == 'draft') {{ __('مسودة') }}
                                        @elseif($appointment->status == 'in_progress') {{ __('قيد المعالجة') }}
                                        @elseif($appointment->status == 'rejected') {{ __('مرفوض') }}
                                        @elseif($appointment->status == 'booked') {{ __('محجوز') }}
                                        @else {{ $appointment->status }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $appointment->workflowInstance->currentStage->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $appointment->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('appointments.show', $appointment) }}"
                                        class="text-indigo-600 hover:text-indigo-900">{{ __('عرض') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
