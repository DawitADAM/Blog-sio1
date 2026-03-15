FROM webdevops/php-nginx:8.3

ENV WEB_DOCUMENT_ROOT=/app
ENV PHP_DATE_TIMEZONE=Europe/Paris

COPY . /app

EXPOSE 8080
