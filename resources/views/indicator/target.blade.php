<x-app-layout>
    <x-slot name="header">🎯 توزيع المستهدفات</x-slot>

    <div class="max-w-4xl mx-auto py-8 space-y-6">

        <div class="flex justify-end">
            <a href="{{ route('indicator.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                العودة للمؤشرات
            </a>
        </div>

        {{-- معلومات المؤشر --}}
        <div class="bg-white p-5 rounded-xl shadow border">
            <h2 class="text-xl font-bold">{{ $indicator->title }}</h2>
            <div class="text-sm text-gray-600 mt-1">
                السنة: {{ $currentYear }} —
                 المستهدف: {{ $sectorTarget }} —
                الدورية: {{ __($indicator->period) }}
               
            </div>
        </div>


        {{-- اختيار القطاع --}}
        <form method="GET" class="bg-white p-5 rounded-xl shadow border">
            <label class="block font-semibold mb-2">اختر القطاع</label>
            <select name="sector_id" onchange="this.form.submit()" class="w-full border-gray-300 rounded-lg">
                <option value="">— اختر —</option>
                @foreach ($sectors as $sector)
                    <option value="{{ $sector->id }}" @selected($sectorId == $sector->id)>
                        {{ $sector->name }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- نموذج الإدخال --}}
        @if ($sectorId)
            <form method="POST" action="{{ route('indicator.target.store', $indicator) }}"
                class="bg-white p-6 rounded-xl shadow border space-y-6">
                @csrf

                {{-- البيانات الأساسية --}}
                <input type="hidden" name="year" value="{{ $currentYear }}">
                <input type="hidden" name="sector_id" value="{{ $sectorId }}">

                <h3 class="font-bold text-lg">إدخال المستهدفات</h3>

                {{-- الفترات --}}
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($periods as $period)
                        <div>
                            <label class="text-sm font-medium">
                                الفترة {{ $period }}
                            </label>

                            <input type="number" step="0.01" name="values[{{ $period }}]"
                                value="{{ $targets[$period]->target_value ?? '' }}"
                                class="w-full text-center border rounded-lg px-3 py-2">
                        </div>
                    @endforeach
                </div>

                {{-- أزرار التحكم --}}
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('indicator.index') }}" class="px-5 py-2 bg-gray-100 rounded-lg">
                        إلغاء
                    </a>

                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                        حفظ
                    </button>
                </div>
            </form>
        @endif

    </div>
</x-app-layout>
