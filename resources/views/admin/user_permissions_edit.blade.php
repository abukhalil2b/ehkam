<x-app-layout title="إدارة أدوار المستخدم: {{ $user->name }}">
    <div class="p-4 md:p-6 max-w-4xl mx-auto">
        {{-- Header --}}
        <header class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-t-lg shadow-md border-b dark:border-gray-700">
            <div>
                <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    <a href="{{ route('admin_users.index') }}" class="hover:text-indigo-600">المستخدمون</a>
                    <span class="mx-2">/</span>
                    <span>{{ $user->name }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">إدارة أدوار المستخدم</h1>
            </div>
            <a href="{{ url()->previous() }}"
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                رجوع
            </a>
        </header>

        {{-- User Info --}}
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-white/80">{{ $user->email }}</p>
                    @if($user->id === 1)
                        <span class="inline-block mt-1 bg-red-500 text-white text-xs px-2 py-1 rounded">Super Admin</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Role Assignment Form --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-b-lg shadow-xl border border-t-0 dark:border-gray-700">
            <form action="{{ route('admin_users.permissions.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- RBAC Notice --}}
                <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="mr-3">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                <strong>نظام RBAC:</strong> المستخدم يحصل على جميع الصلاحيات المرتبطة بالأدوار المسندة إليه.
                                لا يمكن منح صلاحيات مباشرة للمستخدمين.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Roles Section --}}
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        الأدوار المتاحة
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        حدد الأدوار التي تريد إسنادها لهذا المستخدم. يمكن للمستخدم أن يمتلك أدوار متعددة.
                    </p>

                    @if($allroles->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>لا توجد أدوار مسجلة في النظام</p>
                            @can('create_roles')
                            <a href="{{ route('admin.roles.create') }}" class="text-indigo-600 hover:underline mt-2 inline-block">
                                إنشاء دور جديد
                            </a>
                            @endcan
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($allroles as $role)
                                <label class="role-item flex items-start gap-3 p-4 rounded-lg border dark:border-gray-600 cursor-pointer transition
                                    {{ in_array($role->id, $assignedProfileIds) ? 'bg-indigo-50 dark:bg-indigo-900/30 border-indigo-300 dark:border-indigo-700' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                    <input type="checkbox" 
                                        name="roles[]" 
                                        value="{{ $role->id }}"
                                        {{ in_array($role->id, $assignedProfileIds) ? 'checked' : '' }}
                                        onchange="toggleRoleHighlight(this)"
                                        class="role-checkbox mt-1 h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $role->title }}
                                        </div>
                                        @if($role->slug)
                                            <code class="text-xs text-gray-500 dark:text-gray-400">{{ $role->slug }}</code>
                                        @endif
                                        @if($role->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ Str::limit($role->description, 50) }}
                                            </p>
                                        @endif
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                {{ $role->permissions_count ?? $role->permissions()->count() }} صلاحية
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Current Effective Permissions Preview --}}
                @if($user->roles->isNotEmpty())
                <div class="mb-6 pt-6 border-t dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">الصلاحيات الفعلية الحالية</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">هذه الصلاحيات مكتسبة من الأدوار المسندة حالياً:</p>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $effectivePermissions = collect();
                            foreach($user->roles as $role) {
                                $effectivePermissions = $effectivePermissions->merge($role->permissions);
                            }
                            $effectivePermissions = $effectivePermissions->unique('id');
                        @endphp
                        @forelse($effectivePermissions as $perm)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                {{ $perm->title }}
                            </span>
                        @empty
                            <span class="text-gray-500 dark:text-gray-400 text-sm">لا توجد صلاحيات</span>
                        @endforelse
                    </div>
                </div>
                @endif

                {{-- Submit Button --}}
                <div class="flex items-center justify-between pt-6 border-t dark:border-gray-700">
                    <a href="{{ url()->previous() }}"
                        class="px-4 py-2 text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        إلغاء
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        حفظ الأدوار
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleRoleHighlight(checkbox) {
            const label = checkbox.closest('.role-item');
            if (checkbox.checked) {
                label.classList.add('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-300', 'dark:border-indigo-700');
                label.classList.remove('hover:bg-gray-50', 'dark:hover:bg-gray-700');
            } else {
                label.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-300', 'dark:border-indigo-700');
                label.classList.add('hover:bg-gray-50', 'dark:hover:bg-gray-700');
            }
        }
    </script>
    @endpush
</x-app-layout>
