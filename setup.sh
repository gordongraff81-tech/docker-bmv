#!/bin/bash
# ════════════════════════════════════════════════════════════════
# BMV Menüdienst – Lokales Setup
# Aufruf: bash setup.sh
# ════════════════════════════════════════════════════════════════
set -e

GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo ""
echo -e "${BLUE}╔══════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║    BMV Menüdienst – Lokales Setup            ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════╝${NC}"
echo ""

# ── Voraussetzungen prüfen ─────────────────────────────────────
check_cmd() {
  if ! command -v "$1" &> /dev/null; then
    echo -e "${RED}✗ $1 ist nicht installiert.${NC}"
    echo "  Bitte installieren: $2"
    exit 1
  fi
  echo -e "${GREEN}✓ $1${NC}"
}

echo "Prüfe Voraussetzungen..."
check_cmd docker "https://docs.docker.com/get-docker/"
if ! docker compose version &>/dev/null; then
  echo -e "${RED}✗ 'docker compose' nicht gefunden.${NC}"
  echo "  Bitte Docker Desktop oder das Compose-Plugin installieren."
  exit 1
fi
echo -e "${GREEN}✓ docker compose${NC}"
echo ""

# ── www-Verzeichnis prüfen ─────────────────────────────────────
if [ ! -f "www/index.php" ]; then
  echo -e "${YELLOW}⚠ www/index.php nicht gefunden.${NC}"
  echo "  Sicherstellen dass die BMV-Projektdateien in www/ liegen."
  echo ""
fi

# ── Datenordner anlegen ────────────────────────────────────────
echo "Erstelle Datenordner..."
mkdir -p www/data/speiseplaene
mkdir -p www/data/bestellungen
echo -e "${GREEN}✓ www/data/ bereit${NC}"
echo ""

# ── .env prüfen ────────────────────────────────────────────────
if [ ! -f ".env" ]; then
  echo -e "${YELLOW}⚠ .env nicht gefunden – erstelle aus .env.example${NC}"
  if [ -f ".env.example" ]; then
    cp .env.example .env
    echo -e "${GREEN}✓ .env angelegt${NC}"
  else
    echo -e "${RED}✗ .env.example fehlt – .env muss manuell angelegt werden.${NC}"
    exit 1
  fi
fi

# ── BMV_ADMIN_KEY prüfen ───────────────────────────────────────
if ! grep -q '^BMV_ADMIN_KEY=.\+' .env 2>/dev/null; then
  echo ""
  echo -e "${YELLOW}⚠ BMV_ADMIN_KEY ist nicht gesetzt.${NC}"
  echo "  Generiere sicheren Schlüssel..."
  if command -v openssl &>/dev/null; then
    GENERATED_KEY=$(openssl rand -hex 32)
    sed -i "s/^BMV_ADMIN_KEY=.*/BMV_ADMIN_KEY=${GENERATED_KEY}/" .env
    echo -e "${GREEN}✓ BMV_ADMIN_KEY automatisch gesetzt${NC}"
    echo -e "  ${BLUE}Key:${NC} ${GENERATED_KEY}"
    echo ""
    echo -e "  ${YELLOW}Diesen Key sicher aufbewahren – er wird für den Admin-Zugang benötigt.${NC}"
  else
    echo -e "${RED}  openssl nicht verfügbar.${NC}"
    echo "  Bitte manuell in .env setzen: BMV_ADMIN_KEY=<min-32-zeichen>"
    exit 1
  fi
fi
echo ""

# ── Docker starten ─────────────────────────────────────────────
echo "Starte Docker-Container..."
echo ""
docker compose build --quiet
docker compose up -d

echo ""
echo -e "${GREEN}════════════════════════════════════════════════${NC}"
echo -e "${GREEN}  ✓ BMV Entwicklungsumgebung läuft!             ${NC}"
echo -e "${GREEN}════════════════════════════════════════════════${NC}"
echo ""
echo -e "  ${BLUE}Hauptwebsite:${NC}    http://localhost:8080"
echo -e "  ${BLUE}Bestellseite:${NC}    http://localhost:8081"
echo -e "  ${BLUE}Kantine-Plan:${NC}    http://localhost:8082"
echo -e "  ${BLUE}Admin-Panel:${NC}     http://localhost:8083"
echo -e "  ${BLUE}E-Mail-Vorschau:${NC} http://localhost:8025"
echo ""
echo -e "  ${BLUE}Admin-Key:${NC}       $(grep '^BMV_ADMIN_KEY=' .env | cut -d= -f2)"
echo ""
echo "  Logs:    docker compose logs -f"
echo "  Stoppen: docker compose down"
echo ""
