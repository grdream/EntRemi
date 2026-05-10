<x-app-layout>
    @section('title', 'Add Show')
    <x-slot name="header">
        <h1 class="text-xl font-bold text-surface-900 dark:text-white">Discover & Add Shows</h1>
        <p class="text-xs text-surface-500 mt-0.5">Search TMDB or Anime (Jikan) to instantly add content to your watchlist.</p>
    </x-slot>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="space-y-6">
        {{-- Search Section --}}
        <div class="glass-card p-6">
            <livewire:show-search />
        </div>

        {{-- Manual Entry Fallback (Collapsible) --}}
        <details class="group bg-surface-50 dark:bg-surface-800/50 rounded-xl border border-surface-200/50 dark:border-surface-700/50 overflow-hidden">
            <summary class="flex items-center justify-between p-4 cursor-pointer hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors list-none">
                <div class="flex items-center gap-3 text-surface-600 dark:text-surface-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                    <span class="font-medium">Can't find it? Add manually</span>
                </div>
                <svg class="w-5 h-5 text-surface-400 group-open:rotate-180 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            
            <div class="p-6 pt-2 border-t border-surface-200/50 dark:border-surface-700/50">
                <form action="{{ route('watchlist.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-surface-700 dark:text-surface-300">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required class="input-enhanced" placeholder="e.g. Breaking Bad">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-surface-700 dark:text-surface-300">Type <span class="text-red-500">*</span></label>
                            <select name="type" required class="input-enhanced">
                                <option value="drama">Drama</option>
                                <option value="movie">Movie</option>
                                <option value="anime">Anime</option>
                                <option value="tv_series">TV Series</option>
                                <option value="anime_movie">Anime Movie</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-surface-700 dark:text-surface-300">Status</label>
                            <select name="status" class="input-enhanced">
                                <option value="plan_to_watch">Plan to Watch</option>
                                <option value="watching">Watching</option>
                                <option value="completed">Completed</option>
                                <option value="on_hold">On Hold</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-surface-700 dark:text-surface-300">Poster Image URL</label>
                            <input type="url" name="poster_url" class="input-enhanced" placeholder="https://...">
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-xs font-medium text-surface-700 dark:text-surface-300">Synopsis</label>
                            <textarea name="description" rows="3" class="input-enhanced resize-none" placeholder="Brief description..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="btn-primary py-2 px-6">Save Manual Entry</button>
                    </div>
                </form>
            </div>
        </details>
    </div>
</x-app-layout>
