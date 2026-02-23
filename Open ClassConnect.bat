@echo off
title ClassConnect Launcher
cd /d "%~dp0"

set "PHP_EXE=php"
if exist "C:\xampp\php\php.exe" set "PHP_EXE=C:\xampp\php\php.exe"

echo ClassConnect - Starting...
echo.

if not exist ".env" (
    echo Creating .env...
    copy .env.example .env
)
"%PHP_EXE%" artisan key:generate --force >nul 2>&1

if not exist "vendor" (
    echo First run: installing dependencies (composer)...
    call composer install --no-interaction
    if errorlevel 1 (
        echo ERROR: composer install failed. Install Composer from https://getcomposer.org
        pause
        exit /b 1
    )
)

echo Starting PHP server (new window)...
start "ClassConnect Server - KEEP THIS OPEN" cmd /k "call "%~dp0_run_server.bat""
echo.
echo Waiting 6 seconds for server...
timeout /t 6 /nobreak >nul
echo.
echo Opening browser...
start "" "http://127.0.0.1:8000"
echo.
echo Server URL: http://127.0.0.1:8000
echo If it did not open, copy that URL into your browser.
echo Keep the "ClassConnect Server" window open.
echo.
pause
