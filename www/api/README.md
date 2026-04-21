# BMV Menüdienst – Backend API

## Struktur

```
api/
  _bootstrap.php      Gemeinsame Konfiguration, Hilfsfunktionen
  get_week.php        GET  – Speiseplan einer KW abrufen
  create_order.php    POST – Bestellung aufgeben
  save_week.php       POST – Speiseplan einer KW speichern (Admin)
kontakt/
  send.php            POST – Kontaktformular senden
data/
  speiseplaene/       JSON-Dateien pro KW  (z.B. 2025-KW12.json)
  bestellungen/       JSON pro Bestellung  (z.B. BMV-20250317-A3F2.json)
  bestellungen.log    Kurz-Log aller Bestellungen
  kontakt.log         Kurz-Log aller Kontaktanfragen
  .htaccess           Sperrt /data/ vor direktem Web-Zugriff
```

---

## Endpunkte

### GET /api/get_week.php

| Parameter | Typ | Pflicht | Beschreibung |
|-----------|-----|---------|--------------|
| `year`    | int | Nein    | Jahreszahl (Standard: aktuell) |
| `kw`      | int | Nein    | Kalenderwoche 1–53 (Standard: aktuell) |

**Beispiel:**
```
GET /api/get_week.php?year=2025&kw=12
```

**Response 200 (Speiseplan vorhanden):**
```json
{
  "success": true,
  "year": 2025,
  "kw": 12,
  "week_start": "2025-03-17",
  "week_end": "2025-03-23",
  "days": [
    {
      "date": "2025-03-17",
      "weekday": "Montag",
      "is_weekend": false,
      "menus": [
        {
          "menu_number": 1,
          "label": "Vollkost",
          "title": "Rindsroulade mit Rotkohl",
          "description": "mit Kartoffelklößen und Soße",
          "price": 7.50,
          "allergens": "G, L",
          "available": true
        }
      ],
      "addons": [
        { "code": "SUPPE", "name": "Tagessuppe", "price": 1.80 }
      ]
    }
  ]
}
```

**Response 404 (kein Speiseplan):**
```json
{
  "success": false,
  "message": "Für diese Woche ist noch kein Speiseplan hinterlegt.",
  "days": [ ... leere Tage ... ]
}
```

---

### POST /api/create_order.php

**Content-Type:** `application/json`

**Request-Body:**
```json
{
  "customer": {
    "firstname":   "Maria",
    "lastname":    "Müller",
    "phone":       "+49 331 12345678",
    "email":       "maria@example.de",
    "address":     "Hauptstr. 5, 14469 Potsdam",
    "startdate":   "2025-03-24",
    "days":        "5",
    "notes":       "Bitte klingeln",
    "pflegekasse": true
  },
  "selections": {
    "2025-03-24": { "menuNumber": 1, "addons": ["SUPPE"] },
    "2025-03-25": { "menuNumber": 2, "addons": [] }
  }
}
```

**Response 200 (Erfolg):**
```json
{
  "success": true,
  "order_id": "BMV-20250317-A3F2",
  "message": "Ihre Bestellung #BMV-20250317-A3F2 mit 2 Menü(s) wurde erfolgreich übermittelt."
}
```

**Response 422 (Validierungsfehler):**
```json
{
  "success": false,
  "message": "Bitte korrigieren Sie die markierten Felder.",
  "errors": {
    "phone": "Telefonnummer ist erforderlich."
  }
}
```

---

### POST /api/save_week.php  *(Admin)*

**Content-Type:** `application/json`

Speichert einen Speiseplan. Erfordert `admin_key`.

Den Admin-Key als Umgebungsvariable setzen:
```
# Apache (.htaccess oder VirtualHost):
SetEnv BMV_ADMIN_KEY "langer-zufaelliger-schluessel"

# Nginx (fastcgi_params):
fastcgi_param BMV_ADMIN_KEY "langer-zufaelliger-schluessel";
```

**Request-Body:**
```json
{
  "admin_key": "langer-zufaelliger-schluessel",
  "year": 2025,
  "kw": 13,
  "days": [ ... gleiche Struktur wie get_week response ... ]
}
```

---

### POST /kontakt/send.php

Akzeptiert sowohl `application/x-www-form-urlencoded` (HTML-Formular) als auch `application/json`.

**Felder:** `name` (Pflicht), `email` (Pflicht), `phone`, `subject`, `message` (Pflicht), `dsgvo` (Pflicht), `website` (Honeypot, muss leer bleiben)

---

## Einrichtung auf dem Server

### 1. Schreibrechte

```bash
chmod 750 data/
chmod 750 data/speiseplaene/
chmod 750 data/bestellungen/
chown www-data:www-data data/ -R
```

### 2. Admin-Key setzen

In der Apache-VirtualHost-Konfiguration:
```apache
SetEnv BMV_ADMIN_KEY "generiere-hier-einen-langen-zufaelligen-string"
```

Oder in `.env` / `php.ini`:
```ini
; In php.ini oder .user.ini
env[BMV_ADMIN_KEY] = "generiere-hier-einen-langen-zufaelligen-string"
```

### 3. E-Mail testen

```bash
php -r "mail('info@bmv-kantinen.de', 'Test', 'Test', 'From: test@bmv-kantinen.de');"
```

Wenn PHP `mail()` nicht konfiguriert ist, alternativ PHPMailer/SMTP integrieren.

### 4. Speiseplan einpflegen

Entweder:
- JSON-Dateien direkt in `/data/speiseplaene/YYYY-KWxx.json` ablegen
- Oder `save_week.php` via HTTP POST aufrufen (z.B. aus zukünftigem Admin-Panel)

### 5. CORS-Domains anpassen

In `_bootstrap.php` die `$allowed_origins`-Liste bei Bedarf erweitern.
