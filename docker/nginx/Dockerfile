ARG FPM_IMAGE
FROM $FPM_IMAGE as fpm
FROM nginxinc/nginx-unprivileged:1-alpine as production

USER root

RUN apk -U upgrade

COPY --from=fpm --chown=root:nginx /var/www/html /var/www/html

RUN chown -R nginx:nginx /var/www/html/storage && \
    chown -R nginx:nginx /var/www/html/bootstrap/cache && \
    chmod -R 750 /var/www/html && \
    chmod -R 770 /var/www/html/storage && \
    chmod -R 770 /var/www/html/bootstrap/cache && \
    ln -s /var/www/html/storage/app/public /var/www/html/public/storage

COPY docker/nginx/nginx.prod.conf /etc/nginx/nginx.conf

FROM production as development

COPY docker/nginx/nginx.dev.conf /etc/nginx/nginx.conf
