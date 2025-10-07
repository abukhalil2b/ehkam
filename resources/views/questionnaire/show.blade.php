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

                <div class="flex flex-wrap gap-2 justify-end">

                    <a href="{{ route('questionnaire.duplicate', $questionnaire) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
                        onclick="return confirm('هل أنت متأكد من أنك تريد تكرار هذا الاستبيان؟')">
                        تكرار الاستبيان
                    </a>

                    <a href="{{ route('questionnaire.edit', $questionnaire) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        تعديل الاستبيان
                    </a>
                    <a href="{{ route('questionnaire.index') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        العودة للقائمة
                    </a>
                    <form action="{{ route('questionnaire.delete', $questionnaire) }}" method="POST"
                        onsubmit="return confirm('هل أنت متأكد من حذف هذا الاستبيان؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                            حذف الاستبيان
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Questions and Responses Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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
                                                        $answerCount > 0
                                                            ? ($choiceAnswerCount / $answerCount) * 100
                                                            : 0;
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

            <!-- Recent Responses -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">الإجابات</h2>
                @if ($questionnaire->answers->count() > 0)
                    <div class="space-y-3">
                        @foreach ($questionnaire->answers->groupBy('user_id')->take(10) as $userAnswers)
                            @php
                                $firstAnswer = $userAnswers->first();
                                $user = $firstAnswer->user;
                                $answerDate = $firstAnswer->created_at;
                                $answerCount = $userAnswers->count();
                            @endphp

                            <div x-data="{ isOpen: {{ $loop->first ? 'true' : 'false' }} }" class="border border-gray-200 rounded-lg overflow-hidden">
                                <!-- Header -->
                                <button @click="isOpen = !isOpen"
                                    class="w-full flex justify-between items-center p-4 hover:bg-gray-50 transition-colors text-left">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 text-sm font-semibold">
                                                {{ substr($user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 block">{{ $user->name }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <span class="text-sm text-gray-500">{{ $answerDate->diffForHumans() }}</span>
                                        <svg :class="{ 'rotate-180': isOpen }"
                                            class="w-5 h-5 text-gray-400 transition-transform duration-200"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </button>

                                <!-- Collapsible Content -->
                                <div x-show="isOpen" x-collapse class="border-t border-gray-100">
                                    <div class="p-4 bg-gray-50 space-y-4">
                                        @foreach ($userAnswers as $answer)
                                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                                <div class="flex justify-between items-start mb-3">
                                                    <span class="font-medium text-gray-700 text-sm">
                                                        {{ $answer->question->question_text }}
                                                    </span>
                                                    <span
                                                        class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                                                        @switch($answer->question->type)
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

                                                            @case('date')
                                                                تاريخ
                                                            @break
                                                        @endswitch
                                                    </span>
                                                </div>

                                                <div class="text-gray-700">
                                                    @if ($answer->question->type === 'text')
                                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                            <p class="text-sm text-gray-800">
                                                                {{ $answer->text_answer }}
                                                            </p>
                                                            <div class="text-xs">{{ $answer->note }}</div>
                                                        </div>
                                                    @elseif($answer->question->type === 'range')
                                                        <div class="flex items-center gap-3">
                                                            <span
                                                                class="bg-blue-100 text-blue-700 px-3 py-2 rounded-lg font-semibold text-lg">
                                                                {{ $answer->range_value }}
                                                            </span>
                                                            @if ($answer->question->choices->first())
                                                                <span class="text-sm text-gray-500">
                                                                    من
                                                                    {{ $answer->question->choices->first()->min_value }}
                                                                    إلى
                                                                    {{ $answer->question->choices->first()->max_value }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @elseif(in_array($answer->question->type, ['single', 'multiple']))
                                                        @php
                                                            $selectedChoices = $answer->choices();
                                                        @endphp
                                                        @if ($selectedChoices->count() > 0)
                                                            <div class="flex flex-wrap gap-2">
                                                                @foreach ($selectedChoices as $choice)
                                                                    <span
                                                                        class="bg-green-100 text-green-700 px-3 py-2 rounded-lg text-sm font-medium">
                                                                        {{ $choice->choice_text }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="text-sm text-gray-500">لم يتم اختيار أي
                                                                خيار</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <a href="{{ route('questionnaire.answer_show', $answer->id) }}">مشاهدة</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Show More Button if there are more responses -->
                    @if ($questionnaire->answers->groupBy('user_id')->count() > 10)
                        <div class="mt-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                عرض المزيد من الإجابات
                            </button>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-lg mb-2">لا توجد إجابات حتى الآن</p>
                        <p class="text-sm text-gray-400">سيظهر هنا الإجابات بمجرد أن يبدأ المستخدمون في تعبئة الاستبيان
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
