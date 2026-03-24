FROM php:8.2-apache
WORKDIR /var/www/html

# Install PHP extensions if needed
RUN docker-php-ext-install pdo pdo_mysql mysqli





# Copy PHP app source
COPY . .
# Copy app directory to /var/www/app for CodeIgniter
COPY app /var/www/app
# Copy system directory to /var/www/system for CodeIgniter core
COPY system /var/www/system
# Copy writable directory to /var/www/writable for CodeIgniter
COPY writable /var/www/writable

# Use CodeIgniter's front controller as the main entry point
COPY public/index.php /var/www/html/index.php


# Ensure Apache (www-data) owns all files and writable directory
RUN chown -R www-data:www-data /var/www/html /var/www/app /var/www/system /var/www/writable


# Set permissions for storage (if needed)
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache /var/www/writable

EXPOSE 80
