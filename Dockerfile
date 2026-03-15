FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf \
    && sed -i 's/:80>/:8080>/g' /etc/apache2/sites-enabled/000-default.conf

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080
