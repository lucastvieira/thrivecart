FROM php:8.3-apache

RUN a2enmod rewrite headers

COPY apache.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

EXPOSE 80
