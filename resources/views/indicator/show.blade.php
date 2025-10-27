<x-app-layout><x-slot name="header">تفاصيل المؤشر: {{ $indicator->title }}</x-slot>
    <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-gray-200 mb-8">
        <table class="min-w-full divide-y divide-gray-200 text-right" dir="rtl">
            <tbody class="divide-y divide-gray-200">

                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        السنة</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->current_year}}
                    </td>
                </tr>

                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        المستهدف</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->target_for_indicator ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 1: Main Criteria --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        المعيار الرئيسي</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->main_criteria ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 2: Sub Criteria --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        المعيار الفرعي</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->sub_criteria ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 3: Indicator Code --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        رمز المؤشر</th>
                    <td class="p-4 text-base text-gray-900 font-mono text-left" dir="ltr">
                        {{ $indicator->code ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 4: Indicator Title --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        المؤشر (العنوان)</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>

                {{-- Row 5: Owner --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        مالك المؤشر</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->owner ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 6: Supporting Sectors --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        الجهات المساندة</th>
                    <td class="p-4 text-gray-900">
                        @if (count($selectedSectors) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach ($selectedSectors as $sectorName)
                                    <span
                                        class="px-3 py-1 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-full">
                                        {{ $sectorName }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-500">لا توجد جهات مساندة محددة.</span>
                        @endif
                    </td>
                </tr>

                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        دورة القياس (الفترة)</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->period ? __($indicator->period) : 'N/A' }}

                    </td>
                </tr>

                {{-- Row 8: Indicator Description --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        وصف المؤشر</th>
                    <td class="p-4 text-base text-gray-900 whitespace-pre-line">
                        {{ $indicator->description ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 9: Measurement Tool --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        أداة القياس</th>
                    <td class="p-4 text-base text-gray-900 whitespace-pre-line">
                        {{ $indicator->measurement_tool ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 10: Polarity --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        قطبية القياس</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->polarity ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 11: Polarity Description --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        شرح قطبية القياس</th>
                    <td class="p-4 text-base text-gray-900 whitespace-pre-line">
                        {{ $indicator->polarity_description ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 12: Unit --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        وحدة القياس</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->unit ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 13: Formula --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        معادلة القياس</th>
                    <td class="p-4 text-base text-gray-900 font-mono text-left" dir="ltr">
                        {{ $indicator->formula ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 14: First Observation Date --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        تاريخ الرصد الأول</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->first_observation_date ? \Carbon\Carbon::parse($indicator->first_observation_date)->toDateString() : 'N/A' }}
                    </td>
                </tr>

                {{-- Row 15: Baseline Formula --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        معادلة احتساب خط الأساس</th>
                    <td class="p-4 text-base text-gray-900 whitespace-pre-line">
                        {{ $indicator->baseline_formula ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 16: Baseline After Application --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        خط الأساس بعد التطبيق</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->baseline_after_application ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 17: Survey Question --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        اسئلة الاستبيان (سؤال للتحقق)</th>
                    <td class="p-4 text-base text-gray-900 whitespace-pre-line">
                        {{ $indicator->survey_question ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 18: Proposed Initiatives --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        مبادرات ومشاريع مقترحة</th>
                    <td class="p-4 text-base text-gray-900 whitespace-pre-line">
                        {{ $indicator->proposed_initiatives ?? 'N/A' }}
                    </td>
                </tr>

                {{-- Row 19: Sub-Indicators (Children) --}}
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        المؤشرات الفرعية</th>
                    <td class="p-4 text-base text-gray-900">
                        @if ($subIndicators->isNotEmpty())
                            <ul class="list-disc pr-6">
                                @foreach ($subIndicators as $subIndicator)
                                    <li class="mb-1">
                                        {{-- Link to sub-indicator detail page --}}
                                        <a href="{{ route('indicator.show', $subIndicator) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            [{{ $subIndicator->code ?? 'N/A' }}] {{ $subIndicator->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-500">لا يوجد مؤشرات فرعية مرتبطة بهذا المؤشر.</span>
                        @endif
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</x-app-layout>
