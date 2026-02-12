<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">تفاصيل التقييم: {{ $activity->title }}</h2>
            <a href="{{ url()->previous() }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition font-medium">العودة للتقرير</a>
        </div>
    </x-slot>

    <div class="container py-8 mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-8 flex flex-col md:flex-row justify-between items-center shadow-sm">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">المشروع</p>
                <h3 class="text-lg font-bold text-slate-700">{{ $activity->project->title }}</h3>
            </div>
            <div class="mt-4 md:mt-0 px-6 py-2 bg-indigo-50 rounded-xl border border-indigo-100 text-center">
                <p class="text-[10px] font-bold text-indigo-400 uppercase">دورة التقييم</p>
                <p class="text-md font-bold text-indigo-700">{{ $assessmentStage->title }}</p>
            </div>
        </div>

        <div class="space-y-6">
            @foreach ($allQuestions as $question)
                @php $result = $userResults->get($question->id); @endphp
                @if($result)
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                    <div class="p-5">
                        <div class="flex items-start gap-4">
                            <span class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500 flex-shrink-0">{{ $loop->iteration }}</span>
                            <div class="flex-1">
                                <h4 class="text-slate-800 font-semibold mb-4 leading-relaxed">{{ $question->content }}</h4>
                                
                                @if($question->type === 'range')
                                    <div class="flex items-center gap-2">
                                        @for($i = 1; $i <= $question->max_point; $i++)
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center border-2 font-bold text-sm transition-all
                                                {{ $i == $result->range_answer ? 'bg-indigo-600 border-indigo-600 text-white shadow-md' : 'bg-slate-50 border-slate-100 text-slate-300' }}">
                                                {{ $i }}
                                            </div>
                                        @endfor
                                    </div>
                                @else
                                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-100 text-slate-700 text-sm italic leading-loose">
                                        " {{ $result->text_answer }} "
                                    </div>
                                @endif

                                @if($result->note)
                                    <div class="mt-4 p-3 bg-amber-50 rounded-lg border border-amber-100">
                                        <p class="text-[10px] font-bold text-amber-600 uppercase mb-1 tracking-tighter">ملاحظة المقيم:</p>
                                        <p class="text-xs text-amber-800 leading-normal">{{ $result->note }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <div class="mt-10 pt-6 border-t border-slate-200">
            <div class="flex items-center justify-end gap-3 text-slate-500">
                <div class="text-right">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">تم التقييم بواسطة</p>
                    <p class="text-sm font-bold text-slate-700">{{ $evaluator->name ?? 'غير محدد' }}</p>
                    <p class="text-[10px]">{{ $result->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center border border-slate-200 text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>