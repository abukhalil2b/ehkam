<x-app-layout title="صلاحيات الدور: {{ $role->title }}">
    <div class="p-4 md:p-6 max-w-6xl mx-auto">
        {{-- Header --}}
        <header class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-t-lg shadow-md border-b dark:border-gray-700">
            <div>
                <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    <a href="{{ route('admin.roles.index') }}" class="hover:text-indigo-600">الأدوار</a>
                    <span class="mx-2">/</span>
                    <span>{{ $role->title }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">إدارة صلاحيات الدور</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">حدد الصلاحيات التي سيحصل عليها المستخدمون الذين لديهم هذا الدور</p>
            </div>
            <a href="{{ route('admin.roles.index') }}"
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>رجوع</span>
            </a>
        </header>

        {{-- Role Info Card --}}
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
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
                
                {{-- Quick Stats --}}
                <div class="flex items-center justify-between mb-6 pb-4 border-b dark:border-gray-700">
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            الصلاحيات المحددة: 
                            <strong id="selected-count" class="text-indigo-600 dark:text-indigo-400">
                                {{ $permissions->where('selected', true)->count() }}
                            </strong>
                            من {{ $permissions->count() }}
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="selectAll()" class="text-sm px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded hover:bg-green-200 transition">
                            تحديد الكل
                        </button>
                        <button type="button" onclick="deselectAll()" class="text-sm px-3 py-1 bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 rounded hover:bg-red-200 transition">
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
                            <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                                {{-- Category Header --}}
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
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
                                        <label class="permission-item flex items-start gap-3 p-3 rounded-lg border dark:border-gray-600 cursor-pointer transition
                                            {{ $permission->selected ? 'bg-indigo-50 dark:bg-indigo-900/30 border-indigo-300 dark:border-indigo-700' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                            <input type="checkbox" 
                                                name="permissionIds[]" 
                                                value="{{ $permission->permissionId }}"
                                                {{ $permission->selected ? 'checked' : '' }}
                                                onchange="updateCount(); toggleHighlight(this)"
                                                class="permission-checkbox mt-1 h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium text-gray-900 dark:text-white text-sm">
                                                    {{ $permission->permissionTitle }}
                                                </div>
                                                <code class="text-xs text-gray-500 dark:text-gray-400 block truncate">
                                                    {{ $permission->permissionSlug }}
                                                </code>
                                                @if($permission->permissionDescr)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ Str::limit($permission->permissionDescr, 60) }}
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

                {{-- Submit Button --}}
                <div class="mt-8 flex items-center justify-between pt-6 border-t dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        سيحصل جميع المستخدمين الذين لديهم هذا الدور على الصلاحيات المحددة
                    </p>
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        حفظ التغييرات
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function updateCount() {
            const checked = document.querySelectorAll('.permission-checkbox:checked').length;
            document.getElementById('selected-count').textContent = checked;
        }

        function toggleHighlight(checkbox) {
            const label = checkbox.closest('.permission-item');
            if (checkbox.checked) {
                label.classList.add('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-300', 'dark:border-indigo-700');
                label.classList.remove('hover:bg-gray-50', 'dark:hover:bg-gray-700');
            } else {
                label.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-300', 'dark:border-indigo-700');
                label.classList.add('hover:bg-gray-50', 'dark:hover:bg-gray-700');
            }
        }

        function selectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(cb => {
                cb.checked = true;
                toggleHighlight(cb);
            });
            updateCount();
        }

        function deselectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(cb => {
                cb.checked = false;
                toggleHighlight(cb);
            });
            updateCount();
        }
    </script>
    @endpush
</x-app-layout>
