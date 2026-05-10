<x-app-layout>
    @section('title', 'System Settings')
    <x-slot name="header">
        <h1 class="text-xl font-bold text-surface-900 dark:text-white">System Settings</h1>
        <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5">Configure system-wide SMTP, SMS, and site options.</p>
    </x-slot>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-sm">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.save') }}" class="max-w-3xl space-y-6">
        @csrf

        {{-- Site Settings --}}
        <div class="glass-card p-6 space-y-4">
            <h2 class="text-sm font-semibold text-surface-900 dark:text-white pb-3 border-b border-surface-200/50 dark:border-surface-700/50 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253"/></svg>
                Site Configuration
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Site Name</label>
                    <input name="site_name" type="text" class="input-enhanced" value="{{ $settings['site_name'] ?? 'EntRemi' }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Premium Upgrade URL (WhatsApp)</label>
                    <input name="premium_upgrade_url" type="url" class="input-enhanced" value="{{ $settings['premium_upgrade_url'] ?? 'https://wa.me/923274990424' }}">
                    <p class="text-xs text-surface-400 mt-1">e.g. https://wa.me/923274990424?text=I+want+Premium</p>
                </div>
            </div>
        </div>

        {{-- System Email SMTP --}}
        <div class="glass-card p-6 space-y-4">
            <h2 class="text-sm font-semibold text-surface-900 dark:text-white pb-3 border-b border-surface-200/50 dark:border-surface-700/50 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                System Email (SMTP)
                <span class="ml-auto text-[10px] font-normal text-surface-400">Used by FREE users for email reminders</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">SMTP Host</label>
                    <input name="system_mail_host" type="text" class="input-enhanced" value="{{ $settings['system_mail_host'] ?? '' }}" placeholder="smtp.gmail.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">SMTP Port</label>
                    <input name="system_mail_port" type="number" class="input-enhanced" value="{{ $settings['system_mail_port'] ?? '587' }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">SMTP Username</label>
                    <input name="system_mail_user" type="text" class="input-enhanced" value="{{ $settings['system_mail_user'] ?? '' }}" placeholder="your@email.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">SMTP Password</label>
                    <input name="system_mail_pass" type="password" class="input-enhanced" value="{{ $settings['system_mail_pass'] ?? '' }}" placeholder="App password">
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Encryption</label>
                    <select name="system_mail_enc" class="input-enhanced">
                        @foreach(['tls'=>'TLS','ssl'=>'SSL','none'=>'None'] as $v=>$l)
                        <option value="{{ $v }}" @selected(($settings['system_mail_enc']??'tls')===$v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">From Address</label>
                    <input name="system_mail_from" type="email" class="input-enhanced" value="{{ $settings['system_mail_from'] ?? '' }}" placeholder="noreply@yourdomain.com">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">From Name</label>
                    <input name="system_mail_name" type="text" class="input-enhanced" value="{{ $settings['system_mail_name'] ?? 'EntRemi' }}">
                </div>
            </div>
        </div>

        {{-- System SMS --}}
        <div class="glass-card p-6 space-y-4">
            <h2 class="text-sm font-semibold text-surface-900 dark:text-white pb-3 border-b border-surface-200/50 dark:border-surface-700/50 flex items-center gap-2">
                <svg class="w-4 h-4 text-accent-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3.75h3m-3 3.75h3M9.75 9.75h.008v.008H9.75V9.75Z"/></svg>
                System SMS Gateway
                <span class="ml-auto text-[10px] font-normal text-surface-400">Used by PREMIUM users only</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Gateway URL</label>
                    <input name="system_sms_url" type="url" class="input-enhanced" value="{{ $settings['system_sms_url'] ?? '' }}" placeholder="https://api.smsprovider.com/send">
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Method</label>
                    <select name="system_sms_method" class="input-enhanced">
                        <option value="POST" @selected(($settings['system_sms_method']??'POST')==='POST')>POST</option>
                        <option value="GET" @selected(($settings['system_sms_method']??'POST')==='GET')>GET</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Parameters (JSON)</label>
                    <input name="system_sms_params" type="text" class="input-enhanced" value="{{ $settings['system_sms_params'] ?? '' }}" placeholder='{"api_key":"xxx","from":"EntRemi"}'>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-primary">Save All Settings</button>
        </div>
    </form>
</x-app-layout>
