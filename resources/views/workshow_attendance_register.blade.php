<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الحضور في الورشة</title>
    <!-- Tailwind CSS CDN for quick setup -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts - Ensure Tajawal is loaded -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f7f9fb;
        }

        /* Style for better table readability on smaller screens */
        @media (max-width: 640px) {

            .attendance-table th,
            .attendance-table td {
                padding: 8px 10px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <div class="max-w-4xl mx-auto p-4 md:p-8">
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-blue-800">
                {{ $workshop->title }}
            </h1>
            <div class="flex justify-center p-2">
                {!! $qrImage !!}
            </div>
            رابط التسجيل
        </div>

        {{-- Session Messages (Success, Warning, Error) --}}
        @if (session('success') || session('warning') || session('error'))
            @php
                $type = session('success') ? 'success' : (session('warning') ? 'warning' : 'error');
                $message = session($type);
                $color = [
                    'success' => 'bg-green-100 border-green-400 text-green-700',
                    'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
                    'error' => 'bg-red-100 border-red-400 text-red-700',
                ][$type];
            @endphp
            <div class="{{ $color }} border-r-4 p-4 rounded-lg mb-6 shadow-md" role="alert">
                <p class="font-bold">
                    @if ($type === 'success')
                        نجاح
                    @elseif($type === 'warning')
                        تنبيه
                    @else
                        خطأ
                    @endif
                </p>
                <p>{{ $message }}</p>
            </div>
        @endif

        {{-- Workshop Info Card --}}
        @if ($workshop)
            <div class="bg-white p-6 rounded-xl shadow-lg border border-blue-100 mb-8">
                <h2 class="text-xl font-bold text-blue-700 mb-4">{{ $workshop->title }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-gray-600">
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>التاريخ: {{ \Carbon\Carbon::parse($workshop->created_at)->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l3 3a1 1 0 001.414-1.414L10 10V6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>الوقت: {{ \Carbon\Carbon::parse($workshop->created_at)->format('H:i') }}</span>
                    </div>
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>المكان: {{ $workshop->place ?? 'غير محدد' }}</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Content Wrapper (Registration Form & Attendance List) --}}
        @if ($workshop)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Registration Form Card --}}
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">سجل حضورك</h2>

                    <p class="text-gray-500 mb-6">يرجى تعبئة البيانات التالية لتسجيل حضورك في الورشة.</p>

                    <form method="POST" action="{{ route('workshow_attendance_register') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                placeholder="أدخل اسمك الكامل">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="job_title" class="block text-sm font-medium text-gray-700 mb-1">المسمى الوظيفي
                                (اختياري)</label>
                            <input type="text" id="job_title" name="job_title" value="{{ old('job_title') }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="mb-6">
                            <label for="department"
                                class="block text-sm font-medium text-gray-700 mb-1">القسم/الدائرة/المديرية
                                (اختياري)</label>
                            <input type="text" id="department" name="department" value="{{ old('department') }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold text-lg hover:bg-blue-700 transition duration-200 shadow-md">
                            تأكيد تسجيل الحضور
                        </button>
                    </form>
                </div>

                {{-- Attendance List Card --}}
                <div class="bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">قائمة الحضور ({{ $attendances->count() }})
                    </h2>

                    @if ($attendances->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            <p>لم يتم تسجيل أي حضور بعد. كن أول من يسجل!</p>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200 attendance-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الاسم
                                    </th>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                        المسمى الوظيفي
                                    </th>
                                    <th
                                        class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        وقت التسجيل
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $attendance->name }}
                                        </td>
                                        <td
                                            class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                            {{ $attendance->job_title ?? '-' }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($attendance->created_at)->format('H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @else
            {{-- Message if no active workshop --}}
            <div
                class="bg-yellow-100 border-r-4 border-yellow-500 p-6 rounded-xl shadow-lg text-yellow-800 text-center text-xl font-medium">
                {{ session('warning') ?? 'نعتذر، لا توجد ورشة عمل نشطة حاليًا لتسجيل الحضور.' }}
            </div>
        @endif
    </div>
</body>

</html>
