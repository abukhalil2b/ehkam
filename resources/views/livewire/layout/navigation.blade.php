<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

// Define the Livewire Volt component
new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        // Execute the logout action
        $logout();

        // Redirect to the home page after logout with Livewire navigation
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        {{-- Ensure your x-application-logo component exists --}}
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                        الصفحة الرئيسية
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        لوحة القيادة
                    </x-nav-link>

                    {{-- Using Alpine.js for hover-based dropdown --}}
                    <div class="relative group inline-flex">
                        <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.index')" wire:navigate>
                            مشاريع
                            {{-- Dropdown indicator --}}
                             <svg class="ms-2 -me-0.5 h-4 w-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </x-nav-link>
                        {{-- Dropdown submenu: shows on hover --}}
                        <div class="absolute top-full left-0 mt-0 w-56 bg-white shadow-lg rounded-md overflow-hidden hidden group-hover:block z-20 py-1 border border-gray-100">
                            {{-- Consider adding a base link to the main projects page here if desired --}}
                            <a href="{{ route('projects.index', ['status' => 'Draft']) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition ease-in-out duration-150">
                                مشاريع (مسودة)
                            </a>
                            <a href="{{ route('projects.index', ['status' => 'Submitted']) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition ease-in-out duration-150">
                                مشاريع (مقدمة)
                            </a>
                            <a href="{{ route('projects.index', ['status' => 'Approve']) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition ease-in-out duration-150">
                                مشاريع (موافقة)
                            </a>
                            <a href="{{ route('projects.index', ['status' => 'UnderProcessing']) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition ease-in-out duration-150">
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
                     {{-- Moved 'مهامي' to a more logical place, perhaps under a user/task menu or kept here --}}
                    <x-nav-link :href="route('task.index')" :active="request()->routeIs('task.index')" wire:navigate>
                        مهامي
                    </x-nav-link>

                    {{-- Using Alpine.js for hover-based dropdown --}}
                     <div class="relative group inline-flex">
                        <x-nav-link :href="route('indicator.index')" :active="request()->routeIs('indicator.index') || request()->routeIs('indicator.contribute')" wire:navigate>
                           المؤشرات
                             <svg class="ms-2 -me-0.5 h-4 w-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </x-nav-link>
                        {{-- Dropdown submenu: shows on hover --}}
                        <div class="absolute top-full left-0 mt-0 w-48 bg-white shadow-lg rounded-md overflow-hidden hidden group-hover:block z-20 py-1 border border-gray-100">
                            <a href="{{ route('indicator.index') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition ease-in-out duration-150">
                                إدارة المؤشرات
                            </a>
                            <a href="{{ route('indicator.contribute') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition ease-in-out duration-150">
                                حصر المؤشرات
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                {{-- Standard Livewire/Blade component dropdown --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        {{-- Added ARIA attributes for accessibility --}}
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                aria-haspopup="true"
                                :aria-expanded="open"> {{-- Assuming 'open' state from parent nav or define locally if needed --}}
                            {{-- Using a span for better text handling within the button --}}
                            <span x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></span>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        {{-- Profile Link --}}
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        {{-- Optional: Link to 'مهامي' here if it relates to the user's tasks --}}
                         {{--
                         <x-dropdown-link :href="route('task.index')" wire:navigate>
                             مهامي
                         </x-dropdown-link>
                         --}}

                        {{-- Using a button for the logout action is semantically correct --}}
                        <button wire:click="logout" class="block w-full text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out px-4 py-2">
                            {{ __('Log Out') }}
                        </button>
                         {{-- Replaced x-dropdown-link wrapper with direct styling on the button --}}
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                {{-- Hamburger button with ARIA attributes --}}
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                        aria-label="{{ __('Toggle navigation') }}" {{-- Added accessibility label --}}
                        aria-controls="responsive-navigation-menu" {{-- Link to the responsive menu div --}}
                        :aria-expanded="open"> {{-- Bind expanded state --}}
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Added ID for ARIA control --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" id="responsive-navigation-menu">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                الصفحة الرئيسية
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                لوحة القيادة
            </x-responsive-nav-link>

            {{-- Using Alpine.js for click-based toggle --}}
            <div x-data="{ openSub: false }" class="relative">
                {{-- Use a button for the toggle, with ARIA attributes --}}
                <button @click="openSub = !openSub"
                        class="block w-full text-start ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
                        aria-haspopup="true"
                        :aria-expanded="openSub">
                    مشاريع
                     {{-- Dropdown indicator --}}
                     <svg class="ms-2 -me-0.5 h-4 w-4 inline-block transform transition-transform duration-200"
                          :class="{ 'rotate-180': openSub, 'rotate-0': !openSub }"
                          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                {{-- Dropdown submenu: toggles on click --}}
                <div x-show="openSub" x-transition class="mt-1 space-y-1" id="responsive-projects-submenu" role="menu" aria-orientation="vertical">
                    {{-- Added role and aria-orientation for accessibility --}}
                     {{-- Consider adding a base link to the main projects page here if desired --}}
                    <a href="{{ route('projects.index', ['status' => 'Draft']) }}"
                       class="block ps-7 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300" role="menuitem">
                        مشاريع (مسودة)
                    </a>
                    <a href="{{ route('projects.index', ['status' => 'Submitted']) }}"
                       class="block ps-7 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300" role="menuitem">
                        مشاريع (مقدمة)
                    </a>
                    <a href="{{ route('projects.index', ['status' => 'Approve']) }}"
                       class="block ps-7 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300" role="menuitem">
                        مشاريع (موافقة)
                    </a>
                    <a href="{{ route('projects.index', ['status' => 'UnderProcessing']) }}"
                       class="block ps-7 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300" role="menuitem">
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
             {{-- Keep 'مهامي' here or move it under profile, based on desired UX --}}
             <x-responsive-nav-link :href="route('task.index')" :active="request()->routeIs('task.index')" wire:navigate>
                مهامي
            </x-responsive-nav-link>

            {{-- Using Alpine.js for click-based toggle --}}
            <div x-data="{ openSub: false }" class="relative">
                 {{-- Use a button for the toggle, with ARIA attributes --}}
                <button @click="openSub = !openSub"
                        class="block w-full text-start ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
                         aria-haspopup="true"
                         :aria-expanded="openSub">
                   المؤشرات
                    {{-- Dropdown indicator --}}
                     <svg class="ms-2 -me-0.5 h-4 w-4 inline-block transform transition-transform duration-200"
                          :class="{ 'rotate-180': openSub, 'rotate-0': !openSub }"
                          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                {{-- Dropdown submenu: toggles on click --}}
                <div x-show="openSub" x-transition class="mt-1 space-y-1" id="responsive-indicators-submenu" role="menu" aria-orientation="vertical">
                     {{-- Added role and aria-orientation for accessibility --}}
                    <a href="{{ route('indicator.index') }}"
                       class="block ps-7 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300" role="menuitem">
                        إدارة المؤشرات
                    </a>
                    <a href="{{ route('indicator.contribute') }}"
                       class="block ps-7 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300" role="menuitem">
                        حصر المؤشرات
                    </a>
                </div>
            </div>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                {{-- Using a span for better text handling --}}
                <div class="font-medium text-base text-gray-800"
                     x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                     x-text="name"
                     x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                {{-- Profile Link --}}
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                {{-- Using a button for the logout action is semantically correct --}}
                <button wire:click="logout" class="block w-full text-start ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                    {{ __('Log Out') }}
                </button>
                 {{-- Replaced x-responsive-nav-link wrapper with direct styling on the button --}}
            </div>
        </div>
    </div>
</nav>