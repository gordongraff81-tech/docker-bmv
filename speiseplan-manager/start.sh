#!/bin/bash

echo "═══════════════════════════════════════════════════════════════════"
echo "🚀 SPEISEPLAN MANAGER - Docker Compose Starter"
echo "═══════════════════════════════════════════════════════════════════"
echo ""

# Check Docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker nicht gefunden. Bitte Docker installieren."
    exit 1
fi

# Create .env if not exists
if [ ! -f .env ]; then
    echo "📝 Erstelle .env Datei..."
    cp .env.example .env
    echo "✓ .env erstellt (Standard-Werte)"
fi

echo ""
echo "🐳 Starte Docker Compose..."
docker compose down 2>/dev/null
docker compose up -d

echo ""
echo "⏳ Warte auf Services (30 Sekunden)..."
sleep 30

echo ""
echo "✓ Services starten:"
docker compose ps

echo ""
echo "═══════════════════════════════════════════════════════════════════"
echo "🎉 SPEISEPLAN MANAGER läuft!"
echo "═══════════════════════════════════════════════════════════════════"
echo ""
echo "📍 Zugriff:"
echo "   Frontend:  http://localhost:3000"
echo "   Backend:   http://localhost:5000"
echo "   Nginx:     http://localhost:80"
echo ""
echo "📊 Datenbank:"
echo "   Host: localhost"
echo "   Port: 5432"
echo "   User: speiseplan"
echo "   Pass: speiseplan_secure (siehe .env)"
echo ""
echo "📋 Features:"
echo "   ✓ 8 Menü-Kategorien mit Standardpreisen"
echo "   ✓ 140+ vordefinierte Speisen"
echo "   ✓ Gerichte-Pool editierbar"
echo "   ✓ Wochenplaner mit Dropdown-Auswahl"
echo "   ✓ Individuelle Preisanpassung"
echo ""
echo "🛑 Zum Stoppen:"
echo "   docker compose down"
echo ""
echo "═══════════════════════════════════════════════════════════════════"
