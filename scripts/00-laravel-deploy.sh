#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Publishing cloudinary provider..."
php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tag="cloudinary-laravel-config"

# show schedule
echo "Showing schedule..."
php artisan schedule:list

# run schedule
echo "Running schedule..."
php artisan schedule:run

# run queue
echo "Running queue..."
php artisan queue:work --daemon

