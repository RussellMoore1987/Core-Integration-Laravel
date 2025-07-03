#!/bin/bash

#### Entrypoint script for php container upon initialization ######

echo "Entrypoint Script Started" &&

echo "npm install" &&
npm install &&

echo "composer install" &&
composer install &&

echo "php artisan migrate" &&
php artisan migrate --force &&

echo "php artisan migrate --database=testing" &&
php artisan migrate --database=testing --force &&

echo "php-fpm" &&
php-fpm
