<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>غرفة الانتظار - {{ $competition->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center p-4 text-right">
    <div class="max-w-lg w-full"
         x-data="waitingRoom({{ $competition->id }})"
         x-init="init()">
        
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-500"
             :class="show ? 'scale-100 opacity-100' : 'scale-95 opacity-0'">
            
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full translate-x-16 -translate-y-16 animate-pulse"></div>
                    <div class="absolute bottom-0 left-0 w-40 h-40 bg-white rounded-full -translate-x-20 translate-y-20 animate-pulse" style="animation-delay: 1s;"></div>
                </div>
                
                <div class="relative z-10">
                    <div class="inline-block p-5 bg-white/20 backdrop-blur-sm rounded-full mb-4 animate-bounce">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $competition->title }}</h1>
                    <p class="text-white/90 text-lg">استعد للمنافسة!</p>
                </div>
            </div>

            <div class="p-8">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full mb-4 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        أهلاً بك، <span class="text-indigo-600">{{ $participant->name }}</span>!
                    </h2>
                    <p class="text-gray-600">أنت الآن جاهز تماماً للبدء</p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 mb-6 border-2 border-indigo-100 text-center">
                    <div class="flex items-center justify-center mb-4">
                        <div class="flex space-x-reverse space-x-1">
                            <div class="w-3 h-3 bg-indigo-600 rounded-full animate-bounce"></div>
                            <div class="w-3 h-3 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                            <div class="w-3 h-3 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                        </div>
                    </div>
                    <p class="text-lg font-semibold text-gray-800 mb-2">
                        بانتظار المضيف لبدء المسابقة
                    </p>
                    <p class="text-sm text-gray-600">
                        ستبدأ المسابقة خلال لحظات...
                    </p>
                </div>

                <div class="bg-white border-2 border-gray-200 rounded-2xl p-6 mb-6 hover:border-indigo-300 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-reverse space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mr-4">
                                <p class="text-sm font-medium text-gray-600">المشاركون الحاضرون</p>
                                <p class="text-3xl font-bold text-gray-900" x-text="participantsCount"></p>
                            </div>
                        </div>
                        <div class="text-left">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full ml-2 animate-pulse"></span>
                                مباشر
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 text-center">
                        <div class="text-2xl mb-1">⚡</div>
                        <p class="text-xs font-semibold text-blue-900">سريع</p>
                        <p class="text-xs text-blue-700">30ث/للسؤال</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center">
                        <div class="text-2xl mb-1">🎯</div>
                        <p class="text-xs font-semibold text-green-900">دقة</p>
                        <p class="text-xs text-green-700">نقطة لكل سؤال</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 text-center">
                        <div class="text-2xl mb-1">🏆</div>
                        <p class="text-xs font-semibold text-purple-900">منافسة</p>
                        <p class="text-xs text-purple-700">أعلى النقاط</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-5 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 ml-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        طريقة اللعب
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center ml-2 text-xs font-bold">1</span>
                            <span>ستظهر الأسئلة تباعاً على شاشتك</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center ml-2 text-xs font-bold">2</span>
                            <span>لديك 30 ثانية للإجابة على كل سؤال</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center ml-2 text-xs font-bold">3</span>
                            <span>اختر الإجابة الصحيحة لكسب النقاط</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center ml-2 text-xs font-bold">4</span>
                            <span>ابقَ في هذه الصفحة - سيتم تحويلك تلقائياً</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-yellow-50 border-r-4 border-yellow-400 p-4 rounded-l-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 ml-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-yellow-800">تنبيه هام!</p>
                            <p class="text-xs text-yellow-700 mt-1">لا تغلق هذه الصفحة. سيتم دخولك تلقائياً بمجرد بدء المضيف للمسابقة.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <div class="flex items-center justify-center space-x-reverse space-x-2 text-sm text-gray-500">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full ml-2 animate-pulse"></div>
                        <span>متصل</span>
                    </div>
                    <span>•</span>
                    <span>التحديث التلقائي نشط</span>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <div class="inline-flex items-center space-x-reverse space-x-2 bg-white/20 backdrop-blur-sm rounded-full px-6 py-3 text-white">
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span class="text-sm font-medium">جاري البحث عن تحديثات...</span>
            </div>
        </div>
    </div>

    <script>
    function waitingRoom(competitionId) {
        return {
            participantsCount: {{ $competition->participants->count() }},
            show: false,

            init() {
                setTimeout(() => { this.show = true; }, 100);
                this.startPolling();
            },

            startPolling() {
                this.checkStatus();
                setInterval(() => this.checkStatus(), 2000);
            },

            async checkStatus() {
                try {
                    const response = await fetch(`/compete/live/${competitionId}`);
                    if (!response.ok) return;

                    const data = await response.json();
                    if (data.participants_count !== undefined) {
                        this.participantsCount = data.participants_count;
                    }
                    
                    if (data.status === 'started') {
                        this.show = false;
                        setTimeout(() => {
                            window.location.href = `/compete/play/${competitionId}`;
                        }, 300);
                    }
                    
                    if (data.status === 'finished') {
                        window.location.href = '/compete/finished';
                    }
                } catch (error) {
                    console.error('Error checking status:', error);
                }
            }
        }
    }
    </script>
</body>
</html>