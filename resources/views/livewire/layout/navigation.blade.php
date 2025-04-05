<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                        الصفحة الرئيسية
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        لوحة القيادة
                    </x-nav-link>

                    <!-- Projects.index Dropdown for Desktop -->
                    <div class="relative group inline-flex">
                        <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.index')" wire:navigate>
                            مشاريع
                        </x-nav-link>
                        <!-- Dropdown submenu: shows on hover -->
                        <div class="absolute left-0 mt-2 w-56 bg-white shadow-lg rounded hidden group-hover:block z-20">
                            <a href="{{ route('projects.index', ['status' => 'Draft']) }}" 
                               class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                مشاريع (مسودة)
                            </a>
                            <a href="{{ route('projects.index', ['status' => 'Submitted']) }}" 
                               class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                مشاريع (مقدمة)
                            </a>
                            <a href="{{ route('projects.index', ['status' => 'Approve']) }}" 
                               class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                مشاريع (موافقة)
                            </a>
                            <a href="{{ route('projects.index', ['status' => 'UnderProcessing']) }}" 
                               class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                مشاريع (قيد التنفيذ)
                            </a>
                        </div>
                    </div>

                    <x-nav-link :href="route('report')" :active="request()->routeIs('report')" wire:navigate>
                        تقديم التقرير
                    </x-nav-link>
                    <x-nav-link :href="route('achievements')" :active="request()->routeIs('achievements')" wire:navigate>
                        الإنجازات و التحديات
                    </x-nav-link>
                    <x-nav-link :href="route('adhoc')" :active="request()->routeIs('adhoc')" wire:navigate>
                        مهام إضافية (Ad-Hoc)
                    </x-nav-link>
                    <x-nav-link :href="route('indicator.index')" :active="request()->routeIs('indicator.index')" wire:navigate>
                        إدارة المؤشرات
                    </x-nav-link>
                    <x-nav-link :href="route('indicator.contribute')" :active="request()->routeIs('indicator.contribute')" wire:navigate>
                        حصر المؤشرات
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('my-tasks')" wire:navigate>
                            مهامي
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                الصفحة الرئيسية
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                لوحة القيادة
            </x-responsive-nav-link>

            <!-- Responsive Projects.index Dropdown: toggles on click -->
            <div x-data="{ openSub: false }" class="relative">
                <div @click="openSub = !openSub" class="block cursor-pointer ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    مشاريع
                </div>
                <div x-show="openSub" x-transition class="mt-1 space-y-1">
                    <a href="{{ route('projects.index', ['status' => 'Draft']) }}" 
                       class="block ps-7 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300">
                        مشاريع (مسودة)
                    </a>
                    <a href="{{ route('projects.index', ['status' => 'Submitted']) }}" 
                       class="block ps-7 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300">
                        مشاريع (مقدمة)
                    </a>
                    <a href="{{ route('projects.index', ['status' => 'Approve']) }}" 
                       class="block ps-7 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300">
                        مشاريع (موافقة)
                    </a>
                    <a href="{{ route('projects.index', ['status' => 'UnderProcessing']) }}" 
                       class="block ps-7 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300">
                        مشاريع (قيد التنفيذ)
                    </a>
                </div>
            </div>

            <x-responsive-nav-link :href="route('report')" :active="request()->routeIs('report')" wire:navigate>
                تقديم التقرير
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('achievements')" :active="request()->routeIs('achievements')" wire:navigate>
                الإنجازات و التحديات
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('adhoc')" :active="request()->routeIs('adhoc')" wire:navigate>
                مهام إضافية (Ad-Hoc)
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('indicator.index')" :active="request()->routeIs('indicator.index')" wire:navigate>
                إدارة المؤشرات
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('indicator.contribute')" :active="request()->routeIs('indicator.contribute')" wire:navigate>
                حصر المؤشرات
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" 
                     x-data="{{ json_encode(['name' => auth()->user()->name]) }}" 
                     x-text="name" 
                     x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('my-tasks')" wire:navigate>
                    مهامي
                </x-responsive-nav-link>
                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
