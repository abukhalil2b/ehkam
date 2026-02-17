<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 leading-tight">
            تعديل خطوط الأساس للقطاعات: {{ $indicator->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('indicator.baselines.update', $indicator) }}" method="POST"
                class="bg-white shadow-sm rounded-xl overflow-hidden">
                @csrf
                <div class="p-6 space-y-6">
                    <p class="text-sm text-gray-500 border-b pb-4 italic">
                        * يرجى تحديد قيمة البداية وسنة الرصد الأولى لكل قطاع مرتبط بهذا المؤشر.
                    </p>
                    <div class="space-y-4">
                        @foreach ($indicator->sectors_with_baseline as $sector)
                            <div
                                class="flex flex-wrap md:flex-nowrap items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex-1 min-w-[200px]">
                                    <label
                                        class="block text-sm font-bold text-gray-700 mb-1">{{ $sector->name }}</label>
                                    <span class="text-xs text-gray-400">كود القطاع: {{ $sector->code }}</span>
                                </div>

                                <div class="w-full md:w-40">
                                    <label class="block text-xs text-gray-400 mb-1">خط الأساس (القيمة)</label>
                                    <input type="number" step="0.01" name="baselines[{{ $sector->id }}][value]"
                                        value="{{ $sector->pivot->baseline_numeric }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required>
                                </div>

                                <div class="w-full md:w-32">
                                    <label class="block text-xs text-gray-400 mb-1">سنة الأساس</label>
                                    <input type="number" name="baselines[{{ $sector->id }}][year]"
                                        value="{{ $sector->pivot->baseline_year ?? 2022 }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        required>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                    <a href="{{ route('indicator.show', $indicator) }}"
                        class="text-sm text-gray-600 hover:underline">إلغاء والعودة</a>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold shadow-md hover:bg-indigo-700 transition-colors">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
