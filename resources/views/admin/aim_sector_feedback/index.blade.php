<x-app-layout>
    <div class="p-6" dir="rtl">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                قيم المؤشرات لسنة {{ $current_year }}
            </h2>

            <!-- Year Switcher -->
            <div class="flex gap-2 bg-gray-100 rounded-xl p-1">
                @foreach ($years as $year)
                    <a href="{{ route('admin.aim_sector_feedback.index', $year) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold
                       {{ $year === $current_year ? 'bg-[#00bab1] text-white shadow' : 'text-gray-600 hover:bg-white' }}">
                        {{ $year }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow-md rounded-xl border border-gray-200">
            <table class="w-full text-center text-sm">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3 font-bold text-gray-700 text-right">
                            المؤشر
                        </th>

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
                    @foreach ($aims as $aim)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Aim -->
                            <td class="p-3 font-semibold text-gray-800 text-right w-64">
                                {{ $aim->title }}
                            </td>

                            <!-- Sector Values -->
                            @foreach ($sectors as $sector)
                                <td class="p-3">
                                    @php
                                        $value = $aim->feedbackBySector[$sector->id];
                                    @endphp

                                    @if ($value > 0)
                                        <a href="{{ route('admin.aim_sector_feedback.show', [
                                            'aim' => $aim->id,
                                            'sector' => $sector->id,
                                        ]) }}"
                                            class="text-lg font-bold text-[#00bab1] hover:text-[#008a84] transition">
                                            {{ $value }}
                                        </a>
                                    @else
                                        <span class="text-gray-400 font-semibold cursor-not-allowed">
                                            —
                                        </span>
                                    @endif

                                </td>
                            @endforeach

                            <!-- Total -->
                            <td class="p-3 text-lg font-bold text-green-600">
                                {{ $aim->total }}
                            </td>
                        </tr>
                    @endforeach

                    @if ($aims->isEmpty())
                        <tr>
                            <td colspan="{{ $sectors->count() + 2 }}" class="p-6 text-gray-500">
                                لا توجد مؤشرات مسجلة.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
