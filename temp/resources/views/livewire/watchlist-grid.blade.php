<div class="space-y-6">
    {{-- Filters + Search --}}
    <div class="glass-card p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Search your watchlist…"
                       class="input-enhanced pl-10" id="watchlist-search">
            </div>
            {{-- Status --}}
            <select wire:model.live="statusFilter" class="input-enhanced w-full sm:w-44" id="status-filter">
                <option value="">All Statuses</option>
                @foreach(['watching'=>'Watching','completed'=>'Completed','on_hold'=>'On Hold','dropped'=>'Dropped','plan_to_watch'=>'Plan to Watch'] as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>
            {{-- Type --}}
            <select wire:model.live="typeFilter" class="input-enhanced w-full sm:w-40" id="type-filter">
                <option value="">All Types</option>
                @foreach(['drama'=>'Drama','movie'=>'Movie','anime'=>'Anime','tv_series'=>'TV Series','anime_movie'=>'Anime Movie','other'=>'Other'] as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>
            {{-- Sort --}}
            <select wire:model.live="sortField" class="input-enhanced w-full sm:w-44" id="sort-filter">
                @foreach(['updated_at'=>'Last Updated','created_at'=>'Date Added','title'=>'Title A–Z','year'=>'Year','rating'=>'Rating'] as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Grid --}}
    @if($shows->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 lg:gap-6 relative min-h-[200px]">
        <div wire:loading.delay.longer class="absolute inset-0 bg-surface-50/50 dark:bg-surface-900/50 backdrop-blur-sm z-10 flex items-center justify-center rounded-2xl">
            <svg class="animate-spin h-8 w-8 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        
        @foreach($shows as $show)
        <div class="group relative glass-card overflow-hidden p-0 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            {{-- Poster --}}
            <a href="{{ route('watchlist.show', $show->slug) }}" class="block relative aspect-[2/3] overflow-hidden bg-surface-200 dark:bg-surface-800">
                @if($show->poster_url)
                    <img src="{{ $show->poster_url }}" alt="{{ $show->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center gap-2 bg-gradient-to-br from-surface-700 to-surface-800">
                        <svg class="w-10 h-10 text-surface-500" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z"/>
                        </svg>
                        <span class="text-xs text-surface-500 text-center px-2">No Poster</span>
                    </div>
                @endif
                {{-- Overlay badges --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                {{-- Status badge --}}
                <div class="absolute top-2 left-2">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold text-white
                        {{ match($show->status) {
                            'watching' => 'bg-emerald-500',
                            'completed' => 'bg-sky-500',
                            'on_hold' => 'bg-amber-500',
                            'dropped' => 'bg-red-500',
                            default => 'bg-surface-600'
                        } }}">
                        {{ ucwords(str_replace('_', ' ', $show->status)) }}
                    </span>
                </div>
                {{-- Rating --}}
                @if($show->rating)
                <div class="absolute top-2 right-2">
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-semibold bg-black/60 text-amber-400">
                        ★ {{ $show->rating }}
                    </span>
                </div>
                @endif
                {{-- Hover actions --}}
                <div class="absolute bottom-2 inset-x-2 flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <a href="{{ route('watchlist.show', $show->slug) }}"
                       class="flex-1 py-1.5 rounded-lg bg-brand-500 text-white text-xs font-semibold text-center hover:bg-brand-600 transition-colors">
                        View
                    </a>
                    <a href="{{ route('watchlist.edit', $show->slug) }}"
                       class="px-2.5 py-1.5 rounded-lg bg-surface-900/80 text-white text-xs hover:bg-surface-900 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                    </a>
                </div>
            </a>
            
            <div class="p-3">
                <h3 class="text-sm font-semibold text-surface-900 dark:text-white truncate group-hover:text-brand-500 transition-colors" title="{{ $show->title }}">{{ $show->title }}</h3>
                <div class="flex items-center gap-2 text-xs text-surface-500 dark:text-surface-400 mt-0.5">
                    <span class="capitalize">{{ str_replace('_', ' ', $show->type) }}</span>
                    @if($show->year)<span>• {{ $show->year }}</span>@endif
                </div>
                @if($show->episodes_count > 0)
                <p class="text-[10px] text-surface-400 mt-1">{{ $show->episodes_count }} eps</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $shows->links() }}
    </div>
    @else
    <div class="flex flex-col items-center justify-center py-20 px-6 text-center bg-surface-50 dark:bg-surface-800/20 rounded-3xl border border-dashed border-surface-200 dark:border-surface-700">
        <div class="w-16 h-16 rounded-2xl bg-surface-100 dark:bg-surface-800 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-surface-400 dark:text-surface-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-surface-900 dark:text-white mb-2">No shows found</h3>
        <p class="text-sm text-surface-500 dark:text-surface-400 max-w-md mb-6">
            @if($statusFilter === '' && $search === '' && $typeFilter === '')
                Your watchlist is empty. Start adding shows to track your progress and get reminders!
            @else
                No shows match your current filters.
            @endif
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('watchlist.create') }}" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Discover Shows
            </a>
            @if($statusFilter !== '' || $search !== '' || $typeFilter !== '')
            <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('typeFilter', '')" class="btn-secondary">Clear Filters</button>
            @endif
        </div>
    </div>
    @endif
</div>
