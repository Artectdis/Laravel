# Use a lightweight production image with Nginx + PHP-FPM pre-installed
FROM richarvey/nginx-php-fpm:3.1.6

# Copy your application code into the container
COPY . .

# Install dependencies during build
RUN composer install --no-interaction --optimize-autoloader --no-dev && \
    npm install --ignore-scripts && \
    npm run build

# Render/Production specific configurations
ENV SKIP_COMPOSER 0
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel production settings
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root for the build process
ENV COMPOSER_ALLOW_SUPERUSER 1

# Start the built-in start script
CMD ["/start.sh"]
