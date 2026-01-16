<x-app-layout title="ملف المستخدم: {{ $user->name }}">
    <div class="p-4 md:p-6 max-w-4xl mx-auto">

        {{-- Header and Action Buttons --}}
        @include('admin.user.partials.user-header')

        <div class="space-y-6">
            {{-- 1. Basic User Information --}}
            <x-user-details-card :user="$user" />

            {{-- 2. Position History and Update Form --}}
            <x-user-position-card :user="$user" :positions="$positions" :units="$units" />

            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">
                <h3 class="mt-6 text-lg font-semibold">السجل الوظيفي الكامل</h3>
                <ul class="divide-y divide-gray-200 mt-2">
                    @foreach ($user->positionHistory()->latest('start_date')->get() as $history)
                        <li class="py-2 flex justify-between">
                            <span>{{ $history->position?->title ?? '—' }} في
                                {{ $history->OrgUnit?->name ?? '—' }}</span>
                            <span>
                                {{ $history->start_date }} -
                                {{ $history->end_date ?? 'نشط حالياً' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            {{-- 3. roles and Permissions --}}
            <x-user-permissions-card :user="$user" />

            {{-- Effective Permissions Hint --}}
            @include('admin.user.partials.permissions-hint')
        </div>
    </div>
</x-app-layout>
