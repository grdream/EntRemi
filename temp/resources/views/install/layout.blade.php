<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Install - EntRemi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-surface-950 text-white min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Ambient Background -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[40%] -left-[20%] w-[80%] h-[80%] rounded-full bg-brand-500/20 blur-[120px]"></div>
        <div class="absolute top-[20%] -right-[20%] w-[60%] h-[60%] rounded-full bg-accent-500/20 blur-[120px]"></div>
    </div>

    <!-- Header -->
    <nav class="relative z-10 w-full backdrop-blur-md bg-surface-950/70 border-b border-surface-800/50 px-6 h-16 flex items-center justify-center">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-accent-500 flex items-center justify-center shadow-glow-sm">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z"/>
                </svg>
            </div>
            <span class="text-base font-bold tracking-tight">EntRemi Setup</span>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="relative z-10 flex-1 px-4 sm:px-6">
        {{ $slot }}
    </main>

</body>
</html>
