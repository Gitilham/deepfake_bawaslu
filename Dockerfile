FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        mysqli \
        pdo \
        pdo_mysql \
        intl \
        gd \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache Rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Working Directory
WORKDIR /var/www/html

# Copy composer terlebih dahulu agar cache Docker optimal
COPY composer.json composer.lock ./

# Install dependency PHP
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

# Copy seluruh source code
COPY . .

COPY php-upload.ini /usr/local/etc/php/conf.d/99-upload.ini
COPY apache-detection.conf /etc/apache2/conf-available/detection.conf
RUN a2enconf detection
# Folder runtime harus dapat ditulis oleh proses Apache (www-data).
# public/uploads dibuat saat build agar named volume mewarisi ownership ini.
RUN mkdir -p writable public/uploads/profiles \
    && chown -R www-data:www-data writable public/uploads \
    && chmod -R 775 writable public/uploads

# Ubah DocumentRoot Apache ke folder public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

EXPOSE 80

CMD ["apache2-foreground"]
