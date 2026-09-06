#!/bin/sh

set -e

echo "Preparing runtime secrets..."

mkdir -p /tmp/secrets


# =========================================================
# Rebuild Aiven CA certificate from environment chunks
# =========================================================

AIVEN_CA_BASE64_COMBINED="${AIVEN_CA_BASE64_1:-}${AIVEN_CA_BASE64_2:-}${AIVEN_CA_BASE64_3:-}${AIVEN_CA_BASE64_4:-}"

if [ -n "$AIVEN_CA_BASE64_COMBINED" ]; then
    printf '%s' "$AIVEN_CA_BASE64_COMBINED" \
        | base64 -d \
        > /tmp/secrets/aiven-ca.pem

    chmod 644 /tmp/secrets/aiven-ca.pem
fi


# =========================================================
# Rebuild Firebase credentials from environment chunks
# =========================================================

FIREBASE_CREDENTIALS_BASE64_COMBINED="${FIREBASE_CREDENTIALS_BASE64_1:-}${FIREBASE_CREDENTIALS_BASE64_2:-}${FIREBASE_CREDENTIALS_BASE64_3:-}${FIREBASE_CREDENTIALS_BASE64_4:-}${FIREBASE_CREDENTIALS_BASE64_5:-}${FIREBASE_CREDENTIALS_BASE64_6:-}${FIREBASE_CREDENTIALS_BASE64_7:-}${FIREBASE_CREDENTIALS_BASE64_8:-}"

if [ -n "$FIREBASE_CREDENTIALS_BASE64_COMBINED" ]; then
    printf '%s' "$FIREBASE_CREDENTIALS_BASE64_COMBINED" \
        | base64 -d \
        > /tmp/secrets/firebase-credentials.json

    chmod 600 /tmp/secrets/firebase-credentials.json
fi


echo "Preparing Laravel..."

php artisan migrate --force

php artisan config:cache
php artisan view:cache

echo "Starting Apache and Laravel Queue Worker..."

exec /usr/bin/supervisord \
    -c /etc/supervisor/conf.d/supervisord.conf
