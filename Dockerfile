FROM php:8.3-cli

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libxml2-dev libonig-dev libicu-dev libpq-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip intl xml

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
