@props(['name', 'label' => '', 'options' => [], 'selected' => '', 'placeholder' => 'اختر خياراً...', 'required' => false])

<div x-data="{
    open: false,
    search: '',
    selected: @js($selected),
    options: @js($options),
    
    get filteredOptions() {
        if (this.search === '') return this.options;
        return this.options.filter(option => 
            option.name.toLowerCase().includes(this.search.toLowerCase()) || 
            (option.code && option.code.toLowerCase().includes(this.search.toLowerCase()))
        );
    },
    
    get displayValue() {
        if (!this.selected) return '';
        let found = this.options.find(o => o.id == this.selected);
        return found ? found.name + (found.code ? ' (' + found.code + ')' : '') : '';
    },

    select(id) {
        this.selected = id;
        this.open = false;
        this.search = '';
    }
}" class="relative w-full" @click.outside="open = false">

    @if($label)
        <label class="block text-sm font-bold text-gray-700 mb-2">{{ $label }} @if($required) <span
        class="text-red-500">*</span> @endif</label>
    @endif

    {{-- Hidden Input for Form Submission --}}
    <input type="hidden" :name="'{{ $name }}'" :value="selected">

    {{-- Trigger Button --}}
    <div @click="open = !open"
        class="w-full border border-gray-300 rounded-lg shadow-sm px-3 py-3 bg-white flex justify-between items-center cursor-pointer hover:border-emerald-500 focus:ring-2 focus:ring-emerald-500 transition h-[50px]">
        <span x-text="displayValue || '{{ $placeholder }}'"
            :class="{'text-gray-500': !selected, 'text-gray-800': selected}"></span>
        <span class="material-icons text-gray-400 text-xl transition-transform duration-200"
            :class="{'rotate-180': open}">expand_more</span>
    </div>

    {{-- Dropdown Menu --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-1 w-full bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">

        {{-- Search Input --}}
        <div class="p-2 border-b border-gray-100 bg-gray-50">
            <div class="relative">
                <span class="absolute right-3 top-2.5 material-icons text-gray-400 text-sm">search</span>
                <input type="text" x-model="search" x-ref="searchInput"
                    class="w-full text-sm border-gray-200 rounded-lg pr-9 pl-3 py-2 bg-white focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400"
                    placeholder="بحث..." @keydown.escape="open = false">
            </div>
        </div>

        {{-- Options List --}}
        <ul class="max-h-60 overflow-y-auto custom-scrollbar">
            <template x-for="option in filteredOptions" :key="option.id">
                <li @click="select(option.id)"
                    class="px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 cursor-pointer flex justify-between items-center border-b border-gray-50 last:border-0 transition"
                    :class="{'bg-emerald-50 text-emerald-700 font-bold': selected == option.id}">
                    <div>
                        <span x-text="option.name"></span>
                        <span x-show="option.code" class="text-xs text-gray-400 mr-2 font-mono"
                            x-text="option.code"></span>
                    </div>
                    <span x-show="selected == option.id" class="material-icons text-emerald-600 text-sm">check</span>
                </li>
            </template>
            <li x-show="filteredOptions.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">
                لا توجد نتائج مطابقة
            </li>
        </ul>
    </div>
</div>