<x-app-layout>
    @section('title', 'Edit — ' . $show->title)
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('watchlist.show', $show->slug) }}" class="w-9 h-9 rounded-xl bg-surface-100 dark:bg-surface-800 flex items-center justify-center text-surface-500 hover:bg-surface-200 dark:hover:bg-surface-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-surface-900 dark:text-white">Edit Show</h1>
                <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">{{ $show->title }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-700 dark:text-red-400 text-sm">
            <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('watchlist.update', $show->slug) }}" id="edit-show-form">
            @csrf @method('PUT')

            <div class="glass-card p-6 mb-6">
                <div class="flex items-start gap-5 mb-6 pb-6 border-b border-surface-200/40 dark:border-surface-700/40">
                    {{-- Current Poster Preview --}}
                    <div class="w-20 flex-shrink-0">
                        @if($show->poster_url)
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-20 h-28 object-cover rounded-xl shadow">
                        @else
                            <div class="w-20 h-28 rounded-xl bg-surface-700 flex items-center justify-center">
                                <svg class="w-7 h-7 text-surface-500" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-base font-semibold text-surface-900 dark:text-white">{{ $show->title }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-surface-400">Added {{ $show->created_at->diffForHumans() }}</span>
                            @if($show->tmdb_id)<span class="text-xs text-brand-500">· TMDB</span>@endif
                            @if($show->jikan_id)<span class="text-xs text-accent-500">· MAL</span>@endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Title <span class="text-red-400">*</span></label>
                        <input name="title" type="text" class="input-enhanced" value="{{ old('title', $show->title) }}" required id="edit-title">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Type <span class="text-red-400">*</span></label>
                        <select name="type" class="input-enhanced" id="edit-type">
                            @foreach(['drama'=>'Drama','movie'=>'Movie','anime'=>'Anime','tv_series'=>'TV Series','anime_movie'=>'Anime Movie','other'=>'Other'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('type',$show->type)===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Status <span class="text-red-400">*</span></label>
                        <select name="status" class="input-enhanced" id="edit-status">
                            @foreach(['plan_to_watch'=>'Plan to Watch','watching'=>'Watching','completed'=>'Completed','on_hold'=>'On Hold','dropped'=>'Dropped'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('status',$show->status)===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Year</label>
                        <input name="year" type="text" class="input-enhanced" value="{{ old('year', $show->year) }}" id="edit-year">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Rating</label>
                        <input name="rating" type="text" class="input-enhanced" value="{{ old('rating', $show->rating) }}" id="edit-rating">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Total Episodes</label>
                        <input name="total_episodes" type="number" min="1" class="input-enhanced" value="{{ old('total_episodes', $show->total_episodes) }}" id="edit-total-eps">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Country</label>
                        <input name="country" type="text" class="input-enhanced" value="{{ old('country', $show->country) }}" id="edit-country">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Poster URL</label>
                        <input name="poster_url" type="url" class="input-enhanced" value="{{ old('poster_url', $show->poster_url) }}" id="edit-poster-url">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Description</label>
                        <textarea name="description" rows="4" class="input-enhanced resize-none" id="edit-description">{{ old('description', $show->description) }}</textarea>
                    </div>

                    {{-- Genres as comma-separated text --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Genres <span class="font-normal text-surface-400">(comma-separated)</span></label>
                        @php $genreString = implode(', ', $show->genres ?? []); @endphp
                        <input id="genres-input" type="text" class="input-enhanced"
                               value="{{ old('_genres_text', $genreString) }}"
                               placeholder="Action, Drama, Romance"
                               oninput="syncGenres(this.value)">
                        <div id="genres-hidden"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 justify-end">
                <a href="{{ route('watchlist.show', $show->slug) }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary" id="edit-save-btn">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    function syncGenres(value) {
        const container = document.getElementById('genres-hidden');
        container.innerHTML = '';
        value.split(',').map(g => g.trim()).filter(Boolean).forEach(g => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'genres[]';
            inp.value = g;
            container.appendChild(inp);
        });
    }
    // Init on page load
    syncGenres(document.getElementById('genres-input').value);
    </script>
    @endpush
</x-app-layout>
