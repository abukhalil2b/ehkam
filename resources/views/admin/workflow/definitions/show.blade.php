<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $definition->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Workflow Info --}}
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded overflow-hidden">
                    <div class="bg-indigo-600 text-white px-4 py-3 flex justify-between items-center">
                        <h3 class="font-semibold">{{ __('معلومات سير العمل') }}</h3>
                        <a href="{{ route('admin.workflow.definitions.edit', $definition) }}"
                            class="bg-white text-indigo-600 px-2 py-1 rounded text-sm">
                            {{ __('تعديل') }}
                        </a>
                    </div>
                    <div class="p-4">
                        <h4 class="text-lg font-medium">{{ $definition->name }}</h4>
                        @if($definition->description)
                            <p class="text-gray-500 mt-2">{{ $definition->description }}</p>
                        @endif
                        <hr class="my-4">
                        <div class="flex justify-between mb-2">
                            <span>{{ __('الحالة') }}</span>
                            @if($definition->is_active)
                                <span
                                    class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">{{ __('نشط') }}</span>
                            @else
                                <span
                                    class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">{{ __('غير نشط') }}</span>
                            @endif
                        </div>
                        <div class="flex justify-between mb-2">
                            <span>{{ __('عدد المراحل') }}</span>
                            <span
                                class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ $definition->stages->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('عدد العناصر') }}</span>
                            <span
                                class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">{{ $definition->instances->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Workflow Flow --}}
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded overflow-hidden">
                    <div class="bg-blue-500 text-white px-4 py-3">
                        <h3 class="font-semibold">{{ __('مسار سير العمل') }}</h3>
                    </div>
                    <div class="p-4">
                        @if($definition->stages->isEmpty())
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <p class="text-gray-500 mt-2">{{ __('لم يتم إضافة مراحل بعد') }}</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($definition->stages as $index => $stage)
                                    <div
                                        class="border-r-4 {{ $loop->first ? 'border-green-500' : ($loop->last ? 'border-red-500' : 'border-indigo-500') }} p-4 bg-gray-50 rounded-lg">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <span
                                                    class="px-2 py-1 rounded text-white text-xs {{ $loop->first ? 'bg-green-500' : ($loop->last ? 'bg-red-500' : 'bg-indigo-500') }}">
                                                    {{ $stage->order }}
                                                </span>
                                                <strong class="mr-2">{{ $stage->name }}</strong>
                                            </div>
                                            <a href="{{ route('admin.workflow.teams.show', $stage->team) }}"
                                                class="bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm">
                                                {{ $stage->team->name }}
                                            </a>
                                        </div>
                                        <div class="mt-2">
                                            @if($stage->can_approve)
                                                <span
                                                    class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs">{{ __('موافقة') }}</span>
                                            @endif
                                            @if($stage->can_return)
                                                <span
                                                    class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs">{{ __('إرجاع') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <div class="text-center">
                                            <svg class="mx-auto w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                            </svg>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Items using this workflow --}}
                @if($definition->instances->isNotEmpty())
                    <div class="bg-white shadow rounded overflow-hidden mt-4">
                        <div class="bg-yellow-500 text-white px-4 py-3">
                            <h3 class="font-semibold">{{ __('العناصر التي تستخدم هذا السير') }}</h3>
                        </div>
                        <div class="p-4">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-right">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2">{{ __('العنصر') }}</th>
                                            <th class="px-4 py-2">{{ __('المرحلة الحالية') }}</th>
                                            <th class="px-4 py-2">{{ __('الحالة') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($definition->instances->take(10) as $instance)
                                            <tr>
                                                <td class="px-4 py-2">
                                                    @if($instance->workflowable && method_exists($instance->workflowable, 'getAttribute'))
                                                        <a href="#" class="text-indigo-600 hover:underline">
                                                            {{ $instance->workflowable->title ?? $instance->workflowable->name ?? 'Item #' . $instance->workflowable_id }}
                                                        </a>
                                                    @else
                                                        <span class="text-gray-500">{{ __('عنصر محذوف') }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2">{{ $instance->currentStage?->name ?? '-' }}</td>
                                                <td class="px-4 py-2">
                                                    <span
                                                        class="px-2 py-1 rounded text-xs {{ $instance->status === 'completed' ? 'bg-green-100 text-green-800' : ($instance->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-indigo-100 text-indigo-800') }}">
                                                        {{ __($instance->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($definition->instances->count() > 10)
                                <p class="text-gray-500 text-center mt-4">
                                    {{ __('و :count عناصر أخرى...', ['count' => $definition->instances->count() - 10]) }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>