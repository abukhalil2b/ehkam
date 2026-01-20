<x-app-layout title="صلاحيات الدور: {{ $role->title }}">
    <div class="p-4 md:p-6 max-w-6xl mx-auto" x-data="permissionsHandler()">

        {{-- Header --}}
        <header
            class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-t-lg shadow-md border-b dark:border-gray-700">
            <div>
                <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    <a href="{{ route('admin.roles.index') }}" class="hover:text-indigo-600">الأدوار</a>
                    <span class="mx-2">/</span>
                    <span>{{ $role->title }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">إدارة صلاحيات الدور</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">حدد الصلاحيات التي سيحصل عليها المستخدمون الذين
                    لديهم هذا الدور</p>
            </div>
            <a href="{{ route('admin.roles.index') }}"
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>رجوع</span>
            </a>
        </header>

        {{-- Role Info Card --}}
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">{{ $role->title }}</h2>
                    <p class="text-white/80">{{ $role->description ?? 'لا يوجد وصف' }}</p>
                    @if($role->slug)
                        <code class="inline-block mt-1 bg-white/20 px-2 py-1 rounded text-sm">{{ $role->slug }}</code>
                    @endif
                </div>
            </div>
        </div>

        {{-- Permissions Form --}}
        <form action="{{ route('admin.roles.permissions.update', $role) }}" method="POST" id="permissions-form">
            @csrf

            <div class="bg-white dark:bg-gray-800 p-6 rounded-b-lg shadow-xl border border-t-0 dark:border-gray-700">

                {{-- Toolbar with Search and Stats --}}
                <div
                    class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b dark:border-gray-700">

                    {{-- Stats --}}
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            الصلاحيات المحددة:
                            <strong class="text-indigo-600 dark:text-indigo-400 text-lg" x-text="selectedCount">
                                {{-- Initial count rendered by server --}}
                                {{ $permissions->where('selected', true)->count() }}
                            </strong>
                            من {{ $permissions->count() }}
                        </span>
                    </div>

                    {{-- Search Input --}}
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" x-model="search" placeholder="بحث في الصلاحيات..."
                                class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <button type="button" @click="selectAll()"
                            class="text-sm px-3 py-2 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded hover:bg-green-200 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            تحديد الكل
                        </button>
                        <button type="button" @click="deselectAll()"
                            class="text-sm px-3 py-2 bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 rounded hover:bg-red-200 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            إلغاء التحديد
                        </button>
                    </div>
                </div>

                {{-- Permissions by Category --}}
                @if($groupedPermissions->isEmpty())
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        <p>لا توجد صلاحيات مسجلة في النظام</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($groupedPermissions as $category => $categoryPermissions)
                            <div class="border dark:border-gray-700 rounded-lg overflow-hidden transition-all duration-300"
                                x-category-visibility x-transition.opacity>

                                {{-- Category Header --}}
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                        {{ $category ?: 'عام' }}
                                    </h3>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $categoryPermissions->count() }} صلاحية
                                    </span>
                                </div>

                                {{-- Permissions Grid --}}
                                <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($categoryPermissions as $permission)
                                        <label
                                            class="permission-item group flex items-start gap-3 p-3 rounded-lg border dark:border-gray-600 cursor-pointer transition relative"
                                            x-data="{ 
                                                                                    checked: {{ $permission->selected ? 'true' : 'false' }}, 
                                                                                    title: '{{ strtolower($permission->permissionTitle) }}',
                                                                                    slug: '{{ strtolower($permission->permissionSlug) }}',
                                                                                    category: '{{ $category }}'
                                                                                }" x-show="matchesSearch(title, slug)" x-transition
                                            :class="checked ? 'bg-indigo-50 dark:bg-indigo-900/30 border-indigo-300 dark:border-indigo-700' : 'hover:bg-gray-50 dark:hover:bg-gray-700'">

                                            <input type="checkbox" name="permissionIds[]" value="{{ $permission->permissionId }}"
                                                x-model="checked" @change="updateSelectedCount()"
                                                class="permission-checkbox mt-1 h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">

                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium text-gray-900 dark:text-white text-sm">
                                                    {{ $permission->permissionTitle }}
                                                </div>
                                                <code class="text-xs text-gray-500 dark:text-gray-400 block truncate">
                                                                                        {{ $permission->permissionSlug }}
                                                                                    </code>
                                                @if($permission->permissionDescr)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                                        {{ $permission->permissionDescr }}
                                                    </p>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Empty State for Search --}}
                <div x-show="search && allHidden" x-cloak class="text-center py-12">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">لا توجد نتائج</h3>
                    <p class="text-gray-500 dark:text-gray-400">لم نعثر على أي صلاحيات تطابق بحثك.</p>
                </div>

                {{-- Submit Button --}}
                <div class="mt-8 flex items-center justify-between pt-6 border-t dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        سيحصل جميع المستخدمين الذين لديهم هذا الدور على الصلاحيات المحددة
                    </p>
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition transform hover:-translate-y-0.5">
                        حفظ التغييرات
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                // Custom directive to toggle visibility of a container based on its children's matching state
                Alpine.directive('category-visibility', (el, { expression }, { effect, evaluate }) => {
                    effect(() => {
                        const search = evaluate('search'); // Reactive dependency

                        if (!search) {
                            el.style.display = '';
                            return;
                        }

                        // We need to wait for the children to update their visibility? 
                        // Or simply check the data ourselves. Checking data is more robust than waiting for DOM transition.
                        const s = search.toLowerCase();
                        const items = Array.from(el.querySelectorAll('.permission-item'));

                        // Check if ANY item in this category matches
                        const hasMatch = items.some(item => {
                            // We stored data in x-data attributes, we can parse them or use dataset if we added it.
                            // In the blade loop we added: x-data="{ ..., title: '...', slug: '...' }"
                            // We can regex it out of the attribute or access the component scope if mapped.
                            // Regex on the attribute is fast and synchronous.
                            const xData = item.getAttribute('x-data');
                            if (!xData) return false;

                            // Simple parsing assuming the format we wrote
                            const titleMatch = xData.match(/title:\s*'([^']+)'/);
                            const slugMatch = xData.match(/slug:\s*'([^']+)'/);

                            const title = titleMatch ? titleMatch[1] : '';
                            const slug = slugMatch ? slugMatch[1] : '';

                            return title.includes(s) || slug.includes(s);
                        });

                        el.style.display = hasMatch ? '' : 'none';
                    });
                });

                Alpine.data('permissionsHandler', () => ({
                    search: '',
                    selectedCount: {{ $permissions->where('selected', true)->count() }},

                    init() {
                        // Watchers or other init logic if needed
                    },

                    updateSelectedCount() {
                        this.$nextTick(() => {
                            this.selectedCount = document.querySelectorAll('.permission-checkbox:checked').length;
                        });
                    },

                    selectAll() {
                        // Select all currently visible items
                        document.querySelectorAll('.permission-item').forEach(item => {
                            // Check visibility (items hidden by x-show set display: none)
                            if (item.style.display !== 'none' && item.offsetParent !== null) {
                                const checkbox = item.querySelector('.permission-checkbox');
                                if (checkbox && !checkbox.checked) {
                                    checkbox.checked = true;
                                    checkbox.dispatchEvent(new Event('change'));
                                }
                            }
                        });
                        this.updateSelectedCount();
                    },

                    deselectAll() {
                        // Deselect all currently visible items
                        document.querySelectorAll('.permission-item').forEach(item => {
                            if (item.style.display !== 'none' && item.offsetParent !== null) {
                                const checkbox = item.querySelector('.permission-checkbox');
                                if (checkbox && checkbox.checked) {
                                    checkbox.checked = false;
                                    checkbox.dispatchEvent(new Event('change'));
                                }
                            }
                        });
                        this.updateSelectedCount();
                    },

                    matchesSearch(title, slug) {
                        if (this.search === '') return true;
                        const s = this.search.toLowerCase();
                        return title.includes(s) || slug.includes(s);
                    },

                    get allHidden() {
                        // This is a UI state that is hard to calculate reactively without specific tracking.
                        // Leaving as false to prevent complexity.
                        return false;
                    }
                }));
            });
        </script>
    @endpush
</x-app-layout>