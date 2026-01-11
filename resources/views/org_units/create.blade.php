<x-app-layout title="إضافة وحدة تنظيمية">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <a href="{{ route('org_unit.index') }}" class="text-gray-500 hover:text-emerald-600 transition">
                <span class="material-icons">arrow_forward</span>
            </a>
            <span class="text-gray-500">إدارة الهيكل:</span>
            <span class="text-emerald-700">وحدة جديدة</span>
        </h1>
    </x-slot>

    <div class="p-6 bg-slate-50 min-h-screen flex justify-center">

        <div class="w-full max-w-4xl bg-white p-8 rounded-xl shadow-sm border border-gray-200">

            <div class="mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-800">إضافة وحدة تنظيمية جديدة</h2>
                <p class="text-gray-500 mt-1">أدخل بيانات الوحدة لإضافتها إلى الهيكل التنظيمي.</p>
            </div>

            <form action="{{ route('org_unit.store') }}" method="POST" class="space-y-6" x-data="unitForm">
                @csrf

                <div class="bg-blue-50 border-r-4 border-blue-500 p-4 rounded-lg mb-6 flex items-start gap-3">
                    <span class="material-icons text-blue-600 mt-0.5">auto_fix_high</span>
                    <div class="text-sm text-blue-800">
                        <p class="font-bold">نظام التكويد التلقائي</p>
                        <p>سيتم إنشاء <strong>رمز الوحدة (Unit Code)</strong> تلقائياً عند الحفظ بناءً على نوع الوحدة
                            والترتيب التسلسلي.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Unit Name --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم الوحدة التنظيمية <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" required x-model="name"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 p-3 transition"
                            placeholder="مثال: المديرية العامة للشؤون الإدارية والمالية">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">نوع المستوى الإداري <span
                                class="text-red-500">*</span></label>
                        <select name="type" required x-model="type"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 p-3 bg-white">
                            <option value="" disabled>-- اختر المستوى --</option>
                            <option value="Minister">وزير (Minister)</option>
                            <option value="Undersecretary">وكيل وزارة (Undersecretary)</option>
                            <option value="Directorate">مديرية عامة (Directorate)</option>
                            <option value="Department">دائرة (Department)</option>
                            <option value="Section">قسم (Section)</option>
                            <option value="Expert">خبير (Expert)</option>
                        </select>
                        @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Parent Unit --}}
                    <div>
                        @php
                            $parentOptions = $OrgUnits->map(function ($unit) {
                                return ['id' => $unit->id, 'name' => $unit->name, 'code' => $unit->unit_code];
                            })->values();
                        @endphp

                        {{-- Using x-model="parent" requires us to sync the component's internal state with the parent
                        alpine scope if needed.
                        However, our component uses a hidden input which works for standard form submission.
                        To update the 'preview' box, we need to listen for changes. --}}

                        <label class="block text-sm font-bold text-gray-700 mb-2">الوحدة الأم (يتبع لـ)</label>
                        <x-forms.searchable-select name="parent_id" :options="$parentOptions"
                            placeholder="(مستوى جذري / لا يوجد)" />
                        <p class="text-xs text-gray-400 mt-1">اتركه فارغاً إذا كان هذا هو المستوى الأعلى (مثل مكتب
                            الوزير)</p>

                        {{-- Bridge to update 'parent' in Alpine for preview (optional but nice) --}}
                        <div
                            x-init="$watch('$el.parentElement.querySelector(\'[name=parent_id]\').value', value => parent = value)">
                        </div>
                    </div>
                </div>

                {{-- Preview Box (Dynamic) --}}
                <div class="mt-8 border border-gray-100 rounded-xl p-6 bg-gray-50 flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">معاينة البطاقة</h4>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-xl bg-white border border-gray-200 shadow-sm flex items-center justify-center text-emerald-600">
                                <span class="material-icons">account_tree</span>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-lg transition" x-text="name || 'اسم الوحدة...'"
                                    :class="{'text-gray-400': !name}"></p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="text-xs font-mono bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded">CODE-001</span>
                                    <span
                                        class="text-[10px] uppercase text-emerald-600 font-bold bg-emerald-100 px-1.5 py-0.5 rounded"
                                        x-text="type || 'TYPE'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block opacity-50">
                        <span class="material-icons text-6xl text-gray-200">preview</span>
                    </div>
                </div>

                <div class="pt-6 border-t flex justify-end gap-3">
                    <a href="{{ route('org_unit.index') }}"
                        class="px-6 py-3 rounded-lg border border-gray-300 text-gray-600 font-bold hover:bg-gray-50 transition">إلغاء</a>
                    <button type="submit"
                        class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-bold shadow-lg hover:bg-emerald-700 hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center gap-2">
                        <span class="material-icons">save</span>
                        حفظ الوحدة
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('unitForm', () => ({
                name: '',
                type: '',
                parent: ''
            }))
        })
    </script>
</x-app-layout>