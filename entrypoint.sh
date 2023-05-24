#!/bin/bash

#### Entrypoint script for php container upon initialization ######

echo "Entrypoint Script Started" &&

echo "npm install" &&
npm install &&

echo "composer install" &&
composer install &&

echo "php artisan migrate" &&
php artisan migrate &&

echo "php artisan migrate testing db" &&
php artisan migrate --database=testing &&

echo "php-fpm" &&
php-fpm
