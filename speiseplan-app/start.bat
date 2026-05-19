@echo off
echo 🍽️  Speiseplan Docker Setup (Windows)
echo ═══════════════════════════════════════

REM Check Docker
docker --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker nicht gefunden. Bitte Docker Desktop installieren.
    pause
    exit /b 1
)

REM Create .env if not exists
if not exist .env (
    echo 📝 Erstelle .env aus .env.example...
    copy .env.example .env
    echo ⚠️  Bitte .env bearbeiten mit deinen Production-Secrets!
    timeout /t 3
)

REM Build and start
echo 🚀 Starte Docker Compose...
docker compose down
docker compose build --no-cache
docker compose up -d

REM Wait for services
echo ⏳ Warte auf Services...
timeout /t 5

echo.
echo ✓ Speiseplan läuft!
echo.
echo 📍 Services verfügbar unter:
echo    - Admin-Panel:    http://localhost:8081/admin
echo    - Kundenansicht:  http://localhost:8082/speiseplan
echo    - API:            http://localhost:3000/api/menu/2026/21
echo.
echo 💾 Datenbank:
echo    - Host: localhost
echo    - Port: 5432
echo    - User: speiseplan
echo    - Pass: (siehe .env)
echo.
echo 🛑 Zum Stoppen: docker compose down
echo.
pause
