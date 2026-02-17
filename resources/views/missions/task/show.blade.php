<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>لوحة كانبان - {{ $mission->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200"
    x-data="kanbanDashboard" :class="{ 'dark': $store.app.theme === 'dark' || $store.app.isDarkMode }">
    {{-- Top Navigation --}}
    <nav
        class="bg-white dark:bg-gray-800 shadow-sm border-b dark:border-gray-700 sticky top-0 z-40 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                @if ($mission->isLeader(auth()->user()))
                    <button @click="openCreateModal"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2 transition shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">مهمة جديدة</span>
                        <span class="sm:hidden">جديدة</span>
                    </button>
                @endif
                <div class="relative">
                    {{ auth()->user()->name }}
                </div>
                <div class="flex items-center gap-4">

                    {{-- Dark Mode Toggle --}}
                    <button type="button"
                        class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300"
                        @click="$store.app.toggleTheme($store.app.theme === 'dark' ? 'light' : 'dark')">
                        <svg x-show="$store.app.theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                        <svg x-show="$store.app.theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </button>
                    <a href="{{ route('mission.index') }}"
                        class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <x-svgicon.back_arrow/>
                        <span>عودة </span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4" x-data="{ activeTab: 'pending' }">
        {{-- Mission Header --}}
        <div
            class="mission-header mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 transition-colors duration-200">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white truncate">{{ $mission->title }}</h1>
                        <span
                            class="px-2 py-0.5 rounded text-[10px] font-bold {{ $mission->status == 'active'
                                ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400'
                                : ($mission->status == 'completed'
                                    ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400'
                                    : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300') }}">
                            {{ $mission->status == 'active' ? 'نشط' : ($mission->status == 'completed' ? 'مكتمل' : 'مؤرشف') }}
                        </span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-[13px] leading-snug line-clamp-2 max-w-2xl">
                        {{ $mission->description }}
                    </p>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 w-full lg:w-auto shrink-0">
                    <div
                        class="bg-blue-50/50 dark:bg-blue-900/20 p-2.5 rounded-lg border border-blue-100 dark:border-blue-800/40">
                        <p class="text-blue-700 dark:text-blue-400 text-[10px] font-bold mb-0.5">المسؤول</p>
                        <p class="font-bold text-gray-900 dark:text-gray-100 text-xs truncate max-w-[100px]"
                            title="{{ $mission->leader->name }}">
                            {{ $mission->leader->name }}
                        </p>
                    </div>
                    <div
                        class="bg-green-50/50 dark:bg-green-900/20 p-2.5 rounded-lg border border-green-100 dark:border-green-800/40 text-center">
                        <p class="text-green-700 dark:text-green-400 text-[10px] font-bold mb-0.5">المهام</p>
                        <p class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ count($tasks) }}</p>
                    </div>
                    <div
                        class="bg-purple-50/50 dark:bg-purple-900/20 p-2.5 rounded-lg border border-purple-100 dark:border-purple-800/40 text-center col-span-2 lg:col-span-1">
                        <p class="text-purple-700 dark:text-purple-400 text-[10px] font-bold mb-0.5">الأعضاء</p>
                        <p class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ count($members) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Desktop Kanban Board --}}
        <div class="hidden lg:grid grid-cols-4 gap-4 kanban-container">
            @foreach (['pending' => 'قيد الانتظار', 'in_progress' => 'قيد التنفيذ', 'completed' => 'مكتمل', 'cancelled' => 'ملغى'] as $status => $label)
                <div class="bg-gray-50 dark:bg-gray-800/40 rounded-xl p-3 border border-gray-200 dark:border-gray-700/50 flex flex-col h-full"
                    x-ref="{{ $status }}Column"
                    @dragover.prevent="if (draggedTaskId) $el.classList.add('ring-2', 'ring-blue-500/20')"
                    @dragleave="$el.classList.remove('ring-2', 'ring-blue-500/20')"
                    @drop="handleDrop($event, '{{ $status }}'); $el.classList.remove('ring-2', 'ring-blue-500/20')">

                    <div class="flex justify-between items-center mb-4 px-1">
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-[14px] text-gray-800 dark:text-gray-200">{{ $label }}</h3>
                            <span
                                class="bg-gray-200 dark:bg-gray-700 px-1.5 py-0.5 rounded text-[10px] font-bold text-gray-600 dark:text-gray-300"
                                x-text="tasks.filter(t => t.status === '{{ $status }}').length"></span>
                        </div>
                    </div>

                    <div class="space-y-2.5 min-h-[150px]">
                        <template x-for="task in tasks.filter(t => t.status === '{{ $status }}')"
                            :key="task.id">
                            <div @dragstart="startDrag(task.id, $event)" draggable="true" @click="openTaskDetails(task)"
                                :class="`task-card ${task.priority}-priority bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow p-3 cursor-move border border-gray-200 dark:border-gray-700 transition-all group`"
                                :data-task-id="task.id">

                                {{-- Task Header --}}
                                <div class="flex justify-between items-start gap-2 mb-2">
                                    <h4 class="font-bold text-gray-900 dark:text-gray-100 leading-tight text-[13px] line-clamp-2 flex-1"
                                        x-text="task.title"></h4>

                                    <div
                                        class="flex gap-1 shrink-0 opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click.stop="openEditModal(task)"
                                            class="p-1 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <p class="text-gray-500 dark:text-gray-400 text-[11px] mb-3 line-clamp-1"
                                    x-text="task.description || 'لا يوجد وصف'"></p>

                                {{-- Footer --}}
                                <div
                                    class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center gap-1.5 min-w-0 flex-1">
                                        <template x-if="getMember(task.assigned_to)">
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <div class="w-5 h-5 rounded-full flex-shrink-0 flex items-center justify-center text-[9px] font-bold text-white shadow-sm"
                                                    :style="{ backgroundColor: getMemberColor(task.assigned_to) }"
                                                    x-text="getMember(task.assigned_to).name.charAt(0)"></div>
                                                <p class="text-[11px] font-medium text-gray-600 dark:text-gray-300 truncate"
                                                    x-text="getMember(task.assigned_to).name"></p>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        <template x-if="task.due_date">
                                            <div class="flex items-center gap-1 text-[10px] font-bold"
                                                :class="isOverdue(task.due_date) ? 'text-red-600 dark:text-red-400' :
                                                    'text-gray-400 dark:text-gray-500'">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <span x-text="formatDate(task.due_date)"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Empty State --}}
                        <div x-show="tasks.filter(t => t.status === '{{ $status }}').length === 0"
                            class="text-center py-6 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg">
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">لا توجد مهام</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    {{-- Create Task Modal --}}
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto modal-overlay"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm transition-opacity"
                @click="showCreateModal = false"></div>

            <div
                class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-auto flex flex-col max-h-[90vh] transition-all transform">
                {{-- Modal Header --}}
                <div class="border-b dark:border-gray-700 px-5 py-3 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">إنشاء مهمة جديدة</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">{{ $mission->title }}</p>
                    </div>
                    <button @click="showCreateModal = false"
                        class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Form --}}
                <div class="overflow-y-auto p-5 scrollbar-thin">
                    <form @submit.prevent="createTask" class="space-y-4" id="createTaskForm">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">عنوان
                                المهمة *</label>
                            <input type="text" x-model="newTask.title" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                placeholder="أدخل عنوان المهمة">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">الوصف</label>
                            <textarea x-model="newTask.description" rows="2"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                placeholder="وصف مختصر..."></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">الأولوية</label>
                                <div class="flex bg-gray-100 dark:bg-gray-700 p-1 rounded-lg">
                                    <button type="button" @click="newTask.priority = 'low'"
                                        :class="newTask.priority === 'low' ?
                                            'bg-white dark:bg-gray-600 shadow-sm text-green-600' : 'text-gray-500'"
                                        class="flex-1 py-1 text-[11px] font-medium rounded-md transition-all">منخفض</button>
                                    <button type="button" @click="newTask.priority = 'medium'"
                                        :class="newTask.priority === 'medium' ?
                                            'bg-white dark:bg-gray-600 shadow-sm text-yellow-600' : 'text-gray-500'"
                                        class="flex-1 py-1 text-[11px] font-medium rounded-md transition-all">متوسط</button>
                                    <button type="button" @click="newTask.priority = 'high'"
                                        :class="newTask.priority === 'high' ?
                                            'bg-white dark:bg-gray-600 shadow-sm text-red-600' : 'text-gray-500'"
                                        class="flex-1 py-1 text-[11px] font-medium rounded-md transition-all">عالي</button>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">تاريخ
                                    التسليم</label>
                                <input type="date" x-model="newTask.due_date"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">المسؤول
                                عن المهمة *</label>
                            <select x-model="newTask.assigned_to" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">اختر المسؤول</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member['id'] }}">{{ $member['name'] }}
                                        @if ($member['id'] == auth()->id())
                                            (أنت)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if ($mission->isLeader(auth()->user()))
                            <div
                                class="flex items-center justify-between p-3 bg-blue-50/50 dark:bg-blue-900/10 rounded-lg border border-blue-100 dark:border-blue-800/30">
                                <span class="text-xs font-medium text-blue-800 dark:text-blue-300">جعل المهمة
                                    خاصة</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="newTask.is_private" class="sr-only peer">
                                    <div
                                        class="w-8 h-4 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all">
                                    </div>
                                </label>
                            </div>
                        @endif
                    </form>
                </div>

                {{-- Modal Footer --}}
                <div
                    class="border-t dark:border-gray-700 p-4 flex justify-end gap-2 bg-gray-50/50 dark:bg-gray-800/50 rounded-b-xl">
                    <button type="button" @click="showCreateModal = false"
                        class="px-4 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">إلغاء</button>
                    <button type="submit" form="createTaskForm" :disabled="isSubmitting"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition disabled:opacity-50">
                        <span x-show="!isSubmitting">إنشاء المهمة</span>
                        <span x-show="isSubmitting">جاري...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Task Modal --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto modal-overlay"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showEditModal = false"></div>
            <div
                class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-auto flex flex-col max-h-[90vh]">
                {{-- Modal Header --}}
                <div class="border-b dark:border-gray-700 px-5 py-3 flex justify-between items-center shrink-0">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">تعديل المهمة</h3>
                    <button @click="showEditModal = false"
                        class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="overflow-y-auto p-5 scrollbar-thin">
                    <form @submit.prevent="updateTask" class="space-y-4" id="updateTaskForm">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">عنوان
                                المهمة *</label>
                            <input type="text" x-model="editingTask.title" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">الوصف</label>
                            <textarea x-model="editingTask.description" rows="2"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">الأولوية</label>
                                <div class="flex bg-gray-100 dark:bg-gray-700 p-1 rounded-lg">
                                    <button type="button" @click="editingTask.priority = 'low'"
                                        :class="editingTask.priority === 'low' ?
                                            'bg-white dark:bg-gray-600 shadow-sm text-green-600' : 'text-gray-500'"
                                        class="flex-1 py-1 text-[11px] font-medium rounded-md transition-all">منخفض</button>
                                    <button type="button" @click="editingTask.priority = 'medium'"
                                        :class="editingTask.priority === 'medium' ?
                                            'bg-white dark:bg-gray-600 shadow-sm text-yellow-600' : 'text-gray-500'"
                                        class="flex-1 py-1 text-[11px] font-medium rounded-md transition-all">متوسط</button>
                                    <button type="button" @click="editingTask.priority = 'high'"
                                        :class="editingTask.priority === 'high' ?
                                            'bg-white dark:bg-gray-600 shadow-sm text-red-600' : 'text-gray-500'"
                                        class="flex-1 py-1 text-[11px] font-medium rounded-md transition-all">عالي</button>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">تاريخ
                                    التسليم</label>
                                <input type="date" x-model="editingTask.due_date"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>

                        @if ($mission->isLeader(auth()->user()))
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">المسؤول
                                        *</label>
                                    <select x-model="editingTask.assigned_to" required
                                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                        @foreach ($members as $member)
                                            <option value="{{ $member['id'] }}">{{ $member['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">الحالة</label>
                                    <select x-model="editingTask.status"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="pending">قيد الانتظار</option>
                                        <option value="in_progress">قيد التنفيذ</option>
                                        <option value="completed">مكتمل</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>

                {{-- Modal Footer --}}
                <div
                    class="border-t dark:border-gray-700 p-4 flex justify-end gap-2 bg-gray-50/50 dark:bg-gray-800/50 rounded-b-xl">
                    <button type="button" @click="showEditModal = false"
                        class="px-4 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">إلغاء</button>
                    <button type="submit" form="updateTaskForm" :disabled="isUpdating"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition disabled:opacity-50 shadow-sm">
                        <span x-show="!isUpdating">حفظ التغييرات</span>
                        <span x-show="isUpdating">جاري...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto modal-overlay"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm transition-opacity"
                @click="showDeleteModal = false"></div>
            <div
                class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md mx-auto transition-all transform animate-fade-in-up">
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">تأكيد الحذف</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">هل أنت متأكد من حذف هذه المهمة؟
                            </p>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg mb-4 border border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-200" x-text="deletingTask?.title">
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">لا يمكن التراجع عن هذا الإجراء</p>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button @click="showDeleteModal = false"
                            class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition font-medium">إلغاء</button>
                        <button @click="deleteTask" :disabled="isDeleting"
                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium disabled:opacity-50 shadow-md hover:shadow-lg focus:ring-4 focus:ring-red-500/30">
                            <span x-show="!isDeleting">حذف</span>
                            <span x-show="isDeleting" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                جاري الحذف...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notification Toast --}}
    <div x-show="notificationVisible" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-2 opacity-0" class="fixed bottom-4 right-4 z-50">
        <div :class="{
            'bg-green-100 border-green-400 text-green-700 dark:bg-green-900/80 dark:border-green-600 dark:text-green-200': notificationType === 'success',
            'bg-red-100 border-red-400 text-red-700 dark:bg-red-900/80 dark:border-red-600 dark:text-red-200': notificationType === 'error',
            'bg-blue-100 border-blue-400 text-blue-700 dark:bg-blue-900/80 dark:border-blue-600 dark:text-blue-200': notificationType === 'info'
        }"
            class="border rounded-xl px-6 py-4 shadow-lg max-w-sm backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="notificationType === 'success'" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                    <path x-show="notificationType === 'error'" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    <path x-show="notificationType === 'info'" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-text="notificationMessage"></span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kanbanDashboard', () => ({
                // Core Data
                tasks: @json($tasks),
                members: @json($members),
                colors: @json($colors),

                // UI States
                showCreateModal: false,
                showEditModal: false,
                showDeleteModal: false,
                showTaskDetails: false,
                isSubmitting: false,
                isUpdating: false,
                isDeleting: false,
                draggedTaskId: null,
                editingTask: {},
                deletingTask: null,
                statusMap: {
                    'pending': 'قيد الانتظار',
                    'in_progress': 'قيد التنفيذ',
                    'completed': 'مكتمل',
                    'cancelled': 'ملغى'
                },

                // Notification System State
                notificationVisible: false,
                notificationMessage: '',
                notificationType: 'info',

                // New Task Form
                newTask: {
                    title: '',
                    description: '',
                    priority: 'medium',
                    assigned_to: "{{ $mission->isLeader(auth()->user()) && count($members) ? $members[0]['id'] : auth()->id() }}",
                    is_private: {{ $mission->isLeader(auth()->user()) ? 'false' : 'true' }} === true ?
                        true : false,
                    due_date: ''
                },

                // Member Utilities
                getMember(userId) {
                    return this.members.find(member => member.id == userId);
                },

                getMemberColor(userId) {
                    const member = this.getMember(userId);
                    if (!member) return '#94a3b8';
                    const index = this.members.findIndex(m => parseInt(m.id) === parseInt(userId));
                    return this.colors[index % this.colors.length] || '#94a3b8';
                },

                // Date Utilities
                isOverdue(dueDate) {
                    if (!dueDate) return false;
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const due = new Date(dueDate);
                    due.setHours(0, 0, 0, 0);
                    return due < today;
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('ar-EG', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                },

                // Modal Management
                openCreateModal() {
                    this.resetNewTaskForm();
                    this.showCreateModal = true;
                },

                resetNewTaskForm() {
                    this.newTask = {
                        title: '',
                        description: '',
                        priority: 'medium',
                        assigned_to: {{ $mission->isLeader(auth()->user()) && count($members) ? $members[0]['id'] : auth()->id() }},
                        is_private: {{ $mission->isLeader(auth()->user()) ? 'false' : 'true' }} ===
                            true ? true : false,
                        due_date: ''
                    };
                },

                // Task Creation
                async createTask() {
                    if (!this.newTask.title.trim()) {
                        this.showNotification('يرجى إدخال عنوان المهمة', 'error');
                        return;
                    }

                    this.isSubmitting = true;

                    try {
                        const response = await fetch(
                            "{{ route('missions.task.store', $mission) }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify(this.newTask)
                            });

                        const data = await response.json();

                        if (response.ok) {
                            this.tasks.push({
                                ...data.task,
                                is_private: Boolean(data.task.is_private),
                                status: 'pending'
                            });

                            this.showCreateModal = false;
                            this.showNotification('تم إنشاء المهمة بنجاح', 'success');
                        } else {
                            throw new Error(data.message || 'حدث خطأ أثناء إنشاء المهمة');
                        }
                    } catch (error) {
                        this.showNotification(error.message, 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                // Drag & Drop
                startDrag(taskId, event) {
                    this.draggedTaskId = String(taskId);
                    event.dataTransfer.setData('text/plain', taskId);
                    event.dataTransfer.effectAllowed = 'move';
                    event.target.classList.add('dragging');
                },

                async handleDrop(event, newStatus) {
                    event.preventDefault();
                    if (!this.draggedTaskId) return;

                    const taskId = this.draggedTaskId;
                    const task = this.tasks.find(t => String(t.id) === String(taskId));


                    if (!task || task.status === newStatus) {
                        this.draggedTaskId = null;
                        return;
                    }

                    const oldStatus = task.status;
                    task.status = newStatus;

                    try {
                        const url = "{{ route('missions.task.status', [$mission, 'TASK_ID']) }}"
                            .replace('TASK_ID', taskId);
                        const response = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        });

                        if (!response.ok) {
                            task.status = oldStatus;
                            throw new Error('فشل في تحديث حالة المهمة');
                        }

                        this.showNotification('تم تحديث حالة المهمة', 'success');
                    } catch (error) {
                        this.showNotification(error.message, 'error');
                    }

                    this.draggedTaskId = null;
                },

                showNotification(message, type = 'info') {
                    this.notificationMessage = message;
                    this.notificationType = type;
                    this.notificationVisible = true;
                    setTimeout(() => {
                        this.notificationVisible = false;
                    }, 3000);
                },

                // Edit Task
                openEditModal(task) {
                    this.editingTask = {
                        id: task.id,
                        title: task.title,
                        description: task.description || '',
                        priority: task.priority,
                        assigned_to: task.assigned_to,
                        due_date: task.due_date || '',
                        status: task.status,
                        is_private: task.is_private
                    };
                    this.showEditModal = true;
                },

                async updateTask() {
                    if (!this.editingTask.title.trim()) {
                        this.showNotification('يرجى إدخال عنوان المهمة', 'error');
                        return;
                    }

                    this.isUpdating = true;
                    try {
                        const url = "{{ route('missions.task.update', [$mission, 'TASK_ID']) }}"
                            .replace('TASK_ID', this.editingTask.id);
                        const response = await fetch(url, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(this.editingTask)
                        });

                        const data = await response.json();
                        if (response.ok) {
                            const index = this.tasks.findIndex(t => t.id == this.editingTask.id);
                            if (index !== -1) {
                                this.tasks[index] = {
                                    ...data.task,
                                    is_private: Boolean(data.task.is_private)
                                };
                            }
                            this.showEditModal = false;
                            this.showNotification('تم تحديث المهمة بنجاح', 'success');
                        } else {
                            throw new Error(data.message || 'حدث خطأ أثناء تحديث المهمة');
                        }
                    } catch (error) {
                        this.showNotification(error.message, 'error');
                    } finally {
                        this.isUpdating = false;
                    }
                },

                // Delete Task
                confirmDelete(task) {
                    this.deletingTask = task;
                    this.showDeleteModal = true;
                },

                async deleteTask() {
                    if (!this.deletingTask) return;

                    this.isDeleting = true;
                    try {
                        const url = "{{ route('missions.task.destroy', [$mission, 'TASK_ID']) }}"
                            .replace('TASK_ID', this.deletingTask.id);
                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();
                        if (response.ok) {
                            this.tasks = this.tasks.filter(t => t.id != this.deletingTask.id);
                            this.showDeleteModal = false;
                            this.deletingTask = null;
                            this.showNotification('تم حذف المهمة بنجاح', 'success');
                        } else {
                            throw new Error(data.message || 'حدث خطأ أثناء حذف المهمة');
                        }
                    } catch (error) {
                        this.showNotification(error.message, 'error');
                    } finally {
                        this.isDeleting = false;
                    }
                }
            }));
        });
    </script>

    <script src="/assets/js/custom.js"></script>
</body>

</html>
