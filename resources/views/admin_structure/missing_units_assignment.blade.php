<x-app-layout title="ربط المسميات الوظيفية بالوحدات التنظيمية">
    <x-slot name="header">
        <h1 class="text-xl font-bold text-gray-800 flex items-center rtl:space-x-reverse space-x-3">
            <span class="material-icons text-4xl text-yellow-600">warning</span>
            تدقيق وربط المسميات الوظيفية المفقودة
        </h1>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow-xl max-w-7xl mx-auto">
        <div class="mb-6 border-b pb-4">
            <p class="text-lg text-gray-700">
                المسميات الوظيفية التالية موجودة في قاعدة البيانات ولكنها غير مرتبطة بأي وحدة تنظيمية.
                الرجاء تحديد الوحدة الصحيحة لكل مسمى للمحافظة على سلامة الهيكل.
            </p>
        </div>


        <div class="space-y-6">
            @foreach ($unattachedPositions as $position)
                <div class="p-5 border border-gray-200 rounded-lg bg-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0">
                    
                    {{-- Position Title --}}
                    <div class="flex-grow">
                        <span class="text-sm font-semibold text-gray-500 block">المسمى الوظيفي:</span>
                        <h4 class="text-xl font-extrabold text-indigo-700">{{ $position->title }}</h4>
                        <span class="text-xs text-gray-400">ID: {{ $position->id }}</span>
                    </div>

                    {{-- Assignment Form and Select --}}
                    <form action="{{ route('admin.structure.attach_unit') }}" method="POST" class="w-full md:w-1/2 lg:w-1/3 flex space-x-2 rtl:space-x-reverse items-end">
                        @csrf
                        {{-- Hidden Position ID --}}
                        <input type="hidden" name="position_id" value="{{ $position->id }}">

                        {{-- Searchable Select Component --}}
                        <div x-data='searchableSelect(@json($allUnits), null)' x-init="init()" x-on:click.outside="isOpen = false" class="relative flex-grow" x-cloak>

                            <label class="block text-sm font-medium text-gray-700 mb-1">الوحدة التنظيمية المطلوبة</label>
                            
                            <div class="relative flex items-center">
                                <input type="text" x-model="search" x-ref="searchInput"
                                    x-on:focus="isOpen = true"
                                    x-on:keydown.escape.prevent="isOpen = false"
                                    placeholder="ابحث واختر الوحدة..."
                                    class="block w-full border-gray-300 rounded-md shadow-sm p-2 pr-10 border focus:ring-indigo-500 focus:border-indigo-500">

                                {{-- Submit Button --}}
<button type="submit" :disabled="!selectedId"
    class="py-2 px-4 h-[42px] border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition disabled:opacity-50"
    :class="selectedId ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400'">
    ربط
</button>
                            </div>

                            <input type="hidden" name="org_unit_id" :value="selectedId">

                            {{-- Dropdown --}}
                            <ul x-show="isOpen" x-transition 
                                class="absolute z-50 w-full bg-white border border-gray-300 mt-1 max-h-60 overflow-auto rounded-lg shadow-xl ring-1 ring-black ring-opacity-5">

                                <template x-for="unit in filtered" :key="unit.id">
                                    <li @click="select(unit)" class="px-3 py-2 cursor-pointer transition duration-150 ease-in-out"
                                        :class="unit.id == selectedId ? 'bg-indigo-600 text-white font-semibold' : 'hover:bg-indigo-100 text-gray-900'">
                                        <span x-text="unit.name"></span>
                                        <span class="text-xs" :class="unit.id == selectedId ? 'text-indigo-200' : 'text-gray-500'" x-text="' (' + unit.type + ')'"></span>
                                    </li>
                                </template>

                                <li x-show="filtered.length === 0" class="px-3 py-2 text-sm text-gray-500">
                                    لا توجد نتائج مطابقة.
                                </li>
                            </ul>

                            @error('org_unit_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" :disabled="!selectedId"
                            class="py-2 px-4 h-[42px] border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition disabled:opacity-50"
                            :class="selectedId ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400'">
                            ربط
                        </button>
                    </form>

                </div>
            @endforeach
        </div>

    </div>

    {{-- Alpine JS Script for Searchable Select --}}
    <script>
        function searchableSelect(items, oldId = null) {
            return {
                search: '',
                selectedId: oldId,
                units: items,
                isOpen: false,

                get filtered() {
                    if (!this.search) return this.units;
                    const searchTerm = this.search.toLowerCase();
                    return this.units.filter(u => u.name.toLowerCase().includes(searchTerm));
                },

                select(unit) {
                    this.search = unit.name;
                    this.selectedId = unit.id;
                    this.isOpen = false;
                },

                clearSelection() {
                    this.search = '';
                    this.selectedId = null;
                    this.isOpen = true; 
                    this.$nextTick(() => this.$refs.searchInput.focus()); 
                },

                init() {
                    if (oldId) {
                        const old = this.units.find(u => u.id == oldId);
                        if (old) this.search = old.name;
                    }
                }
            }
        }
    </script>
</x-app-layout>
