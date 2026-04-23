# STAGE 1: The Builder
FROM php:8.2.4-alpine AS builder

# 1. Install Build Dependencies (Added icu-dev for intl)
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    openssl-dev \
    brotli-dev \
    icu-dev \
    zlib-dev \
    libstdc++

# 2. Install Extensions
# We use docker-php-ext-install for intl/opcache because they are bundled with PHP
RUN docker-php-ext-install intl opcache

# Install Swoole via PECL
RUN pecl install swoole && \
    docker-php-ext-enable swoole

# 3. Composer
WORKDIR /var/www
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# ---

# STAGE 2: The Runner (Optimized for 1GB VPS)
FROM php:8.2.4-alpine

# Install RUNTIME libraries only (Crucial for the app to actually start)
RUN apk add --no-cache \
    libstdc++ \
    icu-libs \
    openssl \
    brotli \
    zlib

# Copy extensions and configs from builder
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /var/www/vendor /var/www/vendor

WORKDIR /var/www
COPY . .

# Ensure SQLite directory is writable
RUN mkdir -p db && chmod -R 777 db

EXPOSE 8080

# Use exec form for proper signal handling
CMD ["php", "swoole_server.php"]