# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install required PHP extensions and system packages
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app files
COPY . .

# Install Composer dependencies (skip dev)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Fix Laravel file permissions
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
