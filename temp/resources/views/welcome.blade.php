<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && true) }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="WatchList Reminder — Track dramas, movies & anime. Get smart email & SMS reminders before episodes air.">
    <title>WatchList Reminder — Never Miss an Episode</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-surface-50 text-surface-900 dark:bg-surface-950 dark:text-white overflow-x-hidden transition-colors duration-200">

    <!-- Ambient Background Gradients -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[40%] -left-[20%] w-[80%] h-[80%] rounded-full bg-brand-500/10 dark:bg-brand-500/20 blur-[120px]"></div>
        <div class="absolute top-[20%] -right-[20%] w-[60%] h-[60%] rounded-full bg-accent-500/10 dark:bg-accent-500/20 blur-[120px]"></div>
        <div class="absolute -bottom-[40%] left-[10%] w-[70%] h-[70%] rounded-full bg-indigo-500/10 dark:bg-indigo-500/20 blur-[120px]"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col">
        {{-- Nav --}}
        <nav class="fixed top-0 inset-x-0 z-50 flex items-center justify-between px-6 lg:px-16 h-16 bg-white/70 dark:bg-surface-950/80 backdrop-blur-xl border-b border-surface-200/50 dark:border-white/5 transition-colors duration-200">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-accent-500 flex items-center justify-center shadow-glow-sm">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z"/>
                </svg>
            </div>
            <span class="text-base font-bold tracking-tight">WatchList <span class="text-brand-400">Reminder</span></span>
        </div>
        <div class="flex items-center gap-3">
            <button @click="darkMode = !darkMode" class="p-2 rounded-xl text-surface-400 hover:text-white hover:bg-white/10 transition-all duration-200">
                <svg x-show="darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
            </button>
            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-surface-600 hover:text-surface-900 dark:text-surface-300 dark:hover:text-white transition-colors">Sign In</a>
            <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 shadow-lg shadow-brand-500/30 transition-all duration-200 hover:-translate-y-0.5">Get Started Free</a>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative min-h-screen flex items-center pt-16">
        {{-- Background --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-600/20 rounded-full blur-3xl animate-pulse-slow"></div>
            <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-accent-600/15 rounded-full blur-3xl animate-pulse-slow" style="animation-delay:2s"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-brand-900/20 rounded-full blur-3xl"></div>
            {{-- Grid overlay --}}
            <div class="absolute inset-0" style="background-image: linear-gradient(rgba(99,102,241,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.03) 1px, transparent 1px); background-size: 60px 60px;"></div>
        </div>

        <div class="relative z-10 max-w-6xl mx-auto px-6 lg:px-16 py-24 text-center">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-500/10 border border-brand-500/20 text-sm text-brand-300 mb-8 animate-fade-in">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse-slow"></span>
                Free to use · Email + SMS · Auto-fetch from TMDB & Jikan
            </div>

            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold leading-tight mb-6 animate-slide-up text-surface-900 dark:text-white">
                Track Every Show.<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-brand-500 via-brand-400 to-accent-500 dark:from-brand-400 dark:via-brand-300 dark:to-accent-400">Never Miss an Episode.</span>
            </h1>
            <p class="text-lg sm:text-xl text-surface-600 dark:text-surface-400 max-w-2xl mx-auto mb-10 leading-relaxed animate-slide-up" style="animation-delay:.1s">
                Add your favorite dramas, movies, anime & TV shows. Set smart schedules and get email or SMS reminders exactly when you need them.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up" style="animation-delay:.2s">
                <a href="{{ route('register') }}" id="hero-cta" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl font-semibold text-white bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 shadow-xl shadow-brand-500/30 transition-all duration-200 hover:-translate-y-1 hover:shadow-brand-500/50 text-base">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Start Tracking Free
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl font-semibold text-surface-700 dark:text-surface-300 border border-surface-300 dark:border-surface-700 hover:border-surface-400 dark:hover:border-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800 transition-all duration-200 text-base">
                    Sign In
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

            {{-- Feature pills --}}
            <div class="flex flex-wrap justify-center gap-3 mt-12 animate-fade-in" style="animation-delay:.4s">
                @foreach(['Dramas', 'Movies', 'Anime', 'TV Series', 'Email Alerts', 'SMS Alerts', 'Smart Schedules', 'TMDB & Jikan'] as $tag)
                <span class="px-3 py-1 rounded-full bg-surface-200/60 dark:bg-surface-800/60 border border-surface-300/50 dark:border-surface-700/50 text-xs text-surface-600 dark:text-surface-400">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Features Grid --}}
    <section class="py-24 relative">
        <div class="max-w-6xl mx-auto px-6 lg:px-16">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4 text-surface-900 dark:text-white">Everything you need to stay on track</h2>
                <p class="text-surface-600 dark:text-surface-400 max-w-xl mx-auto">From auto-fetching show details to firing reminders at exactly the right time — WatchList Reminder does it all.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['icon'=>'M21 21l-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z', 'color'=>'from-brand-500 to-brand-600', 'glow'=>'brand', 'title'=>'Auto-Fetch Details', 'desc'=>'Search TMDB & Jikan. Poster, synopsis, episodes & air dates fill automatically.'],
                    ['icon'=>'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5', 'color'=>'from-emerald-500 to-emerald-600', 'glow'=>'emerald', 'title'=>'Smart Schedules', 'desc'=>'Daily, weekly, bi-weekly, or custom patterns. Episode dates auto-generated for you.'],
                    ['icon'=>'M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75', 'color'=>'from-accent-500 to-accent-600', 'glow'=>'accent', 'title'=>'Email Reminders', 'desc'=>'Beautiful HTML emails with show poster & countdown. Use your own SMTP or our defaults.'],
                    ['icon'=>'M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3.75h3m-3 3.75h3M9.75 9.75h.008v.008H9.75V9.75Z', 'color'=>'from-amber-500 to-orange-500', 'glow'=>'amber', 'title'=>'SMS Alerts', 'desc'=>'Connect your ViserLab SMS gateway. Get text messages before episodes air.'],
                    ['icon'=>'M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6', 'color'=>'from-sky-500 to-cyan-500', 'glow'=>'sky', 'title'=>'Dashboard & History', 'desc'=>'See airing today, this week, and full notification history with retry support.'],
                    ['icon'=>'M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z', 'color'=>'from-violet-500 to-purple-600', 'glow'=>'violet', 'title'=>'Dark Mode Built-In', 'desc'=>'Full dark mode support saved to your preference. Easy on the eyes for late-night binge sessions.'],
                ] as $feat)
                <div class="group p-6 rounded-2xl bg-white/60 dark:bg-surface-900/60 border border-surface-200/60 dark:border-surface-800/60 hover:border-brand-500/30 dark:hover:border-brand-500/30 hover:bg-white dark:hover:bg-surface-900 transition-all duration-300 hover:-translate-y-1 shadow-sm hover:shadow-md">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $feat['color'] }} flex items-center justify-center mb-4 shadow-lg transition-all duration-300 group-hover:shadow-glow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feat['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-surface-900 dark:text-white mb-2">{{ $feat['title'] }}</h3>
                    <p class="text-sm text-surface-600 dark:text-surface-400 leading-relaxed">{{ $feat['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-24 relative">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute inset-x-0 bottom-0 h-64 bg-gradient-to-t from-brand-100/50 dark:from-brand-900/20 to-transparent"></div>
        </div>
        <div class="relative max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-extrabold mb-4 text-surface-900 dark:text-white">Ready to never miss an episode?</h2>
            <p class="text-surface-600 dark:text-surface-400 mb-8">Join WatchList Reminder. It's completely free to start.</p>
            <a href="{{ route('register') }}" id="bottom-cta" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl font-semibold text-white bg-gradient-to-r from-brand-500 to-accent-500 hover:from-brand-600 hover:to-accent-600 shadow-xl shadow-brand-500/30 transition-all duration-200 hover:-translate-y-1">
                Create Your Free Account
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-surface-200/50 dark:border-surface-800/50 py-8 text-center text-sm text-surface-500">
        <p>© {{ date('Y') }} WatchList Reminder &mdash; Track. Schedule. Never Miss.</p>
    </footer>

    </div> <!-- End relative z-10 wrapper -->
</body>
</html>
