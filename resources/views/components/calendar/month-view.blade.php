<div
    class="bg-white dark:bg-[#1b2e4b] rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-[#191e3a] mb-10">
    <div class="bg-emerald-800 p-6 text-white flex justify-between items-center">
        <button @click="nextMonth()" class="p-2 hover:bg-emerald-700 rounded-full transition">
            <x-calendar.icons.chevron-left class="w-6 h-6" />
        </button>
        <h2 class="text-2xl font-bold" x-text="monthNames[currentMonth] + ' ' + year"></h2>
        <button @click="prevMonth()" class="p-2 hover:bg-emerald-700 rounded-full transition">
            <x-calendar.icons.chevron-right class="w-6 h-6" />
        </button>
    </div>
    <div class="grid grid-cols-7 border-r border-b border-gray-100 dark:border-[#191e3a]">
        <template x-for="dayData in getMonthDays(currentMonth)">
            <div
                class="h-32 border-l border-t border-gray-100 dark:border-[#191e3a] p-2 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors overflow-y-auto">
                <div class="flex justify-between items-start mb-1">
                    <span class="font-bold text-gray-300 dark:text-gray-500" x-text="dayData.day"></span>
                    <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium opacity-75"
                        x-text="dayData.hijriDay"></span>
                </div>
                <template x-for="e in dayData.events">
                    <div @click="openEdit(e)"
                        class="text-[10px] p-1 mb-1 rounded text-white cursor-pointer truncate hover:brightness-110 shadow-sm"
                        :style="`background-color: ${e.bg_color}`" x-text="e.title"></div>
                </template>
            </div>
        </template>
    </div>
</div>