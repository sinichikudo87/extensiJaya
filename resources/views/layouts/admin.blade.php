<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>UP Work Langgeng Consultant</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#02040a] lg:flex">

    {{-- DESKTOP SIDEBAR --}}
    <aside class="hidden lg:flex">
        @include('components.sidebar-admin')
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- CONTENT (WAJIB SELALU ADA) --}}
        <main class="flex-1 p-4 pb-24">

            {{-- MOBILE DASHBOARD MENU (HANYA DI HOME) --}}
            @if (request()->routeIs('dashboard'))
                <div class="lg:hidden">
                    @include('components.mobile-menu-content')
                </div>
            @endif

            {{-- INI YANG PENTING --}}
            @yield('content')

        </main>

        {{-- BOTTOM NAV MOBILE --}}
        <div class="lg:hidden">
            @include('components.mobile-bottom-nav')
        </div>

    </div>
    @include('components.modal-chat')
    @include('admin.project-entry.modal')
</body>

</html>