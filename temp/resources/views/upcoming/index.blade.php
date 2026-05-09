<x-app-layout>
    @section('title', 'Upcoming Episodes')
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-surface-900 dark:text-white">Upcoming Episodes</h1>
                    <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">All scheduled episodes across your watchlist</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs text-surface-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                Timezone: {{ auth()->user()->timezone ?? 'UTC' }}
            </div>
        </div>
    </x-slot>

    {{-- Filters --}}
    <div class="glass-card p-4 mb-6">
        <form method="GET" action="{{ route('upcoming.index') }}" class="flex flex-col sm:flex-row gap-3">
            <select name="days" class="input-enhanced w-full sm:w-44" id="days-filter">
                @foreach([7=>'Next 7 days', 14=>'Next 14 days', 30=>'Next 30 days', 90=>'Next 3 months', 0=>'All upcoming'] as $val=>$label)
                <option value="{{ $val }}" {{ request('days', 14) == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="type" class="input-enhanced w-full sm:w-40" id="type-filter">
                <option value="">All Types</option>
                @foreach(['drama'=>'Drama','movie'=>'Movie','anime'=>'Anime','tv_series'=>'TV Series'] as $v=>$l)
                <option value="{{ $v }}" {{ request('type')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary flex-shrink-0">Apply</button>
            @if(request()->hasAny(['days','type']))<a href="{{ route('upcoming.index') }}" class="btn-secondary flex-shrink-0">Clear</a>@endif
        </form>
    </div>

    @if($grouped->count() > 0)
        @foreach($grouped as $dateLabel => $dayEpisodes)
        <div class="mb-6">
            {{-- Date Header --}}
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-accent-500 flex items-center justify-center flex-shrink-0 shadow-lg shadow-brand-500/20">
                    <span class="text-white text-xs font-bold leading-tight text-center">
                        {{ \Carbon\Carbon::parse($dayEpisodes->first()->air_datetime)->setTimezone(auth()->user()->timezone??'UTC')->format('d') }}<br>
                        <span class="text-[9px] uppercase">{{ \Carbon\Carbon::parse($dayEpisodes->first()->air_datetime)->setTimezone(auth()->user()->timezone??'UTC')->format('M') }}</span>
                    </span>
                </div>
                <div>
                    <p class="font-semibold text-surface-900 dark:text-white text-sm">{{ $dateLabel }}</p>
                    <p class="text-xs text-surface-400">{{ $dayEpisodes->count() }} episode{{ $dayEpisodes->count()>1?'s':'' }}</p>
                </div>
                <div class="flex-1 h-px bg-surface-200/60 dark:bg-surface-700/60 ml-1"></div>
            </div>

            {{-- Episodes for this day --}}
            <div class="space-y-2 pl-13">
                @foreach($dayEpisodes as $ep)
                <div class="glass-card p-4 flex items-center gap-4 hover:shadow-md transition-all hover:-translate-y-0.5 group">
                    {{-- Poster Thumb --}}
                    <a href="{{ route('watchlist.show', $ep->show->slug) }}" class="flex-shrink-0">
                        @if($ep->show->poster_url)
                            <img src="{{ $ep->show->poster_url }}" alt="{{ $ep->show->title }}"
                                 class="w-10 h-14 object-cover rounded-lg shadow group-hover:shadow-md transition-shadow">
                        @else
                            <div class="w-10 h-14 rounded-lg bg-gradient-to-br from-surface-600 to-surface-700 flex items-center justify-center">
                                <svg class="w-4 h-4 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z"/></svg>
                            </div>
                        @endif
                    </a>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="{{ route('watchlist.show', $ep->show->slug) }}" class="text-sm font-semibold text-surface-900 dark:text-white hover:text-brand-500 dark:hover:text-brand-400 transition-colors truncate">
                                {{ $ep->show->title }}
                            </a>
                            <span class="text-xs px-1.5 py-0.5 rounded bg-brand-500/10 text-brand-500 font-medium flex-shrink-0">
                                {{ $ep->season_no ? 'S'.$ep->season_no.'E'.$ep->episode_no : 'Ep '.$ep->episode_no }}
                            </span>
                        </div>
                        @if($ep->title)
                        <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5 truncate">{{ $ep->title }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-1">
                            <p class="text-xs text-surface-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                {{ $ep->air_datetime->setTimezone(auth()->user()->timezone??'UTC')->format('g:i A') }}
                            </p>
                            @if($ep->duration_minutes)
                            <p class="text-xs text-surface-400">{{ $ep->duration_minutes }}m</p>
                            @endif
                            <span class="text-xs px-1.5 py-0.5 rounded bg-surface-100 dark:bg-surface-800 text-surface-500 capitalize">{{ str_replace('_',' ',$ep->show->type) }}</span>
                        </div>
                    </div>

                    {{-- Time until --}}
                    <div class="flex-shrink-0 text-right">
                        @php
                            $diff = now()->diffForHumans($ep->air_datetime, true);
                            $isPast = $ep->air_datetime->isPast();
                        @endphp
                        <p class="text-xs font-medium {{ $isPast ? 'text-red-500' : 'text-amber-500' }}">
                            {{ $isPast ? 'Aired' : 'in ' . $diff }}
                        </p>
                        @if(!$isPast)
                        <p class="text-[10px] text-surface-400 mt-0.5">{{ $ep->air_datetime->setTimezone(auth()->user()->timezone??'UTC')->format('M d') }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

    @else
    <div class="glass-card p-16 text-center">
        <div class="w-16 h-16 rounded-2xl bg-amber-500/10 flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
        </div>
        <h2 class="text-lg font-bold text-surface-900 dark:text-white mb-2">No upcoming episodes</h2>
        <p class="text-surface-500 dark:text-surface-400 mb-6 max-w-sm mx-auto">Add episodes to your shows and set air dates to see them here.</p>
        <a href="{{ route('watchlist.index') }}" class="btn-primary">Go to Watchlist</a>
    </div>
    @endif
</x-app-layout>
