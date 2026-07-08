# ==========================
# Stage 1 - Composer Dependencies
# ==========================
FROM composer:2.8 AS vendor

WORKDIR /app

# Copy seluruh source project
COPY . .

# Install dependency dan generate autoload
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
 && composer dump-autoload --optimize


# ==========================
# Stage 2 - Runtime
# ==========================
FROM php:8.3-fpm-alpine AS runtime

# Install runtime packages & PHP extensions
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
    && rm -rf /var/cache/apk/* /tmp/*

# Non-root user
RUN addgroup -S app && adduser -S app -G app

WORKDIR /var/www/html

# Copy seluruh aplikasi beserta vendor yang sudah dibuat
COPY --from=vendor --chown=app:app /app ./

# Konfigurasi PHP-FPM
RUN sed -i 's/^user = .*/user = app/' /usr/local/etc/php-fpm.d/www.conf \
 && sed -i 's/^group = .*/group = app/' /usr/local/etc/php-fpm.d/www.conf \
 && sed -i 's/^listen.owner = .*/listen.owner = app/' /usr/local/etc/php-fpm.d/www.conf \
 && sed -i 's/^listen.group = .*/listen.group = app/' /usr/local/etc/php-fpm.d/www.conf \
 && mkdir -p storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

USER app

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD php-fpm -t || exit 1

CMD ["php-fpm", "-F"]