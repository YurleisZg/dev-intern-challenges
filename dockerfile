FROM php:8.1.33-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

RUN a2enmod rewrite

# Install composer dependencies for FirstChallenge
WORKDIR /var/www/html/Elkin/Challenge/FirstChallenge
RUN composer install --no-interaction --optimize-autoloader

# Reset working directory
WORKDIR /var/www/html

EXPOSE 80