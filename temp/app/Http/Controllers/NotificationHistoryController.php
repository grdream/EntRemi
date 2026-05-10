<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationHistoryController extends Controller
{
    /**
     * Show paginated notification history for the authenticated user.
     */
    public function index(Request $request): View
    {
        $query = NotificationLog::with(['show', 'episode'])
            ->where('user_id', auth()->id())
            ->latest();

        // Filter by channel
        if ($request->filled('channel') && in_array($request->channel, ['email', 'sms'])) {
            $query->where('channel', $request->channel);
        }

        // Filter by status
        if ($request->filled('status') && in_array($request->status, ['sent', 'failed', 'pending'])) {
            $query->where('status', $request->status);
        }

        $logs = $query->paginate(20)->withQueryString();

        $stats = [
            'total'   => NotificationLog::where('user_id', auth()->id())->count(),
            'sent'    => NotificationLog::where('user_id', auth()->id())->where('status', 'sent')->count(),
            'failed'  => NotificationLog::where('user_id', auth()->id())->where('status', 'failed')->count(),
        ];

        return view('notifications.history', compact('logs', 'stats'));
    }
}
