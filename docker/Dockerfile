FROM php:8.2-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libpng-dev libzip-dev zlib1g-dev default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql gd zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-progress

COPY . /var/www/html

RUN chmod -R 777 /var/www/html/storage
