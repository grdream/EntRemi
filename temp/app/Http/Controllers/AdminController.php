<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * Display the notification logs with filters and stats.
     */
    public function notifications(Request $request): View
    {
        $query = NotificationLog::with(['user', 'show', 'episode'])->latest();

        // Apply status filter
        if ($request->filled('status') && in_array($request->status, ['sent', 'failed', 'pending'])) {
            $query->where('status', $request->status);
        }

        // Apply channel filter
        if ($request->filled('channel') && in_array($request->channel, ['email', 'sms'])) {
            $query->where('channel', $request->channel);
        }

        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(50)->withQueryString();

        // Summary stats (today)
        $todayStats = [
            'sent'    => NotificationLog::where('status', 'sent')->whereDate('created_at', today())->count(),
            'failed'  => NotificationLog::where('status', 'failed')->whereDate('created_at', today())->count(),
            'pending' => NotificationLog::where('status', 'pending')->whereDate('created_at', today())->count(),
            'total'   => NotificationLog::whereDate('created_at', today())->count(),
        ];

        // All-time stats
        $allTimeStats = [
            'total'   => NotificationLog::count(),
            'sent'    => NotificationLog::where('status', 'sent')->count(),
            'failed'  => NotificationLog::where('status', 'failed')->count(),
            'email'   => NotificationLog::where('channel', 'email')->count(),
            'sms'     => NotificationLog::where('channel', 'sms')->count(),
        ];

        return view('admin.notifications', compact('logs', 'todayStats', 'allTimeStats'));
    }

    /**
     * Export notification logs as CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = NotificationLog::with(['user', 'show', 'episode'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

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

