<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="منصة متابعة مشاريع الوزارة - نظام متكامل لمتابعة مؤشرات الأداء">

    <title>منصة متابعة مشاريع الوزارة | نظام إدارة المشاريع والمؤشرات</title>

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #2c5e8a;
            --secondary-color: #4a8c5a;
            --accent-color: #d4af37;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .bg-primary {
            background-color: var(--primary-color);
        }

        .text-primary {
            color: var(--primary-color);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-right: 4px solid var(--accent-color);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .kpi-badge {
            background-color: var(--accent-color);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            display: inline-block;
            margin-left: 0.5rem;
        }


        h2 {
            color: #2980b9;
            background-color: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
            margin-top: 30px;
        }

        .section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px;
            border-radius: 5px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .stat-value {
            font-size: 1.4em;
            color: #e74c3c;
            margin-bottom: 5px;
        }

        .stat-details {
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .highlight {
            background-color: #fffde7;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .percentage-bar {
            height: 20px;
            background-color: #ecf0f1;
            border-radius: 10px;
            margin-top: 10px;
            overflow: hidden;
        }

        .percentage-fill {
            height: 100%;
            background-color: #3498db;
            width: 0%;
            transition: width 1s;
        }

        .note {
            font-style: italic;
            color: #7f8c8d;
            font-size: 0.9em;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="mx-1 flex items-center">
                    <div
                        class="p-1 w-full h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold text-xl">
                        منصة متابعة المشاريع</div>
                </div>



                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4 space-x-reverse">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-gray-700 hover:text-primary transition font-medium">لوحة التحكم</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-700 hover:text-primary transition font-medium">دخول</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="outline-none mobile-menu-button">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>


    </nav>

    <!-- Hero Section -->
    <section class="hero-section py-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">نظام متكامل لمتابعة مشاريع ومؤشرات الوزارة</h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">حوكمة فاعلة، مؤشرات قياس أداء ومتابعة دقيقة لتحقيق الأهداف
                الاستراتيجية</p>
            <div class="flex justify-center space-x-4 space-x-reverse">
                <a href="login"
                    class="bg-white text-primary px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-300">الدخول
                    للنظام</a>

            </div>
        </div>
    </section>

    <!-- KPIs Section -->
    <section id="kpis" class="py-20 bg-gray-50">
        <h2 class="text-3xl font-bold text-center mb-16 text-primary">الإحصائيات العامة للوزارة</h2>
        <div class="container mx-auto px-6">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- KPI 1 -->
                <div class="feature-card bg-white p-8 rounded-lg">
                    <div class="text-primary mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3"> الزكاة</h3>
                    <p class="text-gray-600">رفع نمو إيرادات الزكاة من خلال الوعي المجتمعي وزيادة المساهمات</p>
                </div>

                <!-- KPI 2 -->
                <div class="feature-card bg-white p-8 rounded-lg">
                    <div class="text-primary mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">المساجد والجوامع </h3>
                    <p class="text-gray-600">عدد الجوامع والمساجد ومدارس القرآن الكريم التي تغطي مصاريف الخدمات الأساسية
                    </p>
                </div>

                <!-- KPI 3 -->
                <a href="{{ route('statistic.quran') }}">
                    <div class="feature-card bg-white p-8 rounded-lg">
                        <div class="text-primary mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">تعليم القرآن</h3>
                        <p class="text-gray-600">عدد متعلمي القرآن الكريم وزيادة أعداد المستفيدين من برامج التعليم</p>
                    </div>
                </a>

                <!-- KPI 4 -->
                <div class="feature-card bg-white p-8 rounded-lg">
                    <div class="text-primary mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">الأصول والإيرادات</h3>
                    <p class="text-gray-600">قيمة الأصول الوقفية الجديدة سنويًا وتطوير منظومة الوقف</p>
                </div>

                <!-- KPI 5 -->
                <div class="feature-card bg-white p-8 rounded-lg">
                    <div class="text-primary mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">الأنشطة الدينية</h3>
                    <p class="text-gray-600">زيادة نسبة المستفيدين من الأنشطة الدينية وخدمات الإفتاء</p>
                </div>

                <div class="feature-card bg-white p-8 rounded-lg">
                    <div class="text-primary mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">الموارد البشرية</h3>
                    <p class="text-gray-600">
                        قطاع تمكين الموارد البشرية
                    </p>
                </div>

                <!-- KPI 6 -->
                <div class="feature-card bg-white p-8 rounded-lg">
                    <div class="text-primary mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">التحول الرقمي ورضا المستفيدين</h3>
                    <p class="text-gray-600">رفع نسبة رضا المستفيدين عن الخدمات المقدمة من الوزارة</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="py-20">
        <div class="container mx-auto px-6">

            <div class="section">
                <h2>قطاع الأوقاف</h2>

            </div>

            <div class="section">
                <h2>قطاع الإفتاء</h2>

            </div>

            <div class="section">
                <h2>قطاع الوعظ والإرشاد</h2>

            </div>

            <div class="section">
                <h2>قطاع التحول الرقمي والتقنية</h2>

            </div>

            <div class="section">
                <h2>قطاع الشؤون الفلكية</h2>

            </div>

            <div class="section">
                <h2>قطاع شؤون الحج والعمرة</h2>

            </div>

            <div class="section">
                <h2>قطاع إدارة أموال الأيتام والقُصَّر</h2>

            </div>

            <div class="section">
                <h2>قطاع تمكين الموارد البشرية</h2>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div
                            class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold text-xl">
                            و</div>
                        <span class="mr-3 text-xl font-bold">منصة متابعة المشاريع</span>
                    </div>
                    <p class="text-gray-400">نظام متكامل لمتابعة مؤشرات الأداء ومشاريع الوزارة بأسلوب عصري وآمن</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">روابط سريعة</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">الرئيسية</a></li>
                        <li><a href="#kpis" class="text-gray-400 hover:text-white">المؤشرات</a></li>
                        <li><a href="#projects" class="text-gray-400 hover:text-white">المشاريع</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">التقارير</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">الدعم</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">الأسئلة الشائعة</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">الدليل الإرشادي</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">الشروط والأحكام</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">سياسة الخصوصية</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">اتصل بنا</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>98626878889+</span>
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>info@platform.gov.om</span>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>© 2023 منصة متابعة مشاريع الوزارة. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        const btn = document.querySelector(".mobile-menu-button");
        const menu = document.querySelector(".mobile-menu");

        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });
    </script>
</body>

</html>