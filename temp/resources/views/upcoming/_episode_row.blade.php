{{-- Episode Row Partial --}}
<div class="flex items-center gap-4 px-6 py-4 hover:bg-surface-50/50 dark:hover:bg-surface-800/30 transition-colors">
    {{-- Poster Thumbnail --}}
    <a href="{{ route('watchlist.show', $episode->show->slug) }}" class="flex-shrink-0">
        @if($episode->show->poster_url)
            <img src="{{ $episode->show->poster_url }}"
                 alt="{{ $episode->show->title }}"
                 class="w-10 h-14 rounded-lg object-cover shadow-md">
        @else
            <div class="w-10 h-14 rounded-lg bg-gradient-to-br from-surface-700 to-surface-800 flex items-center justify-center">
                <svg class="w-5 h-5 text-surface-500" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z"/>
                </svg>
            </div>
        @endif
    </a>

    {{-- Episode Info --}}
    <div class="flex-1 min-w-0">
        <a href="{{ route('watchlist.show', $episode->show->slug) }}" class="text-sm font-semibold text-surface-900 dark:text-white hover:text-brand-500 dark:hover:text-brand-400 transition-colors truncate block">
            {{ $episode->show->title }}
        </a>
        <div class="flex items-center gap-2 mt-0.5">
            <span class="text-xs text-surface-500">
                @if($episode->season_no)S{{ $episode->season_no }} · @endif
                Episode {{ $episode->episode_no }}
                @if($episode->title) — {{ Str::limit($episode->title, 30) }}@endif
            </span>
            <span class="text-xs px-1.5 py-0.5 rounded-md {{ match($episode->show->type) {
                'anime', 'anime_movie' => 'bg-accent-500/10 text-accent-500',
                'movie' => 'bg-amber-500/10 text-amber-500',
                default => 'bg-brand-500/10 text-brand-500'
            } }}">
                {{ ucwords(str_replace('_', ' ', $episode->show->type)) }}
            </span>
        </div>
    </div>

    {{-- Air Time --}}
    <div class="text-right flex-shrink-0">
        @if($episode->air_datetime)
            @php
                $userTz = auth()->user()->timezone ?? 'UTC';
                $airLocal = $episode->air_datetime->setTimezone($userTz);
                $isToday = $airLocal->isToday();
            @endphp
            <p class="text-sm font-semibold {{ $isToday ? 'text-accent-500' : 'text-surface-800 dark:text-surface-200' }}">
                {{ $isToday ? 'Today' : $airLocal->format('D, M d') }}
            </p>
            <p class="text-xs text-surface-500">{{ $airLocal->format('g:i A') }}</p>
            <p class="text-[10px] text-surface-400 mt-0.5">{{ $airLocal->diffForHumans() }}</p>
        @else
            <span class="text-xs text-surface-400">TBA</span>
        @endif
    </div>

    {{-- Mark Watched --}}
    <form method="POST" action="{{ route('episodes.toggle', [$episode->show->slug, $episode->id]) }}" class="flex-shrink-0">
        @csrf
        <button type="submit"
                class="w-8 h-8 rounded-lg bg-surface-100 dark:bg-surface-800 hover:bg-emerald-500/20 flex items-center justify-center text-surface-400 hover:text-emerald-500 transition-all"
                title="Mark as Watched">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
            </svg>
        </button>
    </form>
</div>
