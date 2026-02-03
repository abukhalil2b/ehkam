<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" dir="rtl"
        x-data="competitionControl({{ $competition->id }}, '{{ $competition->status }}')">

        <div class="mb-6 text-right">
            <h1 class="text-3xl font-bold text-gray-900">{{ $competition->title }}</h1>
            <p class="text-gray-600 mt-1">الرمز: <span class="font-mono font-bold">{{ $competition->join_code }}</span>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 text-right">رمز QR للانضمام</h3>
                <div class="flex justify-center">{!! $qrCode !!}</div>
                <p class="text-center mt-2 text-sm text-gray-600">{{ $competition->join_url }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow text-right">
                <h3 class="text-lg font-semibold mb-4">لوحة التحكم</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">الحالة:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold"
                            :class="status === 'closed' ? 'bg-gray-100 text-gray-800' : (status === 'started' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800')"
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
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">بدء
                                المسابقة</button>
                        </form>
                    @elseif($competition->status === 'started')
                        <button @click="closeCurrentQuestion()" x-show="currentQuestion"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium mb-2">إغلاق
                            السؤال الحالي</button>
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">إنهاء
                        المسابقة</button>
                        </form>
                    @elseif($competition->status === 'finished')
                        <div class="space-y-2">
                            <a href="{{ route('admin.competitions.results', $competition) }}"
                                class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium">
                                عرض النتائج
                            </a>
                            <form action="{{ route('admin.competitions.reopen', $competition) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('هل أنت متأكد؟ سيعود جميع المتسابقين لشاشة الانتظار.')"
                                    class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                                    إعادة فتح المسابقة 🔄
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">الأسئلة ({{ $competition->questions->count() }})</h3>
                <div class="flex gap-2">
                    <button @click="showAddQuestion = !showAddQuestion" x-show="status === 'closed'"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">إضافة
                        سؤال يدوياً</button>
                    <button @click="showQuestionList = !showQuestionList"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">إظهار/إخفاء
                        الكل</button>
                </div>
            </div>

            <div x-show="status === 'closed'" class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-lg" x-data="{ 
                     aiTopic: '', 
                     aiCount: 5, 
                     aiLoading: false,
                     aiError: '',
                     aiSuccess: false
                 }">
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 text-right">
                        <label class="block text-sm font-medium text-purple-900 mb-1">توليد تلقائي بواسطة الذكاء
                            الاصطناعي ✨</label>
                        <input type="text" x-model="aiTopic" placeholder="مثلاً: التخطيط الاستراتيجي..."
                            class="w-full border-purple-300 rounded-lg focus:ring-purple-500 text-right"
                            @input="aiError = ''; aiSuccess = false">
                    </div>
                    <div class="w-24 text-right">
                        <label class="block text-sm font-medium text-purple-900 mb-1">العدد</label>
                        <input type="number" x-model="aiCount" min="1" max="15"
                            class="w-full border-purple-300 rounded-lg focus:ring-purple-500 text-center">
                    </div>
                    <button @click="
                        aiLoading = true;
                        aiError = '';
                        aiSuccess = false;
                        fetch('{{ route('admin.competitions.generate', $competition) }}', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ topic: aiTopic, count: aiCount })
                        })
                        .then(response => response.json())
                        .then(data => {
                            aiLoading = false;
                            if (data.success) {
                                aiSuccess = true;
                                aiTopic = '';
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                aiError = data.error || 'حدث خطأ غير متوقع';
                            }
                        })
                        .catch(error => {
                            aiLoading = false;
                            aiError = 'حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.';
                            console.error('Error:', error);
                        })" :disabled="aiLoading || !aiTopic"
                        class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-6 py-2 rounded-lg font-bold flex items-center gap-2">
                        <span x-show="!aiLoading">توليد الآن</span>
                        <span x-show="aiLoading" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            جاري التوليد...
                        </span>
                    </button>
                </div>
                <div x-show="aiError"
                    class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-right">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <span x-text="aiError"></span>
                    </div>
                </div>
                <div x-show="aiSuccess"
                    class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-right">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>تم توليد الأسئلة بنجاح! جاري إعادة تحميل الصفحة...</span>
                    </div>
                </div>
            </div>

            <div x-show="showAddQuestion" class="mb-6 p-4 bg-gray-50 rounded-lg text-right">
                <form action="{{ route('admin.competitions.questions.store', $competition) }}" method="POST"
                    x-data="{ options: ['', ''], correctOption: 0 }">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">السؤال</label>
                        <textarea name="question_text" rows="3" required
                            class="w-full border-gray-300 rounded-lg text-right"></textarea>
                    </div>
                    <div class="mb-4 text-right">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الخيارات</label>
                        <template x-for="(option, index) in options" :key="index">
                            <div class="flex gap-2 mb-2 items-center">
                                <input type="radio" name="correct_option" :value="index" x-model="correctOption"
                                    required>
                                <input type="text" :name="'options[' + index + ']'" x-model="options[index]" required
                                    class="flex-1 border-gray-300 rounded-lg text-right" placeholder="نص الخيار">
                                <button type="button" @click="options.splice(index, 1)" x-show="options.length > 2"
                                    class="text-red-600">حذف</button>
                            </div>
                        </template>
                        <button type="button" @click="options.push('')" class="text-blue-600 text-sm">+ إضافة
                            خيار</button>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">حفظ السؤال</button>
                        <button type="button" @click="showAddQuestion = false"
                            class="bg-gray-300 px-4 py-2 rounded-lg">إلغاء</button>
                    </div>
                </form>
            </div>

            <div class="space-y-4 text-right" x-show="showQuestionList">
                @forelse($competition->questions->sortBy('order') as $question)
                    <div class="border rounded-lg p-4 transition-colors duration-300"
                        :class="currentQuestionId == {{ $question->id }} ? 'border-green-500 bg-green-50' : 'border-gray-200'"
                        x-data="{ expanded: false }">
                        <div class="flex justify-between items-center">
                            <div class="flex-1 cursor-pointer" @click="expanded = !expanded">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-lg text-blue-800">السؤال رقم {{ $question->order }}</span>
                                    <svg x-show="!expanded" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                    <svg x-show="expanded" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </div>

                                <div x-show="expanded" class="mt-4" x-transition>
                                    <p class="font-medium text-gray-900 mb-2">{{ $question->question_text }}</p>
                                    <div class="space-y-1">
                                        @foreach($question->options as $option)
                                            <div
                                                class="flex items-center text-sm {{ $option->is_correct ? 'text-green-700 font-bold' : 'text-gray-600' }}">
                                                <span class="ml-2">{{ $option->is_correct ? '✅' : '⚪' }}</span>
                                                {{ $option->option_text }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 mr-4">
                                <button x-show="status === 'started'" @click="pushQuestion({{ $question->id }})"
                                    class="bg-green-600 text-white px-3 py-1 rounded text-sm">إرسال</button>

                                <form x-show="status === 'closed'"
                                    action="{{ route('admin.questions.destroy', $question) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 text-sm">حذف</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">لا توجد أسئلة. استخدم شريط التوليد بالأعلى للبدء!</p>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function competitionControl(competitionId, initialStatus) {
            return {
                status: initialStatus,
                participantsCount: {{ $competition->participants->count() }},
                currentQuestionId: {{ $competition->current_question_id ?? 'null' }},
                currentQuestion: null,
                showAddQuestion: false,
                showQuestionList: true,
                init() {
                    // Polling removed as per request
                },
                async fetchLiveData() {
                    const response = await fetch(`/admin/competitions/${competitionId}/live`);
                    const data = await response.json();
                    this.status = data.status;
                    this.participantsCount = data.participants_count;
                    if (data.current_question) {
                        this.currentQuestionId = data.current_question.id;
                    }
                },
                async pushQuestion(questionId) {
                    // Optimistic update
                    this.currentQuestionId = questionId;

                    await fetch(`/admin/competitions/${competitionId}/push-question/${questionId}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                },
                async closeCurrentQuestion() {
                    await fetch(`/admin/competitions/${competitionId}/close-question`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    this.currentQuestionId = null;
                }
            }
        }
    </script>
</x-app-layout>