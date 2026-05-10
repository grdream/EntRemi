<x-app-layout>
    @section('title', 'Edit User')
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users') }}" class="w-9 h-9 rounded-xl bg-surface-100 dark:bg-surface-800 flex items-center justify-center text-surface-500 hover:bg-surface-200 dark:hover:bg-surface-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-surface-900 dark:text-white">Edit User</h1>
                <p class="text-xs text-surface-500 mt-0.5">{{ $user->email }}</p>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-sm">{{ session('success') }}</div>
    @endif

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="glass-card p-6 space-y-4">
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white border-b border-surface-200/50 dark:border-surface-700/50 pb-3">Account Info</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Full Name</label>
                        <input name="name" type="text" class="input-enhanced" value="{{ old('name', $user->name) }}" required>
                        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Email</label>
                        <input name="email" type="email" class="input-enhanced" value="{{ old('email', $user->email) }}" required>
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Phone</label>
                        <input name="phone" type="text" class="input-enhanced" value="{{ old('phone', $user->phone) }}" placeholder="+1234567890">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Timezone</label>
                        <input name="timezone" type="text" class="input-enhanced" value="{{ old('timezone', $user->timezone) }}" placeholder="Asia/Karachi">
                    </div>
                </div>
            </div>

            <div class="glass-card p-6 space-y-4">
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white border-b border-surface-200/50 dark:border-surface-700/50 pb-3">Plan & Access</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Plan</label>
                        <select name="plan" class="input-enhanced">
                            <option value="free" @selected(old('plan', $user->plan) === 'free')>🆓 Free — Email reminders only</option>
                            <option value="premium" @selected(old('plan', $user->plan) === 'premium')>⭐ Premium — Email + SMS reminders</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Account Status</label>
                        <select name="is_active" class="input-enhanced">
                            <option value="1" @selected((int)old('is_active', $user->is_active) === 1)>✅ Active</option>
                            <option value="0" @selected((int)old('is_active', $user->is_active) === 0)>🚫 Suspended</option>
                        </select>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-amber-500/5 border border-amber-500/20 text-xs text-amber-600 dark:text-amber-400">
                    <strong>Plan rules:</strong> Free users receive email reminders only (using system SMTP). Premium users receive both email + SMS reminders and can configure their own gateways.
                </div>
            </div>

            <div class="glass-card p-6 space-y-4">
                <h2 class="text-sm font-semibold text-surface-900 dark:text-white border-b border-surface-200/50 dark:border-surface-700/50 pb-3">Reset Password <span class="font-normal text-surface-400">(leave blank to keep current)</span></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">New Password</label>
                        <input name="password" type="password" class="input-enhanced" placeholder="Min. 8 characters">
                        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Confirm Password</label>
                        <input name="password_confirmation" type="password" class="input-enhanced" placeholder="Repeat password">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 justify-between">
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-red-500 border border-red-500/30 hover:bg-red-500/10 transition-colors">
                        Delete Account
                    </button>
                </form>
                <div class="flex gap-3">
                    <a href="{{ route('admin.users') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
