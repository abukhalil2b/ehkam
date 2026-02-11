<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تم التسجيل مسبقاً - {{ $workshop->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
        {{-- Success Icon --}}
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white shadow-2xl mb-6">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        {{-- Card --}}
        <div class="glass rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-6 text-white">
                <h1 class="text-2xl font-bold">تم تسجيل حضورك!</h1>
                <p class="text-green-100 mt-1">شكراً لحضورك هذه الورشة</p>
            </div>

            <div class="p-6">
                {{-- Workshop Info --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <h2 class="font-bold text-gray-700 mb-2">{{ $workshop->title }}</h2>
                    <p class="text-gray-500 text-sm">
                        {{ $day->label ?? 'يوم' }} - {{ $day->day_date->format('Y-m-d') }}
                    </p>
                </div>

                {{-- Registration Details --}}
                <div class="space-y-3 text-right">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-bold text-gray-800">{{ $checkin->participant->attendee_name ?? '-' }}</span>
                        <span class="text-gray-500 text-sm">الاسم</span>
                    </div>
                    
                    @if($checkin->participant->job_title)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">{{ $checkin->participant->job_title }}</span>
                        <span class="text-gray-500 text-sm">الوظيفة</span>
                    </div>
                    @endif
                    
                    @if($checkin->participant->department)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">{{ $checkin->participant->department }}</span>
                        <span class="text-gray-500 text-sm">القسم</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="font-medium text-gray-700">{{ $checkin->checkin_time->format('H:i') }}</span>
                        <span class="text-gray-500 text-sm">وقت التسجيل</span>
                    </div>
                </div>

                {{-- Info Message --}}
                <div class="mt-6 flex items-center justify-center gap-2 text-blue-600 bg-blue-50 rounded-xl p-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
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
