#!/bin/bash

# Ensure storage has the right permissions in case the volume was mounted
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Generate .env file from example if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
    echo "Generating .env from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Wait for database to be ready
echo "Waiting for database connection..."
MAX_TRIES=15
TRIES=0
until mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1" > /dev/null 2>&1; do
  TRIES=$((TRIES+1))
  if [ $TRIES -eq $MAX_TRIES ]; then
    echo "Database connection timed out."
    break
  fi
  sleep 2
done
echo "Database is ready!"

# Only attempt migrations and cache clearing if an environment is configured
if [ -f .env ] || [ ! -z "$APP_KEY" ]; then
    echo "Running database migrations..."
    # Force run migrations
    php artisan migrate --force

    echo "Creating storage symlink..."
    php artisan storage:link

    echo "Running production optimizations..."
    php artisan optimize:clear
    php artisan optimize

    echo "Creating default Admin account (if not exists)..."
    php artisan tinker --execute="if(!\App\Models\User::where('email', env('ADMIN_EMAIL', 'admin@entremi.com'))->exists()) { \App\Models\User::create(['name' => 'Super Admin', 'email' => env('ADMIN_EMAIL', 'admin@entremi.com'), 'password' => bcrypt(env('ADMIN_PASSWORD', 'admin12345')), 'plan' => 'premium', 'is_active' => true, 'email_notifications' => true, 'sms_notifications' => false]); echo 'Admin created.\n'; }"
    
    # Secure the application by locking the web installer
    touch /var/www/html/storage/installed.lock
fi

# IMPORTANT: Fix permissions AGAIN after running artisan commands
# Otherwise, files like storage/logs/laravel.log will be owned by root, causing a 500 error!
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

echo "Starting Supervisor (Apache, Queue Worker, Cron)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
