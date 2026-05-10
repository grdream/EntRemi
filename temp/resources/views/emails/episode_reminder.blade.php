<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Episode Reminder</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f4f5; color: #18181b; line-height: 1.6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background: #8b5cf6; padding: 30px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .content { padding: 30px 20px; }
        .show-title { font-size: 20px; font-weight: 600; margin-top: 0; margin-bottom: 5px; }
        .ep-info { font-size: 16px; color: #71717a; margin-top: 0; margin-bottom: 20px; }
        .btn { display: inline-block; background: #8b5cf6; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; margin-top: 10px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #a1a1aa; border-top: 1px solid #f4f4f5; }
        .poster { width: 100%; max-width: 200px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>WatchList Reminder</h1>
        </div>
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            <p>An episode you are tracking is airing very soon!</p>
            
            @if($show->poster_url)
            <div style="text-align: center;">
                <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="poster">
            </div>
            @endif

            <h2 class="show-title">{{ $show->title }}</h2>
            <p class="ep-info">Episode {{ $episode->episode_no }} {{ $episode->title ? '- '.$episode->title : '' }}</p>
            
            <div style="background: #f4f4f5; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <strong>Air Time:</strong> {{ $episode->air_datetime->setTimezone($user->timezone ?? 'UTC')->format('l, F j, Y \a\t g:i A') }} ({{ $user->timezone ?? 'UTC' }})
            </div>

            <p style="text-align: center;">
                <a href="{{ config('app.url') }}/watchlist/{{ $show->slug }}" class="btn">View on Dashboard</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} WatchList Reminder. You can manage your notification preferences in your profile settings.
        </div>
    </div>
</body>
</html>
