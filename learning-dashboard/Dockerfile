FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    mariadb-client \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar archivos de dependencias
COPY composer.json composer.lock ./
COPY package.json package-lock.json* ./

# Instalar dependencias PHP
RUN composer install --no-scripts --no-autoloader --prefer-dist

# Instalar dependencias Node.js
RUN npm install

# Copiar el resto del proyecto
COPY . .

# Optimizar autoloader y permisos
RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Compilar assets
RUN npm run build

# Exponer puerto
EXPOSE 9000

CMD ["php-fpm"]