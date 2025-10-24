#!/bin/sh
set -e

if [ -z "$APP_KEY" ]; then
    echo "APP_KEY not found, generating..."
    php artisan key:generate
fi

if [ ! -d "vendor" ]; then
    echo "vendor directory missing, running composer install..."
    composer install --no-dev --optimize-autoloader --no-scripts
fi

echo "Running migrations..."
php artisan migrate --force

php artisan serve --host=0.0.0.0 --port=8000
