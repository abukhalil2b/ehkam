<x-app-layout>

    <!-- Header -->
    <div class="bg-[#1e3d4f] text-white py-6 px-4 shadow">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
            <h1 class="text-2xl md:text-3xl font-bold">المؤشر: رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي</h1>
            <span class="text-xl md:text-2xl font-semibold">لسنة 2024</span>
        </div>
    </div>

    <div class="container mx-auto py-10 px-4 space-y-10">

        <!-- قياس دورية -->
        <div class="p-4 rounded-xl bg-orange-100 border border-orange-300 text-orange-800 shadow-sm space-y-1">
            <div class="flex justify-between items-center">
                <span class="font-semibold text-lg">دورية القياس:</span>
                <span class="text-lg">ربعي</span>
            </div>
            <p class="text-sm text-orange-600">* يمكن تغيير دورية القياس من صفحة إدارة المؤشرات.</p>
        </div>

        <!-- الأداء الربعي -->
        <section>
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-right" dir="rtl">الأداء الربعي</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full border border-gray-200 text-right" dir="rtl">
                    <thead class="bg-gray-100 text-gray-700 text-sm font-semibold">
                        <tr>
                            <th class="p-3 border">الربع</th>
                            <th class="p-3 border">المستهدف</th>
                            <th class="p-3 border">المحقق</th>
                            <th class="p-3 border">نسبة تحقيق المستهدف</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-800">
                        @php
                            $quarters = [
                                ['name' => 'الأول', 'target' => 10, 'achieved' => 230, 'rate' => '100٪'],
                                ['name' => 'الثاني', 'target' => 10, 'achieved' => 140, 'rate' => '200٪'],
                                ['name' => 'الثالث', 'target' => 10, 'achieved' => '', 'rate' => '--'],
                                ['name' => 'الرابع', 'target' => 10, 'achieved' => '', 'rate' => '--']
                            ];
                        @endphp
                        @foreach($quarters as $q)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 border">{{ $q['name'] }}</td>
                                <td class="p-3 border">{{ $q['target'] }}</td>
                                <td class="p-3 border">{{ $q['achieved'] }}</td>
                                <td class="p-3 border font-semibold {{ is_numeric($q['achieved']) ? 'text-green-600' : 'text-gray-500' }}">{{ $q['rate'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- مالك المؤشر -->
        <div class="p-4 rounded-xl bg-orange-100 border border-orange-300 text-orange-800 shadow-sm">
            <div class="flex justify-between items-center">
                <span class="font-semibold text-lg">مالك المؤشر:</span>
                <span class="text-lg">دائرة الزكاة</span>
            </div>
        </div>

        <!-- أداء المحافظات -->
        <section>
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-right" dir="rtl">أداء المحافظات (إدخال بيانات)</h2>
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
                        @php
                            $governorates = [
                                'مسقط', 'ظفار', 'شمال الشرقية', 'جنوب الشرقية',
                                'شمال الباطنة', 'جنوب الباطنة', 'الداخلية', 'البريمي'
                            ];
                        @endphp
                        @foreach($governorates as $gov)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 border bg-[#1e3d4f] text-white font-semibold">{{ $gov }}</td>
                                <td class="p-3 border text-gray-800">10</td>
                                @for($i = 1; $i <= 4; $i++)
                                    <td class="p-3 border">
                                        <input type="text" value="{{ $i <= 2 ? 20 : '' }}" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 text-right text-gray-800" dir="rtl">
                                    </td>
                                @endfor
                                <td class="p-3 border font-semibold text-green-600">100٪</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7" class="p-4 border text-center">
                                <button type="submit" class="px-8 py-3 bg-blue-600 text-white text-lg font-medium rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-4 focus:ring-blue-300">
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
