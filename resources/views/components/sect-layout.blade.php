<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'لوحة التحكم' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800">

    <!-- Top Navbar -->
    <header class="bg-white shadow-md fixed top-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="text-xl font-bold">منصة متابعة المشاريع</div>
            <div>
                <span class="text-sm text-gray-600">مرحباً، {{ Auth::user()->name ?? 'ضيف' }}</span>
            </div>
        </div>
    </header>

    <!-- Sidebar + Content Layout -->
    <div class="flex pt-16">

        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md h-screen fixed right-0 top-16 overflow-y-auto">
            <nav class="p-4 space-y-2">
                <a href="/dashboard" class="block px-3 py-2 rounded hover:bg-gray-100">لوحة التحكم</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 mr-64 p-6">
            {{ $slot }}
        </main>
    </div>

</body>
</html>