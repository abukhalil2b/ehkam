<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset='utf-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'متابعة المشاريع والمؤشرات' }}</title>

    <link rel="icon" type="image/svg" href="{{ asset('assets/images/favicon.svg') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script src="{{ asset('assets/js/perfect-scrollbar.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/popper.min.js') }}"></script>

    @vite(['resources/css/app.css'])
    @vite('resources/js/app.js')

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        .tab-button {
            transition: all 0.2s;
        }

        .tab-active {
            border-color: #4361ee;
            color: #4361ee;
            background-color: #eff3fe;
        }

        .card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }
    </style>
    @stack('styles')
</head>

<body x-data="main" class="antialiased relative text-sm font-normal overflow-x-hidden **leading-relaxed**"
    :class="[
        $store.app.sidebar ? 'toggle-sidebar' : '',
        $store.app.theme === 'dark' || $store.app.isDarkMode ? 'dark' : '',
        $store.app.menu,
        $store.app.layout,
        $store.app.rtlClass
    ]">

    <div x-cloak class="fixed inset-0 bg-[black]/60 z-50 lg:hidden" :class="{ 'hidden': !$store.app.sidebar }"
        @click="$store.app.toggleSidebar()" aria-hidden="true">
    </div>


    <div class="main-container text-black dark:text-white-dark min-h-screen" :class="[$store.app.navbar]">

        <x-common.sidebar />

        <div class="main-content flex flex-col min-h-screen">
            <x-common.header />
            @isset($header)
                <div class="p-4 bg-white dark:bg-[#111827] border-b border-gray-200 dark:border-gray-700">
                    {{ $header }}
                </div>
            @endisset
            <div class="**p-8** animate__animated flex-grow" :class="[$store.app.animation]">

                <x-flash-messages />

                <div class="max-w-full">
                    {{ $slot }}
                </div>

            </div>
            <x-common.footer />
        </div>
    </div>

    <script src="/assets/js/custom.js"></script>
    @stack('scripts')
</body>

</html>
