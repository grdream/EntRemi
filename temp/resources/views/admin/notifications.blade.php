<x-app-layout>
    @section('title', 'Admin Console — Notification Logs')

    <x-slot name="header">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-purple-500/10 dark:bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-surface-900 dark:text-white">Notification Logs</h1>
                    <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Enterprise observability dashboard · Admin view</p>
                </div>
            </div>
            {{-- Export CSV --}}
            <a href="{{ route('admin.notifications.export', request()->only(['status','channel','date'])) }}"
               class="btn-secondary text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Export CSV
            </a>
        </div>
    </x-slot>

    {{-- Summary Stats Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 mb-6">
        {{-- Today --}}
        <div class="sm:col-span-2 lg:col-span-1">
            <p class="text-[10px] font-semibold text-surface-400 uppercase tracking-wider mb-1">Today Total</p>
            <p class="text-2xl font-bold text-surface-800 dark:text-white">{{ $todayStats['total'] }}</p>
        </div>
        <div>
            <p class="text-[10px] font-semibold text-emerald-500 uppercase tracking-wider mb-1">Sent Today</p>
            <p class="text-2xl font-bold text-emerald-500">{{ $todayStats['sent'] }}</p>
        </div>
        <div>
            <p class="text-[10px] font-semibold text-red-400 uppercase tracking-wider mb-1">Failed Today</p>
            <p class="text-2xl font-bold text-red-400">{{ $todayStats['failed'] }}</p>
        </div>
        <div>
            <p class="text-[10px] font-semibold text-amber-400 uppercase tracking-wider mb-1">Pending Today</p>
            <p class="text-2xl font-bold text-amber-400">{{ $todayStats['pending'] }}</p>
        </div>
        <div class="border-l border-surface-200/50 dark:border-surface-700/50 pl-3 sm:col-span-1">
            <p class="text-[10px] font-semibold text-surface-400 uppercase tracking-wider mb-1">All Time</p>
            <p class="text-2xl font-bold text-surface-800 dark:text-white">{{ $allTimeStats['total'] }}</p>
        </div>
        <div>
            <p class="text-[10px] font-semibold text-brand-500 uppercase tracking-wider mb-1">Total Sent</p>
            <p class="text-2xl font-bold text-brand-500">{{ $allTimeStats['sent'] }}</p>
        </div>
        <div>
            <p class="text-[10px] font-semibold text-surface-400 uppercase tracking-wider mb-1">Via Email</p>
            <p class="text-2xl font-bold text-surface-600 dark:text-surface-300">{{ $allTimeStats['email'] }}</p>
        </div>
        <div>
            <p class="text-[10px] font-semibold text-surface-400 uppercase tracking-wider mb-1">Via SMS</p>
            <p class="text-2xl font-bold text-surface-600 dark:text-surface-300">{{ $allTimeStats['sms'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="glass-card p-4 mb-5">
        <form method="GET" action="{{ route('admin.notifications') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[120px]">
                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1">Status</label>
                <select name="status" class="input-enhanced" id="filter-status">
                    <option value="">All Statuses</option>
                    <option value="sent"    {{ request('status')==='sent'    ? 'selected' : '' }}>Sent</option>
                    <option value="failed"  {{ request('status')==='failed'  ? 'selected' : '' }}>Failed</option>
                    <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="flex-1 min-w-[120px]">
                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1">Channel</label>
                <select name="channel" class="input-enhanced" id="filter-channel">
                    <option value="">All Channels</option>
                    <option value="email" {{ request('channel')==='email' ? 'selected' : '' }}>Email</option>
                    <option value="sms"   {{ request('channel')==='sms'   ? 'selected' : '' }}>SMS</option>
                </select>
            </div>
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1">Date</label>
                <input type="date" name="date" class="input-enhanced" value="{{ request('date') }}" id="filter-date">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary py-2 px-4" id="filter-apply-btn">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/></svg>
                    Filter
                </button>
                @if(request()->hasAny(['status','channel','date']))
                <a href="{{ route('admin.notifications') }}" class="btn-secondary py-2 px-4">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Logs Table --}}
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-surface-100/50 dark:bg-surface-800/50 text-surface-500 dark:text-surface-400 font-medium border-b border-surface-200/50 dark:border-surface-700/50">
                    <tr>
                        <th class="px-4 py-3 text-xs">ID</th>
                        <th class="px-4 py-3 text-xs">User</th>
                        <th class="px-4 py-3 text-xs">Channel</th>
                        <th class="px-4 py-3 text-xs">Show / Episode</th>
                        <th class="px-4 py-3 text-xs">Status</th>
                        <th class="px-4 py-3 max-w-xs text-xs">Message / Error</th>
                        <th class="px-4 py-3 text-xs">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-200/50 dark:divide-surface-700/50 text-surface-700 dark:text-surface-300">
                    @forelse($logs as $log)
                    <tr class="hover:bg-surface-50/50 dark:hover:bg-surface-800/30 transition-colors">
                        <td class="px-4 py-3 text-xs text-surface-400">#{{ $log->id }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-surface-900 dark:text-white text-xs">{{ $log->user->name ?? 'N/A' }}</div>
                            <div class="text-[10px] text-surface-500">{{ $log->user->email ?? '' }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($log->channel === 'email')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-semibold uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                Email
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-purple-500/10 text-purple-600 dark:text-purple-400 text-[10px] font-semibold uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 4.5h3M3 16.5h.008v.008H3v-.008Zm3.75 0h.008v.008H6.75v-.008Z"/></svg>
                                SMS
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($log->show)
                                <a href="{{ route('watchlist.show', $log->show->slug) }}" class="text-xs font-medium text-brand-600 dark:text-brand-400 hover:underline">
                                    {{ Str::limit($log->show->title, 28) }}
                                </a>
                            @else
                                <span class="text-xs text-surface-400">N/A</span>
                            @endif
                            @if($log->episode)
                                <div class="text-[10px] text-surface-500 mt-0.5">S{{ $log->episode->season_no ?? '?' }} E{{ $log->episode->episode_no }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($log->status === 'sent')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>Sent
                                </span>
                            @elseif($log->status === 'failed')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-600 dark:text-red-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>Failed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span>Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 max-w-xs truncate" title="{{ $log->error_message ?: $log->message }}">
                            @if($log->status === 'failed')
                                <span class="text-xs text-red-500">{{ Str::limit($log->error_message, 45) }}</span>
                            @else
                                <span class="text-xs text-surface-500">{{ Str::limit($log->message, 45) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-surface-500">
                            @if($log->sent_at)
                                <span title="{{ $log->sent_at->toRfc850String() }}">{{ $log->sent_at->diffForHumans() }}</span>
                            @else
                                <span title="{{ $log->created_at->toRfc850String() }}">{{ $log->created_at->diffForHumans() }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <svg class="w-10 h-10 text-surface-300 dark:text-surface-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
                            <p class="text-surface-500 dark:text-surface-400 text-sm">No notification logs found matching your filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</x-app-layout>
