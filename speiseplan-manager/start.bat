@echo off
echo ═══════════════════════════════════════════════════════════════════
echo 🚀 SPEISEPLAN MANAGER - Docker Compose Starter
echo ═══════════════════════════════════════════════════════════════════
echo.

REM Check Docker
docker --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker nicht gefunden. Bitte Docker Desktop installieren.
    pause
    exit /b 1
)

REM Create .env if not exists
if not exist .env (
    echo 📝 Erstelle .env Datei...
    copy .env.example .env
    echo ✓ .env erstellt (Standard-Werte)
)

echo.
echo 🐳 Starte Docker Compose...
docker compose down 2>nul
docker compose up -d

echo.
echo ⏳ Warte auf Services (30 Sekunden)...
timeout /t 30 /nobreak

echo.
echo ✓ Services starten:
docker compose ps

echo.
echo ═══════════════════════════════════════════════════════════════════
echo 🎉 SPEISEPLAN MANAGER läuft!
echo ═══════════════════════════════════════════════════════════════════
echo.
echo 📍 Zugriff:
echo    Frontend:  http://localhost:3000
echo    Backend:   http://localhost:5000
echo    Nginx:     http://localhost
echo.
echo 📊 Datenbank:
echo    Host: localhost
echo    Port: 5432
echo    User: speiseplan
echo    Pass: speiseplan_secure (siehe .env)
echo.
echo 📋 Features:
echo    ✓ 8 Menü-Kategorien mit Standardpreisen
echo    ✓ 140+ vordefinierte Speisen
echo    ✓ Gerichte-Pool editierbar
echo    ✓ Wochenplaner mit Dropdown-Auswahl
echo    ✓ Individuelle Preisanpassung
echo.
echo 🛑 Zum Stoppen:
echo    docker compose down
echo.
echo ═══════════════════════════════════════════════════════════════════
pause
