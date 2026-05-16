# BMV Menüdienst – Release Notes

## v1.0.0 — Produktionsrelease (2025)

### Zusammenfassung

Erstes produktionsreifes Release des BMV Menüdienst Webprojekts.
Vollständige Docker-Entwicklungsumgebung mit vier Nginx-Containern,
PHP-FPM, MailHog und persistentem Volume-Mount.

### Neue Features

- Dockerisierte Entwicklungsumgebung (4x Nginx + PHP-FPM + MailHog)
- Admin-Panel zum Einpflegen wöchentlicher Speisepläne
- REST-API mit JSON-Datenspeicher (kein Datenbankserver erforderlich)
- Atomares Schreiben aller JSON-Dateien (tempnam + rename)
- Pexels-API-Integration für automatische Gerichtsbilder
- OpenAI GPT-4.1-mini Fallback für Gerichtsnamensübersetzung
- Vollständiges Bestellformular mit E-Mail-Benachrichtigung
- Kontaktformular mit Honeypot-Schutz
- SEO-Slug-Routing via Nginx + .htaccess
- Sitemap-Generierung (XML)

### Security

- Kein Hardcode-Fallback für BMV_ADMIN_KEY in keiner Datei
- Brute-Force-Verzögerung (200 ms) bei fehlgeschlagenem Admin-Login
- Payload-Limit 512 KB für alle API-Endpunkte
- CORS-Allowlist auf bekannte Domains beschränkt
- Security-Header in PHP und .htaccess gesetzt
- data/ und .env via .htaccess vor direktem Web-Zugriff gesperrt
- display_errors=Off in .htaccess erzwungen

### Deployment

- Greatnet Shared Hosting: rsync www/ + .env manuell
- Vercel: Nur für Frontend-Tests (kein persistentes Filesystem)
- Lokal: docker compose up -d --build

### Geänderte Dateien (finale Cleanup-Session)

- `docker-compose.yml` — BMV_ADMIN_KEY Hardcode-Fallback entfernt
- `setup.sh` — Hardcode-Fallback entfernt, automatische Key-Generierung
- `www/api/_bootstrap.php` — Security Hardening vollständig
- `www/api/save_plan.php` — Hardcode-Fallback entfernt, atomic write
- `.env` — doppelter OPENAI_API_KEY bereinigt
- `.env.example` — professionell, keine Müllwerte
- `.env.production.example` — produktionsfertig
- `.gitignore` — www/vendor/, menu_database*.json, *.sql ergänzt
- `README.md` — vollständig überarbeitet, kein Hardcode-Passwort mehr
- `DEPLOYMENT.md` — production-grade, alle 10 Abschnitte
- `deploy.sh` — neu, Produktions-Deployment mit Security-Scan

### Bekannte Einschränkungen

- E-Mail-Versand auf Produktion via PHP mail() — PHPMailer-Migration empfohlen
- Kein automatisches DB-Backup (JSON-Dateien manuell sichern)
- Vercel-Deployment ohne persistenten Speicher nicht für Produktion geeignet

### Empfohlene nächste Schritte

1. Greatnet-Server SSH-Zugang einrichten und deploy.sh testen
2. SMTP-Konfiguration auf Produktion verifizieren
3. BMV_ADMIN_KEY auf Produktion rotieren
4. Ersten Speiseplan via Admin-Panel einpflegen
5. curl-Tests gegen Produktions-Endpunkte ausführen (siehe DEPLOYMENT.md)
