<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use App\Models\Show;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    // ─── Dashboard ────────────────────────────────────────────────────────────

    public function dashboard(): View
    {
        $stats = [
            'total_users'     => User::count(),
            'premium_users'   => User::where('plan', 'premium')->count(),
            'free_users'      => User::where('plan', 'free')->count(),
            'active_users'    => User::where('is_active', true)->count(),
            'total_shows'     => Show::count(),
            'notifs_today'    => NotificationLog::whereDate('created_at', today())->count(),
            'notifs_sent'     => NotificationLog::where('status', 'sent')->whereDate('created_at', today())->count(),
            'notifs_failed'   => NotificationLog::where('status', 'failed')->whereDate('created_at', today())->count(),
            'total_notifs'    => NotificationLog::count(),
        ];

        $recentUsers  = User::latest()->limit(8)->get();
        $recentNotifs = NotificationLog::with(['user', 'show'])->latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentNotifs'));
    }

    // ─── User Management ──────────────────────────────────────────────────────

    public function users(Request $request): View
    {
        $query = User::latest();

        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->withCount(['shows', 'notificationLogs'])->paginate(25)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function editUser(User $user): View
    {
        return view('admin.user-edit', compact('user'));
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email,' . $user->id],
            'plan'      => ['required', 'in:free,premium'],
            'is_active' => ['boolean'],
            'phone'     => ['nullable', 'string', 'max:30'],
            'timezone'  => ['nullable', 'string', 'max:60'],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->fill([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'plan'      => $data['plan'],
            'is_active' => $request->boolean('is_active', true),
            'phone'     => $data['phone'] ?? $user->phone,
            'timezone'  => $data['timezone'] ?? $user->timezone,
        ]);

        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        return back()->with('success', 'User "' . $user->name . '" updated successfully.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }
        $name = $user->name;
        $user->delete();
        return redirect()->route('admin.users')->with('success', '"' . $name . '" deleted.');
    }

    // ─── System Settings ──────────────────────────────────────────────────────

    public function settings(): View
    {
        $settings = SystemSetting::query()->pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $keys = [
            'site_name', 'premium_upgrade_url',
            'system_mail_host', 'system_mail_port', 'system_mail_user',
            'system_mail_pass', 'system_mail_enc', 'system_mail_from', 'system_mail_name',
            'system_sms_url', 'system_sms_params', 'system_sms_method',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                SystemSetting::set($key, $request->input($key));
            }
        }

        return back()->with('success', 'System settings saved.');
    }

    // ─── Notification Logs (existing) ─────────────────────────────────────────

    public function notifications(Request $request): View
    {
        $query = NotificationLog::with(['user', 'show', 'episode'])->latest();

        if ($request->filled('status') && in_array($request->status, ['sent', 'failed', 'pending'])) {
            $query->where('status', $request->status);
        }
        if ($request->filled('channel') && in_array($request->channel, ['email', 'sms'])) {
            $query->where('channel', $request->channel);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(50)->withQueryString();

        $todayStats = [
            'sent'    => NotificationLog::where('status', 'sent')->whereDate('created_at', today())->count(),
            'failed'  => NotificationLog::where('status', 'failed')->whereDate('created_at', today())->count(),
            'pending' => NotificationLog::where('status', 'pending')->whereDate('created_at', today())->count(),
            'total'   => NotificationLog::whereDate('created_at', today())->count(),
        ];

        $allTimeStats = [
            'total'  => NotificationLog::count(),
            'sent'   => NotificationLog::where('status', 'sent')->count(),
            'failed' => NotificationLog::where('status', 'failed')->count(),
            'email'  => NotificationLog::where('channel', 'email')->count(),
            'sms'    => NotificationLog::where('channel', 'sms')->count(),
        ];

        return view('admin.notifications', compact('logs', 'todayStats', 'allTimeStats'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $query = NotificationLog::with(['user', 'show', 'episode'])->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('channel')) $query->where('channel', $request->channel);
        $logs = $query->get();

        $filename = 'notification-logs-' . now()->format('Y-m-d-His') . '.csv';
        return response()->streamDownload(function () use ($logs) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'User', 'Email', 'Channel', 'Show', 'Season', 'Episode', 'Status', 'Message', 'Error', 'Sent At', 'Created At']);
            foreach ($logs as $log) {
                fputcsv($out, [
                    $log->id,
                    $log->user->name ?? 'N/A',
                    $log->user->email ?? '',
                    $log->channel,
                    $log->show->title ?? 'N/A',
                    $log->episode->season_no ?? '',
                    $log->episode->episode_no ?? '',
                    $log->status,
                    $log->message ?? '',
                    $log->error_message ?? '',
                    $log->sent_at?->toDateTimeString() ?? '',
                    $log->created_at->toDateTimeString(),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
