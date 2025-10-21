<x-app-layout>
    <div class="max-w-5xl mx-auto mt-10 space-y-8">

        <div class="text-center space-y-2">
            <h1 class="text-2xl font-bold text-gray-800">📋 إجابات الاستبيان</h1>
            <p class="text-gray-500 text-sm">
                عرض أحدث الردود على الاستبيان: <span class="font-semibold">{{ $questionnaire->title }}</span>
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            @if ($questionnaire->answers->count() > 0)
                @foreach ($questionnaire->answers->groupBy('user_id')->take(10) as $userAnswers)
                    @php
                        $first = $userAnswers->first();
                        $user = $first->user; // $user can be null
                        
                        // ✅ FIX 1: Safely determine the display name
                        $displayName = optional($user)->name ?? 'مستجيب عام (ضيف)';
                        
                        // ✅ FIX 2: Get the first letter safely, defaulting to a symbol
                        $firstLetter = $user ? mb_substr($user->name, 0, 1) : '?';
                        
                        $date = $first->created_at->diffForHumans();
                    @endphp

                    <div x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }"
                        class="border border-gray-200 rounded-xl mb-5 overflow-hidden transition">
                        <button @click="open = !open"
                            class="flex justify-between items-center w-full px-5 py-4 bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 flex items-center justify-center rounded-full text-white font-semibold 
                                    {{-- Use a different color if it's a guest user --}}
                                    {{ $user ? 'bg-gradient-to-r from-blue-500 to-indigo-500' : 'bg-gray-400' }}">
                                    {{ $firstLetter }}
                                </div>
                                <div>
                                    {{-- ✅ FIX 3: Use the safely determined display name --}}
                                    <p class="font-medium text-gray-800">{{ $displayName }}</p> 
                                    <p class="text-xs text-gray-500">{{ $date }}</p>
                                </div>
                            </div>
                            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-gray-500 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-collapse class="p-5 space-y-4 bg-white">
                            @foreach ($userAnswers as $answer)
                                <div class="p-4 border border-gray-100 rounded-lg shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="text-sm font-semibold text-gray-800">
                                            {{ optional($answer->question)->question_text }}
                                        </h3>
                                        <span class="px-2 py-1 text-xs bg-gray-100 rounded-full text-gray-600">
                                            @switch(optional($answer->question)->type)
                                                @case('single')
                                                    🟢 اختيار فردي
                                                @break
                                                @case('multiple')
                                                    🟣 اختيار متعدد
                                                @break
                                                @case('range')
                                                    🔵 مقياس
                                                @break
                                                @case('text')
                                                    ✏️ نصي
                                                @break
                                                @case('date')
                                                    📅 تاريخ
                                                @break
                                            @endswitch
                                        </span>
                                    </div>

                                    <div class="text-gray-700 leading-relaxed">
                                        @if (optional($answer->question)->type === 'text')
                                            <div
                                                class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-gray-800">
                                                {{ $answer->text_answer ?: '—' }}
                                            </div>
                                        @elseif(optional($answer->question)->type === 'range')
                                            <div class="flex items-center gap-3 text-sm">
                                                <span
                                                    class="bg-blue-100 text-blue-700 font-semibold px-3 py-1 rounded-lg text-base">
                                                    {{ $answer->range_value }}
                                                </span>
                                                {{-- Note: Accessing choices for range type might be brittle; ensure the structure is correct --}}
                                                @if (optional(optional($answer->question)->choices)->first())
                                                    <span class="text-gray-500">
                                                        من {{ optional(optional($answer->question)->choices)->first()->min_value }}
                                                        إلى {{ optional(optional($answer->question)->choices)->first()->max_value }}
                                                    </span>
                                                @endif
                                            </div>
                                        @elseif(in_array(optional($answer->question)->type, ['single', 'multiple', 'dropdown']))
                                            @php $selected = $answer->choices; @endphp 
                                            @if ($selected->count() > 0)
                                                <div class="flex flex-wrap gap-2 mt-1">
                                                    @foreach ($selected as $choice)
                                                        <span
                                                            class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                                            {{ $choice->choice_text }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-gray-400 text-sm">لم يتم اختيار أي خيار</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div class="text-left">
                                {{-- The 'answer_show' route typically expects the Answer ID, which is $first->id --}}
                                <a href="{{ route('questionnaire.answer_show', $first->id) }}"
                                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium mt-3">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    عرض التفاصيل الكاملة
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($questionnaire->answers->groupBy('user_id')->count() > 10)
                    <div class="text-center">
                        <button
                            class="text-blue-600 hover:text-blue-800 text-sm font-semibold mt-4 inline-flex items-center">
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            عرض المزيد من الإجابات
                        </button>
                    </div>
                @endif
            @else
                <div class="text-center py-10 text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-lg font-medium">لا توجد إجابات حتى الآن</p>
                    <p class="text-sm text-gray-400">سيظهر هنا الردود عند تعبئة الاستبيان</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>