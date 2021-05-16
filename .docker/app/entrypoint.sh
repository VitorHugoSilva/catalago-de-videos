#!/bin/bash
cp .env.example .env
cp .env.testing.example .env.testing
composer install
php artisan key:generate
php artisan key:generate --env=testing

chmod -R 777 .

php artisan migrate --seed


php-fpm
