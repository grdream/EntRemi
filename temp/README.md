# WatchList Reminder

A premium dark-mode WatchList & Episode Reminder application built with **Laravel 12**, Livewire 3, Alpine.js, and TailwindCSS 3. Track dramas, movies, and anime — and get automated email/SMS reminders before episodes air.

---

## ✨ Features

- 🎬 Watchlist with status tracking (Watching / Completed / Plan to Watch / On Hold / Dropped)
- 🔍 Auto-search from TMDB & Jikan (MyAnimeList) APIs
- 📅 Smart episode scheduling (daily, weekly, bi-weekly, etc.)
- 📧 Email reminders via custom SMTP (per user)
- 📱 SMS reminders via custom HTTP gateway
- 📊 Admin notification log dashboard with CSV export
- 📝 Personal notes per show
- 🔔 Reminder configuration (30min / 1hr / 2hr / 1-day before)
- 🌙 Dark mode preference stored in database
- 📥 Bulk JSON importer (MALSync / Anilist compatible)

---

## 🚀 Production Setup

### Requirements
- PHP 8.2+
- MySQL 8.0+ **or** MariaDB 10.6+
- Composer 2.x
- Node.js 18+ / npm
- Redis (optional, for queue)

---

### 1. Clone & Install

```bash
git clone https://github.com/youruser/EntrRemi.git
cd EntRemi
composer install --optimize-autoloader --no-dev
npm install && npm run build
```

---

### 2. Environment Configuration

Copy and edit the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```dotenv
APP_NAME="WatchList Reminder"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# ──────────────────── DATABASE ────────────────────
# For MySQL:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=entremi
DB_USERNAME=entremi_user
DB_PASSWORD=your_strong_password

# For MariaDB (same driver):
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=entremi
# DB_USERNAME=entremi_user
# DB_PASSWORD=your_strong_password

# ──────────────────── QUEUE ────────────────────
QUEUE_CONNECTION=database
# Or use Redis for production:
# QUEUE_CONNECTION=redis
# REDIS_HOST=127.0.0.1
# REDIS_PORT=6379

# ──────────────────── CACHE / SESSION ────────────────────
CACHE_STORE=file
SESSION_DRIVER=database
SESSION_LIFETIME=120

# ──────────────────── MAIL (System Default) ────────────────────
# Each user can override this with their own SMTP in Profile → Gateways
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="WatchList Reminder"

# ──────────────────── OPTIONAL APIS ────────────────────
TMDB_API_KEY=your_tmdb_read_access_token
ADMIN_EMAIL=admin@yourdomain.com

# ──────────────────── FILESYSTEM ────────────────────
FILESYSTEM_DISK=local
```

---

### 3. Database Setup (MySQL / MariaDB)

Create the database first:

```sql
CREATE DATABASE entremi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'entremi_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON entremi.* TO 'entremi_user'@'localhost';
FLUSH PRIVILEGES;
```

Run migrations and seeders:

```bash
php artisan migrate --force
php artisan db:seed --force   # optional: creates demo admin
```

---

### 4. Storage & Permissions

```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

### 5. Optimize for Production

```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

### 6. Cron Job (Scheduler)

Add to the server crontab (`crontab -e`):

```cron
# Run Laravel scheduler every minute (it decides what to run internally)
* * * * * php /var/www/entremi/artisan schedule:run >> /dev/null 2>&1
```

The scheduler is configured to check for upcoming episodes every 15 minutes and dispatch reminder jobs.

---

### 7. Queue Worker (Supervisor)

Install Supervisor and create `/etc/supervisor/conf.d/entremi-worker.conf`:

```ini
[program:entremi-worker]
command=php /var/www/entremi/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
directory=/var/www/entremi
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/entremi-worker.log
stopwaitsecs=3600
```

Activate it:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start entremi-worker:*
```

---

### 8. Nginx Config (Example)

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/entremi/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

---

## 🔄 Updates / Redeployment

```bash
git pull origin main
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan optimize
sudo supervisorctl restart entremi-worker:*
```

---

## 📊 Admin Access

Set `ADMIN_EMAIL` in `.env` to your admin email. The admin user sees the **Admin Console** in the sidebar for notification log observability and CSV export.

---

## 🗄️ Database Compatibility

All migrations use standard Laravel Blueprint types compatible with:
- ✅ **MySQL 5.7+** / **MySQL 8.0+**
- ✅ **MariaDB 10.3+** / **MariaDB 10.6+**
- ✅ **SQLite 3.x** (for local development only)

The `json()` column type is used for `channels`, `days_of_week`, `extra_params`, and `genres` — supported natively by MySQL 5.7.8+ and MariaDB 10.2.7+.

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2, Laravel 12 |
| Frontend | Blade, Livewire 3, Alpine.js |
| Styling | TailwindCSS 3 + Custom glassmorphism |
| Database | MySQL / MariaDB (SQLite for dev) |
| Queue | Laravel Queue (database driver) |
| Scheduler | Laravel Scheduler |
| APIs | TMDB, Jikan (MyAnimeList), YouTube |

---

## 📄 License

MIT License © 2026 WatchList Reminder
