<x-app-layout title="تعديل المسمى الوظيفي">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <a href="{{ route('positions.index') }}" class="text-gray-500 hover:text-purple-600 transition">
                <span class="material-icons">arrow_forward</span>
            </a>
            <span>تعديل: <span class="text-purple-700">{{ $position->title }}</span></span>
        </h1>
    </x-slot>

    <div class="p-6 bg-gray-50 min-h-screen flex justify-center">

        <div class="w-full max-w-2xl bg-white p-8 rounded-xl border shadow-lg">

            <form action="{{ route('positions.update', $position->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">المسمى الوظيفي</label>
                    <input type="text" name="title" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3"
                        value="{{ old('title', $position->title) }}">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Job Code --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الرمز الوظيفي</label>
                    <input type="text" name="job_code"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 bg-gray-50"
                        value="{{ old('job_code', $position->job_code) }}">
                    @error('job_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Org Unit --}}
                <div>
                    @php
                        $unitOptions = $allUnits->map(function ($unit) {
                            return ['id' => $unit->id, 'name' => $unit->name, 'code' => $unit->type];
                        })->values();
                    @endphp
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الوحدة التنظيمية</label>
                    {{-- Note: currentUnitIds is an array, but we are designing for single selection here as per
                    controller logic --}}
                    <x-forms.searchable-select name="org_unit_id" :options="$unitOptions"
                        :selected="!empty($currentUnitIds) ? $currentUnitIds[0] : null"
                        placeholder="-- اختر الوحدة التنظيمية --" :required="true" />
                    @error('org_unit_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Reports To --}}
                <div>
                    @php
                        $positionOptions = $allPositions->map(function ($pos) {
                            return ['id' => $pos->id, 'name' => $pos->title, 'code' => $pos->job_code];
                        })->values();
                    @endphp
                    <label class="block text-sm font-semibold text-gray-700 mb-1">يتبع إدارياً لـ</label>
                    <x-forms.searchable-select name="reports_to_position_id" :options="$positionOptions"
                        :selected="$position->reports_to_position_id" placeholder="(لا يوجد - وظيفة عليا)" />
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-6 border-t mt-8">
                    <a href="{{ route('positions.index') }}" class="text-gray-500 hover:underline">إلغاء</a>

                    <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-[1.02] flex items-center gap-2">
                        <span class="material-icons">save</span>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>