<x-app-layout title="إدارة المستخدمين">
    <div class="p-4 md:p-6 max-w-7xl mx-auto" x-data="usersFilter()">

        {{-- Header --}}
        <header class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md border dark:border-gray-700 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                    <span class="material-icons text-3xl text-indigo-600">group</span>
                    إدارة المستخدمين
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">إدارة المستخدمين وتعيين الأدوار والوظائف</p>
            </div>
            @can('create_users')
                <a href="{{ route('admin_users.create') }}"
                    class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow-md hover:bg-indigo-700 hover:shadow-lg transition transform hover:-translate-y-0.5">
                    <span class="material-icons">person_add</span>
                    <span>مستخدم جديد</span>
                </a>
            @endcan
        </header>

        {{-- Guide Section --}}
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl p-5 mb-6">
            <div class="flex items-start gap-4">
                <div class="bg-indigo-100 text-indigo-600 p-3 rounded-full">
                    <span class="material-icons text-2xl">help_outline</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-indigo-800 text-lg mb-2">كيف تعمل إدارة المستخدمين؟</h3>
                    <p class="text-gray-700 text-sm leading-relaxed mb-3">
                        من هنا يمكنك إدارة جميع مستخدمي النظام. لكل مستخدم يمكنك تعيين: <strong>أدوار</strong> (تحدد صلاحياته)، 
                        <strong>وظيفة</strong> (مسماه الوظيفي)، و <strong>وحدة تنظيمية</strong> (مكان عمله في الهيكل التنظيمي).
                    </p>
                    <div class="flex flex-wrap gap-3 text-sm">
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-indigo-100">
                            <span class="material-icons text-indigo-500">visibility</span>
                            <span>عرض = ملف المستخدم الكامل</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-indigo-100">
                            <span class="material-icons text-green-500">security</span>
                            <span>الدرع = إدارة الأدوار والصلاحيات</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-indigo-100">
                            <span class="material-icons text-blue-500">link</span>
                            <span>الرابط = ربط بالقطاعات</span>
                        </div>
                    </div>
                </div>
                <button @click="$el.closest('.bg-gradient-to-r').remove()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
        </div>

        {{-- Filter Controls --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-600 dark:text-gray-300 mb-2">
                        <span class="material-icons text-sm align-middle">search</span>
                        بحث سريع في الصفحة الحالية
                    </label>
                    <input type="text" x-model="searchQuery" @input="filterTable()"
                        placeholder="ابحث بالاسم أو البريد الإلكتروني..."
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition dark:bg-gray-700 dark:text-white">
                </div>

                {{-- Filter by Role --}}
                <div>
                    <label class="block text-sm font-bold text-gray-600 dark:text-gray-300 mb-2">
                        <span class="material-icons text-sm align-middle">admin_panel_settings</span>
                        الدور
                    </label>
                    <select x-model="roleFilter" @change="filterTable()"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white dark:bg-gray-700 dark:text-white">
                        <option value="">الكل</option>
                        @php
                            $roles = $users->getCollection()->flatMap->roles->unique('id');
                        @endphp
                        @foreach($roles as $role)
                            <option value="{{ $role->title }}">{{ $role->title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter by Position Status --}}
                <div>
                    <label class="block text-sm font-bold text-gray-600 dark:text-gray-300 mb-2">
                        <span class="material-icons text-sm align-middle">work</span>
                        الوظيفة
                    </label>
                    <select x-model="positionFilter" @change="filterTable()"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white dark:bg-gray-700 dark:text-white">
                        <option value="">الكل</option>
                        <option value="assigned">معيّن</option>
                        <option value="unassigned">غير معيّن</option>
                    </select>
                </div>
            </div>

            {{-- Active Filters & Reset --}}
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <span class="material-icons text-lg">filter_list</span>
                    <span>عرض: <strong x-text="visibleCount"></strong> من {{ $users->count() }} في هذه الصفحة</span>
                </div>
                <button @click="resetFilters()" x-show="searchQuery || roleFilter || positionFilter"
                    class="text-red-500 hover:text-red-700 text-sm font-bold flex items-center gap-1 transition">
                    <span class="material-icons text-sm">clear</span>
                    مسح الفلاتر
                </button>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border dark:border-gray-700 overflow-hidden">
            @if($users->isEmpty())
                <div class="text-center py-12">
                    <span class="material-icons text-6xl text-gray-300 mb-4">group_off</span>
                    <h3 class="text-lg font-bold text-gray-600 dark:text-white mb-2">لا يوجد مستخدمون</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">ابدأ بإضافة أول مستخدم للنظام</p>
                    @can('create_users')
                        <a href="{{ route('admin_users.create') }}" class="text-indigo-600 font-bold hover:underline">
                            إضافة مستخدم جديد
                        </a>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    المستخدم
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                                    البريد الإلكتروني
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">
                                    المسمى الوظيفي
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">
                                    الأدوار
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    الإجراءات
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($users as $user)
                                @php
                                    $userRoles = $user->roles->pluck('title')->implode(', ');
                                    $hasPosition = $user->latestHistory?->position ? 'assigned' : 'unassigned';
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 user-row"
                                    data-name="{{ strtolower($user->name) }}"
                                    data-email="{{ strtolower($user->email) }}"
                                    data-roles="{{ strtolower($userRoles) }}"
                                    data-position="{{ $hasPosition }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                                <span class="text-indigo-600 dark:text-indigo-400 font-bold">
                                                    {{ mb_substr($user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $user->name }}
                                                </div>
                                                @if($user->id == 1)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Super Admin
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 hidden sm:table-cell">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        @if($user->latestHistory?->position)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 rounded text-xs font-bold border border-green-100 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                                <span class="material-icons text-xs">work</span>
                                                {{ $user->latestHistory->position->title }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-500 rounded text-xs border border-gray-200 dark:bg-gray-700 dark:text-gray-400">
                                                <span class="material-icons text-xs">work_off</span>
                                                غير محدد
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                        @if($user->roles->isNotEmpty())
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($user->roles->take(2) as $role)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                                        {{ $role->title }}
                                                    </span>
                                                @endforeach
                                                @if($user->roles->count() > 2)
                                                    <span class="text-xs text-gray-500">+{{ $user->roles->count() - 2 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">بدون أدوار</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2 justify-center">
                                            {{-- View Details --}}
                                            <a href="{{ route('admin_users.show', $user) }}"
                                                class="text-indigo-500 hover:text-indigo-700 bg-indigo-50 p-2 rounded-lg hover:bg-indigo-100 transition dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50"
                                                title="عرض الملف">
                                                <span class="material-icons text-sm">visibility</span>
                                            </a>

                                            {{-- Assign Roles --}}
                                            @can('assign_roles')
                                                <a href="{{ route('admin_users.permissions.edit', $user) }}"
                                                    class="text-green-500 hover:text-green-700 bg-green-50 p-2 rounded-lg hover:bg-green-100 transition dark:bg-green-900/30 dark:hover:bg-green-900/50"
                                                    title="إدارة الأدوار">
                                                    <span class="material-icons text-sm">security</span>
                                                </a>
                                            @endcan

                                            {{-- Link to Sectors --}}
                                            @can('link_user_sectors')
                                                <a href="{{ route('admin_users.link_user_with_sector_create', $user) }}"
                                                    class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded-lg hover:bg-blue-100 transition dark:bg-blue-900/30 dark:hover:bg-blue-900/50"
                                                    title="ربط بالقطاعات">
                                                    <span class="material-icons text-sm">link</span>
                                                </a>
                                            @endcan

                                            {{-- Impersonate User --}}
                                            @if(auth()->id() === 1 && $user->id !== 1)
                                                <a href="{{ route('admin.impersonate.start', $user) }}"
                                                    class="text-purple-500 hover:text-purple-700 bg-purple-50 p-2 rounded-lg hover:bg-purple-100 transition dark:bg-purple-900/30 dark:hover:bg-purple-900/50"
                                                    title="انتحال شخصية المستخدم">
                                                    <span class="material-icons text-sm">person</span>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Empty State for Filter --}}
                <div x-show="visibleCount === 0" x-cloak class="text-center py-12 border-t border-gray-200 dark:border-gray-700">
                    <span class="material-icons text-5xl text-gray-300 mb-3">search_off</span>
                    <h3 class="text-lg font-bold text-gray-600 dark:text-gray-300 mb-1">لا توجد نتائج</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">جرب تغيير معايير البحث أو الفلترة</p>
                </div>

                {{-- Pagination Links --}}
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function usersFilter() {
            return {
                searchQuery: '',
                roleFilter: '',
                positionFilter: '',
                visibleCount: {{ $users->count() }},

                filterTable() {
                    const rows = document.querySelectorAll('.user-row');
                    let count = 0;

                    rows.forEach(row => {
                        const name = row.dataset.name || '';
                        const email = row.dataset.email || '';
                        const roles = row.dataset.roles || '';
                        const position = row.dataset.position || '';

                        const searchLower = this.searchQuery.toLowerCase();

                        const matchesSearch = !this.searchQuery ||
                            name.includes(searchLower) ||
                            email.includes(searchLower);

                        const matchesRole = !this.roleFilter || roles.includes(this.roleFilter.toLowerCase());
                        const matchesPosition = !this.positionFilter || position === this.positionFilter;

                        if (matchesSearch && matchesRole && matchesPosition) {
                            row.style.display = '';
                            count++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    this.visibleCount = count;
                },

                resetFilters() {
                    this.searchQuery = '';
                    this.roleFilter = '';
                    this.positionFilter = '';
                    this.filterTable();
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
