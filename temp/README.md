# WatchList Reminder Application

A modern, high-performance web application built with Laravel 11, Livewire 3, and Tailwind CSS. WatchList Reminder allows users to track their favorite Anime, TV Shows, and Dramas, automatically fetch data using TMDB and Jikan APIs, and receive automated Email or SMS notifications when new episodes air.

---

## 🌟 Key Features

* **Content Discovery:** Auto-fetch show metadata, posters, and episode counts from TMDB and Jikan.
* **Smart Scheduling:** Automatically generate release schedules based on timezone, interval (e.g., Weekly), and Air Time.
* **Notification Engine:** Background-processed Email and SMS reminders (via Twilio) dispatched automatically before episodes air.
* **Admin Observability:** Secure Admin dashboard to observe notification logs, success rates, and errors.
* **Data Portability:** JSON Importer tool to bulk-import Anime lists (e.g., from MALSync).
* **Premium UX:** Fully responsive, Livewire-powered SPA-like experience with persistent Dark Mode and Glassmorphism design.

---

## 🚀 Deployment Guide (CloudPanel / Ubuntu)

This guide assumes you are deploying to an Ubuntu server running [CloudPanel](https://www.cloudpanel.io/) or a similar Nginx/PHP-FPM stack.

### 1. Server Requirements
* **PHP:** >= 8.2 (8.3 recommended)
* **Extensions:** BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, cURL.
* **Database:** MySQL 8.0+ or MariaDB 10.5+
* **Composer:** Latest v2
* **Node.js:** v20+ & NPM (for compiling assets during build)

### 2. Initial Setup
Clone the repository into your site's `htdocs` directory and configure the environment:

```bash
cd /htdocs/yourdomain.com
git clone <repository_url> .

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies and compile assets
npm install
npm run build

# Copy environment file
cp .env.example .env

# Generate Application Key
php artisan key:generate
```

### 3. Environment Configuration (`.env`)

Edit the `.env` file to configure your production settings. 

**CRITICAL PRODUCTION SETTINGS:**
```dotenv
APP_NAME="WatchList Reminder"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Connection
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_secure_password

# Background Queues
QUEUE_CONNECTION=database

# APIs Configuration
TMDB_API_KEY=your_tmdb_api_key

# Twilio (For SMS)
TWILIO_SID=your_twilio_sid
TWILIO_AUTH_TOKEN=your_twilio_token
TWILIO_FROM_NUMBER=+1234567890

# SMTP/Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="WatchList Reminder"

# Admin Dashboard Access
ADMIN_EMAIL=your_admin_email@domain.com
```

### 4. Database Migration

Once your `.env` is configured, run the migrations:

```bash
php artisan migrate --force
```

---

## ⚙️ Background Workers (CRITICAL)

The application relies on Laravel's Scheduler and Queue system to check for upcoming episodes and dispatch notifications. These **must** be configured on your server.

### A. The Scheduler (Cron Job)
In CloudPanel, go to your site -> **Cron Jobs** and add a new cron job that runs **Every Minute** (`* * * * *`):

```bash
/usr/bin/php8.3 /htdocs/yourdomain.com/artisan schedule:run >> /dev/null 2>&1
```
*(Adjust `php8.3` to match the exact PHP version your site is using).*

### B. The Queue Worker (Supervisor)
The notifications (Email/SMS) are pushed to the `database` queue. We need a persistent worker to process them. 

1. SSH into your server as `root`.
2. Install Supervisor (if not already installed): `apt-get install supervisor`
3. Create a configuration file: `nano /etc/supervisor/conf.d/watchlist-worker.conf`
4. Paste the following configuration:

```ini
[program:watchlist-worker]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php8.3 /htdocs/yourdomain.com/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=clp-yourusername
numprocs=2
redirect_stderr=true
stdout_logfile=/htdocs/yourdomain.com/storage/logs/worker.log
stopwaitsecs=3600
```
*(Ensure `user=` is set to the specific Linux user that owns your Cloudpanel site, e.g., `clp-john`)*

5. Start the worker:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start watchlist-worker:*
```

---

## 🚀 Performance Optimization

Before launching to the public, cache all configurations to maximize speed:

```bash
php artisan optimize
php artisan view:cache
php artisan event:cache
```

If you ever change your `.env` variables, you must clear the cache:
```bash
php artisan optimize:clear
php artisan queue:restart
```

---

## 🔒 Security Notes
- CSRF protection is active on all `POST/PUT/DELETE` routes.
- Output escaping (`{{ }}`) is utilized across all Blade templates to prevent XSS.
- The Admin dashboard (`/admin/notifications`) is rigidly protected by middleware checking the `ADMIN_EMAIL` configured in the `.env`. Ensure this email matches your registered User account.
