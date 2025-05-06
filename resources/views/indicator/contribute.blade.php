<x-app-layout>

    <div class="bg-[#1e3d4f] text-2xl text-white font-bold p-4">
        <h1 class="container mx-auto"> حصر المؤشرات سنة 2024</h1>
    </div>

    <div class="container py-8 mx-auto">
        <table class="w-full border border-gray-400 border-collapse text-right" dir="rtl">
            <thead class="bg-black text-white">
                <tr class="border border-gray-300">
                    <th class="p-3 font-bold border border-gray-300">التصنيف</th>
                    <th class="p-3 font-bold border border-gray-300">المؤشر</th>
                    <th class="p-3 font-bold border border-gray-300">المجموع</th>
                    <th class="p-3 font-bold border border-gray-300">التفاصيل</th>
                    <th class="p-3 font-bold border border-gray-300">إدارة</th>
                </tr>
            </thead>
            <tbody>
                <!-- رئيسي rows -->
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">قيمة الأصول الوقفية الجديدة</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">بيانات المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">عدد الأصول الوقفية الجديدة</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">عدد الجوامع والمساجد ومدارس القرآن الكريم التي تغطي مصاريف الخدمات الأساسية الصيانة والتنظيف</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">عدد المساجد ومدارس القرآن التي تم إفتتاحها هذا العام</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">مبلغ العوائد من الأصول الوقفية</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">مبلغ العوائد من بيت المال</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">مبلغ العوائد من أصول أموال الأيتام والقصّر</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">نسبة اشغال أصول الأوقاف من اجمالي الأصول</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">نسبة اشغال أصول بيت المال من اجمالي الأصول</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">نسبة اشغال أصول أموال الأيتام والقصر من اجمالي الأصول</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">عدد متعلمي القرآن الكريم</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">عدد المستفيدين من الأنشطة الدينية وخدمات الإفتاء</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">عدد المستفيدين من الأنشطة الدينية وخدمات الإفتاء الإرشاد النسوي</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">عدد الأنشطة المتعلقة بالظواهر الاجتماعية والمؤثرات العقلية</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">عدد البحوث المتعلقة بالظواهر الاجتماعية والمؤثرات العقلية</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">عدد المستفيدين من خدمات الإفتاء</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">عدد المستفيدين من خدمات التعريف بالإسلام</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">عدد المستفيدين من برامج تعزيز الهوية الوطنية (محلياً)</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">عدد المستفيدين من برامج تعزيز قيم التسامح والتعايش والمؤتلف الإنساني (دولياً)</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">مبلغ إيرادات الزكاة</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">متوسط المبالغ الموزعة لكل فرد مستحق من أموال الزكاة هذا العام</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">عدد برامج الوعي المجتمعي الخاصة بالزكاة</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">تحسين الخدمات المرقمنة من إجمالي الخدمات</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">عدد الخدمات المقدمة للجمهور</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-[#1e3d4f] text-white">
                    <td class="p-3 border border-gray-300">رئيسي</td>
                    <td class="p-3 border border-gray-300">نسبة رضا المستفيدين من الخدمات المقدمة</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
                <tr class="bg-green-100">
                    <td class="p-3 border border-gray-300">فرعي</td>
                    <td class="p-3 border border-gray-300">عدد ملاحظات جهاز الرقابة المالية والإدارية للدولة</td>
                    <td class="p-3 border border-gray-300"></td>
                    <td class="p-3 border border-gray-300"> 
                        <a href="{{ route('indicator.contribute.details') }}">التفاصيل</a>
                    </td>
                    <td class="p-3 border border-gray-300">
                        <div class="flex gap-2 text-xs">
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المؤشر</a>
                            <a class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors" href="{{ route('indicator.contribute.details') }}">إدارة المشاريع</a>
                        </div> 
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
   
</x-app-layout>


<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('modalComponent', () => ({
            open: false
        }));
    });
</script>
