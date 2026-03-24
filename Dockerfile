FROM php:8.2-apache
WORKDIR /var/www/html

# Install PHP extensions if needed
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy PHP app source
COPY . .

# Ensure Apache (www-data) owns all files
RUN chown -R www-data:www-data /var/www/html

# Set permissions for storage (if needed)
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80
