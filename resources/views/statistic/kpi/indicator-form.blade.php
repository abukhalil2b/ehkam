<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($indicator) ? 'تعديل المؤشر' : 'إضافة مؤشر جديد' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 font-sans text-gray-900 min-h-screen">

    <div x-data="">
        {{-- Navigation --}}
        <nav class="bg-white border-b sticky top-0 z-50 shadow-sm">
            <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white p-2.5 rounded-xl font-bold shadow-md">
                        <span class="material-icons">analytics</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">{{ isset($indicator) ? 'تعديل المؤشر' : 'إضافة مؤشر جديد' }}</h1>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('statistic.kpi.indicators') }}" 
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">arrow_back</span>
                        <span>العودة</span>
                    </a>
                </div>
            </div>
        </nav>

        <main class="container mx-auto py-8 px-6">
            <form action="{{ isset($indicator) ? route('statistic.kpi.indicator.update', $indicator) : route('statistic.kpi.indicator.store') }}" 
                method="POST" 
                class="max-w-2xl mx-auto">
                @csrf
                @if(isset($indicator))
                    @method('PUT')
                @endif

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 space-y-6">
                        {{-- Code --}}
                        <div>
                            <label for="code" class="block text-sm font-bold text-slate-700 mb-2">
                                <span class="material-icons text-sm align-middle">code</span>
                                الرمز <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                name="code" 
                                id="code" 
                                value="{{ old('code', $indicator->code ?? '') }}"
                                class="w-full border border-slate-200 rounded-lg p-3 text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="مثال: KPI-001"
                                required>
                        </div>

                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-bold text-slate-700 mb-2">
                                <span class="material-icons text-sm align-middle">title</span>
                                عنوان المؤشر <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                name="title" 
                                id="title" 
                                value="{{ old('title', $indicator->title ?? '') }}"
                                class="w-full border border-slate-200 rounded-lg p-3 text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="أدخل عنوان المؤشر"
                                required>
                        </div>

                        {{-- Unit --}}
                        <div>
                            <label for="unit" class="block text-sm font-bold text-slate-700 mb-2">
                                <span class="material-icons text-sm align-middle">straighten</span>
                                الوحدة <span class="text-red-500">*</span>
                            </label>
                            <select name="unit" 
                                id="unit" 
                                class="w-full border border-slate-200 rounded-lg p-3 text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                required>
                                <option value="">اختر الوحدة</option>
                                <option value="number" {{ old('unit', $indicator->unit ?? '') == 'number' ? 'selected' : '' }}>رقم</option>
                                <option value="percentage" {{ old('unit', $indicator->unit ?? '') == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                                <option value="currency" {{ old('unit', $indicator->unit ?? '') == 'currency' ? 'selected' : '' }}>عملة</option>
                            </select>
                        </div>

                        {{-- Currency --}}
                        <div id="currency-field" class="{{ old('unit', $indicator->unit ?? '') != 'currency' ? 'hidden' : '' }}">
                            <label for="currency" class="block text-sm font-bold text-slate-700 mb-2">
                                <span class="material-icons text-sm align-middle">attach_money</span>
                                العملة
                            </label>
                            <input type="text" 
                                name="currency" 
                                id="currency" 
                                value="{{ old('currency', $indicator->currency ?? '') }}"
                                class="w-full border border-slate-200 rounded-lg p-3 text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="مثال: ر.ع."
                                maxlength="10">
                        </div>

                        {{-- Category --}}
                        <div>
                            <label for="category" class="block text-sm font-bold text-slate-700 mb-2">
                                <span class="material-icons text-sm align-middle">category</span>
                                الفئة
                            </label>
                            <input type="text" 
                                name="category" 
                                id="category" 
                                value="{{ old('category', $indicator->category ?? '') }}"
                                class="w-full border border-slate-200 rounded-lg p-3 text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="مثال: مؤشرات مالية">
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-bold text-slate-700 mb-2">
                                <span class="material-icons text-sm align-middle">description</span>
                                الوصف
                            </label>
                            <textarea 
                                name="description" 
                                id="description" 
                                rows="3"
                                class="w-full border border-slate-200 rounded-lg p-3 text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
                                placeholder="أدخل وصف المؤشر">{{ old('description', $indicator->description ?? '') }}</textarea>
                        </div>

                        {{-- Is Active --}}
                        @if(isset($indicator))
                        <div class="flex items-center gap-3">
                            <input type="checkbox" 
                                name="is_active" 
                                id="is_active" 
                                value="1"
                                {{ $indicator->is_active ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                            <label for="is_active" class="text-sm font-medium text-slate-700">المؤشر نشط</label>
                        </div>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                        <a href="{{ route('statistic.kpi.indicators') }}" 
                            class="px-5 py-2.5 rounded-lg font-medium text-slate-600 hover:bg-slate-200 transition">
                            إلغاء
                        </a>
                        <button type="submit" 
                            class="px-5 py-2.5 rounded-lg font-medium text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-2">
                            <span class="material-icons">save</span>
                            <span>حفظ</span>
                        </button>
                    </div>
                </div>
            </form>
        </main>

        <footer class="bg-white border-t mt-12 py-6">
            <div class="container mx-auto px-6 text-center text-sm text-slate-500">
                <p>نظام إدارة المؤشرات الاستراتيجية</p>
            </div>
        </footer>
    </div>

    <script>
        // Show/hide currency field based on unit selection
        document.getElementById('unit').addEventListener('change', function() {
            const currencyField = document.getElementById('currency-field');
            if (this.value === 'currency') {
                currencyField.classList.remove('hidden');
            } else {
                currencyField.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
