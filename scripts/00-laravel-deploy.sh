#!/usr/bin/env bash
set -e

echo "Installing Composer dependencies..."
composer install --no-interaction --optimize-autoloader --no-dev

echo "Installing NPM dependencies..."
npm install --ignore-scripts

echo "Building assets..."
npm run build

echo "Running migrations..."
php artisan migrate --force

echo "Deployment completed successfully!"
