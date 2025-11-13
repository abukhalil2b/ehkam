<x-app-layout title="تفاصيل الخطوة">

    <div class="py-6" dir="rtl">
        <div class="max-w-5xl mx-auto px-4">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-3">
                <h1 class="text-2xl font-bold text-[#1b5e20]">
                    {{ $step->name }}
                </h1>

                <a href="{{ route('step.index', $step->project_id) }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg shadow">
                    ⟵ رجوع إلى الخطوات
                </a>
            </div>

            {{-- Tabs --}}
            <div x-data="{ activeTab: 'info' }" class="space-y-4">

                <div class="flex gap-4 border-b border-gray-200">
                    <button type="button" @click="activeTab='info'"
                        :class="activeTab === 'info' ? 'border-b-2 border-green-600 text-green-700 font-semibold' :
                            'text-gray-500'"
                        class="pb-2">معلومات الخطوة</button>
                    <button type="button" @click="activeTab='units'"
                        :class="activeTab === 'units' ? 'border-b-2 border-green-600 text-green-700 font-semibold' :
                            'text-gray-500'"
                        class="pb-2"> المنفذون </button>
                    <button type="button" @click="activeTab='files'"
                        :class="activeTab === 'files' ? 'border-b-2 border-green-600 text-green-700 font-semibold' :
                            'text-gray-500'"
                        class="pb-2">الوثائق الداعمة</button>
                    <button type="button" @click="activeTab='targets'"
                        :class="activeTab === 'targets' ? 'border-b-2 border-green-600 text-green-700 font-semibold' :
                            'text-gray-500'"
                        class="pb-2">توزيع المستهدف</button>
                </div>

                {{-- Info Tab --}}
                <div x-show="activeTab==='info'" x-cloak
                    class="bg-white shadow rounded-2xl border border-gray-100 p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">
                        <div>
                            <p class="font-semibold text-[#1b5e20]">تاريخ البداية:</p>
                            <p>{{ $step->start_date ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1b5e20]">تاريخ النهاية:</p>
                            <p>{{ $step->end_date ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1b5e20]">النسبة المستهدفة:</p>
                            <p>{{ $step->target_percentage ?? 0 }}%</p>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1b5e20]">المرحلة:</p>
                            <p>{{ $phases[$step->phase]['title'] ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-[#1b5e20]">الحالة:</p>
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full
                                @if ($step->status == 'completed') bg-green-100 text-green-800
                                @elseif ($step->status == 'delayed') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                @switch($step->status)
                                    @case('completed')
                                        منجز
                                    @break

                                    @case('delayed')
                                        متأخر
                                    @break

                                    @default
                                        قيد التنفيذ
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Organizational Units Tab --}}
                <div x-show="activeTab==='units'" x-cloak
                    class="bg-white shadow rounded-2xl border border-gray-100 p-6 space-y-2">

                    @if ($step->stepOrganizationalUnitTasks && $step->stepOrganizationalUnitTasks->count())

                         <section>
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-right">
                أداء المنفذون
            </h2>

            <div class="text-xl text-red-800 font-bold mb-4">
                المستهدف الكلي للمؤشر: {{ number_format($overallTarget) }}
            </div>

            <table class="min-w-full border text-right border-gray-300 text-sm" dir="rtl">
                <thead>
                    <tr>
                        <th class="bg-[#1e3d4f] text-white p-3 border">المنفذون</th>

                        @php
                            // Get unique period template names from first unit
                            $periodNames = collect($unitData)
                                ->flatMap(fn($u) => array_keys($u['periods']))
                                ->unique();
                        @endphp

                        @foreach ($periodNames as $periodName)
                            <th class="bg-[#1e3d4f] text-white p-3 border">
                                {{ $periodName }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unitData as $unit)
                        @php
                            $totalPercentage = $unit['total_target'] > 0
                                ? round(($unit['total_achieved'] / $unit['total_target']) * 100, 2)
                                : 0;
                        @endphp

                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 border bg-[#1e3d4f] text-white font-semibold">
                                <div>{{ $unit['name'] }}</div>
                                <div>المستهدف الكلي للفترات: {{ number_format($unit['total_target']) }}</div>
                                <div>المحقق: {{ number_format($unit['total_achieved']) }}</div>
                                <div>بنسبة {{ $totalPercentage }} %</div>
                            </td>

                            @foreach ($periodNames as $periodName)
                                @php
                                    $period = $unit['periods'][$periodName] ?? null;
                                @endphp
                                <td class="p-3 border align-top">
                                    @if ($period)
                                        <div>المستهدف: {{ number_format($period['target']) }}</div>
                                        <div>المحقق: {{ number_format($period['achieved']) }}</div>
                                        <div>بنسبة {{ $period['percentage'] }} %</div>
                                    @else
                                        <div class="text-gray-400">—</div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
                    @else
                        <p class="text-gray-500">لا يوجد منفذون.</p>
                    @endif


                </div>

                {{-- Supporting Files Tab --}}
                <div x-show="activeTab==='files'" x-cloak
                    class="bg-white shadow rounded-2xl border border-gray-100 p-6 space-y-4">
                    <p class="font-semibold text-[#1b5e20] mb-1">الوثائق الداعمة:</p>
                    <p class="bg-gray-50 p-3 rounded-lg text-gray-700 whitespace-pre-line">
                        {{ $step->supporting_documents ?: '—' }}
                    </p>

                    @if ($step->stepEvidenceFiles && $step->stepEvidenceFiles->count())
                        <div class="mt-4">
                            <p class="font-semibold text-[#1b5e20] mb-2">الملفات المرفقة:</p>
                            <ul class="list-disc pr-4 space-y-1 text-gray-700">
                                @foreach ($step->stepEvidenceFiles as $file)
                                    <li>
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            {{ $file->file_name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Targets Tab --}}
                <div x-show="activeTab==='targets'" x-cloak
                    class="bg-white shadow rounded-2xl border border-gray-100 p-6 space-y-4">
                    @if ($step->is_need_to_put_target && $step->stepOrganizationalUnitTasks->count())
                        @foreach ($step->stepOrganizationalUnitTasks as $task)
                            <div class="mb-4">
                                <p class="font-semibold text-[#1b5e20] mb-2">{{ $task->organizational_name }}:</p>
                                @if ($task->stepOrganizationalUnitTaskTargets && $task->stepOrganizationalUnitTaskTargets->count())
                                    <ul class="list-disc pr-4 space-y-1 text-gray-700">
                                        @foreach ($task->stepOrganizationalUnitTaskTargets as $target)
                                            <li>{{ $target->periodTemplate->name ?? 'فترة غير مسماة' }} :
                                                {{ $target->target }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500">—</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">لا توجد أهداف تم إدخالها لهذا الخطوة.</p>
                    @endif
                </div>

            </div>

            {{-- Actions --}}
            <div class="bg-white mt-6 rounded-2xl shadow border border-gray-100 p-6">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3">

                    {{-- Edit Button --}}
                    <a href="{{ route('step.edit', $step->id) }}"
                        class="flex items-center justify-center gap-1 w-full sm:w-auto
                        bg-[#1b5e20] text-white font-medium rounded-lg
                        px-4 py-2 shadow hover:bg-[#2e7d32] transition">
                        تعديل
                    </a>

                    {{-- Delete Form --}}
                    <form action="{{ route('step.destroy', $step->id) }}" method="POST"
                        onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذه الخطوة؟');" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex items-center justify-center gap-1 w-full sm:w-auto
                            text-red-700 border border-red-700 font-medium rounded-lg
                            px-4 py-2 hover:bg-red-700 hover:text-white transition shadow-sm">
                            حذف
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
