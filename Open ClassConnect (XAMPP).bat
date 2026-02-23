@echo off
title ClassConnect - Open in Browser (XAMPP)
cd /d "%~dp0"
echo.
echo XAMPP Apache must be RUNNING.
echo.
echo Project folder must be:  C:\xampp\htdocs\ClassConnect
echo (If you see "Not Found", copy this folder there.)
echo.
echo Opening: http://localhost/ClassConnect/
start "" "http://localhost/ClassConnect/"
timeout /t 2
