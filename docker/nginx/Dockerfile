ARG FPM_IMAGE
FROM $FPM_IMAGE as fpm
FROM nginx:1.23 as production

COPY --from=fpm /var/www/html /var/www/html

COPY docker/nginx/site.conf /etc/nginx/templates/default.conf.template
