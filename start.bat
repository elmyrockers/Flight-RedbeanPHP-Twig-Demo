@echo off
chcp 65001>nul
title Swoole Http Server
color 0F

echo.
echo ╔══════════════════════════════════════╗
echo ║           Swoole HTTP Server         ║
echo ╚══════════════════════════════════════╝
echo.
echo 📁 %cd%
echo 🌐 http://localhost:8080
echo.
echo ▶ Starting...
echo.

wsl bash -c "docker run -it --rm --name flight-swoole-container -p 8080:8080 -v \"$(pwd):/var/www\" flight-swoole-app php /var/www/swoole_server.php"