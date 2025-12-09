<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة إحصائية لتعليم القرآن في سلطنة عمان</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }

        .active-province {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }

        .active-year {
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.5);
        }
    </style>
</head>

<body class="bg-gray-50 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-green-800 mb-2">لوحة إحصائية لتعليم القرآن الكريم</h1>
            <div class="w-40 h-1.5 bg-green-600 mx-auto mt-2 flex justify-between">
                <a class="bg-green-800 h-6 w-6 text-white" href="{{ route('statistic.quran',2) }}">2</a>
                <a class="bg-green-800 h-6 w-6 text-white" href="{{ route('statistic.quran',3) }}">3</a>
                <a class="bg-green-800 h-6 w-6 text-white" href="{{ route('statistic.quran',4) }}">4</a>
                <a class="bg-green-800 h-6 w-6 text-white" href="{{ route('statistic.quran',5) }}">5</a>
                <a class="bg-green-800 h-6 w-6 text-white" href="{{ route('statistic.quran',6) }}">6</a>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white p-6 rounded-xl shadow-md mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Year Selection -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">السنة الإحصائية</h3>
                    <div class="flex flex-wrap gap-2">
                        <button
                            class="active-year px-4 py-2 bg-amber-100 text-amber-800 rounded-lg font-medium">2024</button>
                        <button class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg font-medium">2023</button>
                        <button class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg font-medium">2022</button>
                    </div>
                </div>

                <!-- Province Selection -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">اختر المحافظة</h3>
                    <div class="relative">
                        <select
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">جميع المحافظات</option>
                            <option>مسقط</option>
                            <option>ظفار</option>
                            <option>مسندم</option>
                            <option>البريمي</option>
                            <option>الداخلية</option>
                            <option>شمال الباطنة</option>
                            <option>جنوب الباطنة</option>
                            <option>جنوب الشرقية</option>
                            <option>شمال الشرقية</option>
                            <option>الظاهرة</option>
                            <option>الوسطى</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Traditional Education -->
            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-blue-600">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-blue-800">التعليم التقليدي (الكتاتيب)</h2>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">المدارس
                        والمساجد</span>
                </div>

                <!-- Key Metrics -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-blue-700">320</p>
                        <p class="text-gray-700">عدد المدارس</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-blue-700">640</p>
                        <p class="text-gray-700">عدد الحلقات</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-blue-700">650</p>
                        <p class="text-gray-700">عدد المعلمين</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-blue-700">15:1</p>
                        <p class="text-gray-700">نسبة الطلاب للمعلم</p>
                    </div>
                </div>

                <!-- Students Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-3">الطلاب المسجلين</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>الذكور</span>
                                <span class="font-bold">1,200</span>
                            </div>
                            <div class="flex justify-between">
                                <span>الإناث</span>
                                <span class="font-bold">2,800</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                                <span class="font-bold">المجموع</span>
                                <span class="font-bold text-blue-700">4,000</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-3">الطلاب المتخرجين</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>الذكور</span>
                                <span class="font-bold">1,100</span>
                            </div>
                            <div class="flex justify-between">
                                <span>الإناث</span>
                                <span class="font-bold">2,600</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                                <span class="font-bold">المجموع</span>
                                <span class="font-bold text-blue-700">3,700</span>
                            </div>
                        </div>
                    </div>
                </div>
                <h1> مجموع الطلاب التقليدي وعن بعد</h1>
                <!-- Supervisors -->
                <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold text-gray-800 mb-2">المشرفين</h4>
                    <p class="text-xl font-bold text-blue-700">120</p>
                    <p class="text-sm text-gray-600">(مشرف لكل 5 حلقات تقريباً)</p>
                </div>
            </div>

            <!-- Distance Education -->
            <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-green-600">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-green-800">التعليم عن بعد</h2>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">البرنامج
                        الإلكتروني</span>
                </div>

                <!-- Key Metrics -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-green-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-green-700">240</p>
                        <p class="text-gray-700">عدد الحصص</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-green-700">160</p>
                        <p class="text-gray-700">عدد المعلمين</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-green-700">12:1</p>
                        <p class="text-gray-700">نسبة الطلاب للمعلم</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-green-700">4</p>
                        <p class="text-gray-700">الفصول الدراسية</p>
                    </div>
                </div>

                <!-- Students Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-3">الطلاب المسجلين</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>الذكور</span>
                                <span class="font-bold">800</span>
                            </div>
                            <div class="flex justify-between">
                                <span>الإناث</span>
                                <span class="font-bold">1,200</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                                <span class="font-bold">المجموع</span>
                                <span class="font-bold text-green-700">2,000</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-3">الطلاب المتخرجين</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>الذكور</span>
                                <span class="font-bold">750</span>
                            </div>
                            <div class="flex justify-between">
                                <span>الإناث</span>
                                <span class="font-bold">1,100</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                                <span class="font-bold">المجموع</span>
                                <span class="font-bold text-green-700">1,850</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Programs Distribution -->
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-1">مسار التلاوة</h4>
                        <p class="text-lg font-bold text-green-700">80 حصة</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-1">مسار الحفظ</h4>
                        <p class="text-lg font-bold text-green-700">160 حصة</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Enrollment Trend Chart -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-4">تطور أعداد الطلاب المسجلين</h3>
                <div class="bg-gray-100 h-64 rounded-lg flex items-center justify-center">
                    <p class="text-gray-500">[رسم بياني يظهر تطور الأعداد حسب السنة والمحافظة]</p>
                </div>
            </div>

            <!-- Gender Distribution Chart -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-4">توزيع الطلاب حسب النوع</h3>
                <div class="bg-gray-100 h-64 rounded-lg flex items-center justify-center">
                    <p class="text-gray-500">[رسم بياني دائري يظهر نسبة الذكور والإناث]</p>
                </div>
            </div>
        </div>

        <!-- Quarterly Stats -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">الإحصاءات الفصلية</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 font-semibold">الفصل الدراسي</th>
                            <th class="p-3 font-semibold">الملتحقين</th>
                            <th class="p-3 font-semibold">المتخرجين</th>
                            <th class="p-3 font-semibold">نسبة التخرج</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="p-3">الأول</td>
                            <td class="p-3 font-bold">1,200</td>
                            <td class="p-3 font-bold">1,100</td>
                            <td class="p-3 font-bold text-green-600">92%</td>
                        </tr>
                        <tr>
                            <td class="p-3">الشتوي</td>
                            <td class="p-3 font-bold">1,050</td>
                            <td class="p-3 font-bold">950</td>
                            <td class="p-3 font-bold text-green-600">90%</td>
                        </tr>
                        <tr>
                            <td class="p-3">الثاني</td>
                            <td class="p-3 font-bold">1,300</td>
                            <td class="p-3 font-bold">1,150</td>
                            <td class="p-3 font-bold text-green-600">88%</td>
                        </tr>
                        <tr>
                            <td class="p-3">الصيفي</td>
                            <td class="p-3 font-bold">900</td>
                            <td class="p-3 font-bold">800</td>
                            <td class="p-3 font-bold text-green-600">89%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-500 py-4 border-t border-gray-200">
            <p>وزارة الأوقاف والشؤون الدينية - سلطنة عمان | آخر تحديث: يونيو 2024</p>
        </div>
    </div>
</body>

</html>
