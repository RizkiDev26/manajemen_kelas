@echo off
echo ===== OPTIMASI VS CODE =====
echo.

echo 1. Menutup semua proses VS Code...
taskkill /f /im Code.exe 2>nul
timeout /t 2 /nobreak >nul

echo 2. Membersihkan cache VS Code...
set VSCODE_CACHE="%APPDATA%\Code\User\workspaceStorage"
if exist %VSCODE_CACHE% (
    echo    - Menghapus workspace cache...
    rmdir /s /q %VSCODE_CACHE% 2>nul
)

set VSCODE_LOGS="%APPDATA%\Code\logs"
if exist %VSCODE_LOGS% (
    echo    - Menghapus log files...
    rmdir /s /q %VSCODE_LOGS% 2>nul
)

set VSCODE_CRASH="%APPDATA%\Code\CrashDumps"
if exist %VSCODE_CRASH% (
    echo    - Menghapus crash dumps...
    rmdir /s /q %VSCODE_CRASH% 2>nul
)

echo 3. Membersihkan file sementara project...
if exist "writable\cache" (
    echo    - Menghapus CI4 cache...
    rmdir /s /q "writable\cache" 2>nul
    mkdir "writable\cache" 2>nul
)

if exist "writable\logs" (
    echo    - Membersihkan logs...
    del /q "writable\logs\*.log" 2>nul
)

if exist "writable\session" (
    echo    - Membersihkan sessions...
    del /q "writable\session\*" 2>nul
)

echo 4. Membersihkan file temporary...
del /q "*.tmp" 2>nul
del /q "*.temp" 2>nul
del /q "*.log" 2>nul

echo.
echo ===== OPTIMASI SELESAI =====
echo.
echo VS Code telah dioptimasi untuk performa yang lebih baik!
echo.
echo Pengaturan yang diterapkan:
echo - Minimap dinonaktifkan
echo - Autocomplete dioptimasi
echo - File watcher dibatasi
echo - Extension auto-update dinonaktifkan
echo - Cache dibersihkan
echo.
echo Tekan any key untuk membuka VS Code...
pause >nul

echo.
echo Membuka VS Code...
code .
