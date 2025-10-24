#!/bin/sh

echo "Running composer install..."
composer install --no-interaction --prefer-dist

echo "Checking APP_KEY..."
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY not found, generating..."
    php artisan key:generate --force
fi

echo "Running migrations..."
php artisan migrate --force || true

echo "Starting Laravel app..."
php artisan serve --host=0.0.0.0 --port=8000
