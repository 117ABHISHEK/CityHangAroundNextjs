#!/usr/bin/env bash
set -e

# Set defaults
cd /var/www/frontend

# Ensure Laravel env exists
if [ ! -f /var/www/backend/.env ]; then
  cp /var/www/backend/.env.example /var/www/backend/.env
fi

cd /var/www/backend
php artisan key:generate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache || true

# Run frontend and backend together
cd /var/www/frontend
npm run start &

cd /var/www/backend
php -S 0.0.0.0:9000 -t public
