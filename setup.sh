#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"
php -r 'if(PHP_VERSION_ID<80300){fwrite(STDERR,"PHP 8.3 or newer is required.\n");exit(1);}'
if [ ! -f vendor/autoload.php ]; then composer install --prefer-dist --no-interaction; fi
if [ ! -f .env ]; then cp .env.example .env; fi
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache public/images/uploads
if [ ! -f database/database.sqlite ]; then touch database/database.sqlite; fi
php artisan studio:prepare
php artisan migrate --seed
printf '\nSetup complete. Create an admin with: php artisan studio:admin your-email@example.com\nStart with: php artisan serve\n'
