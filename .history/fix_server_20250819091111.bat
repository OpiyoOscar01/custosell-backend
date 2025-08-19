@echo off
echo Stopping any running PHP processes...
taskkill /f /im php.exe 2>nul

echo Clearing Laravel cache...
php artisan config:clear
php artisan route:clear
php artisan cache:clear

echo Starting server...
php artisan serve --host=127.0.0.1 --port=8000

pause
