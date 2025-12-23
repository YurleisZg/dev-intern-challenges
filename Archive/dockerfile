FROM php:8.1.33-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql


COPY ./src /var/www/html

RUN a2enmod rewrite

EXPOSE 80