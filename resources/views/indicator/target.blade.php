    <x-app-layout>

        <div class="container py-2 mx-auto px-4">


            <!-- Year Target & Frequency Selector -->
            <div class="p-6 bg-white rounded-xl shadow space-y-4 border mb-8">
                <h1 class="text-xl md:text-2xl font-bold">
                    {{ $indicator->title }}
                </h1>


                <h2 class="text-xl font-bold text-gray-700">
                    بيانات المستهدف للمؤشر لعام
                    <span class="text-blue-700">{{ $current_year }}</span>:
                    <span class="text-red-800">{{ number_format($indicator->target_for_indicator) }}</span>
                </h2>

                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <label class="font-semibold text-gray-700">دورية قياس
                        المستهدف:</label>
                    <span class="text-blue-700">{{ __($indicator->period) }}</span>
                </div>
            </div>

            <!-- Contributing Sectors Section -->
            <div class="p-6 bg-white rounded-xl shadow space-y-4 border">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-700">
                        توزيع المستهدف على الجهات المساندة
                    </h2>

                </div>

                <div class="space-y-4">
                    @foreach ($sectors as $sector)
                        <div class="p-4 bg-gray-50 rounded-xl shadow space-y-3 border border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-gray-800">
                                    {{ $sector->name }}
                                </div>

                            </div>
                            <div class="flex gap-2 text-sm text-gray-600">
                                <div>المستهدف للمساهمة:</div>
                                <span class="font-medium text-gray-800">4000</span>
                                <div> المحقق:</div>
                                <span class="font-medium text-gray-800">4000</span>
                            </div>
                            <div class="text-blue-600 font-bold">المحقق لهذا العام/من اجالي المستهدف لهذه السنة*100
                            </div>
                            <div class="overflow-x-auto">
                                <table role="table"
                                    class="min-w-full border text-right border-gray-300 rounded-md overflow-hidden"
                                    dir="rtl">
                                    <thead class="bg-gray-200 text-xs text-gray-700">
                                        <tr>
                                            @foreach ($periods as $period)
                                                <th class="p-2 border-l border-gray-300"> {{ $period->name }} </th>
                                            @endforeach
                                            <th class="p-2 bg-gray-300">إجمالي النسبة من الهدف السنوي</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                        <tr>
                                            @foreach ($periods as $period)
                                                <td class="p-2 border-l border-gray-300 align-top">
                                                    <div>المستهدف: <span class="font-semibold">1000</span></div>
                                                    <div>المحقق: <span class="font-semibold">1000</span></div>
                                                    <div>بنسبة: <span class="font-bold text-blue-600">
                                                            المحقق لهذا الربع/المستهدف لهذا الربع * 100
                                                        </span>
                                                    </div>
                                                </td>
                                            @endforeach
                                            <td class="p-2 bg-gray-100 align-top font-semibold">
                                                <div>
                                                    <span>إجمالي المحقق</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 space-y-4">
                <h3 id="contribute-modal-title" class="text-lg font-bold text-gray-800">
                    توزيع المستهدف على الجهات المساندة
                </h3>

                <div class="space-y-1">
                    <label for="contribute-target" class="text-sm font-medium text-gray-700">
                        المستهدف
                        الإجمالي
                        للمساهمة
                        لهذا العام
                    </label>
                </div>

                <div class="space-y-3 border p-3 rounded-md bg-gray-50">
                    <h4 class="text-sm font-semibold text-gray-700">توزيع المستهدف على الفترات:</h4>
                    @foreach ($periods as $period)
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-600">
                                {{ $period->name }}
                            </label>
                            <input type="number"
                                class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500"
                                min="0" />
                        </div>
                    @endforeach

                </div>

                <div class="flex justify-end space-x-2 rtl:space-x-reverse pt-4">
                    <button type="button"
                        class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        إلغاء
                    </button>
                    <button type="button"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        إضافة
                    </button>
                </div>
            </div>


        </div>

    </x-app-layout>
