# EntRemi - WatchList Reminder

An enterprise-grade SaaS application for tracking TV Shows, Anime, Movies, and Dramas. 
EntRemi automatically fetches data from TMDB and Jikan, generates episode schedules, and sends automated Email and SMS reminders before episodes air.

---

## 🚀 Easy Web Installation (No Terminal Required)

EntRemi features a **Zero-Command Web Installer**. You don't need SSH or terminal access to install it. It works perfectly on shared hosting panels like **cPanel, Hostinger hPanel, and CloudPanel**.

### Step-by-Step Deployment Guide

1. **Upload the Files**
   - Compress your EntRemi project folder into a `.zip` file.
   - Go to your hosting panel's **File Manager** and upload the `.zip` file to your server (usually inside `public_html` or your domain's folder).
   - Extract the `.zip` file.

2. **Set the Document Root**
   - **cPanel:** Go to *Domains* -> Find your domain -> Click *Manage* -> Change the Document Root to `/public_html/entremi/public` (point it to the `public` folder inside the extracted directory).
   - **hPanel (Hostinger):** Go to *Websites* -> *Manage* -> *Advanced* -> *Folder Index* or use the *Subdomain/Addon Domain* settings to point to the `/public` folder.
   - **CloudPanel:** Go to *Sites* -> Select your site -> *Settings* -> *Vhost* -> Set the Document Root to `/home/user/htdocs/yourdomain.com/public`.

3. **Create a Database**
   - Go to the **MySQL Databases** section of your hosting panel.
   - Create a new Database.
   - Create a new Database User and generate a strong password.
   - Assign the User to the Database with **All Privileges**.

4. **Run the Web Installer**
   - Open your web browser and go to your domain: `https://yourdomain.com/install`
   - The EntRemi Web Installer will appear.
   - Follow the 5-step wizard to:
     1. Check system requirements (PHP 8.2+, extensions, folder permissions).
     2. Enter the Database credentials you created in Step 3.
     3. Set your Site Name and create your **Super Admin** account.
     4. (Optional) Configure System Email (SMTP).
     5. Click **Install**. The system will automatically configure `.env`, run migrations, and secure itself.

5. **Setup Cron Jobs (Automated Reminders)**
   - To send scheduled emails and SMS, Laravel needs a cron job to run every minute.
   - **cPanel/hPanel:** Go to *Cron Jobs* -> Add a new cron job -> Set it to run **Once Per Minute (`* * * * *`)** -> Enter the following command:
     ```bash
     /usr/local/bin/php /home/yourusername/public_html/entremi/artisan schedule:run >> /dev/null 2>&1
     ```
     *(Note: Replace `/usr/local/bin/php` with the correct path to PHP 8.2 on your server, and update the path to your `artisan` file).*

---

## 🐳 Coolify Deployment (True One-Click)

EntRemi is natively compatible with [Coolify](https://coolify.io/) v4. By using the included `docker-compose.yml`, you can deploy the App, the Database, and all background queues with literally a few clicks. The configuration handles linking the database automatically.

### How to deploy on Coolify:

1. **Push to GitHub**
   - Push your EntRemi code (including the `docker-compose.yml`, `Dockerfile` and `docker/` folder) to a private GitHub/GitLab repository.
2. **Create Resource in Coolify**
   - In your Coolify dashboard, create a new **Project** -> **Environment**.
   - Click **+ New Resource** and select **Docker Compose** (Not Git Repository or Dockerfile).
   - Select your Git repository.
3. **Deploy**
   - Coolify will load your `docker-compose.yml`.
   - Set your domains in the Coolify UI.
   - Click **Deploy**.
   
   
**What happens next?**
Coolify will build the app, spin up a secure MariaDB database alongside it, and wire them together. The app container will automatically run the database migrations and start the Web Server, Queue Worker, and Cron jobs. **You don't need to configure any environment variables or create a separate database.**

Once deployed, the Web Installer is automatically skipped and secured. You can log in immediately using the default auto-generated Super Admin account:
- **Email:** `admin@entremi.com`
- **Password:** `admin12345`

*(You can change these defaults before deploying by modifying the `ADMIN_EMAIL` and `ADMIN_PASSWORD` in your `docker-compose.yml`, or change the password directly from the Profile settings after logging in).*

---

<details>
<summary><strong>Advanced: Manual / VPS Installation (Terminal)</strong></summary>

If you have SSH access to a VPS (Ubuntu/Debian) and prefer to install manually using Composer and Artisan:

### 1. Prerequisites
- PHP 8.2+ (with extensions: pdo_mysql, mbstring, openssl, xml, zip, curl)
- MySQL 8.0+ or MariaDB 10.4+
- Composer
- Node.js & NPM (for frontend assets)
- Nginx or Apache

### 2. Install Dependencies
```bash
git clone <your-repo-url> entremi
cd entremi
composer install --optimize-autoloader --no-dev
npm install && npm run build
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env` and set your database credentials and `ADMIN_EMAIL`.

### 4. Database Migration & Setup
```bash
php artisan migrate --force
```

### 5. Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 6. Background Workers (Supervisor)
Create `/etc/supervisor/conf.d/entremi.conf`:
```ini
[program:entremi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/entremi/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/entremi/storage/logs/worker.log
stopwaitsecs=3600
```
Then run:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start entremi-worker:*
```

### 7. Cron Job
Run `crontab -e` and add:
```bash
* * * * * cd /path/to/entremi && php artisan schedule:run >> /dev/null 2>&1
```

</details>

---

## 💎 Features Overview

### SaaS Architecture
- **Multi-tenant:** Designed to handle multiple users with isolated watchlists.
- **Plan System:** `Free` (Email only) vs `Premium` (Email + SMS custom gateways).
- **Admin Dashboard:** Full user management, plan toggling, and system-wide setting control.

### Smart Reminders
- Automatic air-date calculation based on configurable scheduling patterns.
- Daily, Weekly, Bi-weekly, or custom interval scheduling.
- Resilient notification engine with history logs and failure tracking.

### Data Fetching
- Direct integration with **TMDB** (Movies, TV Shows, Dramas).
- Direct integration with **Jikan** (MyAnimeList unofficial API for Anime).
- Zero manual data entry: Search -> Click -> Added.

### Modern UI
- Deep **Dark Mode** & Light Mode integration using TailwindCSS.
- Alpine.js and Livewire 3 powered SPA-like responsiveness without page reloads.
- Glassmorphism design system.
