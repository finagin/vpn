#!/usr/bin/env sh

set -e

if [ "$#" -gt 0 ]; then
    exec php artisan "$@"
else
    if [ "$APP_ENV" != "local" ]; then
        php artisan optimize
        php artisan db:show --silent && \
        php artisan migrate --force

    else
        php artisan optimize:clear --except=cache
    fi
    php artisan storage:link
    exec php-fpm
fi
