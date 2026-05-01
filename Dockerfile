# Use PHP 8.3 with Apache
FROM php:8.3-apache

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    apt-get clean

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP and Node dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev && \
    npm install --ignore-scripts && \
    npm run build

# Set Apache document root
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Set environment
ENV APP_ENV=production
ENV APP_DEBUG=false

# Start Apache
CMD ["apache2-foreground"]
