#!/bin/bash

echo "🍽️  Speiseplan Docker Setup"
echo "═══════════════════════════════════════"

# Check Docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker nicht gefunden. Bitte Docker installieren."
    exit 1
fi

if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    echo "❌ Docker Compose nicht gefunden. Bitte Docker Compose installieren."
    exit 1
fi

# Create .env if not exists
if [ ! -f .env ]; then
    echo "📝 Erstelle .env aus .env.example..."
    cp .env.example .env
    echo "⚠️  Bitte .env bearbeiten mit deinen Production-Secrets!"
    sleep 2
fi

# Build and start
echo "🚀 Starte Docker Compose..."
docker compose down
docker compose build --no-cache
docker compose up -d

# Wait for services
echo "⏳ Warte auf Services..."
sleep 5

# Health checks
echo ""
echo "✓ Speiseplan läuft!"
echo ""
echo "📍 Services verfügbar unter:"
echo "   - Admin-Panel:    http://localhost:8081/admin"
echo "   - Kundenansicht:  http://localhost:8082/speiseplan"
echo "   - API:            http://localhost:3000/api/menu/2026/21"
echo ""
echo "💾 Datenbank:"
echo "   - Host: localhost"
echo "   - Port: 5432"
echo "   - User: speiseplan"
echo "   - Pass: (siehe .env)"
echo ""
echo "🛑 Zum Stoppen: docker compose down"
echo ""
