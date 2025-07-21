@echo off
echo ===============================================
echo  FIREWALL CONFIGURATION FOR CODEIGNITER 4
echo ===============================================
echo.
echo This script will configure Windows Firewall to allow
echo incoming connections on port 8080 for CodeIgniter 4
echo.
echo WARNING: This will open port 8080 to the public internet.
echo Only run this if you understand the security implications.
echo.
set /p confirm=Do you want to continue? (y/N): 

if /i "%confirm%" NEQ "y" (
    echo Operation cancelled.
    pause
    exit /b
)

echo.
echo Configuring Windows Firewall...
echo.

:: Add inbound rule for port 8080
netsh advfirewall firewall add rule name="CodeIgniter 4 Development Server" dir=in action=allow protocol=TCP localport=8080

echo.
echo Firewall rule added successfully!
echo Port 8080 is now open for incoming connections.
echo.
echo Your application should now be accessible from:
echo   - Local: http://localhost:8080
echo   - Network: http://192.168.50.3:8080
echo   - External: http://[YOUR_PUBLIC_IP]:8080
echo.
echo To remove this rule later, run:
echo netsh advfirewall firewall delete rule name="CodeIgniter 4 Development Server"
echo.
pause
