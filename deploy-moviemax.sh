#!/bin/bash
# MOVIEMAX deploy script — run by GitHub Actions via SSH
set -e

cd /home/admin/domains/moviemax.co.tz/public_html

# Pull the latest code
git pull origin main

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear & rebuild caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan view:cache

# Make sure storage + bootstrap/cache are writable
chown -R admin:admin storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "MOVIEMAX deployment complete ✔"