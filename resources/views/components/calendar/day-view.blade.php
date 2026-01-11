<div
    class="bg-white dark:bg-[#1b2e4b] rounded-2xl shadow-sm border border-gray-200 dark:border-[#191e3a] mb-10 overflow-hidden flex flex-col h-[800px]">

    {{-- 1. Header (Navigation) --}}
    <div
        class="bg-emerald-50 dark:bg-[#0e1726] p-4 shrink-0 flex items-center justify-between border-b border-emerald-100 dark:border-[#191e3a] z-20 relative shadow-sm">
        <button @click="changeDay(-1)"
            class="p-2 bg-white dark:bg-[#1b2e4b] rounded-full text-emerald-700 dark:text-emerald-400 shadow-sm hover:bg-emerald-600 hover:text-white transition">
            <x-calendar.icons.chevron-right class="w-5 h-5" />
        </button>

        <div class="text-center">
            <h2 class="text-xl font-bold text-emerald-900 dark:text-emerald-400" x-text="formatDayDate(selectedDate)">
            </h2>
            <div class="flex items-center justify-center gap-2 mt-0.5">
                <span class="text-emerald-700 dark:text-emerald-500 font-medium text-sm"
                    x-text="getDayName(selectedDate)"></span>
                <span class="text-emerald-400 text-xs" x-text="getHijriDateForDay(selectedDate)"></span>
            </div>
        </div>

        <button @click="changeDay(1)"
            class="p-2 bg-white dark:bg-[#1b2e4b] rounded-full text-emerald-700 dark:text-emerald-400 shadow-sm hover:bg-emerald-600 hover:text-white transition">
            <x-calendar.icons.chevron-left class="w-5 h-5" />
        </button>
    </div>

    {{-- 2. Scrollable Grid Area --}}
    <div class="flex-1 overflow-y-auto relative bg-white dark:bg-[#1b2e4b] custom-scrollbar" x-ref="dayScrollContainer"
        x-init="$nextTick(() => { $refs.dayScrollContainer.scrollTop = 360; })"> {{-- Auto-scroll to 6:00 AM --}}

        <div class="relative min-h-[1440px]"> {{-- 24 hours * 60px height --}}

            {{-- Background Grid (Hours) --}}
            <template x-for="hour in 24"> {{-- 6:00 to 24:00 is 19 hours --}}
                <div class="absolute w-full border-b border-gray-100 dark:border-[#191e3a] flex items-start group"
                    :style="`top: ${(hour - 1) * 60}px; height: 60px;`">

                    {{-- Time Label --}}
                    <div
                        class="w-16 text-right pr-3 -mt-2.5 text-xs font-bold text-gray-400 dark:text-gray-500 select-none sticky left-0 bg-white dark:bg-[#1b2e4b] z-10">
                        <span x-text="(hour - 1).toString().padStart(2, '0') + ':00'"></span>
                    </div>

                    {{-- Grid Line & Click Area --}}
                    <div class="flex-1 h-full border-l border-gray-100 dark:border-[#191e3a] relative hover:bg-gray-50/50 dark:hover:bg-[#0e1726]/50 transition-colors"
                        @click="handleGridClick($event)">
                        {{-- Half-hour dashed line --}}
                        <div
                            class="absolute top-1/2 w-full border-t border-gray-50 dark:border-[#0e1726] border-dashed pointer-events-none">
                        </div>
                    </div>
                </div>
            </template>

            {{-- Current Time Indicator --}}
            <div x-show="isToday(selectedDate)"
                class="absolute left-16 right-0 border-t-2 border-red-500 z-30 pointer-events-none flex items-center"
                :style="`top: ${getCurrentTimeTop()}px`">
                <div class="w-2 h-2 bg-red-500 rounded-full -ml-1"></div>
            </div>

            {{-- Events Layer --}}
            <div class="absolute top-0 left-16 right-0 bottom-0 pointer-events-none">
                <template x-for="event in getEventsForDay(selectedDate)" :key="event.id">

                    {{-- Event Card --}}
                    <div @click="openEdit(event)"
                        class="absolute left-1 right-2 rounded-md shadow-sm border-l-4 cursor-pointer hover:brightness-95 hover:z-50 hover:shadow-md transition-all pointer-events-auto overflow-hidden flex flex-col px-2 py-1"
                        :class="getEventHeight(event) < 40 ? 'flex-row items-center gap-2' : ''"
                        :style="`top: ${getEventTop(event)}px; height: ${getEventHeight(event)}px; background-color: ${event.bg_color}20; border-color: ${event.bg_color};`">

                        {{-- Time (Shows differently based on height) --}}
                        <span class="text-[10px] font-bold opacity-80"
                            :class="getEventHeight(event) < 40 ? '' : 'mb-0.5'" :style="`color: ${event.bg_color}`"
                            x-text="event.startTime + (getEventHeight(event) < 40 ? '' : ' - ' + event.endTime)"></span>

                        {{-- Title --}}
                        <span class="text-xs font-bold text-gray-800 leading-tight line-clamp-2"
                            x-text="event.title"></span>

                        {{-- Type (Hide if very small) --}}
                        <template x-if="getEventHeight(event) > 50">
                            <span class="text-[9px] text-gray-500 mt-auto" x-text="event.type"></span>
                        </template>
                    </div>

                </template>
            </div>

        </div>
    </div>
</div>