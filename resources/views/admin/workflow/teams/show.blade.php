<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $team->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Team Info --}}
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded overflow-hidden">
                    <div class="bg-indigo-600 text-white px-4 py-3 flex justify-between items-center">
                        <h3 class="font-semibold">{{ __('معلومات الفريق') }}</h3>
                        <a href="{{ route('admin.workflow.teams.edit', $team) }}"
                            class="bg-white text-indigo-600 px-2 py-1 rounded text-sm">
                            {{ __('تعديل') }}
                        </a>
                    </div>
                    <div class="p-4">
                        <h4 class="text-lg font-medium">{{ $team->name }}</h4>
                        @if($team->description)
                            <p class="text-gray-500 mt-2">{{ $team->description }}</p>
                        @endif
                        <hr class="my-4">
                        <div class="flex justify-between">
                            <span>{{ __('عدد الأعضاء') }}</span>
                            <span
                                class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-xs">{{ $team->users->count() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Team Members --}}
                <div class="bg-white shadow rounded overflow-hidden mt-4">
                    <div class="bg-gray-100 px-4 py-3">
                        <h3 class="font-semibold">{{ __('أعضاء الفريق') }}</h3>
                    </div>
                    <ul class="divide-y">
                        @forelse($team->users as $user)
                            <li class="px-4 py-3 flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $user->name }}</span>
                                </div>
                                @if($user->email)
                                    <span class="text-gray-400 text-sm">{{ $user->email }}</span>
                                @endif
                            </li>
                        @empty
                            <li class="px-4 py-6 text-center text-gray-500">
                                {{ __('لا يوجد أعضاء') }}
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Pending Steps --}}
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded overflow-hidden">
                    <div class="bg-yellow-500 text-white px-4 py-3">
                        <h3 class="font-semibold">{{ __('الخطوات المعلقة لهذا الفريق') }}</h3>
                    </div>
                    <div class="p-4">
                        @if($pendingSteps->isEmpty())
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-500 mt-2">{{ __('لا توجد خطوات معلقة') }}</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-right">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2">{{ __('الخطوة') }}</th>
                                            <th class="px-4 py-2">{{ __('سير العمل') }}</th>
                                            <th class="px-4 py-2">{{ __('المرحلة') }}</th>
                                            <th class="px-4 py-2">{{ __('الحالة') }}</th>
                                            <th class="px-4 py-2">{{ __('الإجراءات') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($pendingSteps as $step)
                                            <tr>
                                                <td class="px-4 py-2">
                                                    <a href="{{ route('step.show', $step) }}"
                                                        class="text-indigo-600 hover:underline">
                                                        {{ $step->name }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-2">{{ $step->workflow?->name ?? '-' }}</td>
                                                <td class="px-4 py-2">{{ $step->currentStage?->name ?? '-' }}</td>
                                                <td class="px-4 py-2">
                                                    <span
                                                        class="px-2 py-1 rounded text-xs {{ $step->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $step->status_label }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <a href="{{ route('step.show', $step) }}"
                                                        class="bg-indigo-600 text-white px-2 py-1 rounded text-xs">
                                                        {{ __('عرض') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Stages using this team --}}
                @if($team->stages->isNotEmpty())
                    <div class="bg-white shadow rounded overflow-hidden mt-4">
                        <div class="bg-gray-100 px-4 py-3">
                            <h3 class="font-semibold">{{ __('المراحل التي تستخدم هذا الفريق') }}</h3>
                        </div>
                        <ul class="divide-y">
                            @foreach($team->stages as $stage)
                                <li class="px-4 py-3 flex justify-between items-center">
                                    <div>
                                        <strong>{{ $stage->name }}</strong>
                                        <span class="text-gray-400 text-sm mr-2">{{ __('في') }}:
                                            {{ $stage->workflow->name }}</span>
                                    </div>
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">{{ __('الترتيب') }}:
                                        {{ $stage->order }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>