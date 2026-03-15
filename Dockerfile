FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql \
    && sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-enabled/000-default.conf

COPY . /var/www/html/

EXPOSE 8080

CMD ["apache2-foreground"]
