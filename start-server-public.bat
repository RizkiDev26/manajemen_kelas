@echo off
echo ===============================================
echo  CODEIGNITER 4 - START SERVER (PUBLIC ACCESS)
echo ===============================================
echo.
echo Starting server on all network interfaces...
echo Server will be accessible from:
echo   - Local: http://localhost:8080
echo   - Network: http://192.168.50.3:8080
echo   - External: http://[YOUR_PUBLIC_IP]:8080
echo.
echo Press Ctrl+C to stop the server
echo ===============================================
echo.

php spark serve --host=0.0.0.0 --port=8080

pause
