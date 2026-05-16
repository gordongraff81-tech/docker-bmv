#!/bin/bash
# ════════════════════════════════════════════════════════════════
# BMV Menüdienst – Produktions-Deployment
#
# Verwendung:
#   bash deploy.sh [--host user@server] [--dry-run]
#
# Voraussetzungen:
#   - SSH-Zugang zum Produktionsserver
#   - rsync installiert
#   - .env.production auf dem Server bereits vorhanden
# ════════════════════════════════════════════════════════════════
set -euo pipefail

GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# ── Konfiguration ──────────────────────────────────────────────
REMOTE_HOST="${DEPLOY_HOST:-}"
REMOTE_PATH="${DEPLOY_PATH:-~/html}"
DRY_RUN=false

# ── Argumente parsen ───────────────────────────────────────────
while [[ $# -gt 0 ]]; do
  case $1 in
    --host)
      REMOTE_HOST="$2"
      shift 2
      ;;
    --dry-run)
      DRY_RUN=true
      shift
      ;;
    *)
      echo -e "${RED}Unbekanntes Argument: $1${NC}"
      echo "Verwendung: bash deploy.sh [--host user@server] [--dry-run]"
      exit 1
      ;;
  esac
done

echo ""
echo -e "${BLUE}╔══════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║    BMV Menüdienst – Produktions-Deploy       ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════╝${NC}"
echo ""

# ── Zielhost prüfen ────────────────────────────────────────────
if [ -z "$REMOTE_HOST" ]; then
  echo -e "${YELLOW}Kein Zielhost angegeben.${NC}"
  echo "  Aufruf: bash deploy.sh --host user@server"
  echo "  Oder: DEPLOY_HOST=user@server bash deploy.sh"
  exit 1
fi

echo -e "  ${BLUE}Zielhost:${NC}  $REMOTE_HOST"
echo -e "  ${BLUE}Zielpfad:${NC}  $REMOTE_PATH"
if [ "$DRY_RUN" = true ]; then
  echo -e "  ${YELLOW}Modus: DRY RUN — keine Änderungen werden durchgeführt${NC}"
fi
echo ""

# ── Voraussetzungen prüfen ─────────────────────────────────────
echo "Prüfe Voraussetzungen..."

if ! command -v rsync &>/dev/null; then
  echo -e "${RED}✗ rsync nicht gefunden.${NC}"
  exit 1
fi
echo -e "${GREEN}✓ rsync${NC}"

if [ ! -d "www" ]; then
  echo -e "${RED}✗ www/ Verzeichnis nicht gefunden.${NC}"
  exit 1
fi
echo -e "${GREEN}✓ www/ vorhanden${NC}"

# Sicherstellen dass keine .env in www/ liegt
if [ -f "www/.env" ]; then
  echo -e "${RED}✗ www/.env gefunden — darf nicht deployed werden!${NC}"
  exit 1
fi
echo -e "${GREEN}✓ keine .env in www/${NC}"
echo ""

# ── Security-Scan vor Deployment ──────────────────────────────
echo "Security-Scan..."

ISSUES=0

# Keine .phar-Dateien
if find www -name "*.phar" 2>/dev/null | grep -q .; then
  echo -e "${RED}✗ .phar-Dateien in www/ gefunden:${NC}"
  find www -name "*.phar"
  ISSUES=$((ISSUES+1))
fi

# Keine dump/sql-Dateien
if find www -name "*.sql" -o -name "*.dump" 2>/dev/null | grep -q .; then
  echo -e "${RED}✗ .sql/.dump-Dateien gefunden:${NC}"
  find www -name "*.sql" -o -name "*.dump"
  ISSUES=$((ISSUES+1))
fi

# Keine backup-Dateien
if find www -name "*.bak" -o -name "*.orig" -o -name "*.tmp" 2>/dev/null | grep -q .; then
  echo -e "${YELLOW}⚠ Backup/Temp-Dateien gefunden (werden ausgeschlossen):${NC}"
  find www -name "*.bak" -o -name "*.orig" -o -name "*.tmp"
fi

if [ $ISSUES -gt 0 ]; then
  echo -e "${RED}Deployment abgebrochen: $ISSUES kritische Probleme gefunden.${NC}"
  exit 1
fi

echo -e "${GREEN}✓ Security-Scan sauber${NC}"
echo ""

# ── Deployment ─────────────────────────────────────────────────
echo "Übertrage Dateien nach $REMOTE_HOST:$REMOTE_PATH ..."
echo ""

RSYNC_FLAGS="-avz --delete"
if [ "$DRY_RUN" = true ]; then
  RSYNC_FLAGS="$RSYNC_FLAGS --dry-run"
fi

rsync $RSYNC_FLAGS \
  --exclude='.env' \
  --exclude='.env.*' \
  --exclude='.git/' \
  --exclude='*.sqlite' \
  --exclude='vendor/' \
  --exclude='node_modules/' \
  --exclude='data/' \
  --exclude='*.bak' \
  --exclude='*.orig' \
  --exclude='*.tmp' \
  --exclude='*.phar' \
  --exclude='*.sql' \
  --exclude='*.dump' \
  --exclude='.DS_Store' \
  --exclude='Thumbs.db' \
  www/ "${REMOTE_HOST}:${REMOTE_PATH}/"

echo ""

if [ "$DRY_RUN" = true ]; then
  echo -e "${YELLOW}DRY RUN abgeschlossen — keine Dateien übertragen.${NC}"
  exit 0
fi

# ── Post-Deployment Checks ─────────────────────────────────────
echo "Post-Deployment Checks..."

# Schreibrechte auf data/ setzen (falls Verzeichnis auf Server existiert)
ssh "$REMOTE_HOST" "
  if [ -d ~/data ]; then
    chmod 750 ~/data ~/data/speiseplaene ~/data/bestellungen 2>/dev/null || true
    echo 'Rechte auf ~/data/ gesetzt.'
  fi
  chmod 644 ${REMOTE_PATH}/.htaccess 2>/dev/null || true
  find ${REMOTE_PATH} -name '*.php' -exec chmod 644 {} \; 2>/dev/null || true
  echo 'Dateirechte gesetzt.'
" || echo -e "${YELLOW}⚠ SSH Post-Deploy-Befehle fehlgeschlagen — manuell prüfen.${NC}"

echo ""
echo -e "${GREEN}════════════════════════════════════════════════${NC}"
echo -e "${GREEN}  ✓ Deployment abgeschlossen!                   ${NC}"
echo -e "${GREEN}════════════════════════════════════════════════${NC}"
echo ""
echo "  Jetzt prüfen:"
echo "  curl -I https://www.bmv-kantinen.de/.env   # Muss 403 liefern"
echo "  curl -I https://www.bmv-kantinen.de/data/  # Muss 403 liefern"
echo "  curl https://www.bmv-kantinen.de/api/get_week.php"
echo ""
