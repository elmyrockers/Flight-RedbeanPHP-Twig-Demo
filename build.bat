@echo off
setlocal
echo [WSL] Checking for existing image: flight-swoole-app...

:: Check if the image exists in Docker
wsl bash -c "docker images -q flight-swoole-app | grep -q ."

if %errorlevel% equ 0 (
    echo [SKIP] Image 'flight-swoole-app' already exists. 
    echo [INFO] Run your 'run.bat' to start the container.
    pause
    exit /b
)

echo [STATUS] Image not found. Starting build...

:: Execute the build inside WSL
wsl bash -c "cd $(wslpath '%cd%') && docker build -t flight-swoole-app ."

if %errorlevel% neq 0 (
    echo.
    echo [ERROR] Build failed. Check the output above.
    pause
    exit /b
)

echo [SUCCESS] Build complete. You can now use start.bat.
pause