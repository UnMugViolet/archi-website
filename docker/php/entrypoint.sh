#!/usr/bin/env sh
set -eu

mkdir -p /var/www/html/storage/logs \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache

if [ "${RUN_MIGRATIONS:-false}" = "true" ] && [ "${RUN_SEEDERS:-false}" = "true" ]; then
    php artisan migrate:fresh --seed
elif [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

if [ "${APP_ENV:-production}" = "production" ]; then
    php artisan optimize
fi

php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
