<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($kpiYear) ? 'تعديل السنة' : 'إضافة سنة جديدة' }}</title>
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
                        <span class="material-icons">calendar_today</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">{{ isset($kpiYear) ? 'تعديل السنة' : 'إضافة سنة جديدة' }}</h1>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('kpi-years.index') }}" 
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">arrow_back</span>
                        <span>العودة</span>
                    </a>
                </div>
            </div>
        </nav>

        <main class="container mx-auto py-8 px-6">
            <form action="{{ isset($kpiYear) ? route('kpi-years.update', $kpiYear) : route('kpi-years.store') }}" 
                method="POST" 
                class="max-w-xl mx-auto">
                @csrf
                @if(isset($kpiYear))
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
                        {{-- Year --}}
                        <div>
                            <label for="year" class="block text-sm font-bold text-slate-700 mb-2">
                                <span class="material-icons text-sm align-middle">calendar_today</span>
                                السنة <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                name="year" 
                                id="year" 
                                value="{{ old('year', $kpiYear->year ?? date('Y')) }}"
                                class="w-full border border-slate-200 rounded-lg p-3 text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="مثال: 2024"
                                min="2000"
                                max="2100"
                                required>
                        </div>

                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-2">
                                <span class="material-icons text-sm align-middle">title</span>
                                الاسم <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name', $kpiYear->name ?? 'سنة ' . date('Y')) }}"
                                class="w-full border border-slate-200 rounded-lg p-3 text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                placeholder="مثال: سنة 2024"
                                required>
                        </div>

                        {{-- Is Active --}}
                        <div class="flex items-center gap-3">
                            <input type="checkbox" 
                                name="is_active" 
                                id="is_active" 
                                value="1"
                                {{ old('is_active', isset($kpiYear) ? $kpiYear->is_active : true) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                            <label for="is_active" class="text-sm font-medium text-slate-700">السنة نشطة</label>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                        <a href="{{ route('kpi-years.index') }}" 
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
</body>
</html>
