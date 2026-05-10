{{-- Top Navigation Bar --}}
<header class="sticky top-0 z-30 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8
               bg-white/80 dark:bg-surface-900/80 backdrop-blur-xl
               border-b border-surface-200/50 dark:border-surface-800/50">

    {{-- Left: Mobile Menu + Search --}}
    <div class="flex items-center gap-3">
        {{-- Mobile Menu Toggle --}}
        <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                class="lg:hidden p-2 rounded-xl text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-200 hover:bg-surface-100 dark:hover:bg-surface-800 transition-all duration-200"
                id="mobile-menu-toggle">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        {{-- Desktop: Expand sidebar button (when collapsed) --}}
        <button @click="sidebarOpen = true"
                x-show="!sidebarOpen"
                class="hidden lg:flex p-2 rounded-xl text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-200 hover:bg-surface-100 dark:hover:bg-surface-800 transition-all duration-200"
                title="Expand sidebar">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        {{-- Search Bar --}}
        <div class="hidden sm:flex items-center relative">
            <svg class="absolute left-3 w-4 h-4 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text"
                   placeholder="Search shows, episodes..."
                   class="w-64 lg:w-80 pl-10 pr-4 py-2 rounded-xl text-sm
                          bg-surface-100/80 dark:bg-surface-800/50
                          border border-surface-200/50 dark:border-surface-700/50
                          text-surface-700 dark:text-surface-300
                          placeholder-surface-400 dark:placeholder-surface-500
                          focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500/50
                          transition-all duration-200"
                   id="global-search">
        </div>
    </div>

    {{-- Right: Actions --}}
    <div class="flex items-center gap-2">
        {{-- Quick Add Button --}}
        <a href="{{ route('watchlist.create') }}"
                class="hidden sm:flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium
                       bg-gradient-to-r from-brand-500 to-brand-600
                       text-white shadow-lg shadow-brand-500/20
                       hover:shadow-brand-500/40 hover:from-brand-600 hover:to-brand-700
                       transition-all duration-200 hover:-translate-y-0.5"
                id="quick-add-btn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Add Show</span>
        </a>

        {{-- Notifications --}}
        <livewire:notification-bell />

        {{-- Dark Mode Toggle --}}
        <button @click="darkMode = !darkMode"
                class="p-2.5 rounded-xl text-surface-500 hover:text-surface-700 dark:text-surface-400 dark:hover:text-surface-200 hover:bg-surface-100 dark:hover:bg-surface-800 transition-all duration-200"
                id="dark-mode-toggle"
                :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'">
            {{-- Sun Icon (shown in dark mode) --}}
            <svg x-show="darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
            </svg>
            {{-- Moon Icon (shown in light mode) --}}
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
            </svg>
        </button>

        {{-- User Dropdown --}}
        <div x-data="{ userDropdown: false }" class="relative">
            <button @click="userDropdown = !userDropdown"
                    @click.outside="userDropdown = false"
                    class="flex items-center gap-2 p-1.5 pr-3 rounded-xl hover:bg-surface-100 dark:hover:bg-surface-800 transition-all duration-200"
                    id="user-dropdown-toggle">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                         class="w-8 h-8 rounded-lg object-cover" alt="avatar">
                @else
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-400 to-accent-500 flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <span class="hidden md:block text-sm font-medium text-surface-700 dark:text-surface-300 max-w-[100px] truncate">
                    {{ Auth::user()->name }}
                </span>
                <svg class="w-4 h-4 text-surface-400 transition-transform duration-200" :class="{ 'rotate-180': userDropdown }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="userDropdown"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                 class="absolute right-0 mt-2 w-56 py-2
                        bg-white dark:bg-surface-800 rounded-xl
                        border border-surface-200/70 dark:border-surface-700/70
                        shadow-xl shadow-surface-900/10 dark:shadow-surface-950/40"
                 style="display: none;">

                {{-- User Info --}}
                <div class="px-4 py-3 border-b border-surface-100 dark:border-surface-700">
                    <p class="text-sm font-semibold text-surface-900 dark:text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-surface-500 dark:text-surface-400 truncate">{{ Auth::user()->email }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-surface-600 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors" id="profile-link">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Profile
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-surface-600 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors" id="settings-link">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    Settings
                </a>

                <div class="my-1 border-t border-surface-100 dark:border-surface-700"></div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" id="logout-btn">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
