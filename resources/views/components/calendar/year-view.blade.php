<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-10">
    <template x-for="(monthName, mIndex) in monthNames" :key="mIndex">
        <div
            class="bg-white dark:bg-[#191e3a] rounded-xl shadow-sm border border-gray-100 dark:border-[#1b2e4b] overflow-hidden flex flex-col h-full hover:shadow-md transition">

            {{-- Month Header --}}
            <div @click="goToMonth(mIndex)"
                class="bg-emerald-50 dark:bg-emerald-900/20 p-3 border-b border-emerald-100 dark:border-emerald-900/30 flex justify-between items-center cursor-pointer hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition">
                <h3 class="font-bold text-emerald-900 dark:text-emerald-400" x-text="monthName"></h3>
                <span
                    class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-white dark:bg-[#0e1726] px-2 py-0.5 rounded-full shadow-sm"
                    x-text="getMonthEvents(mIndex).length + ' أنشطة'"></span>
            </div>

            {{-- Calendar Grid --}}
            <div class="p-3">
                <div class="grid grid-cols-7 gap-1 text-center mb-2">
                    <template x-for="d in ['ح','ن','ث','ر','خ','ج','س']">
                        <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold" x-text="d"></span>
                    </template>
                </div>

                <div class="grid grid-cols-7 gap-1">
                    {{-- Blank Days --}}
                    <template x-for="blank in getBlankDays(mIndex)">
                        <div class="h-8"></div>
                    </template>

                    {{-- Actual Days --}}
                    <template x-for="dayData in getMonthDays(mIndex)" :key="dayData.day">
                        {{-- Click Logic: Set selectedDate -> Switch View --}}
                        <div @click="goToDayView(mIndex, dayData.day)"
                            class="h-8 flex items-center justify-center rounded-lg text-sm relative cursor-pointer transition hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:font-bold hover:scale-110"
                            :class="dayData.hasEvent ? 'font-bold text-emerald-800 dark:text-emerald-400 bg-emerald-50/50 dark:bg-emerald-900/20' : 'text-gray-500 dark:text-gray-400'">

                            <span x-text="dayData.day"></span>
                            <span
                                class="absolute top-0.5 left-1 text-[8px] text-gray-400 dark:text-gray-600 font-normal"
                                x-text="dayData.hijriDay"></span>

                            {{-- Event Indicator Dot --}}
                            <template x-if="dayData.hasEvent">
                                <span class="absolute bottom-1 w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Downside List (Limit 2 Events) --}}
            <div class="mt-auto bg-gray-50 dark:bg-[#0e1726] border-t border-gray-100 dark:border-[#1b2e4b] p-3">
                <div class="space-y-1.5 min-h-[50px]">
                    <template x-for="event in getMonthEvents(mIndex).slice(0, 2)" :key="event.id">
                        <div @click="openEdit(event)"
                            class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 p-1 rounded transition">
                            <span class="w-2 h-2 rounded-full shrink-0"
                                :style="`background-color: ${event.bg_color}`"></span>
                            <span class="text-[10px] text-gray-600 truncate flex-1" x-text="event.title"></span>
                            <span class="text-[9px] text-gray-400 font-mono"
                                x-text="new Date(event.startDate).getDate()"></span>
                        </div>
                    </template>

                    {{-- "More" Indicator --}}
                    <template x-if="getMonthEvents(mIndex).length > 2">
                        <div @click="goToMonth(mIndex)"
                            class="text-[10px] text-center text-emerald-600 font-bold cursor-pointer hover:underline pt-1">
                            <span x-text="'+ ' + (getMonthEvents(mIndex).length - 2) + ' المزيد'"></span>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <template x-if="getMonthEvents(mIndex).length === 0">
                        <div class="text-[10px] text-center text-gray-400 py-2">لا توجد أنشطة</div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>