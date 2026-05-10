<x-app-layout>
    @section('title', 'Upcoming Episodes')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-surface-900 dark:text-white">Upcoming Episodes</h1>
                    <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">All unaired episodes across your watchlist</p>
                </div>
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

    {{-- Tab Filter --}}
    <div x-data="{ tab: 'week' }" class="space-y-6">
        {{-- Tabs --}}
        <div class="flex gap-1 p-1 bg-surface-100 dark:bg-surface-800/60 rounded-xl border border-surface-200/60 dark:border-surface-700/60 w-fit">
            @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'all' => 'All Upcoming'] as $key => $label)
            <button @click="tab='{{ $key }}'"
                    :class="tab==='{{ $key }}' ? 'bg-white dark:bg-surface-700 text-surface-900 dark:text-white shadow-sm' : 'text-surface-500 hover:text-surface-700 dark:hover:text-surface-300'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all">
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Today --}}
        <div x-show="tab==='today'" x-transition class="glass-card overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200/50 dark:border-surface-700/50 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-accent-500/10 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-accent-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-surface-900 dark:text-white">Airing Today</h2>
                    <span class="text-xs px-2 py-0.5 rounded-md bg-accent-500/10 text-accent-500 font-bold">{{ $today->count() }}</span>
                </div>
            </div>
            <div class="divide-y divide-surface-200/50 dark:divide-surface-700/50">
                @forelse($today as $episode)
                    @include('upcoming._episode_row', ['episode' => $episode])
                @empty
                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-surface-100 dark:bg-surface-800 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-surface-700 dark:text-surface-300">Nothing airing today</p>
                    <p class="text-xs text-surface-500 mt-1">Check back tomorrow or view upcoming episodes this week.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- This Week --}}
        <div x-show="tab==='week'" x-transition class="glass-card overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200/50 dark:border-surface-700/50 flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-500/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white">This Week</h2>
                <span class="text-xs px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-500 font-bold">{{ $week->count() }}</span>
            </div>
            <div class="divide-y divide-surface-200/50 dark:divide-surface-700/50">
                @forelse($week as $episode)
                    @include('upcoming._episode_row', ['episode' => $episode])
                @empty
                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                    <p class="text-sm text-surface-500">No episodes airing this week.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- This Month --}}
        <div x-show="tab==='month'" x-transition class="glass-card overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200/50 dark:border-surface-700/50 flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-brand-500/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white">This Month</h2>
                <span class="text-xs px-2 py-0.5 rounded-md bg-brand-500/10 text-brand-500 font-bold">{{ $month->count() }}</span>
            </div>
            <div class="divide-y divide-surface-200/50 dark:divide-surface-700/50">
                @forelse($month as $episode)
                    @include('upcoming._episode_row', ['episode' => $episode])
                @empty
                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                    <p class="text-sm text-surface-500">No episodes scheduled this month.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- All Upcoming --}}
        <div x-show="tab==='all'" x-transition class="glass-card overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200/50 dark:border-surface-700/50 flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white">All Upcoming</h2>
                <span class="text-xs px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-500 font-bold">{{ $all->count() }}</span>
            </div>
            <div class="divide-y divide-surface-200/50 dark:divide-surface-700/50">
                @forelse($all as $episode)
                    @include('upcoming._episode_row', ['episode' => $episode])
                @empty
                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-surface-100 dark:bg-surface-800 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-surface-700 dark:text-surface-300">No upcoming episodes</p>
                    <p class="text-xs text-surface-500 mt-1 max-w-sm">Add shows and create schedules to see upcoming episodes here.</p>
                    <a href="{{ route('watchlist.create') }}" class="mt-4 btn-primary text-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Add Shows
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
