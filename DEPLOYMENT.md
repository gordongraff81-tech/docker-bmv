# BMV Menüdienst – Deployment-Anleitung

## Inhaltsverzeichnis

1. [Lokale Entwicklung (Docker)](#1-lokale-entwicklung-docker)
2. [Docker Compose Referenz](#2-docker-compose-referenz)
3. [Shared Hosting Produktion (Greatnet)](#3-shared-hosting-produktion-greatnet)
4. [Vercel Test-Deployment](#4-vercel-test-deployment)
5. [SMTP-Konfiguration](#5-smtp-konfiguration)
6. [Dateirechte](#6-dateirechte)
7. [JSON-Speicher-Struktur](#7-json-speicher-struktur)
8. [API-Endpunkte](#8-api-endpunkte)
9. [Sicherheitshinweise](#9-sicherheitshinweise)
10. [Troubleshooting](#10-troubleshooting)

---

## 1. Lokale Entwicklung (Docker)

### Voraussetzungen

- Docker Desktop (Windows / Mac / Linux)
- `openssl` für Key-Generierung

### Ersteinrichtung

```bash
git clone <repo-url>
cd docker-bmv

# .env aus Vorlage anlegen
cp .env.example .env

# Sicheren Admin-Key generieren und in .env eintragen
openssl rand -hex 32
# Ausgabe als BMV_ADMIN_KEY= in .env setzen

# Setup-Skript ausführen (prüft Abhängigkeiten, startet Container)
bash setup.sh
```

### Erreichbare Dienste

| Port | Container | Entspricht live |
|------|-----------|----------------|
| 8080 | nginx-main | www.bmv-kantinen.de |
| 8081 | nginx-bestellen | bestellen.bmv-kantinen.de |
| 8082 | nginx-kantine | kantinen-speiseplan.bmv-kantinen.de |
| 8083 | nginx-admin | Admin-Panel |
| 8025 | mailhog | MailHog Web-UI |

---

## 2. Docker Compose Referenz

### Starten / Stoppen

```bash
# Starten (im Hintergrund)
docker compose up -d

# Mit Build-Schritt (nach Dockerfile-Änderungen)
docker compose up -d --build

# Stoppen
docker compose down

# Stoppen und alle Volumes entfernen
docker compose down -v
```

### Logs

```bash
# Alle Container
docker compose logs -f

# Einzelne Container
docker compose logs -f php
docker compose logs -f nginx-main
docker compose logs -f mailhog
```

### Container-Zugriff

```bash
# In PHP-Container einsteigen
docker compose exec php sh

# PHP-Version prüfen
docker compose exec php php -v

# Konfiguration validieren
docker compose config
```

### Volumes

Der PHP-Container mountet zwei Volumes:

| Host-Pfad | Container-Pfad | Zugriff |
|-----------|----------------|---------|
| `./www` | `/var/www/html` | rw |
| `./data` | `/var/www/html/data` | rw |

`data/` bleibt nach `docker compose down` erhalten. Nur `docker compose down -v` löscht es.

---

## 3. Shared Hosting Produktion (Greatnet)

### Voraussetzungen

- PHP 8.1+ mit FastCGI
- SFTP oder SSH-Zugang
- SMTP-Zugangsdaten

### Document Root

Greatnet stellt `~/html/` als document root bereit.
Die `.htaccess` in `www/` übernimmt alle Rewrites, Security-Header und HTTPS-Redirect.

### Deployment-Schritte

```bash
# 1. Nur www/ übertragen — alles andere gehört nicht auf den Server
rsync -avz \
  --exclude='.env' \
  --exclude='.git/' \
  --exclude='*.sqlite' \
  --exclude='vendor/' \
  --exclude='node_modules/' \
  --exclude='data/' \
  www/ user@server:~/html/

# 2. Produktions-.env anlegen (einmalig)
cp .env.production.example .env.prod
# Alle Werte ausfüllen
# Per SFTP manuell nach ~/html/.env übertragen — niemals per rsync

# 3. Daten-Verzeichnis anlegen (außerhalb document root empfohlen)
ssh user@server "mkdir -p ~/data/speiseplaene ~/data/bestellungen && chmod 750 ~/data ~/data/speiseplaene ~/data/bestellungen"

# 4. Schreibrechte setzen
ssh user@server "find ~/html -name '*.php' -exec chmod 644 {} \; && chmod 644 ~/html/.htaccess"
```

### Admin-Key auf dem Server setzen

In der Greatnet-Hostkonfiguration oder in `.htpasswd`/`.htaccess` nicht möglich für Env-Variablen.
Stattdessen `.env` im document root ablegen und in `_bootstrap.php` via `getenv()` lesen.

Alternative über `.user.ini` (falls Greatnet FastCGI-Env-Variablen unterstützt):
```ini
; ~/html/.user.ini
env[BMV_ADMIN_KEY] = "dein-key-hier"
```

Oder direkt in `.env`:
```env
BMV_ADMIN_KEY=dein-key-hier
```

Die `.htaccess` verhindert direkten Web-Zugriff auf `.env`:
```apache
<Files ".env">
    Require all denied
</Files>
```

### DATA_PATH außerhalb document root

Wenn `~/data/` außerhalb von `~/html/` liegt:
```env
# In .env auf dem Server
DATA_PATH=/home/username/data
```

### Cronjobs

Aktuell keine Cronjobs erforderlich. Speisepläne werden manuell via Admin-Panel gepflegt.

---

## 4. Vercel Test-Deployment

> **Einschränkung:** Vercel hat kein persistentes Dateisystem. Speiseplan-JSON und
> Bestelldaten können nicht gespeichert werden. Ausschließlich für Routing-
> und Frontend-Tests geeignet.

```bash
# Vercel CLI installieren
npm i -g vercel

# Einmalig einrichten
vercel

# Umgebungsvariablen setzen
vercel env add BMV_ADMIN_KEY
vercel env add APP_ENV        # production
vercel env add APP_DEBUG      # false
vercel env add PEXELS_API_KEY
vercel env add OPENAI_API_KEY

# Deployment
vercel --prod
```

`vercel.json` ist im Repo enthalten und konfiguriert PHP-Routing.

**Bekannte Serverless-Einschränkungen:**

| Feature | Status |
|---------|--------|
| Speiseplan speichern | Nicht funktionsfähig (kein Filesystem) |
| Bestellungen empfangen | Nicht funktionsfähig (kein Filesystem) |
| E-Mail-Versand | Nur mit externem SMTP-Service |
| Admin-Panel lesen | Funktionsfähig |
| Frontend-Seiten | Funktionsfähig |

---

## 5. SMTP-Konfiguration

### Lokal (MailHog)

```env
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_ENCRYPTION=null
MAIL_USERNAME=
MAIL_PASSWORD=
```

Alle E-Mails werden abgefangen und unter http://localhost:8025 angezeigt.

### Produktion (Greatnet)

```env
MAIL_HOST=mail.bmv-kantinen.de
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=info@bmv-kantinen.de
MAIL_PASSWORD=<Passwort>
MAIL_FROM=info@bmv-kantinen.de
MAIL_FROM_NAME="BMV Menüdienst"
MAIL_TO=info@bmv-kantinen.de
```

### SMTP testen

```bash
# Lokal im Container
docker compose exec php php -r "mail('test@example.de', 'Test', 'Test');"
# Ergebnis in MailHog prüfen: http://localhost:8025

# Auf Produktionsserver
php -r "mail('info@bmv-kantinen.de', 'SMTP-Test', 'Testmail', 'From: info@bmv-kantinen.de');"
```

Für robusteren Versand: PHPMailer als Composer-Dependency einbinden und in `_bootstrap.php::send_order_mail()` integrieren.

---

## 6. Dateirechte

### Lokal (Docker)

Rechte werden automatisch durch den Docker-Volume-Mount verwaltet.
Bei Problemen:
```bash
docker compose exec php chmod -R 750 /var/www/html/data
docker compose exec php chown -R www-data:www-data /var/www/html/data
```

### Produktion (Greatnet)

| Pfad | Recht | Begründung |
|------|-------|-----------|
| `~/html/*.php` | 644 | PHP-Dateien nur lesbar |
| `~/html/.htaccess` | 644 | Apache liest es |
| `~/html/assets/` | 755 | Öffentlich lesbar |
| `~/data/` | 750 | Webserver schreibt, kein öffentlicher Zugriff |
| `~/data/speiseplaene/` | 750 | JSON-Speicherort |
| `~/data/bestellungen/` | 750 | Bestelldaten |
| `~/html/.env` | 600 | Nur Webserver-User |

```bash
find ~/html -name "*.php" -exec chmod 644 {} \;
chmod 644 ~/html/.htaccess
chmod -R 755 ~/html/assets
chmod 750 ~/data ~/data/speiseplaene ~/data/bestellungen
chmod 600 ~/html/.env
```

---

## 7. JSON-Speicher-Struktur

### Speisepläne

```
data/speiseplaene/
  essen_auf_raedern-YYYY-KWNN.json
  kantine-YYYY-KWNN.json
```

Beispiel: `essen_auf_raedern-2025-KW22.json`

Dateiformat:
```json
{
  "year": 2025,
  "kw": 22,
  "system": "essen_auf_raedern",
  "published": true,
  "week_start": "2025-05-26",
  "saved_at": "2025-05-20T14:30:00+02:00",
  "data": {
    "0": {
      "menu1": { "name": "Rindsroulade", "allergens": "G,L", "price": 7.50 },
      "menu2": { "name": "Gemüsepfanne", "allergens": "G", "price": 6.80 }
    }
  }
}
```

Das Admin-Panel schreibt via `save_plan.php` atomar (tempnam + rename).

### Bestellungen

```
data/bestellungen/
  BMV-YYYYMMDD-XXXXXX.json
```

Beispiel: `BMV-20250522-A3F2.json`

### Logs

```
data/bestellungen.log   — Kurz-Log aller Bestellungen (eine Zeile pro Bestellung)
data/kontakt.log        — Kurz-Log aller Kontaktanfragen
```

---

## 8. API-Endpunkte

Vollständige Dokumentation: [www/api/README.md](www/api/README.md)

| Methode | Endpunkt | Authentifizierung | Beschreibung |
|---------|----------|-------------------|--------------|
| GET | `/api/get_week.php?year=&kw=` | — | Speiseplan einer KW abrufen |
| POST | `/api/create_order.php` | — | Bestellung aufgeben |
| POST | `/api/save_plan.php` | `X-Admin-Key` Header | Speiseplan speichern |
| POST | `/api/save_week.php` | `X-Admin-Key` Header | Wochendaten speichern |
| GET | `/api/categories.php` | — | Kategorien abrufen |
| GET | `/api/dishes.php` | — | Gerichtliste abrufen |
| GET | `/api/pexels_image.php?q=` | — | Pexels-Bild-Proxy |
| POST | `/kontakt/send.php` | — | Kontaktformular senden |

**Admin-Key übergeben:**
```bash
curl -X POST https://www.bmv-kantinen.de/api/save_plan.php \
  -H "X-Admin-Key: $BMV_ADMIN_KEY" \
  -H "Content-Type: application/json" \
  -d '{"year":2025,"kw":22,"system":"essen_auf_raedern",...}'
```

---

## 9. Sicherheitshinweise

| Punkt | Status |
|-------|--------|
| `BMV_ADMIN_KEY` ohne Hardcode-Fallback in `_bootstrap.php` | ✅ |
| `BMV_ADMIN_KEY` ohne Hardcode-Fallback in `save_plan.php` | ✅ |
| `BMV_ADMIN_KEY` ohne Hardcode-Fallback in `docker-compose.yml` | ✅ |
| `BMV_ADMIN_KEY` ohne Hardcode-Fallback in `setup.sh` | ✅ |
| `OPENAI_API_KEY` nur einmal in `.env` definiert | ✅ |
| Brute-Force-Verzögerung bei fehlgeschlagenem Admin-Login | ✅ |
| Payload-Limit 512 KB in `_bootstrap.php` | ✅ |
| Atomares Schreiben (tempnam + rename) in `save_plan.php` | ✅ |
| Security-Header (X-Frame, X-Content-Type, Referrer-Policy) | ✅ |
| CORS-Allowlist nur auf bekannte Domains beschränkt | ✅ |
| `data/` via `.htaccess` vor direktem Web-Zugriff gesperrt | ✅ |
| `.env` via `.htaccess` vor direktem Web-Zugriff gesperrt | ✅ |
| `error_reporting(0)` + `display_errors=0` in Produktion | ✅ |
| `node_modules/`, `vendor/` nicht im Repo | ✅ |
| `*.phar`-Dateien nicht im Repo | ✅ |
| `APP_DEBUG=false` in `.env.production.example` vorgegeben | ✅ |

**Vor jedem Produktionsdeployment prüfen:**
```bash
# Keine .env-Leaks im Webroot
curl -I https://www.bmv-kantinen.de/.env
# Muss 403 zurückgeben

# data/ gesperrt
curl -I https://www.bmv-kantinen.de/data/
# Muss 403 zurückgeben
```

---

## 10. Troubleshooting

### Container startet nicht — Port belegt

```bash
# Windows
netstat -ano | findstr :8080
taskkill /PID <PID> /F

# Mac/Linux
lsof -i :8080
kill <PID>

docker compose up -d
```

### PHP 500 — keine Fehlermeldung

```bash
docker compose logs -f php
docker compose exec php tail -f /var/log/php_errors.log
```

Auf Produktion: `APP_DEBUG=false` lässt keine Details durch.
Greatnet-Fehlerlog über Hosting-Panel prüfen.

### BMV_ADMIN_KEY nicht gesetzt — 503 im Admin

```bash
# Key generieren und in .env setzen
echo "BMV_ADMIN_KEY=$(openssl rand -hex 32)" >> .env
docker compose restart php
```

### Speiseplan wird nicht gespeichert

1. Schreibrechte prüfen:
   ```bash
   docker compose exec php ls -la /var/www/html/data/
   ```
2. Volume-Mount in `docker-compose.yml` kontrollieren: `./data:/var/www/html/data:rw`
3. Verzeichnis auf dem Host anlegen: `mkdir -p data/speiseplaene data/bestellungen`

### require_once-Fehler in speiseplan/index.php

Nginx document root prüfen — `root` muss auf `/var/www/html` zeigen, nicht auf ein Unterverzeichnis.
```bash
docker compose exec nginx-bestellen nginx -T | grep root
```

### MailHog empfängt keine E-Mails

```bash
# MAIL_HOST muss 'mailhog' sein (nicht localhost)
grep MAIL_HOST .env

# MailHog läuft?
docker compose ps mailhog
```

### PDF-Generierung schlägt fehl

```bash
docker compose exec php python3 -c "import reportlab; print('OK')"
# Wenn Fehler: docker compose build --no-cache
```

### Git zeigt .env als untracked

```bash
# .env muss in .gitignore stehen
grep "^\.env$" .gitignore
# Falls nicht: echo ".env" >> .gitignore
```
