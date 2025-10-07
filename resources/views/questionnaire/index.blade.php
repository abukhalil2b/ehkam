<x-app-layout>

    <div class="max-w-4xl mx-auto mt-6 space-y-4">
        <h2 class="text-xl font-bold">الاستبيانات</h2>
        <a href="{{ route('questionnaire.create') }}" class="w-44 flex items-center text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:ring-2 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200 shadow-sm">
            جديد
        </a>
        @foreach ($questionnaires as $q)
            <div class="p-6 bg-white rounded-2xl shadow">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $q->title }}</h3>
                        <p class="text-gray-500 text-sm">{{ $q->description }}</p>
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
                        <a href="{{ route('questionnaire.show', $q) }}" class="text-orange-600 font-semibold text-sm">إدارة</a>
                        <a href="{{ route('questionnaire.take', $q) }}" class="text-blue-600 font-semibold text-sm">تعبئة الاستبيان</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
