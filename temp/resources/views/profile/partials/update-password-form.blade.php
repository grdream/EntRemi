<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="input-enhanced" autocomplete="current-password" placeholder="••••••••">
            @error('current_password', 'updatePassword')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- New Password --}}
        <div>
            <label for="update_password_password" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">New Password</label>
            <input id="update_password_password" name="password" type="password" class="input-enhanced" autocomplete="new-password" placeholder="••••••••">
            @error('password', 'updatePassword')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Confirm New Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="input-enhanced" autocomplete="new-password" placeholder="••••••••">
            @error('password_confirmation', 'updatePassword')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn-primary">Update Password</button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    Password updated!
                </p>
            @endif
        </div>
    </form>
</section>
