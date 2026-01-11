<x-app-layout title="إدارة المسميات الوظيفية">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <span class="material-icons text-4xl text-purple-600">badge</span>
            إدارة المسميات الوظيفية
        </h1>
    </x-slot>

    <div class="p-6 bg-gray-50 min-h-screen grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- 1. Positions Tree Listing --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg border shadow-sm">
            <div class="flex justify-between items-center mb-6">
                 <h3 class="text-xl font-bold text-gray-800 flex items-center space-x-2 rtl:space-x-reverse">
                    <span class="material-icons">account_tree</span>
                    الهيكل الوظيفي
                </h3>
            </div>

            <div class="border border-gray-200 p-4 rounded-lg bg-gray-50 min-h-[300px]">
                @forelse ($topLevelPositions as $position)
                    @include('positions.partials._hierarchy-item', [
                        'position' => $position,
                        'depth' => 0,
                    ])
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                        <span class="material-icons text-6xl text-gray-300 mb-2">format_list_bulleted</span>
                        <p>لا يوجد مسميات وظيفية مضافه حتى الآن.</p>
                        <p class="text-sm">قم بإضافة أول مسمى وظيفي من النموذج المقابل.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 2. Create/Edit Actions (Initially Create) --}}
        <div class="bg-white p-6 rounded-lg border shadow-lg h-fit">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h3 class="text-xl font-bold text-purple-700">إضافة مسمى وظيفي جديد</h3>
                <a href="{{ route('positions.create') }}" class="text-sm bg-purple-50 text-purple-700 px-3 py-1 rounded hover:bg-purple-100 transition flex items-center gap-1">
                    <span class="material-icons text-sm">open_in_new</span>
                    صفحة كاملة
                </a>
            </div>
            
            <form action="{{ route('positions.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">المسمى الوظيفي</label>
                    <input type="text" name="title" required 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 transition"
                           placeholder="مثال: مدير عام، محاسب، مطور برمجيات..."
                           value="{{ old('title') }}">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Job Code (Optional) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الرمز الوظيفي (اختياري)</label>
                    <input type="text" name="job_code" 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 transition"
                           placeholder="تلقائي إذا ترك فارغاً"
                           value="{{ old('job_code') }}">
                </div>

                {{-- Org Unit --}}
                <div>
                    @php
                        $unitOptions = $allUnits->map(function ($unit) {
                            return ['id' => $unit->id, 'name' => $unit->name, 'code' => $unit->type];
                        })->values();
                    @endphp
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الوحدة التنظيمية التابع لها</label>
                    <x-forms.searchable-select 
                        name="org_unit_id" 
                        :options="$unitOptions" 
                        placeholder="-- اختر الوحدة التنظيمية --" 
                        :required="true" 
                    />
                    @error('org_unit_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Reports To --}}
                <div>
                    @php
                        $positionOptions = $allPositions->map(function ($pos) {
                            return ['id' => $pos->id, 'name' => $pos->title, 'code' => $pos->job_code];
                        })->values();
                    @endphp
                    <label class="block text-sm font-semibold text-gray-700 mb-1">يتبع إدارياً لـ (الرئيس المباشر)</label>
                    <x-forms.searchable-select 
                        name="reports_to_position_id" 
                        :options="$positionOptions" 
                        placeholder="(لا يوجد - وظيفة قيادية عليا)" 
                    />
                    <p class="text-xs text-gray-500 mt-1">اتركه فارغاً إذا كانت هذه الوظيفة أعلى الهرم.</p>
                </div>

                <div class="pt-4">
                    <button type="submit" 
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                        <span class="material-icons">save</span>
                        حفظ المسمى الجديد
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
