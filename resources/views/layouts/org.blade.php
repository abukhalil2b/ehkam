<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'وزارة الأوقاف والشؤون الدينية' }} - Ministry of Awqaf</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts - Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Tajawal:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9f4',
                            100: '#dbf0e3',
                            200: '#b9e1c9',
                            300: '#8bcba7',
                            400: '#5aaf82',
                            500: '#389465',
                            600: '#2d8659',
                            700: '#1a5f3f',
                            800: '#184c35',
                            900: '#143f2c',
                        },
                        accent: {
                            DEFAULT: '#c5a572',
                            light: '#d4b88a',
                            dark: '#a58a5d',
                        }
                    },
                    fontFamily: {
                        'arabic': ['Tajawal', 'Cairo', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Tajawal', 'Cairo', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen">
    <!-- Main Header -->
    <header class="bg-gradient-to-l from-primary-700 to-primary-600 text-white shadow-lg no-print">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-primary-700 text-2xl">
                        <i class="fas fa-mosque"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold font-arabic">وزارة الأوقاف والشؤون الدينية</h1>
                        <p class="text-sm text-primary-100">سلطنة عُمان - Ministry of Awqaf and Religious Affairs</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="window.print()"
                        class="px-4 py-2 bg-white text-primary-700 rounded-lg hover:bg-primary-50 transition-colors flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        <span>طباعة</span>
                    </button>
                    <a href="{{ route('org_unit.index') }}"
                        class="px-4 py-2 bg-white text-primary-700 rounded-lg hover:bg-primary-50 transition-colors flex items-center gap-2">
                        <i class="fas fa-home"></i>
                        <span>الرئيسية</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 shadow-sm no-print">
        <div class="container mx-auto px-4">
            <ul class="flex gap-1">
                <li>
                    <a href="{{ route('org_unit.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-3 text-gray-700 hover:text-primary-700 hover:bg-primary-50 border-b-2 transition-colors {{ request()->routeIs('org_unit.index') ? 'border-primary-700 text-primary-700 bg-primary-50' : 'border-transparent' }}">
                        <i class="fas fa-sitemap"></i>
                        <span>الهيكل التنظيمي</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin_users.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-3 text-gray-700 hover:text-primary-700 hover:bg-primary-50 border-b-2 transition-colors {{ request()->routeIs('admin_users.*') ? 'border-primary-700 text-primary-700 bg-primary-50' : 'border-transparent' }}">
                        <i class="fas fa-users"></i>
                        <span>الموظفون</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('positions.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-3 text-gray-700 hover:text-primary-700 hover:bg-primary-50 border-b-2 transition-colors {{ request()->routeIs('positions.*') ? 'border-primary-700 text-primary-700 bg-primary-50' : 'border-transparent' }}">
                        <i class="fas fa-briefcase"></i>
                        <span>الوظائف</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="inline-flex items-center gap-2 px-4 py-3 text-gray-700 hover:text-primary-700 hover:bg-primary-50 border-b-2 border-transparent transition-colors">
                        <i class="fas fa-building"></i>
                        <span>الوحدات التنظيمية</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="inline-flex items-center gap-2 px-4 py-3 text-gray-700 hover:text-primary-700 hover:bg-primary-50 border-b-2 border-transparent transition-colors">
                        <i class="fas fa-chart-bar"></i>
                        <span>التقارير</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container mx-auto px-4 py-4 no-print">
        <nav class="bg-white rounded-lg shadow-sm px-4 py-3">
            <ol class="flex items-center gap-2 text-sm">
                <li>
                    <a href="{{ route('org_unit.index') }}"
                        class="text-primary-700 hover:text-primary-800 flex items-center gap-1">
                        <i class="fas fa-home text-xs"></i>
                        <span>الرئيسية</span>
                    </a>
                </li>
                @if(isset($breadcrumbs))
                    @foreach($breadcrumbs as $breadcrumb)
                        <li class="flex items-center gap-2">
                            <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
                            @if($loop->last)
                                <span class="text-gray-600">{{ $breadcrumb['title'] }}</span>
                            @else
                                <a href="{{ $breadcrumb['url'] }}" class="text-primary-700 hover:text-primary-800">
                                    {{ $breadcrumb['title'] }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                @else
                    <li class="flex items-center gap-2">
                        <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
                        <span class="text-gray-600">{{ $title ?? '' }}</span>
                    </li>
                @endif
            </ol>
        </nav>
    </div>

    <!-- Page Title -->
    @if(isset($title))
        <div class="container mx-auto px-4 pb-4 no-print">
            <div class="bg-white rounded-lg shadow-sm p-6 border-r-4 border-primary-700">
                <h1 class="text-3xl font-bold text-primary-700 font-arabic">{{ $title }}</h1>
                @if(isset($subtitle))
                    <p class="text-gray-600 mt-2">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mx-auto px-4 pb-4 no-print">
            <div
                class="bg-green-50 border-r-4 border-green-500 text-green-800 p-4 rounded-lg shadow-sm flex items-start gap-3">
                <i class="fas fa-check-circle text-green-500 text-xl mt-0.5"></i>
                <div class="flex-1">
                    <p class="font-semibold">نجح</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 pb-4 no-print">
            <div class="bg-red-50 border-r-4 border-red-500 text-red-800 p-4 rounded-lg shadow-sm flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5"></i>
                <div class="flex-1">
                    <p class="font-semibold">خطأ</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container mx-auto px-4 pb-4 no-print">
            <div class="bg-red-50 border-r-4 border-red-500 text-red-800 p-4 rounded-lg shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-semibold mb-2">يرجى تصحيح الأخطاء التالية:</p>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="container mx-auto px-4 pb-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-primary-700 text-white mt-16 py-8 no-print">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h5 class="text-lg font-bold mb-3 font-arabic">وزارة الأوقاف والشؤون الدينية</h5>
                    <p class="text-primary-100 mb-2">سلطنة عُمان</p>
                    <p class="text-sm text-primary-200">جميع الحقوق محفوظة © {{ date('Y') }}</p>
                </div>
                <div class="text-left">
                    <h5 class="text-lg font-bold mb-3">روابط سريعة</h5>
                    <ul class="space-y-2 text-primary-100">
                        <li>
                            <a href="#" class="hover:text-white transition-colors inline-flex items-center gap-2">
                                <i class="fas fa-angle-left text-xs"></i>
                                <span>من نحن</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-white transition-colors inline-flex items-center gap-2">
                                <i class="fas fa-angle-left text-xs"></i>
                                <span>اتصل بنا</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-white transition-colors inline-flex items-center gap-2">
                                <i class="fas fa-angle-left text-xs"></i>
                                <span>الدعم الفني</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>