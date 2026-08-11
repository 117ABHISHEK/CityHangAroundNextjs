#!/bin/sh
set -e

mkdir -p /run/php

cd /var/www/backend
cp -n .env.example .env || true
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
