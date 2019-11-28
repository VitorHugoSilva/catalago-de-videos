#!/bin/bash

composer install
php artisan key:generate
php artisan migrate
chmod -R 777 .
php-fpm
