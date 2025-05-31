#!/usr/bin/env sh

set -e

if [ "$APP_ENV" != "local" ]; then
    php artisan optimize
    php artisan db:show --silent && \
    php artisan migrate --force
else
    php artisan optimize:clear --except=cache
fi
php artisan storage:link

if [ "$#" -gt 0 ]; then
    exec php artisan "$@"
else
    exec php-fpm
fi
