FROM php:8.3-cli

# Instalacja zależności systemowych oraz rozszerzeń PHP
RUN apt-get update && apt-get install -y \
    git unzip zip \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libicu-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
        intl \
        xml \
        dom \
        xmlwriter

# Instalacja Xdebug (dla coverage)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Dodanie Composera z oficjalnego obrazu
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ustawienie katalogu roboczego
WORKDIR /app

# Kopiowanie plików projektu
COPY . .
COPY php.ini /usr/local/etc/php/php.ini
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Instalacja zależności PHP
RUN composer install

# Domyślne polecenie po starcie kontenera
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
