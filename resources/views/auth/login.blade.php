<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico">

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-neo bg-[#062426] text-white">
    <div class="flex items-center justify-center h-full">
        <a href="/" class="absolute top-4 left-4 text-white">
            <i class="material-icons text-3xl">arrow_back</i>
        </a>

        <form method="POST" action="{{ route('login') }}" class="w-3/4 sm:w-1/3 bg-opacity-20 rounded-lg p-6">
            @csrf
            <h1 class="font-frutiger text-3xl font-bold mb-8 text-center">تسجيل الدخول</h1>

            <div class="mb-4">
                <input type="email" name="email" placeholder="البريد الإلكتروني"
                    class="shadow placeholder-gray-800 text-gray-700 rounded bg-white p-5 w-full focus:bg-white outline-none"
                    value="" oninput="this.value = this.value.replace(/\s+/g, '')">
            </div>

            <div class="mb-4 relative" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'" name="password" placeholder="كلمة المرور"
                    class="shadow placeholder-gray-800 text-gray-700 rounded bg-white p-5 w-full focus:bg-white outline-none"
                    value="" oninput="this.value = this.value.replace(/\s+/g, '')">

                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 left-3 flex items-center text-gray-700">
                    <i class="material-icons">
                        <span x-show="!show">visibility</span>
                        <span x-show="show">visibility_off</span>
                    </i>
                </button>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-500 rounded">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <button type="submit"
                class="w-full bg-[#00d6af] rounded py-4 font-bold text-white hover:bg-[#00c0a0] transition">
                دخول
            </button>
        </form>
    </div>
</body>

</html>
