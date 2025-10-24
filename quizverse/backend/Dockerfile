FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts

EXPOSE 8000

ENTRYPOINT ["sh", "-c", "composer install --no-dev --optimize-autoloader && php artisan package:discover && php artisan serve --host=0.0.0.0 --port=8000"]
