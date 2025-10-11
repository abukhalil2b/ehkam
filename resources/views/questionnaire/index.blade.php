<x-app-layout>

    <div class="max-w-4xl mx-auto mt-6 space-y-4">
        <h2 class="text-xl font-bold">الاستبيانات</h2>
        <a href="{{ route('questionnaire.create') }}"
            class="p-3 w-44 flex items-center justify-center text-white bg-green-800 rounded-lg text-sm px-5">
            جديد
        </a>
        @foreach ($questionnaires as $q)
            <div class="p-6 bg-white rounded-2xl shadow">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $q->title }}</h3>
                        @if($q->target_response == 'open_for_all')
                         <div>مفتوح للكل</div>
                        @else
                        <div>فقط المسجلين</div>
                        @endif
                        
                        <span class="flex items-center gap-1">
                            @if ($q->is_active)
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                نشط
                            @else
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                غير نشط
                            @endif
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('questionnaire.show', $q) }}"
                            class="text-orange-600 font-semibold text-sm">إدارة</a>
                        @if ($q->target_response === 'open_for_all' && $q->public_hash)
                            <a href="{{ route('questionnaire.public_take', $q->public_hash) }}"
                                class="text-blue-600 font-semibold text-sm">تعبئة الاستبيان عام</a>
                        @elseif ($q->target_response === 'registerd_only')
                            <a href="{{ route('questionnaire.take', $q) }}"
                                class="text-blue-600 font-semibold text-sm">تعبئة الاستبيان</a>
                        @else
                            <span class="text-gray-500 text-sm">غير متاح حالياً</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
