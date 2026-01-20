<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('سجل سير العمل') }}: {{ $activity->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="mb-6 flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            {{ __('سير العمل') }}: 
                            <span class="font-bold text-gray-800">
                                {{ $activity->workflowInstance?->workflow?->name ?? __('غير محدد') }}
                            </span>
                        </div>
                        <a href="{{ route('project.show', $activity->project_id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                            &larr; {{ __('عودة للمشروع') }}
                        </a>
                    </div>

                    @if($activity->transitions->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            {{ __('لا يوجد سجل نشاط حتى الآن.') }}
                        </div>
                    @else
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($activity->transitions as $transition)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3 space-x-reverse">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                        @if($transition->action === 'submit') bg-blue-500
                                                        @elseif($transition->action === 'approve') bg-green-500
                                                        @elseif($transition->action === 'reject') bg-red-500
                                                        @elseif($transition->action === 'return') bg-yellow-500
                                                        @else bg-gray-500 @endif
                                                    ">
                                                        <i class="text-white text-xs fas 
                                                            @if($transition->action === 'submit') fa-paper-plane
                                                            @elseif($transition->action === 'approve') fa-check
                                                            @elseif($transition->action === 'reject') fa-times
                                                            @elseif($transition->action === 'return') fa-undo
                                                            @else fa-circle @endif
                                                        "></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4 space-x-reverse">
                                                    <div>
                                                        <p class="text-sm text-gray-500">
                                                            <span class="font-medium text-gray-900">{{ $transition->actor->name }}</span>
                                                            
                                                            @if($transition->action === 'submit')
                                                                {{ __('قام بإرسال النشاط') }}
                                                            @elseif($transition->action === 'approve')
                                                                {{ __('وافق على النشاط') }}
                                                            @elseif($transition->action === 'reject')
                                                                {{ __('رفض النشاط') }}
                                                            @elseif($transition->action === 'return')
                                                                {{ __('أعاد النشاط') }}
                                                            @else
                                                                {{ $transition->action }}
                                                            @endif
                                                            
                                                            @if($transition->toStage)
                                                                {{ __('إلى مرحلة') }} <span class="font-medium text-gray-900">{{ $transition->toStage->name }}</span>
                                                            @endif
                                                        </p>
                                                        @if($transition->comments)
                                                            <div class="mt-2 text-sm text-gray-700 bg-gray-50 p-2 rounded border border-gray-100 italic">
                                                                "{{ $transition->comments }}"
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time datetime="{{ $transition->created_at }}">{{ $transition->created_at->format('Y/m/d H:i') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>