#!/bin/bash

#### Entrypoint script for php container upon initialization ######

echo "Entrypoint Script Started" &&

echo "npm install" &&
npm install &&

echo "composer install" &&
composer install &&

echo "php-fpm" &&
php-fpm
