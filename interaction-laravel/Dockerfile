FROM php:8.3-fpm

# Instalar dependencias del sistema necesarias para compilar extensiones
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libssl-dev \
    pkg-config \
    build-essential \
    autoconf \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensión MongoDB desde PECL
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000