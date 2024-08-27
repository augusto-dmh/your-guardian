<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'K UI') }}</title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div x-data="mainState" x-on:resize.window="handleWindowResize" x-cloak>
        <div class="min-h-screen text-gray-200 bg-dark-eval-0">
            <!-- Sidebar -->
            <x-sidebar.sidebar />

            <!-- Page Wrapper -->
            <div class="relative flex flex-col justify-between min-h-screen" {{-- :class="{
                'lg:ml-64': isSidebarOpen,
                'md:ml-16': !isSidebarOpen
            }" --}}
                style="transition-property: margin; transition-duration: 150ms;">
                <!-- Navbar -->
                <x-navbar />

                <div class="px-8 md:ml-16">
                    @if (session('success'))
                        <div id="flash-message"
                            class="fixed z-30 p-4 text-white transition-opacity duration-1000 transform -translate-x-1/2 bg-green-500 rounded-md shadow-md top-24 left-1/2 whitespace-nowrap">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Page Heading -->
                    <header class="py-4">
                        {{ $header }}
                    </header>

                    <!-- Page Content -->
                    <main>
                        {{ $slot }}
                    </main>
                </div>

                <!-- Page Footer -->
                <x-footer />
            </div>
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                flashMessage.classList.add('opacity-0');
            }
        }, 3000);
    });
</script>
