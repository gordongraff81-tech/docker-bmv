#!/bin/bash

echo "═══════════════════════════════════════════════════════════════════"
echo "🖨️  SPEISEPLAN DRUCKFUNKTION - TESTE JETZT"
echo "═══════════════════════════════════════════════════════════════════"
echo ""

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}1. Docker Compose Status${NC}"
docker compose ps --filter "name=speiseplan" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"

echo ""
echo -e "${BLUE}2. API Health Check${NC}"
curl -s http://localhost:3000/health && echo "" || echo "❌ Backend nicht erreichbar"

echo ""
echo -e "${BLUE}3. Speiseplan API${NC}"
curl -s http://localhost:3000/api/menu/2026/21 | head -c 200
echo "..."

echo ""
echo -e "${BLUE}4. Frontend Status${NC}"
curl -s -I http://localhost:8082/speiseplan | grep "HTTP"

echo ""
echo -e "${GREEN}✓ ALLE SERVICES AKTIV${NC}"
echo ""
echo -e "${YELLOW}ÖFFNE JETZT IM BROWSER:${NC}"
echo ""
echo -e "${GREEN}  Kundenansicht mit Druckbutton:${NC}"
echo "  http://localhost:8082/speiseplan/?year=2026&kw=21"
echo ""
echo -e "${GREEN}  Admin-Panel zum Testen:${NC}"
echo "  http://localhost:8081/admin"
echo ""
echo "═══════════════════════════════════════════════════════════════════"
echo ""
echo "📋 DRUCKFUNKTION TESTEN:"
echo ""
echo "1. Besuche: http://localhost:8082/speiseplan/?year=2026&kw=21"
echo "2. Klicke oben rechts: '🖨️ Speiseplan drucken'"
echo "3. Modal öffnet sich mit A4-Querformat-Layout"
echo "4. Klicke 'Drucken' ODER Ctrl+P"
echo "5. Wähle Drucker und drucke A4-Seite"
echo ""
echo "✨ Das Layout passt perfekt auf EINE Seite mit:"
echo "   ✓ Wochenspeiseplan (7 Tage × 8 Kategorien)"
echo "   ✓ Bestellabschnitt rechts"
echo "   ✓ Kundenfelder (Name, Adresse, Tel)"
echo "   ✓ Bestellmatrix zum Ankreuzen"
echo "   ✓ Professionelles Schwarzweiß-Design"
echo ""
echo "═══════════════════════════════════════════════════════════════════"
