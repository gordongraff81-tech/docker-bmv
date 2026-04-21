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
echo -e "${BLUE}║    BMV Menüdienst – Lokales Setup           ║${NC}"
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
check_cmd "docker" "docker compose plugin (Docker Desktop enthält es)"
# Test ob compose plugin verfügbar
if ! docker compose version &>/dev/null; then
  echo -e "${RED}✗ 'docker compose' nicht gefunden.${NC}"
  echo "  Bitte Docker Desktop oder das Compose-Plugin installieren."
  exit 1
fi
echo ""

# ── www-Verzeichnis prüfen ─────────────────────────────────────
if [ ! -d "www" ]; then
  echo -e "${YELLOW}⚠ Ordner 'www/' fehlt.${NC}"
  echo "  Bitte die BMV-Projektdateien nach 'www/' kopieren:"
  echo "  cp -r /pfad/zu/bmv-php/* www/"
  echo ""
  echo "  Erwartete Struktur:"
  echo "  www/"
  echo "  ├── index.php (oder index.html)"
  echo "  ├── api/"
  echo "  ├── includes/"
  echo "  ├── admin/"
  echo "  ├── pdf/"
  echo "  ├── data/speiseplaene/"
  echo "  └── ..."
  echo ""
  read -p "  Jetzt trotzdem fortfahren? (j/N) " yn
  if [[ "$yn" != "j" && "$yn" != "J" ]]; then
    exit 0
  fi
fi

# ── Datenordner anlegen ────────────────────────────────────────
echo "Erstelle Datenordner..."
mkdir -p www/data/speiseplaene
mkdir -p www/data/bestellungen
echo -e "${GREEN}✓ www/data/ bereit${NC}"
echo ""

# ── .env prüfen ────────────────────────────────────────────────
if [ ! -f ".env" ]; then
  echo -e "${YELLOW}⚠ .env nicht gefunden – erstelle Standard-.env${NC}"
  cat > .env << 'EOF'
BMV_ADMIN_KEY=bmv-admin-2025
MAIL_HOST=mailhog
MAIL_PORT=1025
EOF
fi

echo -e "${BLUE}Admin-Passwort aus .env:${NC}"
grep BMV_ADMIN_KEY .env
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
echo -e "  ${YELLOW}Admin-Passwort:${NC}  $(grep BMV_ADMIN_KEY .env | cut -d= -f2)"
echo ""
echo "  Logs:    docker compose logs -f"
echo "  Stoppen: docker compose down"
echo ""
