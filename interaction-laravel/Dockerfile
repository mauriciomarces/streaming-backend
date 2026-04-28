FROM php:8.2-fpm

# Paquetes necesarios
RUN apt-get update && apt-get install -y \
    git unzip libssl-dev pkg-config \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 👉 PRIMERO copiamos TODO el proyecto
COPY . .

# 👉 DESPUÉS instalamos dependencias
RUN composer install

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000