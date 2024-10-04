#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

# show schedule
echo "Showing schedule..."
php artisan schedule:list

# run schedule
echo "Running schedule..."
php artisan schedule:run

echo "Optimizing..."
php artisan optimize

echo "Publish cors..."
php artisan config:publish cors
# run queue
echo "Running queue..."
nohup php artisan queue:work --daemon &
echo "Queue is running..."

echo "Publishing cloudinary provider..."
php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tag="cloudinary-laravel-config"



