<x-app-layout>
    <x-slot name="header">
        إدخال الإنجاز الفعلي للمؤشرات
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 space-y-6">

        {{-- زر العودة --}}
        <div class="flex justify-end">
            <a href="{{ route('indicator.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                العودة للمؤشرات
            </a>
        </div>

        {{-- معلومات المؤشر --}}
        <div class="bg-white p-5 rounded-xl shadow border border-green-100 border-l-4 border-l-green-500">
            <h2 class="text-xl font-bold text-gray-800">{{ $indicator->title }}</h2>
            <p class="text-sm text-gray-500 mt-1">
                دورية القياس:
                <strong>{{ $indicator->period_label }}</strong>
                | وحدة القياس: <strong>{{ $indicator->unit === 'percentage' ? 'نسبة مئوية %' : 'رقم ثابت' }}</strong>
            </p>
        </div>

        {{-- اختيار القطاع والسنة --}}
        <form method="GET" class="bg-white p-5 rounded-xl shadow border">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">السنة</label>
                    <select name="year" onchange="this.form.submit()"
                        class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        {{-- توليد سنوات من الأساس إلى المستقبل --}}
                        @php $startYear = $indicator->baseline_year ?? 2022; @endphp
                        @for ($y = $startYear; $y <= now()->year + 5; $y++)
                            <option value="{{ $y }}" @selected((int) $currentYear === $y)>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2 text-gray-700">القطاع</label>
                    <select name="sector_id" onchange="this.form.submit()"
                        class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="">— اختر القطاع المُنفذ —</option>
                        @foreach ($sectors as $sector)
                            <option value="{{ $sector->id }}" @selected((int) $sectorId === (int) $sector->id)>
                                {{ $sector->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        {{-- نموذج إدخال المحقق --}}
        @if ($sectorId)
            <form method="POST" action="{{ route('indicator.achieved.store', $indicator) }}"
                class="bg-white p-6 rounded-xl shadow border space-y-6">
                @csrf

                <input type="hidden" name="year" value="{{ $currentYear }}">
                <input type="hidden" name="sector_id" value="{{ $sectorId }}">

                <div class="border-b pb-3">
                    <h3 class="font-bold text-lg text-green-700">المحقق الفعلي للقطاع لسنة {{ $currentYear }}</h3>
                    <p class="text-sm text-gray-500">قم بإدخال القيمة المحققة فعلياً لكل فترة. يمكنك ترك الفترات
                        المستقبلية فارغة.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($periods as $period)
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <label class="block text-sm font-bold mb-2 text-gray-800 text-center">
                                الفترة {{ $period }}
                            </label>

                            <div class="space-y-3">
                                {{-- حقل القيمة --}}
                                <div>
                                    <label class="text-xs text-gray-500 block mb-1">القيمة المحققة</label>
                                    <input type="number" step="0.01" name="values[{{ $period }}]"
                                        value="{{ old('values.' . $period, $sectorAchievements->get($period)?->achieved_value ?? '') }}"
                                        class="w-full text-center border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="0.00">
                                </div>

                                {{-- حقل الملاحظات --}}
                                <div>
                                    <label class="text-xs text-gray-500 block mb-1">ملاحظات (اختياري)</label>
                                    <textarea name="notes[{{ $period }}]" rows="2"
                                        class="w-full border-gray-300 rounded-md px-3 py-1 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-gray-300"
                                        placeholder="سبب الارتفاع/الانخفاض...">{{ old('notes.' . $period, $sectorAchievements->get($period)?->notes ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t mt-4">
                    <a href="{{ route('indicator.index') }}"
                        class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        إلغاء
                    </a>

                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition shadow">
                        حفظ المحقق الفعلي
                    </button>
                </div>
            </form>
        @endif

    </div>
</x-app-layout>
