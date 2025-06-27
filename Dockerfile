FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Copy custom Nginx config
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

RUN sed -i 's|listen = .*|listen = /run/php/php8.2-fpm.sock|' /usr/local/etc/php-fpm.d/www.conf

# Expose port
EXPOSE 80

# Start services
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]


RUN cat storage/logs/laravel.log || echo 'No Laravel log found'


