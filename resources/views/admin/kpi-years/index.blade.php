<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة السنوات</title>
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
                        <h1 class="text-xl font-bold text-slate-800">إدارة السنوات</h1>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}" 
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">home</span>
                        <span>Dashboard</span>
                    </a>
                    {{-- Report Settings --}}
                    <a href="{{ route('statistic.settings') }}" 
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">tune</span>
                        <span>إعدادات التقرير</span>
                    </a>
                    {{-- KPI Indicators --}}
                    <a href="{{ route('statistic.kpi.indicators') }}" 
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">analytics</span>
                        <span>إدارة المؤشرات</span>
                    </a>
                    {{-- Years Management (Active) --}}
                    <a href="{{ route('kpi-years.index') }}" 
                        class="bg-blue-100 text-blue-700 shadow-inner px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2">
                        <span class="material-icons text-lg">calendar_today</span>
                        <span>إدارة السنوات</span>
                    </a>
                    {{-- View Report --}}
                    <a href="{{ route('statistic.bsc') }}" 
                        class="px-4 py-2.5 rounded-lg font-medium transition flex items-center gap-2 text-gray-600 hover:bg-gray-100">
                        <span class="material-icons text-lg">assessment</span>
                        <span>عرض التقرير</span>
                    </a>
                    {{-- Add Year --}}
                    <a href="{{ route('kpi-years.create') }}" 
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 shadow-md transition flex items-center gap-2">
                        <span class="material-icons text-lg">add</span>
                        <span>إضافة سنة</span>
                    </a>
                </div>
            </div>
        </nav>

        <main class="container mx-auto py-8 px-6">
            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                    <span class="material-icons text-green-500">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Message --}}
            <div id="error-message" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <span class="material-icons text-red-500">error</span>
                <span id="error-text"></span>
            </div>

            {{-- Years Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="text-right px-6 py-4 text-sm font-bold text-slate-600">الترتيب</th>
                                <th class="text-right px-6 py-4 text-sm font-bold text-slate-600">السنة</th>
                                <th class="text-right px-6 py-4 text-sm font-bold text-slate-600">الاسم</th>
                                <th class="text-right px-6 py-4 text-sm font-bold text-slate-600">الحالة</th>
                                <th class="text-center px-6 py-4 text-sm font-bold text-slate-600">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($years as $index => $year)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition" id="row-{{ $year->id }}">
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <div class="flex items-center gap-1">
                                            <span>{{ $year->display_order }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-blue-600">{{ $year->year }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $year->name }}</td>
                                    <td class="px-6 py-4">
                                        @if($year->is_active)
                                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs">نشط</span>
                                        @else
                                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs">غير نشط</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- Move Up Button --}}
                                            @if($index > 0)
                                                <button 
                                                    onclick="moveYear({{ $year->id }}, 'up')"
                                                    class="text-gray-600 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition"
                                                    title="تحريك للأعلى">
                                                    <span class="material-icons">arrow_upward</span>
                                                </button>
                                            @else
                                                <span class="material-icons text-gray-300 p-2">arrow_upward</span>
                                            @endif
                                            
                                            {{-- Move Down Button --}}
                                            @if($index < count($years) - 1)
                                                <button 
                                                    onclick="moveYear({{ $year->id }}, 'down')"
                                                    class="text-gray-600 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition"
                                                    title="تحريك للأسفل">
                                                    <span class="material-icons">arrow_downward</span>
                                                </button>
                                            @else
                                                <span class="material-icons text-gray-300 p-2">arrow_downward</span>
                                            @endif
                                            
                                            <a href="{{ route('kpi-years.edit', $year) }}" 
                                                class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition"
                                                title="تعديل">
                                                <span class="material-icons">edit</span>
                                            </a>
                                            <form action="{{ route('kpi-years.destroy', $year) }}" 
                                                method="POST" 
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذه السنة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition"
                                                    title="حذف">
                                                    <span class="material-icons">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <span class="material-icons text-6xl text-slate-300">inbox</span>
                                        <h3 class="text-xl font-bold text-slate-600 mt-4">لا توجد سنوات</h3>
                                        <p class="text-slate-500 mt-2">يرجى إضافة سنة جديدة للبدء</p>
                                        <a href="{{ route('kpi-years.create') }}" 
                                            class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                            <span class="material-icons">add</span>
                                            <span>إضافة سنة</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <footer class="bg-white border-t mt-12 py-6">
            <div class="container mx-auto px-6 text-center text-sm text-slate-500">
                <p>نظام إدارة المؤشرات الاستراتيجية</p>
            </div>
        </footer>
    </div>

    <script>
        function moveYear(id, direction) {
            const url = direction === 'up' 
                ? '{{ route('kpi-years.move-up', ':id') }}'.replace(':id', id)
                : '{{ route('kpi-years.move-down', ':id') }}'.replace(':id', id);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to reflect the new order
                    location.reload();
                } else {
                    showError('حدث خطأ أثناء إعادة الترتيب');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('حدث خطأ أثناء إعادة الترتيب');
            });
        }

        function showError(message) {
            const errorDiv = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');
            errorText.textContent = message;
            errorDiv.classList.remove('hidden');
            setTimeout(() => {
                errorDiv.classList.add('hidden');
            }, 3000);
        }
    </script>
</body>
</html>
