<x-guest-layout>
    @section('title', 'Login')

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-surface-900 dark:text-white">Welcome back</h2>
        <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Sign in to your account to continue</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5" />
            <x-text-input id="email"
                class="input-enhanced"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5" />
            <x-text-input id="password"
                class="input-enhanced"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded-md border-surface-300 dark:border-surface-600 text-brand-500 shadow-sm focus:ring-brand-500 dark:bg-surface-800 dark:focus:ring-brand-400" name="remember">
                <span class="ms-2 text-sm text-surface-600 dark:text-surface-400">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-brand-500 dark:text-brand-400 hover:text-brand-600 dark:hover:text-brand-300 font-medium transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary w-full py-3" id="login-submit">
            {{ __('Sign In') }}
        </button>

        <!-- Register Link -->
        <p class="text-center text-sm text-surface-500 dark:text-surface-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-brand-500 dark:text-brand-400 hover:text-brand-600 dark:hover:text-brand-300 font-semibold transition-colors">
                Create one
            </a>
        </p>
    </form>
</x-guest-layout>
