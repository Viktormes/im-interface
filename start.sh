#!/bin/sh

# Create necessary directories and set permissions
mkdir -p /var/log/nginx /var/log/php8.3 /var/run/php
chown -R www-data:www-data /var/log/nginx /var/log/php8.3 /var/run/php
chown -R www-data:www-data /var/www

# Run Composer update
composer update

# Run Laravel artisan commands
php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force

# Start PHP-FPM in the background and log output
php-fpm -D

# Check if PHP-FPM started successfully
if [ $? -ne 0 ]; then
  echo "Failed to start PHP-FPM"
  exit 1
fi

# Start Nginx in the foreground and log output
nginx -g 'daemon off;'
