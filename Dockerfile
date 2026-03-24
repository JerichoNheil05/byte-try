FROM php:8.2-apache
WORKDIR /var/www/html

# Install PHP extensions if needed
RUN docker-php-ext-install pdo pdo_mysql mysqli


# Copy PHP app source
COPY . .

# Use CodeIgniter's front controller as the main entry point
COPY public/index.php /var/www/html/index.php

# Ensure Apache (www-data) owns all files
RUN chown -R www-data:www-data /var/www/html

# Set permissions for storage (if needed)
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80
