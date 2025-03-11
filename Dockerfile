# Use the official PHP image
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    procps && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the application source code
COPY . /var/www

# Set the correct file ownership
COPY --chown=www-data:www-data . /var/www

# Add /var/www to the list of safe directories
RUN git config --global --add safe.directory /var/www

# Copy nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Create PHP-FPM configuration directory and file
RUN mkdir -p /etc/php/8.3/fpm/pool.d && \
    echo "[www]" > /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "listen = 9000" >> /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "listen.owner = www-data" >> /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "listen.group = www-data" >> /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "listen.mode = 0660" >> /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "user = www-data" >> /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "group = www-data" >> /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "pm = dynamic" >> /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "pm.max_children = 5" >> /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "pm.start_servers = 2" >> /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "pm.min_spare_servers = 1" >> /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "pm.max_spare_servers = 3" >> /etc/php/8.3/fpm/pool.d/www.conf && \
    echo "chdir = /var/www" >> /etc/php/8.3/fpm/pool.d/www.conf

# Create PHP-FPM socket directory
RUN mkdir -p /var/run/php && \
    chown -R www-data:www-data /var/run/php

# Copy the start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose port 80 for Nginx
EXPOSE 80

# Start Nginx and PHP-FPM
CMD ["/start.sh"]
