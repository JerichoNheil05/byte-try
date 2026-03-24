FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM php:8.2-apache
WORKDIR /var/www/html

# Install PHP extensions if needed
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy PHP app source
COPY . .

# Copy built frontend assets
COPY --from=frontend /app/public/build ./public/build

# Set permissions for storage (if needed)
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80
