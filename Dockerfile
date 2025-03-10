# Stage 1: Build the application with dependencies
FROM php:8.3-fpm AS builder

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
    unzip && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the application source code
COPY . /var/www

# Set the correct file ownership
COPY --chown=www-data:www-data . /var/www

# Copy nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Stage 2: Production Image (only includes the application and runtime dependencies)
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Copy only the necessary files from the builder image
COPY --from=builder /var/www /var/www

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
