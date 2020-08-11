FROM centos:centos7

ARG UID
ARG PHP=73

# install php
RUN  yum -y install epel-release && \
     yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm && \
     yum-config-manager --enable remi-php${PHP} && \
     yum -y update && \
     yum -y install php${PHP}-php-bcmath \
                    php${PHP}-php-cli \
                    php${PHP}-php-common \
                    php${PHP}-php-imap \
                    php${PHP}-php-intl \
                    php${PHP}-php-json \
                    php${PHP}-php-ldap \
                    php${PHP}-php-mbstring \
                    php${PHP}-php-mysqlnd \
                    php${PHP}-php-odbc \
                    php${PHP}-php-opcache \
                    php${PHP}-php-pdo \
                    php${PHP}-php-pecl-rpminfo.x86_64 \
                    php${PHP}-php-pecl-crypto \
                    php${PHP}-php-pecl-zip \
                    php${PHP}-php-sodium \
                    php${PHP}-php-xml \
                    php${PHP}-php-xmlrpc \
                    php${PHP}-runtime

# add repo management stuff
RUN yum -y install createrepo bzip2

RUN yum clean all

# Create ssh user
RUN useradd -G wheel -d /home/amc -m -p '$1$KYSABxpV$M6libTtfQGniU3gWapUT51' -u $UID -s /bin/bash amc

RUN ln -s /usr/bin/php${PHP} /usr/local/bin/php

USER betterprotect

CMD ["php", "artisan", "queue:work ", "--tries=3", "--timeout=120", "database", "--queue=task"]
