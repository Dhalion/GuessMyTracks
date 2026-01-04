<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'GuessMyTracks') }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'GuessMyTracks — discover and share music' }}">
    <meta name="theme-color" content="#0ea5a4">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans bg-base-200 text-base-content">

    <div id="app">
        <main id="main-content" role="main" tabindex="-1">
            {{ $slot }}
        </main>
    </div>


    <noscript>
        <div class="noscript-warning">Diese Anwendung benötigt aktiviertes JavaScript für volle Funktionalität.</div>
    </noscript>

    @livewireScripts
    @stack('scripts')
</body>

</html>