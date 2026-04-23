@echo off

wsl bash -c "docker stop flight-swoole-container && docker rm flight-swoole-container"