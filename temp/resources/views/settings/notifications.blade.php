<x-app-layout>
    @section('title', 'Notification Settings')

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-brand-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-surface-900 dark:text-white">Notification Settings</h1>
                <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Configure email and SMS reminders for your shows</p>
            </div>
        </div>
    </x-slot>

    {{-- Flash Messages --}}
    @foreach(['smtp_success' => 'emerald', 'smtp_error' => 'red', 'sms_success' => 'emerald', 'sms_error' => 'red'] as $key => $color)
    @if(session($key))
    <div x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,5000)" x-transition
         class="mb-4 px-4 py-3 rounded-xl bg-{{ $color }}-500/10 border border-{{ $color }}-500/30 text-{{ $color }}-700 dark:text-{{ $color }}-400 text-sm flex items-center gap-2">
        @if(str_contains($key, 'success'))
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
        @else
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
        @endif
        {{ session($key) }}
    </div>
    @endif
    @endforeach

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- SMTP Email Settings --}}
        <div class="space-y-4">
            <div class="glass-card overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-surface-200/50 dark:border-surface-700/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-brand-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-surface-900 dark:text-white">Email SMTP Settings</h2>
                            @if($smtp?->tested_at)
                                <p class="text-xs text-emerald-500">✓ Tested {{ $smtp->tested_at->diffForHumans() }}</p>
                            @else
                                <p class="text-xs text-surface-500">Not tested yet</p>
                            @endif
                        </div>
                    </div>
                    @if($smtp)
                    <form method="POST" action="{{ route('settings.smtp.test') }}">
                        @csrf
                        <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-brand-500/10 text-brand-500 hover:bg-brand-500/20 transition-colors font-medium">
                            Test Connection
                        </button>
                    </form>
                    @endif
                </div>
                <div class="p-6">
                    <livewire:manage-smtp />
                </div>
            </div>
        </div>

        {{-- SMS Settings --}}
        <div class="space-y-4">
            <div class="glass-card overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-surface-200/50 dark:border-surface-700/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3.75h3m-3 3.75h3M9.75 9.75h.008v.008H9.75V9.75Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-surface-900 dark:text-white">SMS Gateway Settings</h2>
                            @if($sms?->tested_at)
                                <p class="text-xs text-emerald-500">✓ Tested {{ $sms->tested_at->diffForHumans() }}</p>
                            @else
                                <p class="text-xs text-surface-500">Not tested yet</p>
                            @endif
                        </div>
                    </div>
                    @if($sms)
                    <form method="POST" action="{{ route('settings.sms.test') }}">
                        @csrf
                        <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-amber-500/10 text-amber-500 hover:bg-amber-500/20 transition-colors font-medium">
                            Send Test SMS
                        </button>
                    </form>
                    @endif
                </div>
                <div class="p-6">
                    <livewire:manage-sms />
                </div>
            </div>
        </div>

    </div>

    {{-- Notification Preferences --}}
    <div class="mt-6 glass-card p-6">
        <h2 class="text-sm font-semibold text-surface-900 dark:text-white mb-4">Notification Preferences</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex items-center justify-between p-4 rounded-xl bg-surface-50 dark:bg-surface-800/50 border border-surface-200/50 dark:border-surface-700/40">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-brand-500/10 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-surface-800 dark:text-surface-200">Email Reminders</p>
                        <p class="text-xs text-surface-500">Send episode alerts via email</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('profile.update') }}" id="toggle-email-form">
                    @csrf @method('PATCH')
                    <input type="hidden" name="email_notifications" value="{{ $user->email_notifications ? '0' : '1' }}">
                    <button type="submit"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $user->email_notifications ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                        <span class="translate-x-0 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $user->email_notifications ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                </form>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl bg-surface-50 dark:bg-surface-800/50 border border-surface-200/50 dark:border-surface-700/40">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-amber-500/10 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-surface-800 dark:text-surface-200">SMS Reminders</p>
                        <p class="text-xs text-surface-500">Send alerts via SMS gateway</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('profile.update') }}" id="toggle-sms-form">
                    @csrf @method('PATCH')
                    <input type="hidden" name="sms_notifications" value="{{ $user->sms_notifications ? '0' : '1' }}">
                    <button type="submit"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $user->sms_notifications ? 'bg-amber-500' : 'bg-surface-300 dark:bg-surface-600' }}">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $user->sms_notifications ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
