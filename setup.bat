@echo off
cd /d "%~dp0"
php -r "if(PHP_VERSION_ID<80300){fwrite(STDERR,'PHP 8.3 or newer is required.');exit(1);}"
if errorlevel 1 goto fail
if not exist vendor\autoload.php (
 call composer install --prefer-dist --no-interaction
 if errorlevel 1 goto fail
)
if not exist .env copy .env.example .env >nul
if not exist database\database.sqlite type nul > database\database.sqlite
if not exist storage\framework\cache\data mkdir storage\framework\cache\data
if not exist storage\framework\sessions mkdir storage\framework\sessions
if not exist storage\framework\views mkdir storage\framework\views
if not exist storage\logs mkdir storage\logs
if not exist bootstrap\cache mkdir bootstrap\cache
if not exist public\images\uploads mkdir public\images\uploads
php artisan studio:prepare
if errorlevel 1 goto fail
php artisan migrate --seed
if errorlevel 1 goto fail
echo Setup complete. Create admin: php artisan studio:admin your-email@example.com
echo Start: php artisan serve
exit /b 0
:fail
echo Setup failed. Read the error above. Run php -v to check PHP.
exit /b 1
