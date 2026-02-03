<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>التسجيل مغلق - {{ $workshop->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
        }

        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center py-8 px-4">
    <div class="max-w-md mx-auto text-center">
        {{-- Icon --}}
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white shadow-2xl mb-6">
            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        {{-- Card --}}
        <div class="glass rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">
                    التسجيل مغلق حالياً
                </h1>
                
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <h2 class="font-bold text-gray-700 mb-2">{{ $workshop->title }}</h2>
                    <p class="text-gray-500 text-sm">
                        {{ $day->label ?? 'يوم' }} - {{ $day->day_date->format('Y-m-d') }}
                    </p>
                </div>

                <p class="text-gray-600 mb-6">
                    عذراً، تسجيل الحضور لهذا اليوم غير متاح حالياً.
                    <br>
                    يرجى التواصل مع منظمي الورشة.
                </p>

                <div class="flex items-center justify-center gap-2 text-amber-600 bg-amber-50 rounded-xl p-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">سيتم تفعيل الرابط عند بدء التسجيل</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <p class="text-white/70 text-sm mt-6">
            وزارة الأوقاف والشؤون الدينية
        </p>
    </div>
</body>

</html>
