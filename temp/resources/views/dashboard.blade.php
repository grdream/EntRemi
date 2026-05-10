<x-app-layout>
    @section('title', 'Dashboard')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-surface-900 dark:text-white">
                    Welcome back, <span class="text-gradient">{{ Auth::user()->name }}</span> 👋
                </h1>
                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Here's what's happening with your watchlist today.</p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="badge-brand">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ now(auth()->user()->timezone ?? 'UTC')->format('l, M d') }}
                </span>
            </div>
        </div>
    </x-slot>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 animate-slide-up" style="animation-delay: 0.1s">
        {{-- Total Shows --}}
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-brand-500 to-brand-600 shadow-glow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-surface-900 dark:text-white">{{ $totalShows }}</p>
                <p class="text-sm text-surface-500 dark:text-surface-400">Total Shows</p>
            </div>
        </div>

        {{-- Watching Now --}}
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-emerald-500 to-emerald-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-surface-900 dark:text-white">{{ $watchingCount }}</p>
                <p class="text-sm text-surface-500 dark:text-surface-400">Watching</p>
            </div>
        </div>

        {{-- Airing Today --}}
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-accent-500 to-accent-600 shadow-glow-accent">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-surface-900 dark:text-white">{{ $airingTodayCount }}</p>
                <p class="text-sm text-surface-500 dark:text-surface-400">Airing Today</p>
            </div>
        </div>

        {{-- This Week --}}
        <div class="stat-card">
            <div class="stat-icon bg-gradient-to-br from-amber-500 to-orange-500">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-surface-900 dark:text-white">{{ $thisWeekCount }}</p>
                <p class="text-sm text-surface-500 dark:text-surface-400">This Week</p>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Airing Today Section --}}
        <div class="lg:col-span-2 animate-slide-up" style="animation-delay: 0.2s">
            <div class="glass-card overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-surface-200/50 dark:border-surface-700/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-accent-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-accent-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-surface-900 dark:text-white">Airing Today</h2>
                    </div>
                </div>

                <div class="p-6">
                    <livewire:upcoming-episodes timeframe="today" />
                </div>
            </div>
        </div>

        {{-- Sidebar Panels --}}
        <div class="space-y-6 animate-slide-up" style="animation-delay: 0.3s">

            {{-- Upcoming This Week --}}
            <div class="glass-card overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-surface-200/50 dark:border-surface-700/50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-amber-500/10 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-surface-900 dark:text-white">This Week</h3>
                    </div>
                </div>
                <div class="p-5">
                    <livewire:upcoming-episodes timeframe="week" />
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="glass-card p-5">
                <h3 class="text-sm font-semibold text-surface-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('watchlist.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-surface-50 dark:bg-surface-800/50 hover:bg-surface-100 dark:hover:bg-surface-800 text-sm text-surface-600 dark:text-surface-300 transition-all duration-200 group">
                        <div class="w-8 h-8 rounded-lg bg-brand-500/10 flex items-center justify-center group-hover:bg-brand-500/20 transition-colors">
                            <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        Search & Add Shows
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-surface-50 dark:bg-surface-800/50 hover:bg-surface-100 dark:hover:bg-surface-800 text-sm text-surface-600 dark:text-surface-300 transition-all duration-200 group">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center group-hover:bg-emerald-500/20 transition-colors">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </div>
                        Configure Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
