# Use PHP 8.4 with Apache
FROM php:8.4-apache

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip gd

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    apt-get clean

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

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

# Create folders and set permissions
RUN mkdir -p storage/logs bootstrap/cache && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# Start Apache
CMD ["apache2-foreground"]
