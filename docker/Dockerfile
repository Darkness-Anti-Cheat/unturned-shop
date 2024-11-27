# Using image base PHP 7.4.3
FROM php:7.4.3-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    curl \
    libmemcached-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libssl-dev \
    libxrender1 \
    # libxext6 \
    zlib1g-dev \
    libmcrypt-dev \
    libicu-dev \
    git \
    unzip

RUN docker-php-ext-install pdo_mysql

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
# Configuramos composer para usar la versión 1, por defecto usa la 2
RUN composer self-update --1


RUN chown -R 755 /var/www
RUN mkdir /var/log/php
RUN touch /var/log/php/error.log
RUN chown -R 755 /var/log/php
RUN chown -R 755 /var/www
WORKDIR /var/www/html

