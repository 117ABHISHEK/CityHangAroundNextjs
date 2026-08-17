# ---------- Stage 1: Build Frontend ----------
FROM node:20 AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# ---------- Stage 2: PHP ----------
FROM dunglas/frankenphp:1.4.4-php8.2

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \

    && docker-php-ext-install \
    pdo_pgsql \
    pdo_mysql \
    bcmath \
    intl \
    mbstring \
    zip \
    gd \
    exif \
    pcntl \

    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

COPY --from=node-builder /app/public/build ./public/build

RUN php artisan storage:link || true

RUN mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache

RUN chown -R www-data:www-data storage bootstrap/cache

ENV SERVER_NAME=:8080
ENV SERVER_ROOT=/app/public

EXPOSE 8080

CMD ["frankenphp","run","--config","/etc/caddy/Caddyfile"]