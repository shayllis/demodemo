#!/bin/sh
cd /
php artisan migrate:fresh
php artisan passport:install --uuids
php artisan queue:table
php artisan migrate
php artisan db:seed --class=DatabaseSeeder
php artisan schedule:work
php artisan serve --host=0.0.0.0 --port=$APP_PORT
