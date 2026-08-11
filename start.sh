#!/bin/sh
set -e

mkdir -p /run/php

cd /var/www/backend
if [ ! -f .env ]; then
  cat > .env <<'EOF'
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

APP_LOCALE="${APP_LOCALE:-en}"
APP_FALLBACK_LOCALE="${APP_FALLBACK_LOCALE:-en}"
APP_FAKER_LOCALE="${APP_FAKER_LOCALE:-en_US}"

APP_MAINTENANCE_DRIVER="file"

BCRYPT_ROUNDS="${BCRYPT_ROUNDS:-12}"

LOG_CHANNEL="stack"
LOG_STACK="single"
LOG_DEPRECATIONS_CHANNEL="null"
LOG_LEVEL="${LOG_LEVEL:-debug}"

DB_CONNECTION="${DB_CONNECTION:-pgsql}"
DB_URL="${DB_URL:-${DATABASE_URL:-}}"
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE:-laravel}"
DB_USERNAME="${DB_USERNAME:-postgres}"
DB_PASSWORD="${DB_PASSWORD:-}"
DB_SSLMODE="${DB_SSLMODE:-prefer}"

SESSION_DRIVER="database"
SESSION_LIFETIME="120"
SESSION_ENCRYPT="false"
SESSION_PATH="/"
SESSION_DOMAIN="${SESSION_DOMAIN:-null}"

BROADCAST_CONNECTION="log"
FILESYSTEM_DISK="local"
QUEUE_CONNECTION="database"

CACHE_STORE="database"

REDIS_CLIENT="phpredis"
REDIS_HOST="${REDIS_HOST:-127.0.0.1}"
REDIS_PASSWORD="${REDIS_PASSWORD:-null}"
REDIS_PORT="${REDIS_PORT:-6379}"

MAIL_MAILER="log"
MAIL_SCHEME="null"
MAIL_HOST="${MAIL_HOST:-127.0.0.1}"
MAIL_PORT="${MAIL_PORT:-2525}"
MAIL_USERNAME="${MAIL_USERNAME:-null}"
MAIL_PASSWORD="${MAIL_PASSWORD:-null}"
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-hello@example.com}"
MAIL_FROM_NAME="${MAIL_FROM_NAME:-${APP_NAME}}"

AWS_ACCESS_KEY_ID="${AWS_ACCESS_KEY_ID:-}"
AWS_SECRET_ACCESS_KEY="${AWS_SECRET_ACCESS_KEY:-}"
AWS_DEFAULT_REGION="${AWS_DEFAULT_REGION:-us-east-1}"
AWS_BUCKET="${AWS_BUCKET:-}"
AWS_USE_PATH_STYLE_ENDPOINT="${AWS_USE_PATH_STYLE_ENDPOINT:-false}"

VITE_APP_NAME="${VITE_APP_NAME:-${APP_NAME}}"
EOF
fi

php artisan key:generate --force || true
php artisan migrate --force || true
php artisan storage:link || true
php artisan config:clear || true
php artisan optimize:clear || true

cd /var/www/frontend
PORT=3000 HOSTNAME=0.0.0.0 npm run start > /tmp/next.log 2>&1 &

if [ -z "$PORT" ]; then
  export PORT=10000
fi

envsubst '$PORT' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

php-fpm8.2 -F &
nginx -g 'daemon off;'
