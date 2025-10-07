<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-4">تعديل وإعادة ترتيب الأسئلة</h2>
    <div class="bg-white shadow rounded-2xl p-6">
        <ul wire:sortable="updateQuestionOrder" class="space-y-4">
            @forelse ($questions as $question)
                <li wire:sortable.item="{{ $question->id }}" wire:key="question-{{ $question->id }}" class="border border-gray-200 rounded-lg p-4 bg-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div wire:sortable.handle class="cursor-move text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700">{{ $question->question_text }}</p>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $question->type }}</span>
                            </div>
                        </div>
                        <div>
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">تعديل</button>
                        </div>
                    </div>
                </li>
            @empty
                <p class="text-gray-500 text-center py-4">لا توجد أسئلة بعد. قم بإضافتها أولاً.</p>
            @endforelse
        </ul>
    </div>
</div>