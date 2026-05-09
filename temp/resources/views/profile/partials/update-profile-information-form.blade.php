<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        {{-- Avatar Upload --}}
        <div x-data="avatarUpload()" class="flex items-start gap-5">
            {{-- Current Avatar / Preview --}}
            <div class="flex-shrink-0">
                <div class="relative w-20 h-20 rounded-2xl overflow-hidden ring-2 ring-surface-200 dark:ring-surface-700 group">
                    @if($user->avatar)
                        <img id="avatar-preview"
                             src="{{ asset('storage/' . $user->avatar) }}"
                             alt="Avatar"
                             class="w-full h-full object-cover">
                    @else
                        <div id="avatar-preview-initials"
                             class="w-full h-full bg-gradient-to-br from-brand-400 to-accent-500 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    {{-- Overlay on hover --}}
                    <label for="avatar-input"
                           class="absolute inset-0 bg-surface-900/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/>
                        </svg>
                        <span class="text-white text-[10px] mt-1 font-medium">Change</span>
                    </label>
                </div>
            </div>

            {{-- Upload controls --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-surface-700 dark:text-surface-300 mb-1">Profile Photo</p>
                <p class="text-xs text-surface-400 dark:text-surface-500 mb-3">JPG, PNG, GIF or WebP · Max 2MB · Hover photo to change</p>
                <input id="avatar-input"
                       name="avatar"
                       type="file"
                       accept="image/jpeg,image/png,image/gif,image/webp"
                       class="hidden"
                       @change="previewAvatar($event)">
                <label for="avatar-input"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium cursor-pointer
                              text-surface-600 dark:text-surface-400 bg-surface-100 dark:bg-surface-800
                              border border-surface-200 dark:border-surface-700
                              hover:bg-surface-200 dark:hover:bg-surface-700 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                    </svg>
                    Choose File
                </label>
                <span x-text="fileName" class="ml-2 text-xs text-surface-400 dark:text-surface-500"></span>
                @error('avatar')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>

        <hr class="border-surface-200/60 dark:border-surface-700/60">

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Full Name</label>
            <input id="name" name="name" type="text" class="input-enhanced"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   placeholder="Your full name">
            @error('name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Email Address</label>
            <input id="email" name="email" type="email" class="input-enhanced"
                   value="{{ old('email', $user->email) }}" required autocomplete="username"
                   placeholder="your@email.com">
            @error('email')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 rounded-xl bg-amber-500/10 border border-amber-500/20">
                    <p class="text-sm text-amber-600 dark:text-amber-400">
                        Your email address is unverified.
                        <button form="send-verification"
                                class="underline font-medium hover:text-amber-700 dark:hover:text-amber-300 ml-1">
                            Re-send verification email.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                            ✓ Verification link sent!
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Phone --}}
        <div>
            <label for="phone" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">
                Phone Number
                <span class="font-normal text-surface-400">(optional — for SMS reminders)</span>
            </label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-surface-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3.75h3m-3 3.75h3M9.75 9.75h.008v.008H9.75V9.75Z"/>
                </svg>
                <input id="phone" name="phone" type="tel" class="input-enhanced pl-10"
                       value="{{ old('phone', $user->phone) }}" autocomplete="tel"
                       placeholder="+1 555 000 0000">
            </div>
            @error('phone')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Timezone --}}
        <div>
            <label for="timezone" class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Timezone</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-surface-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>
                </svg>
                <select id="timezone" name="timezone" class="input-enhanced pl-10">
                    @php
                        $currentTz = old('timezone', $user->timezone ?? 'UTC');
                        $allTimezones = \DateTimeZone::listIdentifiers();
                    @endphp
                    @foreach($allTimezones as $tz)
                        <option value="{{ $tz }}" {{ $currentTz === $tz ? 'selected' : '' }}>
                            {{ $tz }}
                        </option>
                    @endforeach
                </select>
            </div>
            <p class="mt-1 text-xs text-surface-400">
                Current time in selected zone:
                <strong class="text-surface-500 dark:text-surface-400">
                    {{ now()->setTimezone($user->timezone ?? 'UTC')->format('D, M d Y · g:i A') }}
                </strong>
            </p>
            @error('timezone')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Notification Preferences --}}
        <div class="p-4 rounded-xl bg-surface-50 dark:bg-surface-800/50 border border-surface-200/60 dark:border-surface-700/60">
            <p class="text-sm font-medium text-surface-700 dark:text-surface-300 mb-3">Notification Preferences</p>
            <div class="space-y-3">
                {{-- Email Notifications --}}
                <label for="email_notifications" class="flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-brand-500/10 flex items-center justify-center group-hover:bg-brand-500/20 transition-colors">
                            <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-surface-800 dark:text-surface-200">Email Reminders</p>
                            <p class="text-xs text-surface-400">Receive episode reminders via email</p>
                        </div>
                    </div>
                    {{-- Toggle --}}
                    <div x-data="{ on: {{ $user->email_notifications ? 'true' : 'false' }} }" class="relative">
                        <input type="hidden" name="email_notifications" value="0">
                        <input id="email_notifications" name="email_notifications" type="checkbox"
                               value="1" {{ $user->email_notifications ? 'checked' : '' }}
                               class="sr-only" x-model="on">
                        <div @click="on = !on; $el.previousElementSibling.checked = on"
                             :class="on ? 'bg-brand-500' : 'bg-surface-300 dark:bg-surface-600'"
                             class="w-11 h-6 rounded-full cursor-pointer transition-colors duration-200 relative">
                            <div :class="on ? 'translate-x-5' : 'translate-x-0.5'"
                                 class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"></div>
                        </div>
                    </div>
                </label>

                {{-- SMS Notifications --}}
                <label for="sms_notifications" class="flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center group-hover:bg-emerald-500/20 transition-colors">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3.75h3m-3 3.75h3M9.75 9.75h.008v.008H9.75V9.75Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-surface-800 dark:text-surface-200">SMS Reminders</p>
                            <p class="text-xs text-surface-400">Requires a phone number and SMS gateway setup</p>
                        </div>
                    </div>
                    <div x-data="{ on: {{ $user->sms_notifications ? 'true' : 'false' }} }" class="relative">
                        <input type="hidden" name="sms_notifications" value="0">
                        <input id="sms_notifications" name="sms_notifications" type="checkbox"
                               value="1" {{ $user->sms_notifications ? 'checked' : '' }}
                               class="sr-only" x-model="on">
                        <div @click="on = !on; $el.previousElementSibling.checked = on"
                             :class="on ? 'bg-emerald-500' : 'bg-surface-300 dark:bg-surface-600'"
                             class="w-11 h-6 rounded-full cursor-pointer transition-colors duration-200 relative">
                            <div :class="on ? 'translate-x-5' : 'translate-x-0.5'"
                                 class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"></div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex items-center gap-4 pt-1">
            <button type="submit" class="btn-primary" id="profile-save-btn">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"/>
                </svg>
                Save Profile
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="flex items-center gap-1.5 text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                    Profile saved successfully!
                </p>
            @endif
        </div>
    </form>
</section>

@push('scripts')
<script>
function avatarUpload() {
    return {
        fileName: '',
        previewAvatar(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.fileName = file.name;
            const reader = new FileReader();
            reader.onload = (e) => {
                // Replace preview
                const preview = document.getElementById('avatar-preview');
                const initials = document.getElementById('avatar-preview-initials');
                if (initials) initials.style.display = 'none';
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                } else {
                    // Create img if only initials div was there
                    const img = document.createElement('img');
                    img.id = 'avatar-preview';
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    if (initials) initials.parentNode.insertBefore(img, initials);
                }
            };
            reader.readAsDataURL(file);
        }
    };
}
</script>
@endpush
