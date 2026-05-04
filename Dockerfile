FROM php:7.4-fpm AS base

# Устанавливаем расширение pdo_mysql вместо pdo_sqlite
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    docker-php-ext-install pdo_mysql

# Копируем файлы сайта
COPY site /var/www/html
