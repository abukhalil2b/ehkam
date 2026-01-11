<x-app-layout title="تعديل وحدة تنظيمية">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <a href="{{ route('org_unit.index') }}" class="text-gray-500 hover:text-emerald-600 transition">
                <span class="material-icons">arrow_forward</span>
            </a>
            <span class="text-gray-500">تعديل الوحدة:</span>
            <span class="text-emerald-700">{{ $orgUnit->name }}</span>
        </h1>
    </x-slot>

    <div class="p-6 bg-slate-50 min-h-screen" x-data="{ activeTab: 'info' }">
        <div class="max-w-5xl mx-auto">

            {{-- Tabs Header --}}
            <div class="flex gap-4 mb-6 border-b border-gray-200">
                <button @click="activeTab = 'info'"
                    :class="activeTab === 'info' ? 'border-emerald-500 text-emerald-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 border-b-2 transition flex items-center gap-2">
                    <span class="material-icons text-xl">info</span>
                    البيانات الأساسية
                </button>
                <button @click="activeTab = 'positions'"
                    :class="activeTab === 'positions' ? 'border-emerald-500 text-emerald-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 border-b-2 transition flex items-center gap-2 relative">
                    <span class="material-icons text-xl">work</span>
                    الوظائف المرتبطة
                    <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-0.5 rounded-full font-bold">
                        {{ $orgUnit->positions->count() }}
                    </span>
                </button>
            </div>

            {{-- TAB 1: BASIC INFO --}}
            <div x-show="activeTab === 'info'"
                x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-95"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <form action="{{ route('org_unit.update', $orgUnit->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Read-only Code --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-500 mb-1">رمز الوحدة (Unit Code)</label>
                                <div
                                    class="bg-gray-100 border border-gray-200 text-gray-600 px-4 py-3 rounded-lg font-mono">
                                    {{ $orgUnit->unit_code }}
                                </div>
                            </div>

                            {{-- Name --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">اسم الوحدة التنظيمية</label>
                                <input type="text" name="name" value="{{ old('name', $orgUnit->name) }}" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 p-3 transition"
                                    placeholder="مثال: المديرية العامة للشؤون الإدارية والمالية">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Type --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">نوع المستوى الإداري</label>
                                <select name="type" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 p-3 bg-white">
                                    @foreach(['Minister', 'Undersecretary', 'Directorate', 'Department', 'Section', 'Expert'] as $type)
                                        <option value="{{ $type }}" {{ old('type', $orgUnit->type) === $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Parent --}}
                            <div>
                                @php
                                    $parentOptions = $parentUnits->map(function ($unit) {
                                        return ['id' => $unit->id, 'name' => $unit->name, 'code' => $unit->unit_code];
                                    })->values();
                                @endphp

                                <label class="block text-sm font-bold text-gray-700 mb-2">الوحدة الأم (يتبع لـ)</label>
                                <x-forms.searchable-select name="parent_id" :options="$parentOptions"
                                    :selected="$orgUnit->parent_id" placeholder="(مستوى جذري / لا يوجد)" />
                                <p class="text-xs text-gray-400 mt-1">تغيير الوحدة الأم سيغير موقع هذه الوحدة في الهيكل
                                    التنظيمي.</p>
                                @error('parent_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="pt-6 border-t flex justify-end gap-3 mt-4">
                            <button type="submit"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-bold shadow-md transition flex items-center gap-2">
                                <span class="material-icons">save_as</span>
                                حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TAB 2: POSITIONS --}}
            <div x-show="activeTab === 'positions'" x-cloak
                x-transition:enter="transition ease-out duration-300 transform opacity-0"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Left Column: Add New Position --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                            <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                                <span class="material-icons text-emerald-600">add_circle</span>
                                إضافة وظيفة للوحدة
                            </h3>
                            <form action="{{ route('org_unit.positions.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="org_unit_id" value="{{ $orgUnit->id }}">
                                
                                @php
                                    $positionOptions = $allPositions->map(function($pos) {
                                        return ['id' => $pos->id, 'name' => $pos->title, 'code' => $pos->job_code];
                                    })->values();
                                @endphp

                                <label class="block text-sm font-bold text-gray-600 mb-2">الوظيفة (Job Title)</label>
                                <div class="mb-4">
                                    <x-forms.searchable-select 
                                        name="position_id" 
                                        :options="$positionOptions" 
                                        placeholder="-- اختر الوظيفة --" 
                                        :required="true"
                                    />
                                </div>

                                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-3 rounded-lg font-bold transition flex justify-center items-center gap-2">
                                    <span class="material-icons text-sm">link</span>
                                    ربط الوظيفة
                                </button>
                                <p class="text-xs text-gray-400 mt-3 text-center">
                                    لا تجد الوظيفة المطلوبة؟ <a href="{{ route('positions.index') }}" class="text-emerald-600 hover:underline">إدارة الوظائف</a>
                                </p>
                            </form>
                        </div>
                    </div>

                    {{-- Right Column: List of Attached Positions --}}
                    <div class="lg:col-span-2 space-y-4">
                        @if($orgUnit->positions->count() > 0)
                            @foreach($orgUnit->positions as $position)
                                <div
                                    class="bg-white rounded-xl border border-gray-200 p-5 flex justify-between items-start group hover:shadow-md transition">
                                    <div>
                                        <h4 class="font-bold text-lg text-gray-800 mb-1 flex items-center gap-2">
                                            {{ $position->title }}
                                            <span
                                                class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded font-mono">{{ $position->job_code }}</span>
                                        </h4>
                                        <div class="text-sm text-gray-500 space-y-1">
                                            <p class="flex items-center gap-1">
                                                <span class="material-icons text-sm text-gray-400">person</span>
                                                @if($position->currentEmployees->count() > 0)
                                                    <span class="text-emerald-600 font-bold">مشغول بواسطة:</span>
                                                    {{ $position->currentEmployees->first()->employee->name ?? 'موظف' }}
                                                @else
                                                    <span class="text-red-500 font-bold">شاغر (Vacant)</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <form action="{{ route('org_unit.positions.destroy') }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من فك ارتباط هذه الوظيفة من الوحدة؟');">
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="org_unit_id" value="{{ $orgUnit->id }}">
                                        <input type="hidden" name="position_id" value="{{ $position->id }}">
                                        <button type="submit"
                                            class="text-gray-300 hover:text-red-500 p-2 rounded-full hover:bg-red-50 transition"
                                            title="فك الارتباط">
                                            <span class="material-icons">link_off</span>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        @else
                            <div class="bg-white rounded-xl border-2 border-dashed border-gray-300 p-10 text-center">
                                <span class="material-icons text-6xl text-gray-200 mb-3">work_off</span>
                                <h3 class="text-gray-500 font-bold mb-1">لا توجد وظائف مرتبطة</h3>
                                <p class="text-gray-400 text-sm">استخدم النموذج لإضافة وظائف لهذه الوحدة التنظيمية.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>