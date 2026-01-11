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
            <h3 class="text-xl font-bold mb-4 text-purple-700 border-b pb-2">إضافة مسمى وظيفي جديد</h3>
            
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
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الوحدة التنظيمية التابع لها</label>
                    <div class="relative">
                        <select name="org_unit_id" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 transition appearance-none">
                            <option value="" disabled selected>-- اختر الوحدة التنظيمية --</option>
                            @foreach ($allUnits as $unit)
                                <option value="{{ $unit->id }}" {{ old('org_unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} ({{ $unit->type }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-2 text-gray-700 rtl:right-unset rtl:left-0">
                             <span class="material-icons text-gray-500">expand_more</span>
                        </div>
                    </div>
                    @error('org_unit_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Reports To --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">يتبع إدارياً لـ (الرئيس المباشر)</label>
                    <div class="relative">
                        <select name="reports_to_position_id" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 transition">
                            <option value="">(لا يوجد - وظيفة قيادية عليا)</option>
                            @php
                                function renderRecursiveOptions($nodes, $prefix = '') {
                                    foreach ($nodes as $node) {
                                        echo '<option value="'.$node->id.'">'.$prefix.' '.$node->title.'</option>';
                                        if ($node->subordinates->count()) {
                                            renderRecursiveOptions($node->subordinates, $prefix . '-- ');
                                        }
                                    }
                                }
                                renderRecursiveOptions($topLevelPositions); 
                            @endphp
                        </select>
                    </div>
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
