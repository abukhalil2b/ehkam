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
                          d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
                العودة للمؤشرات
            </a>
        </div>

        {{-- Indicator Info --}}
        <div class="bg-white p-5 rounded-xl shadow border">
            <h2 class="text-xl font-bold">{{ $indicator->title }}</h2>

            <div class="text-sm text-gray-600 mt-2 space-x-2 rtl:space-x-reverse">
                <span>السنة: {{ $currentYear }}</span>

                @if($sectorId && $sectorTarget !== null)
                    <span>— المستهدف: {{ number_format($sectorTarget, 2) }}</span>
                @endif

                <span>— الدورية: {{ __($indicator->period) }}</span>
            </div>
        </div>

        {{-- Sector Selection --}}
        <form method="GET" class="bg-white p-5 rounded-xl shadow border">
            <label class="block font-semibold mb-2">اختر القطاع</label>

            <select name="sector_id"
                    onchange="this.form.submit()"
                    class="w-full border-gray-300 rounded-lg">
                <option value="">— اختر —</option>

                @foreach ($sectors as $sector)
                    <option value="{{ $sector->id }}"
                        @selected((int)$sectorId === (int)$sector->id)>
                        {{ $sector->name }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Target Input Form --}}
        @if ($sectorId)
            <form method="POST"
                  action="{{ route('indicator.target.store', $indicator) }}"
                  class="bg-white p-6 rounded-xl shadow border space-y-6">
                @csrf

                <input type="hidden" name="year" value="{{ $currentYear }}">
                <input type="hidden" name="sector_id" value="{{ $sectorId }}">

                <h3 class="font-bold text-lg">إدخال المستهدفات</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($periods as $period)
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                الفترة {{ $period }}
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="values[{{ $period }}]"
                                   value="{{ old('values.' . $period, $targets[$period]->target_value ?? '') }}"
                                   class="w-full text-center border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('indicator.index') }}"
                       class="px-5 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                        إلغاء
                    </a>

                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        حفظ
                    </button>
                </div>
            </form>
        @endif

    </div>
</x-app-layout>