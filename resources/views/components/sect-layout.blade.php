<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'لوحة التحكم' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-800">

    <div class="bg-blue-800 text-white py-3 shadow-lg">
        <div class="max-w-4xl mx-auto px-2 text-center">
            <h1 class="text-2xl font-extrabold">
                المديرية العامة للتخطيط والدراسات
            </h1>
            <h2 class="text-xl font-extrabold"> وزارة الأوقاف والشؤون الدينية</h2>
            <div>
                <span class="text-sm py-4">مرحباً، {{ Auth::user()->name ?? 'ضيف' }}</span>
            </div>
        </div>
    </div>

    <!-- Enhanced Header -->
    <header class="bg-gradient-to-r from-[#1e3d4f] to-[#2d5b73] text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Title with Home Icon -->
            <h1 class="text-2xl font-bold flex items-center text-white">
                <svg class="w-6 h-6 mr-2 rtl:ml-2 rtl:mr-0 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <a href="/dashboard">الصفحة الرئيسية</a>
            </h1>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                @csrf
                <button
                    class="flex items-center gap-2 text-sm bg-white text-[#1e3d4f] hover:bg-gray-100 px-3 py-1.5 rounded-lg transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                    </svg>
                    تسجيل الخروج
                </button>
            </form>
        </div>
    </header>
    <main>
        {{ $slot }}
    </main>
</body>

</html>
