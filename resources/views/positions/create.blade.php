<x-app-layout title="إضافة مسمى وظيفي">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <a href="{{ route('positions.index') }}" class="text-gray-500 hover:text-purple-600 transition">
                <span class="material-icons">arrow_forward</span>
            </a>
            <span class="text-gray-500">إدارة الوظائف:</span>
            <span class="text-purple-700">مسمى جديد</span>
        </h1>
    </x-slot>

    <div class="p-6 bg-gray-50 min-h-screen flex justify-center">

        <div class="w-full max-w-2xl bg-white p-8 rounded-xl border shadow-lg">
            <div class="mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800">إضافة مسمى وظيفي جديد</h2>
                <p class="text-gray-500 mt-1">أدخل بيانات الوظيفة لإضافتها إلى الهيكل الوظيفي.</p>
            </div>

            <form action="{{ route('positions.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">المسمى الوظيفي <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 transition"
                        placeholder="مثال: مدير عام، محاسب، مطور برمجيات..." value="{{ old('title') }}">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Job Code --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الرمز الوظيفي (اختياري)</label>
                    <input type="text" name="job_code"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 p-3 bg-gray-50"
                        placeholder="سيتم إنشاؤه تلقائياً إذا ترك فارغاً" value="{{ old('job_code') }}">
                    @error('job_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Org Unit --}}
                <div>
                    @php
                        $unitOptions = $allUnits->map(function ($unit) {
                            return ['id' => $unit->id, 'name' => $unit->name, 'code' => $unit->type];
                        })->values();
                    @endphp
                    <label class="block text-sm font-bold text-gray-700 mb-2">الوحدة التنظيمية التابع لها <span
                            class="text-red-500">*</span></label>
                    <x-forms.searchable-select name="org_unit_id" :options="$unitOptions"
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
                    <label class="block text-sm font-bold text-gray-700 mb-2">يتبع إدارياً لـ (الرئيس المباشر)</label>
                    <x-forms.searchable-select name="reports_to_position_id" :options="$positionOptions"
                        placeholder="(لا يوجد - وظيفة قيادية عليا)" />
                    <p class="text-xs text-gray-400 mt-1">اتركه فارغاً إذا كانت هذه الوظيفة أعلى الهرم.</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end pt-6 border-t mt-8 gap-3">
                    <a href="{{ route('positions.index') }}"
                        class="px-6 py-3 rounded-lg border border-gray-300 text-gray-600 font-bold hover:bg-gray-50 transition">إلغاء</a>

                    <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                        <span class="material-icons">save</span>
                        حفظ المسمى
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>