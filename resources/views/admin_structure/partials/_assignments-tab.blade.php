{{-- 3. Assignments Tab --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm lg:col-span-2">
        <h3 class="text-xl font-bold mb-6 text-green-700 flex items-center space-x-3 rtl:space-x-reverse pb-3 border-b border-green-100">
            <span class="material-icons text-2xl bg-green-100 p-2 rounded-lg">history_toggle_off</span>
            <span>سجل التعيينات والترقيات</span>
        </h3>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-green-50 to-emerald-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-green-800 uppercase tracking-wider flex items-center justify-end">
                            <span class="material-icons text-lg ml-2">person</span>
                            الموظف
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-green-800 uppercase tracking-wider flex items-center justify-end">
                            <span class="material-icons text-lg ml-2">work</span>
                            الحالة الحالية
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-green-800 uppercase tracking-wider flex items-center justify-end">
                            <span class="material-icons text-lg ml-2">business</span>
                            الوحدة التنظيمية
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-green-800 uppercase tracking-wider flex items-center justify-end">
                            <span class="material-icons text-lg ml-2">event</span>
                            تاريخ البدء/الانتهاء
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-green-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 min-w-[150px]">
                                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                    <span class="material-icons text-blue-500 text-lg">person_outline</span>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 min-w-[150px]">
                                @if ($user->currentPositionHistory)
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <span class="material-icons text-indigo-500 text-lg">badge</span>
                                        <span class="font-bold text-indigo-600">{{ $user->currentPositionHistory->title }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse text-red-500">
                                        <span class="material-icons">warning</span>
                                        <span>غير معين</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 min-w-[150px]">
                                @if ($user->currentUnitHistory)
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <span class="material-icons text-green-500 text-lg">corporate_fare</span>
                                        <span>{{ $user->currentUnitHistory->name }} <span class="text-gray-500">({{ $user->currentUnitHistory->type }})</span></span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse text-red-500">
                                        <span class="material-icons">location_off</span>
                                        <span>غير محدد</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 min-w-[150px]">
                                @if ($user->currentHistory)
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <span class="material-icons text-green-500">schedule</span>
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1.5 rounded-full border border-green-200">
                                            حالي منذ: {{ \Carbon\Carbon::parse($user->currentHistory->start_date)->format('Y-m-d') }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td colspan="4" class="px-6 pt-3 pb-4 text-sm text-gray-600">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse mb-2">
                                    <span class="material-icons text-lg text-gray-500">timeline</span>
                                    <p class="font-bold text-gray-700">السجل الكامل:</p>
                                </div>
                                <ul class="space-y-2 mr-6">
                                    @foreach ($user->positionHistory->sortByDesc('start_date') as $history)
                                        @php
                                            $pos = $allPositions->firstWhere('id', $history->position_id)->title ?? 'N/A';
                                            $unitName = $organizationalUnits->firstWhere('id', $history->organizational_unit_id)->name ?? 'N/A';
                                        @endphp
                                        <li class="flex items-center space-x-3 rtl:space-x-reverse text-xs bg-white p-2 rounded-lg border border-gray-200">
                                            <span class="material-icons text-purple-400 text-sm">arrow_left</span>
                                            <span class="flex-1">
                                                <span class="font-medium text-gray-800">{{ $pos }}</span> في 
                                                <span class="font-medium text-gray-800">{{ $unitName }}</span> من
                                                {{ \Carbon\Carbon::parse($history->start_date)->format('Y/m/d') }}
                                                إلى
                                                {{ $history->end_date ? \Carbon\Carbon::parse($history->end_date)->format('Y/m/d') : 'الآن' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    
</div>