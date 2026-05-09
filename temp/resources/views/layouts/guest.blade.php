<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && true) }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="WatchList Reminder — Track your shows, movies & anime. Never miss an episode.">

        <title>{{ config('app.name', 'WatchList Reminder') }} — @yield('title', 'Welcome')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex bg-surface-50 dark:bg-surface-950 transition-colors duration-300">

            {{-- Left Panel: Branding --}}
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
                {{-- Gradient Background --}}
                <div class="absolute inset-0 bg-gradient-to-br from-brand-600 via-brand-700 to-surface-900"></div>

                {{-- Mesh overlay --}}
                <div class="absolute inset-0" style="background: radial-gradient(circle at 30% 20%, rgba(129, 140, 248, 0.3) 0, transparent 50%), radial-gradient(circle at 70% 80%, rgba(244, 114, 182, 0.2) 0, transparent 50%), radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.1) 0, transparent 80%);"></div>

                {{-- Floating shapes --}}
                <div class="absolute top-20 left-10 w-72 h-72 bg-brand-400/10 rounded-full blur-3xl animate-pulse-slow"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-accent-500/10 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 1.5s"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-brand-300/5 rounded-full blur-3xl"></div>

                {{-- Content --}}
                <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                    {{-- Logo --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">WatchList</h2>
                            <p class="text-[10px] font-medium text-brand-200 uppercase tracking-widest -mt-0.5">Reminder</p>
                        </div>
                    </div>

                    {{-- Hero Text --}}
                    <div class="max-w-md">
                        <h1 class="text-4xl font-extrabold text-white leading-tight mb-4">
                            Never miss an<br>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-brand-200 to-accent-300">episode again.</span>
                        </h1>
                        <p class="text-base text-brand-200/80 leading-relaxed">
                            Track your favorite dramas, movies, anime, and TV series. Get smart reminders via email and SMS before episodes air.
                        </p>

                        {{-- Feature Pills --}}
                        <div class="flex flex-wrap gap-2 mt-8">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/10 text-sm text-white/90">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Auto-fetch details
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/10 text-sm text-white/90">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Smart schedules
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/10 text-sm text-white/90">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Email & SMS alerts
                            </span>
                        </div>
                    </div>

                    {{-- Bottom --}}
                    <p class="text-sm text-brand-300/50">© {{ date('Y') }} WatchList Reminder. Track. Schedule. Never Miss.</p>
                </div>
            </div>

            {{-- Right Panel: Form --}}
            <div class="flex-1 flex flex-col items-center justify-center px-6 py-12 relative">
                {{-- Mesh gradient background --}}
                <div class="absolute inset-0 bg-mesh-gradient"></div>

                {{-- Dark Mode Toggle --}}
                <button @click="darkMode = !darkMode"
                        class="absolute top-6 right-6 p-2.5 rounded-xl text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-200 hover:bg-surface-100 dark:hover:bg-surface-800 transition-all duration-200 z-10"
                        id="auth-dark-mode-toggle">
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>

                {{-- Mobile Logo --}}
                <div class="lg:hidden mb-8 flex items-center gap-3 relative z-10">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-accent-500 flex items-center justify-center shadow-glow">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-surface-900 dark:text-white">WatchList</h2>
                        <p class="text-[10px] font-medium text-brand-500 dark:text-brand-400 uppercase tracking-widest -mt-0.5">Reminder</p>
                    </div>
                </div>

                {{-- Form Card --}}
                <div class="w-full max-w-md relative z-10">
                    <div class="glass-card p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
