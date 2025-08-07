<x-app-layout>

    <!-- Header -->
    <div class="bg-[#1e3d4f] text-white py-6 px-4 shadow">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
            <h1 class="text-2xl md:text-3xl font-bold">المؤشر: رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي</h1>
            <span class="text-xl md:text-2xl font-semibold">لسنة 2024</span>
        </div>
    </div>

    <div class="container mx-auto py-10 px-4 space-y-10">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-gray-200 mb-8">
            <table class="min-w-full divide-y divide-gray-200 text-right" dir="rtl">
                <tbody class="divide-y divide-gray-200">
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            المعيار الرئيسي</th>
                        <td class="p-4 text-base text-gray-900">الوعظ والإرشاد (دائرة الزكاة)</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            المعيار الفرعي</th>
                        <td class="p-4 text-base text-gray-900">(دائرة الزكاة)</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            رمز المؤشر</th>
                        <td class="p-4 text-base text-gray-900">MARA 5</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            المؤشر</th>
                        <td class="p-4 text-base text-gray-900">رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            مالك المؤشر</th>
                        <td class="p-4 text-gray-900">
                            دائرة الزكاة
                        </td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            الجهات المساندة</th>
                        <td class="p-4 text-gray-900">
                            <div class="flex flex-wrap gap-6">
                                @php
                                    $governorates = [
                                        'ديوان عام الوزارة',
                                        'إدارة الأوقاف  الشؤون الدينة بمحافظة جنوب الباطنة',
                                        'إدارة الأوقاف  الشؤون الدينة بمحافظة شمال الباطنة',
                                        'إدارة الأوقاف  الشؤون الدينة بمحافظة الداخلية',
                                        'إدارة الأوقاف  الشؤون الدينة بمحافظة الظاهرة',
                                        'إدارة الأوقاف  الشؤون الدينة الوسطى',
                                        'إدارة الأوقاف  الشؤون بمحافظة ظفار',
                                    ];
                                @endphp
                                <div class="flex flex-wrap gap-6">
                                    @foreach ($governorates as $gov)
                                        <div class="mr-2 text-sm md:text-base">{{ $gov }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            وصف المؤشر</th>
                        <td class="p-4 text-base text-gray-900">مؤشر يقيس زيادة مبلغ إيرادات الزكاة</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            أداة القياس</th>
                        <td class="p-4 text-base text-gray-900">البيانات المتوفرة في برنامج الزكاة والحسابات المصرفية
                            تقارير لجان الزكاة.</td>
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
                        <td class="p-4 text-base text-gray-900">موجبة</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            شرح قطبية القياس</th>
                        <td class="p-4 text-base text-gray-900">موجبة حيث يرتفع المؤشر بارتفاع إيرادات الزكاة.</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            وحدة القياس</th>
                        <td class="p-4 text-base text-gray-900">رقم</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            معادلة القياس</th>
                        <td class="p-4 text-base text-gray-900">رقم</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            تاريخ الرصد الأول</th>
                        <td class="p-4 text-base text-gray-900">يناير</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            معادلة احتساب خط الأساس للربع الأول في السنة الأولى من التطبيق</th>
                        <td class="p-4 text-base text-gray-900">خط الأساس (العوائد في العام السابق) * نسبة المستهدف
                            للعام الحالي + قيمة العام السابق</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            خط الأساس بعد التطبيق</th>
                        <td class="p-4 text-base text-gray-900">1.5% (80,000,000)</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            اسئلة الاستبيان (سؤال للتحقق)</th>
                        <td class="p-4 text-base text-gray-900">-</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            مبادرات ومشاريع مقترحة</th>
                        <td class="p-4 text-base text-gray-900">رفع الوعي المجتمعي بالزكاة، رفع مستوى فاعلية لجان
                            الزكاة.</td>
                    </tr>
                    <tr class="transition duration-150 ease-in-out hover:bg-gray-50">
                        <th scope="row"
                            class="w-1/4 bg-gray-100 p-4 text-sm font-semibold text-gray-700 border-l border-gray-200 align-top">
                            المؤشرات الفرعية</th>
                        <td class="p-4 text-gray-900">
                            <div class="space-y-3">
                                <p class="text-gray-500 text-sm">لا توجد مؤشرات فرعية مضافة.</p>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>


        <!-- أداء المحافظات -->
        <section>
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-right" dir="rtl">أداء المحافظات (إدخال بيانات)
            </h2>
            <div class="text-xl text-red-800 font-bold">المستهدف:80,000,000</div>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full border text-right border-gray-300" dir="rtl">
                    <thead class="bg-black text-white text-sm font-semibold">
                        <tr>
                            <th class="p-3 border">المحافظة</th>
                            <th class="p-3 border">المستهدف للمحافظة</th>
                            <th class="p-3 border">المحقق الربع الأول</th>
                            <th class="p-3 border">المحقق الربع الثاني</th>
                            <th class="p-3 border">المحقق الربع الثالث</th>
                            <th class="p-3 border">المحقق الربع الرابع</th>
                            <th class="p-3 border">نسبة تحقيق المستهدف</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 border bg-[#1e3d4f] text-white font-semibold">مسقط</td>
                            <td class="p-3 border text-gray-800">40,000,000</td>
                            <td class="p-3 border">
                                <div>المستهدف: 200000</div>
                                <div> المحقق 220000</div>
                                بنسبة 110 %
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 4000</div>
                                <input type="text" value="20"
                                    class="w-full p-2 border rounded-md text-right text-gray-800" dir="rtl">
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 6000</div>
                                <input type="text" disabled
                                    class="bg-gray-200 w-full p-2 border rounded-md text-right text-gray-800"
                                    dir="rtl">
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 8000</div>
                                <input type="text" disabled
                                    class="bg-gray-200 w-full p-2 border rounded-md text-right text-gray-800"
                                    dir="rtl">
                            </td>
                            <td class="p-3 border font-semibold text-green-600">100٪</td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 border bg-[#1e3d4f] text-white font-semibold">ظفار</td>
                            <td class="p-3 border text-gray-800">20,000,000</td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                المحقق 120
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                <input type="text" value="20"
                                    class="w-full p-2 border rounded-md text-right text-gray-800" dir="rtl">
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                <input type="text" disabled
                                    class="bg-gray-200 w-full p-2 border rounded-md text-right text-gray-800"
                                    dir="rtl">
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                <input type="text" disabled
                                    class="bg-gray-200 w-full p-2 border rounded-md text-right text-gray-800"
                                    dir="rtl">
                            </td>
                            <td class="p-3 border font-semibold text-green-600">100٪</td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 border bg-[#1e3d4f] text-white font-semibold">شمال الشرقية</td>
                            <td class="p-3 border text-gray-800">10,000,000</td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                المحقق 120
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                <input type="text" value="20"
                                    class="w-full p-2 border rounded-md text-right text-gray-800" dir="rtl">
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                <input type="text" disabled
                                    class="bg-gray-200 w-full p-2 border rounded-md text-right text-gray-800"
                                    dir="rtl">
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                <input type="text" disabled
                                    class="bg-gray-200 w-full p-2 border rounded-md text-right text-gray-800"
                                    dir="rtl">
                            </td>
                            <td class="p-3 border font-semibold text-green-600">100٪</td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 border bg-[#1e3d4f] text-white font-semibold">جنوب الشرقية</td>
                            <td class="p-3 border text-gray-800">10,000,000</td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                المحقق 120
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                <input type="text" value="20"
                                    class="w-full p-2 border rounded-md text-right text-gray-800" dir="rtl">
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                <input type="text" disabled
                                    class="bg-gray-200 w-full p-2 border rounded-md text-right text-gray-800"
                                    dir="rtl">
                            </td>
                            <td class="p-3 border">
                                <div>المستهدف: 100</div>
                                <input type="text" disabled
                                    class="bg-gray-200 w-full p-2 border rounded-md text-right text-gray-800"
                                    dir="rtl">
                            </td>
                            <td class="p-3 border font-semibold text-green-600">100٪</td>
                        </tr>

                        <tr>
                            <td colspan="7" class="p-4 border text-center">
                                <button type="submit"
                                    class="px-8 py-3 bg-blue-600 text-white text-lg font-medium rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-4 focus:ring-blue-300">
                                    إرسال البيانات
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </section>

    </div>

</x-app-layout>
