# ── Build Stage ──────────────────────────────────────────────
FROM php:8.3-fpm-alpine AS builder

# Install build dependencies
RUN apk add --no-cache --virtual .build-deps \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    python3-dev

# Install persistent dependencies
RUN apk add --no-cache \
    python3 \
    py3-pip \
    zip \
    unzip \
    msmtp \
    libffi

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd opcache

# Install Python packages
RUN pip3 install --break-system-packages --no-cache-dir reportlab

# ── Runtime Stage ────────────────────────────────────────────
FROM php:8.3-fpm-alpine

# Copy PHP extensions from builder
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Install runtime dependencies only
RUN apk add --no-cache \
    python3 \
    py3-pip \
    zip \
    unzip \
    msmtp \
    libffi && \
    pip3 install --break-system-packages --no-cache-dir reportlab

# Copy application config
COPY config/msmtprc /etc/msmtprc
RUN chmod 600 /etc/msmtprc

COPY config/php.ini /usr/local/etc/php/conf.d/bmv.ini
COPY config/fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html

# Create data directories with correct permissions
RUN mkdir -p /var/www/html/data/speiseplaene \
             /var/www/html/data/bestellungen && \
    chown -R www-data:www-data /var/www/html/data

# Health check - test PHP-FPM availability
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD wget --quiet --tries=1 --spider http://127.0.0.1:9000/ || exit 1

EXPOSE 9000

CMD ["php-fpm"]
