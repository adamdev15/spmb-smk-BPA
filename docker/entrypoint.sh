#!/bin/sh
set -e

# Wait for database to be ready (optional, but good practice)
# You might need to install netcat (nc) in Dockerfile if you uncomment this
# echo "Waiting for database..."
# while ! nc -z db 3306; do
#   sleep 1
# done

# Fix permissions
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache

# Clear and cache configurations
echo "Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (force is needed for production)
echo "Running migrations..."
php artisan migrate --force

# Execute the main process (e.g., php-fpm)
echo "Starting PHP-FPM..."
exec "$@"
