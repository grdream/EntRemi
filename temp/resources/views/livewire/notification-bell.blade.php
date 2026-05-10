<div class="relative" x-data="{ open: @entangle('open') }" @click.outside="open = false; $wire.close()">
    {{-- Bell Button --}}
    <button wire:click="toggle"
            class="relative p-2.5 rounded-xl text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-200 hover:bg-surface-100 dark:hover:bg-surface-800 transition-all duration-200"
            id="notifications-btn">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>
        @if($unreadCount > 0)
        <span class="absolute top-1 right-1 min-w-[18px] h-[18px] flex items-center justify-center rounded-full bg-accent-500 text-white text-[10px] font-bold px-1 animate-pulse-slow">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
        @else
        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-emerald-400"></span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
         class="absolute right-0 mt-2 w-80 rounded-2xl overflow-hidden
                bg-white dark:bg-surface-800
                border border-surface-200/70 dark:border-surface-700/70
                shadow-xl shadow-surface-900/10 dark:shadow-surface-950/40 z-50"
         style="display:none;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-surface-100 dark:border-surface-700">
            <p class="text-sm font-semibold text-surface-900 dark:text-white">Notifications</p>
            @if($unreadCount > 0)
            <button wire:click="markRead" class="text-xs text-brand-500 hover:text-brand-400 transition-colors">Mark all read</button>
            @endif
        </div>

        {{-- List --}}
        <div class="max-h-72 overflow-y-auto divide-y divide-surface-100 dark:divide-surface-700/60">
            @forelse($recent as $notif)
            <div class="flex items-start gap-3 px-4 py-3 {{ $notif['is_new'] ? 'bg-brand-500/5' : '' }} hover:bg-surface-50 dark:hover:bg-surface-700/40 transition-colors">
                {{-- Channel icon --}}
                <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center mt-0.5
                            {{ $notif['channel'] === 'email' ? 'bg-brand-500/10' : 'bg-accent-500/10' }}">
                    @if($notif['channel'] === 'email')
                    <svg class="w-3.5 h-3.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    @else
                    <svg class="w-3.5 h-3.5 text-accent-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-surface-800 dark:text-surface-200 font-medium truncate">{{ $notif['show_title'] }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-[10px] uppercase font-semibold {{ $notif['status'] === 'sent' ? 'text-emerald-500' : 'text-red-400' }}">
                            {{ $notif['status'] }}
                        </span>
                        <span class="text-[10px] text-surface-400">{{ $notif['time'] }}</span>
                    </div>
                </div>
                @if($notif['is_new'])
                <span class="w-2 h-2 rounded-full bg-brand-500 flex-shrink-0 mt-1.5"></span>
                @endif
            </div>
            @empty
            <div class="px-4 py-8 text-center">
                <svg class="w-8 h-8 text-surface-300 dark:text-surface-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
                <p class="text-sm text-surface-400">No notifications yet.</p>
            </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="px-4 py-3 border-t border-surface-100 dark:border-surface-700">
            <a href="{{ route('notifications.history') }}"
               class="block text-center text-xs text-brand-500 hover:text-brand-400 font-medium transition-colors">
                View all notifications →
            </a>
        </div>
    </div>
</div>
