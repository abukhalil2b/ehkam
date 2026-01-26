<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($event) ? 'تعديل حدث' : 'إضافة حدث جديد' }} | {{ $year }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        .islamic-pattern {
            background-color: #064e3b;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 30L15 45L0 30L15 15L30 30zm30 0L45 45L30 30L45 15L60 30zM15 15L0 0h30L15 15zm30 0L30 0h30L45 15zM15 45L0 60h30L15 45zm30 0L30 60h30L45 45z' fill='%23065f46' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        [x-cloak] {
            display: none !important;
        }

        .progress-step {
            transition: all 0.3s ease;
        }

        .progress-step.active {
            transform: scale(1.1);
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-[#060818] min-h-screen" x-data="eventForm()"
    :class="{ 'dark': $store.app.theme === 'dark' || $store.app.isDarkMode }">

    <header class="islamic-pattern text-white py-10 shadow-xl mb-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-right">
                    <h1 class="text-3xl font-bold mb-2">{{ isset($event) ? 'تعديل بيانات النشاط' : 'إضافة نشاط جديد' }}
                    </h1>
                    <p class="text-xl opacity-90">التقويم السنوي لعام {{ $year }} م</p>
                </div>
                <a href="{{ route('calendar.index', ['year' => $year]) }}"
                    class="bg-white/10 hover:bg-white/20 px-6 py-3 rounded-xl border border-white/30 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    العودة للتقويم
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 pb-20">
        <div class="max-w-6xl mx-auto">

            {{-- Progress Indicator --}}
            <div class="bg-white dark:bg-[#1b2e4b] rounded-2xl shadow-sm p-6 mb-6">
                {{-- Edit Mode Info Banner --}}
                <div x-show="isEditMode" class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                    <div class="flex items-center gap-2 text-blue-800 dark:text-blue-200 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>تعديل حدث قائم - لا يمكن تغيير المستخدم المستهدف</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-2">
                    <template x-for="(step, index) in (isEditMode ? steps.slice(1) : steps)" :key="index">
                        <div class="flex items-center flex-1">
                            <div class="progress-step flex flex-col items-center gap-2"
                                :class="(isEditMode ? currentStep - 1 : currentStep) >= index ? 'active' : ''">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition"
                                    :class="(isEditMode ? currentStep - 1 : currentStep) >= index ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-400'">
                                    <span x-text="index + 1"></span>
                                </div>
                                <span class="text-xs font-bold hidden md:block"
                                    :class="(isEditMode ? currentStep - 1 : currentStep) >= index ? 'text-emerald-700 dark:text-emerald-400' : 'text-gray-400'"
                                    x-text="step"></span>
                            </div>
                            <div x-show="index < (isEditMode ? steps.slice(1) : steps).length - 1" class="flex-1 h-1 mx-2"
                                :class="(isEditMode ? currentStep - 1 : currentStep) > index ? 'bg-emerald-600' : 'bg-gray-200'"></div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Main Form Card --}}
            <div
                class="bg-white dark:bg-[#1b2e4b] rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-[#191e3a] relative">

                {{-- CONFLICT ERROR BANNER --}}
                @if (session('conflicts'))
                    <div class="bg-red-50 dark:bg-red-900/20 p-6 border-b border-red-100 dark:border-red-900/30">
                        <h3 class="text-red-800 dark:text-red-300 font-bold text-lg mb-2 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            يوجد تعارض في المواعيد!
                        </h3>
                        <p class="text-red-600 dark:text-red-400 text-sm mb-4">يتعارض هذا النشاط مع الأنشطة التالية:</p>
                        <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 mb-4 space-y-1">
                            @foreach (session('conflicts') as $conflict)
                                <li><strong>{{ $conflict['title'] }}</strong> ({{ $conflict['start_date'] }} -
                                    {{ $conflict['end_date'] }})</li>
                            @endforeach
                        </ul>
                        <div class="flex gap-4">
                            <button type="button"
                                @click="document.getElementById('force_save_input').value = '1'; document.getElementById('event_form').submit();"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-bold transition">
                                حفظ وتجاهل التعارض
                            </button>
                            @if (session('suggested_slot'))
                                <p class="text-emerald-700 dark:text-emerald-400 self-center text-sm font-bold">
                                    💡 وقت مقترح: {{ session('suggested_slot') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                <form id="event_form" method="POST"
                    action="{{ isset($event) ? route('calendar.update', $event->id) : route('calendar.store') }}"
                    class="p-8 space-y-8" novalidate>
                    @csrf
                    @if (isset($event))
                        @method('PUT')
                    @endif
                    <input type="hidden" name="target_type" x-model="targetType">
                    <input type="hidden" name="force_save" id="force_save_input" value="0">
                    {{-- Hidden inputs for Type and Color, updated by Alpine --}}
                    <input type="hidden" name="type" x-model="selectedType">
                    <input type="hidden" name="bg_color" x-model="selectedColor">

                    {{-- Step 1: Target User Selection (Only for new events) --}}
                    <div x-show="currentStep === 0 && !isEditMode" x-cloak x-transition>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">لمن هذا النشاط؟</h2>

                        {{-- 1. TABS SWITCHER (User vs Org) --}}
                        {{-- This must be visible to everyone --}}
                        <div class="flex p-1 bg-gray-100 dark:bg-[#0e1726] rounded-xl mb-6">
                            <button type="button" @click="targetType = 'user'"
                                class="flex-1 py-3 px-4 rounded-lg text-sm font-bold transition"
                                :class="targetType === 'user' ? 'bg-white dark:bg-[#1b2e4b] shadow text-emerald-600' :
                                    'text-gray-500'">
                                أفراد (أنا وموظفيني)
                            </button>
                            <button type="button" @click="targetType = 'org'"
                                class="flex-1 py-3 px-4 rounded-lg text-sm font-bold transition"
                                :class="targetType === 'org' ? 'bg-white dark:bg-[#1b2e4b] shadow text-blue-600' :
                                    'text-gray-500'">
                                وحدات تنظيمية (أقسام/دوائر)
                            </button>
                        </div>

                        {{-- 2. USERS GRID --}}
                        <div x-show="targetType === 'user'"
                            class="grid grid-cols-2 md:grid-cols-4 gap-4 animate__animated animate__fadeIn">

                            {{-- A. My Calendar (Always Visible) --}}
                            <label
                                class="relative flex flex-col items-center text-center p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-[#0e1726] dark:to-[#1b2e4b] rounded-2xl border-2 cursor-pointer transition hover:shadow-lg h-full"
                                :class="selectedUser == '{{ auth()->id() }}' ?
                                    'border-emerald-600 ring-2 ring-emerald-100 dark:ring-emerald-900' :
                                    'border-transparent dark:border-[#191e3a]'">
                                <input type="radio" name="target_user_id" value="{{ auth()->id() }}"
                                    x-model="selectedUser" class="sr-only">
                                <div
                                    class="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center text-white mb-3 shadow-md">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <p class="font-bold text-emerald-900 dark:text-emerald-400 text-sm">تقويمي الشخصي</p>
                                <svg x-show="selectedUser == '{{ auth()->id() }}'"
                                    class="w-6 h-6 text-emerald-600 dark:text-emerald-400 absolute top-2 left-2"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </label>

                            {{-- B. Managed Users (Only if they exist) --}}
                            @foreach ($managedUsers as $u)
                                <label
                                    class="relative flex flex-col items-center text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-[#0e1726] dark:to-[#1b2e4b] rounded-2xl border-2 cursor-pointer transition hover:shadow-lg h-full"
                                    :class="selectedUser == '{{ $u->id }}' ?
                                        'border-blue-600 ring-2 ring-blue-100 dark:ring-blue-900' :
                                        'border-transparent dark:border-[#191e3a]'">
                                    <input type="radio" name="target_user_id" value="{{ $u->id }}"
                                        x-model="selectedUser" class="sr-only">
                                    <div
                                        class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg mb-3 shadow-md">
                                        {{ mb_substr($u->name, 0, 1) }}
                                    </div>
                                    <p
                                        class="font-bold text-blue-900 dark:text-blue-400 text-sm line-clamp-2 leading-tight">
                                        {{ $u->name }}</p>
                                    <svg x-show="selectedUser == '{{ $u->id }}'"
                                        class="w-6 h-6 text-blue-600 dark:text-blue-400 absolute top-2 left-2"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </label>
                            @endforeach
                        </div>

                        {{-- 3. ORG UNITS GRID --}}
                        <div x-show="targetType === 'org'"
                            class="grid grid-cols-2 md:grid-cols-3 gap-4 animate__animated animate__fadeIn">
                            @forelse($allowedOrgUnits as $unit)
                                <label
                                    class="relative flex flex-col items-center text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-[#0e1726] dark:to-[#1b2e4b] rounded-2xl border-2 cursor-pointer transition hover:shadow-lg h-full"
                                    :class="selectedOrg == '{{ $unit->id }}' ?
                                        'border-purple-600 ring-2 ring-purple-100 dark:ring-purple-900' :
                                        'border-transparent dark:border-[#191e3a]'">
                                    <input type="radio" name="target_org_id" value="{{ $unit->id }}"
                                        x-model="selectedOrg" class="sr-only">

                                    <div
                                        class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white mb-3 shadow-md">
                                        {{-- Org Icon --}}
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>

                                    <p class="font-bold text-purple-900 dark:text-purple-400 text-sm">
                                        {{ $unit->name }}</p>
                                    <span
                                        class="text-xs text-purple-500 mt-1">{{ $unit->unit_code ?? $unit->type }}</span>

                                    <svg x-show="selectedOrg == '{{ $unit->id }}'"
                                        class="w-6 h-6 text-purple-600 dark:text-purple-400 absolute top-2 left-2"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </label>
                            @empty
                                <div
                                    class="col-span-3 flex flex-col items-center justify-center py-10 text-gray-500 border-2 border-dashed border-gray-300 rounded-2xl dark:border-gray-700">
                                    <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p>ليس لديك صلاحيات لإضافة أنشطة لأي وحدة تنظيمية.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" @click="nextStep"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-8 py-4 rounded-xl transition shadow-lg flex items-center gap-2">
                                التالي <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Step 2: Details (Title, Type & Color Buttons, Notes) --}}
                    {{-- Step 2: Details --}}
                    <div x-show="currentStep === 1" x-cloak x-transition>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">تفاصيل النشاط</h2>

                        <div class="space-y-6">
                            {{-- Title Input --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">عنوان
                                    النشاط *</label>
                                {{-- CRITICAL FIX: Added x-model="title" so Alpine knows what you typed --}}
                                <input type="text" name="title" x-model="title"
                                    class="w-full border-2 border-gray-100 dark:border-[#191e3a] bg-gray-50 dark:bg-[#0e1726] rounded-2xl px-5 py-4 focus:border-emerald-500 dark:text-gray-300"
                                    placeholder="مثال: اجتماع المراجعة السنوي" required>
                            </div>

                            {{-- Type Buttons --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">تصنيف
                                    النشاط *</label>
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                    <template x-for="typeObj in eventTypes" :key="typeObj.value">
                                        <button type="button"
                                            @click="selectedType = typeObj.value; selectedColor = typeObj.color"
                                            class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all duration-200 hover:shadow-md"
                                            {{-- Dynamic Styling based on selection --}}
                                            :style="selectedType === typeObj.value ?
                                                `background-color: ${typeObj.color}15; border-color: ${typeObj.color};` :
                                                'border-color: transparent; background-color: rgba(243, 244, 246, 1)'"
                                            :class="selectedType !== typeObj.value ? 'dark:bg-[#0e1726]' : ''">

                                            <span
                                                class="w-8 h-8 rounded-full mb-2 flex items-center justify-center shadow-sm"
                                                :style="`background-color: ${typeObj.color}`">
                                                <svg x-show="selectedType === typeObj.value"
                                                    class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>

                                            <span class="font-bold text-sm"
                                                :style="`color: ${selectedType === typeObj.value ? typeObj.color : '#6b7280'}`"
                                                x-text="typeObj.label"></span>
                                        </button>
                                    </template>
                                </div>

                            </div>

                            {{-- Notes --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ملاحظات
                                    إضافية (اختياري)</label>
                                <textarea name="notes" rows="3"
                                    class="w-full border-2 border-gray-100 dark:border-[#191e3a] bg-gray-50 dark:bg-[#0e1726] rounded-2xl px-5 py-4 focus:border-emerald-500 dark:text-gray-300">{{ old('notes', $event->notes ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" @click="prevStep"
                                class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold px-8 py-4 rounded-xl">السابق</button>
                            <button type="button" @click="nextStep"
                                class="bg-emerald-600 text-white font-bold px-8 py-4 rounded-xl shadow-lg">التالي</button>
                        </div>
                    </div>

                    {{-- Step 3: Date & Time & Submit --}}
                    <div x-show="currentStep === 2" x-cloak x-transition>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">التوقيت والحفظ</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            {{-- Start Date --}}
                            <div>
                                <label
                                    class="block text-sm font-bold text-emerald-800 dark:text-emerald-500 mb-2">بداية
                                    النشاط</label>
                                <div class="flex gap-2">
                                    <input type="date" name="start_date_day" x-model="startDate"
                                        @change="syncDates()" :min="minDate"
                                        class="flex-2 border-2 border-gray-100 dark:border-[#191e3a] bg-gray-50 dark:bg-[#0e1726] rounded-xl px-4 py-3 focus:border-emerald-500 transition w-full dark:text-gray-300"
                                        required>

                                    <input type="time" name="start_date_time" x-model="startTime"
                                        class="flex-1 border-2 border-gray-100 dark:border-[#191e3a] bg-gray-50 dark:bg-[#0e1726] rounded-xl px-4 py-3 focus:border-emerald-500 transition dark:text-gray-300"
                                        required>
                                </div>
                            </div>

                            {{-- End Date --}}
                            <div>
                                <label class="block text-sm font-bold text-red-800 dark:text-red-400 mb-2">نهاية
                                    النشاط</label>
                                <div class="flex gap-2">
                                    <input type="date" name="end_date_day" x-model="endDate"
                                        :min="startDate || minDate"
                                        class="flex-2 border-2 border-gray-100 dark:border-[#191e3a] bg-gray-50 dark:bg-[#0e1726] rounded-xl px-4 py-3 focus:border-emerald-500 transition w-full dark:text-gray-300"
                                        required>

                                    <input type="time" name="end_date_time" x-model="endTime"
                                        class="flex-1 border-2 border-gray-100 dark:border-[#191e3a] bg-gray-50 dark:bg-[#0e1726] rounded-xl px-4 py-3 focus:border-emerald-500 transition dark:text-gray-300"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex flex-col md:flex-row gap-4 pt-6 mt-8 border-t-2 border-gray-100 dark:border-[#191e3a]">
                            <button type="button" @click="prevStep"
                                class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold py-5 rounded-2xl transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                                السابق
                            </button>
                            <button type="submit" :disabled="!isValidForm()"
                                :class="!isValidForm() ? 'opacity-50 cursor-not-allowed bg-gray-400' :
                                    'bg-emerald-700 hover:bg-emerald-800'"
                                class="flex-[2] text-white font-bold py-5 rounded-2xl transition shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                {{ isset($event) ? 'تحديث البيانات' : 'حفظ في التقويم' }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        function eventForm() {
            return {
                // Edit mode detection
                isEditMode: {{ isset($event) ? 'true' : 'false' }},
                
                // Start at step 1 (Details) if editing, step 0 (Target) if new
                currentStep: {{ isset($event) ? '1' : '0' }},
                steps: ['المستخدم', 'التفاصيل', 'التوقيت'],

                // Models
                targetType: '{{ isset($event) && $event->org_unit_id ? 'org' : 'user' }}',
                selectedUser: '{{ old('target_user_id', $event->target_user_id ?? ($preSelectedUser ?? auth()->id())) }}',
                selectedOrg: '{{ old('org_unit_id', $event->org_unit_id ?? '') }}',

                // CRITICAL: Ensure title and type are loaded from PHP
                title: '{{ old('title', $event->title ?? '') }}',
                selectedType: '{{ old('type', $event->type ?? '') }}',
                selectedColor: '{{ old('bg_color', $event->bg_color ?? '') }}',

                // Dates
                startDate: '{{ old('start_date_day', isset($event) ? $event->start_date->format('Y-m-d') : '') }}',
                startTime: '{{ old('start_date_time', isset($event) ? $event->start_date->format('H:i') : '08:00') }}',
                endDate: '{{ old('end_date_day', isset($event) ? $event->end_date->format('Y-m-d') : '') }}',
                endTime: '{{ old('end_date_time', isset($event) ? $event->end_date->format('H:i') : '09:00') }}',

                // Types List
                eventTypes: [{
                        value: 'program',
                        label: 'برنامج',
                        color: '#10b981'
                    },
                    {
                        value: 'meeting',
                        label: 'اجتماع',
                        color: '#3b82f6'
                    },
                    {
                        value: 'conference',
                        label: 'مؤتمر',
                        color: '#7c3aed'
                    },
                    {
                        value: 'competition',
                        label: 'مسابقة',
                        color: '#f59e0b'
                    },
                    {
                        value: 'other',
                        label: 'آخر',
                        color: '#6b7280'
                    }
                ],

                // Dynamic Min Date (Today)
                get minDate() {
                    const today = new Date();
                    return today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(
                        today.getDate()).padStart(2, '0');
                },

                syncDates() {
                    if (this.startDate < this.minDate) {
                        alert('لا يمكن اختيار تاريخ في الماضي');
                        this.startDate = this.minDate;
                    }
                    if (!this.endDate || this.endDate < this.startDate) {
                        this.endDate = this.startDate;
                    }
                },

                isValidForm() {
                    if (!this.startDate || !this.endDate || !this.startTime || !this.endTime) return false;
                    if (this.startDate < this.minDate) return false;
                    if (this.endDate < this.startDate) return false;
                    if (this.startDate === this.endDate && this.endTime <= this.startTime) return false;
                    return true;
                },

                nextStep() {
                    // Step 0: User/Org Selection
                    if (this.currentStep === 0) {
                        if (this.targetType === 'user' && !this.selectedUser) return alert('يرجى اختيار مستخدم');
                        if (this.targetType === 'org' && !this.selectedOrg) return alert('يرجى اختيار وحدة تنظيمية');
                    }

                    // Step 1: Details Validation
                    if (this.currentStep === 1) {
                        // This is where your error was coming from
                        if (!this.title.trim()) return alert('الرجاء إدخال عنوان النشاط');
                        if (!this.selectedType) return alert('الرجاء اختيار تصنيف النشاط');
                    }

                    if (this.currentStep < this.steps.length - 1) {
                        this.currentStep++;
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                },

                prevStep() {
                    // In edit mode, don't go below step 1 (skip target selection)
                    const minStep = this.isEditMode ? 1 : 0;
                    if (this.currentStep > minStep) {
                        this.currentStep--;
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                }
            }
        }
    </script>
</body>

</html>
