@echo off

wsl bash -c "docker stop flight-swoole-container 2>nul && docker rm flight-swoole-container 2>nul"