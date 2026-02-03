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
        body {
            font-family: 'Tajawal', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        .animate-tilt {
            animation: tilt 10s infinite linear;
        }

        @keyframes tilt {

            0%,
            50%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(1deg);
            }

            75% {
                transform: rotate(-1deg);
            }
        }
    </style>
</head>

<body class="bg-[#0f172a] min-h-screen relative overflow-hidden text-white">
    <!-- Background Effects -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div
            class="absolute -top-1/2 -right-1/2 w-full h-full bg-gradient-to-b from-purple-600/30 to-transparent rounded-full blur-3xl transform rotate-12 animate-pulse">
        </div>
        <div class="absolute -bottom-1/2 -left-1/2 w-full h-full bg-gradient-to-t from-blue-600/30 to-transparent rounded-full blur-3xl transform -rotate-12 animate-pulse"
            style="animation-delay: 2s"></div>
    </div>

    <div class="relative max-w-md mx-auto min-h-screen flex flex-col p-4"
        x-data="competitionPlay({{ $competition->id }})" x-init="init()">

        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-8 pt-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-tr from-purple-500 to-blue-500 flex items-center justify-center text-lg font-bold shadow-lg ring-2 ring-white/20">
                    {{ substr($participant->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-sm font-medium text-gray-300">المتسابق</h1>
                    <p class="font-bold text-white leading-none">{{ $participant->name }}</p>
                </div>
            </div>
            <div class="glass px-4 py-2 rounded-2xl flex flex-col items-center">
                <span class="text-xs text-gray-300 font-medium">النقاط</span>
                <span
                    class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500"
                    x-text="score"></span>
            </div>
        </div>

        <!-- Waiting State -->
        <div x-show="!currentQuestion && !showResults" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="flex-1 flex flex-col items-center justify-center text-center pb-20">
            <div class="relative mb-8">
                <div class="absolute inset-0 bg-blue-500 blur-2xl opacity-20 animate-pulse rounded-full"></div>
                <div
                    class="w-24 h-24 bg-gradient-to-tr from-blue-600 to-purple-600 rounded-full flex items-center justify-center shadow-2xl relative z-10 animate-bounce">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-black mb-3">استعد!</h2>
            <p class="text-blue-200 text-lg">بانتظار السؤال القادم...</p>
            <div class="mt-8 flex gap-2">
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
            </div>
        </div>

        <!-- Question State -->
        <div x-show="currentQuestion && !hasAnswered" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0"
            class="flex-1 flex flex-col">

            <!-- Timer Bar -->
            <div class="mb-6 relative h-2 bg-white/10 rounded-full overflow-hidden">
                <div class="absolute top-0 right-0 h-full bg-gradient-to-l from-green-400 via-yellow-400 to-red-500 transition-all duration-1000 ease-linear shadow-[0_0_10px_rgba(255,255,255,0.5)]"
                    :style="`width: ${(timeRemaining / 30) * 100}%`"></div>
            </div>

            <!-- Question Card -->
            <div
                class="glass-card text-gray-800 rounded-3xl p-6 mb-6 text-center transform transition-all hover:scale-[1.01] duration-300">
                <div class="mb-4 text-blue-600 font-bold tracking-wider text-xs uppercase">جاري اللعب</div>
                <h2 class="text-2xl md:text-3xl font-bold leading-relaxed mb-4" x-text="currentQuestion?.question_text">
                </h2>

                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 bg-gray-100 rounded-full text-sm font-bold shadow-inner">
                    <span class="w-2 h-2 rounded-full animate-pulse"
                        :class="timeRemaining <= 10 ? 'bg-red-500' : 'bg-green-500'"></span>
                    <span :class="timeRemaining <= 10 ? 'text-red-600' : 'text-gray-600'"
                        x-text="timeRemaining + ' ثانية'"></span>
                </div>
            </div>

            <!-- Options Grid -->
            <div class="grid grid-cols-1 gap-3 pb-8">
                <template x-for="(option, index) in currentQuestion?.options" :key="option.id">
                    <button @click="submitAnswer(option.id)"
                        class="group relative overflow-hidden bg-white/10 hover:bg-white/20 active:bg-blue-600 backdrop-blur-md border border-white/10 p-5 rounded-2xl text-right transition-all duration-200 transform hover:scale-[1.02] active:scale-95 shadow-lg">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-600/0 via-blue-600/0 to-blue-600/0 group-hover:from-blue-600/10 group-hover:via-purple-600/10 group-hover:to-blue-600/10 transition-all duration-300">
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-sm font-bold group-hover:bg-white/20 transition-colors">
                                <span x-text="['أ', 'ب', 'ج', 'د'][index]"></span>
                            </div>
                            <span class="text-lg font-medium text-white group-hover:text-blue-100"
                                x-text="option.option_text"></span>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        <!-- Submitted/Waiting Result State -->
        <div x-show="currentQuestion && hasAnswered" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            class="flex-1 flex flex-col items-center justify-center text-center pb-20">
            <div
                class="w-24 h-24 bg-green-500/20 rounded-full flex items-center justify-center mb-6 ring-4 ring-green-500/30 animate-[pulse_3s_infinite]">
                <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold mb-2">تم استلام إجابتك</h2>
            <p class="text-gray-400">ننتظر باقي المتسابقين...</p>
        </div>

        <!-- Round Results State -->
        <div x-show="showResults" class="flex-1 flex flex-col items-center justify-center text-center pb-10">

            <div
                class="glass-card rounded-3xl p-8 w-full max-w-sm mb-8 transform transition-all duration-500 hover:rotate-1">
                <div class="text-gray-500 font-medium mb-4 uppercase tracking-wider text-xs">الإجابة الصحيحة</div>
                <div class="text-xl font-bold text-gray-900 mb-6" x-text="correctAnswer?.option_text"></div>

                <div class="h-px bg-gray-200 w-full mb-6"></div>

                <div class="text-gray-500 font-medium mb-2">رصيدك الحالي</div>
                <div class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-blue-600 to-purple-600"
                    x-text="score"></div>
            </div>
        </div>

        <!-- Finished Modal -->
        <div x-show="status === 'finished'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="glass-card rounded-3xl p-8 max-w-sm w-full text-center relative overflow-hidden">
                <!-- Fireworks decorations could go here -->
                <div class="relative z-10">
                    <div
                        class="w-20 h-20 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg shadow-orange-500/30 animate-bounce">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 mb-2">انتهت اللعبة!</h2>
                    <p class="text-gray-500 mb-8" x-text="getFeedbackMessage()"></p>

                    <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                        <span class="block text-gray-400 text-sm mb-2 uppercase tracking-wide">النتيجة النهائية</span>
                        <span class="block text-5xl font-black text-gray-900" x-text="score"></span>
                    </div>

                    <p class="text-sm text-gray-400">شكراً لمشاركتك معنا</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Background Logic -->
    <script>
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
                    // Check if already finished
                    this.fetchLiveData();
                },

                getFeedbackMessage() {
                    if (this.score == 0) return 'حظاً أوفر في المرة القادمة!';
                    if (this.score < 50) return 'بداية جيدة، القادم أفضل!';
                    if (this.score < 100) return 'أداء جيد جداً!';
                    return 'أداء استثنائي ومميز!';
                },

                startPolling() {
                    setInterval(() => this.fetchLiveData(), 2500);
                },

                async fetchLiveData() {
                    try {
                        const response = await fetch(`/compete/live/${competitionId}?t=${new Date().getTime()}`);
                        const data = await response.json();

                        this.status = data.status;
                        this.currentQuestion = data.current_question;
                        this.hasAnswered = data.has_answered;
                        this.timeRemaining = data.time_remaining;
                        // Only update score if changed to animate (optional enhancement idea)
                        this.score = data.score;
                        this.showResults = data.show_results;
                        this.correctAnswer = data.correct_answer;
                    } catch (error) {
                        console.error('Error fetching live data:', error);
                    }
                },

                async submitAnswer(optionId) {
                    // Haptic feedback if supported
                    if (navigator.vibrate) navigator.vibrate(50);

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