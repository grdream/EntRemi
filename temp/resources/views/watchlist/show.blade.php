<x-app-layout>
    @section('title', $show->title)
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('watchlist.index') }}" class="w-9 h-9 rounded-xl bg-surface-100 dark:bg-surface-800 flex items-center justify-center text-surface-500 hover:bg-surface-200 dark:hover:bg-surface-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-surface-900 dark:text-white">{{ $show->title }}</h1>
                    <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                        <span class="text-xs px-2 py-0.5 rounded-md font-medium {{ match($show->status) { 'watching'=>'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400','completed'=>'bg-sky-500/15 text-sky-600 dark:text-sky-400','on_hold'=>'bg-amber-500/15 text-amber-600 dark:text-amber-400','dropped'=>'bg-red-500/15 text-red-500',default=>'bg-surface-500/15 text-surface-500' } }}">{{ ucwords(str_replace('_',' ',$show->status)) }}</span>
                        <span class="text-xs text-surface-400">{{ ucwords(str_replace('_',' ',$show->type)) }}</span>
                        @if($show->year)<span class="text-xs text-surface-400">· {{ $show->year }}</span>@endif
                        @if($show->rating)<span class="text-xs text-amber-500">· ★ {{ $show->rating }}</span>@endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('watchlist.edit', $show->slug) }}" class="btn-secondary text-sm py-2">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('watchlist.destroy', $show->slug) }}" onsubmit="return confirm('Remove this show?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="py-2 px-3.5 rounded-xl text-sm font-medium bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white border border-red-500/20 hover:border-transparent transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
    <div x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,3500)" x-transition class="mb-4 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Tab Navigation --}}
    <div x-data="{ tab: '{{ request('tab','overview') }}' }" class="space-y-5">
        <div class="flex gap-1 p-1 bg-surface-100 dark:bg-surface-800/60 rounded-xl border border-surface-200/60 dark:border-surface-700/60 w-fit">
            @foreach(['overview'=>'Overview','episodes'=>'Episodes','schedule'=>'Schedule'] as $key=>$label)
            <button @click="tab='{{ $key }}'" :class="tab==='{{ $key }}' ? 'bg-white dark:bg-surface-700 text-surface-900 dark:text-white shadow-sm' : 'text-surface-500 hover:text-surface-700 dark:hover:text-surface-300'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-1.5">
                @if($key==='episodes')
                <span class="text-xs px-1.5 py-0.5 rounded-md bg-brand-500/10 text-brand-500 font-bold">{{ $episodeCount }}</span>
                @endif
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- OVERVIEW TAB --}}
        <div x-show="tab==='overview'" x-transition class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            {{-- Poster + Quick Stats --}}
            <div class="space-y-4">
                <div class="glass-card p-0 overflow-hidden rounded-2xl">
                    @if($show->poster_url)
                        <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full aspect-[2/3] object-cover">
                    @else
                        <div class="w-full aspect-[2/3] bg-gradient-to-br from-surface-700 to-surface-800 flex flex-col items-center justify-center gap-3">
                            <svg class="w-14 h-14 text-surface-500" fill="none" viewBox="0 0 24 24" stroke-width="0.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z"/></svg>
                            <span class="text-surface-500 text-sm">No Poster</span>
                        </div>
                    @endif
                </div>
                <div class="glass-card p-4 space-y-3">
                    @if($show->rating)<div class="flex items-center justify-between"><span class="text-sm text-surface-500">Rating</span><span class="text-sm font-semibold text-amber-500">★ {{ $show->rating }}</span></div>@endif
                    @if($show->total_episodes)<div class="flex items-center justify-between"><span class="text-sm text-surface-500">Total Eps</span><span class="text-sm font-medium text-surface-800 dark:text-surface-200">{{ $show->total_episodes }}</span></div>@endif
                    <div class="flex items-center justify-between"><span class="text-sm text-surface-500">Tracked Eps</span><span class="text-sm font-medium text-brand-500">{{ $episodeCount }}</span></div>
                    @if($show->country)<div class="flex items-center justify-between"><span class="text-sm text-surface-500">Country</span><span class="text-sm font-medium text-surface-800 dark:text-surface-200">{{ $show->country }}</span></div>@endif
                    @if($show->language)<div class="flex items-center justify-between"><span class="text-sm text-surface-500">Language</span><span class="text-sm font-medium text-surface-800 dark:text-surface-200 capitalize">{{ $show->language }}</span></div>@endif
                    @if($show->genres && count($show->genres)>0)
                    <div><span class="text-sm text-surface-500 block mb-2">Genres</span><div class="flex flex-wrap gap-1.5">@foreach($show->genres as $g)<span class="px-2 py-0.5 rounded-md bg-brand-500/10 text-brand-600 dark:text-brand-400 text-xs font-medium">{{ $g }}</span>@endforeach</div></div>
                    @endif
                    @if($show->tmdb_id || $show->jikan_id)
                    <div class="pt-2 border-t border-surface-200/40 dark:border-surface-700/40 flex gap-3">
                        @if($show->tmdb_id)<a href="https://www.themoviedb.org/{{ $show->type==='movie'?'movie':'tv' }}/{{ $show->tmdb_id }}" target="_blank" class="text-xs text-brand-500 hover:text-brand-600">TMDB ↗</a>@endif
                        @if($show->jikan_id)<a href="https://myanimelist.net/anime/{{ $show->jikan_id }}" target="_blank" class="text-xs text-accent-500 hover:text-accent-600">MAL ↗</a>@endif
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right: Synopsis + Progress + Upcoming --}}
            <div class="lg:col-span-2 space-y-5">
                @if($show->description)
                <div class="glass-card p-5">
                    <h2 class="text-sm font-semibold text-surface-900 dark:text-white mb-3">Synopsis</h2>
                    <p class="text-sm text-surface-600 dark:text-surface-400 leading-relaxed">{{ $show->description }}</p>
                </div>
                @endif

                {{-- Active Schedule Banner --}}
                @if($activeSchedule)
                <div class="glass-card p-4 flex items-center gap-4 border border-brand-500/20">
                    <div class="w-10 h-10 rounded-xl bg-brand-500/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-surface-800 dark:text-surface-200">{{ $activeSchedule->summaryLabel() }}</p>
                        <p class="text-xs text-surface-400">Active schedule · {{ $activeSchedule->timezone }}</p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                </div>
                @endif

                {{-- Episode Progress --}}
                <div class="glass-card p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-surface-900 dark:text-white">Progress</h2>
                        <span class="text-xs text-surface-400">{{ $airedCount }} / {{ $episodeCount ?: ($show->total_episodes ?? '?') }}</span>
                    </div>
                    @php $total = $episodeCount ?: ($show->total_episodes ?? 0); $pct = $total>0 ? min(100,round($airedCount/$total*100)) : 0; @endphp
                    <div class="h-2.5 bg-surface-200 dark:bg-surface-700 rounded-full overflow-hidden mb-2">
                        <div class="h-full bg-gradient-to-r from-brand-500 to-accent-500 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                    </div>
                    <p class="text-xs text-surface-400">{{ $pct }}% complete</p>
                </div>

                {{-- Upcoming Episodes --}}
                <div class="glass-card p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-surface-900 dark:text-white">Upcoming Episodes</h2>
                        <button @click="tab='episodes'" class="text-xs text-brand-500 hover:text-brand-600">Manage all →</button>
                    </div>
                    @if($upcomingEpisodes->count() > 0)
                    <div class="space-y-2">
                        @foreach($upcomingEpisodes as $ep)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-surface-50 dark:bg-surface-800/50 border border-surface-200/50 dark:border-surface-700/40">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-brand-500/10 flex items-center justify-center"><span class="text-xs font-bold text-brand-500">{{ $ep->episode_no }}</span></div>
                                <div>
                                    <p class="text-sm font-medium text-surface-800 dark:text-surface-200">{{ $ep->title ?? 'Episode '.$ep->episode_no }}</p>
                                    @if($ep->air_datetime)<p class="text-xs text-surface-400">{{ $ep->air_datetime->setTimezone(auth()->user()->timezone??'UTC')->format('D, M d · g:i A') }}</p>@endif
                                </div>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-600 dark:text-amber-400">Soon</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-surface-400 text-center py-4">No upcoming episodes. Add some in the Episodes tab.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- EPISODES TAB --}}
        <div x-show="tab==='episodes'" x-transition class="glass-card p-5">
            <livewire:episode-manager :show="$show" :timezone="auth()->user()->timezone ?? 'UTC'" />
        </div>

        {{-- SCHEDULE TAB --}}
        <div x-show="tab==='schedule'" x-transition class="space-y-5">
            {{-- Current Schedule --}}
            @if($allSchedules->count() > 0)
            <div class="glass-card p-5">
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white mb-4">Schedule History</h2>
                <div class="space-y-2">
                    @foreach($allSchedules as $sched)
                    <div class="flex items-center justify-between p-3 rounded-xl {{ $sched->is_active ? 'bg-brand-500/5 border border-brand-500/20' : 'bg-surface-50 dark:bg-surface-800/40 border border-surface-200/50 dark:border-surface-700/40' }}">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full {{ $sched->is_active ? 'bg-emerald-500' : 'bg-surface-400' }}"></div>
                            <div>
                                <p class="text-sm font-medium text-surface-800 dark:text-surface-200">{{ $sched->summaryLabel() }}</p>
                                <p class="text-xs text-surface-400">{{ $sched->timezone }} · From {{ $sched->start_date->format('M d, Y') }}{{ $sched->end_date ? ' to '.$sched->end_date->format('M d, Y') : '' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($sched->is_active)
                            <form method="POST" action="{{ route('schedule.deactivate', $show->slug) }}">@csrf
                                <button type="submit" class="text-xs px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-600 hover:bg-amber-500/20 transition-colors">Pause</button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('schedule.destroy', [$show->slug, $sched->id]) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs px-2.5 py-1 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors">Delete</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- New Schedule Form --}}
            <div class="glass-card p-5" x-data="scheduleForm()">
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white mb-5">
                    {{ $allSchedules->count() > 0 ? 'Set New Schedule' : 'Create Schedule' }}
                </h2>
                <form method="POST" action="{{ route('schedule.upsert', $show->slug) }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Pattern <span class="text-red-400">*</span></label>
                            <select name="pattern" x-model="pattern" class="input-enhanced" id="sched-pattern">
                                @foreach(['daily'=>'Daily','weekly'=>'Weekly','bi_weekly'=>'Bi-Weekly','twice_per_week'=>'Twice Per Week','monthly'=>'Monthly','irregular'=>'Irregular / Manual','movie_one_time'=>'One-Time (Movie)'] as $v=>$l)
                                <option value="{{ $v }}" {{ old('pattern')===$v?'selected':'' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Air Time <span class="text-red-400">*</span></label>
                            <input name="air_time" type="time" class="input-enhanced" value="{{ old('air_time','20:00') }}" id="sched-air-time">
                        </div>

                        {{-- Days of week — shown for relevant patterns --}}
                        <div class="sm:col-span-2" x-show="['weekly','bi_weekly','twice_per_week'].includes(pattern)" x-transition>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-2">Days of Week</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                                <label class="flex items-center gap-1.5 cursor-pointer group">
                                    <input type="checkbox" name="days_of_week[]" value="{{ $day }}"
                                           class="w-4 h-4 rounded border-surface-300 text-brand-500 cursor-pointer"
                                           {{ is_array(old('days_of_week')) && in_array($day, old('days_of_week')) ? 'checked' : '' }}>
                                    <span class="text-sm text-surface-600 dark:text-surface-400 capitalize group-hover:text-surface-800 dark:group-hover:text-surface-200 transition-colors">{{ ucfirst($day) }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Timezone <span class="text-red-400">*</span></label>
                            <select name="timezone" class="input-enhanced" id="sched-timezone">
                                @foreach(\DateTimeZone::listIdentifiers() as $tz)
                                <option value="{{ $tz }}" {{ (old('timezone', auth()->user()->timezone??'UTC')===$tz)?'selected':'' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Episodes per Slot</label>
                            <input name="episodes_per_slot" type="number" min="1" max="10" class="input-enhanced" value="{{ old('episodes_per_slot',1) }}" id="sched-eps-per-slot">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Start Date <span class="text-red-400">*</span></label>
                            <input name="start_date" type="date" class="input-enhanced" value="{{ old('start_date', now()->format('Y-m-d')) }}" id="sched-start-date">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">End Date <span class="text-surface-400 font-normal">(optional)</span></label>
                            <input name="end_date" type="date" class="input-enhanced" value="{{ old('end_date') }}" id="sched-end-date">
                        </div>
                    </div>

                    @if($errors->has('pattern') || $errors->has('air_time') || $errors->has('timezone') || $errors->has('start_date'))
                    <div class="px-3 py-2.5 rounded-lg bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 text-xs space-y-0.5">
                        @foreach(['pattern','air_time','timezone','start_date','end_date','days_of_week'] as $f)
                        @error($f)<p>{{ $message }}</p>@enderror
                        @endforeach
                    </div>
                    @endif

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn-primary" id="save-schedule-btn">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"/></svg>
                            Save Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function scheduleForm() {
        return { pattern: '{{ old('pattern','weekly') }}' };
    }
    </script>
    @endpush
</x-app-layout>
