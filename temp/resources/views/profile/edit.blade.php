<x-app-layout>
    @section('title', 'Profile Settings')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-500/10 dark:bg-brand-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-surface-900 dark:text-white">Profile Settings</h1>
                    <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Manage your account, notifications and security.</p>
                </div>
            </div>
            {{-- Avatar + name chip --}}
            <div class="hidden sm:flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-surface-100 dark:bg-surface-800/60 border border-surface-200/60 dark:border-surface-700/60">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                         class="w-7 h-7 rounded-lg object-cover" alt="avatar">
                @else
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-brand-400 to-accent-500 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <span class="text-sm font-medium text-surface-800 dark:text-surface-200">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </x-slot>

    {{-- Tab navigation --}}
    <div x-data="{ tab: '{{ session('status') === 'password-updated' ? 'security' : 'profile' }}' }" class="max-w-3xl mx-auto">

        {{-- Tabs --}}
        <div class="flex gap-1 p-1 mb-6 bg-surface-100 dark:bg-surface-800/60 rounded-xl border border-surface-200/60 dark:border-surface-700/60">
            @foreach([
                ['id'=>'profile', 'label'=>'Profile & Preferences', 'icon'=>'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'],
                ['id'=>'security', 'label'=>'Security', 'icon'=>'M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z'],
                ['id'=>'danger', 'label'=>'Danger Zone', 'icon'=>'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z'],
            ] as $t)
            <button @click="tab = '{{ $t['id'] }}'"
                    :class="tab === '{{ $t['id'] }}' ? 'bg-white dark:bg-surface-700/80 text-surface-900 dark:text-white shadow-sm' : 'text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-300'"
                    class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['icon'] }}"/>
                </svg>
                <span class="hidden sm:inline">{{ $t['label'] }}</span>
            </button>
            @endforeach
        </div>

        {{-- Profile & Preferences Tab --}}
        <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="glass-card p-6 sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Security Tab --}}
        <div x-show="tab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="glass-card p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-surface-200/50 dark:border-surface-700/50">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-surface-900 dark:text-white">Change Password</h2>
                        <p class="text-xs text-surface-400 mt-0.5">Use a strong password of at least 8 characters.</p>
                    </div>
                </div>
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Danger Zone Tab --}}
        <div x-show="tab === 'danger'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="glass-card p-6 sm:p-8 border border-red-500/20">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-red-200/30 dark:border-red-500/20">
                    <div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-red-600 dark:text-red-400">Danger Zone</h2>
                        <p class="text-xs text-surface-400 mt-0.5">Permanent and irreversible actions.</p>
                    </div>
                </div>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

    @stack('scripts')
</x-app-layout>
