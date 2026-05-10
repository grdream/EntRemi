<x-app-layout>
    @section('title', 'My Notification History')

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-brand-500/10 dark:bg-brand-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-surface-900 dark:text-white">My Notification History</h1>
                <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">All reminders sent to you across email and SMS.</p>
            </div>
        </div>
    </x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="glass-card p-4 text-center">
            <p class="text-2xl font-bold text-surface-800 dark:text-white">{{ $stats['total'] }}</p>
            <p class="text-xs text-surface-400 mt-1">Total Sent</p>
        </div>
        <div class="glass-card p-4 text-center">
            <p class="text-2xl font-bold text-emerald-500">{{ $stats['sent'] }}</p>
            <p class="text-xs text-surface-400 mt-1">Delivered</p>
        </div>
        <div class="glass-card p-4 text-center">
            <p class="text-2xl font-bold text-red-400">{{ $stats['failed'] }}</p>
            <p class="text-xs text-surface-400 mt-1">Failed</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="glass-card p-4 mb-5">
        <form method="GET" action="{{ route('notifications.history') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[130px]">
                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1">Channel</label>
                <select name="channel" class="input-enhanced" id="hist-filter-channel">
                    <option value="">All Channels</option>
                    <option value="email" {{ request('channel')==='email' ? 'selected' : '' }}>Email</option>
                    <option value="sms"   {{ request('channel')==='sms'   ? 'selected' : '' }}>SMS</option>
                </select>
            </div>
            <div class="flex-1 min-w-[130px]">
                <label class="block text-xs font-medium text-surface-500 dark:text-surface-400 mb-1">Status</label>
                <select name="status" class="input-enhanced" id="hist-filter-status">
                    <option value="">All Statuses</option>
                    <option value="sent"    {{ request('status')==='sent'    ? 'selected' : '' }}>Sent</option>
                    <option value="failed"  {{ request('status')==='failed'  ? 'selected' : '' }}>Failed</option>
                    <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary py-2 px-4" id="hist-filter-btn">Filter</button>
                @if(request()->hasAny(['channel','status']))
                <a href="{{ route('notifications.history') }}" class="btn-secondary py-2 px-4">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Log List --}}
    @if($logs->count() > 0)
    <div class="space-y-2">
        @foreach($logs as $log)
        <div class="glass-card p-4 flex items-center gap-4 hover:shadow-md transition-all group">
            {{-- Channel icon --}}
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $log->channel === 'email' ? 'bg-blue-500/10' : 'bg-purple-500/10' }}">
                @if($log->channel === 'email')
                <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                @else
                <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 4.5h3"/></svg>
                @endif
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    @if($log->show)
                    <a href="{{ route('watchlist.show', $log->show->slug) }}" class="text-sm font-semibold text-surface-900 dark:text-white hover:text-brand-500 transition-colors">
                        {{ Str::limit($log->show->title, 35) }}
                    </a>
                    @else
                    <span class="text-sm font-semibold text-surface-900 dark:text-white">System Notification</span>
                    @endif
                    @if($log->episode)
                    <span class="text-xs px-1.5 py-0.5 rounded bg-surface-200/60 dark:bg-surface-700/60 text-surface-500">
                        S{{ $log->episode->season_no ?? '?' }}E{{ $log->episode->episode_no }}
                    </span>
                    @endif
                </div>
                <p class="text-xs text-surface-500 mt-0.5 truncate">{{ $log->message ?? $log->error_message ?? 'No message.' }}</p>
            </div>

            {{-- Status + Time --}}
            <div class="text-right flex-shrink-0 space-y-1">
                @if($log->status === 'sent')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Sent
                    </span>
                @elseif($log->status === 'failed')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-red-500/10 text-red-500">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Failed
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/10 text-amber-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Pending
                    </span>
                @endif
                <p class="text-xs text-surface-400">{{ ($log->sent_at ?? $log->created_at)->diffForHumans() }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
    @else
    <div class="flex flex-col items-center justify-center py-20 px-6 text-center glass-card">
        <div class="w-16 h-16 rounded-2xl bg-surface-100 dark:bg-surface-800 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-surface-900 dark:text-white mb-2">No notifications yet</h3>
        <p class="text-sm text-surface-500 dark:text-surface-400 max-w-sm">
            Once reminders start sending, they'll appear here. Make sure your SMTP or SMS gateway is configured.
        </p>
        <a href="{{ route('profile.edit') }}?tab=gateways" class="btn-primary mt-6">Configure Gateways</a>
    </div>
    @endif
</x-app-layout>
