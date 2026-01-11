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
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الوحدة التنظيمية</label>
                    <div class="relative">
                        <select name="org_unit_id" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 appearance-none">
                            @foreach ($allUnits as $unit)
                                <option value="{{ $unit->id }}" {{ in_array($unit->id, old('org_unit_id', $currentUnitIds)) ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-700 rtl:right-unset rtl:left-0">
                            <span class="material-icons">expand_more</span>
                        </div>
                    </div>
                    @error('org_unit_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Reports To --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">يتبع إدارياً لـ</label>
                    <div class="relative">
                        <select name="reports_to_position_id"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3">
                            <option value="">(لا يوجد - وظيفة عليا)</option>
                            @foreach ($allPositions as $pos)
                                <option value="{{ $pos->id }}" {{ old('reports_to_position_id', $position->reports_to_position_id) == $pos->id ? 'selected' : '' }}>
                                    {{ $pos->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
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