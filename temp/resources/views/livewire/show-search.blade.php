<div class="space-y-4">

    {{-- Source Tabs + Search Input --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex gap-1 p-1 bg-surface-100 dark:bg-surface-800/60 rounded-xl border border-surface-200/60 dark:border-surface-700/60 flex-shrink-0">
            @foreach(['all' => 'All Sources', 'tmdb' => 'TMDB', 'jikan' => 'Anime (Jikan)', 'youtube' => 'Paste Link'] as $val => $label)
            <button wire:click="$set('source', '{{ $val }}')"
                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
                           {{ $source === $val ? 'bg-white dark:bg-surface-700 text-surface-900 dark:text-white shadow-sm' : 'text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-300' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>

        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
            <input wire:model.live.debounce.600ms="query" type="text"
                   placeholder="Search by title or paste YouTube link…"
                   class="input-enhanced pl-10 pr-10"
                   id="show-search-input" autocomplete="off">
            @if($query)
            <button wire:click="clearSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
            @endif
        </div>
    </div>

    {{-- Loading --}}
    <div wire:loading wire:target="query,source" class="flex items-center gap-2 py-2 text-sm text-surface-400">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
        Searching…
    </div>

    {{-- Results Grid --}}
    @if($searched && !$searching)
        @if(count($results) > 0)
        <div wire:loading.remove wire:target="query,source">
            <p class="text-xs text-surface-400 mb-3">{{ count($results) }} result(s) found — click a poster to add it directly</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 max-h-[420px] overflow-y-auto pr-1">
                @foreach($results as $index => $result)
                <button wire:click="selectResult({{ $index }})" type="button"
                        class="group relative text-left rounded-xl overflow-hidden border-2 transition-all duration-200
                               {{ ($selected && ($selected['title'] === $result['title'])) ? 'border-brand-500 ring-2 ring-brand-500/30' : 'border-surface-200/50 dark:border-surface-700/50 hover:border-brand-500/50 hover:shadow-lg' }}
                               bg-surface-100 dark:bg-surface-800/60">
                    <div class="aspect-[2/3] relative overflow-hidden bg-surface-200 dark:bg-surface-700">
                        @if($result['poster_url'])
                            <img src="{{ $result['poster_url'] }}" alt="{{ $result['title'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z"/></svg>
                            </div>
                        @endif
                        <div class="absolute top-1.5 left-1.5">
                            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-semibold {{ $result['source'] === 'tmdb' ? 'bg-brand-500 text-white' : 'bg-accent-500 text-white' }}">
                                {{ strtoupper($result['source']) }}
                            </span>
                        </div>
                        @if($result['rating'])
                        <div class="absolute top-1.5 right-1.5">
                            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-semibold bg-black/60 text-amber-400">★ {{ $result['rating'] }}</span>
                        </div>
                        @endif
                        {{-- Selected overlay --}}
                        @if($selected && $selected['title'] === $result['title'])
                        <div class="absolute inset-0 bg-brand-500/20 flex items-center justify-center">
                            <div class="w-8 h-8 rounded-full bg-brand-500 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                            </div>
                        </div>
                        @endif
                        {{-- Hover: Add button --}}
                        <div class="absolute inset-x-0 bottom-0 p-1.5 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <span class="block w-full text-center text-[10px] font-semibold text-white py-1 rounded-lg bg-brand-500/80 backdrop-blur-sm">
                                Click to Add
                            </span>
                        </div>
                    </div>
                    <div class="p-2">
                        <p class="text-xs font-medium text-surface-800 dark:text-surface-200 line-clamp-2 leading-tight">{{ $result['title'] }}</p>
                        <p class="text-[10px] text-surface-400 mt-0.5">{{ $result['year'] ?? '—' }}</p>
                    </div>
                </button>
                @endforeach
            </div>
        </div>
        @else
        <div wire:loading.remove wire:target="query,source" class="py-8 text-center">
            <svg class="w-10 h-10 text-surface-300 dark:text-surface-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            <p class="text-sm text-surface-500 dark:text-surface-400">No results for "<strong>{{ $query }}</strong>"</p>
            <p class="text-xs text-surface-400 mt-1">Try a different title or add manually below.</p>
        </div>
        @endif
    @endif

    {{-- Selected Detail Panel --}}
    @if($selected)
    <div class="mt-4 p-5 rounded-2xl bg-gradient-to-br from-brand-500/10 to-accent-500/5 border border-brand-500/30 animate-fade-in">
        <div class="flex gap-4">
            {{-- Poster --}}
            <div class="flex-shrink-0">
                @if($selected['poster_url'])
                <img src="{{ $selected['poster_url'] }}" alt="{{ $selected['title'] }}"
                     class="w-20 h-28 object-cover rounded-xl shadow-lg">
                @else
                <div class="w-20 h-28 rounded-xl bg-surface-800 flex items-center justify-center">
                    <svg class="w-6 h-6 text-surface-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-surface-900 dark:text-white text-base leading-tight">{{ $selected['title'] }}</h3>
                        <div class="flex flex-wrap items-center gap-2 mt-1.5">
                            <span class="badge badge-brand capitalize">{{ str_replace('_', ' ', $selected['type'] ?? 'other') }}</span>
                            <span class="badge badge-accent">{{ strtoupper($selected['source'] ?? '') }}</span>
                            @if($selected['year'])<span class="text-xs text-surface-400">{{ $selected['year'] }}</span>@endif
                            @if($selected['rating'])<span class="text-xs text-amber-500 font-medium">★ {{ $selected['rating'] }}</span>@endif
                            @if($selected['total_episodes'])<span class="text-xs text-surface-400">{{ $selected['total_episodes'] }} eps</span>@endif
                        </div>
                    </div>
                    <button wire:click="clearSelection" class="text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 transition-colors flex-shrink-0" title="Dismiss">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                @if($selected['description'])
                <p class="text-xs text-surface-500 dark:text-surface-400 mt-2 line-clamp-2 leading-relaxed">{{ $selected['description'] }}</p>
                @endif

                {{-- Direct Add Form --}}
                @if($addError)
                <p class="text-xs text-red-500 mt-2">{{ $addError }}</p>
                @endif

                <div class="flex items-center gap-3 mt-4">
                    <select wire:model="addStatus" class="input-enhanced text-sm py-2 flex-1">
                        <option value="plan_to_watch">📋 Plan to Watch</option>
                        <option value="watching">👁️ Currently Watching</option>
                        <option value="completed">✅ Completed</option>
                        <option value="on_hold">⏸️ On Hold</option>
                    </select>
                    <button wire:click="quickAdd" wire:loading.attr="disabled"
                            class="btn-primary text-sm py-2 flex-shrink-0 flex items-center gap-2">
                        <span wire:loading.remove wire:target="quickAdd">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        </span>
                        <svg wire:loading wire:target="quickAdd" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span wire:loading.remove wire:target="quickAdd">Add to Watchlist</span>
                        <span wire:loading wire:target="quickAdd">Adding…</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
