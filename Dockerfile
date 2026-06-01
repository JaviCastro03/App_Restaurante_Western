FROM php:8.2-apache

RUN apt-get update
RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html

COPY . /var/www/html/

COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-xdebug.ini