#!/bin/sh

set -e

echo "Preparing Laravel..."

php artisan migrate --force

php artisan config:cache
php artisan view:cache

echo "Starting Apache and Laravel Queue Worker..."

exec /usr/bin/supervisord \
    -c /etc/supervisor/conf.d/supervisord.conf