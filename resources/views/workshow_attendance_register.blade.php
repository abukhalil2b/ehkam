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
            background: linear-gradient(135deg, #f7f9fb 0%, #eef2f7 100%);
            min-height: 100vh;
        }

     

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        @media (max-width: 640px) {
            .attendance-table th,
            .attendance-table td {
                padding: 8px 10px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-blue-800 text-white py-8 shadow-lg">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-2xl font-extrabold mb-1">
               المديرية العامة للتخطيط والدراسات
            </h1>
           <h2 class="text-xl font-extrabold mb-1"> وزارة الأوقاف والشؤون الدينية</h2>
            @if($workshop)
                <p class="text-xl opacity-90">{{ $workshop->title }}</p>
            @endif
        </div>
    </div>

    <div class="max-w-6xl mx-auto p-4 md:p-8 -mt-6">
        {{-- QR Code Section --}}
        @if($workshop && $qrImage)
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 card-hover text-center">
                <div class="flex flex-col items-center">
                    <div class="bg-white p-4 rounded-xl shadow-inner border-2 border-blue-200 inline-block">
                        {!! $qrImage !!}
                    </div>
                    <p class="mt-4 text-gray-600 font-medium flex items-center justify-center">
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        مسح الكود لتسجيل الحضور
                    </p>
                </div>
            </div>
        @endif

        {{-- Session Messages --}}
        @if (session('success') || session('warning') || session('error'))
            @php
                $type = session('success') ? 'success' : (session('warning') ? 'warning' : 'error');
                $message = session($type);
                $colors = [
                    'success' => 'bg-green-50 border-green-200 text-green-800',
                    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                    'error' => 'bg-red-50 border-red-200 text-red-800',
                ];
                $icons = [
                    'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                    'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>',
                    'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                ];
            @endphp
            <div class="{{ $colors[$type] }} border-r-4 p-4 rounded-xl mb-6 shadow-md flex items-start" role="alert">
                <svg class="w-6 h-6 ml-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $icons[$type] !!}
                </svg>
                <div>
                    <p class="font-bold">
                        @if ($type === 'success')
                            نجاح
                        @elseif($type === 'warning')
                            تنبيه
                        @else
                            خطأ
                        @endif
                    </p>
                    <p class="mt-1">{{ $message }}</p>
                </div>
            </div>
        @endif

        {{-- Workshop Info Card --}}
        @if ($workshop)
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-blue-100 mb-8 card-hover">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 text-blue-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h2 class="text-xl font-bold text-blue-700">معلومات الورشة</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-gray-600">
                    <div class="flex items-center space-x-2 space-x-reverse bg-blue-50 p-3 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-500">تاريخ البدء</p>
                            <p class="font-semibold">{{ $workshop->starts_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 space-x-reverse bg-green-50 p-3 rounded-lg">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-500">وقت البدء</p>
                            <p class="font-semibold">{{ $workshop->starts_at->format('H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 space-x-reverse bg-purple-50 p-3 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-500">المكان</p>
                            <p class="font-semibold">{{ $workshop->location ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                    
                    @if($workshop->ends_at)
                    <div class="flex items-center space-x-2 space-x-reverse bg-orange-50 p-3 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-500">تاريخ الانتهاء</p>
                            <p class="font-semibold">{{ $workshop->ends_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                
                @if($workshop->description)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-gray-600"><strong>الوصف:</strong> {{ $workshop->description }}</p>
                </div>
                @endif
            </div>

            {{-- Main Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Registration Form --}}
                <div class="bg-white p-6 rounded-2xl shadow-xl card-hover">
                    <div class="flex items-center mb-6">
                        <svg class="w-6 h-6 text-green-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <h2 class="text-2xl font-semibold text-gray-800">سجل حضورك</h2>
                    </div>

                    <p class="text-gray-500 mb-6">يرجى تعبئة البيانات التالية لتسجيل حضورك في الورشة.</p>

                    <form method="POST" action="{{ route('workshow_attendance_register') }}">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    الاسم الكامل
                                    <span class="text-red-500 mr-1">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('name') border-red-500 @enderror"
                                    placeholder="أدخل اسمك الكامل">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-2 flex items-center">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="job_title" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    المسمى الوظيفي (اختياري)
                                </label>
                                <input type="text" id="job_title" name="job_title" value="{{ old('job_title') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                    placeholder="المسمى الوظيفي">
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    القسم/الدائرة/المديرية (اختياري)
                                </label>
                                <input type="text" id="department" name="department" value="{{ old('department') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                    placeholder="القسم أو الدائرة">
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-blue-800 transition duration-200 shadow-lg mt-6 flex items-center justify-center">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                             تسجيل الحضور
                        </button>
                    </form>
                </div>

                {{-- Attendance List --}}
                <div class="bg-white p-6 rounded-2xl shadow-xl card-hover">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-purple-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h2 class="text-2xl font-semibold text-gray-800">قائمة الحضور</h2>
                        </div>
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $attendances->count() }}
                        </span>
                    </div>

                    @if ($attendances->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg">لم يتم تسجيل أي حضور بعد</p>
                            <p class="text-sm mt-2">كن أول من يسجل!</p>
                        </div>
                    @else
                        <div class="overflow-hidden rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200 attendance-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                الاسم
                                            </div>
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                المسمى الوظيفي
                                            </div>
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                وقت التسجيل
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($attendances as $attendance)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="bg-blue-100 rounded-full p-2 ml-3">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $attendance->attendee_name }}</div>
                                                        <div class="text-sm text-gray-500 md:hidden">{{ $attendance->job_title ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                                {{ $attendance->job_title ?? '-' }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $attendance->created_at->format('H:i') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- No Active Workshop --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center card-hover">
                <div class="max-w-md mx-auto">
                    <svg class="w-24 h-24 mx-auto text-yellow-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">لا توجد ورشة عمل نشطة</h2>
                    <p class="text-gray-600 mb-6 text-lg">
                        {{ session('warning') ?? 'نعتذر، لا توجد ورشة عمل نشطة حاليًا لتسجيل الحضور.' }}
                    </p>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-800">
                        <p class="flex items-center justify-center">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            يرجى التواصل مع المسؤول لتفعيل ورشة العمل
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>

</html>