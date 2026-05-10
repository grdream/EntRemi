<div>
    <form wire:submit="save" class="space-y-4">
        @if (session()->has('sms_status'))
            <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm">
                {{ session('sms_status') }}
            </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Gateway URL</label>
            <input type="url" wire:model="gateway_url" class="input-enhanced" placeholder="https://api.smsprovider.com/v1/send">
            @error('gateway_url') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">API Key</label>
                <input type="password" wire:model="api_key" class="input-enhanced" placeholder="Leave blank to keep current" autocomplete="new-password">
                @error('api_key') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Sender ID</label>
                <input type="text" wire:model="sender_id" class="input-enhanced" placeholder="AppSender">
                @error('sender_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Extra Parameters (JSON)</label>
            <textarea wire:model="extra_params_json" class="input-enhanced font-mono text-sm" rows="4" placeholder='{
    "type": "text",
    "unicode": 1
}'></textarea>
            @error('extra_params_json') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            <p class="mt-1 text-xs text-surface-400">Additional payload values sent with every request.</p>
        </div>

        <div class="flex items-center gap-2 mt-2">
            <input type="checkbox" wire:model="is_active" id="sms_is_active" class="rounded border-surface-300 text-brand-600 shadow-sm focus:border-brand-300 focus:ring focus:ring-brand-200 focus:ring-opacity-50">
            <label for="sms_is_active" class="text-sm text-surface-600 dark:text-surface-400">Enable this SMS Gateway</label>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full sm:w-auto">Save SMS Settings</button>
        </div>
    </form>
</div>
