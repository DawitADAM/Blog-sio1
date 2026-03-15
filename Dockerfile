FROM webdevops/php-nginx:8.3

ENV WEB_DOCUMENT_ROOT=/app
ENV PHP_DATE_TIMEZONE=Europe/Paris
ENV NGINX_LISTEN_PORT=8080    ← ajoute cette ligne

COPY . /app

EXPOSE 8080
