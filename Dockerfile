# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel app files to the container
COPY . .

# Change Apache DocumentRoot to Laravel's /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Set correct permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Install Composer dependencies (skip dev, ignore platform PHP version)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
