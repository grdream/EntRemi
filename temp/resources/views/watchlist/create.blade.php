<x-app-layout>
    @section('title', 'Add Show')
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('watchlist.index') }}" class="w-9 h-9 rounded-xl bg-surface-100 dark:bg-surface-800 flex items-center justify-center text-surface-500 hover:bg-surface-200 dark:hover:bg-surface-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-surface-900 dark:text-white">Add to Watchlist</h1>
                <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Search TMDB & Jikan or enter details manually.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-700 dark:text-red-400 text-sm">
            <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('watchlist.store') }}" id="add-show-form">
            @csrf

            {{-- Recommendations --}}
            @if(isset($recommendations) && $recommendations->count() > 0)
            <div class="mb-6">
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.866 8.21 8.21 0 0 0 3 2.48Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" /></svg>
                    Trending Right Now
                </h2>
                <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                    @foreach($recommendations as $rec)
                        <div class="relative group rounded-xl overflow-hidden aspect-[2/3] bg-surface-200 dark:bg-surface-800 shadow-sm cursor-pointer"
                             onclick="window.Livewire.dispatch('search-term-selected', { term: '{{ addslashes($rec['title']) }}' })">
                            @if($rec['poster_url'])
                                <img src="{{ $rec['poster_url'] }}" alt="{{ $rec['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 inset-x-0 p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <p class="text-[10px] font-bold text-white leading-tight truncate">{{ $rec['title'] }}</p>
                                <p class="text-[9px] text-brand-300">Click to Search</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Search Panel --}}
            <div class="glass-card p-6 mb-5">
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    Auto-Search
                    @if(!$tmdbConfigured)<span class="text-[10px] px-2 py-0.5 rounded bg-amber-500/10 text-amber-600 border border-amber-500/20">TMDB not configured — Jikan only</span>@endif
                </h2>
                <livewire:show-search />
            </div>

            {{-- Divider --}}
            <div class="flex items-center gap-4 mb-5">
                <hr class="flex-1 border-surface-200/60 dark:border-surface-700/60">
                <span class="text-xs text-surface-400 font-medium">OR FILL MANUALLY</span>
                <hr class="flex-1 border-surface-200/60 dark:border-surface-700/60">
            </div>

            {{-- Manual Fields --}}
            <div class="glass-card p-6 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Title <span class="text-red-400">*</span></label>
                        <input name="title" type="text" class="input-enhanced" value="{{ old('title') }}" placeholder="e.g. Goblin, Inception, One Piece" id="manual-title">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Type <span class="text-red-400">*</span></label>
                        <select name="type" class="input-enhanced" id="type-select">
                            @foreach(['drama'=>'Drama','movie'=>'Movie','anime'=>'Anime','tv_series'=>'TV Series','anime_movie'=>'Anime Movie','other'=>'Other'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('type')===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Status <span class="text-red-400">*</span></label>
                        <select name="status" class="input-enhanced" id="status-select">
                            @foreach(['plan_to_watch'=>'Plan to Watch','watching'=>'Watching','completed'=>'Completed','on_hold'=>'On Hold','dropped'=>'Dropped'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('status','plan_to_watch')===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Year</label>
                        <input name="year" type="text" class="input-enhanced" value="{{ old('year') }}" placeholder="2024" id="year-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Rating (0–10)</label>
                        <input name="rating" type="text" class="input-enhanced" value="{{ old('rating') }}" placeholder="8.5" id="rating-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Total Episodes</label>
                        <input name="total_episodes" type="number" min="1" class="input-enhanced" value="{{ old('total_episodes') }}" placeholder="16" id="total-eps-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Country</label>
                        <input name="country" type="text" class="input-enhanced" value="{{ old('country') }}" placeholder="South Korea" id="country-input">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Poster URL</label>
                        <input name="poster_url" type="url" class="input-enhanced" value="{{ old('poster_url') }}" placeholder="https://…" id="poster-url-input">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Description</label>
                        <textarea name="description" rows="3" class="input-enhanced resize-none" placeholder="Brief description…" id="description-input">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 justify-end">
                <a href="{{ route('watchlist.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary" id="add-show-submit">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add to Watchlist
                </button>
            </div>
        </form>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('result-selected', (event) => {
                const data = event[0].data; // Livewire v3 structure
                
                document.getElementById('manual-title').value = data.title || '';
                
                if (data.type) {
                    const typeSelect = document.getElementById('type-select');
                    const options = Array.from(typeSelect.options).map(opt => opt.value);
                    if (options.includes(data.type)) {
                        typeSelect.value = data.type;
                    } else {
                        typeSelect.value = 'other';
                    }
                }

                if (data.year) document.getElementById('year-input').value = data.year;
                if (data.rating) document.getElementById('rating-input').value = data.rating;
                if (data.total_episodes) document.getElementById('total-eps-input').value = data.total_episodes;
                if (data.country) document.getElementById('country-input').value = data.country;
                if (data.poster_url) document.getElementById('poster-url-input').value = data.poster_url;
                if (data.description) document.getElementById('description-input').value = data.description;
                
                // Add hidden fields for TMDB/Jikan IDs if they don't exist yet
                let form = document.getElementById('add-show-form');
                
                ['tmdb_id', 'jikan_id', 'backdrop_url', 'language'].forEach(field => {
                    let input = document.getElementById('hidden-' + field);
                    if (!input) {
                        input = document.createElement('input');
                        input.type = 'hidden';
                        input.id = 'hidden-' + field;
                        input.name = field;
                        form.appendChild(input);
                    }
                    input.value = data[field] || '';
                });
                
                // Handle genres array
                // Remove old genres
                document.querySelectorAll('.hidden-genre').forEach(e => e.remove());
                if (data.genres && Array.isArray(data.genres)) {
                    data.genres.forEach(genre => {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.className = 'hidden-genre';
                        input.name = 'genres[]';
                        input.value = genre;
                        form.appendChild(input);
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
