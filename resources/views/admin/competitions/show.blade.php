<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" 
     dir="rtl"
     x-data="competitionControl({{ $competition->id }}, '{{ $competition->status }}')">
    
    <div class="mb-6 text-right">
        <h1 class="text-3xl font-bold text-gray-900">{{ $competition->title }}</h1>
        <p class="text-gray-600 mt-1">الرمز: <span class="font-mono font-bold">{{ $competition->join_code }}</span></p>
    </div>



    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4 text-right">رمز QR للانضمام</h3>
            <div class="flex justify-center">
                {!! $qrCode !!}
            </div>
            <p class="text-center mt-2 text-sm text-gray-600">{{ $competition->join_url }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow text-right">
            <h3 class="text-lg font-semibold mb-4">لوحة التحكم</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium">الحالة:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold"
                          :class="{
                              'bg-gray-100 text-gray-800': status === 'closed',
                              'bg-green-100 text-green-800': status === 'started',
                              'bg-blue-100 text-blue-800': status === 'finished'
                          }"
                          x-text="status === 'closed' ? 'مغلقة' : (status === 'started' ? 'بدأت' : 'منتهية')"></span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium">عدد المشاركين:</span>
                    <span class="text-lg font-bold" x-text="participantsCount"></span>
                </div>

                @if($competition->status === 'closed')
                    <form action="{{ route('admin.competitions.start', $competition) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                            بدء المسابقة
                        </button>
                    </form>
                @elseif($competition->status === 'started')
                    <button @click="closeCurrentQuestion()" 
                            x-show="currentQuestion"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium mb-2">
                        إغلاق السؤال الحالي
                    </button>

                    <form action="{{ route('admin.competitions.finish', $competition) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                            إنهاء المسابقة
                        </button>
                    </form>
                @endif
            </div>

            <div x-show="currentQuestion && timeRemaining > 0" class="mt-4 p-4 bg-yellow-50 rounded-lg">
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600" x-text="timeRemaining"></div>
                    <div class="text-sm text-gray-600">ثوانٍ متبقية</div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">الأسئلة ({{ $competition->questions->count() }})</h3>
            <button @click="showAddQuestion = !showAddQuestion"
                    x-show="status === 'closed'"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                إضافة سؤال
            </button>
            <div @click="showQuestionList = !showQuestionList" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">إظهار/إخفاء</div>
        </div>

        <div x-show="showAddQuestion" class="mb-6 p-4 bg-gray-50 rounded-lg text-right">
            <form action="{{ route('admin.competitions.questions.store', $competition) }}" method="POST" 
                  x-data="{ options: ['', ''], correctOption: 0 }">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">السؤال</label>
                    <textarea name="question_text" rows="3" required
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-right"></textarea>
                </div>

                <div class="mb-4 text-right">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الخيارات</label>
                    <template x-for="(option, index) in options" :key="index">
                        <div class="flex gap-2 mb-2 items-center">
                            <input type="radio" name="correct_option" :value="index" x-model="correctOption" required>
                            <input type="text" :name="'options[' + index + ']'" x-model="options[index]" required
                                   class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-right"
                                   placeholder="نص الخيار">
                            <button type="button" @click="options.splice(index, 1)" x-show="options.length > 2"
                                    class="text-red-600 hover:text-red-800 text-sm">حذف</button>
                        </div>
                    </template>
                    <button type="button" @click="options.push('')"
                            class="text-blue-600 hover:text-blue-800 text-sm">+ إضافة خيار</button>
                </div>

                <div class="flex gap-2 justify-start">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                        حفظ السؤال
                    </button>
                    <button type="button" @click="showAddQuestion = false"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-4 text-right" x-show="showQuestionList">
            @forelse($competition->questions as $question)
                <div class="border rounded-lg p-4"
                     :class="currentQuestion && currentQuestion.id === {{ $question->id }} ? 'border-green-500 bg-green-50' : 'border-gray-200'">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 mb-2">{{ $question->question_text }}</p>
                            <div class="space-y-1">
                                @foreach($question->options as $option)
                                    <div class="flex items-center text-sm">
                                        @if($option->is_correct)
                                            <svg class="w-4 h-4 text-green-600 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 000 2h4a1 1 0 100-2H8z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                        <span class="{{ $option->is_correct ? 'text-green-700 font-medium' : 'text-gray-600' }}">
                                            {{ $option->option_text }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex gap-2 mr-4">
                            @if($competition->status === 'started')
                                <button @click="pushQuestion({{ $question->id }})"
                                        :disabled="currentQuestion && currentQuestion.id === {{ $question->id }}"
                                        class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white px-3 py-1 rounded text-sm font-medium">
                                    إرسال
                                </button>
                            @endif
                            @if($competition->status === 'closed')
                                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟')"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        حذف
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">لا توجد أسئلة مضافة حتى الآن.</p>
            @endforelse
        </div>
    </div>

    <div x-show="showResults" class="bg-white rounded-lg shadow p-6 text-right">
        <h3 class="text-lg font-semibold mb-4">نتائج السؤال الأخير</h3>
        <div class="mb-4">
            <p class="font-medium">الإجابة الصحيحة: <span x-text="correctAnswer?.option_text" class="text-green-600"></span></p>
        </div>
        <div>
            <p class="font-medium mb-2">المشاركون الذين أجابوا بشكل صحيح:</p>
            <div class="space-y-1">
                <template x-for="participant in correctParticipants" :key="participant.id">
                    <div class="text-sm text-gray-700" x-text="participant.name"></div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
/* JavaScript function remains same - logic is language agnostic */
function competitionControl(competitionId, initialStatus) {
    return {
        status: initialStatus,
        participantsCount: {{ $competition->participants->count() }},
        currentQuestion: null,
        timeRemaining: 0,
        showAddQuestion: false,
        showQuestionList:true,
        showResults: false,
        correctAnswer: null,
        correctParticipants: [],
        timer: null,

        init() {
            this.startPolling();
        },

        startPolling() {
            this.fetchLiveData();
            setInterval(() => this.fetchLiveData(), 2000);
        },

        async fetchLiveData() {
            const response = await fetch(`/admin/competitions/${competitionId}/live`);
            const data = await response.json();
            
            this.status = data.status;
            this.currentQuestion = data.current_question;
            this.timeRemaining = data.time_remaining;
            this.participantsCount = data.participants_count;

            if (this.timeRemaining === 0 && this.currentQuestion) {
                this.closeCurrentQuestion();
            }
        },

        async pushQuestion(questionId) {
            const response = await fetch(`/admin/competitions/${competitionId}/push-question/${questionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                this.showResults = false;
            }
        },

        async closeCurrentQuestion() {
            const response = await fetch(`/admin/competitions/${competitionId}/close-question`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            if (data.success) {
                this.showResults = true;
                this.correctAnswer = data.correct_answer;
                this.correctParticipants = data.correct_participants;
                this.currentQuestion = null;
            }
        }
    }
}
</script>
</x-app-layout>