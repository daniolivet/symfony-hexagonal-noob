FROM php:8.1-apache

# Linux configuration
RUN apt-get update & apt-get upgrade -y

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    libonig-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip

# xDebug configuration
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY ./docker/apache/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


# Composer install
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-interaction --optimize-autoloader && \
    chown -R www-data:www-data var

# Symfony binary
COPY ./docker/bin/symfony /usr/local/bin/symfony

COPY . .

WORKDIR /var/www/html

EXPOSE 8000