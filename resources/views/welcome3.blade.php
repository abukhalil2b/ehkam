<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة فيا | حلول التخطيط والإحصاء للمؤسسات الحكومية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Kufi Arabic', sans-serif; }
        .gov-shadow { shadow-sm hover:shadow-xl transition-shadow duration-300; }
        .gradient-overlay { background: linear-gradient(0deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0) 100%); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    <nav class="bg-white border-b-4 border-indigo-700 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="p-2 bg-indigo-50 rounded-lg">
                    <svg class="w-10 h-10 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">منصة <span class="text-indigo-700">فيا</span> الحكومية</h1>
            </div>
            <div class="hidden lg:flex gap-8 font-semibold text-slate-600">
                <a href="#services" class="hover:text-indigo-700 transition">الخدمات الاستشارية</a>
                <a href="#courses" class="hover:text-indigo-700 transition">أكاديمية التدريب</a>
                <a href="#reports" class="hover:text-indigo-700 transition">مركز الدراسات</a>
            </div>
            <button class="bg-indigo-700 text-white px-6 py-2 rounded font-bold hover:bg-indigo-800 transition">بوابة الجهات الحكومية</button>
        </div>
    </nav>

    <header class="relative bg-slate-900 py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1577412647305-991150c7d163?q=80&w=1600" class="w-full h-full object-cover" alt="Modern Architecture">
        </div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="max-w-3xl">
                <span class="bg-indigo-600 text-white px-4 py-1 rounded text-sm mb-6 inline-block">رؤية عُمان 2040</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">
                    تمكين القيادات الحكومية <br>بأدوات التخطيط المبني على البيانات
                </h2>
                <p class="text-xl text-slate-300 mb-8">
                    نقدم خدمات استشارية وتدريبية متكاملة لدعم التحول الرقمي وصناعة السياسات الوطنية في سلطنة عمان ودول الخليج.
                </p>
                <div class="flex gap-4">
                    <button class="bg-white text-indigo-900 px-8 py-3 rounded font-bold hover:bg-indigo-50 transition">طلب عرض خدمات</button>
                    <button class="border border-slate-500 text-white px-8 py-3 rounded font-bold hover:bg-white/10 transition">دليل الدورات</button>
                </div>
            </div>
        </div>
    </header>

    <section id="services" class="py-20 max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold mb-4">خدمات المنصة للمؤسسات</h2>
            <div class="w-24 h-1 bg-indigo-700 mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-8 bg-white border-t-4 border-indigo-700 shadow-sm gov-shadow">
                <h3 class="text-xl font-bold mb-4">الاستشارات الاستراتيجية</h3>
                <p class="text-slate-600 mb-4 text-sm leading-relaxed">بناء ومراجعة الخطط الاستراتيجية والتشغيلية للوزارات والهيئات بما يتوافق مع التوجهات الوطنية.</p>
                <a href="#" class="text-indigo-700 font-bold text-sm">التفاصيل ←</a>
            </div>
            <div class="p-8 bg-white border-t-4 border-indigo-700 shadow-sm gov-shadow">
                <h3 class="text-xl font-bold mb-4">مرصد البيانات الوطني</h3>
                <p class="text-slate-600 mb-4 text-sm leading-relaxed">تحليل المؤشرات القطاعية وبناء لوحات تحكم (Dashboards) لمتابعة الأداء الحكومي اللحظي.</p>
                <a href="#" class="text-indigo-700 font-bold text-sm">التفاصيل ←</a>
            </div>
            <div class="p-8 bg-white border-t-4 border-indigo-700 shadow-sm gov-shadow">
                <h3 class="text-xl font-bold mb-4">دراسات استشراف المستقبل</h3>
                <p class="text-slate-600 mb-4 text-sm leading-relaxed">إعداد البحوث والدراسات الميدانية باستخدام تقنيات الذكاء الاصطناعي والإحصاء المتقدم.</p>
                <a href="#" class="text-indigo-700 font-bold text-sm">التفاصيل ←</a>
            </div>
        </div>
    </section>

    <section id="courses" class="py-20 bg-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-12 border-r-8 border-indigo-700 pr-6">
                <h2 class="text-3xl font-bold text-slate-800">أكاديمية فيا للتطوير الإداري</h2>
                <p class="text-slate-600 mt-2">برامج تدريبية متوافقة مع معايير الخدمة المدنية والتميز المؤسسي</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <div class="bg-white rounded-lg overflow-hidden shadow group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?q=80&w=600" class="w-full h-full object-cover group-hover:scale-105 transition" alt="Gulf Professional">
                        <div class="absolute inset-0 gradient-overlay flex items-end p-6">
                            <span class="text-white font-bold">التخطيط الاستراتيجي الحكومي</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-500 mb-4">منهجية صياغة الرؤية الوطنية في المؤسسات الخدمية.</p>
                        <button class="w-full py-2 bg-indigo-50 text-indigo-700 rounded font-bold hover:bg-indigo-700 hover:text-white transition">التسجيل للمؤسسات</button>
                    </div>
                </div>

                <div class="bg-white rounded-lg overflow-hidden shadow group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?q=80&w=600" class="w-full h-full object-cover group-hover:scale-105 transition" alt="Omani Professional">
                        <div class="absolute inset-0 gradient-overlay flex items-end p-6">
                            <span class="text-white font-bold">إدارة مؤشرات الأداء KPIs</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-500 mb-4">قياس الكفاءة المؤسسية وربطها بالحوافز والإنتاجية.</p>
                        <button class="w-full py-2 bg-indigo-50 text-indigo-700 rounded font-bold hover:bg-indigo-700 hover:text-white transition">التسجيل للمؤسسات</button>
                    </div>
                </div>

                <div class="bg-white rounded-lg overflow-hidden shadow group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=600" class="w-full h-full object-cover group-hover:scale-105 transition" alt="Gulf Business">
                        <div class="absolute inset-0 gradient-overlay flex items-end p-6">
                            <span class="text-white font-bold">الحوكمة والتميز المؤسسي</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-500 mb-4">تطبيق معايير التميز الحكومي والنزاهة الإدارية.</p>
                        <button class="w-full py-2 bg-indigo-50 text-indigo-700 rounded font-bold hover:bg-indigo-700 hover:text-white transition">التسجيل للمؤسسات</button>
                    </div>
                </div>

                <div class="bg-white rounded-lg overflow-hidden shadow group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1542744094-3a31f272c491?q=80&w=600" class="w-full h-full object-cover group-hover:scale-105 transition" alt="Statistics">
                        <div class="absolute inset-0 gradient-overlay flex items-end p-6">
                            <span class="text-white font-bold">الإحصاء لدعم القرار</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-500 mb-4">تحليل البيانات الديموغرافية والقطاعية لصناع القرار.</p>
                        <button class="w-full py-2 bg-indigo-50 text-indigo-700 rounded font-bold hover:bg-indigo-700 hover:text-white transition">التسجيل للمؤسسات</button>
                    </div>
                </div>

                <div class="bg-white rounded-lg overflow-hidden shadow group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=600" class="w-full h-full object-cover group-hover:scale-105 transition" alt="Team Work">
                        <div class="absolute inset-0 gradient-overlay flex items-end p-6">
                            <span class="text-white font-bold">القيادة الإبداعية في العمل</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-500 mb-4">تطوير مهارات الصف الثاني من القيادات الحكومية.</p>
                        <button class="w-full py-2 bg-indigo-50 text-indigo-700 rounded font-bold hover:bg-indigo-700 hover:text-white transition">التسجيل للمؤسسات</button>
                    </div>
                </div>

                <div class="bg-white rounded-lg overflow-hidden shadow group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?q=80&w=600" class="w-full h-full object-cover group-hover:scale-105 transition" alt="Digital Trans">
                        <div class="absolute inset-0 gradient-overlay flex items-end p-6">
                            <span class="text-white font-bold">إدارة مشاريع التحول الرقمي</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-500 mb-4">قيادة المشاريع التقنية والربط بين الجهات الحكومية.</p>
                        <button class="w-full py-2 bg-indigo-50 text-indigo-700 rounded font-bold hover:bg-indigo-700 hover:text-white transition">التسجيل للمؤسسات</button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-16 border-t-8 border-indigo-700">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
            <div>
                <h4 class="text-white font-bold text-xl mb-6">عن منصة فيا</h4>
                <p class="text-sm leading-relaxed">شريككم الموثوق في رحلة التميز المؤسسي وبناء القدرات الوطنية في سلطنة عمان. نقدم حلولاً تعتمد على الدقة الإحصائية والمنهجية العلمية.</p>
            </div>
            <div>
                <h4 class="text-white font-bold text-xl mb-6">روابط سريعة</h4>
                <ul class="text-sm space-y-3">
                    <li><a href="#" class="hover:text-indigo-400">نظام إدارة الجودة</a></li>
                    <li><a href="#" class="hover:text-indigo-400">مركز تحميل التقارير</a></li>
                    <li><a href="#" class="hover:text-indigo-400">طلب استشارة فنية</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold text-xl mb-6">تواصل معنا</h4>
                <p class="text-sm">مسقط، سلطنة عمان</p>
                <p class="text-sm mt-2">الهاتف: 8000-1234 (مجاني)</p>
                <p class="text-sm mt-2">البريد: gov@feya.om</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 mt-12 pt-8 border-t border-slate-800 text-center text-xs">
            © 2026 منصة فيا للحلول الحكومية المتكاملة - مسجلة في سلطنة عمان.
        </div>
    </footer>

</body>
</html>