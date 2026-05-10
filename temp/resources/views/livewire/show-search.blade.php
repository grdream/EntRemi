<div class="space-y-4">

    {{-- Source Tabs + Search Input --}}
    <div class="flex flex-col sm:flex-row gap-3">
        {{-- Source Selector --}}
        <div class="flex gap-1 p-1 bg-surface-100 dark:bg-surface-800/60 rounded-xl border border-surface-200/60 dark:border-surface-700/60 flex-shrink-0">
            @foreach(['all' => 'All Sources', 'tmdb' => 'TMDB', 'jikan' => 'Anime (Jikan)', 'youtube' => 'Paste Link'] as $val => $label)
            <button wire:click="$set('source', '{{ $val }}')"
                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
                           {{ $source === $val ? 'bg-white dark:bg-surface-700 text-surface-900 dark:text-white shadow-sm' : 'text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-300' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Search Input --}}
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
            <input wire:model.live.debounce.600ms="query"
                   type="text"
                   placeholder="Search by title or paste YouTube link…"
                   class="input-enhanced pl-10 pr-10"
                   id="show-search-input"
                   autocomplete="off">
            @if($query)
            <button wire:click="clearSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
            @endif
        </div>
    </div>

    {{-- Selected Result → Pre-fill Form --}}
    @if($selected)
    <div class="p-4 rounded-xl bg-brand-500/10 border border-brand-500/30 flex items-start gap-4 animate-fade-in">
        @if($selected['poster_url'])
        <img src="{{ $selected['poster_url'] }}" alt="{{ $selected['title'] }}"
             class="w-16 h-24 object-cover rounded-lg shadow-md flex-shrink-0">
        @else
        <div class="w-16 h-24 rounded-lg bg-surface-800 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-surface-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
            </svg>
        </div>
        @endif
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="font-semibold text-surface-900 dark:text-white">{{ $selected['title'] }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="badge badge-brand capitalize">{{ str_replace('_', ' ', $selected['type']) }}</span>
                        <span class="badge badge-accent capitalize">{{ $selected['source'] === 'tmdb' ? 'TMDB' : 'Jikan/MAL' }}</span>
                        @if($selected['year'])<span class="text-xs text-surface-400">{{ $selected['year'] }}</span>@endif
                        @if($selected['rating'])<span class="text-xs text-amber-500">★ {{ $selected['rating'] }}</span>@endif
                    </div>
                </div>
                <button wire:click="clearSelection" class="text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-xs text-surface-400 mt-1.5 line-clamp-2">{{ $selected['description'] ?? 'No description available.' }}</p>
        </div>
    </div>

    {{-- Hidden inputs removed to prevent conflicts. JS will populate the manual fields. --}}
    @endif

    {{-- Loading --}}
    <div wire:loading wire:target="query,source" class="flex items-center gap-2 py-2 text-sm text-surface-400">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        Searching…
    </div>

    {{-- Results Grid --}}
    @if($searched && !$searching)
        @if(count($results) > 0)
        <div wire:loading.remove wire:target="query,source">
            <p class="text-xs text-surface-400 mb-3">{{ count($results) }} result(s) found — click one to select it</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 max-h-[420px] overflow-y-auto pr-1">
                @foreach($results as $index => $result)
                <button wire:click="selectResult({{ $index }})" type="button"
                        class="group relative text-left rounded-xl overflow-hidden border-2 transition-all duration-200
                               {{ ($selected && ($selected['tmdb_id'] ?? '') === ($result['tmdb_id'] ?? '') && ($selected['jikan_id'] ?? '') === ($result['jikan_id'] ?? ''))
                                  ? 'border-brand-500 ring-2 ring-brand-500/30'
                                  : 'border-surface-200/50 dark:border-surface-700/50 hover:border-brand-500/50 hover:shadow-lg' }}
                               bg-surface-100 dark:bg-surface-800/60">
                    {{-- Poster --}}
                    <div class="aspect-[2/3] relative overflow-hidden bg-surface-200 dark:bg-surface-700">
                        @if($result['poster_url'])
                            <img src="{{ $result['poster_url'] }}" alt="{{ $result['title'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z"/>
                                </svg>
                            </div>
                        @endif
                        {{-- Source badge --}}
                        <div class="absolute top-1.5 left-1.5">
                            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-semibold
                                       {{ $result['source'] === 'tmdb' ? 'bg-brand-500 text-white' : 'bg-accent-500 text-white' }}">
                                {{ strtoupper($result['source']) }}
                            </span>
                        </div>
                        {{-- Rating --}}
                        @if($result['rating'])
                        <div class="absolute top-1.5 right-1.5">
                            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-semibold bg-black/60 text-amber-400">
                                ★ {{ $result['rating'] }}
                            </span>
                        </div>
                        @endif
                        {{-- Selected check --}}
                        @if($selected && ($selected['title'] === $result['title']))
                        <div class="absolute inset-0 bg-brand-500/20 flex items-center justify-center">
                            <div class="w-8 h-8 rounded-full bg-brand-500 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                            </div>
                        </div>
                        @endif
                    </div>
                    {{-- Title --}}
                    <div class="p-2">
                        <p class="text-xs font-medium text-surface-800 dark:text-surface-200 line-clamp-2 leading-tight">
                            {{ $result['title'] }}
                        </p>
                        <p class="text-[10px] text-surface-400 mt-0.5">{{ $result['year'] ?? '—' }}</p>
                    </div>
                </button>
                @endforeach
            </div>
        </div>
        @else
        <div wire:loading.remove wire:target="query,source" class="py-8 text-center">
            <svg class="w-10 h-10 text-surface-300 dark:text-surface-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
            <p class="text-sm text-surface-500 dark:text-surface-400">No results for "<strong>{{ $query }}</strong>"</p>
            <p class="text-xs text-surface-400 mt-1">Try a different title or add manually below.</p>
        </div>
        @endif
    @endif
</div>
