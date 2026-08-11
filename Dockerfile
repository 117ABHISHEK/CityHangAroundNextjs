# syntax=docker/dockerfile:1

FROM node:20-bookworm-slim AS frontend-build
WORKDIR /app/frontend
COPY frontend/package*.json ./
RUN npm install
COPY frontend/ .
RUN npm run build

FROM node:20-bookworm-slim
WORKDIR /var/www
ENV DEBIAN_FRONTEND=noninteractive \
    APP_ENV=production \
    APP_DEBUG=false \
    PORT=10000

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        nginx \
        curl \
        git \
        unzip \
        gettext-base \
        php8.2-fpm \
        php8.2-cli \
        php8.2-mbstring \
        php8.2-xml \
        php8.2-curl \
        php8.2-zip \
        php8.2-pgsql \
        php8.2-sqlite3 \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY backend /var/www/backend
WORKDIR /var/www/backend
RUN composer install --no-interaction --prefer-dist --no-dev \
    && cp .env.example .env \
    && php artisan key:generate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan storage:link \
    && chown -R www-data:www-data storage bootstrap/cache

COPY --from=frontend-build /app/frontend/.next /var/www/frontend/.next
COPY --from=frontend-build /app/frontend/public /var/www/frontend/public
COPY --from=frontend-build /app/frontend/package.json /var/www/frontend/package.json
COPY --from=frontend-build /app/frontend/next.config.ts /var/www/frontend/next.config.ts
COPY --from=frontend-build /app/frontend/node_modules /var/www/frontend/node_modules
COPY --from=frontend-build /app/frontend/tsconfig.json /var/www/frontend/tsconfig.json

COPY nginx/default.conf.template /etc/nginx/conf.d/default.conf.template
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 10000
CMD ["/start.sh"]
