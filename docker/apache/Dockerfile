FROM php:7.3-apache-buster

ARG UID

RUN apt update

RUN apt -y install libldap2-dev libxml2-dev

RUN docker-php-ext-configure curl json pdo_mysql opcache readline gd mbstring xml ldap

RUN docker-php-ext-install curl json pdo_mysql opcache readline gd mbstring xml ldap

RUN a2enmod proxy_wstunnel headers rewrite ssl

RUN userdel www-data && \
    groupadd -g $UID www-data && \
    useradd -b /home/www-data -s /sbin/nologin -g www-data -u 1001 www-data
