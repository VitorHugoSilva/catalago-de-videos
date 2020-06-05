#!/bin/bash
composer install
php artisan key:generate
php artisan key:generate --env=testing
php artisan migrate:fresh --seed
chmod -R 777 .
php-fpm
