<x-app-layout>
   <x-slot name="header">
    قائمة أسئلة التقييم
   </x-slot>

    <div class="container py-8 mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">أسئلة التقييم</h2>
            
            <a href="{{ route('assessment_questions.create') }}"
                class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150">
                + إنشاء سؤال جديد
            </a>
        </div>


        @if ($questions->isEmpty())
            <div class="p-6 text-center bg-gray-50 border border-dashed rounded-lg">
                <p class="text-lg text-gray-500">لم يتم إنشاء أي أسئلة تقييم بعد.</p>
                <a href="{{ route('assessment_questions.create') }}" class="text-purple-600 hover:text-purple-800 mt-2 inline-block font-medium">ابدأ بإضافة أول سؤال.</a>
            </div>
        @else
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السؤال</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">النوع</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">الحد الأقصى</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($questions as $question)
                            <tr>
                                <td class="px-6 py-4 text-gray-900 font-medium">
                                    {{ $question->content }}
                                    <div class="text-xs text-gray-400">{{ $question->description }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $question->type == 'range' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $question->type == 'range' ? 'نقاط' : 'نص' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $question->max_point ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <!-- Add actions like Edit or Delete here -->
                                    <a href="{{ route('assessment_questions.edit',$question->id) }}" class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
