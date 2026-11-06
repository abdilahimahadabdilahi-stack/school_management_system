@echo off
title School Management System - Laravel Server
cd /d "C:\xampp\htdocs\school_management_system"
start /b php artisan serve
timeout /t 3 >nul
start "" http://127.0.0.1:8000