<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            line-height: 1.6;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #0f172a;
        }
        /* Header */
        .header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            padding: 40px 40px 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .logo-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            position: relative;
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-text {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.02em;
        }
        .badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 16px;
            position: relative;
        }
        .header h1 {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.03em;
            line-height: 1.2;
            position: relative;
        }
        /* Poster Section */
        .poster-section {
            background: #1e293b;
            border-bottom: 1px solid #334155;
            padding: 0;
        }
        .poster-row {
            display: flex;
            align-items: stretch;
        }
        .poster-col {
            width: 100px;
            flex-shrink: 0;
            background: #0f172a;
        }
        .poster-col img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        .poster-placeholder {
            width: 100%;
            height: 150px;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .show-info {
            flex: 1;
            padding: 20px 24px;
        }
        .show-type {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #818cf8;
            background: rgba(99, 102, 241, 0.15);
            padding: 2px 8px;
            border-radius: 4px;
            margin-bottom: 8px;
        }
        .show-title {
            font-size: 18px;
            font-weight: 700;
            color: #f1f5f9;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }
        .show-meta {
            font-size: 12px;
            color: #64748b;
        }
        /* Countdown */
        .countdown {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 32px 40px;
            text-align: center;
            border-bottom: 1px solid #334155;
        }
        .episode-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 8px;
            padding: 8px 16px;
            margin-bottom: 16px;
        }
        .episode-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            animation: pulse 2s infinite;
        }
        .episode-label {
            font-size: 13px;
            font-weight: 600;
            color: #818cf8;
        }
        .air-time {
            font-size: 28px;
            font-weight: 800;
            color: #f1f5f9;
            letter-spacing: -0.03em;
            margin-bottom: 4px;
        }
        .air-timezone {
            font-size: 12px;
            color: #64748b;
        }
        /* Details */
        .details {
            background: #1e293b;
            padding: 28px 40px;
            border-bottom: 1px solid #334155;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #334155;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .detail-value {
            font-size: 13px;
            color: #e2e8f0;
            font-weight: 600;
        }
        /* CTA */
        .cta-section {
            padding: 32px 40px;
            text-align: center;
            background: #0f172a;
        }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            padding: 14px 32px;
            border-radius: 10px;
            letter-spacing: 0.01em;
        }
        .cta-hint {
            margin-top: 12px;
            font-size: 11px;
            color: #475569;
        }
        /* Footer */
        .footer {
            background: #0a0f1e;
            border-top: 1px solid #1e293b;
            padding: 24px 40px;
            text-align: center;
        }
        .footer p {
            font-size: 11px;
            color: #475569;
            margin-bottom: 4px;
        }
        .footer a {
            color: #6366f1;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Header -->
    <div class="header">
        <div class="logo-wrap">
            <div class="logo-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z"/>
                </svg>
            </div>
            <span class="logo-text">WatchList Reminder</span>
        </div>
        <div class="badge">⏰ Episode Alert</div>
        <h1>New Episode Airing Soon!</h1>
    </div>

    <!-- Show Poster + Info -->
    <div class="poster-section">
        <div class="poster-row">
            <div class="poster-col">
                @if($episode->show->poster_url)
                    <img src="{{ $episode->show->poster_url }}" alt="{{ $episode->show->title }}">
                @else
                    <div class="poster-placeholder">
                        <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,0.2)" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125Z"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="show-info">
                <div class="show-type">{{ ucwords(str_replace('_', ' ', $episode->show->type)) }}</div>
                <div class="show-title">{{ $episode->show->title }}</div>
                <div class="show-meta">
                    @if($episode->show->year){{ $episode->show->year }} &middot; @endif
                    @if($episode->show->rating)★ {{ $episode->show->rating }}@endif
                </div>
            </div>
        </div>
    </div>

    <!-- Countdown / Air Time -->
    <div class="countdown">
        <div class="episode-badge">
            <div class="episode-dot"></div>
            <span class="episode-label">
                @if($episode->season_no) Season {{ $episode->season_no }} · @endif
                Episode {{ $episode->episode_no }}
                @if($episode->title) — {{ $episode->title }}@endif
            </span>
        </div>
        @if($episode->air_datetime)
        <div class="air-time">{{ $episode->air_datetime->format('D, M d · g:i A') }}</div>
        <div class="air-timezone">{{ $episode->air_datetime->timezone->getName() }}</div>
        @endif
    </div>

    <!-- Detail Rows -->
    <div class="details">
        @if($episode->duration_minutes)
        <div class="detail-row">
            <span class="detail-label">Duration</span>
            <span class="detail-value">{{ $episode->duration_minutes }} min</span>
        </div>
        @endif
        <div class="detail-row">
            <span class="detail-label">Status</span>
            <span class="detail-value">{{ ucwords(str_replace('_', ' ', $episode->show->status)) }}</span>
        </div>
        @if($episode->show->genres && count($episode->show->genres) > 0)
        <div class="detail-row">
            <span class="detail-label">Genres</span>
            <span class="detail-value">{{ implode(', ', array_slice($episode->show->genres, 0, 3)) }}</span>
        </div>
        @endif
        @if($episode->air_datetime)
        <div class="detail-row">
            <span class="detail-label">Airing</span>
            <span class="detail-value">{{ $episode->air_datetime->diffForHumans() }}</span>
        </div>
        @endif
    </div>

    <!-- CTA -->
    <div class="cta-section">
        <a href="{{ url('/watchlist/' . $episode->show->slug) }}" class="cta-btn">
            View in WatchList →
        </a>
        <p class="cta-hint">You're receiving this because you enabled email reminders for this show.</p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© {{ date('Y') }} WatchList Reminder &mdash; Track. Schedule. Never Miss.</p>
        <p><a href="{{ url('/profile') }}">Manage notification preferences</a></p>
    </div>
</div>
</body>
</html>
