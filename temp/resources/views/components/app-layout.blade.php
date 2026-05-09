@props(['header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && true) }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Track your shows, movies &amp; anime. Get smart reminders before episodes air.">

        <title>{{ config('app.name', 'WatchList Reminder') }} — @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
        <div class="min-h-screen bg-surface-50 dark:bg-surface-950 bg-mesh-gradient transition-colors duration-300">

            {{-- Mobile Sidebar Overlay --}}
            <div x-show="mobileSidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileSidebarOpen = false"
                 class="fixed inset-0 z-40 bg-surface-900/60 backdrop-blur-sm lg:hidden"
                 style="display: none;">
            </div>

            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Main Content Area --}}
            <div class="transition-all duration-300 lg:ml-[272px]"
                 :class="{ 'lg:ml-[72px]': !sidebarOpen, 'lg:ml-[272px]': sidebarOpen }">

                {{-- Top Bar --}}
                @include('layouts.topbar')

                {{-- Page Content --}}
                <main class="p-4 sm:p-6 lg:p-8 animate-fade-in">
                    {{-- Page Heading --}}
                    @if($header)
                        <div class="mb-6">
                            {{ $header }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
        @livewireScripts
    </body>
</html>
