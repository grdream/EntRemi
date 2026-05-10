{{-- Sidebar Navigation --}}
<aside
    :class="{
        'translate-x-0': mobileSidebarOpen,
        '-translate-x-full lg:translate-x-0': !mobileSidebarOpen,
        'lg:w-[272px]': sidebarOpen,
        'lg:w-[72px]': !sidebarOpen,
    }"
    class="fixed inset-y-0 left-0 z-50 w-[272px] flex flex-col transition-all duration-300 ease-in-out
           bg-white/90 dark:bg-surface-900/95 backdrop-blur-xl
           border-r border-surface-200/70 dark:border-surface-800/70"
>
    {{-- Logo & Toggle --}}
    <div class="flex items-center justify-between h-16 px-4 border-b border-surface-200/50 dark:border-surface-800/50">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group" :class="{ 'justify-center w-full': !sidebarOpen }">
            {{-- Logo Icon --}}
            <div class="relative flex-shrink-0">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-accent-500 flex items-center justify-center shadow-glow-sm group-hover:shadow-glow transition-shadow duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                    </svg>
                </div>
                {{-- Pulse dot --}}
                <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white dark:border-surface-900 animate-pulse-slow"></span>
            </div>
            {{-- Logo Text --}}
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="overflow-hidden">
                <h1 class="text-base font-bold text-surface-900 dark:text-white tracking-tight">WatchList</h1>
                <p class="text-[10px] font-medium text-brand-500 dark:text-brand-400 uppercase tracking-widest -mt-0.5">Reminder</p>
            </div>
        </a>

        {{-- Collapse Toggle (Desktop) --}}
        <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex items-center justify-center w-7 h-7 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-800 text-surface-400 hover:text-surface-600 dark:hover:text-surface-300 transition-all duration-200" x-show="sidebarOpen" title="Collapse sidebar">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
            </svg>
        </button>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        {{-- Main --}}
        <p x-show="sidebarOpen" class="px-3 mb-2 text-[11px] font-semibold text-surface-400 dark:text-surface-500 uppercase tracking-wider">Main</p>

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="Dashboard">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span x-show="sidebarOpen" class="truncate">Dashboard</span>
        </a>

        {{-- My Watchlist --}}
        <a href="{{ route('watchlist.index') }}"
           class="{{ request()->routeIs('watchlist.*') && !request()->routeIs('watchlist.create') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="My Watchlist">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0 1 18 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 0 1 6 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M19.125 12h1.5m0 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h1.5m14.25 0h1.5" />
            </svg>
            <span x-show="sidebarOpen" class="truncate">My Watchlist</span>
        </a>

        {{-- Add Content --}}
        <a href="{{ route('watchlist.create') }}"
           class="{{ request()->routeIs('watchlist.create') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="Add Content">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span x-show="sidebarOpen" class="truncate">Add Content</span>
        </a>

        {{-- Upcoming Episodes --}}
        <a href="{{ route('upcoming.index') }}"
           class="{{ request()->routeIs('upcoming.*') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="Upcoming Episodes">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
            <span x-show="sidebarOpen" class="truncate">Upcoming</span>
        </a>

        {{-- My Notification History --}}
        <a href="{{ route('notifications.history') }}"
           class="{{ request()->routeIs('notifications.history') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="My Notification History">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
            </svg>
            <span x-show="sidebarOpen" class="truncate">My Notifications</span>
        </a>

        {{-- Separator --}}
        <div class="!my-4 border-t border-surface-200/50 dark:border-surface-800/50"></div>
        <p x-show="sidebarOpen" class="px-3 mb-2 text-[11px] font-semibold text-surface-400 dark:text-surface-500 uppercase tracking-wider">System</p>

        @if(auth()->check() && auth()->user()->isAdmin())
        {{-- Admin Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="Admin Dashboard">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
            </svg>
            <span x-show="sidebarOpen" class="truncate">Admin Console</span>
        </a>
        {{-- User Management --}}
        <a href="{{ route('admin.users') }}"
           class="{{ request()->routeIs('admin.users*') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="User Management">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            <span x-show="sidebarOpen" class="truncate">Users</span>
        </a>
        {{-- Notification Logs --}}
        <a href="{{ route('admin.notifications') }}"
           class="{{ request()->routeIs('admin.notifications*') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="Notification Logs">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5" />
            </svg>
            <span x-show="sidebarOpen" class="truncate">Notif Logs</span>
        </a>
        {{-- System Settings --}}
        <a href="{{ route('admin.settings') }}"
           class="{{ request()->routeIs('admin.settings*') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="System Settings">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span x-show="sidebarOpen" class="truncate">System Settings</span>
        </a>
        @endif

        {{-- Import Tools --}}
        <a href="{{ route('tools.import.index') }}"
           class="{{ request()->routeIs('tools.import.*') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="Import Data">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <span x-show="sidebarOpen" class="truncate">Import Data</span>
        </a>

        {{-- Notifications --}}
        <a href="{{ route('settings.notifications') }}"
           class="{{ request()->routeIs('settings.*') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="Notification Settings">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            <span x-show="sidebarOpen" class="truncate">Notifications</span>
        </a>

        {{-- Settings --}}
        <a href="{{ route('profile.edit') }}"
           class="{{ request()->routeIs('profile.*') ? 'nav-item-active' : 'nav-item' }}"
           :class="{ 'justify-center': !sidebarOpen }"
           title="Account Settings">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span x-show="sidebarOpen" class="truncate">Settings</span>
        </a>
    </nav>

    {{-- User Section --}}
    <div class="p-3 border-t border-surface-200/50 dark:border-surface-800/50">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-all duration-200 group"
           :class="{ 'justify-center': !sidebarOpen }">
            {{-- Avatar --}}
            <div class="relative flex-shrink-0">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                         class="w-9 h-9 rounded-full object-cover shadow-lg" alt="avatar">
                @else
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-400 to-accent-500 flex items-center justify-center text-white text-sm font-bold shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white dark:border-surface-900"></span>
            </div>
            {{-- User Info --}}
            <div x-show="sidebarOpen" class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-surface-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-surface-500 dark:text-surface-400 truncate">{{ Auth::user()->email }}</p>
            </div>
            {{-- Chevron --}}
            <svg x-show="sidebarOpen" class="w-4 h-4 text-surface-400 group-hover:text-surface-600 dark:group-hover:text-surface-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </a>
    </div>
</aside>
