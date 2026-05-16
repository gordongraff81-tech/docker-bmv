# ── Build Stage ──────────────────────────────────────────────
FROM php:8.1-fpm-alpine AS builder

# Build-Abhängigkeiten für PHP Extensions
RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libffi-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd opcache

# ── Runtime Stage ────────────────────────────────────────────
FROM php:8.1-fpm-alpine

# WICHTIG: Runtime-Bibliotheken für GD (müssen in der Runtime vorhanden sein)
RUN apk add --no-cache \
    freetype \
    libjpeg-turbo \
    libpng \
    python3 \
    py3-pip \
    zip \
    unzip \
    msmtp \
    libffi && \
    pip3 install --break-system-packages --no-cache-dir reportlab

# Extensions vom Builder kopieren
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Konfiguration
COPY config/msmtprc /etc/msmtprc
RUN chmod 600 /etc/msmtprc
COPY config/php.ini /usr/local/etc/php/conf.d/bmv.ini
COPY config/fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html

RUN mkdir -p /var/www/html/data/speiseplaene /var/www/html/data/bestellungen && \
    chown -R www-data:www-data /var/www/html/data

EXPOSE 9000
CMD ["php-fpm"]