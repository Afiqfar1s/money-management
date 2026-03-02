@echo off
REM Laravel Task Scheduler Runner for Windows
REM This file should be scheduled to run every minute in Windows Task Scheduler

cd /d C:\xampp\htdocs\money-management

REM Run Laravel's task scheduler
"C:\xampp\php\php.exe" artisan schedule:run >> storage\logs\scheduler.log 2>&1
