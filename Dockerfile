# Use official PHP 8.1 with Apache
FROM php:8.1-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql mbstring zip

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Copy the application code to Apache's web root
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Install Composer (copy from official composer image)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate Laravel optimized files (optional)
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Set permissions for storage and cache folders
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
