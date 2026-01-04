<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة حدث جديد | {{ $year }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @vite('resources/js/app.js')

    <style>
        body { font-family: 'Cairo', sans-serif; }
        .islamic-pattern {
            background-color: #064e3b;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 30L15 45L0 30L15 15L30 30zm30 0L45 45L30 30L45 15L60 30zM15 15L0 0h30L15 15zm30 0L30 0h30L45 15zM15 45L0 60h30L15 45zm30 0L30 60h30L45 45z' fill='%23065f46' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <header class="islamic-pattern text-white py-12 shadow-xl mb-10">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl font-bold mb-2">إضافة نشاط جديد</h1>
            <p class="text-xl opacity-90">التقويم السنوي لعام {{ $year }} م</p>
        </div>
    </header>

    <main class="container mx-auto px-4 pb-20">
        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-emerald-800 p-4 text-white text-center font-bold">
               حدث جديد
            </div>

            <form method="POST" action="{{ route('calendar.store') }}" class="p-8 space-y-6">
                @csrf

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">عنوان الحدث</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-0 focus:border-emerald-500 transition shadow-sm" 
                        placeholder="مثلاً: حفل تكريم الحفظة..." required>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">نوع الحدث</label>
                        <select name="type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-0 focus:border-emerald-500 bg-white" required>
                            <option value="program" {{ old('type') == 'program' ? 'selected' : '' }}>برنامج</option>
                            <option value="meeting" {{ old('type') == 'meeting' ? 'selected' : '' }}>اجتماع</option>
                            <option value="conference" {{ old('type') == 'conference' ? 'selected' : '' }}>مؤتمر</option>
                            <option value="competition" {{ old('type') == 'competition' ? 'selected' : '' }}>مسابقة</option>
                        </select>
                    </div>

                    {{-- Program Metadata --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">البرنامج (اختياري)</label>
                        <input type="text" name="program" value="{{ old('program') }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-0 focus:border-emerald-500 transition shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Start Date --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ البداية</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-0 focus:border-emerald-500" required>
                    </div>

                    {{-- End Date --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ النهاية</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-0 focus:border-emerald-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    {{-- Color --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">لون الحدث</label>
                        <div class="flex items-center gap-3 bg-gray-50 border-2 border-gray-200 rounded-xl p-2">
                            <input type="color" name="bg_color" value="{{ old('bg_color', '#16a34a') }}"
                                class="h-10 w-20 cursor-pointer bg-transparent border-0">
                            <span class="text-xs text-gray-500">اختر لوناً للتمييز</span>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ملاحظات إضافية</label>
                    <textarea name="notes" rows="4" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-0 focus:border-emerald-500" 
                        placeholder="تفاصيل النشاط أو البرنامج...">{{ old('notes') }}</textarea>
                </div>

                <hr class="border-gray-100 my-6">

                {{-- Actions --}}
                <div class="flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-emerald-700 text-white font-bold py-4 rounded-xl hover:bg-emerald-800 transition shadow-lg text-lg">
                        حفظ النشاط في التقويم
                    </button>
                    <a href="{{ route('calendar.index', ['year' => $year]) }}" class="flex-1 bg-gray-100 text-gray-600 font-bold py-4 rounded-xl hover:bg-gray-200 transition text-center text-lg">
                        إلغاء والعودة
                    </a>
                </div>
            </form>
        </div>
    </main>
    <div class="flex py-6">
        <a href="/dashboard" class="text-orange-600 px-6 py-3 rounded-xl font-bold">خروج</a>
    </div>
</body>
</html>