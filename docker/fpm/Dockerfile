FROM composer:2 as composer

WORKDIR /app

COPY . .

RUN composer install --optimize-autoloader \
				 --prefer-dist \
                 --ignore-platform-reqs

FROM php:8.2-fpm-alpine as artisan

WORKDIR /app

COPY --from=composer  /app .

RUN php artisan event:cache && php artisan route:cache

FROM php:8.2-fpm-alpine as production
ARG FPM_MAX_CHILDREN=96
ARG FPM_START_SERVERS=48
ARG FPM_MIN_SPARE_SERVERS=24
ARG FPM_MAX_SPARE_SERVERS=48

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_ENABLE="1" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

RUN rm -f .env.testing && \
    rm -f .gitattributes && \
    rm -f .gitignore && \
    rm -f auth.json && \
    rm -f composer.lock && \
    rm -f phpstorm.config.js && \
    rm -f phpunit.xml && \
    rm -f readme.md && \
    rm -rf docker && \
    rm -rf .git && \
    rm -rf tests

RUN echo "clear_env = no" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm = dynamic" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.max_children = $FPM_MAX_CHILDREN" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.start_servers = $FPM_START_SERVERS" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.min_spare_servers = $FPM_MIN_SPARE_SERVERS" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "pm.max_spare_servers = $FPM_MAX_SPARE_SERVERS" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/fpm/opcache.ini /usr/local/etc/php-fpm.d/opcache.ini
COPY "docker/fpm/config.ini" "$PHP_INI_DIR/conf.d"

RUN apk add --update --no-cache --virtual .build-dependencies-in-virtual-world autoconf zip unzip && \
    # These need to be kept in the image for the extensions to function
    apk add --update --no-cache \
    libzip-dev  \
    curl-dev \
    openldap-dev \
    libpng-dev \
    jpeg-dev \
    icu-dev \
    gmp-dev \
    keepassxc \
    pcre-dev $PHPIZE_DEPS && \
    apk --no-cache add sudo

RUN docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install curl ldap pdo_mysql zip opcache gd pcntl intl gmp exif && \
    pecl install redis && \
    docker-php-ext-enable redis.so

RUN apk del .build-dependencies-in-virtual-world && rm -rf /tmp/pear

COPY --from=artisan --chown=root:www-data /app /var/www/html
COPY --chown=root:root --chmod=0755 docker/fpm/deploy.sh /deploy.sh
COPY --chown=root:root docker/fpm/sudo /etc/sudoers.d/bp
COPY --chown=root:root --chmod=0755 \
               docker/fpm/docker-dev-queue-listen-default-entrypoint  \
               docker/fpm/docker-dev-queue-listen-task-entrypoint \
               docker/fpm/docker-fpm-entrypoint \
               docker/fpm/docker-queue-default-entrypoint \
               docker/fpm/docker-queue-task-entrypoint \
               docker/fpm/docker-scheduler-entrypoint \
               /usr/local/bin/

RUN chown -R www-data:www-data /var/www/html/storage && \
    chown -R www-data:www-data /var/www/html/bootstrap/cache && \
    ln -s /var/www/html/storage/app/public /var/www/html/public/storage && \
    #
    chmod -R 750 /var/www/html && \
    chmod -R 770 /var/www/html/storage && \
    chmod -R 770 /var/www/html/bootstrap/cache && \
    #
    mkdir /var/www/.config && \
    chown www-data:www-data /var/www/.config && \
    chmod 770 /var/www/.config && \
    #
    mkdir /.config && \
    chown www-data:www-data /.config && \
    chmod 770 /.config && \
    #
    mkdir /root/.config && \
    chown www-data:www-data /root/.config && \
    chmod 770 /root/.config

USER www-data

WORKDIR /var/www/html

ENTRYPOINT ["docker-fpm-entrypoint"]
CMD ["php-fpm"]

FROM production as development
USER root

RUN apk add --update --no-cache build-base autoconf linux-headers && \
    #
    mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    #
    addgroup -g 1000 bp && \
    adduser -D -G bp -H -u 1000 "bp" && \
    #
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    #
    pecl install pcov && \
    docker-php-ext-enable pcov

COPY --from=composer /usr/bin/composer /usr/bin/composer

USER www-data
