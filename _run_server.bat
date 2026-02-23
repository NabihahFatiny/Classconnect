@echo off
cd /d "%~dp0"
title ClassConnect Server - KEEP THIS OPEN
if exist "C:\xampp\php\php.exe" (
    "C:\xampp\php\php.exe" artisan serve
) else (
    php artisan serve
)
echo.
echo Server stopped. You can close this window.
pause
