<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>لوحة كانبان - {{ $mission->title }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
  [x-cloak] {
            display: none !important;
        }

        .dragging {
            opacity: 0.5;
            transform: scale(0.95);
        }

        .drag-over {
            border: 2px dashed #3b82f6 !important;
            background: rgba(59, 130, 246, 0.05);
        }

        @media (max-width: 640px) {
            .task-card {
                padding: 12px;
            }

            .user-avatar {
                width: 24px;
                height: 24px;
                font-size: 12px;
            }
        }

        @media (min-width: 641px) and (max-width: 1024px) {
            .kanban-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .mobile-tab-button {
            flex: 1;
            padding: 12px 16px;
            font-size: 14px;
            white-space: nowrap;
        }

        .kanban-column {
            min-height: 500px;
            max-height: calc(100vh - 250px);
            overflow-y: auto;
        }

        .task-card {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border-left-width: 4px;
        }

        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .task-card.high-priority {
            border-left-color: var(--danger-color);
        }

        .task-card.medium-priority {
            border-left-color: var(--warning-color);
        }

        .task-card.low-priority {
            border-left-color: var(--success-color);
        }

        .drag-over {
            background-color: rgba(67, 97, 238, 0.1);
            border: 2px dashed var(--primary-color) !important;
        }

        .dragging {
            opacity: 0.5;
            transform: rotate(2deg);
        }

        @media (max-width: 768px) {
            .kanban-container {
                flex-direction: column !important;
                gap: 16px;
            }

            .kanban-column {
                min-height: 300px;
                max-height: 400px;
            }

            .mission-header {
                flex-direction: column;
                gap: 16px;
            }

            .mobile-stats {
                grid-template-columns: 1fr !important;
            }
        }

        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 transparent;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #cbd5e0;
            border-radius: 20px;
        }

        .modal-overlay {
            backdrop-filter: blur(4px);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            font-size: 14px;
            font-weight: 600;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50" x-data="kanbanDashboard">
    {{-- Top Navigation --}}
    <nav class="bg-white shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('mission.index') }}"
                    class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="hidden sm:inline">العودة إلى المهام الرئيسية</span>
                    <span class="sm:hidden">عودة</span>
                </a>

                <div class="flex items-center gap-4">
                    @if ($mission->isLeader(auth()->user()))
                        <button @click="openCreateModal"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2 transition shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="hidden sm:inline">مهمة جديدة</span>
                            <span class="sm:hidden">جديدة</span>
                        </button>
                    @endif

                    <div class="relative">
                        <div
                            class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6"  x-data="{ activeTab: 'pending' }">
        {{-- Mission Header --}}
        <div class="mission-header mb-8 bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $mission->title }}</h1>
                        <span
                            class="status-badge {{ $mission->status == 'active'
                                ? 'bg-green-100 text-green-800'
                                : ($mission->status == 'completed'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-gray-100 text-gray-800') }}">
                            {{ $mission->status == 'active' ? 'نشط' : ($mission->status == 'completed' ? 'مكتمل' : 'مؤرشف') }}
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm lg:text-base">{{ $mission->description }}</p>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 w-full lg:w-auto mobile-stats">
                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                        <p class="text-blue-600 text-sm">المسؤول</p>
                        <p class="font-bold text-gray-900 truncate">{{ $mission->leader->name }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg text-center">
                        <p class="text-green-600 text-sm">عدد المهام</p>
                        <p class="font-bold text-gray-900 text-xl">{{ count($tasks) }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg text-center col-span-2 lg:col-span-1">
                        <p class="text-purple-600 text-sm">الأعضاء</p>
                        <p class="font-bold text-gray-900">{{ count($members) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Status Tabs --}}
        <div class="lg:hidden mb-4 bg-white rounded-lg shadow-sm overflow-scroll">
            <div class="flex border-b">
                <button @click="activeTab = 'pending'"
                    :class="activeTab === 'pending' ? 'bg-blue-50 text-blue-600 border-b-2 border-blue-600' :
                        'text-gray-500 hover:text-gray-700'"
                    class="mobile-tab-button text-center font-medium">
                    قيد الانتظار
                    <span class="bg-gray-100 text-gray-800 rounded-full px-2 py-1 text-xs ml-1"
                        x-text="tasks.filter(t => t.status === 'pending').length"></span>
                </button>
                <button @click="activeTab = 'in_progress'"
                    :class="activeTab === 'in_progress' ? 'bg-blue-50 text-blue-600 border-b-2 border-blue-600' :
                        'text-gray-500 hover:text-gray-700'"
                    class="mobile-tab-button text-center font-medium">
                    قيد التنفيذ
                    <span class="bg-gray-100 text-gray-800 rounded-full px-2 py-1 text-xs ml-1"
                        x-text="tasks.filter(t => t.status === 'in_progress').length"></span>
                </button>
                <button @click="activeTab = 'completed'"
                    :class="activeTab === 'completed' ? 'bg-blue-50 text-blue-600 border-b-2 border-blue-600' :
                        'text-gray-500 hover:text-gray-700'"
                    class="mobile-tab-button text-center font-medium">
                    مكتمل
                    <span class="bg-gray-100 text-gray-800 rounded-full px-2 py-1 text-xs ml-1"
                        x-text="tasks.filter(t => t.status === 'completed').length"></span>
                </button>
                <button @click="activeTab = 'cancelled'"
                    :class="activeTab === 'cancelled' ? 'bg-blue-50 text-blue-600 border-b-2 border-blue-600' :
                        'text-gray-500 hover:text-gray-700'"
                    class="mobile-tab-button text-center font-medium">
                    ملغى
                    <span class="bg-gray-100 text-gray-800 rounded-full px-2 py-1 text-xs ml-1"
                        x-text="tasks.filter(t => t.status === 'cancelled').length"></span>
                </button>
            </div>

            {{-- Mobile Columns --}}
            <div class="p-4" x-show="activeTab === 'pending'">
                <div x-ref="pendingColumn" class="space-y-3 min-h-[400px]"
                    @dragover.prevent="if (draggedTaskId) $el.classList.add('drag-over')"
                    @dragleave="$el.classList.remove('drag-over')"
                    @drop="handleDrop($event, 'pending'); $el.classList.remove('drag-over')">
                    <template x-for="task in tasks.filter(t => t.status === 'pending')" :key="task.id">
                        <div @dragstart="startDrag(task.id, $event)" draggable="true"
                            :class="`task-card ${task.priority}-priority bg-white rounded-lg shadow p-4 cursor-move`">
                            {{-- Task content --}}
                        </div>
                    </template>
                </div>
            </div>
            {{-- Repeat for other tabs --}}
        </div>

        {{-- Desktop Kanban Board --}}
        <div class="hidden lg:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 kanban-container">
            @foreach (['pending' => 'قيد الانتظار', 'in_progress' => 'قيد التنفيذ', 'completed' => 'مكتمل', 'cancelled' => 'ملغى'] as $status => $label)
                <div class="bg-gray-50 rounded-xl p-4 shadow-sm kanban-column scrollbar-thin"
                    x-ref="{{ $status }}Column"
                    @dragover.prevent="if (draggedTaskId) $el.classList.add('drag-over')"
                    @dragleave="$el.classList.remove('drag-over')"
                    @drop="handleDrop($event, '{{ $status }}'); $el.classList.remove('drag-over')">

                    <div class="sticky top-0 bg-gray-50 pt-2 pb-4 z-10">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-lg text-gray-900">{{ $label }}</h3>
                                <span class="bg-white px-2 py-1 rounded-full text-sm font-semibold text-gray-700"
                                    x-text="tasks.filter(t => t.status === '{{ $status }}').length"></span>
                            </div>
                            <span class="text-xs text-gray-500">
                                @if ($status == 'pending')
                                    اسحب المهمة هنا
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <template x-for="task in tasks.filter(t => t.status === '{{ $status }}')"
                            :key="task.id">
                            <div @dragstart="startDrag(task.id, $event)" draggable="true"
                                @click="openTaskDetails(task)"
                                :class="`task-card ${task.priority}-priority bg-white rounded-lg shadow p-4 cursor-move relative`"
                                :data-task-id="task.id">

                                {{-- Priority Indicator --}}
                                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-2">
                                    <div :class="{
                                        'bg-red-500': task.priority === 'high',
                                        'bg-yellow-500': task.priority === 'medium',
                                        'bg-green-500': task.priority === 'low'
                                    }"
                                        class="w-2 h-2 rounded-full"></div>
                                </div>

                                {{-- Task Header --}}
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-semibold text-gray-900 pr-2" x-text="task.title"></h4>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs px-2 py-1 rounded font-semibold"
                                            :class="{
                                                'bg-red-100 text-red-800': task.priority === 'high',
                                                'bg-yellow-100 text-yellow-800': task.priority === 'medium',
                                                'bg-green-100 text-green-800': task.priority === 'low'
                                            }"
                                            x-text="task.priority === 'high' ? 'عالي' :(task.priority === 'medium' ? 'متوسط' : 'منخفض')"></span>
                                        <template x-if="task.is_private">
                                            <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">
                                                خاص
                                            </span>
                                        </template>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2"
                                    x-text="task.description || 'لا يوجد وصف للمهمة'"></p>

                                {{-- Footer --}}
                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    {{-- Assigned User --}}
                                    <div class="flex items-center gap-2">
                                        <template x-if="getMember(task.assigned_to)">
                                            <div class="flex items-center gap-2">
                                                <div class="user-avatar rounded-full flex items-center justify-center text-white text-xs"
                                                    :style="{ backgroundColor: getMemberColor(task.assigned_to) }"
                                                    x-text="getMember(task.assigned_to).name.charAt(0)"></div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900"
                                                        x-text="getMember(task.assigned_to).name"></p>
                                                    <p class="text-xs text-gray-500">المسؤول</p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Due Date --}}
                                    <template x-if="task.due_date">
                                        <div class="text-right"
                                            :class="{ 'text-red-600 font-semibold': isOverdue(task.due_date) }">
                                            <p class="text-xs text-gray-500 mb-1">تاريخ التسليم</p>
                                            <p class="text-sm" x-text="formatDate(task.due_date)"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Empty State --}}
                        <div x-show="tasks.filter(t => t.status === '{{ $status }}').length === 0"
                            class="text-center py-8 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="text-gray-500">لا توجد مهام هنا</p>
                            <p class="text-sm text-gray-400 mt-1">اسحب مهام من الأعمدة الأخرى</p>
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
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="showCreateModal = false"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto">
                {{-- Modal Header --}}
                <div class="border-b px-6 py-4 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">إنشاء مهمة جديدة</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ $mission->title }}</p>
                    </div>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form @submit.prevent="createTask" class="p-6 space-y-5">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">عنوان المهمة *</label>
                        <input type="text" x-model="newTask.title" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                            placeholder="أدخل عنوان المهمة">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <textarea x-model="newTask.description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="وصف تفصيلي للمهمة (اختياري)"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Priority --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الأولوية *</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" @click="newTask.priority = 'low'"
                                    :class="newTask.priority === 'low' ?
                                        'bg-green-100 border-green-500 text-green-700' :
                                        'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200'"
                                    class="py-2 border rounded-lg text-center text-sm font-medium transition">
                                    منخفض
                                </button>
                                <button type="button" @click="newTask.priority = 'medium'"
                                    :class="newTask.priority === 'medium' ?
                                        'bg-yellow-100 border-yellow-500 text-yellow-700' :
                                        'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200'"
                                    class="py-2 border rounded-lg text-center text-sm font-medium transition">
                                    متوسط
                                </button>
                                <button type="button" @click="newTask.priority = 'high'"
                                    :class="newTask.priority === 'high' ?
                                        'bg-red-100 border-red-500 text-red-700' :
                                        'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200'"
                                    class="py-2 border rounded-lg text-center text-sm font-medium transition">
                                    عالي
                                </button>
                            </div>
                        </div>

                        {{-- Due Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ التسليم</label>
                            <input type="date" x-model="newTask.due_date"
                                :min="new Date().toISOString().split('T')[0]"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    {{-- Assigned To Field (Always Visible) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المسؤول عن المهمة *</label>
                        <select x-model="newTask.assigned_to" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">اختر المسؤول</option>
                            @foreach ($members as $member)
                                <option value="{{ $member['id'] }}" @if ($mission->isLeader(auth()->user()) && $loop->first) selected @endif>
                                    {{ $member['name'] }}
                                    @if ($member['id'] == auth()->id())
                                        (أنت)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-2">
                            @if (!$mission->isLeader(auth()->user()))
                                <span class="text-blue-600">ملاحظة:</span> المهام التي تنشئها ستكون خاصة بك بشكل
                                افتراضي
                            @endif
                        </p>
                    </div>

                    {{-- Privacy Setting (Only for leaders) --}}
                    @if ($mission->isLeader(auth()->user()))
                        <div class="bg-blue-50 p-4 rounded-xl">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" x-model="newTask.is_private" class="sr-only peer">
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                                <span class="mr-3 text-sm font-medium text-gray-900">
                                    مهمة خاصة
                                    <span class="block text-xs text-gray-600 mt-1">سترى المهمة فقط أنت والمسؤول
                                        عنها</span>
                                </span>
                            </label>
                        </div>
                    @else
                        <input type="hidden" x-model="newTask.is_private" value="true">
                    @endif

                    {{-- Modal Footer --}}
                    <div class="border-t pt-6 flex justify-end gap-3">
                        <button type="button" @click="showCreateModal = false"
                            class="px-6 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition font-medium">
                            إلغاء
                        </button>
                        <button type="submit" :disabled="isSubmitting"
                            class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting" class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                إنشاء المهمة
                            </span>
                            <span x-show="isSubmitting" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                جاري الإنشاء...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Task Details Modal (Placeholder) --}}
    <div x-show="showTaskDetails" x-cloak @click.away="showTaskDetails = false"
        class="fixed inset-0 z-50 overflow-y-auto modal-overlay">
        {{-- Task details content would go here --}}
    </div>

    {{-- Notification Toast --}}
    <div x-show="notificationVisible" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-2 opacity-0" class="fixed bottom-4 right-4 z-50">
        <div :class="{
            'bg-green-100 border-green-400 text-green-700': notificationType === 'success',
            'bg-red-100 border-red-400 text-red-700': notificationType === 'error',
            'bg-blue-100 border-blue-400 text-blue-700': notificationType === 'info'
        }"
            class="border rounded-xl px-6 py-4 shadow-lg max-w-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                showTaskDetails: false,
                isSubmitting: false,
                draggedTaskId: null,
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
                }
            }));
        });
    </script>

</body>

</html>
