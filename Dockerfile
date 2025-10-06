# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git \
    && docker-php-ext-install zip pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer and install dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --no-dev --optimize-autoloader


# Set permissions for storage, cache, and database
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN chmod -R ug+rw /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN touch /var/www/html/database/database.sqlite && chown www-data:www-data /var/www/html/database/database.sqlite && chmod 664 /var/www/html/database/database.sqlite


# Set Apache DocumentRoot to /var/www/html/public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Install Node.js and npm, then build frontend assets (Vite)
RUN apt-get update && apt-get install -y nodejs npm
RUN npm install
RUN npm run build


# Run migrations and seed admin user on build
RUN php artisan migrate --force
RUN php artisan db:seed --force


# Publish Backpack assets for admin panel styling
RUN php artisan vendor:publish --provider="Backpack\\Base\\BackpackBaseServiceProvider" --tag=public --force

# Create storage symlink for asset loading
RUN php artisan storage:link

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
