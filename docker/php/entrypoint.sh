#!/usr/bin/env sh
set -eu

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
