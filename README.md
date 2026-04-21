# BMV Menüdienst – Lokale Entwicklungsumgebung

Docker-Setup zum lokalen Testen des kompletten BMV-Webprojekts.

---

## Schnellstart

### 1. Voraussetzungen

| Software | Download |
|---|---|
| Docker Desktop | https://www.docker.com/products/docker-desktop/ |
| Git (optional) | https://git-scm.com/ |

> **Windows:** Docker Desktop installieren, WSL2-Integration aktivieren.  
> **Mac:** Docker Desktop für Mac (Intel oder Apple Silicon).

---

### 2. Projektdateien einrichten

```
docker-bmv/          ← dieser Ordner
├── docker-compose.yml
├── Dockerfile.php
├── .env
├── setup.sh
├── config/
│   ├── php.ini
│   ├── msmtprc
│   └── fpm-pool.conf
├── nginx/
│   ├── main.conf
│   ├── bestellen.conf
│   ├── kantine.conf
│   └── admin.conf
└── www/             ← BMV-Projektdateien hier rein!
    ├── index.php
    ├── api/
    ├── admin/
    ├── includes/
    ├── pdf/
    ├── data/
    │   ├── speiseplaene/
    │   └── bestellungen/
    └── ...
```

**BMV-Dateien kopieren:**

```bash
# Alle Projektdateien nach www/ kopieren
cp -r /pfad/zu/bmv-php/* www/
```

---

### 3. Starten

**Mac / Linux:**
```bash
cd docker-bmv
bash setup.sh
```

**Windows (PowerShell):**
```powershell
cd docker-bmv
docker compose build
docker compose up -d
```

---

## URLs im Browser

| URL | Entspricht live |
|---|---|
| http://localhost:8080 | www.bmv-kantinen.de |
| http://localhost:8081 | bestellen.bmv-kantinen.de |
| http://localhost:8082 | kantinen-speiseplan.bmv-kantinen.de |
| http://localhost:8083 | Admin-Panel |
| http://localhost:8025 | E-Mail-Vorschau (Mailhog) |

---

## Admin-Panel

1. http://localhost:8083 aufrufen
2. Passwort eingeben: **`bmv-admin-2025`** (aus `.env`)
3. KW wählen → Speiseplan einpflegen → Speichern

**Passwort ändern:** In `.env` den Wert von `BMV_ADMIN_KEY` anpassen:
```
BMV_ADMIN_KEY=mein-neues-passwort
```
Dann neu starten:
```bash
docker compose restart php
```

---

## E-Mails testen

Alle E-Mails (Bestellbestätigungen, Kontaktformular) landen in **Mailhog**:

→ http://localhost:8025

Kein echter E-Mail-Versand im lokalen Betrieb.

---

## Nützliche Befehle

```bash
# Starten
docker compose up -d

# Stoppen
docker compose down

# Logs anzeigen (alle Container)
docker compose logs -f

# Nur PHP-Logs
docker compose logs -f php

# PHP-Container neu starten (nach php.ini Änderungen)
docker compose restart php

# Alles neu bauen (nach Dockerfile-Änderungen)
docker compose build --no-cache
docker compose up -d

# In PHP-Container einloggen
docker compose exec php sh

# PHP-Version prüfen
docker compose exec php php -v
```

---

## Speisepläne

Speisepläne werden als JSON-Dateien gespeichert:

```
www/data/speiseplaene/2025-KW12.json
www/data/speiseplaene/2025-KW13.json
...
```

Diese Dateien bleiben auch nach `docker compose down` erhalten (Volume-Mount).

---

## Häufige Probleme

**Port bereits belegt:**
```bash
# Welcher Prozess nutzt Port 8080?
lsof -i :8080        # Mac/Linux
netstat -ano | findstr 8080   # Windows
```
→ In `docker-compose.yml` die Ports ändern, z.B. `"9080:80"`.

**Permission-Fehler auf data/:**
```bash
chmod -R 777 www/data/
```

**PHP-Fehler 500:**
```bash
docker compose logs php
```

**PDF funktioniert nicht:**
```bash
# Python + reportlab prüfen
docker compose exec php python3 -c "import reportlab; print('OK')"
```

---

## Deployment auf Live-Server

Wenn alles lokal funktioniert:

1. Dateien per FTP/SSH auf den Server hochladen
2. `BMV_ADMIN_KEY` in Apache/Nginx-Config setzen
3. `pip install reportlab` auf dem Server ausführen
4. Schreibrechte auf `data/` setzen: `chmod -R 755 data/`

Detaillierte Deployment-Anleitung: auf Anfrage verfügbar.
