<x-app-layout>
    <div class="p-6" dir="rtl">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                قيم المؤشرات لسنة {{ $current_year }}
            </h2>

            {{-- Year Switcher --}}
            <div class="flex gap-2 bg-gray-100 rounded-xl p-1">
                @foreach ($years as $year)
                    <a href="{{ route('admin.indicator_feedback_value.index', $year) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold
                            {{ $year == $current_year ? 'bg-[#00bab1] text-white shadow' : 'text-gray-600 hover:bg-white' }}">
                        {{ $year }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto bg-white shadow-md rounded-xl border border-gray-200">
            <table class="w-full text-center text-sm">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3 font-bold text-gray-700">المؤشر</th>

                        @foreach ($sectors as $sector)
                            <th class="p-3 font-bold text-gray-700">
                                {{ $sector->short_name }}
                            </th>
                        @endforeach
                        <th class="p-3 font-bold text-gray-700">
                            المجموع
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($indicators as $indicator)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 font-semibold text-gray-800 w-64">
                                {{ $indicator->title }}
                            </td>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($sectors as $sector)
                                @php
                                    $value =
                                        $indicator->indicatorFeedbackValues->where('sector_id', $sector->id)->first()
                                            ->achieved ?? 0;
                                    $total = $total + $value;
                                @endphp

                                <td class="p-3">
                                    <a href="{{ route('admin.indicator_feedback_value.show', [
                                        'indicator' => $indicator,
                                        'sector' => $sector,
                                    ]) }}"
                                        class="text-lg font-bold text-[#00bab1] hover:text-[#008a84] transition">
                                        {{ $value }}
                                    </a>
                                </td>
                            @endforeach
                            <td class="text-lg font-bold text-green-600 ">
                                {{ $total }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
