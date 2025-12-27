<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $competition->title }} - العب</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen p-4">
    <div class="max-w-2xl mx-auto"
         x-data="competitionPlay({{ $competition->id }})"
         x-init="init()">
        
        <div class="bg-white rounded-t-2xl shadow-lg p-6">
            <div class="flex justify-between items-center">
                <div class="text-right">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $competition->title }}</h1>
                    <p class="text-gray-600">{{ $participant->name }}</p>
                </div>
                <div class="text-left">
                    <div class="text-3xl font-bold text-blue-600" x-text="score"></div>
                    <div class="text-sm text-gray-600">النقاط</div>
                </div>
            </div>
        </div>

        <div x-show="!currentQuestion && !showResults" 
             class="bg-white shadow-lg p-12 text-center">
            <div class="inline-block p-4 bg-blue-100 rounded-full mb-4">
                <svg class="w-12 h-12 text-blue-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">استعد!</h2>
            <p class="text-gray-600">بانتظار السؤال القادم...</p>
        </div>

        <div x-show="currentQuestion && !hasAnswered" class="bg-white shadow-lg">
            <div class="relative h-3 bg-gray-200">
                <div class="absolute top-0 right-0 h-full bg-gradient-to-l from-green-500 via-yellow-500 to-red-500 transition-all duration-1000"
                     :style="`width: ${(timeRemaining / 30) * 100}%`"></div>
            </div>

            <div class="p-6 text-right">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-xl font-bold text-gray-900 flex-1" x-text="currentQuestion?.question_text"></h2>
                    <div class="mr-4">
                        <div class="text-3xl font-bold" 
                             :class="timeRemaining <= 5 ? 'text-red-600 animate-pulse' : 'text-blue-600'"
                             x-text="timeRemaining"></div>
                    </div>
                </div>

                <div class="space-y-3">
                    <template x-for="option in currentQuestion?.options" :key="option.id">
                        <button @click="submitAnswer(option.id)"
                                class="w-full text-right p-4 border-2 border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all duration-200 transform hover:scale-102">
                            <span class="text-lg text-gray-800" x-text="option.option_text"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <div x-show="currentQuestion && hasAnswered" class="bg-white shadow-lg p-12 text-center">
            <div class="inline-block p-4 bg-green-100 rounded-full mb-4">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">تم إرسال الإجابة!</h2>
            <p class="text-gray-600">بانتظار النتائج...</p>
        </div>

        <div x-show="showResults" class="bg-white shadow-lg p-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">الإجابة الصحيحة</h2>
                <div class="inline-block px-6 py-3 bg-green-100 rounded-lg">
                    <p class="text-lg font-semibold text-green-800" x-text="correctAnswer?.option_text"></p>
                </div>
            </div>
            
            <div class="text-center">
                <p class="text-gray-600 mb-2">نقاطك الحالية</p>
                <div class="text-4xl font-bold text-blue-600" x-text="score"></div>
            </div>
        </div>

        <div class="bg-white rounded-b-2xl shadow-lg p-4 mt-0 border-t border-gray-100">
            <div class="flex justify-center space-x-reverse space-x-2 text-sm text-gray-500">
                <span>ابقَ في هذه الصفحة</span>
                <span>•</span>
                <span>تحديث تلقائي</span>
            </div>
        </div>

        <div x-show="status === 'finished'" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md text-center">
                <div class="inline-block p-4 bg-blue-100 rounded-full mb-4">
                    <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">انتهت المسابقة!</h2>
                <div class="mb-6">
                    <p class="text-gray-600 mb-2">نقاطك النهائية</p>
                    <div class="text-5xl font-bold text-blue-600" x-text="score"></div>
                </div>
                <p class="text-gray-600">شكراً لمشاركتك معنا!</p>
            </div>
        </div>
    </div>

    <script>
    /* JavaScript logic remains unchanged as it is backend-dependent */
    function competitionPlay(competitionId) {
        return {
            status: 'started',
            currentQuestion: null,
            hasAnswered: false,
            timeRemaining: 0,
            score: {{ $participant->score }},
            showResults: false,
            correctAnswer: null,

            init() {
                this.startPolling();
            },

            startPolling() {
                this.fetchLiveData();
                setInterval(() => this.fetchLiveData(), 1000);
            },

            async fetchLiveData() {
                try {
                    const response = await fetch(`/compete/live/${competitionId}`);
                    const data = await response.json();
                    
                    this.status = data.status;
                    this.currentQuestion = data.current_question;
                    this.hasAnswered = data.has_answered;
                    this.timeRemaining = data.time_remaining;
                    this.score = data.score;
                    this.showResults = data.show_results;
                    this.correctAnswer = data.correct_answer;
                } catch (error) {
                    console.error('Error fetching live data:', error);
                }
            },

            async submitAnswer(optionId) {
                try {
                    const response = await fetch(`/compete/answer/${competitionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            question_id: this.currentQuestion.id,
                            option_id: optionId
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        this.hasAnswered = true;
                    }
                } catch (error) {
                    console.error('Error submitting answer:', error);
                }
            }
        }
    }
    </script>
</body>
</html>