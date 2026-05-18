FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libffi-dev \
    python3 \
    python3-pip \
    zip \
    unzip \
    msmtp \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd opcache

RUN pip3 install --break-system-packages --no-cache-dir reportlab

ENV APACHE_DOCUMENT_ROOT /var/www/html/www
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .

RUN mkdir -p /var/www/html/data/speiseplaene /var/www/html/data/bestellungen && \
    chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
