FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libpng-dev libzip-dev zlib1g-dev default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql gd zip \
    && a2enmod rewrite \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-progress

COPY . /var/www/html

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN chmod -R 777 /var/www/html/storage
