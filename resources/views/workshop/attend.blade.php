<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الحضور - {{ $workshop->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f3f4f6;
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .input-group:focus-within label {
            color: #2563eb;
        }

        .input-group:focus-within input {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
    </style>
</head>

<body class="min-h-screen py-6 px-4 flex flex-col justify-center sm:py-12">

    <div class="relative sm:max-w-md w-full mx-auto">

        {{-- Background blobs for decoration --}}
        <div
            class="absolute top-0 -right-4 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob">
        </div>
        <div
            class="absolute top-0 -left-4 w-72 h-72 bg-teal-300 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob animation-delay-2000">
        </div>

        <div class="relative bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">

            {{-- Header Image / Logic --}}
            <div class="bg-gradient-to-br from-blue-600 to-teal-500 p-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 opacity-30 pattern-dots"></div>

                {{-- Logo Placeholder --}}
                <div
                    class="relative z-10 w-20 h-20 bg-white rounded-2xl mx-auto flex items-center justify-center shadow-lg mb-4 transform -rotate-3">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h1 class="relative z-10 text-2xl font-extrabold text-white mb-1">تسجيل الحضور</h1>
                <p class="relative z-10 text-blue-50 text-sm font-medium">نظام إدارة الورش - وزارة الأوقاف</p>
            </div>

            {{-- Workshop Info Badge --}}
            <div class="px-6 -mt-6 relative z-20">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 text-center">
                    <h2 class="text-gray-800 font-bold text-lg mb-2 line-clamp-2">{{ $workshop->title }}</h2>

                    <div class="flex flex-wrap justify-center gap-2 text-xs font-medium text-gray-500">
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-lg flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $day->day_date->format('Y-m-d') }}
                        </span>

                        @if($day->label)
                            <span class="bg-teal-50 text-teal-700 px-2 py-1 rounded-lg flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $day->label }}
                            </span>
                        @endif

                        @if($workshop->location)
                            <span class="bg-orange-50 text-orange-700 px-2 py-1 rounded-lg flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ Str::limit($workshop->location, 20) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="p-6 pt-4">

                {{-- Messages --}}
                @if(session('success'))
                    <div
                        class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3 animate-fade-in-up">
                        <svg class="w-6 h-6 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="text-green-800 font-bold text-sm">تم التسجيل بنجاح!</h3>
                            <p class="text-green-700 text-sm mt-1">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div
                        class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 animate-fade-in-up">
                        <svg class="w-6 h-6 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h3 class="text-red-800 font-bold text-sm">تنبيه</h3>
                            <p class="text-red-700 text-sm mt-1">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('workshop.attend', $day->attendance_hash) }}" class="space-y-5">
                    @csrf

                    {{-- Name Input --}}
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5 transition-colors">اسمك الكريم
                            (ثلاثي) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute right-4 top-3.5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input type="text" name="name"
                                value="{{ old('name', \Illuminate\Support\Facades\Cookie::get('attendee_name')) }}"
                                required
                                class="w-full pr-11 pl-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white transition-all outline-none"
                                placeholder="اكتب اسمك هنا...">
                        </div>
                        @error('name')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Job Title Input --}}
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5 transition-colors">المسمى
                            الوظيفي</label>
                        <div class="relative">
                            <span class="absolute right-4 top-3.5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input type="text" name="job_title"
                                value="{{ old('job_title', \Illuminate\Support\Facades\Cookie::get('attendee_job')) }}"
                                class="w-full pr-11 pl-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white transition-all outline-none"
                                placeholder="مثال: إمام، موظف إداري...">
                        </div>
                    </div>

                    {{-- Department Input --}}
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5 transition-colors">الجهة /
                            الدائرة</label>
                        <div class="relative">
                            <span class="absolute right-4 top-3.5 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </span>
                            <input type="text" name="department"
                                value="{{ old('department', \Illuminate\Support\Facades\Cookie::get('attendee_dept')) }}"
                                class="w-full pr-11 pl-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white transition-all outline-none"
                                placeholder="مثال: دائرة المساجد...">
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 active:shadow-md transition-all duration-200 flex items-center justify-center gap-2 group">
                        <span>تأكيد الحضور</span>
                        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>
                </form>

            </div>

            {{-- Footer Area --}}
            <div class="bg-gray-50 p-6 border-t border-gray-100 text-center">
                @if(isset($qrImage))
                    <p class="text-gray-500 mb-3">تسجيل الحضور</p>
                    <div class="inline-block bg-white p-2 border border-gray-200 rounded-lg shadow-sm">
                        {!! $qrImage !!}
                    </div>
                @endif
                <p class="text-xs text-gray-400 mt-4">دائرة التخطيط والإحصاء</p>
            </div>

        </div>

    </div>

</body>

</html>