# Build the frontend with Node
FROM node:20-bullseye AS frontend-builder
WORKDIR /app/frontend
COPY frontend/package.json frontend/package-lock.json frontend/tsconfig.json frontend/next.config.ts frontend/postcss.config.mjs ./
COPY frontend/public ./public
COPY frontend/app ./app
COPY frontend/src ./src
RUN npm install
RUN npm run build

# Build the backend with PHP and Composer
FROM php:8.2-cli-bullseye AS backend-builder
RUN apt-get update \
    && apt-get install -y git unzip curl ca-certificates \
    && rm -rf /var/lib/apt/lists/*
WORKDIR /app/backend
COPY backend/composer.json backend/composer.lock ./
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
COPY backend .
RUN composer dump-autoload --optimize

# Final runtime image with Node and PHP
FROM php:8.2-cli-bullseye
RUN apt-get update \
    && apt-get install -y curl gnupg ca-certificates sqlite3 php8.2-sqlite3 php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath php8.2-intl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www
COPY --from=frontend-builder /app/frontend /var/www/frontend
COPY --from=backend-builder /app/backend /var/www/backend

WORKDIR /var/www/frontend
RUN npm install --omit=dev

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 3000 9000
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
