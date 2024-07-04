#!/bin/sh

sudo -Eu www-data php /var/www/html/artisan migrate --force --no-interaction
sudo -Eu www-data php /var/www/html/artisan db:seed --force --no-interaction
