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
                                    $positionOptions = $availablePositions->map(function($pos) {
                                        return ['id' => $pos->id, 'name' => $pos->title, 'code' => $pos->job_code];
                                    })->values();
                                @endphp

                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <span class="material-icons text-sm align-middle">work</span>
                                    الوظيفة (Job Title)
                                </label>
                                <div class="mb-4">
                                    @if($availablePositions->count() > 0)
                                        <x-forms.searchable-select 
                                            name="position_id" 
                                            :options="$positionOptions" 
                                            placeholder="-- ابحث واختر الوظيفة --" 
                                            :required="true"
                                        />
                                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                            <span class="material-icons text-xs">info</span>
                                            متاح {{ $availablePositions->count() }} وظيفة للربط
                                        </p>
                                    @else
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                                            <span class="material-icons text-gray-400 text-2xl mb-2">check_circle</span>
                                            <p class="text-sm text-gray-600 font-semibold">جميع الوظائف مرتبطة بالفعل</p>
                                            <p class="text-xs text-gray-500 mt-1">لا توجد وظائف متاحة للربط</p>
                                        </div>
                                    @endif
                                </div>

                                @if($availablePositions->count() > 0)
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-lg font-bold transition transform hover:scale-[1.02] flex justify-center items-center gap-2 shadow-md">
                                        <span class="material-icons text-sm">link</span>
                                        ربط الوظيفة
                                    </button>
                                @endif
                                <p class="text-xs text-gray-400 mt-3 text-center">
                                    لا تجد الوظيفة المطلوبة؟ <a href="{{ route('positions.index') }}" class="text-emerald-600 hover:underline font-semibold">إدارة الوظائف</a>
                                </p>
                            </form>
                        </div>
                    </div>

                    {{-- Right Column: List of Attached Positions --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                                    <span class="material-icons text-emerald-600">list</span>
                                    الوظائف المرتبطة
                                </h3>
                                <span class="bg-emerald-100 text-emerald-700 text-sm px-3 py-1 rounded-full font-bold">
                                    {{ $orgUnit->positions->count() }} وظيفة
                                </span>
                            </div>

                            @if($orgUnit->positions->count() > 0)
                                <div class="space-y-3">
                                    @foreach($orgUnit->positions as $position)
                                        <div
                                            class="bg-gray-50 rounded-lg border border-gray-200 p-4 flex justify-between items-start group hover:bg-white hover:shadow-sm transition-all duration-200">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h4 class="font-bold text-base text-gray-800">
                                                        {{ $position->title }}
                                                    </h4>
                                                    @if($position->job_code)
                                                        <span
                                                            class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded font-mono">
                                                            {{ $position->job_code }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @php
                                                    // فلترة الموظفين حسب الوحدة التنظيمية الحالية
                                                    $employeeInThisUnit = $position->currentEmployees
                                                        ->where('org_unit_id', $orgUnit->id)
                                                        ->first();
                                                @endphp
                                                <div class="flex items-center gap-4 text-sm">
                                                    <div class="flex items-center gap-1">
                                                        <span class="material-icons text-sm text-gray-400">person</span>
                                                        @if($employeeInThisUnit)
                                                            <span class="text-emerald-600 font-semibold">مشغول:</span>
                                                            <span class="text-gray-700">
                                                                {{ $employeeInThisUnit->user->name ?? 'موظف' }}
                                                            </span>
                                                        @else
                                                            <span class="text-red-500 font-semibold">شاغر</span>
                                                        @endif
                                                    </div>
                                                    @if($employeeInThisUnit)
                                                        <div class="flex items-center gap-1">
                                                            <span class="material-icons text-sm text-emerald-500">check_circle</span>
                                                            <span class="text-emerald-600 text-xs">نشط</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <form action="{{ route('org_unit.positions.destroy') }}" method="POST"
                                                onsubmit="return confirm('هل أنت متأكد من فك ارتباط الوظيفة \"{{ $position->title }}\" من هذه الوحدة؟');"
                                                class="ml-4">
                                                @csrf @method('DELETE')
                                                <input type="hidden" name="org_unit_id" value="{{ $orgUnit->id }}">
                                                <input type="hidden" name="position_id" value="{{ $position->id }}">
                                                <button type="submit"
                                                    class="text-gray-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition-all duration-200 group"
                                                    title="فك الارتباط">
                                                    <span class="material-icons text-lg group-hover:scale-110 transition-transform">link_off</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                                    <span class="material-icons text-6xl text-gray-300 mb-4 block">work_off</span>
                                    <h3 class="text-gray-600 font-bold text-lg mb-2">لا توجد وظائف مرتبطة</h3>
                                    <p class="text-gray-500 text-sm mb-4">استخدم النموذج على اليسار لإضافة وظائف لهذه الوحدة التنظيمية.</p>
                                    @if($availablePositions->count() > 0)
                                        <a href="{{ route('positions.index') }}" 
                                           class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold text-sm">
                                            <span class="material-icons text-sm">add_circle</span>
                                            إضافة وظيفة جديدة
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>