<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>انتهت المسابقة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0; }
            10% { opacity: 0.6; }
            90% { opacity: 0.6; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }
        .animate-float { animation: float 8s ease-in-out infinite; }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-500 via-pink-500 to-red-500 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-500"
             :class="show ? 'scale-100 opacity-100' : 'scale-95 opacity-0'">
            
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-8 text-center">
                <div class="inline-block p-6 bg-white rounded-full shadow-lg mb-4 animate-bounce">
                    <svg class="w-20 h-20 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">انتهت المسابقة!</h1>
                <p class="text-xl text-white opacity-90">شكراً جزيلاً لمشاركتك معنا</p>
            </div>

            <div class="p-8 text-center">
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-blue-100 rounded-full mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">لقد انتهينا!</h2>
                    <p class="text-lg text-gray-600 max-w-md mx-auto leading-relaxed">
                        لقد وصلت هذه المسابقة إلى نهايتها. نأمل أنك استمتعت بهذه التجربة واكتسبت معلومات جديدة ومفيدة!
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4">
                        <div class="flex items-center justify-center mb-2">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">وقت ممتع</p>
                        <p class="text-2xl font-bold text-gray-900">✓</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4">
                        <div class="flex items-center justify-center mb-2">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">معرفة مكتسبة</p>
                        <p class="text-2xl font-bold text-gray-900">✓</p>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4">
                        <div class="flex items-center justify-center mb-2">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">تفاعل وتسلية</p>
                        <p class="text-2xl font-bold text-gray-900">✓</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <p class="text-gray-700 leading-relaxed">
                        <span class="font-bold text-gray-900">تم تسجيل نتائجك بنجاح!</span><br>
                        يمتلك منظم المسابقة جميع النتائج الآن وسيقوم بمشاركتها قريباً.
                    </p>
                </div>

                <div class="space-y-3">
                    <button onclick="window.location.reload()" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 rounded-xl transition duration-200 transform hover:scale-105 shadow-lg">
                        تحديث الصفحة
                    </button>
                    
                    <button onclick="window.close()" 
                            class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 rounded-xl transition duration-200">
                        إغلاق النافذة
                    </button>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        يمكنك إغلاق هذه الصفحة بأمان الآن
                    </p>
                </div>
            </div>

            <div class="h-2 bg-gradient-to-r from-yellow-400 via-pink-500 to-purple-500"></div>
        </div>

        <div class="fixed inset-0 pointer-events-none overflow-hidden" x-show="show">
            <div class="absolute animate-float" style="right: 10%; top: 20%; animation-delay: 0s;">
                <div class="w-3 h-3 bg-yellow-400 rounded-full opacity-60"></div>
            </div>
            <div class="absolute animate-float" style="right: 20%; top: 60%; animation-delay: 0.5s;">
                <div class="w-2 h-2 bg-pink-400 rounded-full opacity-60"></div>
            </div>
            <div class="absolute animate-float" style="right: 80%; top: 30%; animation-delay: 1s;">
                <div class="w-4 h-4 bg-blue-400 rounded-full opacity-60"></div>
            </div>
            <div class="absolute animate-float" style="right: 70%; top: 70%; animation-delay: 1.5s;">
                <div class="w-3 h-3 bg-purple-400 rounded-full opacity-60"></div>
            </div>
            <div class="absolute animate-float" style="right: 30%; top: 40%; animation-delay: 2s;">
                <div class="w-2 h-2 bg-red-400 rounded-full opacity-60"></div>
            </div>
        </div>
    </div>
</body>
</html>