<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الانضمام للمسابقة - {{ $competition->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $competition->title }}</h1>
            <p class="text-gray-600">أدخل اسمك للانضمام إلى المسابقة</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-right">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('participant.competition.register', $competition->join_code) }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="text-right">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسمك الكريم</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       required 
                       autofocus
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-right"
                       placeholder="أدخل اسمك هنا">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200 transform hover:scale-105 shadow-md">
                دخول المسابقة
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            <p>رمز المسابقة: <span class="font-mono font-bold text-gray-700">{{ $competition->join_code }}</span></p>
        </div>
    </div>
</body>
</html>