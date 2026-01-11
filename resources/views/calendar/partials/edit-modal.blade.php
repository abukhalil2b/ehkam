 <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div @click="closeEdit" class="fixed inset-0 bg-emerald-900/60 backdrop-blur-sm transition-opacity"></div>

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative z-10 p-6">

                {{-- Header --}}
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">{{ __('edit event') }}</h3>
                    <button type="button" @click="closeEdit" class="text-gray-400 hover:text-gray-600 transition">
                        <x-calendar.icons.close class="w-6 h-6" />
                    </button>
                </div>

                {{-- Form --}}
                <form :action="`/calendar/update/${originalEvent?.id}`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Hidden Required Fields (Ensure these are populated in openEdit JS function) --}}
                    <input type="hidden" name="type" x-model="editingEvent.type">
                    <input type="hidden" name="bg_color" x-model="editingEvent.bg_color">
                    <input type="hidden" name="program" x-model="editingEvent.program">

                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">عنوان النشاط</label>
                        <input type="text" name="title" x-model="editingEvent.title"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-emerald-500 focus:ring-0 transition"
                            required>
                    </div>

                    {{-- Start Date & Time --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">تاريخ البدء</label>
                            <input type="date" name="start_date_day" x-model="editingEvent.startDate"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-2 focus:border-emerald-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">وقت البدء</label>
                            <input type="time" name="start_date_time" x-model="editingEvent.startTime"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-2 focus:border-emerald-500 focus:ring-0">
                        </div>
                    </div>

                    {{-- End Date & Time --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">تاريخ الانتهاء</label>
                            <input type="date" name="end_date_day" x-model="editingEvent.endDate"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-2 focus:border-emerald-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">وقت الانتهاء</label>
                            <input type="time" name="end_date_time" x-model="editingEvent.endTime"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-2 focus:border-emerald-500 focus:ring-0">
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3 pt-4 border-t border-gray-100 mt-4">
                        {{-- Save Button --}}
                        <button type="submit"
                            class="flex-1 bg-emerald-600 text-white font-bold py-3 rounded-xl hover:bg-emerald-700 transition shadow-md">
                            {{ __('save') }}
                        </button>

                        {{-- Cancel Button --}}
                        <button type="button" @click="closeEdit"
                            class="px-6 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition">
                            إلغاء
                        </button>

                        {{-- Delete Button --}}
                        <button type="button" @click="confirmDelete(originalEvent.id)"
                            class="px-4 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 border border-red-100 transition"
                            title="حذف النشاط">
                            <x-calendar.icons.trash class="w-6 h-6" />
                        </button>
                    </div>
                </form>
            </div>
        </div>
