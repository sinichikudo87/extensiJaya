<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Admin Panel</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-700 flex">

    {{-- Sidebar --}}
    @include('components.sidebar')

    <div class="flex-1 min-h-screen flex flex-col md:ml-64">

        {{-- Navbar --}}
        @include('components.navbar')

        {{-- Content --}}
        <main class="p-6 overflow-auto h-[calc(100vh-4rem)] scrollbar-thin scrollbar-thumb-gray-500 scrollbar-track-gray-300 scrollbar-thin">
            @yield('content')
        </main>
    </div>
</body>

</html>
