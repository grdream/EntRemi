<x-guest-layout>
    @section('title', 'Create Account')

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-surface-900 dark:text-white">Create your account</h2>
        <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">Start tracking your watchlist in seconds</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Full Name</label>
            <input id="name" name="name" type="text" class="input-enhanced"
                   value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
            @error('name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Email Address</label>
            <input id="email" name="email" type="email" class="input-enhanced"
                   value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
            @error('email')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Phone (optional) --}}
        <div>
            <label for="phone" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">
                Phone <span class="text-surface-400 font-normal">(optional — for SMS reminders)</span>
            </label>
            <input id="phone" name="phone" type="tel" class="input-enhanced"
                   value="{{ old('phone') }}" autocomplete="tel" placeholder="+1 555 000 0000">
            @error('phone')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Timezone --}}
        <div>
            <label for="timezone" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Your Timezone</label>
            <select id="timezone" name="timezone" class="input-enhanced">
                @php
                    $userTz = old('timezone', 'Asia/Karachi');
                    $timezones = \DateTimeZone::listIdentifiers();
                @endphp
                @foreach($timezones as $tz)
                    <option value="{{ $tz }}" {{ $userTz === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-surface-400">Used to show episode air times in your local time.</p>
            @error('timezone')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Password</label>
            <input id="password" name="password" type="password" class="input-enhanced"
                   required autocomplete="new-password" placeholder="Min 8 characters">
            @error('password')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="input-enhanced"
                   required autocomplete="new-password" placeholder="••••••••">
            @error('password_confirmation')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-primary w-full py-3 mt-2" id="register-submit">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
            </svg>
            Create My Account
        </button>

        {{-- Login link --}}
        <p class="text-center text-sm text-surface-500 dark:text-surface-400 pt-1">
            Already have an account?
            <a href="{{ route('login') }}" class="text-brand-500 dark:text-brand-400 hover:text-brand-600 dark:hover:text-brand-300 font-semibold transition-colors">
                Sign in
            </a>
        </p>
    </form>
</x-guest-layout>
