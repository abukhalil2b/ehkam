<x-app-layout>
    <x-slot name="header">
        المؤشر
    </x-slot>

    <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-gray-200 mb-8">
        <table class="min-w-full divide-y divide-gray-200 text-right" dir="rtl">
            <tbody class="divide-y divide-gray-200">
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        المعيار الرئيسي</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        المعيار الفرعي</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        رمز المؤشر</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        المؤشر</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        مالك المؤشر</th>
                    <td class="p-4 text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        الجهات المساندة</th>
                    <td class="p-4 text-gray-900">
                        <div class="flex flex-wrap gap-6">

                            <div class="flex flex-wrap gap-6">
                                @foreach ($sectors as $sector)
                                    <div class="mr-2 text-sm md:text-base">{{ $sector->name }}</div>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        وصف المؤشر</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        أداة القياس</th>
                    <td class="p-4 text-base text-gray-900">

                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        نوع الدليل الداعم</th>
                    <td class="p-4 text-base text-gray-900">
                        <ol>
                            <li>احصائيات</li>
                            <li>قرار</li>
                            <li>صور حفل ختامي</li>
                        </ol>
                    </td>
                </tr>

                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        قطبية القياس</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        شرح قطبية القياس</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        وحدة القياس</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        معادلة القياس</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        تاريخ الرصد الأول</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        معادلة احتساب خط الأساس للربع الأول في السنة الأولى من التطبيق</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        خط الأساس بعد التطبيق</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        اسئلة الاستبيان (سؤال للتحقق)</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        مبادرات ومشاريع مقترحة</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>
                <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                    <th scope="row"
                        class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                        المؤشرات الفرعية</th>
                    <td class="p-4 text-base text-gray-900">
                        {{ $indicator->title }}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>


</x-app-layout>
