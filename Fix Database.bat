@echo off
title ClassConnect - Fix Database
cd /d "%~dp0"

set PHP_EXE=php
if exist "C:\xampp\php\php.exe" set PHP_EXE=C:\xampp\php\php.exe
set MYSQL_EXE=C:\xampp\mysql\bin\mysql.exe

echo ClassConnect - Creating database and tables...
echo.

REM Create database (XAMPP default: user root, no password)
if exist "%MYSQL_EXE%" (
    "%MYSQL_EXE%" -u root -e "CREATE DATABASE IF NOT EXISTS classconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
    if errorlevel 1 (
        echo Could not create database. Make sure XAMPP MySQL is running.
        echo You can create it manually in phpMyAdmin: database name "classconnect"
        echo.
    ) else (
        echo Database "classconnect" is ready.
    )
) else (
    echo MySQL not found at XAMPP. Create database "classconnect" in phpMyAdmin, then run migrations below.
    echo.
)

echo Running migrations (create/update all tables)...
"%PHP_EXE%" artisan migrate --force
if errorlevel 1 (
    echo.
    echo Migration failed. Check:
    echo - XAMPP MySQL is started
    echo - .env has DB_DATABASE=classconnect, DB_USERNAME=root, DB_PASSWORD= (or your MySQL password)
    pause
    exit /b 1
)

echo.
echo Done. All ClassConnect tables are now in the classconnect database.
echo You can open phpMyAdmin: http://localhost/phpmyadmin
pause
