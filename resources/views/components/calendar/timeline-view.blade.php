<div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-10 overflow-hidden">
    
    {{-- Navigation Header (Same as Day View for consistency) --}}
    <div class="bg-emerald-50 p-6 flex items-center justify-between border-b border-emerald-100">
        <button @click="changeDay(-1)" class="p-2 bg-white rounded-full text-emerald-700 shadow-sm hover:bg-emerald-600 hover:text-white transition">
            <x-calendar.icons.chevron-right class="w-6 h-6" />
        </button>
        
        <div class="text-center">
            <h2 class="text-2xl font-bold text-emerald-900" x-text="formatDayDate(selectedDate)"></h2>
            <div class="flex items-center justify-center gap-2 mt-1">
                <span class="text-emerald-700 font-medium" x-text="getDayName(selectedDate)"></span>
                <span class="w-1 h-1 bg-emerald-300 rounded-full"></span>
                <span class="text-emerald-600 text-sm opacity-80" x-text="getHijriDateForDay(selectedDate)"></span>
            </div>
        </div>

        <button @click="changeDay(1)" class="p-2 bg-white rounded-full text-emerald-700 shadow-sm hover:bg-emerald-600 hover:text-white transition">
            <x-calendar.icons.chevron-left class="w-6 h-6" />
        </button>
    </div>

    {{-- Timeline List --}}
    <div class="p-8 relative">
        {{-- Vertical Line --}}
        <div class="absolute right-12 top-0 bottom-0 w-0.5 bg-gray-100"></div>

        <template x-for="event in getEventsForHour(selectedDate, -1).sort((a,b) => a.startTime.localeCompare(b.startTime))" :key="event.id">
            <div class="relative flex gap-6 mb-8 group">
                
                {{-- Time Bubble --}}
                <div class="w-24 shrink-0 flex flex-col items-end pt-1 relative z-10">
                    <span class="text-lg font-bold text-gray-800" x-text="event.startTime"></span>
                    <span class="text-xs text-gray-400 font-mono" x-text="event.endTime"></span>
                    {{-- Dot on line --}}
                    <div class="absolute top-3 -right-[1.6rem] w-4 h-4 rounded-full border-2 border-white shadow-sm"
                         :style="`background-color: ${event.bg_color}`"></div>
                </div>

                {{-- Event Card --}}
                <div class="flex-1 bg-white border border-gray-100 rounded-xl p-5 shadow-sm hover:shadow-md transition cursor-pointer relative overflow-hidden"
                     @click="openEdit(event)">
                    
                    <div class="absolute top-0 right-0 w-1 h-full" :style="`background-color: ${event.bg_color}`"></div>
                    
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg" x-text="event.title"></h3>
                            <p class="text-sm text-gray-500 mt-1" x-text="event.program ? 'ضمن برنامج: ' + event.program : ''"></p>
                        </div>
                        <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-1 rounded" x-text="event.type"></span>
                    </div>

                    {{-- Footer Details --}}
                    <div class="mt-4 pt-3 border-t border-gray-50 flex gap-4 text-xs text-gray-400">
                        <span class="flex items-center gap-1">
                            <span class="font-bold">المنشئ:</span> <span x-text="event.creator"></span>
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="font-bold">المسند إليه:</span> <span x-text="event.target" class="text-emerald-600"></span>
                        </span>
                    </div>
                </div>
            </div>
        </template>

        {{-- Empty State --}}
        <div x-show="getEventsForHour(selectedDate, -1).length === 0" class="text-center py-12">
            <div class="inline-block p-4 bg-gray-50 rounded-full mb-3">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-gray-500 font-medium">لا توجد أنشطة مجدولة لهذا اليوم</p>
            <button @click="openCreateAt(selectedDate, 8)" class="mt-4 text-emerald-600 text-sm font-bold hover:underline">
                + إضافة نشاط جديد
            </button>
        </div>
    </div>
</div>