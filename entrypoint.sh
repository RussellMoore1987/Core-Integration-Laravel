#!/bin/bash

#### Entrypoint script for php container upon initialization ######

echo "Entrypoint Script Started" &&

echo "npm install" &&
npm install &&

echo "composer install" &&
composer install &&

echo "php artisan migrate --seed" &&
php artisan migrate --seed &&

echo "php-fpm" &&
php-fpm
