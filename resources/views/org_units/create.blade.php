<x-app-layout title="إضافة وحدة تنظيمية">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <a href="{{ route('org_unit.index') }}" class="text-gray-500 hover:text-emerald-600 transition">
                <span class="material-icons">arrow_forward</span>
            </a>
            <span>إضافة وحدة تنظيمية جديدة</span>
        </h1>
    </x-slot>

    <div class="p-6 bg-gray-50 min-h-screen flex justify-center">

        <div class="w-full max-w-3xl bg-white p-8 rounded-xl border shadow-lg">

            <form action="{{ route('org_unit.store') }}" method="POST" class="space-y-6" x-data="unitForm">
                @csrf

                <div class="bg-emerald-50 border-r-4 border-emerald-500 p-4 rounded-lg mb-6 flex items-start gap-3">
                    <span class="material-icons text-emerald-600 mt-0.5">info</span>
                    <div class="text-sm text-emerald-800">
                        <p class="font-bold">نظام التكويد التلقائي</p>
                        <p>سيتم إنشاء رمز الوحدة (Unit Code) تلقائياً عند الحفظ بناءً على نوع الوحدة المختار والعدد
                            التسلسلي.</p>
                    </div>
                </div>

                {{-- Unit Name --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">اسم الوحدة التنظيمية</label>
                    <input type="text" name="name" required x-model="name"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 p-3 transition"
                        placeholder="مثال: المديرية العامة للشؤون الإدارية والمالية">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نوع المستوى الإداري</label>
                        <div class="relative">
                            <select name="type" required x-model="type"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 p-3 appearance-none bg-white">
                                <option value="" disabled>-- اختر المستوى --</option>
                                <option value="Minister">وزير (Minister)</option>
                                <option value="Undersecretary">وكيل وزارة (Undersecretary)</option>
                                <option value="Directorate">مديرية عامة (Directorate)</option>
                                <option value="Department">دائرة (Department)</option>
                                <option value="Section">قسم (Section)</option>
                                <option value="Expert">خبير (Expert)</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500 rtl:right-unset rtl:left-0">
                                <span class="material-icons">expand_more</span>
                            </div>
                        </div>
                        @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Parent Unit --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">الوحدة الأم (يتبع لـ)</label>
                        <div class="relative">
                            <select name="parent_id" x-model="parent"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500 p-3 appearance-none bg-white">
                                <option value="">(مستوى جذري / لا يوجد)</option>
                                @foreach($OrgUnits as $unit)
                                    <option value="{{ $unit->id }}">
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500 rtl:right-unset rtl:left-0">
                                <span class="material-icons">expand_more</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">اتركه فارغاً إذا كان هذا هو المستوى الأعلى (مثل مكتب
                            الوزير)</p>
                    </div>
                </div>

                {{-- Preview Box (Dynamic) --}}
                <div class="mt-8 border border-gray-100 rounded-xl p-4 bg-gray-50">
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">معاينة الهيكلية</h4>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <span class="material-icons">domain</span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-lg" x-text="name || 'اسم الوحدة...'"></p>
                            <p class="text-sm text-gray-500">
                                <span x-text="type || 'النوع'"></span>
                                <span x-show="parent" class="mx-1">•</span>
                                <span x-show="parent">يتبع <span class="font-semibold text-gray-700">للوحدة
                                        المختارة</span></span>
                            </p>
                        </div>
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