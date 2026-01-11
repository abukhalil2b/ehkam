<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($event) ? 'تعديل حدث' : 'إضافة حدث جديد' }} | {{ $year }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .islamic-pattern {
            background-color: #064e3b;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 30L15 45L0 30L15 15L30 30zm30 0L45 45L30 30L45 15L60 30zM15 15L0 0h30L15 15zm30 0L30 0h30L45 15zM15 45L0 60h30L15 45zm30 0L30 60h30L45 45z' fill='%23065f46' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        [x-cloak] { display: none !important; }
        .progress-step { transition: all 0.3s ease; }
        .progress-step.active { transform: scale(1.1); }
    </style>
</head>

<body class="bg-gray-100 min-h-screen" x-data="eventForm()">

    <header class="islamic-pattern text-white py-10 shadow-xl mb-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-right">
                    <h1 class="text-3xl font-bold mb-2">{{ isset($event) ? 'تعديل بيانات النشاط' : 'إضافة نشاط جديد' }}</h1>
                    <p class="text-xl opacity-90">التقويم السنوي لعام {{ $year }} م</p>
                </div>
                <a href="{{ route('calendar.index', ['year' => $year]) }}" 
                   class="bg-white/10 hover:bg-white/20 px-6 py-3 rounded-xl border border-white/30 transition flex items-center gap-2">
                    {{-- RTL Arrow: Points Right for "Back" --}}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                    العودة للتقويم
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 pb-20">
        <div class="max-w-4xl mx-auto">
            
            {{-- Progress Indicator --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <template x-for="(step, index) in steps" :key="index">
                        <div class="flex items-center flex-1">
                            <div class="progress-step flex flex-col items-center gap-2"
                                 :class="currentStep >= index ? 'active' : ''">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition"
                                     :class="currentStep >= index ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-400'">
                                    <span x-text="index + 1"></span>
                                </div>
                                <span class="text-xs font-bold hidden md:block"
                                      :class="currentStep >= index ? 'text-emerald-700' : 'text-gray-400'"
                                      x-text="step"></span>
                            </div>
                            <div x-show="index < steps.length - 1" 
                                 class="flex-1 h-1 mx-2"
                                 :class="currentStep > index ? 'bg-emerald-600' : 'bg-gray-200'"></div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Main Form Card --}}
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 relative">
                
                {{-- CONFLICT ERROR BANNER (If Controller returns conflicts) --}}
                @if(session('conflicts'))
                <div class="bg-red-50 p-6 border-b border-red-100">
                    <h3 class="text-red-800 font-bold text-lg mb-2 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        يوجد تعارض في المواعيد!
                    </h3>
                    <p class="text-red-600 text-sm mb-4">يتعارض هذا النشاط مع الأنشطة التالية:</p>
                    <ul class="list-disc list-inside text-sm text-red-700 mb-4 space-y-1">
                        @foreach(session('conflicts') as $conflict)
                            <li><strong>{{ $conflict['title'] }}</strong> ({{ $conflict['start_date'] }} - {{ $conflict['end_date'] }})</li>
                        @endforeach
                    </ul>
                    <div class="flex gap-4">
                        <button type="button" @click="document.getElementById('force_save_input').value = '1'; document.getElementById('event_form').submit();" 
                                class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-700 transition">
                            حفظ وتجاهل التعارض
                        </button>
                        @if(session('suggested_slot'))
                        <p class="text-emerald-700 self-center text-sm font-bold">
                            💡 وقت مقترح: {{ session('suggested_slot') }}
                        </p>
                        @endif
                    </div>
                </div>
                @endif

                <form id="event_form" method="POST" 
                      action="{{ isset($event) ? route('calendar.update', $event->id) : route('calendar.store') }}" 
                      class="p-8 space-y-8">
                    @csrf
                    @if(isset($event)) @method('PUT') @endif
                    
                    {{-- Hidden Force Save Input --}}
                    <input type="hidden" name="force_save" id="force_save_input" value="0">

                    {{-- Step 1: Target User Selection --}}
                    <div x-show="currentStep === 0" x-cloak x-transition>
                        @if ($managedUsers->count() > 0)
                        <div class="space-y-4">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">لمن هذا النشاط؟</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative flex items-center p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl border-2 cursor-pointer transition hover:shadow-lg"
                                       :class="selectedUser === '{{ auth()->id() }}' ? 'border-emerald-600 ring-4 ring-emerald-100' : 'border-transparent'">
                                    <input type="radio" name="target_user_id" value="{{ auth()->id() }}" 
                                           x-model="selectedUser" class="sr-only">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center text-white">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-emerald-900">تقويمي الشخصي</p>
                                        </div>
                                    </div>
                                    <svg x-show="selectedUser === '{{ auth()->id() }}'" class="w-6 h-6 text-emerald-600 absolute top-4 left-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                </label>

                                @foreach ($managedUsers as $u)
                                <label class="relative flex items-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl border-2 cursor-pointer transition hover:shadow-lg"
                                       :class="selectedUser === '{{ $u->id }}' ? 'border-blue-600 ring-4 ring-blue-100' : 'border-transparent'">
                                    <input type="radio" name="target_user_id" value="{{ $u->id }}" 
                                           x-model="selectedUser" class="sr-only"
                                           {{ old('target_user_id', $event->target_user_id ?? '') == $u->id ? 'checked' : '' }}>
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ mb_substr($u->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-blue-900">{{ $u->name }}</p>
                                        </div>
                                    </div>
                                    <svg x-show="selectedUser === '{{ $u->id }}'" class="w-6 h-6 text-blue-600 absolute top-4 left-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="target_user_id" value="{{ auth()->id() }}">
                        @endif

                        <div class="flex justify-end mt-8">
                            <button type="button" @click="nextStep" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-8 py-4 rounded-xl transition shadow-lg flex items-center gap-2">
                                التالي
                                {{-- RTL: Next points Left --}}
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Step 2: Event Details --}}
                    <div x-show="currentStep === 1" x-cloak x-transition>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">تفاصيل النشاط</h2>
                        
                        <div class="space-y-6">
                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">عنوان النشاط أو المهمة *</label>
                                <input type="text" name="title" value="{{ old('title', $event->title ?? '') }}"
                                    class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-5 py-4 focus:bg-white focus:border-emerald-500 transition shadow-inner" required>
                                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">تصنيف النشاط *</label>
                                    <select name="type" class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-5 py-4 focus:bg-white focus:border-emerald-500 transition shadow-inner" required>
                                        <option value="">-- اختر التصنيف --</option>
                                        @foreach(['program' => 'برنامج', 'meeting' => 'اجتماع', 'conference' => 'مؤتمر', 'competition' => 'مسابقة'] as $type => $label)
                                        <option value="{{ $type }}" {{ old('type', $event->type ?? '') == $type ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">اسم البرنامج (اختياري)</label>
                                    <input type="text" name="program" value="{{ old('program', $event->program ?? '') }}" class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-5 py-4 focus:bg-white focus:border-emerald-500 transition shadow-inner">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" @click="prevStep" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold px-8 py-4 rounded-xl transition flex items-center gap-2">
                                {{-- RTL: Prev points Right --}}
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                السابق
                            </button>
                            <button type="button" @click="nextStep" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-8 py-4 rounded-xl transition shadow-lg flex items-center gap-2">
                                التالي
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Step 3: Date & Time --}}
                    <div x-show="currentStep === 2" x-cloak x-transition>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">التوقيت والمدة</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-emerald-800 mb-2">بداية النشاط</label>
                                <div class="flex gap-2">
                                    <input type="date" name="start_date_day" value="{{ old('start_date_day', isset($event) ? $event->start_date->format('Y-m-d') : '') }}" class="flex-2 border-2 border-gray-100 bg-gray-50 rounded-xl px-4 py-3 focus:bg-white focus:border-emerald-500 transition w-full" required>
                                    <input type="time" name="start_date_time" value="{{ old('start_date_time', isset($event) ? $event->start_date->format('H:i') : '08:00') }}" class="flex-1 border-2 border-gray-100 bg-gray-50 rounded-xl px-4 py-3 focus:bg-white focus:border-emerald-500 transition" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-red-800 mb-2">نهاية النشاط</label>
                                <div class="flex gap-2">
                                    <input type="date" name="end_date_day" value="{{ old('end_date_day', isset($event) ? $event->end_date->format('Y-m-d') : '') }}" class="flex-2 border-2 border-gray-100 bg-gray-50 rounded-xl px-4 py-3 focus:bg-white focus:border-emerald-500 transition w-full" required>
                                    <input type="time" name="end_date_time" value="{{ old('end_date_time', isset($event) ? $event->end_date->format('H:i') : '09:00') }}" class="flex-1 border-2 border-gray-100 bg-gray-50 rounded-xl px-4 py-3 focus:bg-white focus:border-emerald-500 transition" required>
                                </div>
                            </div>
                        </div>

                        {{-- Navigation --}}
                        <div class="flex justify-between mt-8">
                            <button type="button" @click="prevStep" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold px-8 py-4 rounded-xl transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                السابق
                            </button>
                            <button type="button" @click="nextStep" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-8 py-4 rounded-xl transition shadow-lg flex items-center gap-2">
                                التالي
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Step 4: Customization & Save --}}
                    <div x-show="currentStep === 3" x-cloak x-transition>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">التخصيص والإعدادات</h2>
                        
                        <div x-data="{ selectedColor: '{{ old('bg_color', $event->bg_color ?? '#16a34a') }}' }" class="space-y-4 mb-6">
                            <label class="block text-sm font-bold text-gray-700">لون التمييز</label>
                            <input type="hidden" name="bg_color" :value="selectedColor">
                            <div class="flex flex-wrap gap-4 p-1">
                                @foreach(['#16a34a', '#2563eb', '#7c3aed', '#db2777', '#ea580c', '#4b5563', '#0891b2', '#b91c1c'] as $color)
                                <button type="button" @click="selectedColor = '{{ $color }}'"
                                        class="w-16 h-16 rounded-2xl border-4 transition-all hover:scale-110 shadow-sm"
                                        style="background-color: {{ $color }}"
                                        :class="selectedColor === '{{ $color }}' ? 'border-gray-800 scale-110' : 'border-white opacity-70 hover:opacity-100'">
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-4 bg-gray-50 p-5 rounded-2xl border-2 border-gray-100 mb-6">
                            <input type="checkbox" name="is_public" value="1" {{ old('is_public', $event->is_public ?? true) ? 'checked' : '' }} class="w-6 h-6 text-emerald-600 rounded focus:ring-emerald-500">
                            <span class="font-bold text-gray-800">إظهار للجميع (نشاط عام)</span>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700">ملاحظات</label>
                            <textarea name="notes" rows="3" class="w-full border-2 border-gray-100 bg-gray-50 rounded-2xl px-5 py-4 focus:bg-white focus:border-emerald-500 transition shadow-inner">{{ old('notes', $event->notes ?? '') }}</textarea>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4 pt-6 mt-8 border-t-2 border-gray-100">
                            <button type="button" @click="prevStep" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-5 rounded-2xl transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                السابق
                            </button>
                            <button type="submit" class="flex-[2] bg-emerald-700 text-white font-bold py-5 rounded-2xl hover:bg-emerald-800 transition shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                {{ isset($event) ? 'تحديث البيانات' : 'حفظ في التقويم' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function eventForm() {
            return {
                // If there are errors in 'title' (step 2), default to step 1 (index 1)
                // If there are errors in dates (step 3), default to step 2 (index 2)
                currentStep: {{ $errors->has('title') || $errors->has('type') ? 1 : ($errors->has('start_date_day') || $errors->has('conflict') ? 2 : 0) }},
                steps: ['المستخدم', 'التفاصيل', 'التوقيت', 'الإعدادات'],
                selectedUser: '{{ old('target_user_id', $event->target_user_id ?? auth()->id()) }}',

                nextStep() { if (this.currentStep < this.steps.length - 1) { this.currentStep++; window.scrollTo({top: 0, behavior: 'smooth'}); } },
                prevStep() { if (this.currentStep > 0) { this.currentStep--; window.scrollTo({top: 0, behavior: 'smooth'}); } }
            }
        }
    </script>
</body>
</html>