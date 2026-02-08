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

        .option-selected {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%) !important;
            border-color: #3b82f6 !important;
        }
    </style>
</head>

<body class="bg-[#0f172a] min-h-screen relative overflow-scroll text-white">
    <!-- Background Effects -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div
            class="absolute -top-1/2 -right-1/2 w-full h-full bg-gradient-to-b from-purple-600/30 to-transparent rounded-full blur-3xl transform rotate-12 animate-pulse">
        </div>
        <div class="absolute -bottom-1/2 -left-1/2 w-full h-full bg-gradient-to-t from-blue-600/30 to-transparent rounded-full blur-3xl transform -rotate-12 animate-pulse"
            style="animation-delay: 2s"></div>
    </div>

    <div class="relative max-w-md mx-auto min-h-screen flex flex-col p-4" x-data="competitionPlay()" x-init="init()">

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
                <span class="text-xs text-gray-300 font-medium">السؤال</span>
                <span
                    class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500"
                    x-text="(currentQuestionIndex + 1) + '/' + questions.length"></span>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-6 relative h-2 bg-white/10 rounded-full overflow-hidden">
            <div class="absolute top-0 right-0 h-full bg-gradient-to-l from-green-400 via-yellow-400 to-blue-500 transition-all duration-500 ease-out"
                :style="`width: ${((currentQuestionIndex + 1) / questions.length) * 100}%`"></div>
        </div>

        <!-- Question Card -->
        <div x-show="!isSubmitting && !isFinished" class="flex-1 flex flex-col">
            <div class="glass-card text-gray-800 rounded-3xl p-6 mb-6 text-center transform transition-all">
                <div class="mb-4 text-blue-600 font-bold tracking-wider text-xs uppercase">
                    السؤال <span x-text="currentQuestionIndex + 1"></span>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold leading-relaxed mb-4" x-text="currentQuestion?.question_text">
                </h2>
            </div>

            <!-- Options Grid -->
            <div class="grid grid-cols-1 gap-3 pb-8">
                <template x-for="(option, index) in currentQuestion?.options" :key="option.id">
                    <button @click="selectOption(option.id)"
                        :class="selectedOptionId === option.id ? 'option-selected' : 'bg-white/10 hover:bg-white/20'"
                        class="group relative overflow-hidden backdrop-blur-md border border-white/10 p-5 rounded-2xl text-right transition-all duration-200 transform hover:scale-[1.02] active:scale-95 shadow-lg">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-sm font-bold group-hover:bg-white/20 transition-colors">
                                <span x-text="['أ', 'ب', 'ج', 'د'][index]"></span>
                            </div>
                            <span class="text-lg font-medium text-white" x-text="option.option_text"></span>
                        </div>
                    </button>
                </template>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex gap-4 mt-auto pb-8">
                <button x-show="currentQuestionIndex > 0" @click="previousQuestion()"
                    class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-2xl font-bold transition-all">
                    السابق
                </button>
                <button x-show="currentQuestionIndex < questions.length - 1" @click="nextQuestion()"
                    :disabled="!selectedOptionId"
                    :class="selectedOptionId ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-500 cursor-not-allowed'"
                    class="flex-1 text-white px-6 py-3 rounded-2xl font-bold transition-all">
                    التالي
                </button>
                <button x-show="currentQuestionIndex === questions.length - 1" @click="submitAllAnswers()"
                    :disabled="!selectedOptionId || isSubmitting"
                    :class="selectedOptionId ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-500 cursor-not-allowed'"
                    class="flex-1 text-white px-6 py-3 rounded-2xl font-bold transition-all">
                    <span x-show="!isSubmitting">إرسال الإجابات</span>
                    <span x-show="isSubmitting" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        جاري الإرسال...
                    </span>
                </button>
            </div>
        </div>

        <!-- Submitting State -->
        <div x-show="isSubmitting" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="flex-1 flex flex-col items-center justify-center text-center pb-20">
            <div class="relative mb-8">
                <div class="absolute inset-0 bg-blue-500 blur-2xl opacity-20 animate-pulse rounded-full"></div>
                <div
                    class="w-24 h-24 bg-gradient-to-tr from-blue-600 to-purple-600 rounded-full flex items-center justify-center shadow-2xl relative z-10 animate-bounce">
                    <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-black mb-3">جاري إرسال الإجابات...</h2>
            <p class="text-blue-200 text-lg">يرجى الانتظار</p>
        </div>

        <!-- Finished State -->
        <div x-show="isFinished" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            class="flex-1 flex flex-col items-center justify-center text-center pb-10">

            <div class="glass-card rounded-3xl p-8 w-full max-w-sm mb-8">
                <div
                    class="w-20 h-20 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg shadow-orange-500/30 animate-bounce">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-black text-gray-900 mb-2">تم الإرسال!</h2>
                <p class="text-gray-500 mb-6" x-text="getFeedbackMessage()"></p>

                <div class="bg-gray-50 rounded-2xl p-6 mb-4">
                    <span class="block text-gray-400 text-sm mb-2 uppercase tracking-wide">النتيجة النهائية</span>
                    <span class="block text-5xl font-black text-gray-900" x-text="finalScore"></span>
                </div>

                <div class="flex gap-4 text-sm">
                    <div class="flex-1 bg-green-100 rounded-xl p-3">
                        <span class="block text-green-600 font-bold" x-text="correctCount"></span>
                        <span class="text-green-500">صحيح</span>
                    </div>
                    <div class="flex-1 bg-red-100 rounded-xl p-3">
                        <span class="block text-red-600 font-bold" x-text="incorrectCount"></span>
                        <span class="text-red-500">خطأ</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('participant.competition.finished', $competition) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-2xl font-bold transition-all">
               موافق
            </a>
        </div>

    </div>

    <script>
        function competitionPlay() {
            return {
                questions: @json($questions),
                currentQuestionIndex: 0,
                answers: {}, // { question_id: option_id }
                selectedOptionId: null,
                isSubmitting: false,
                isFinished: false,
                finalScore: 0,
                correctCount: 0,
                incorrectCount: 0,
                competitionId: {{ $competition->id }},

                init() {
                    // Restore previously selected answer if exists
                    this.restoreSelectedOption();
                },

                get currentQuestion() {
                    return this.questions[this.currentQuestionIndex];
                },

                selectOption(optionId) {
                    this.selectedOptionId = optionId;
                    // Store answer
                    this.answers[this.currentQuestion.id] = optionId;
                },

                restoreSelectedOption() {
                    const questionId = this.currentQuestion?.id;
                    if (questionId && this.answers[questionId]) {
                        this.selectedOptionId = this.answers[questionId];
                    } else {
                        this.selectedOptionId = null;
                    }
                },

                nextQuestion() {
                    if (this.currentQuestionIndex < this.questions.length - 1) {
                        this.currentQuestionIndex++;
                        this.restoreSelectedOption();
                    }
                },

                previousQuestion() {
                    if (this.currentQuestionIndex > 0) {
                        this.currentQuestionIndex--;
                        this.restoreSelectedOption();
                    }
                },

                getFeedbackMessage() {
                    const percentage = (this.correctCount / this.questions.length) * 100;
                    if (percentage === 100) return 'ممتاز! إجابات صحيحة بالكامل!';
                    if (percentage >= 80) return 'أداء رائع!';
                    if (percentage >= 60) return 'أداء جيد!';
                    if (percentage >= 40) return 'بداية جيدة!';
                    return 'حظاً أوفر في المرة القادمة!';
                },

                async submitAllAnswers() {
                    // Haptic feedback if supported
                    if (navigator.vibrate) navigator.vibrate(50);

                    this.isSubmitting = true;

                    // Convert answers object to array
                    const answersArray = Object.entries(this.answers).map(([questionId, optionId]) => ({
                        question_id: parseInt(questionId),
                        option_id: optionId
                    }));

                    try {
                        const response = await fetch(`/compete/answers/${this.competitionId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ answers: answersArray })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.finalScore = data.final_score;
                            this.correctCount = data.correct_count;
                            this.incorrectCount = data.incorrect_count;
                            this.isFinished = true;
                        } else {
                            alert(data.error || 'حدث خطأ أثناء الإرسال');
                        }
                    } catch (error) {
                        console.error('Error submitting answers:', error);
                        alert('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }
        }
    </script>
</body>

</html>