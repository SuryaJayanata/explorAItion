<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'eduSPACE')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-secondary text-white font-poppins min-h-screen overflow-x-hidden relative">
    <!-- Background Blobs -->
    <div class="bg-blob blob-1 fixed w-64 h-64 md:w-80 md:h-80 left-5 top-15"></div>
    <div class="bg-blob blob-2 fixed w-64 h-64 md:w-80 md:h-80 right-5 bottom-20"></div>

    <!-- Header -->
    @include('layouts.header')

    <!-- Main Content -->
    <main class="main-content flex-1 px-5 py-28 md:py-32 w-full relative z-10">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Scripts -->
    @stack('scripts')
    
    <style>
        .bg-blob {
            border-radius: 50%;
            background: rgba(199.32, 68.73, 239.06, 0.2);
            filter: blur(112.8px);
            z-index: 0;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .mobile-menu {
            display: flex !important;
        }
    </style>
</body>
</html>