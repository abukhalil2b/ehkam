<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset='utf-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'التقويم السنوي' }}</title>

    <link rel="icon" type="image/svg" href="{{ asset('assets/images/favicon.svg') }}" />

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    {{-- Core Scripts --}}
    <script src="{{ asset('assets/js/perfect-scrollbar.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/popper.min.js') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Cairo', sans-serif; }
        [x-cloak] { display: none !important; }

        /* Global Calendar Styles */
        .islamic-pattern {
            background-color: #064e3b;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 30L15 45L0 30L15 15L30 30zm30 0L45 45L30 30L45 15L60 30zM15 15L0 0h30L15 15zm30 0L30 0h30L45 15zM15 45L0 60h30L15 45zm30 0L30 60h30L45 45z' fill='%23065f46' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        /* Print Optimization */
        @media print {
            .print\:hidden, .no-print { display: none !important; }
            body { background: white !important; }
            .grid { display: grid !important; }
        }
    </style>
    @stack('styles')
</head>

<body x-data="main" class="antialiased relative text-sm font-normal overflow-x-hidden leading-relaxed bg-gray-100 dark:bg-[#060818] text-black dark:text-white-dark"
    :class="[$store.app.theme === 'dark' || $store.app.isDarkMode ? 'dark' : '', $store.app.rtlClass]">


    {{-- No Sidebar, No Header, No Footer --}}
    <div class="animate__animated min-h-screen" :class="[$store.app.animation]">
        
        <x-calendar.flash-message />
        
        {{ $slot }}

    </div>

    <script src="/assets/js/custom.js"></script>
    @stack('scripts')
</body>

</html>