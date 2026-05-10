<div>
    <form wire:submit="save" class="space-y-4">
        @if (session()->has('smtp_status'))
            <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm">
                {{ session('smtp_status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Host</label>
                <input type="text" wire:model="host" class="input-enhanced" placeholder="smtp.mailtrap.io">
                @error('host') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Port</label>
                <input type="number" wire:model="port" class="input-enhanced" placeholder="587">
                @error('port') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Username</label>
                <input type="text" wire:model="username" class="input-enhanced" autocomplete="off">
                @error('username') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Password</label>
                <input type="password" wire:model="password" class="input-enhanced" placeholder="Leave blank to keep current" autocomplete="new-password">
                @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Encryption</label>
                <select wire:model="encryption" class="input-enhanced">
                    <option value="tls">TLS</option>
                    <option value="ssl">SSL</option>
                    <option value="none">None</option>
                </select>
                @error('encryption') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">From Address</label>
                <input type="email" wire:model="from_address" class="input-enhanced" placeholder="noreply@domain.com">
                @error('from_address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">From Name</label>
                <input type="text" wire:model="from_name" class="input-enhanced" placeholder="WatchList App">
                @error('from_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex items-center gap-2 mt-2">
            <input type="checkbox" wire:model="is_active" id="smtp_is_active" class="rounded border-surface-300 text-brand-600 shadow-sm focus:border-brand-300 focus:ring focus:ring-brand-200 focus:ring-opacity-50">
            <label for="smtp_is_active" class="text-sm text-surface-600 dark:text-surface-400">Enable this SMTP configuration</label>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full sm:w-auto">Save SMTP Settings</button>
        </div>
    </form>
</div>
