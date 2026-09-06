#!/bin/sh

set -e

echo "Preparing runtime secrets..."

mkdir -p /tmp/secrets

if [ -n "$AIVEN_CA_BASE64" ]; then
    echo "$AIVEN_CA_BASE64" | base64 -d > /tmp/secrets/aiven-ca.pem
    chmod 644 /tmp/secrets/aiven-ca.pem
fi

if [ -n "$FIREBASE_CREDENTIALS_BASE64" ]; then
    echo "$FIREBASE_CREDENTIALS_BASE64" | base64 -d > /tmp/secrets/firebase-credentials.json
    chmod 644 /tmp/secrets/firebase-credentials.json
fi

echo "Preparing Laravel..."

php artisan migrate --force

php artisan config:cache
php artisan view:cache

echo "Starting Apache and Laravel Queue Worker..."

exec /usr/bin/supervisord \
    -c /etc/supervisor/conf.d/supervisord.conf