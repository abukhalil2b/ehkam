<x-app-layout>
    <x-slot name="header">
        توزيع المستهدفات
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 space-y-6">

        {{-- Back Button --}}
        <div class="flex justify-end">
            <a href="{{ route('indicator.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                العودة للمؤشرات
            </a>
        </div>

        {{-- Indicator Info --}}
        <div class="bg-white p-5 rounded-xl shadow border">
            <h2 class="text-xl font-bold mb-4">{{ $indicator->title }}</h2>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">مستهدف المؤشر العام لسنة {{ $currentYear }}</h3>

                @if ($publicTarget)
                    <ul class="space-y-2 text-gray-600 border-t pt-2">
                        <li>
                            <strong class="text-gray-900">المستهدف كنسبة (نسبة النمو):</strong>
                            {{ $publicTarget['target_increment'] }}%
                        </li>
                        <li>
                            <strong class="text-gray-900">المستهدف كقيمة (القيمة التراكمية المحسوبة):</strong>
                            {{ number_format($publicTarget['calculated_target'], 2) }}
                            @if ($indicator->unit === 'percentage') % @endif
                        </li>
                    </ul>
                @else
                    <div class="p-3 bg-yellow-50 text-yellow-700 rounded-md border border-yellow-200">
                        لا توجد مستهدفات مدخلة أو محسوبة للمؤشر العام لهذه السنة.
                    </div>
                @endif
            </div>
        </div>

        {{-- Sector Selection --}}
        <form method="GET" class="bg-white p-5 rounded-xl shadow border">
            <input type="hidden" name="year" value="{{ $currentYear }}">

            <label class="block font-semibold mb-2">اختر القطاع لإدخال أو تعديل مستهدفه</label>

            <select name="sector_id" onchange="this.form.submit()" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">— اختر القطاع —</option>
                @foreach ($sectors as $sector)
                    <option value="{{ $sector->id }}" @selected((int) $sectorId === (int) $sector->id)>
                        {{ $sector->name }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Target Input Form --}}
        @if ($sectorId)
            <form method="POST" action="{{ route('indicator.target.store', $indicator) }}"
                class="bg-white p-6 rounded-xl shadow border space-y-6">
                @csrf

                <input type="hidden" name="year" value="{{ $currentYear }}">
                <input type="hidden" name="sector_id" value="{{ $sectorId }}">

                <div class="border-b pb-3">
                    <h3 class="font-bold text-lg text-indigo-700">مستهدفات القطاع لسنة {{ $currentYear }}</h3>
                    <p class="text-sm text-gray-500">الرجاء إدخال المستهدف المتوقع تحقيقه للقطاع في كل فترة (الوحدة: {{ $indicator->unit === 'percentage' ? 'نسبة مئوية %' : 'رقم ثابت' }}).</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($periods as $period)
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700">
                                الفترة {{ $period }}
                            </label>

                            <input type="number" step="0.01" name="values[{{ $period }}]"
                                value="{{ old('values.' . $period, $sectorTargets->get($period)?->target_value ?? '') }}"
                                class="w-full text-center border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-300"
                                placeholder="0.00">
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t mt-4">
                    <a href="{{ route('indicator.index') }}"
                        class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        إلغاء
                    </a>

                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        حفظ المستهدفات
                    </button>
                </div>
            </form>
        @endif

    </div>
</x-app-layout>