FROM composer:2.5.4 as composer

WORKDIR /app

COPY . .

RUN composer install --optimize-autoloader \
				 --prefer-dist \
                 --ignore-platform-reqs

FROM php:8.1-fpm-bullseye as artisan

WORKDIR /app

COPY --from=composer  /app .

RUN php artisan VueTranslation:generate

FROM node:18-alpine as node

WORKDIR /app

COPY --from=artisan /app .

RUN npm ci
RUN npm run production

RUN rm package.json
RUN rm package-lock.json
RUN rm composer.lock
RUN rm webpack.mix.js
RUN rm -rf node_modules
RUN rm -rf docker

FROM php:8.0-fpm-bullseye as production

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt update && \
    apt -y install libzip-dev \
                zip \
                unzip \
                libcurl4-openssl-dev \
                libssl-dev \
                libzstd-dev \
                libldap2-dev \
                libzstd1

COPY "docker/fpm/config.ini" "$PHP_INI_DIR/conf.d"
COPY "docker/fpm/fpm.conf" "/usr/local/etc/php-fpm.d/"

RUN apt update && \
    apt -y dist-upgrade && \
    apt -y install libzip-dev \
                zip \
                unzip \
                libcurl4-openssl-dev \
                libssl-dev \
                libzstd-dev \
                libzstd1 \
                libgpgme-dev \
                libpng-dev \
                libjpeg-dev

RUN docker-php-ext-configure zip && \
    docker-php-ext-install zip

RUN docker-php-ext-configure curl && \
    docker-php-ext-install curl

RUN docker-php-ext-configure ldap && \
    docker-php-ext-install ldap

RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-enable pdo_mysql

RUN docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd && \
    docker-php-ext-enable gd

COPY --from=node --chown=www-data:www-data /app /var/www/html

RUN mkdir /var/www/.config/
RUN chown www-data:www-data /var/www/.config/

USER www-data

WORKDIR /var/www/html
