# EntRemi - WatchList Reminder

An enterprise-grade SaaS application for tracking TV Shows, Anime, Movies, and Dramas. 
EntRemi automatically fetches data from TMDB and Jikan, generates episode schedules, and sends automated Email and SMS reminders before episodes air.

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

---

## 🚀 Deployment Guide

EntRemi is designed to be easily deployed across any environment, from modern containerized platforms like Coolify to traditional shared hosting panels. Choose your preferred environment below.

### 1. 🐳 Coolify (Recommended - One Click)

EntRemi is natively compatible with [Coolify](https://coolify.io/) v4. By using the included `docker-compose.yml`, you can deploy the App, the Database, and all background queues with just a few clicks.

1. **Push to GitHub / GitLab**
   - Push your EntRemi code to a private GitHub or GitLab repository.
2. **Create Resource in Coolify**
   - In your Coolify dashboard, navigate to your Project -> Environment.
   - Click **+ New Resource** and select **Docker Compose** (Do not select Git Repository or Dockerfile).
   - Select your Git repository.
3. **Configure Environment Variables (Crucial)**
   - Before deploying, go to the **Environment Variables** tab for your new resource.
   - Add the following secrets:
     - `APP_KEY`: A base64 string (Generate one locally via `php artisan key:generate --show`, e.g. `base64:9aX+zM0lV5O7p8A9...`)
     - `DB_PASSWORD`: A strong password for the database user.
     - `DB_ROOT_PASSWORD`: A strong password for the database root user (will fallback to `DB_PASSWORD` if left empty).
     - `ADMIN_EMAIL`: The email for your default Super Admin account.
     - `ADMIN_PASSWORD`: The password for your default Super Admin account.
4. **Deploy**
   - Set your Domains in the Coolify UI.
   - Click **Deploy**.
   
*Note: Coolify automatically builds the app securely, provisions the MariaDB database, runs migrations, and starts the Web Server, Queue Worker, and Cron jobs. You can log in immediately using your `ADMIN_EMAIL` and `ADMIN_PASSWORD`.*

---

### 2. 🎛️ cPanel (Shared Hosting)

EntRemi features a **Zero-Command Web Installer**, meaning no SSH/Terminal access is needed.

1. **Upload the Files**
   - Compress your EntRemi project folder into a `.zip` file.
   - Open cPanel **File Manager** and upload the `.zip` file to your server (usually inside `public_html` or a dedicated subdomain folder).
   - Extract the `.zip` file.
2. **Set the Document Root**
   - In cPanel, go to **Domains** -> Find your domain -> Click **Manage**.
   - Update the Document Root to point to the `public` folder inside your extracted directory (e.g., `/public_html/entremi/public`).
3. **Database Creation**
   - Go to **MySQL Databases**. Create a new Database and a new User with a strong password. Add the user to the database with **All Privileges**.
4. **Web Installer**
   - Visit `https://yourdomain.com/install` in your browser.
   - Follow the visual installation wizard to connect the database and create your admin account.
5. **Setup Cron Jobs**
   - In cPanel, go to **Cron Jobs**.
   - Add a new cron job to run **Once Per Minute (`* * * * *`)**:
     ```bash
     /usr/local/bin/php /home/yourusername/public_html/entremi/artisan schedule:run >> /dev/null 2>&1
     ```
     *(Ensure the PHP path and artisan paths match your server's configuration).*

---

### 3. 🟣 hPanel (Hostinger)

1. **Upload and Extract**
   - Compress the EntRemi files to `.zip`.
   - In hPanel, go to **File Manager**, upload, and extract the files to your domain's folder (e.g., `domains/yourdomain.com/public_html`).
2. **Update Document Root**
   - In hPanel, go to **Websites** -> **Manage** -> **Advanced** -> **Folder Index**.
   - Point the default folder to `/public`. Alternatively, if you're using a subdomain, point the subdomain root to the `/public` directory inside your app folder.
3. **Database Creation**
   - Go to **Databases** -> **Management**. Create a new MySQL database, username, and password.
4. **Run the Installer**
   - Navigate to `https://yourdomain.com/install` and complete the UI wizard.
5. **Cron Jobs**
   - Go to **Advanced** -> **Cron Jobs**. Select **Custom** (Every Minute) and input:
     ```bash
     php /home/u123456789/domains/yourdomain.com/public_html/artisan schedule:run >> /dev/null 2>&1
     ```

---

### 4. ☁️ CloudPanel (VPS)

1. **Create the Site**
   - Log into CloudPanel, go to **Sites**, and click **Add Site** -> **Create a PHP Site**.
   - Enter your domain and select PHP 8.2 or 8.3.
2. **Database & Code Upload**
   - Click on your newly created site. Go to the **Databases** tab and add a new database.
   - Upload and extract the `.zip` file via CloudPanel's File Manager into the `htdocs/yourdomain.com/` folder.
3. **Configure Vhost & Root**
   - Go to the **Settings** tab -> **Vhost**.
   - Ensure the Document Root points to `/home/youruser/htdocs/yourdomain.com/public`.
4. **Permissions**
   - Ensure the file owner is set correctly via SSH or File Manager (`chown -R youruser:youruser /home/youruser/htdocs/yourdomain.com/`).
5. **Web Installer**
   - Visit `https://yourdomain.com/install` to finish setup.
6. **Cron Jobs**
   - In CloudPanel, go to **Cron Jobs** under your site settings. Add a new cron job running every minute (`* * * * *`) with the command:
     ```bash
     php /home/youruser/htdocs/yourdomain.com/artisan schedule:run
     ```

---

### 5. 💻 Manual / Full VPS (Ubuntu/Debian)

If you prefer full terminal control on a raw VPS:

**1. Install Prerequisites**
Ensure you have PHP 8.2+, Composer, Node.js/NPM, Nginx, and MariaDB/MySQL installed.

**2. Clone and Install**
```bash
git clone <your-repo-url> entremi
cd entremi
composer install --optimize-autoloader --no-dev
npm install && npm run build
```

**3. Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env` and fill in your database credentials and application URL.

**4. Database & Permissions**
```bash
php artisan migrate --force
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
php artisan storage:link
```

**5. Background Queues (Supervisor)**
Create `/etc/supervisor/conf.d/entremi.conf`:
```ini
[program:entremi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/entremi/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/entremi/storage/logs/worker.log
```
Run `sudo supervisorctl update` and `sudo supervisorctl start entremi-worker:*`.

**6. Cron Job**
Run `crontab -e` and add:
```bash
* * * * * cd /var/www/entremi && php artisan schedule:run >> /dev/null 2>&1
```
