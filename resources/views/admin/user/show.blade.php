<x-app-layout title="ملف المستخدم: {{ $user->name }}">
    <div class="p-4 md:p-6 max-w-4xl mx-auto">

        {{-- Header and Action Buttons --}}
        @include('admin.user.partials.user-header')

        <div class="space-y-6">
            {{-- 1. Basic User Information --}}
            <x-user-details-card :user="$user" />

            {{-- 2. Position History and Update Form --}}
            <x-user-position-card :user="$user" :positions="$positions" :units="$units" />
            
            {{-- 3. Profiles and Permissions --}}
            <x-user-permissions-card :user="$user" />

            {{-- Effective Permissions Hint --}}
            @include('admin.user.partials.permissions-hint')
        </div>
    </div>
</x-app-layout>