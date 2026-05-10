<div>
    @if($episodes->count() > 0)
        <div class="space-y-3">
            @foreach($episodes as $ep)
            <div class="flex items-center justify-between p-3 rounded-xl bg-surface-50 dark:bg-surface-800/50 border border-surface-200/50 dark:border-surface-700/40 group hover:border-brand-500/30 transition-all">
                <div class="flex items-center gap-3">
                    <button wire:click="toggleWatched({{ $ep->id }})" class="w-6 h-6 rounded-md border border-surface-300 dark:border-surface-600 flex items-center justify-center text-transparent hover:border-brand-500 hover:text-brand-500 transition-colors" title="Mark as watched">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    </button>
                    <div>
                        <a href="{{ route('watchlist.show', $ep->show->slug) }}" class="text-sm font-medium text-surface-900 dark:text-white hover:text-brand-500 transition-colors">
                            {{ $ep->show->title }}
                        </a>
                        <p class="text-xs text-surface-500 dark:text-surface-400">
                            Ep {{ $ep->episode_no }} {{ $ep->title ? ' - '.$ep->title : '' }} 
                            <span class="text-surface-300 dark:text-surface-600 mx-1">•</span> 
                            {{ $ep->air_datetime->setTimezone($userTimezone)->format('g:i A') }}
                        </p>
                    </div>
                </div>
                <div class="hidden sm:block">
                    @if($ep->show->poster_url)
                        <img src="{{ $ep->show->poster_url }}" class="w-8 h-12 object-cover rounded shadow-sm">
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-8 px-6 text-center">
            <div class="w-12 h-12 rounded-xl bg-surface-100 dark:bg-surface-800 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-surface-300 dark:text-surface-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-surface-700 dark:text-surface-300">Nothing coming up</h3>
            <p class="text-xs text-surface-500 dark:text-surface-400 mt-1 max-w-[200px]">
                No episodes scheduled for {{ $timeframe }}.
            </p>
        </div>
    @endif
</div>
