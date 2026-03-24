FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    git \
    default-mysql-client \
    && docker-php-ext-install intl mysqli \
    && a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p /var/www/html/writable/cache \
    /var/www/html/writable/logs \
    /var/www/html/writable/session \
    /var/www/html/writable/uploads \
    /var/www/html/public/uploads \
    /var/www/html/public/uploads/product-thumbnails \
    && chown -R www-data:www-data /var/www/html/writable /var/www/html/public/uploads \
    && chmod -R 777 /var/www/html/writable /var/www/html/public/uploads
    
RUN printf "upload_max_filesize=50M\npost_max_size=64M\nmax_file_uploads=20\nmemory_limit=128M\nmax_execution_time=300\nmax_input_time=300\n" > /usr/local/etc/php/conf.d/uploads.ini

EXPOSE 80
CMD ["apache2-foreground"]
