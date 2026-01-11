<x-app-layout>
    <x-slot name="header">📊 النتائج المحققة للمؤشر</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Info Card -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-600">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $indicator->title }}</h1>
            <div class="flex items-center gap-6 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-900">سنة القياس:</span>
                    <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded">{{ $current_year }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-900">دورية القياس:</span>
                    <span>{{ __($indicator->period) }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('indicator.achieved.store', $indicator) }}" method="POST">
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
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-center">
                                    <thead>
                                        <tr>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">الفترة</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">المستهدف</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">المحقق</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">نسبة الإنجاز
                                            </th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach ($periods as $period)
                                            @php
                                                $key = $sector->id . '-' . $period->id;
                                                $targetObj = $targets[$key] ?? null;
                                                $targetVal = $targetObj ? $targetObj->target_value : 0;

                                                $achievedObj = $achievements[$key] ?? null;
                                                $achievedVal = $achievedObj ? $achievedObj->achieved_value : '';
                                                $achievedNotes = $achievedObj ? $achievedObj->notes : '';

                                                $percentage = 0;
                                                if ($targetVal > 0 && is_numeric($achievedVal)) {
                                                    $percentage = ($achievedVal / $targetVal) * 100;
                                                }
                                            @endphp
                                            <tr>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                    {{ $period->name }}
                                                    <input type="hidden" name="achievements[][sector_id]"
                                                        value="{{ $sector->id }}">
                                                    <input type="hidden" name="achievements[][period_index]"
                                                        value="{{ $period->id }}">
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 font-medium">
                                                    {{ $targetVal > 0 ? number_format($targetVal) : '-' }}
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <input type="number" step="0.01" name="achievements[][value]"
                                                        value="{{ $achievedVal }}"
                                                        class="w-32 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                        placeholder="0">
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    @if($percentage > 0)
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $percentage >= 100 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                            {{ number_format($percentage, 1) }}%
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <input type="text" name="achievements[][notes]" value="{{ $achievedNotes }}"
                                                        class="w-full text-right border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                        placeholder="ملاحظات...">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
                        class="px-8 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold shadow-md">
                        <i class="far fa-save ml-2"></i> حفظ النتائج
                    </button>
                </div>
            </div>

            <div class="h-20"></div>
        </form>

    </div>
</x-app-layout>