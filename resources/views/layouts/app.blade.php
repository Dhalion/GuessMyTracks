<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'GuessMyTracks') }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'GuessMyTracks — discover and share music' }}">
    <meta name="theme-color" content="#0ea5a4">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        @media (prefers-reduced-motion: reduce) {
            * { animation-duration: 0.001ms !important; transition-duration: 0.001ms !important; }
        }
        .skip-link { position: absolute; left: -999px; top: auto; width: 1px; height: 1px; overflow: hidden; }
        .skip-link:focus { position: static; width: auto; height: auto; margin: .5rem; padding: .5rem 1rem; z-index: 1000; }
    </style>
</head>
<body class="antialiased font-sans bg-white text-slate-900">

    <a href="#main-content" class="skip-link">Weiter zum Inhalt</a>

    <div id="app">
        <header role="banner">
            {{-- optional nav slot --}}
            @hasSection('nav')
                @yield('nav')
            @endif
        </header>

        <main id="main-content" role="main" tabindex="-1">
            {{ $slot }}
        </main>

        <footer role="contentinfo">
            {{-- minimal footer; extend in pages if needed --}}
        </footer>
    </div>

    <noscript>
        <div class="noscript-warning">Diese Anwendung benötigt aktiviertes JavaScript für volle Funktionalität.</div>
    </noscript>

    @livewireScripts
    @stack('scripts')
</body>
</html>
