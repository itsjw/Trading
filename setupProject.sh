#!/usr/bin/env bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
chmod 777 -R storage/
* * * * * php artisan schedule:run >> /dev/null 2>&1
exit