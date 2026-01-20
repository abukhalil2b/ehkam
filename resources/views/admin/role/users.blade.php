<x-app-layout title="مستخدمو الدور: {{ $role->title }}">
    <div class="p-4 md:p-6 max-w-6xl mx-auto">
        {{-- Header --}}
        <header
            class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-t-lg shadow-md border-b dark:border-gray-700">
            <div>
                <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    <a href="{{ route('admin.roles.index') }}" class="hover:text-indigo-600">الأدوار</a>
                    <span class="mx-2">/</span>
                    <span>{{ $role->title }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">إدارة مستخدمي الدور</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">حدد المستخدمين الذين لديهم هذا الدور</p>
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
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.768-.231-1.48-.634-2.072M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.768.231-1.48.634-2.072m0 0A6.001 6.001 0 0112 14c2.21 0 4.104 1.207 5.152 3">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">{{ $role->title }}</h2>
                    <p class="text-white/80">{{ $role->description ?? 'لا يوجد وصف' }}</p>
                    <span class="inline-block mt-1 bg-white/20 px-2 py-1 rounded text-sm">
                        {{ $role->users->count() }} مستخدم حالياً
                    </span>
                </div>
            </div>
        </div>

        {{-- Users Form --}}
        <form action="{{ route('admin.roles.users.update', $role) }}" method="POST">
            @csrf

            <div class="bg-white dark:bg-gray-800 p-6 rounded-b-lg shadow-xl border border-t-0 dark:border-gray-700">

                {{-- Quick Stats --}}
                <div class="flex items-center justify-between mb-6 pb-4 border-b dark:border-gray-700">
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            المستخدمون المحددون:
                            <strong id="selected-count" class="text-indigo-600 dark:text-indigo-400">
                                {{ $role->users->count() }}
                            </strong>
                            من {{ $allUsers->count() }}
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="selectAll()"
                            class="text-sm px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded hover:bg-green-200 transition">
                            تحديد الكل
                        </button>
                        <button type="button" onclick="deselectAll()"
                            class="text-sm px-3 py-1 bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 rounded hover:bg-red-200 transition">
                            إلغاء التحديد
                        </button>
                    </div>
                </div>

                {{-- Search Box --}}
                <div class="mb-4">
                    <input type="text" id="user-search" placeholder="البحث عن مستخدم..."
                        class="w-full md:w-1/2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        onkeyup="filterUsers()">
                </div>

                {{-- Users List --}}
                @if($allUsers->isEmpty())
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        <p>لا يوجد مستخدمون مسجلون في النظام</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-96 overflow-y-auto"
                        id="users-list">
                        @foreach($allUsers as $user)
                            @php $hasRole = $role->users->contains($user->id); @endphp
                            <label
                                class="user-item flex items-start gap-3 p-3 rounded-lg border dark:border-gray-600 cursor-pointer transition
                                        {{ $hasRole ? 'bg-indigo-50 dark:bg-indigo-900/30 border-indigo-300 dark:border-indigo-700' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                                data-username="{{ strtolower($user->name) }} {{ strtolower($user->email) }}">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" {{ $hasRole ? 'checked' : '' }}
                                    onchange="updateCount(); toggleHighlight(this)"
                                    class="user-checkbox mt-1 h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-gray-900 dark:text-white text-sm">
                                        {{ $user->name }}
                                    </div>
                                    <code class="text-xs text-gray-500 dark:text-gray-400 block truncate">
                                                {{ $user->email }}
                                            </code>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif

                {{-- Submit Button --}}
                <div class="mt-8 flex items-center justify-between pt-6 border-t dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        المستخدمون المحددون سيحصلون على جميع صلاحيات هذا الدور
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
                const checked = document.querySelectorAll('.user-checkbox:checked').length;
                document.getElementById('selected-count').textContent = checked;
            }

            function toggleHighlight(checkbox) {
                const label = checkbox.closest('.user-item');
                if (checkbox.checked) {
                    label.classList.add('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-300', 'dark:border-indigo-700');
                    label.classList.remove('hover:bg-gray-50', 'dark:hover:bg-gray-700');
                } else {
                    label.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-300', 'dark:border-indigo-700');
                    label.classList.add('hover:bg-gray-50', 'dark:hover:bg-gray-700');
                }
            }

            function selectAll() {
                document.querySelectorAll('.user-checkbox').forEach(cb => {
                    cb.checked = true;
                    toggleHighlight(cb);
                });
                updateCount();
            }

            function deselectAll() {
                document.querySelectorAll('.user-checkbox').forEach(cb => {
                    cb.checked = false;
                    toggleHighlight(cb);
                });
                updateCount();
            }

            function filterUsers() {
                const searchValue = document.getElementById('user-search').value.toLowerCase();
                document.querySelectorAll('.user-item').forEach(item => {
                    const username = item.dataset.username;
                    item.style.display = username.includes(searchValue) ? '' : 'none';
                });
            }
        </script>
    @endpush
</x-app-layout>