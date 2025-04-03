<x-app-layout>


    <div class="bg-[#1e3d4f] text-2xl text-white font-bold p-4">
        <h1 class="container mx-auto"> إدارة المؤشرات</h1>
    </div>

    <div x-data="modalComponent">

        <div class="container py-8 mx-auto">
            <!-- Trigger Button -->
            <button @click="open = true" type="button"
                class="w-32 mb-1 text-white bg-green-700 hover:bg-green-800 focus:ring-2 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                + جديد
            </button>
            <a class="m-2 p-2 bg-white border border-black rounded block" href="{{ route('indicator.show') }}">رفع نمو
                إيرادات
                الزكاة من خلال الوعي المجتمعي</a>
            <a class="m-2 p-2 bg-white border border-black rounded block">عدد الجوامع والمساجد ومدارس القرآن الكريم التي
                تغطي
                مصاريف
                الخدمات الأساسية</a>
            <a class="m-2 p-2 bg-white border border-black rounded block">عدد متعلمي القرآن الكريم</a>
            <a class="m-2 p-2 bg-white border border-black rounded block">قيمة الأصول الوقفية الجديدة سنويًا</a>
            <a class="m-2 p-2 bg-white border border-black rounded block">زيادة نسبة المستفيدين من الأنشطة الدينية
                وخدمات
                الإفتاء
            </a>
            <a class="m-2 p-2 bg-white border border-black rounded block">رفع نسبة رضا المستفيدين عن الخدمات المقدمة من
                الوزارة</a>
            <a class="m-2 p-2 bg-white border border-black rounded block">عدد المستفيدين من برامج تعزيز قيم التسامح
                والتعايش
                والمؤتلف
                الإنساني (دولياً)</a>
            <a class="m-2 p-2 bg-white border border-black rounded block">عدد المستفيدين من برامج تعزيز الهوية الوطنية
                (محلياً)
            </a>
            <a class="m-2 p-2 bg-white border border-black rounded block">زيادة نسبة إيرادات أصول بيت المال</a>
            <a class="m-2 p-2 bg-white border border-black rounded block">زيادة نسبة إيرادات الأصول</a>

        </div>


        <!-- Modal -->
        <div x-show="open" @keydown.escape.window="open = false" @click.away="open = false"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4" style="display: none;">
            <div class="bg-white w-full max-w-4xl max-h-[90vh] p-6 rounded-lg shadow-lg overflow-y-auto" x-show="open"
                x-transition.scale>

                <table class="w-full border-collapse border border-gray-300">
                    <tbody>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">المعيار الرئيسي</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded"
                                    value="الوعظ والإرشاد (دائرة الزكاة)"></td>
                        </tr>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">المعيار الفرعي</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded" value="(دائرة الزكاة)"></td>
                        </tr>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">رمز المؤشر</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded" value="5"></td>
                        </tr>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">المؤشر</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded"
                                    value="رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي"></td>
                        </tr>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">مالك المؤشر</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded" value="دائرة الزكاة"></td>
                        </tr>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">وصف المؤشر</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded"
                                    value="مؤشر يقيس زيادة مبلغ إيرادات الزكاة"></td>
                        </tr>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">أداة القياس</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded"
                                    value="البيانات المتوفرة في برنامج الزكاة والحسابات البنكية تقارير لجان الزكاة.">
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">دورية القياس</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded" value="ربع سنوية"></td>
                        </tr>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">قطبية القياس</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded" value="موجبة"></td>
                        </tr>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">معادلة القياس</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded" value="رقم"></td>
                        </tr>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">خط الأساس بعد التطبيق</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded" value="1.5% (80000000)"></td>
                        </tr>
                        <tr>
                            <td class="bg-green-200 p-2 border border-gray-300">مبادرات ومشاريع مقترحة</td>
                            <td class="bg-white p-2 border border-gray-300"><input type="text"
                                    class="w-full p-1 border border-gray-300 rounded"
                                    value="رفع الوعي المجتمعي بالزكاة، رفع مستوى فاعلية لجان الزكاة."></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Close Button -->
                <div class="mt-4 flex justify-end">
                    <button @click="open = false" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        حفظ
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('modalComponent', () => ({
            open: false
        }));
    });
</script>
