<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InstallController extends Controller
{
    /** Show the installer wizard. */
    public function index(): View
    {
        $checks = $this->runChecks();
        return view('install.index', compact('checks'));
    }

    /** AJAX: test DB connection. */
    public function checkDb(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'db_host'     => 'required|string',
            'db_port'     => 'required|integer',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        try {
            $pdo = new \PDO(
                "mysql:host={$data['db_host']};port={$data['db_port']};dbname={$data['db_database']}",
                $data['db_username'],
                $data['db_password'] ?? '',
                [\PDO::ATTR_TIMEOUT => 5]
            );
            return response()->json(['success' => true, 'message' => 'Connection successful!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /** Run the full installation. */
    public function run(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'app_name'      => 'required|string|max:100',
            'app_url'       => 'required|url',
            'app_timezone'  => 'required|string',
            'admin_email'   => 'required|email',
            'db_host'       => 'required|string',
            'db_port'       => 'required|integer',
            'db_database'   => 'required|string',
            'db_username'   => 'required|string',
            'db_password'   => 'nullable|string',
            'admin_name'    => 'required|string|max:100',
            'admin_user_email' => 'required|email',
            'admin_password' => 'required|string|min:8',
            'mail_host'     => 'nullable|string',
            'mail_port'     => 'nullable|integer',
            'mail_user'     => 'nullable|string',
            'mail_pass'     => 'nullable|string',
            'mail_enc'      => 'nullable|in:tls,ssl,none',
            'mail_from'     => 'nullable|email',
        ]);

        try {
            // 1. Write .env
            $envPath = base_path('.env');
            $envContent = $this->buildEnv($data);
            File::put($envPath, $envContent);

            // 2. Reload config
            $this->refreshEnv();

            // 3. Run migrations
            Artisan::call('migrate', ['--force' => true]);

            // 4. Create admin user
            $adminEmail = $data['admin_user_email'];
            $user = \App\Models\User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name'     => $data['admin_name'],
                    'password' => bcrypt($data['admin_password']),
                    'plan'     => 'premium',
                    'is_active' => true,
                    'email_notifications' => true,
                    'sms_notifications'   => false,
                ]
            );

            // 5. Write installed.lock
            File::put(storage_path('installed.lock'), now()->toDateTimeString());

            // 6. Clear caches
            Artisan::call('optimize:clear');

            return response()->json([
                'success'   => true,
                'login_url' => url('/login'),
                'message'   => 'Installation complete! Redirecting to login…',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─── Private ──────────────────────────────────────────────────────────────

    private function runChecks(): array
    {
        return [
            'php_version'   => ['label' => 'PHP ≥ 8.2',        'ok' => version_compare(PHP_VERSION, '8.2.0', '>='), 'value' => PHP_VERSION],
            'pdo_mysql'     => ['label' => 'PDO MySQL',         'ok' => extension_loaded('pdo_mysql'),               'value' => extension_loaded('pdo_mysql') ? 'Enabled' : 'Missing'],
            'mbstring'      => ['label' => 'Mbstring',          'ok' => extension_loaded('mbstring'),                'value' => extension_loaded('mbstring') ? 'Enabled' : 'Missing'],
            'openssl'       => ['label' => 'OpenSSL',           'ok' => extension_loaded('openssl'),                 'value' => extension_loaded('openssl') ? 'Enabled' : 'Missing'],
            'tokenizer'     => ['label' => 'Tokenizer',         'ok' => extension_loaded('tokenizer'),               'value' => extension_loaded('tokenizer') ? 'Enabled' : 'Missing'],
            'xml'           => ['label' => 'XML',               'ok' => extension_loaded('xml'),                     'value' => extension_loaded('xml') ? 'Enabled' : 'Missing'],
            'json'          => ['label' => 'JSON',              'ok' => extension_loaded('json'),                    'value' => extension_loaded('json') ? 'Enabled' : 'Missing'],
            'storage_write' => ['label' => 'storage/ writable', 'ok' => is_writable(storage_path()),                 'value' => is_writable(storage_path()) ? 'Writable' : 'Not writable'],
            'bootstrap_write'=> ['label'=> 'bootstrap/ writable','ok' => is_writable(base_path('bootstrap/cache')), 'value' => is_writable(base_path('bootstrap/cache')) ? 'Writable' : 'Not writable'],
        ];
    }

    private function buildEnv(array $data): string
    {
        $key  = 'base64:' . base64_encode(random_bytes(32));
        $pass = addslashes($data['db_password'] ?? '');
        $mailPass = addslashes($data['mail_pass'] ?? '');

        return <<<ENV
APP_NAME="{$data['app_name']}"
APP_ENV=production
APP_KEY={$key}
APP_DEBUG=false
APP_URL={$data['app_url']}
APP_TIMEZONE={$data['app_timezone']}
ADMIN_EMAIL={$data['admin_email']}

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST={$data['db_host']}
DB_PORT={$data['db_port']}
DB_DATABASE={$data['db_database']}
DB_USERNAME={$data['db_username']}
DB_PASSWORD={$pass}

CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST={$data['mail_host']}
MAIL_PORT={$data['mail_port']}
MAIL_USERNAME={$data['mail_user']}
MAIL_PASSWORD={$mailPass}
MAIL_ENCRYPTION={$data['mail_enc']}
MAIL_FROM_ADDRESS={$data['mail_from']}
MAIL_FROM_NAME="{$data['app_name']}"

FILESYSTEM_DISK=local
ENV;
    }

    private function refreshEnv(): void
    {
        // Reload environment variables from the newly written .env
        $dotenv = \Dotenv\Dotenv::createImmutable(base_path());
        try { $dotenv->load(); } catch (\Throwable) {}
    }
}
