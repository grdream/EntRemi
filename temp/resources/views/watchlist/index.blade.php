<x-app-layout>
    @section('title', 'My Watchlist')

    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-500/10 dark:bg-brand-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0 1 18 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 0 1 6 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M19.125 12h1.5m0 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h1.5m14.25 0h1.5"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-surface-900 dark:text-white">My Watchlist</h1>
                    <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">{{ $stats['total'] }} titles tracked</p>
                </div>
            </div>
            <a href="{{ route('watchlist.create') }}" class="btn-primary" id="add-show-btn">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add Show
            </a>
        </div>
    </x-slot>

    {{-- Flash --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition class="mb-4 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        @foreach([
            ['label'=>'Total', 'val'=>$stats['total'], 'color'=>'from-brand-500 to-brand-600', 'link'=>route('watchlist.index')],
            ['label'=>'Watching', 'val'=>$stats['watching'], 'color'=>'from-emerald-500 to-emerald-600', 'link'=>route('watchlist.index', ['status'=>'watching'])],
            ['label'=>'Completed', 'val'=>$stats['completed'], 'color'=>'from-sky-500 to-sky-600', 'link'=>route('watchlist.index', ['status'=>'completed'])],
            ['label'=>'Plan to Watch', 'val'=>$stats['plan_to_watch'], 'color'=>'from-accent-500 to-accent-600', 'link'=>route('watchlist.index', ['status'=>'plan_to_watch'])],
        ] as $stat)
        <a href="{{ $stat['link'] }}" class="glass-card p-4 flex items-center gap-3 hover:shadow-lg transition-all hover:-translate-y-0.5">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $stat['color'] }} flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-base">{{ $stat['val'] }}</span>
            </div>
            <span class="text-sm text-surface-600 dark:text-surface-400 font-medium">{{ $stat['label'] }}</span>
        </a>
        @endforeach
    </div>

    {{-- Filters + Search --}}
    <div class="glass-card p-4 mb-6">
        <form method="GET" action="{{ route('watchlist.index') }}" class="flex flex-col sm:flex-row gap-3" id="watchlist-filter-form">
            {{-- Search --}}
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input name="search" type="text" value="{{ request('search') }}"
                       placeholder="Search your watchlist…"
                       class="input-enhanced pl-10" id="watchlist-search">
            </div>
            {{-- Status --}}
            <select name="status" class="input-enhanced w-full sm:w-44" id="status-filter">
                <option value="">All Statuses</option>
                @foreach(['watching'=>'Watching','completed'=>'Completed','on_hold'=>'On Hold','dropped'=>'Dropped','plan_to_watch'=>'Plan to Watch'] as $val => $label)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            {{-- Type --}}
            <select name="type" class="input-enhanced w-full sm:w-40" id="type-filter">
                <option value="">All Types</option>
                @foreach(['drama'=>'Drama','movie'=>'Movie','anime'=>'Anime','tv_series'=>'TV Series','anime_movie'=>'Anime Movie','other'=>'Other'] as $val => $label)
                <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            {{-- Sort --}}
            <select name="sort" class="input-enhanced w-full sm:w-44" id="sort-filter">
                @foreach(['updated_at'=>'Last Updated','created_at'=>'Date Added','title'=>'Title A–Z','year'=>'Year','rating'=>'Rating'] as $val => $label)
                <option value="{{ $val }}" {{ request('sort', 'updated_at') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary flex-shrink-0" id="filter-submit">Filter</button>
            @if(request()->hasAny(['search','status','type','sort']))
            <a href="{{ route('watchlist.index') }}" class="btn-secondary flex-shrink-0">Clear</a>
            @endif
        </form>
    </div>

    {{-- Shows Grid --}}
    @if($shows->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 mb-6">
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
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold
                        {{ match($show->status) {
                            'watching' => 'bg-emerald-500 text-white',
                            'completed' => 'bg-sky-500 text-white',
                            'on_hold' => 'bg-amber-500 text-white',
                            'dropped' => 'bg-red-500 text-white',
                            default => 'bg-surface-600 text-white'
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
                    <form method="POST" action="{{ route('watchlist.destroy', $show->slug) }}" class="inline"
                          onsubmit="return confirm('Remove {{ addslashes($show->title) }} from your watchlist?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-red-500/80 text-white text-xs hover:bg-red-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                        </button>
                    </form>
                </div>
            </a>

            {{-- Info --}}
            <div class="p-3">
                <h3 class="text-xs font-semibold text-surface-900 dark:text-white line-clamp-2 leading-tight mb-1">
                    <a href="{{ route('watchlist.show', $show->slug) }}" class="hover:text-brand-500 dark:hover:text-brand-400 transition-colors">
                        {{ $show->title }}
                    </a>
                </h3>
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-[10px] text-surface-400">{{ str_replace('_', ' ', ucfirst($show->type)) }}</span>
                    @if($show->year)<span class="text-[10px] text-surface-400">· {{ $show->year }}</span>@endif
                </div>
                @if($show->episodes_count > 0)
                <p class="text-[10px] text-surface-400 mt-1">{{ $show->episodes_count }} eps</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    {{ $shows->links() }}

    @else
    {{-- Empty state --}}
    <div class="glass-card p-16 text-center">
        <div class="w-20 h-20 rounded-2xl bg-brand-500/10 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375M20.625 4.5H3.375"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-surface-900 dark:text-white mb-2">
            @if(request()->hasAny(['search','status','type']))
                No shows match your filters
            @else
                Your watchlist is empty
            @endif
        </h2>
        <p class="text-surface-500 dark:text-surface-400 mb-8 max-w-sm mx-auto">
            @if(request()->hasAny(['search','status','type']))
                Try clearing the filters or search with different terms.
            @else
                Start by adding your first show, movie or anime. Auto-fetch details from TMDB and Jikan.
            @endif
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('watchlist.create') }}" class="btn-primary" id="empty-add-btn">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Your First Show
            </a>
            @if(request()->hasAny(['search','status','type']))
            <a href="{{ route('watchlist.index') }}" class="btn-secondary">Clear Filters</a>
            @endif
        </div>
    </div>
    @endif
</x-app-layout>
