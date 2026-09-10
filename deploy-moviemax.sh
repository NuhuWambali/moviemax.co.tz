#!/bin/bash
set -e
cd /home/admin/domains/moviemax.co.tz/app
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan view:cache
chown -R admin:admin storage bootstrap public
chmod -R 775 storage bootstrap
rm -f public/storage
php artisan storage:link
ln -sfn /home/admin/domains/moviemax.co.tz/app/public /home/admin/domains/moviemax.co.tz/public_html
echo 'MOVIEMAX deployment complete'