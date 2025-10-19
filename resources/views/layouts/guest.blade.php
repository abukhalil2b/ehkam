<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        title
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-green-900">

    <header class="h-60 w-full bg-cover bg-center flex flex-col items-center">

    </header>
    
    {{ $slot }}


    <footer class="w-full h-16 bg-gray-800 text-white fixed bottom-0">
    </footer>
</body>
