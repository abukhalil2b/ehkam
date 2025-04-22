<x-app-layout>
    {{-- Define a dummy user ID and dummy data arrays --}}
    {{-- In a real app, replace 1 with Auth::id() and populate dummy data --}}
    @php
        $currentUserId = Auth::id() ?? 1; // Use logged in user or a dummy ID
        $dummyUsers = \App\Models\User::all(['id', 'name']); // Fetch actual users or use dummy array
        $dummyTasks = [
            [
                'id' => 1,
                'user_id' => $currentUserId, // Created by current user
                'assigned_to_id' => $currentUserId, // Assigned to current user
                'title' => 'إكمال تقرير المشروع س',
                'description' => 'كتابة وتدقيق التقرير النهائي للمشروع س وتسليمه للرئيس المباشر',
                'assigned_date' => '2025-05-01',
                'done_date' => null,
                'status' => 'In Progress',
                'priority' => 'High',
                'created_by' => ['name' => \App\Models\User::find($currentUserId)->name ?? 'المستخدم الحالي'],
                'assigned_to' => ['name' => \App\Models\User::find($currentUserId)->name ?? 'المستخدم الحالي'],
            ],
            [
                'id' => 2,
                'user_id' => 99, // Created by another user (dummy)
                'assigned_to_id' => $currentUserId, // Assigned to current user
                'title' => 'مراجعة مستندات العقد',
                'description' => 'مراجعة البنود القانونية في مستندات العقد مع الشركة ص',
                'assigned_date' => '2025-04-28',
                'done_date' => null,
                'status' => 'Open',
                'priority' => 'Urgent',
                'created_by' => ['name' => 'فريق العمل'],
                'assigned_to' => ['name' => \App\Models\User::find($currentUserId)->name ?? 'المستخدم الحالي'],
            ],
            [
                'id' => 3,
                'user_id' => $currentUserId, // Created by current user
                'assigned_to_id' => 99, // Assigned to another user (dummy)
                'title' => 'إعداد عرض تقديمي',
                'description' => 'إعداد الشرائح اللازمة للاجتماع القادم',
                'assigned_date' => '2025-05-05',
                'done_date' => null,
                'status' => 'Open',
                'priority' => 'Medium',
                'created_by' => ['name' => \App\Models\User::find($currentUserId)->name ?? 'المستخدم الحالي'],
                'assigned_to' => ['name' => 'مستخدم آخر'],
            ],
            [
                'id' => 4,
                'user_id' => $currentUserId, // Created by current user
                'assigned_to_id' => $currentUserId, // Assigned to current user
                'title' => 'مهمة مكتملة تجريبية',
                'description' => 'هذه المهمة تم إكمالها لتوضيح الحالة المكتملة',
                'assigned_date' => '2025-04-10',
                'done_date' => '2025-04-18',
                'status' => 'Completed',
                'priority' => 'Low',
                'created_by' => ['name' => \App\Models\User::find($currentUserId)->name ?? 'المستخدم الحالي'],
                'assigned_to' => ['name' => \App\Models\User::find($currentUserId)->name ?? 'المستخدم الحالي'],
            ],
        ];
        // Convert PHP arrays to JSON for Alpine.js
        $usersJson = json_encode($dummyUsers->toArray());
        $tasksJson = json_encode($dummyTasks);
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة المهام') }}
        </h2>
    </x-slot>

    {{-- This wrapper div will hold the Alpine.js state and logic --}}
    <div class="py-12" x-data="{
        // Inject dummy data and current user ID
        allTasks: {{ $tasksJson }},
        users: {{ $usersJson }},
        currentUserId: {{ $currentUserId }}, // User ID for filtering
    
        filter: 'my_tasks', // 'my_tasks', 'assigned_tasks', 'all'
    
        showCreateModal: false,
        showEditModal: false,
        nextTaskId: {{ count($dummyTasks) + 1 }}, // Simple ID counter
    
        // Form Data
        formData: {
            taskId: null,
            title: '',
            description: '',
            assigned_date: '',
            assigned_to_id: '',
            status: 'Open',
            priority: 'Medium',
            done_date: '',
        },
    
        // Flash Messages
        flashMessage: '',
        flashType: 'success', // 'success' or 'error'
    
        // Computed property for filtered tasks
        get filteredTasks() {
            return this.allTasks.filter(task => {
                if (this.filter === 'my_tasks') {
                    return task.user_id === this.currentUserId;
                } else if (this.filter === 'assigned_tasks') {
                    return task.assigned_to_id === this.currentUserId;
                }
                // 'all' filter shows tasks created by OR assigned to current user
                return task.user_id === this.currentUserId || task.assigned_to_id === this.currentUserId;
            });
        },
    
        // --- Methods ---
    
        // Open Create Modal
        openCreateModal() {
            this.resetForm();
            this.showCreateModal = true;
        },
    
        // Open Edit Modal
        openEditModal(task) {
            this.resetForm();
            // Populate form data from the selected task object
            this.formData = {
                taskId: task.id,
                title: task.title,
                description: task.description,
                assigned_date: task.assigned_date,
                assigned_to_id: task.assigned_to_id,
                status: task.status,
                priority: task.priority,
                done_date: task.done_date,
            };
            this.showEditModal = true;
        },
    
        // Close Modals
        closeModal() {
            this.showCreateModal = false;
            this.showEditModal = false;
            this.resetForm();
        },
    
        // Reset Form Data
        resetForm() {
            this.formData = {
                taskId: null,
                title: '',
                description: '',
                assigned_date: '',
                assigned_to_id: '',
                status: 'Open',
                priority: 'Medium',
                done_date: '',
            };
        },
    
        // Create Task (Client-side only)
        createTask() {
            // Simple client-side validation (can be expanded)
            if (!this.formData.title) {
                this.showFlashMessage('عنوان المهمة مطلوب.', 'error');
                return;
            }
    
            const newUser = this.users.find(u => u.id == this.formData.assigned_to_id); // Find assigned user object
            const creator = this.users.find(u => u.id == this.currentUserId); // Find creator user object
    
            const newTask = {
                id: this.nextTaskId++, // Assign a simple unique ID
                user_id: this.currentUserId, // Creator is current user
                assigned_to_id: parseInt(this.formData.assigned_to_id) || null, // Ensure integer or null
                title: this.formData.title,
                description: this.formData.description,
                assigned_date: this.formData.assigned_date,
                done_date: null, // Always null on creation
                status: this.formData.status,
                priority: this.formData.priority,
                // Attach user objects for display
                created_by: { name: creator ? creator.name : 'المستخدم الحالي' },
                assigned_to: newUser ? { name: newUser.name, id: newUser.id } : null,
            };
    
            this.allTasks.push(newTask); // Add task to the main array
    
            this.closeModal();
            this.showFlashMessage('تم إنشاء المهمة بنجاح.', 'success');
        },
    
        // Update Task (Client-side only)
        updateTask() {
            // Simple client-side validation
            if (!this.formData.title) {
                this.showFlashMessage('عنوان المهمة مطلوب.', 'error');
                return;
            }
    
            const taskIndex = this.allTasks.findIndex(t => t.id === this.formData.taskId);
    
            if (taskIndex === -1) {
                this.showFlashMessage('خطأ: المهمة غير موجودة.', 'error');
                return;
            }
    
            const updatedAssignedUser = this.users.find(u => u.id == this.formData.assigned_to_id);
    
            // Update the task properties
            this.allTasks[taskIndex].title = this.formData.title;
            this.allTasks[taskIndex].description = this.formData.description;
            this.allTasks[taskIndex].assigned_date = this.formData.assigned_date;
            this.allTasks[taskIndex].assigned_to_id = parseInt(this.formData.assigned_to_id) || null;
            this.allTasks[taskIndex].status = this.formData.status;
            this.allTasks[taskIndex].priority = this.formData.priority;
            this.allTasks[taskIndex].done_date = this.formData.done_date;
    
            // Update assigned_to object for display
            this.allTasks[taskIndex].assigned_to = updatedAssignedUser ? { name: updatedAssignedUser.name, id: updatedAssignedUser.id } : null;
    
    
            // Optional: Auto-set done_date if status becomes Completed
            if (this.formData.status === 'Completed' && !this.formData.done_date) {
                this.allTasks[taskIndex].done_date = new Date().toISOString().slice(0, 10); // Today's date YYYY-MM-DD
            } else if (this.formData.status !== 'Completed' && this.allTasks[taskIndex].done_date) {
                // Optional: Clear done_date if status changes from Completed
                // this.allTasks[taskIndex].done_date = null;
            }
    
    
            // Trigger reactivity (optional, often happens automatically)
            this.allTasks = [...this.allTasks]; // Create a new array instance
    
            this.closeModal();
            this.showFlashMessage('تم تحديث المهمة بنجاح.', 'success');
        },
    
        // Delete Task (Client-side only)
        deleteTask(taskId) {
            if (!confirm('هل أنت متأكد أنك تريد حذف هذه المهمة؟')) {
                return; // Do nothing if user cancels
            }
    
            // Filter out the task to delete
            this.allTasks = this.allTasks.filter(task => task.id !== taskId);
    
            this.showFlashMessage('تم حذف المهمة بنجاح.', 'success');
        },
    
        // Show Flash Message
        showFlashMessage(message, type = 'success', duration = 3000) {
            this.flashMessage = message;
            this.flashType = type;
            setTimeout(() => {
                this.flashMessage = '';
            }, duration);
        },
    
        // Helper to get Avatar URL (Matches the logic in the User model accessor)
        getAvatarUrl(user) {
            if (!user || !user.name) return 'https://ui-avatars.com/api/?name=N/A&color=ffffff&background=cccccc';
            const name = encodeURIComponent(user.name);
            return `https://ui-avatars.com/api/?name=${name}&color=ffffff&background=111827`; // Tailwind's gray-900
        }
    
    }" x-init="console.log('Alpine Task Component Initialized with', allTasks.length, 'tasks and', users.length, 'users');" {{-- Basic initialization --}}>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            <div x-show="flashMessage" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                :class="{ 'bg-green-100 border-green-400 text-green-700': flashType === 'success', 'bg-red-100 border-red-400 text-red-700': flashType === 'error' }"
                class="border px-4 py-3 rounded relative mb-4" role="alert">
                <span x-text="flashMessage"></span>
            </div>


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex flex-col md:flex-row items-center justify-between mb-6 border-b pb-4">
                        <h2 class="text-2xl font-semibold mb-4 md:mb-0">قائمة المهام</h2>

                        <div class="flex flex-wrap gap-2 mb-4 md:mb-0">
                            <button @click="filter = 'all'" {{-- Just update filter state --}}
                                :class="{ 'bg-indigo-600 text-white': filter === 'all', 'bg-gray-200 text-gray-700 hover:bg-gray-300': filter !== 'all' }"
                                class="px-4 py-2 rounded-md text-sm font-medium">
                                كل المهام
                            </button>
                            <button @click="filter = 'my_tasks'" {{-- Just update filter state --}}
                                :class="{ 'bg-indigo-600 text-white': filter === 'my_tasks', 'bg-gray-200 text-gray-700 hover:bg-gray-300': filter !== 'my_tasks' }"
                                class="px-4 py-2 rounded-md text-sm font-medium">
                                مهام مسندة
                            </button>
                            <button @click="filter = 'assigned_tasks'" {{-- Just update filter state --}}
                                :class="{ 'bg-indigo-600 text-white': filter === 'assigned_tasks', 'bg-gray-200 text-gray-700 hover:bg-gray-300': filter !== 'assigned_tasks' }"
                                class="px-4 py-2 rounded-md text-sm font-medium">
                                مهامي    
                            </button>
                        </div>

                        <button @click="openCreateModal()"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 inline-block ltr:mr-2 rtl:ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            إضافة مهمة جديدة
                        </button>
                    </div>

                    {{-- No loading indicator needed if data is local --}}

                    {{-- Task List --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="task in filteredTasks" :key="task.id"> {{-- Loop through filteredTasks --}}
                            <div
                                class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                                <div>
                                    <h3 class="text-lg font-semibold mb-2" x-text="task.title"></h3>
                                    <p class="text-gray-700 text-sm mb-3" x-text="task.description"></p>

                                    <div class="flex items-center text-sm text-gray-600 mb-2">
                                        <svg class="w-4 h-4 ltr:mr-1 rtl:ml-1" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ltr:mr-2 rtl:ml-2 font-medium">تاريخ الإسناد:</span> <span
                                            x-text="task.assigned_date ? task.assigned_date : 'غير محدد'"></span>
                                    </div>

                                    <div x-show="task.done_date" class="flex items-center text-sm text-gray-600 mb-2">
                                        <svg class="w-4 h-4 ltr:mr-1 rtl:ml-1" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ltr:mr-2 rtl:ml-2 font-medium">تاريخ الإنجاز:</span> <span
                                            x-text="task.done_date"></span>
                                    </div>


                                    <div class="flex items-center text-sm mb-2">
                                        <span class="ltr:mr-2 rtl:ml-2 font-medium text-gray-600">الأولوية:</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                            :class="{
                                                'bg-blue-200 text-blue-800': task.priority === 'Low',
                                                'bg-yellow-200 text-yellow-800': task.priority === 'Medium',
                                                'bg-orange-200 text-orange-800': task.priority === 'High',
                                                'bg-red-200 text-red-800': task.priority === 'Urgent',
                                                'bg-gray-200 text-gray-800': !['Low', 'Medium', 'High', 'Urgent']
                                                    .includes(task.priority)
                                            }"
                                            x-text="task.priority"></span>

                                        <span
                                            class="ltr:ml-4 rtl:mr-4 ltr:mr-2 rtl:ml-2 font-medium text-gray-600">الحالة:</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                            :class="{
                                                'bg-gray-300 text-gray-800': task.status === 'Open',
                                                'bg-blue-300 text-blue-800': task.status === 'In Progress',
                                                'bg-green-300 text-green-800': task.status === 'Completed',
                                                'bg-red-300 text-red-800': task.status === 'Cancelled',
                                                'bg-gray-200 text-gray-800': !['Open', 'In Progress', 'Completed',
                                                    'Cancelled'
                                                ].includes(task.status)
                                            }"
                                            x-text="task.status"></span>
                                    </div>


                                    <div class="flex items-center text-sm text-gray-600 mb-3">
                                        <span class="ltr:mr-2 rtl:ml-2 font-medium">المنشئ:</span>
                                        <span x-text="task.created_by ? task.created_by.name : 'N/A'"></span>
                                    </div>

                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="ltr:mr-2 rtl:ml-2 font-medium">المسند إليه:</span>
                                        <template x-if="task.assigned_to">
                                            <div class="flex items-center">
                                                <img :src="getAvatarUrl(task.assigned_to)" :alt="task.assigned_to.name"
                                                    class="w-6 h-6 rounded-full ltr:mr-2 rtl:ml-2">
                                                <span x-text="task.assigned_to.name"></span>
                                            </div>
                                        </template>
                                        <template x-if="!task.assigned_to">
                                            <div class="flex items-center">
                                                <img :src="getAvatarUrl(null)" alt="غير مسند"
                                                    class="w-6 h-6 rounded-full ltr:mr-2 rtl:ml-2">
                                                <span>غير مسند</span>
                                            </div>
                                        </template>
                                    </div>

                                </div>

                                {{-- Actions --}}
                                <div class="mt-4 flex justify-end space-x-2 rtl:space-x-reverse">
                                    <button @click="openEditModal(task)" class="text-indigo-600 hover:text-indigo-900"
                                        title="تعديل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a12.036 12.036 0 017.072 7.072l-3.536 3.536m-2.036-5.036a12.036 12.036 0 017.072 7.072l-3.536 3.536M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button @click="deleteTask(task.id)" class="text-red-600 hover:text-red-900"
                                        title="حذف">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <template x-if="filteredTasks.length === 0">
                            <div class="md:col-span-full text-center text-gray-600 p-4">
                                لا توجد مهام مطابقة لمعايير التصفية الحالية.
                            </div>
                        </template>
                    </div>

                </div>
            </div>
        </div>

        {{-- Create Task Modal --}}
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
            x-show="showCreateModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90" @click.away="closeModal()"
            @keydown.escape.window="closeModal()" style="display: none;" {{-- Hidden by default --}}>
            <div class="relative p-8 bg-white w-full max-w-md mx-auto rounded-lg shadow-lg" @click.stop>
                <h3 class="text-lg font-bold mb-4">إنشاء مهمة جديدة</h3>
                <form @submit.prevent="createTask()">
                    <div class="mb-4">
                        <label for="create-title" class="block text-sm font-medium text-gray-700">العنوان</label>
                        <input type="text" id="create-title" x-model="formData.title"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        {{-- Client-side validation check --}}
                        <template x-if="!formData.title && flashMessage && flashType === 'error'">
                            <span class="text-red-500 text-xs" x-text="'عنوان المهمة مطلوب.'"></span>
                        </template>
                    </div>

                    <div class="mb-4">
                        <label for="create-description" class="block text-sm font-medium text-gray-700">الوصف</label>
                        <textarea id="create-description" x-model="formData.description" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="create-assigned_date" class="block text-sm font-medium text-gray-700">تاريخ
                            الإسناد (المستحق)</label>
                        <input type="date" id="create-assigned_date" x-model="formData.assigned_date"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="mb-4">
                        <label for="create-assigned_to_id" class="block text-sm font-medium text-gray-700">المسند
                            إليه</label>
                        <select id="create-assigned_to_id" x-model="formData.assigned_to_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- اختر مستخدم --</option>
                            <template x-for="user in users" :key="user.id">
                                <option :value="user.id" x-text="user.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="create-priority" class="block text-sm font-medium text-gray-700">الأولوية</label>
                        <select id="create-priority" x-model="formData.priority"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="Low">منخفضة</option>
                            <option value="Medium">متوسطة</option>
                            <option value="High">عالية</option>
                            <option value="Urgent">عاجلة</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="create-status" class="block text-sm font-medium text-gray-700">الحالة</label>
                        <select id="create-status" x-model="formData.status"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="Open">مفتوحة</option>
                            <option value="In Progress">قيد التقدم</option>
                            <option value="Completed">مكتملة</option>
                            <option value="Cancelled">ملغاة</option>
                        </select>
                    </div>
                    {{-- Done date is typically not set on creation --}}

                    <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                        <button type="button" @click="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            إلغاء
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            حفظ المهمة
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Task Modal --}}
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
            x-show="showEditModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90" @click.away="closeModal()"
            @keydown.escape.window="closeModal()" style="display: none;" {{-- Hidden by default --}}>
            <div class="relative p-8 bg-white w-full max-w-md mx-auto rounded-lg shadow-lg" @click.stop>
                <h3 class="text-lg font-bold mb-4">تعديل المهمة</h3>
                <form @submit.prevent="updateTask()">
                    <input type="hidden" x-model="formData.taskId">

                    <div class="mb-4">
                        <label for="edit-title" class="block text-sm font-medium text-gray-700">العنوان</label>
                        <input type="text" id="edit-title" x-model="formData.title"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        {{-- Client-side validation check --}}
                        <template x-if="!formData.title && flashMessage && flashType === 'error'">
                            <span class="text-red-500 text-xs" x-text="'عنوان المهمة مطلوب.'"></span>
                        </template>
                    </div>

                    <div class="mb-4">
                        <label for="edit-description" class="block text-sm font-medium text-gray-700">الوصف</label>
                        <textarea id="edit-description" x-model="formData.description" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="edit-assigned_date" class="block text-sm font-medium text-gray-700">تاريخ إنجاز المهمة</label>
                        <input type="date" id="edit-assigned_date" x-model="formData.assigned_date"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="mb-4">
                        <label for="edit-assigned_to_id" class="block text-sm font-medium text-gray-700">المسند
                            إليه</label>
                        <select id="edit-assigned_to_id" x-model="formData.assigned_to_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- اختر مستخدم --</option>
                            <template x-for="user in users" :key="user.id">
                                <option :value="user.id" x-text="user.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="edit-priority" class="block text-sm font-medium text-gray-700">الأولوية</label>
                        <select id="edit-priority" x-model="formData.priority"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="Low">منخفضة</option>
                            <option value="Medium">متوسطة</option>
                            <option value="High">عالية</option>
                            <option value="Urgent">عاجلة</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="edit-status" class="block text-sm font-medium text-gray-700">الحالة</label>
                        <select id="edit-status" x-model="formData.status"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="Open">مفتوحة</option>
                            <option value="In Progress">قيد التقدم</option>
                            <option value="Completed">مكتملة</option>
                            <option value="Cancelled">ملغاة</option>
                        </select>
                    </div>

                    {{-- Show done date field --}}
                    <div class="mb-4">
                        <label for="edit-done_date" class="block text-sm font-medium text-gray-700">تاريخ
                            الإنجاز</label>
                        <input type="date" id="edit-done_date" x-model="formData.done_date"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>


                    <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                        <button type="button" @click="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            إلغاء
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            تحديث المهمة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
