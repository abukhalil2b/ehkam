<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" dir="rtl">
        <div class="mb-6">
            <a href="{{ route('admin.competitions.show', $competition) }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                العودة إلى المسابقة
            </a>
            <h1 class="text-2xl font-bold text-gray-900">تعديل السؤال</h1>
            <p class="text-gray-600 mt-1">السؤال رقم {{ $question->order }} من {{ $competition->title }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('admin.competitions.questions.update', [$competition, $question]) }}" method="POST"
                x-data="{ 
                    options: {{ $question->options->pluck('option_text')->toJson() }},
                    correctOption: {{ $question->options->search(fn($opt) => $opt->is_correct) ?? 0 }},
                    order: {{ $question->order }}
                }">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم السؤال</label>
                    <input type="number" name="order" x-model="order" min="1" required
                        class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                    <p class="text-sm text-gray-500 mt-1">ترتيب السؤال في المسابقة</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">نص السؤال</label>
                    <textarea name="question_text" rows="3" required
                        class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right"
                        placeholder="أدخل نص السؤال هنا...">{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                <div class="mb-6 text-right">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الخيارات</label>
                    <template x-for="(option, index) in options" :key="index">
                        <div class="flex gap-2 mb-2 items-center">
                            <input type="radio" name="correct_option" :value="index" x-model="correctOption" required>
                            <input type="text" :name="'options[' + index + ']'" x-model="options[index]" required
                                class="flex-1 border-gray-300 rounded-lg text-right" placeholder="نص الخيار">
                            <button type="button" @click="options.splice(index, 1); if(correctOption >= options.length) correctOption = options.length - 1" 
                                x-show="options.length > 2"
                                class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="options.push('')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        + إضافة خيار
                    </button>
                    <p class="text-sm text-gray-500 mt-2">حدد الخيار الصحيح من خلال الدائرة الموجودة بجانبه</p>
                </div>

                @error('correct_option')
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-right">
                        {{ $message }}
                    </div>
                @enderror

                <div class="flex gap-4 justify-end">
                    <a href="{{ route('admin.competitions.show', $competition) }}" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium">
                        إلغاء
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
