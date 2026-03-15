FROM webdevops/php-nginx:8.3

ENV WEB_DOCUMENT_ROOT=/app
ENV PHP_DATE_TIMEZONE=Europe/Paris

COPY . /app
COPY nginx.conf /opt/docker/etc/nginx/vhost.common.d/10-default.conf

RUN chmod -R 755 /app

EXPOSE 80
