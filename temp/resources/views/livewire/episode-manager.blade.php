<div class="space-y-4">

    {{-- Flash --}}
    @if(session('success'))
    <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)" x-transition
         class="px-4 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        {{-- Filters --}}
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Status Filter --}}
            <div class="flex gap-1 p-1 bg-surface-100 dark:bg-surface-800/60 rounded-lg border border-surface-200/60 dark:border-surface-700/60">
                @foreach(['all'=>'All','upcoming'=>'Upcoming','aired'=>'Aired'] as $val=>$label)
                <button wire:click="$set('filterStatus','{{ $val }}')"
                        class="px-3 py-1 rounded-md text-xs font-medium transition-all {{ $filterStatus===$val ? 'bg-white dark:bg-surface-700 text-surface-900 dark:text-white shadow-sm' : 'text-surface-500 hover:text-surface-700 dark:hover:text-surface-300' }}">
                    {{ $label }}
                </button>
                @endforeach
            </div>
            {{-- Sort --}}
            <select wire:model.live="sortBy" class="input-enhanced text-xs py-1.5 h-auto w-auto">
                <option value="episode_no">Sort: Episode #</option>
                <option value="air_datetime">Sort: Air Date</option>
            </select>
            {{-- Search --}}
            <div class="relative">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="Search…" class="input-enhanced text-xs py-1.5 pl-8 h-auto w-40">
            </div>
        </div>
        {{-- Action Buttons --}}
        <div class="flex gap-2">
            <button wire:click="$set('showBulkForm', !showBulkForm)"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-surface-100 dark:bg-surface-800 border border-surface-200 dark:border-surface-700 text-surface-600 dark:text-surface-300 hover:bg-surface-200 dark:hover:bg-surface-700 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                Bulk Add
            </button>
            <button wire:click="$set('showAddForm', !showAddForm)"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium btn-primary">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Episode
            </button>
        </div>
    </div>

    {{-- Bulk Add Form --}}
    @if($showBulkForm)
    <div class="p-4 rounded-xl bg-brand-500/5 border border-brand-500/20 space-y-3 animate-fade-in">
        <p class="text-sm font-semibold text-surface-800 dark:text-surface-200">Bulk Add Episodes</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div>
                <label class="text-xs text-surface-500 mb-1 block">From Ep #</label>
                <input wire:model="bulkFrom" type="number" min="1" class="input-enhanced text-sm py-1.5">
            </div>
            <div>
                <label class="text-xs text-surface-500 mb-1 block">To Ep #</label>
                <input wire:model="bulkTo" type="number" min="1" class="input-enhanced text-sm py-1.5">
            </div>
            <div>
                <label class="text-xs text-surface-500 mb-1 block">Season #</label>
                <input wire:model="bulkSeason" type="number" min="1" placeholder="optional" class="input-enhanced text-sm py-1.5">
            </div>
            <div>
                <label class="text-xs text-surface-500 mb-1 block">Interval (days)</label>
                <input wire:model="bulkInterval" type="number" min="1" class="input-enhanced text-sm py-1.5">
            </div>
            <div>
                <label class="text-xs text-surface-500 mb-1 block">First Air Date</label>
                <input wire:model="bulkFirstDate" type="date" class="input-enhanced text-sm py-1.5">
            </div>
            <div>
                <label class="text-xs text-surface-500 mb-1 block">Air Time</label>
                <input wire:model="bulkAirTime" type="time" class="input-enhanced text-sm py-1.5">
            </div>
        </div>
        @error('bulkTo')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
        <div class="flex gap-2">
            <button wire:click="bulkAdd" wire:loading.attr="disabled" class="btn-primary text-sm py-1.5">
                <span wire:loading.remove wire:target="bulkAdd">Add Episodes</span>
                <span wire:loading wire:target="bulkAdd">Adding…</span>
            </button>
            <button wire:click="$set('showBulkForm',false)" class="btn-secondary text-sm py-1.5">Cancel</button>
        </div>
    </div>
    @endif

    {{-- Add Single Episode Form --}}
    @if($showAddForm)
    <div class="p-4 rounded-xl bg-emerald-500/5 border border-emerald-500/20 space-y-3 animate-fade-in">
        <p class="text-sm font-semibold text-surface-800 dark:text-surface-200">Add Episode</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div>
                <label class="text-xs text-surface-500 mb-1 block">Episode # <span class="text-red-400">*</span></label>
                <input wire:model="newEpisodeNo" type="number" min="1" class="input-enhanced text-sm py-1.5">
                @error('newEpisodeNo')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs text-surface-500 mb-1 block">Season #</label>
                <input wire:model="newSeasonNo" type="number" min="1" placeholder="optional" class="input-enhanced text-sm py-1.5">
            </div>
            <div class="sm:col-span-2">
                <label class="text-xs text-surface-500 mb-1 block">Title</label>
                <input wire:model="newTitle" type="text" placeholder="Episode title (optional)" class="input-enhanced text-sm py-1.5">
            </div>
            <div>
                <label class="text-xs text-surface-500 mb-1 block">Air Date</label>
                <input wire:model="newAirDate" type="date" class="input-enhanced text-sm py-1.5">
            </div>
            <div>
                <label class="text-xs text-surface-500 mb-1 block">Air Time</label>
                <input wire:model="newAirTime" type="time" class="input-enhanced text-sm py-1.5">
            </div>
            <div>
                <label class="text-xs text-surface-500 mb-1 block">Duration (min)</label>
                <input wire:model="newDuration" type="number" min="1" placeholder="45" class="input-enhanced text-sm py-1.5">
            </div>
            <div class="flex items-center gap-2 pt-5">
                <input wire:model="newIsAired" type="checkbox" id="new-is-aired" class="w-4 h-4 rounded border-surface-300 text-brand-500">
                <label for="new-is-aired" class="text-xs text-surface-500">Already aired</label>
            </div>
        </div>
        <div class="flex gap-2">
            <button wire:click="addEpisode" wire:loading.attr="disabled" class="btn-primary text-sm py-1.5">
                <span wire:loading.remove wire:target="addEpisode">Add Episode</span>
                <span wire:loading wire:target="addEpisode">Adding…</span>
            </button>
            <button wire:click="$set('showAddForm',false)" class="btn-secondary text-sm py-1.5">Cancel</button>
        </div>
    </div>
    @endif

    {{-- Episodes Table --}}
    @if($episodes->count() > 0)
    <div class="overflow-x-auto rounded-xl border border-surface-200/60 dark:border-surface-700/60">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-surface-50 dark:bg-surface-800/60 border-b border-surface-200/60 dark:border-surface-700/60">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider w-16">Ep</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Title</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider hidden md:table-cell">Air Date</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider hidden sm:table-cell">Duration</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-200/40 dark:divide-surface-700/40">
                @foreach($episodes as $ep)
                <tr class="hover:bg-surface-50/50 dark:hover:bg-surface-800/30 transition-colors group"
                    wire:key="ep-{{ $ep->id }}">

                    @if($editingId === $ep->id)
                    {{-- EDIT ROW --}}
                    <td colspan="6" class="px-4 py-3">
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                            <div>
                                <label class="text-xs text-surface-400 mb-1 block">Ep #</label>
                                <input wire:model="editData.episode_no" type="number" min="1" class="input-enhanced text-sm py-1.5">
                            </div>
                            <div>
                                <label class="text-xs text-surface-400 mb-1 block">Season</label>
                                <input wire:model="editData.season_no" type="number" min="1" placeholder="—" class="input-enhanced text-sm py-1.5">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-xs text-surface-400 mb-1 block">Title</label>
                                <input wire:model="editData.title" type="text" class="input-enhanced text-sm py-1.5">
                            </div>
                            <div>
                                <label class="text-xs text-surface-400 mb-1 block">Duration (min)</label>
                                <input wire:model="editData.duration_minutes" type="number" min="1" class="input-enhanced text-sm py-1.5">
                            </div>
                            <div>
                                <label class="text-xs text-surface-400 mb-1 block">Air Date</label>
                                <input wire:model="editData.air_date" type="date" class="input-enhanced text-sm py-1.5">
                            </div>
                            <div>
                                <label class="text-xs text-surface-400 mb-1 block">Air Time</label>
                                <input wire:model="editData.air_time" type="time" class="input-enhanced text-sm py-1.5">
                            </div>
                            <div class="flex items-center gap-2 pt-4">
                                <input wire:model="editData.is_aired" type="checkbox" class="w-4 h-4 rounded text-brand-500">
                                <span class="text-xs text-surface-500">Aired</span>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-3">
                            <button wire:click="saveEdit" class="btn-primary text-xs py-1.5">Save</button>
                            <button wire:click="cancelEdit" class="btn-secondary text-xs py-1.5">Cancel</button>
                        </div>
                    </td>
                    @else
                    {{-- NORMAL ROW --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1.5">
                            @if($ep->season_no)
                            <span class="text-[10px] text-surface-400 font-medium">S{{ $ep->season_no }}</span>
                            @endif
                            <span class="font-bold text-surface-800 dark:text-surface-200">{{ $ep->episode_no }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 max-w-[180px]">
                        <p class="text-surface-800 dark:text-surface-200 truncate">
                            {{ $ep->title ?: 'Episode ' . $ep->episode_no }}
                        </p>
                        @if($ep->duration_minutes)
                        <p class="text-xs text-surface-400">{{ $ep->duration_minutes }} min</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        @if($ep->air_datetime)
                            <p class="text-surface-700 dark:text-surface-300">
                                {{ $ep->air_datetime->setTimezone($userTimezone)->format('M d, Y') }}
                            </p>
                            <p class="text-xs text-surface-400">
                                {{ $ep->air_datetime->setTimezone($userTimezone)->format('g:i A') }}
                            </p>
                        @else
                            <span class="text-surface-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        @if($ep->duration_minutes)
                        <span class="text-xs text-surface-400">{{ $ep->duration_minutes }}m</span>
                        @else
                        <span class="text-surface-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <button wire:click="toggleAired({{ $ep->id }})"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-all
                                       {{ $ep->is_aired
                                           ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 hover:bg-red-500/15 hover:text-red-500'
                                           : 'bg-surface-200/60 dark:bg-surface-700/60 text-surface-500 dark:text-surface-400 hover:bg-emerald-500/15 hover:text-emerald-600' }}">
                            @if($ep->is_aired)
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                Aired
                            @else
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                Soon
                            @endif
                        </button>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="startEdit({{ $ep->id }})"
                                    class="w-7 h-7 rounded-lg bg-surface-100 dark:bg-surface-700 flex items-center justify-center text-surface-500 hover:text-brand-500 hover:bg-brand-500/10 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                            </button>
                            <button wire:click="deleteEpisode({{ $ep->id }})"
                                    wire:confirm="Delete episode {{ $ep->episode_no }}?"
                                    class="w-7 h-7 rounded-lg bg-surface-100 dark:bg-surface-700 flex items-center justify-center text-surface-500 hover:text-red-500 hover:bg-red-500/10 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            </button>
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Summary row --}}
    @php
        $airedCount    = $episodes->where('is_aired', true)->count();
        $upcomingCount = $episodes->where('is_aired', false)->count();
    @endphp
    <div class="flex items-center gap-4 pt-1 text-xs text-surface-400">
        <span>{{ $episodes->count() }} total</span>
        <span class="text-emerald-500">{{ $airedCount }} aired</span>
        <span class="text-amber-500">{{ $upcomingCount }} upcoming</span>
    </div>

    @else
    <div class="text-center py-10">
        <svg class="w-10 h-10 text-surface-300 dark:text-surface-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z"/>
        </svg>
        <p class="text-sm text-surface-500 mb-1">No episodes yet.</p>
        <p class="text-xs text-surface-400">Use "Add Episode" for single episodes or "Bulk Add" for an entire season.</p>
    </div>
    @endif
</div>
