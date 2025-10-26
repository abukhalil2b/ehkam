<x-app-layout title="إدارة الهيكل التنظيمي والوظائف">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <span class="material-icons text-4xl text-indigo-600">account_tree</span>
            إدارة الهيكل التنظيمي والوظائف
        </h1>
    </x-slot>
    <a href="{{ route('missing_units_assignment') }}"
        class="w-full py-3 px-4 rounded-lg shadow-md text-center text-lg font-bold 
           text-white bg-orange-600 hover:bg-orange-700 transition duration-200 
           flex items-center justify-center space-x-3 rtl:space-x-reverse">

        <span class="material-icons text-xl">link</span>

        <span>تدقيق وربط المسميات الوظيفية المفقودة</span>
    </a>
    <div class="p-4 bg-gray-50 min-h-screen grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- 1️⃣ Positions Tree --}}
        <div class="lg:col-span-2 bg-gray-50 p-6 rounded-lg border">
            <h3 class="text-xl font-bold mb-4 text-purple-700 flex items-center space-x-2 rtl:space-x-reverse">
                <span class="material-icons">format_list_numbered</span>
                سلسلة المسميات الوظيفية
            </h3>

            <div class="border border-dashed border-purple-300 p-4 rounded-lg bg-white">
                @forelse ($topLevelPositions as $position)
                    {{-- Assuming partial exists for rendering the hierarchy --}}
                    @include('admin_structure.partials._position-hierarchy-item', [
                        'position' => $position,
                        'depth' => 0,
                    ])
                @empty
                    <p class="text-center text-gray-500">الرجاء إضافة أول مسمى وظيفي.</p>
                @endforelse
            </div>
        </div>

        {{-- 2️⃣ Position Creation Form --}}
        <div class="bg-white p-6 rounded-lg border shadow-lg">
            <h3 class="text-xl font-bold mb-4 text-gray-700">إضافة مسمى وظيفي جديد</h3>
            <form action="{{ route('admin.position.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">عنوان الوظيفة</label>
                    <input type="text" name="title" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border @error('title') border-red-500 @enderror"
                        value="{{ old('title') }}">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Organizational Unit (IMPROVED SEARCHABLE SELECT) --}}
                <div x-data='searchableSelect(@json($allUnits), "{{ old('organizational_unit_id') }}")'
                    x-init="init()" x-on:click.outside="isOpen = false" class="relative" x-cloak>

                    <label class="block text-sm font-medium text-gray-700">الوحدة التنظيمية</label>

                    {{-- Input and Clear Button Wrapper --}}
                    <div class="relative flex items-center mt-1">
                        <input type="text" x-model="search" x-ref="searchInput" x-on:focus="isOpen = true"
                            x-on:keydown.escape.prevent="isOpen = false" placeholder="اكتب للبحث عن وحدة..."
                            class="block w-full border-gray-300 rounded-md shadow-sm p-2 pr-10 border focus:ring-indigo-500 focus:border-indigo-500">

                        {{-- Clear Button (appears if a unit is selected) --}}
                        <button type="button" x-show="selectedId" x-on:click.prevent="clearSelection()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition duration-150">
                            {{-- Material icon for clear/close --}}
                            <span class="material-icons text-lg">close</span>
                        </button>
                    </div>

                    {{-- Hidden input to store selected value --}}
                    <input type="hidden" name="organizational_unit_id" :value="selectedId">

                    {{-- Dropdown --}}
                    <ul x-show="isOpen" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute z-50 w-full bg-white border border-gray-300 mt-1 max-h-60 overflow-auto rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none">

                        <template x-for="unit in filtered" :key="unit.id">
                            <li @click="select(unit)"
                                class="px-3 py-2 cursor-pointer transition duration-150 ease-in-out"
                                :class="unit.id == selectedId ? 'bg-indigo-600 text-white font-semibold' :
                                    'hover:bg-indigo-100 text-gray-900'">
                                <span x-text="unit.name"></span>
                                <span class="text-xs"
                                    :class="unit.id == selectedId ? 'text-indigo-200' : 'text-gray-500'"
                                    x-text="' (' + unit.type + ')'"></span>
                            </li>
                        </template>

                        {{-- No Results Message --}}
                        <li x-show="filtered.length === 0" class="px-3 py-2 text-sm text-gray-500">
                            لا توجد نتائج مطابقة.
                        </li>
                    </ul>

                    @error('organizational_unit_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- Reports To --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">يتبع مباشرةً إلى (الرئيس المباشر)</label>
                    <select name="reports_to_position_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                        <option value="">(لا يوجد / وظيفة عليا)</option>
                        @php
                            // The PHP function remains the same, used to build the nested <option> list
                            function renderPositionOptions($positions, $prefix = '')
                            {
                                foreach ($positions as $pos) {
                                    $selected = (string) $pos->id === old('reports_to_position_id') ? 'selected' : '';
                                    echo '<option value="' .
                                        $pos->id .
                                        '" ' .
                                        $selected .
                                        '>' .
                                        $prefix .
                                        $pos->title .
                                        '</option>';
                                    if ($pos->subordinates->isNotEmpty()) {
                                        renderPositionOptions($pos->subordinates, $prefix . '— ');
                                    }
                                }
                            }
                            renderPositionOptions($topLevelPositions);
                        @endphp
                    </select>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 transition">
                    <span class="material-icons text-lg -mt-1 rtl:ml-1">add</span> إضافة المسمى الوظيفي
                </button>
            </form>
        </div>

    </div>

    <script>
        /**
         * Alpine component for a searchable, selectable input field.
         * Enhancements: Tracks open state, includes a clear button, and closes on click-outside.
         * @param {Array<{id: number, name: string, type: string}>} items - List of units.
         * @param {string|null} oldId - The previously selected ID from form submission.
         */
        function searchableSelect(items, oldId = null) {
            return {
                search: '',
                selectedId: oldId,
                units: items,
                isOpen: false, // Controls dropdown visibility

                get filtered() {
                    const searchTerm = this.search.toLowerCase();
                    // Filter by name (case-insensitive)
                    return this.units.filter(u => u.name.toLowerCase().includes(searchTerm));
                },

                select(unit) {
                    this.search = unit.name;
                    this.selectedId = unit.id;
                    this.isOpen = false; // Close on selection
                    this.$refs.searchInput.focus(); // Keep focus on the input after selection
                },

                clearSelection() {
                    this.search = '';
                    this.selectedId = null;
                    this.isOpen = true; // Open the list to encourage a new selection
                    this.$nextTick(() => this.$refs.searchInput.focus()); // Focus back on input
                },

                init() {
                    // Pre-fill input if an old value exists
                    if (oldId) {
                        const old = this.units.find(u => u.id == oldId);
                        if (old) this.search = old.name;
                    }
                }
            }
        }
    </script>
</x-app-layout>
