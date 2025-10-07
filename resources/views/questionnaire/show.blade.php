<x-app-layout>
    <div class="max-w-6xl mx-auto mt-8 space-y-6 py-4">
        <!-- Header Section -->
        <div class="bg-white shadow rounded-2xl p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="w-full sm:w-auto">
                    <h1 class="text-xl font-bold text-gray-800 mb-2">{{ $questionnaire->title }}</h1>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            {{ $questionnaire->questions_count }} أسئلة
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $questionnaire->answers_count }} إجابة
                        </span>
                        <span class="flex items-center gap-1">
                            @if ($questionnaire->is_active)
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                نشط
                            @else
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                غير نشط
                            @endif
                        </span>
                    </div>
                </div>


            </div>
        </div>

        <div class="space-y-8">

            <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    <a href="{{ route('questionnaire.question_edit', $questionnaire) }}"
                        class="block p-6 bg-white border border-blue-200 rounded-xl shadow-md hover:shadow-xl hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1 group">
                        <div class="flex items-center w-full mb-3">
                            <div
                                class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-600 rounded-full transition group-hover:bg-blue-600 group-hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mr-3 text-right">إدارة الأسئلة</h3>
                        </div>
                        <p class="text-gray-500 text-sm">إضافة، تعديل، وإعادة ترتيب أسئلة الاستبيان.</p>
                    </a>

                    <a href="{{ route('questionnaire.answer_index', $questionnaire) }}"
                        class="block p-6 bg-white border border-green-200 rounded-xl shadow-md hover:shadow-xl hover:border-green-500 transition-all duration-300 transform hover:-translate-y-1 group">
                        <div class="flex items-center w-full mb-3">
                            <div
                                class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-green-100 text-green-600 rounded-full transition group-hover:bg-green-600 group-hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mr-3 text-right">عرض الردود</h3>
                        </div>
                        <p class="text-gray-500 text-sm">تصفح وتحليل الردود الفردية من المشاركين.</p>
                    </a>

                    <a href="{{ route('questionnaire.statistics', $questionnaire) }}"
                        class="block p-6 bg-white border border-purple-200 rounded-xl shadow-md hover:shadow-xl hover:border-purple-500 transition-all duration-300 transform hover:-translate-y-1 group">
                        <div class="flex items-center w-full mb-3">
                            <div
                                class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-purple-100 text-purple-600 rounded-full transition group-hover:bg-purple-600 group-hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mr-3 text-right">التقارير والإحصاءات</h3>
                        </div>
                        <p class="text-gray-500 text-sm">عرض ملخصات مرئية وإحصائيات للإجابات.</p>
                    </a>

                    <a href="{{ route('questionnaire.export', $questionnaire) }}"
                        class="block p-6 bg-white border border-yellow-200 rounded-xl shadow-md hover:shadow-xl hover:border-yellow-500 transition-all duration-300 transform hover:-translate-y-1 group">
                        <div class="flex items-center w-full mb-3">
                            <div
                                class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full transition group-hover:bg-yellow-600 group-hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mr-3 text-right">تصدير البيانات</h3>
                        </div>
                        <p class="text-gray-500 text-sm">تنزيل جميع بيانات الردود في ملف Excel.</p>
                    </a>

                    <a href="{{ route('questionnaire.edit', $questionnaire) }}"
                        class="block p-6 bg-white border border-gray-200 rounded-xl shadow-md hover:shadow-xl hover:border-gray-500 transition-all duration-300 transform hover:-translate-y-1 group">
                        <div class="flex items-center w-full mb-3">
                            <div
                                class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-gray-100 text-gray-600 rounded-full transition group-hover:bg-gray-600 group-hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mr-3 text-right">تعديل الاستبيان</h3>
                        </div>
                        <p class="text-gray-500 text-sm">تغيير العنوان والوصف وإعدادات الاستبيان.</p>
                    </a>

                    <a href="{{ route('questionnaire.duplicate', $questionnaire) }}"
                        class="block p-6 bg-white border border-indigo-200 rounded-xl shadow-md hover:shadow-xl hover:border-indigo-500 transition-all duration-300 transform hover:-translate-y-1 group"
                        onclick="return confirm('هل أنت متأكد من أنك تريد تكرار هذا الاستبيان؟ سيتم إنشاء نسخة جديدة كاملة.');">
                        <div class="flex items-center w-full mb-3">
                            <div
                                class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full transition group-hover:bg-indigo-600 group-hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7v4a1 1 0 001 1h4a1 1 0 001-1V7m0 10l-4-4m4 4l4-4m-4 4V14m0 4h.01M3 21h18a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mr-3 text-right">تكرار الاستبيان</h3>
                        </div>
                        <p class="text-gray-500 text-sm">إنشاء نسخة جديدة مطابقة لهذا الاستبيان.</p>
                    </a>

                    <form action="{{ route('questionnaire.delete', $questionnaire) }}" method="POST"
                        onsubmit="return confirm('هل أنت متأكد من حذف هذا الاستبيان؟ سيتم حذف جميع الردود بشكل دائم ولا يمكن التراجع عن هذا الإجراء.');"
                        class="block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full text-right block p-6 bg-white border border-red-200 rounded-xl shadow-md hover:shadow-xl hover:border-red-500 transition-all duration-300 transform hover:-translate-y-1 group">
                            <div class="flex items-center w-full mb-3">
                                <div
                                    class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-red-100 text-red-600 rounded-full transition group-hover:bg-red-600 group-hover:text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mr-3 text-right">حذف الاستبيان</h3>
                            </div>
                            <p class="text-gray-500 text-sm">حذف الاستبيان وجميع الردود المرتبطة به بشكل دائم.</p>
                        </button>
                    </form>

                    <a href="{{ route('questionnaire.index') }}"
                        class="block p-6 bg-white border border-gray-200 rounded-xl shadow-md hover:shadow-xl hover:border-gray-500 transition-all duration-300 transform hover:-translate-y-1 group">
                        <div class="flex items-center w-full mb-3">
                            <div
                                class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-gray-100 text-gray-600 rounded-full transition group-hover:bg-gray-600 group-hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mr-3 text-right">العودة للقائمة</h3>
                        </div>
                        <p class="text-gray-500 text-sm">الرجوع إلى صفحة قائمة جميع الاستبيانات.</p>
                    </a>

                </div>
            </div>
        </div>

        <!-- Questions List -->
        <div class="bg-white shadow rounded-2xl p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">الأسئلة</h2>
            <div class="space-y-4">
                @foreach ($questionnaire->questions as $question)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-gray-700">{{ $loop->iteration }}.
                                {{ $question->question_text }}</h3>
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                                @switch($question->type)
                                    @case('single')
                                        اختيار فردي
                                    @break

                                    @case('multiple')
                                        اختيار متعدد
                                    @break

                                    @case('range')
                                        مقياس
                                    @break

                                    @case('text')
                                        نصي
                                    @break
                                @endswitch
                            </span>
                        </div>

                        @if ($question->description)
                            <p class="text-sm text-gray-600 mb-3">{{ $question->description }}</p>
                        @endif

                        <!-- Choices -->
                        @if (in_array($question->type, ['single', 'multiple']))
                            <div class="space-y-1 mt-2">
                                @foreach ($question->choices as $choice)
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                        {{ $choice->choice_text }}
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($question->type === 'range')
                            @if ($question->choices->first())
                                <div class="mt-2">
                                    <span class="text-sm text-gray-600">
                                        من {{ $question->choices->first()->min_value }} إلى
                                        {{ $question->choices->first()->max_value }}
                                    </span>
                                </div>
                            @endif
                        @endif

                        <!-- Response Statistics -->
                        @php
                            $questionAnswers = $question->answers;
                            $answerCount = $questionAnswers->count();
                        @endphp

                        @if ($answerCount > 0)
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">الإجابات ({{ $answerCount }})
                                </h4>

                                @if (in_array($question->type, ['single', 'multiple']))
                                    <div class="space-y-2">
                                        @foreach ($question->choices as $choice)
                                            @php
                                                $choiceAnswerCount = $questionAnswers
                                                    ->filter(function ($answer) use ($choice) {
                                                        return in_array($choice->id, $answer->choice_ids ?? []);
                                                    })
                                                    ->count();
                                                $percentage =
                                                    $answerCount > 0 ? ($choiceAnswerCount / $answerCount) * 100 : 0;
                                            @endphp
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">{{ $choice->choice_text }}</span>
                                                <div class="flex items-center gap-2">
                                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-blue-600 h-2 rounded-full"
                                                            style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                    <span
                                                        class="text-gray-500 w-8 text-left">{{ number_format($percentage, 1) }}%</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($question->type === 'range')
                                    @php
                                        $rangeAnswers = $questionAnswers->pluck('range_value')->filter();
                                        $average = $rangeAnswers->avg();
                                        $min = $rangeAnswers->min();
                                        $max = $rangeAnswers->max();
                                    @endphp
                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div class="text-center">
                                            <span class="block text-gray-500">المتوسط</span>
                                            <span
                                                class="font-semibold text-blue-600">{{ number_format($average, 1) }}</span>
                                        </div>
                                        <div class="text-center">
                                            <span class="block text-gray-500">أقل قيمة</span>
                                            <span class="font-semibold text-green-600">{{ $min }}</span>
                                        </div>
                                        <div class="text-center">
                                            <span class="block text-gray-500">أعلى قيمة</span>
                                            <span class="font-semibold text-red-600">{{ $max }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($question->type === 'text')
                                    <div class="text-sm text-gray-600">
                                        {{ $answerCount }} إجابة نصية
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="mt-2 text-sm text-gray-500">لا توجد إجابات بعد</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>
