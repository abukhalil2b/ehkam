<x-app-layout>
    <div class="max-w-6xl mx-auto mt-8 space-y-6 py-4">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">
                ردود: {{ $questionnaire->title }}
            </h1>
            <form action="{{ route('questionnaire.answers', $questionnaire) }}" method="GET" class="w-full sm:w-auto">
                <div class="relative">
                    <input type="text" name="search" placeholder="ابحث باسم المشارك..." value="{{ request('search') }}" class="w-full sm:w-64 pl-10 pr-4 py-2 border rounded-lg">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            @forelse ($answersByUser as $userId => $userAnswers)
                <div class="bg-white shadow rounded-xl p-5 flex justify-between items-center">
                    <a href="{{ route('questionnaire.answer_show', ['answer' => $userAnswers->first()->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        عرض التفاصيل
                    </a>
                </div>
            @empty
                <div class="text-center py-10 bg-white rounded-xl shadow text-gray-500">
                    <p class="text-lg">لم يتم العثور على ردود تطابق بحثك.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8">
            {{ $paginator->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>