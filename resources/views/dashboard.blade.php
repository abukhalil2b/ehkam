<x-app-layout>

    <div class="container py-6 mx-auto px-4 sm:px-6">
        <!-- Quick Stats Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @if (isset($myWorkflows) && $myWorkflows->count() > 0)
                <div
                    class="col-span-2 md:col-span-4 bg-blue-50/50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800 mb-2">
                    <h3 class="font-bold text-blue-900 dark:text-blue-100 mb-3 flex items-center gap-2">
                        <span class="material-icons text-blue-600 dark:text-blue-400 text-sm">pending_actions</span>
                        مهامي المعلقة (Workflows)
                    </h3>
                    <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($myWorkflows as $workflow)
                            <div
                                class="bg-white dark:bg-[#1b2e4b] p-3 rounded-lg shadow-sm border border-gray-100 dark:border-[#191e3a] hover:shadow-md transition group relative overflow-hidden">
                                <div
                                    class="absolute top-0 right-0 w-1 h-full bg-{{ $workflow->status == 'pending' ? 'yellow-400' : 'blue-500' }}">
                                </div>
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 dark:bg-[#0e1726] text-gray-600 dark:text-gray-400">
                                        {{ __('step_stages.' . $workflow->stage) }}
                                    </span>
                                    <span
                                        class="text-[10px] text-gray-400">{{ $workflow->created_at?->diffForHumans() ?? '' }}</span>
                                </div>
                                <h4 class="font-bold text-gray-800 dark:text-white-light text-sm mb-1 truncate">
                                    {{ $workflow->step->name ?? 'خطوة بدون عنوان' }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 truncate">
                                    {{ $workflow->step->project->name ?? '' }}
                                </p>

                                <a href="{{ route('step.show', $workflow->step_id) }}"
                                    class="flex items-center justify-center w-full py-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded transition-colors">
                                    معالجة
                                    <i class="fas fa-arrow-left mr-1 rtl:ml-1 rtl:mr-0 text-[10px]"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <p class="md:col-span-4 font-semibold text-gray-700 dark:text-gray-300 mt-2">مهام </p>
            @foreach ($tasks as $task)
                    <div class="bg-white dark:bg-[#1b2e4b] p-3 rounded-xl shadow-sm border 
                {{ $task->status_styles['border'] }} dark:border-[#191e3a]
                hover:shadow-md transition">

                        <div class="flex items-start justify-between gap-2">

                            <div>
                                <div class="text-sm font-semibold text-gray-800 dark:text-white-light leading-tight">
                                    {{ $task->title }}
                                </div>

                                <div class="mt-1">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium
                                {{ $task->status_styles['bg'] }} 
                                {{ $task->status_styles['text'] }}">
                                        {{ $task->status_label }}
                                    </span>
                                </div>

                                <div class="mt-1 text-[10px] text-gray-500 dark:text-gray-400">
                                    {{ $task->due_date?->format('Y-m-d') ?? 'بدون موعد نهائي' }}
                                </div>
                            </div>

                            <div class="text-purple-500 text-sm">
                                <i class="fas fa-users"></i>
                            </div>

                        </div>
                    </div>
            @endforeach


        </div>

        <!-- Main Dashboard -->
        <div
            class="bg-white dark:bg-[#1b2e4b] p-6 rounded-2xl shadow-xs border border-gray-100 dark:border-[#191e3a] hover:shadow-sm transition-shadow">
            <!-- Header with Actions -->
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white-light">لمحة سريعة عن الأداء</h2>
                    <p class="text-sm text-gray-400 mt-1">نظرة عامة على تقدم المشروع والتنبيهات</p>
                </div>
            </div>


            <!-- Main Content -->
            <div class="flex flex-col lg:flex-row gap-6 mb-8">
                <!-- Legend & Actions -->
                <div class="w-full lg:w-80 xl:w-96 space-y-6">
                    <!-- Quick Actions -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 p-5 rounded-xl border border-blue-200 dark:border-blue-800 hover:shadow-xs transition">
                        <h3 class="text-lg font-medium text-blue-700 dark:text-blue-200 mb-4 flex items-center">
                            <i class="fas fa-bolt text-blue-400 ml-2 rtl:mr-2 rtl:ml-0 text-sm"></i>
                            إجراءات سريعة
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('notifications.index') }}"
                                class="w-full flex items-center justify-between p-3 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg transition-colors text-blue-800 dark:text-blue-300">
                                <span>عرض التنبيهات</span>
                                <i class="fas fa-bell"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Charts Section -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Project Status Chart -->

                    <!-- Task Priority Chart -->
                    <div
                        class="bg-gray-50 dark:bg-[#0e1726] p-5 rounded-xl border border-gray-200 dark:border-[#1b2e4b] hover:shadow-xs transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                <span class="w-2 h-2 bg-amber-500 rounded-full ml-2 rtl:mr-2 rtl:ml-0"></span>
                                أولويات المهام
                            </h3>
                        </div>
                        <div id="flagContainer" class="h-60 flex items-center justify-center">
                            <canvas id="flagStatusChart"></canvas>
                        </div>
                    </div>

                    <div
                        class="bg-gray-50 dark:bg-[#0e1726] p-5 rounded-xl border border-gray-200 dark:border-[#1b2e4b] hover:shadow-xs transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full ml-2 rtl:mr-2 rtl:ml-0"></span>
                                الخطوات - الحالة
                            </h3>

                        </div>
                        <div id="projectStatusContainer" class="h-60 flex items-center justify-center">
                            <canvas id="projectStatusChart"></canvas>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div
                    class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl border border-green-100 dark:border-green-800 hover:shadow-xs transition">
                    <div class="flex justify-between">
                        <span class="text-green-800 dark:text-green-200 text-sm font-medium">صحة المشروع</span>
                        <span class="text-green-600 dark:text-green-400 text-xs">منجز</span>
                    </div>
                    <p class="text-2xl font-bold text-green-900 dark:text-green-100 mt-1">{{ $healthPercentage }}%</p>
                    <div class="h-1.5 w-full bg-green-200 dark:bg-green-900 rounded-full mt-2">
                        <div class="h-1.5 bg-green-500 rounded-full" style="width: {{ $healthPercentage }}%"></div>
                    </div>
                </div>

                <div
                    class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl border border-amber-100 dark:border-amber-800 hover:shadow-xs transition">
                    <div class="flex justify-between">
                        <span class="text-amber-800 dark:text-amber-200 text-sm font-medium">تنبيهات نشطة</span>
                        <span class="text-amber-600 dark:text-amber-400 text-xs">خطوات متأخرة</span>
                    </div>
                    <p class="text-2xl font-bold text-amber-900 dark:text-amber-100 mt-1">{{ $activeAlerts }}</p>
                    <div class="h-1.5 w-full bg-amber-200 dark:bg-amber-900 rounded-full mt-2">
                        <div class="h-1.5 bg-amber-500 rounded-full" style="width: {{ $activeAlerts > 0 ? 100 : 0 }}%">
                        </div>
                    </div>
                </div>

                <div
                    class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800 hover:shadow-xs transition">
                    <div class="flex justify-between">
                        <span class="text-blue-800 dark:text-blue-200 text-sm font-medium">الوقت المتبقي للتسليم</span>
                        <span class="text-blue-600 dark:text-blue-400 text-xs">على المسار</span>
                    </div>
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100 mt-1">--</p>
                    <div class="h-1.5 w-full bg-blue-200 dark:bg-blue-900 rounded-full mt-2">
                        <div class="h-1.5 bg-blue-500 rounded-full" style="width: 50%"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Updates Section (Notifications) -->
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white-light mt-6 mb-4">آخر التحديثات</h3>
        <div class="grid md:grid-cols-3 gap-6">
            @forelse($recentNotifications as $notification)
                <div
                    class="bg-white dark:bg-[#1b2e4b] p-5 rounded-xl border border-gray-200 dark:border-[#191e3a] hover:shadow-xs transition">
                    <div class="border-r-4 rtl:border-l-4 rtl:border-r-0 border-blue-500 pr-4 rtl:pl-4 rtl:pr-0 py-1">
                        <h3 class="font-medium text-gray-800 dark:text-white-light">
                            {{ $notification->data['message'] ?? 'إشعار جديد' }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            {{ $notification->data['task_title'] ?? ($notification->data['step_name'] ?? '') }}
                        </p>
                        <div class="flex items-center mt-3">
                            <span class="text-xs text-gray-500 dark:text-gray-400 mr-auto rtl:ml-auto rtl:mr-0">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-6 text-gray-500 dark:text-gray-400">
                    لا توجد تحديثات جديدة
                </div>
            @endforelse
        </div>
    </div>

    <!-- Chart.js Implementation -->
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script>
        // Project Status Chart
        const projectCtx = document.getElementById('projectStatusChart').getContext('2d');
        const projectData = @json($chartData ?? []);

        new Chart(projectCtx, {
            type: 'doughnut',
            data: {
                labels: ['التحضير', 'التخطيط والتطوير', 'التنفيذ', 'المراجعة', 'الاعتماد'],
                datasets: [{
                    data: projectData.length ? projectData : [0, 0, 0, 0, 0],
                    backgroundColor: ['#60A5FA', '#34D399', '#FBBF24', '#A78BFA', '#F87171'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500
                }
            }
        });

        // Task Priority Chart (Flag Status)
        const flagCtx = document.getElementById('flagStatusChart').getContext('2d');
        const taskPriorities = @json($taskPriorities);

        new Chart(flagCtx, {
            type: 'bar',
            data: {
                labels: ['حرجة', 'متوسطة', 'منخفضة'],
                datasets: [{
                    label: 'المهام',
                    data: taskPriorities,
                    backgroundColor: ['#EF4444', '#F59E0B', '#10B981'],
                    borderRadius: 6
                }]
            },
            options: {
                plugins: {
                    legend: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                animation: {
                    duration: 1500
                }
            }
        });
    </script>
</x-app-layout>