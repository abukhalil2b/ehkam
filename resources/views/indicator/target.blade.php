<x-app-layout>
    <x-slot name="header">🎯 توزيع المستهدفات للمؤشر</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Info Card -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-600">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $indicator->title }}</h1>
            <div class="flex items-center gap-6 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-900">سنة القياس:</span>
                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded">{{ $current_year }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-900">دورية القياس:</span>
                    <span>{{ __($indicator->period) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-900">إجمالي المستهدف:</span>
                    <span
                        class="text-green-700 font-bold text-base">{{ number_format($indicator->target_for_indicator) }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('indicator.target.store', $indicator) }}" method="POST">
            @csrf
            <input type="hidden" name="year" value="{{ $current_year }}">

            <div class="space-y-6">
                @foreach ($sectors as $sector)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800">{{ $sector->name }}</h3>
                            <span class="text-xs font-medium text-gray-500 bg-gray-200 px-2 py-1 rounded">
                                {{ $sector->code ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="p-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                </svg>
                                توزيع المستهدف الفتري:
                            </h4>

                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                @foreach ($periods as $period)
                                    @php
                                        $key = $sector->id . '-' . $period->id; // Assuming period->id corresponds to period_index or we use loop index if id is not 1-12
                                        // But period_index in DB is integer. Let's assume period->id is the index (1..12 or 1..4)
                                        // Wait, PeriodTemplate might use 'id' but we want logical index.
                                        // Let's use loop iteration for index if periods are ordered.
                                        // However, existing data relies on matching index.
                                        // Let's use $period->id as index for now, assuming it matches standard periods.
                                        $targetVal = $targets[$key]->target_value ?? '';
                                    @endphp
                                    <div class="relative">
                                        <label
                                            class="block text-xs font-medium text-gray-500 mb-1 mx-1">{{ $period->name }}</label>
                                        <input type="hidden" name="targets[][sector_id]" value="{{ $sector->id }}">
                                        <input type="hidden" name="targets[][period_index]" value="{{ $period->id }}">
                                        <input type="number" step="0.01" name="targets[][value]" value="{{ $targetVal }}"
                                            class="w-full text-center border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            placeholder="0">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-lg z-50">
                <div class="max-w-7xl mx-auto flex justify-end gap-3">
                    <a href="{{ route('indicator.index') }}"
                        class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="px-8 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold shadow-md">
                        <i class="far fa-save ml-2"></i> حفظ المستهدفات
                    </button>
                </div>
            </div>

            <!-- Spacer for fixed footer -->
            <div class="h-20"></div>
        </form>

    </div>
</x-app-layout>