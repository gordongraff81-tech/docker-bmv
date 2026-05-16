# BMV Menüdienst

PHP-basierte Website für Essen auf Rädern und Kantinenbetrieb im Raum Potsdam/Brandenburg.

Lokale Entwicklungsumgebung via Docker. Produktionsbetrieb auf Greatnet Shared Hosting.

---

## Schnellstart

### Voraussetzungen

| Software | Version | Download |
|----------|---------|----------|
| Docker Desktop | aktuell | https://www.docker.com/products/docker-desktop/ |
| Git | beliebig | https://git-scm.com/ |

### Setup (Mac / Linux)

```bash
git clone <repo-url>
cd docker-bmv
cp .env.example .env
# BMV_ADMIN_KEY in .env setzen (openssl rand -hex 32)
bash setup.sh
```

### Setup (Windows PowerShell)

```powershell
git clone <repo-url>
cd docker-bmv
Copy-Item .env.example .env
# BMV_ADMIN_KEY in .env setzen
docker compose build
docker compose up -d
```

---

## Lokale URLs

| URL | Entspricht | Beschreibung |
|-----|-----------|--------------|
| http://localhost:8080 | www.bmv-kantinen.de | Hauptwebsite |
| http://localhost:8081 | bestellen.bmv-kantinen.de | Speiseplan-Bestellung |
| http://localhost:8082 | kantinen-speiseplan.bmv-kantinen.de | Kantine-Speiseplan |
| http://localhost:8083 | /admin/ | Admin-Panel |
| http://localhost:8025 | — | MailHog E-Mail-Vorschau |

---

## Admin-Panel

1. http://localhost:8083 aufrufen
2. Den `BMV_ADMIN_KEY` aus `.env` als Passwort eingeben
3. KW wählen, Speiseplan einpflegen, speichern

**Key ändern:**
```bash
# In .env den Wert anpassen, dann:
docker compose restart php
```

---

## Nützliche Befehle

```bash
# Starten
docker compose up -d

# Stoppen
docker compose down

# Alle Logs
docker compose logs -f

# Nur PHP
docker compose logs -f php

# Container neu bauen (nach Dockerfile-Änderung)
docker compose build --no-cache && docker compose up -d

# In PHP-Container einsteigen
docker compose exec php sh
```

---

## Projektstruktur

```
docker-bmv/
├── docker-compose.yml       # Service-Definitionen (4x Nginx + PHP + MailHog)
├── Dockerfile.php           # PHP-FPM Build
├── setup.sh                 # Lokales Setup-Skript
├── deploy.sh                # Produktions-Deployment-Skript
├── .env                     # Lokale Konfiguration (nicht im Repo)
├── .env.example             # Vorlage Entwicklung
├── .env.production.example  # Vorlage Produktion
├── config/
│   ├── php.ini
│   ├── msmtprc
│   └── fpm-pool.conf
├── nginx/
│   ├── main.conf            # :8080
│   ├── bestellen.conf       # :8081
│   ├── kantine.conf         # :8082
│   └── admin.conf           # :8083
├── www/                     # Webroot (PHP-Dateien)
│   ├── index.php
│   ├── api/                 # Backend-Endpunkte
│   ├── admin/               # Admin-Panel
│   ├── includes/            # Shared PHP-Components
│   ├── assets/              # CSS, JS, Bilder
│   └── data/
│       ├── speiseplaene/    # JSON pro KW (nicht versioniert)
│       └── bestellungen/    # JSON pro Bestellung (nicht versioniert)
└── data/                    # Docker-Volume-Mount für Persistenz
```

---

## E-Mails testen

Alle E-Mails landen lokal in MailHog: http://localhost:8025

Kein echter Versand im lokalen Betrieb.

---

## Deployment

Vollständige Anleitung: [DEPLOYMENT.md](DEPLOYMENT.md)

API-Dokumentation: [www/api/README.md](www/api/README.md)

---

## Häufige Probleme

**Port belegt:**
```bash
# Mac/Linux
lsof -i :8080
# Windows
netstat -ano | findstr :8080
```
Ports in `docker-compose.yml` anpassen (z.B. `9080:80`).

**Permission-Fehler auf data/:**
```bash
docker compose exec php chmod -R 750 /var/www/html/data
```

**PHP 500:**
```bash
docker compose logs php
```

**PDF-Generierung schlägt fehl:**
```bash
docker compose exec php python3 -c "import reportlab; print('OK')"
```
