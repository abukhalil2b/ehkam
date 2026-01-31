<x-app-layout>

    <div class="container py-6 mx-auto px-4 sm:px-6">
        <!-- Quick Stats Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @if(isset($myWorkflows) && $myWorkflows->count() > 0)
                <div class="col-span-2 md:col-span-4 bg-blue-50/50 p-4 rounded-xl border border-blue-100 mb-2">
                    <h3 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                        <span class="material-icons text-blue-600 text-sm">pending_actions</span>
                        مهامي المعلقة (Workflows)
                    </h3>
                    <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($myWorkflows as $workflow)
                            <div
                                class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition group relative overflow-hidden">
                                <div
                                    class="absolute top-0 right-0 w-1 h-full bg-{{ $workflow->status == 'pending' ? 'yellow-400' : 'blue-500' }}">
                                </div>
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                                        {{ __('step_stages.' . $workflow->stage) }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">{{ $workflow->created_at?->diffForHumans() ?? '' }}</span>
                                </div>
                                <h4 class="font-bold text-gray-800 text-sm mb-1 truncate">
                                    {{ $workflow->step->name ?? 'خطوة بدون عنوان' }}</h4>
                                <p class="text-xs text-gray-500 mb-3 truncate">{{ $workflow->step->project->name ?? '' }}</p>

                                <a href="{{ route('step.show', $workflow->step_id) }}"
                                    class="flex items-center justify-center w-full py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded transition-colors">
                                    معالجة
                                    <i class="fas fa-arrow-left mr-1 rtl:ml-1 rtl:mr-0 text-[10px]"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <p class="md:col-span-4 font-semibold text-gray-700 mt-2">مهام </p>
            @foreach ($tasks as $task)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="text-purple-600 mb-1"><i class="fas fa-users"></i></div>
                    <div class="text-xl text-gray-500">
                        {{ $task->title }}
                    </div>

                    <div class="mt-5 flex gap-2 text-[10px]">

                    </div>
                </div>
            @endforeach

        </div>

        <!-- Main Dashboard -->
        <div class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 hover:shadow-sm transition-shadow">
            <!-- Header with Actions -->
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">لمحة سريعة عن الأداء</h2>
                    <p class="text-sm text-gray-400 mt-1">نظرة عامة على تقدم المشروع والتنبيهات</p>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <button
                        class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-download ml-1 rtl:mr-1 rtl:ml-0"></i> تصدير
                    </button>
                    <button
                        class="px-3 py-1.5 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus ml-1 rtl:mr-1 rtl:ml-0"></i> إضافة تقرير
                    </button>
                </div>
            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-green-50 p-4 rounded-xl border border-green-100 hover:shadow-xs transition">
                    <div class="flex justify-between">
                        <span class="text-green-800 text-sm font-medium">صحة المشروع</span>
                        <span class="text-green-600 text-xs">منجز</span>
                    </div>
                    <p class="text-2xl font-bold text-green-900 mt-1">{{ $healthPercentage }}%</p>
                    <div class="h-1.5 w-full bg-green-200 rounded-full mt-2">
                        <div class="h-1.5 bg-green-500 rounded-full" style="width: {{ $healthPercentage }}%"></div>
                    </div>
                </div>

                <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 hover:shadow-xs transition">
                    <div class="flex justify-between">
                        <span class="text-amber-800 text-sm font-medium">تنبيهات نشطة</span>
                        <span class="text-amber-600 text-xs">خطوات متأخرة</span>
                    </div>
                    <p class="text-2xl font-bold text-amber-900 mt-1">{{ $activeAlerts }}</p>
                    <div class="h-1.5 w-full bg-amber-200 rounded-full mt-2">
                        <div class="h-1.5 bg-amber-500 rounded-full" style="width: {{ $activeAlerts > 0 ? 100 : 0 }}%">
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 hover:shadow-xs transition">
                    <div class="flex justify-between">
                        <span class="text-blue-800 text-sm font-medium">الوقت المتبقي للتسليم</span>
                        <span class="text-blue-600 text-xs">على المسار</span>
                    </div>
                    <p class="text-2xl font-bold text-blue-900 mt-1">--</p>
                    <div class="h-1.5 w-full bg-blue-200 rounded-full mt-2">
                        <div class="h-1.5 bg-blue-500 rounded-full" style="width: 50%"></div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Charts Section -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Project Status Chart -->
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 hover:shadow-xs transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-700 flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full ml-2 rtl:mr-2 rtl:ml-0"></span>
                                الخطوات - الحالة
                            </h3>

                        </div>
                        <div id="projectStatusContainer" class="h-60 flex items-center justify-center">
                            <canvas id="projectStatusChart"></canvas>
                        </div>
                    </div>

                    <!-- Task Priority Chart -->
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 hover:shadow-xs transition">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-700 flex items-center">
                                <span class="w-2 h-2 bg-amber-500 rounded-full ml-2 rtl:mr-2 rtl:ml-0"></span>
                                أولويات المهام
                            </h3>
                        </div>
                        <div id="flagContainer" class="h-60 flex items-center justify-center">
                            <canvas id="flagStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Legend & Actions -->
                <div class="w-full lg:w-80 xl:w-96 space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-blue-50 p-5 rounded-xl border border-blue-200 hover:shadow-xs transition">
                        <h3 class="text-lg font-medium text-blue-700 mb-4 flex items-center">
                            <i class="fas fa-bolt text-blue-400 ml-2 rtl:mr-2 rtl:ml-0 text-sm"></i>
                            إجراءات سريعة
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('notifications.index') }}"
                                class="w-full flex items-center justify-between p-3 hover:bg-blue-100 rounded-lg transition-colors text-blue-800">
                                <span>عرض التنبيهات</span>
                                <i class="fas fa-bell"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Updates Section (Notifications) -->
        <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-4">آخر التحديثات</h3>
        <div class="grid md:grid-cols-3 gap-6">
            @forelse($recentNotifications as $notification)
                <div class="bg-white p-5 rounded-xl border border-gray-200 hover:shadow-xs transition">
                    <div class="border-r-4 rtl:border-l-4 rtl:border-r-0 border-blue-500 pr-4 rtl:pl-4 rtl:pr-0 py-1">
                        <h3 class="font-medium text-gray-800">{{ $notification->data['message'] ?? 'إشعار جديد' }}</h3>
                        <p class="text-sm text-gray-600 mt-2">
                            {{ $notification->data['task_title'] ?? $notification->data['step_name'] ?? '' }}
                        </p>
                        <div class="flex items-center mt-3">
                            <span class="text-xs text-gray-500 mr-auto rtl:ml-auto rtl:mr-0">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-6 text-gray-500">
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
                            font: { size: 10 }
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
                        ticks: { stepSize: 1 }
                    }
                },
                animation: {
                    duration: 1500
                }
            }
        });
    </script>
</x-app-layout>