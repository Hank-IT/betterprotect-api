FROM php:7.3-alpine

ARG UID

RUN apk add --virtual build \
        gcc \
        g++ \
        make \
        autoconf && \
    apk --update add \
        libxml2-dev \
        openldap-dev && \
    docker-php-ext-configure ldap && \
    docker-php-ext-install pdo_mysql ldap  && \
    apk del build && \
    rm -rf /var/cache/apk/*

RUN addgroup -g $UID betterprotect && \
    adduser -h /home/betterprotect -s /sbin/nologin -G amc -D -H -u $UID betterprotect

USER betterprotect

CMD ["php", "artisan", "websockets:serve"]
