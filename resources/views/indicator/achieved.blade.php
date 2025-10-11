<x-app-layout>
   <x-slot name="header">
       المحقق
    </x-slot>
    <!-- Header -->
    <div class="bg-[#1e3d4f] text-white py-6 px-4 shadow">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
            <h1 class="text-2xl md:text-3xl font-bold">المؤشر: رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي</h1>
            <span class="text-xl md:text-2xl font-semibold">لسنة 2024</span>
        </div>
    </div>

    <div class="container mx-auto py-10 px-4 space-y-10">


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
