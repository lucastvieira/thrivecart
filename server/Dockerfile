FROM php:8.5-apache

RUN a2enmod rewrite headers \
    && pecl install xdebug-3.5.3 \
    && docker-php-ext-enable xdebug

COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html

EXPOSE 80
