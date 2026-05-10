<x-app-layout>
    @section('title', 'Admin Dashboard')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-surface-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-accent-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25"/></svg>
                    </span>
                    Admin Dashboard
                </h1>
                <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">System overview — {{ now()->format('l, F j Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users') }}" class="btn-secondary text-sm">Manage Users</a>
                <a href="{{ route('admin.settings') }}" class="btn-primary text-sm">System Settings</a>
            </div>
        </div>
    </x-slot>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        @foreach([
            ['label' => 'Total Users',    'value' => $stats['total_users'],   'color' => 'from-brand-500 to-brand-600',   'icon' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z'],
            ['label' => 'Premium',        'value' => $stats['premium_users'], 'color' => 'from-amber-500 to-orange-500',  'icon' => 'M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z'],
            ['label' => 'Free',           'value' => $stats['free_users'],    'color' => 'from-surface-500 to-surface-600','icon' => 'M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'],
            ['label' => "Today's Notifs", 'value' => $stats['notifs_today'],  'color' => 'from-emerald-500 to-teal-500',  'icon' => 'M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0'],
            ['label' => 'Total Shows',    'value' => $stats['total_shows'],   'color' => 'from-violet-500 to-purple-600', 'icon' => 'M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5'],
        ] as $card)
        <div class="glass-card p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-surface-500 dark:text-surface-400 font-medium">{{ $card['label'] }}</p>
                    <p class="text-2xl font-bold text-surface-900 dark:text-white mt-1">{{ number_format($card['value']) }}</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br {{ $card['color'] }} flex items-center justify-center shadow-lg flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Today's Notification Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="glass-card p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
            </div>
            <div>
                <p class="text-xs text-surface-500 dark:text-surface-400">Sent Today</p>
                <p class="text-xl font-bold text-emerald-500">{{ $stats['notifs_sent'] }}</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <p class="text-xs text-surface-500 dark:text-surface-400">Failed Today</p>
                <p class="text-xl font-bold text-red-500">{{ $stats['notifs_failed'] }}</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-brand-500/10 border border-brand-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
            </div>
            <div>
                <p class="text-xs text-surface-500 dark:text-surface-400">All Time Sent</p>
                <p class="text-xl font-bold text-brand-500">{{ number_format($stats['total_notifs']) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Users --}}
        <div class="glass-card p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white">Recent Sign-ups</h2>
                <a href="{{ route('admin.users') }}" class="text-xs text-brand-500 hover:text-brand-400 transition-colors">View all →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-accent-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-surface-900 dark:text-white truncate">{{ $user->name }}</p>
                        <p class="text-xs text-surface-500 dark:text-surface-400 truncate">{{ $user->email }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $user->isPremium() ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20' : 'bg-surface-200/60 dark:bg-surface-700/60 text-surface-500 dark:text-surface-400' }}">
                            {{ $user->planLabel() }}
                        </span>
                        <span class="text-[10px] text-surface-400">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-surface-400 text-center py-4">No users yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Notifications --}}
        <div class="glass-card p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white">Recent Notifications</h2>
                <a href="{{ route('admin.notifications') }}" class="text-xs text-brand-500 hover:text-brand-400 transition-colors">View all →</a>
            </div>
            <div class="space-y-2.5">
                @forelse($recentNotifs as $log)
                <div class="flex items-center gap-3 text-sm">
                    <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $log->status === 'sent' ? 'bg-emerald-400' : ($log->status === 'failed' ? 'bg-red-400' : 'bg-amber-400') }}"></span>
                    <span class="flex-1 truncate text-surface-700 dark:text-surface-300">
                        <span class="font-medium">{{ $log->user->name ?? 'Unknown' }}</span>
                        <span class="text-surface-500"> · {{ $log->show->title ?? 'N/A' }}</span>
                    </span>
                    <span class="text-[10px] uppercase font-semibold px-1.5 py-0.5 rounded {{ $log->channel === 'email' ? 'bg-brand-500/10 text-brand-500' : 'bg-accent-500/10 text-accent-500' }}">{{ $log->channel }}</span>
                    <span class="text-[10px] text-surface-400 flex-shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-sm text-surface-400 text-center py-4">No notifications logged yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
