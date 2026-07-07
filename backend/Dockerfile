# ==========================
# Stage 1 - Install Composer Dependencies
# ==========================
FROM composer:2.8 AS vendor

WORKDIR /app

# Copy dependency manifest terlebih dahulu agar Docker cache bekerja optimal
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --optimize-autoloader \
    --classmap-authoritative

# ==========================
# Stage 2 - Runtime
# ==========================
FROM php:8.3-fpm-alpine AS runtime

# Install runtime & PHP extensions
RUN apk add --no-cache \
        bash \
        icu-libs \
        libzip \
        oniguruma \
        tzdata \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install \
        bcmath \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        zip \
    && apk del .build-deps \
    && rm -rf /tmp/* /var/cache/apk/*

# Buat user non-root
RUN addgroup -S app && adduser -S app -G app

WORKDIR /var/www/html

# Copy dependency
COPY --from=vendor /app/vendor ./vendor

# Copy source code dengan ownership langsung
COPY --chown=app:app . .

# Konfigurasi PHP-FPM
RUN sed -i 's/^user = .*/user = app/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/^group = .*/group = app/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/^listen.owner = .*/listen.owner = app/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/^listen.group = .*/listen.group = app/' /usr/local/etc/php-fpm.d/www.conf \
    && mkdir -p storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Jalankan sebagai non-root
USER app

EXPOSE 9000

# Health check sederhana untuk PHP-FPM
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD php-fpm -t || exit 1

CMD ["php-fpm", "-F"]