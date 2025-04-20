<x-app-layout>
    <div class="bg-[#1e3d4f] text-2xl text-white font-bold p-4">
        <h1 class="container mx-auto"> إدارة المؤشرات</h1>
    </div>

    <div class="container py-8 mx-auto">
      

        <table class="w-full border border-gray-400 border-collapse"cellpadding="5" cellspacing="0">
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">المعيار الرئيسي</td>
                <td class="bg-white p-2 border border-gray-400">الوعظ والإرشاد (دائرة الزكاة)</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">المعيار الفرعي</td>
                <td class="bg-white p-2 border border-gray-400">(دائرة الزكاة)</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">رمز المؤشر</td>
                <td class="bg-white p-2 border border-gray-400">5</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">المؤشر</td>
                <td class="bg-white p-2 border border-gray-400">رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">مالك المؤشر</td>
                <td class="bg-white p-2 border border-gray-400">
                    <label>
                        <input type="checkbox">
                        دائرة الزكاة
                    </label>
                    <label>
                        <input type="checkbox">
                        مسقط
                    </label>
                    <label>
                        <input type="checkbox">
                        صلالة
                    </label>
                </td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">وصف المؤشر</td>
                <td class="bg-white p-2 border border-gray-400">مؤشر يقيس زيادة مبلغ إيرادات الزكاة</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">أداة القياس</td>
                <td class="bg-white p-2 border border-gray-400">البيانات المتوفرة في برنامج الزكاة والحسابات البنكية
                    تقارير لجان
                    الزكاة.</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">نوع الدليل الداعم</td>
                <td class="bg-white p-2 border border-gray-400">كشوف الحسابات البنكية، تقرير من نظام الزكاة، تقارير
                    وإحصائيات لجان
                    الزكاة.</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">دورية القياس</td>
                <td class="bg-white p-2 border border-gray-400">
                    <select name="" id="">
                        <option value="">شهري</option>
                        <option value="">ربع سنوية</option>
                        <option value=""> نصف سنوي</option>
                        <option value=""> سنوي</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">قطبية القياس</td>
                <td class="bg-white p-2 border border-gray-400">موجبة</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">شرح قطبية القياس</td>
                <td class="bg-white p-2 border border-gray-400">موجبة حيث يرتفع المؤشر بارتفاع إيرادات الزكاة.</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">وحدة القياس</td>
                <td class="bg-white p-2 border border-gray-400">رقم</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">معادلة القياس</td>
                <td class="bg-white p-2 border border-gray-400">رقم</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">تاريخ الرصد الأول</td>
                <td class="bg-white p-2 border border-gray-400">يناير</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">معادلة احتساب خط الأساس للربع الأول في السنة الأولى
                    من التطبيق
                </td>
                <td class="bg-white p-2 border border-gray-400">خط الأساس (العوائد في العام السابق) * نسبة المستهدف
                    للعام الحالي +
                    قيمة العام السابق</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">خط الأساس بعد التطبيق</td>
                <td class="bg-white p-2 border border-gray-400">1.5% (80000000)</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">اسئلة الاستبيان (سؤال للتحقق)</td>
                <td class="bg-white p-2 border border-gray-400">-</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">مبادرات ومشاريع مقترحة</td>
                <td class="bg-white p-2 border border-gray-400">رفع الوعي المجتمعي بالزكاة، رفع مستوى فاعلية لجان
                    الزكاة.</td>
            </tr>
            <tr>
                <td class="bg-green-200 p-2 border border-gray-400">  المؤشر الفرعي   
                   
                </td>
                <td class="bg-white p-2 border border-gray-400">
                    متوسط قيمة المبالغ الموزعة للمستحقين

                    <button @click="open = true" type="button"
                    class="w-32 mb-1 text-white bg-green-700 hover:bg-green-800 focus:ring-2 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                     + إضافة
                </button>
                </td>
            </tr>
            <tr>
                <td class="bg-[#1e3d4f] p-2 border border-gray-400  text-white">صلاحيات تغذية المؤشر</td>
                <td class="bg-white p-2 border border-gray-400">
                    <div>المستخدم1</div>
                    <div> المستخدم2</div>
                    <div> المستخدم3</div>
                </td>
            </tr>
        </table>

      
        <div>دورية قياس المستهدف : ربع سنوي</div>
        <table class="w-full border border-gray-400 border-collapse text-right" dir="rtl">
            <tr>
                <td class="p-2 border border-gray-300">الربع</td>
                <td class="p-2 border border-gray-300">المستهدف</td>
                <td class="p-2 border border-gray-300">المحقق</td>
                <td class="p-2 border border-gray-300">نسبة تحقيق المستهدف</td>
            </tr>
            <tr>
                <td class="p-2 border border-gray-300">الاول</td>
                <td class="p-2 border border-gray-300">
                    <input type="text" value="10">
                </td>
                <td class="p-2 border border-gray-300">10</td>
                <td class="p-2 border border-gray-300"> 100٪</td>
            </tr>
            <tr>
                <td class="p-2 border border-gray-300">الثاني</td>
                <td class="p-2 border border-gray-300">
                    <input type="text" value="10">
                </td>
                <td class="p-2 border border-gray-300">20</td>
                <td class="p-2 border border-gray-300"> 200٪</td>
            </tr>
        </table>
    </div>

</x-app-layout>
