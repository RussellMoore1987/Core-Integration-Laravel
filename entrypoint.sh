#!/bin/bash

#### Entrypoint script for php container upon initialization ######

echo "Entrypoint Script Started" &&

echo "npm install" &&
npm install &&

echo "composer install" &&
composer install &&

# chaned on 1/22/22
# echo "php artisan migrate:fresh --seed" &&
# php artisan migrate:fresh --seed &&
echo "php artisan migrate" &&
php artisan migrate &&

echo "php-fpm" &&
php-fpm
