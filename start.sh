#!/bin/sh

set -e  # Exit script on any error

echo "Starting setup..."

# Ensure necessary directories exist and set correct permissions
mkdir -p /var/log/nginx /var/log/php8.3 /var/run/php
chown -R www-data:www-data /var/log/nginx /var/log/php8.3 /var/run/php /var/www

# Ensure PHP-FPM socket directory exists
mkdir -p /var/run/php
chown -R www-data:www-data /var/run/php

# Run Laravel setup (if applicable)
if [ -f artisan ]; then
    echo "Running Laravel setup..."
    php artisan key:generate --force
    php artisan migrate --force
    php artisan db:seed --force
    echo "Laravel setup complete."
else
    echo "No Laravel setup found, skipping migrations."
fi

# Start PHP-FPM in the background
php-fpm -D
sleep 3

if ! pgrep php-fpm > /dev/null; then
  echo "❌ PHP-FPM failed to start!"
  exit 1
fi
echo "✅ PHP-FPM started successfully."

# Start Nginx in the foreground
echo "Starting Nginx..."
exec nginx -g 'daemon off;'
