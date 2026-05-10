<x-install-layout>
    <div x-data="installer()" class="max-w-3xl mx-auto py-12">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-surface-900 dark:text-white">EntRemi Setup</h1>
            <p class="text-surface-500 mt-2">Deploy your WatchList Reminder platform in minutes.</p>
        </div>

        <div class="glass-card overflow-hidden relative">
            {{-- Progress Bar --}}
            <div class="h-1 bg-surface-200 dark:bg-surface-800">
                <div class="h-full bg-brand-500 transition-all duration-500" :style="'width: ' + ((step/5)*100) + '%'"></div>
            </div>

            <div class="p-8">
                {{-- Step 1: Requirements --}}
                <div x-show="step === 1" x-transition.opacity>
                    <h2 class="text-xl font-bold mb-4">1. System Requirements</h2>
                    <div class="space-y-3 mb-6">
                        @php $allPassed = true; @endphp
                        @foreach($checks as $key => $check)
                            @if(!$check['ok']) @php $allPassed = false; @endphp @endif
                            <div class="flex items-center justify-between p-3 rounded-xl border {{ $check['ok'] ? 'bg-emerald-500/5 border-emerald-500/20 text-emerald-700 dark:text-emerald-400' : 'bg-red-500/5 border-red-500/20 text-red-700 dark:text-red-400' }}">
                                <div class="flex items-center gap-3">
                                    @if($check['ok'])
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    @else
                                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                    @endif
                                    <span class="font-medium text-sm">{{ $check['label'] }}</span>
                                </div>
                                <span class="text-sm">{{ $check['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-end items-center gap-4">
                        @if(!$allPassed)
                            <p class="text-xs text-red-500 font-medium">Please fix the red issues above and refresh the page to continue.</p>
                            <button onclick="window.location.reload()" class="btn-secondary">Refresh</button>
                        @else
                            <button @click="step = 2" class="btn-primary">Next: Database Setup →</button>
                        @endif
                    </div>
                </div>

                {{-- Step 2: Database --}}
                <div x-show="step === 2" x-transition.opacity style="display:none;">
                    <h2 class="text-xl font-bold mb-4">2. Database Connection</h2>
                    <p class="text-sm text-surface-500 mb-6">Create a MySQL/MariaDB database in your hosting panel (cPanel/hPanel) and enter the details below.</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium mb-1">Database Host</label>
                            <input x-model="form.db_host" type="text" class="input-enhanced" placeholder="127.0.0.1 or localhost">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Database Port</label>
                            <input x-model="form.db_port" type="number" class="input-enhanced" placeholder="3306">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium mb-1">Database Name</label>
                            <input x-model="form.db_database" type="text" class="input-enhanced" placeholder="entremi_db">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Username</label>
                            <input x-model="form.db_username" type="text" class="input-enhanced" placeholder="root">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Password</label>
                            <input x-model="form.db_password" type="password" class="input-enhanced" placeholder="Leave blank if none">
                        </div>
                    </div>

                    <div x-show="dbError" x-text="dbError" class="mb-4 text-sm text-red-500 bg-red-500/10 p-3 rounded-lg border border-red-500/20"></div>

                    <div class="flex justify-between items-center">
                        <button @click="step = 1" class="btn-secondary">← Back</button>
                        <button @click="testDb" class="btn-primary flex items-center gap-2" :disabled="loading">
                            <span x-show="loading" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            <span x-text="loading ? 'Testing Connection…' : 'Test Connection & Continue →'"></span>
                        </button>
                    </div>
                </div>

                {{-- Step 3: Site & Admin --}}
                <div x-show="step === 3" x-transition.opacity style="display:none;">
                    <h2 class="text-xl font-bold mb-4">3. Site Info & Admin Account</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">App Name</label>
                                <input x-model="form.app_name" type="text" class="input-enhanced" placeholder="WatchList Reminder">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">App URL</label>
                                <input x-model="form.app_url" type="url" class="input-enhanced" placeholder="https://yourdomain.com">
                            </div>
                        </div>

                        <div class="border-t border-surface-200 dark:border-surface-700 my-4"></div>
                        <h3 class="text-sm font-semibold">Super Admin Account</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Admin Name</label>
                                <input x-model="form.admin_name" type="text" class="input-enhanced" placeholder="John Doe">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Admin Email (Used for Login & System Notifications)</label>
                                <input x-model="form.admin_user_email" @input="form.admin_email = form.admin_user_email" type="email" class="input-enhanced" placeholder="admin@example.com">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium mb-1">Admin Password</label>
                                <input x-model="form.admin_password" type="password" class="input-enhanced" placeholder="Minimum 8 characters">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <button @click="step = 2" class="btn-secondary">← Back</button>
                        <button @click="step = 4" class="btn-primary">Next: Mail Settings →</button>
                    </div>
                </div>

                {{-- Step 4: Mail --}}
                <div x-show="step === 4" x-transition.opacity style="display:none;">
                    <h2 class="text-xl font-bold mb-4">4. System Email (Optional)</h2>
                    <p class="text-sm text-surface-500 mb-6">You can configure the system-wide SMTP for sending free user reminders now, or do it later from the Admin Dashboard.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium mb-1">SMTP Host</label>
                            <input x-model="form.mail_host" type="text" class="input-enhanced" placeholder="smtp.gmail.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">SMTP Port</label>
                            <input x-model="form.mail_port" type="number" class="input-enhanced" placeholder="587">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">SMTP Username</label>
                            <input x-model="form.mail_user" type="text" class="input-enhanced" placeholder="you@email.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">SMTP Password</label>
                            <input x-model="form.mail_pass" type="password" class="input-enhanced" placeholder="App password">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Encryption</label>
                            <select x-model="form.mail_enc" class="input-enhanced">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="none">None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">From Address</label>
                            <input x-model="form.mail_from" type="email" class="input-enhanced" placeholder="noreply@domain.com">
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <button @click="step = 3" class="btn-secondary">← Back</button>
                        <button @click="step = 5" class="btn-primary">Next: Ready to Install →</button>
                    </div>
                </div>

                {{-- Step 5: Install --}}
                <div x-show="step === 5" x-transition.opacity style="display:none;">
                    <h2 class="text-xl font-bold mb-4 text-center">Ready to Install</h2>
                    <p class="text-sm text-surface-500 mb-8 text-center max-w-md mx-auto">This process will configure the environment, run database migrations, and create your super admin account. Do not close the window.</p>

                    <div x-show="installError" x-text="installError" class="mb-4 text-sm text-red-500 bg-red-500/10 p-4 rounded-xl border border-red-500/20 text-center"></div>

                    <div class="flex flex-col items-center gap-4">
                        <button @click="runInstall" class="btn-primary text-lg px-12 py-4 shadow-xl shadow-brand-500/30 flex items-center gap-3" :disabled="loading">
                            <span x-show="loading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            <span x-text="loading ? 'Installing... Please wait...' : 'Install EntRemi Now'"></span>
                        </button>
                        <button x-show="!loading" @click="step = 4" class="text-sm text-surface-500 hover:text-surface-700">← Back to settings</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function installer() {
            return {
                step: 1,
                loading: false,
                dbError: null,
                installError: null,
                form: {
                    db_host: '127.0.0.1',
                    db_port: 3306,
                    db_database: '',
                    db_username: '',
                    db_password: '',
                    app_name: 'EntRemi WatchList',
                    app_url: window.location.origin,
                    app_timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                    admin_email: '',
                    admin_name: '',
                    admin_user_email: '',
                    admin_password: '',
                    mail_host: '',
                    mail_port: 587,
                    mail_user: '',
                    mail_pass: '',
                    mail_enc: 'tls',
                    mail_from: '',
                },
                
                async testDb() {
                    if(!this.form.db_database || !this.form.db_username) {
                        this.dbError = 'Database name and username are required.';
                        return;
                    }
                    this.loading = true;
                    this.dbError = null;
                    
                    try {
                        const res = await fetch('{{ route('install.checkDb') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify(this.form)
                        });
                        const data = await res.json();
                        
                        if(data.success) {
                            this.step = 3;
                        } else {
                            this.dbError = data.message || 'Connection failed.';
                        }
                    } catch(e) {
                        this.dbError = 'Network error testing connection.';
                    } finally {
                        this.loading = false;
                    }
                },
                
                async runInstall() {
                    if(!this.form.admin_user_email || !this.form.admin_password || this.form.admin_password.length < 8) {
                        this.installError = 'Admin email and password (min 8 chars) are required.';
                        return;
                    }
                    this.loading = true;
                    this.installError = null;
                    
                    try {
                        const res = await fetch('{{ route('install.run') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify(this.form)
                        });
                        const data = await res.json();
                        
                        if(data.success) {
                            window.location.href = data.login_url;
                        } else {
                            this.installError = data.message || 'Installation failed.';
                            this.loading = false;
                        }
                    } catch(e) {
                        this.installError = 'Network timeout or server error. Check your logs.';
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</x-install-layout>
