<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                {{ __('فرق سير العمل') }}
            </h2>
            <a href="{{ route('admin.workflow.teams.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                {{ __('إضافة فريق جديد') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded overflow-x-auto">
            @if($teams->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <p class="mt-4 text-gray-500">{{ __('لا توجد فرق حالياً') }}</p>
                    <a href="{{ route('admin.workflow.teams.create') }}"
                        class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded">
                        {{ __('إنشاء أول فريق') }}
                    </a>
                </div>
            @else
                <table class="w-full text-sm text-right">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">{{ __('اسم الفريق') }}</th>
                            <th class="px-6 py-3">{{ __('الوصف') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('عدد الأعضاء') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('عدد المراحل') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('الإجراءات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teams as $team)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">{{ $team->id }}</td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.workflow.teams.show', $team) }}"
                                        class="text-indigo-600 hover:underline font-medium">
                                        {{ $team->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ Str::limit($team->description, 50) ?: '-' }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span
                                        class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-xs">{{ $team->users_count }}</span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span
                                        class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">{{ $team->stages_count }}</span>
                                </td>
                                <td class="px-6 py-3 text-center flex justify-center gap-2">
                                    <a href="{{ route('admin.workflow.teams.show', $team) }}"
                                        class="bg-blue-500 text-white px-3 py-1 rounded text-xs">
                                        {{ __('عرض') }}
                                    </a>
                                    <a href="{{ route('admin.workflow.teams.edit', $team) }}"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded text-xs">
                                        {{ __('تعديل') }}
                                    </a>
                                    @if($team->stages_count == 0)
                                        <form action="{{ route('admin.workflow.teams.destroy', $team) }}" method="POST"
                                            onsubmit="return confirm('{{ __('هل أنت متأكد من الحذف؟') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                {{ __('حذف') }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>