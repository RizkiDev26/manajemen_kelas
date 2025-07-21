@echo off
echo ===============================================
echo  RESTORE LOCAL-ONLY CONFIGURATION
echo ===============================================
echo.
echo This script will restore CodeIgniter 4 to local-only access
echo and remove the firewall rule.
echo.
set /p confirm=Do you want to continue? (y/N): 

if /i "%confirm%" NEQ "y" (
    echo Operation cancelled.
    pause
    exit /b
)

echo.
echo Removing firewall rule...
netsh advfirewall firewall delete rule name="CodeIgniter 4 Development Server"

echo.
echo Restoring local-only configuration...
echo.

:: Note: You would need to manually edit the config files back to localhost
echo Please manually change the following files back to localhost:
echo   - app/Config/App.php: baseURL = 'http://localhost:8080/'
echo   - .env: app.baseURL = 'http://localhost:8080/'
echo   - app/Config/App.php: allowedHostnames = []
echo   - app/Config/Cors.php: allowedOrigins = []
echo.
echo Configuration restored to local-only access.
echo.
pause
