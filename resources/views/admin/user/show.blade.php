<x-app-layout title="ملف المستخدم: {{ $user->name }}">
    <div class="p-4 md:p-6 max-w-4xl mx-auto">

        {{-- Header and Action Buttons --}}
        @include('admin.user.partials.user-header')

        {{-- Guide Section --}}
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-xl p-4 mb-6">
            <div class="flex items-start gap-3">
                <div class="bg-indigo-100 text-indigo-600 p-2 rounded-full">
                    <span class="material-icons">help_outline</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-indigo-800 mb-1">ملف المستخدم</h3>
                    <p class="text-gray-600 text-sm">
                        من هذه الصفحة يمكنك عرض وإدارة بيانات المستخدم، تعيينه على وظيفة، وإدارة صلاحياته.
                    </p>
                </div>
                <button onclick="this.closest('.bg-gradient-to-r').remove()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons text-sm">close</span>
                </button>
            </div>
        </div>

        <div class="space-y-6">
            {{-- 1. Basic User Information --}}
            <x-user-details-card :user="$user" />

            {{-- 2. Position History and Update Form --}}
            <x-user-position-card :user="$user" :positions="$positions" :units="$units" />

            {{-- 3. Position History with Search --}}
            @php
                $positionHistory = $user->positionHistory()->latest('start_date')->get();
            @endphp
            @if($positionHistory->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6" x-data="historyFilter()">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <span class="material-icons text-amber-500">history</span>
                            السجل الوظيفي الكامل
                        </h3>
                        <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-bold">
                            {{ $positionHistory->count() }} سجل
                        </span>
                    </div>
                    
                    {{-- Search Box --}}
                    @if($positionHistory->count() > 3)
                        <div class="mb-4">
                            <div class="relative">
                                <input type="text" x-model="searchQuery" @input="filterHistory()"
                                    placeholder="ابحث في السجل الوظيفي..."
                                    class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition dark:bg-gray-700 dark:text-white text-sm">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <span class="material-icons text-sm">search</span>
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($positionHistory as $history)
                            <div class="py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 history-item"
                                data-position="{{ strtolower($history->position?->title ?? '') }}"
                                data-unit="{{ strtolower($history->OrgUnit?->name ?? '') }}">
                                <div class="flex items-center gap-3">
                                    @if(is_null($history->end_date))
                                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    @else
                                        <span class="w-2 h-2 bg-gray-300 rounded-full"></span>
                                    @endif
                                    <div>
                                        <span class="font-semibold text-gray-800 dark:text-white">
                                            {{ $history->position?->title ?? '—' }}
                                        </span>
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">
                                            في {{ $history->OrgUnit?->name ?? '—' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">
                                        {{ $history->start_date ? \Carbon\Carbon::parse($history->start_date)->format('Y-m-d') : '—' }}
                                    </span>
                                    <span class="text-gray-400">←</span>
                                    @if($history->end_date)
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($history->end_date)->format('Y-m-d') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-bold">
                                            نشط حالياً
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- No Results --}}
                    <div x-show="visibleCount === 0" x-cloak class="text-center py-6">
                        <span class="material-icons text-3xl text-gray-300">search_off</span>
                        <p class="text-gray-500 text-sm mt-2">لا توجد نتائج للبحث</p>
                    </div>
                </div>
                
                @push('scripts')
                <script>
                    function historyFilter() {
                        return {
                            searchQuery: '',
                            visibleCount: {{ $positionHistory->count() }},
                            
                            filterHistory() {
                                const items = document.querySelectorAll('.history-item');
                                let count = 0;
                                const search = this.searchQuery.toLowerCase();
                                
                                items.forEach(item => {
                                    const position = item.dataset.position || '';
                                    const unit = item.dataset.unit || '';
                                    
                                    if (!this.searchQuery || position.includes(search) || unit.includes(search)) {
                                        item.style.display = '';
                                        count++;
                                    } else {
                                        item.style.display = 'none';
                                    }
                                });
                                
                                this.visibleCount = count;
                            }
                        }
                    }
                </script>
                @endpush
            @endif
            
            {{-- 4. Roles and Permissions --}}
            <x-user-permissions-card :user="$user" />

            {{-- Effective Permissions Hint --}}
            @include('admin.user.partials.permissions-hint')
        </div>
    </div>
</x-app-layout>
