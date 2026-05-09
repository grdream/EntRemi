<section x-data="{ confirmDelete: false }">
    <p class="text-sm text-surface-600 dark:text-surface-400 mb-6">
        Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
    </p>

    <button @click="confirmDelete = true" id="delete-account-btn"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition-all duration-200">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
        </svg>
        Delete Account
    </button>

    {{-- Confirmation Modal --}}
    <div x-show="confirmDelete" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="absolute inset-0 bg-surface-900/70 backdrop-blur-sm" @click="confirmDelete = false"></div>
        <div class="relative glass-card p-8 max-w-md w-full z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-surface-900 dark:text-white">Are you absolutely sure?</h3>
            </div>
            <p class="text-sm text-surface-600 dark:text-surface-400 mb-6">
                This action cannot be undone. This will permanently delete your account and remove all associated data including your watchlist, schedules, and notification history.
            </p>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="mb-4">
                    <label for="confirm-delete-password" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Confirm your password</label>
                    <input id="confirm-delete-password" name="password" type="password" class="input-enhanced" placeholder="••••••••">
                    @error('password', 'userDeletion')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors">
                        Yes, Delete My Account
                    </button>
                    <button type="button" @click="confirmDelete = false" class="btn-secondary flex-1">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</section>
