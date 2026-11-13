@echo off
title Rom and nd Shade Studio - Live HTTPS Tunnel
echo ========================================================
echo   Starting Rom and nd Shade Studio - Live Public HTTPS Tunnel
echo ========================================================
echo.
echo Make sure your Laravel server is running in another window:
echo     php artisan serve --port=8000
echo.
echo Connecting to Cloudflare Tunnel...
echo.
"%~dp0cloudflared.exe" tunnel --url http://127.0.0.1:8000
pause
