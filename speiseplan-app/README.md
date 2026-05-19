# 🍽️ Speiseplan – Digitales Menümanagement

Produktionsreife Docker-Compose-Anwendung für professionelle Speiseplanverwaltung mit Admin-Panel, öffentlicher Kundenansicht, PostgreSQL-Datenbank und Echtzeit-Synchronisation via Socket.IO.

## 🎯 Features

✅ **Admin-Dashboard**
- Wochennavigation (KW + Jahr)
- 8 Menü-Kategorien (Vollkost, Leichte Kost, Premium, Tagesmenü, Dessert, Rohkost, Abendessen, Salatteller)
- Tagesweise Erfassung (Mo–So)
- Allergene-Management
- Preiskalkulierung
- Veröffentlichung & Entwürfe
- Echtzeit-Vorschau

✅ **Kundenansicht**
- Responsive mobile Optimierung
- Pixelgenaue Darstellung
- Allergen-Kennzeichnung
- Preisanzeige
- Echtzeit-Aktualisierung (kein Reload nötig)
- A4-Querformat Druck-CSS
- druckoptimiert für Chrome, Edge, Firefox, PDF

✅ **Backend**
- Node.js/Express REST API
- Socket.IO für Echtzeit-Events
- PostgreSQL mit vollständigem Schema
- Health Checks auf allen Services
- CORS-Sicherheit
- UTF-8 Unterstützung

✅ **Infrastruktur**
- Docker Compose (Production-ready)
- Nginx Reverse Proxy
- PostgreSQL 16 Alpine
- Getrennte Netzwerke
- Persistente Volumes
- Automatische Restarts
- Health Checks

---

## 🚀 Schnellstart

### Voraussetzungen
- Docker & Docker Compose installiert
- Port 8080, 8081, 8082, 3000, 5432 verfügbar

### 1. .env-Datei erstellen

```bash
cp .env.example .env
```

Editiere `.env` und ändere sensitive Werte:
```env
DB_PASSWORD=your_production_password
JWT_SECRET=your_production_jwt_secret
```

### 2. Anwendung starten

```bash
cd speiseplan-app
docker compose up -d
```

### 3. Auf Services zugreifen

| Service | URL | Beschreibung |
|---------|-----|-------------|
| **Admin-Panel** | http://localhost:8081/admin | Menüverwaltung |
| **Kundenansicht** | http://localhost:8082/speiseplan | Öffentliches Menü |
| **API** | http://localhost:3000/api | REST Endpoints |
| **Datenbank** | localhost:5432 | PostgreSQL |

---

## 📊 Datenmodell

### menu_plans
```sql
id (PK) | year | week | published | published_at | created_at | updated_at
```

### menu_items
```sql
id | plan_id (FK) | category | weekday | title | description | allergens | price | visible | sort_order
```

---

## 🔌 API Endpoints

### Speiseplan laden
```bash
GET /api/menu/:year/:week
```

Response:
```json
{
  "plan": {
    "id": 1,
    "year": 2026,
    "week": 21,
    "published": false,
    "created_at": "2026-05-01T10:00:00Z"
  },
  "items": [
    {
      "id": 1,
      "category": "Vollkost M1",
      "weekday": 0,
      "title": "Schweineschnitzel",
      "allergens": "G, L, Ei",
      "price": 6.20
    }
  ]
}
```

### Speiseplan speichern
```bash
POST /api/menu
Content-Type: application/json

{
  "year": 2026,
  "week": 21,
  "items": [
    {
      "category": "Vollkost M1",
      "weekday": 0,
      "title": "Schweineschnitzel",
      "allergens": "G, L, Ei",
      "price": 6.20
    }
  ],
  "published": false
}
```

### Menü veröffentlichen
```bash
POST /api/menu/:year/:week/publish
```

---

## 🎨 Admin-Oberfläche

Die Admin-Oberfläche zeigt eine **Tabellen-Grid-Ansicht** mit:

- **Zeilen**: 8 Menü-Kategorien
- **Spalten**: Wochentage (Mo–So)
- **Zellen**: Eingabefelder für Gerichtname, Allergene, Preis
- **Buttons**: Speichern, Veröffentlichen, Zurückziehen, Aktualisieren

```
┌──────────────────┬────────┬────────┬────────┬────────┬────────┬────────┬────────┐
│ Kategorie        │   Mo   │   Di   │   Mi   │   Do   │   Fr   │   Sa   │   So   │
├──────────────────┼────────┼────────┼────────┼────────┼────────┼────────┼────────┤
│ Vollkost M1      │ Input  │ Input  │ Input  │ Input  │ Input  │ Input  │ Input  │
│ Leichte Kost M2  │ Input  │ Input  │ Input  │ Input  │ Input  │ Input  │ Input  │
│ Premium M3       │ Input  │ Input  │ Input  │ Input  │ Input  │ Input  │ Input  │
│ ... (weitere)    │  ...   │  ...   │  ...   │  ...   │  ...   │  ...   │  ...   │
└──────────────────┴────────┴────────┴────────┴────────┴────────┴────────┴────────┘
```

---

## 👥 Kundenansicht

Die Kundenansicht zeigt die **exakt gleichen Daten** als responsive Tabelle:

- Mobile-optimiert (responsive Grid)
- Pixelgenaue Formatierung
- Alle Allergen-Codes sichtbar
- Preisanzeige mit € Symbol
- **Echtzeit-Sync** via Socket.IO

```
┌─────────────────────────────────────────────────┐
│         KW 21 / 2026 - Speiseplan              │
├──────────────────┬───┬───┬───┬───┬───┬───┬────┤
│ Kategorie        │Mo │Di │Mi │Do │Fr │Sa │So │
├──────────────────┼───┼───┼───┼───┼───┼───┼────┤
│ Vollkost M1      │...│...│...│...│...│...│... │
│ Leichte Kost M2  │...│...│...│...│...│...│... │
│ Premium M3       │...│...│...│...│...│...│... │
└──────────────────┴───┴───┴───┴───┴───┴───┴────┘
```

---

## 🖨️ Druck (A4 Querformat)

### Print-Features

✅ Optimiert für DIN A4 Landscape  
✅ Maximale 1–2 Seiten  
✅ Seitenumbruch bei Tabellenkopf  
✅ Keine abgeschnittenen Texte  
✅ Kompakte Schriftgröße  
✅ Nur Tabelleninhalt (keine Buttons)  
✅ Firma + Logo + KW/Jahr im Header  

### Druck-Aktivierung

```javascript
// JavaScript
window.print();

// CSS
@media print {
  @page { size: A4 landscape; margin: 0.5cm; }
  .no-print { display: none !important; }
}
```

---

## 🔄 Echtzeit-Synchronisation (Socket.IO)

Wenn der Admin ein Gericht speichert → **alle offenen Kundenansichten aktualisieren sich sofort**.

### Socket-Events

```javascript
// Admin speichert Gericht
socket.emit('menu:updated', { year: 2026, week: 21 })

// Kundenansicht empfängt Update
socket.on('menu:updated', (data) => {
  fetchMenu(data.year, data.week);
})

// Veröffentlichung
socket.on('menu:published', (data) => {
  console.log(`KW ${data.week}/${data.year} veröffentlicht`);
})
```

---

## 🐳 Docker Compose Services

| Service | Image | Port | Status |
|---------|-------|------|--------|
| **postgres** | postgres:16-alpine | 5432 | ✓ Health |
| **backend** | node:20-alpine | 3000 | ✓ Health |
| **frontend** | node:20-alpine (build) | 3000 | ✓ Health |
| **nginx** | nginx:alpine | 80 | ✓ Health |

### Logs anschauen
```bash
docker compose logs -f backend
docker compose logs -f frontend
docker compose logs -f postgres
```

### Services neu starten
```bash
docker compose restart
docker compose restart backend
```

### Datenbank-Zugriff
```bash
docker compose exec postgres psql -U speiseplan -d speiseplan
```

---

## 📁 Projektstruktur

```
speiseplan-app/
├── docker-compose.yml          # Orchestrierung
├── .env.example                # Umgebungsvariablen (Vorlage)
│
├── backend/
│   ├── Dockerfile
│   ├── package.json
│   ├── server.js               # Express + Socket.IO
│   └── (Node Module hier)
│
├── frontend/
│   ├── Dockerfile
│   ├── package.json
│   ├── public/
│   │   └── index.html
│   └── src/
│       ├── index.js
│       ├── App.js
│       ├── AdminDashboard.js   # Admin-Panel
│       ├── AdminDashboard.css
│       ├── PublicMenu.js       # Kundenansicht
│       ├── PublicMenu.css      # Druck-CSS
│       └── (React Module hier)
│
├── nginx/
│   ├── nginx.conf              # Hauptconfig
│   └── conf.d/
│       └── default.conf        # Reverse Proxy
│
├── db/
│   └── init.sql                # PostgreSQL Schema
│
└── volumes/
    ├── postgres/               # Persistente DB
    └── uploads/                # Datei-Storage
```

---

## 🔒 Sicherheit

✅ **CORS** nur für konfigurierte Origins  
✅ **JWT** für zukünftige Authentifizierung  
✅ **SQL Injection** via Parameterized Queries  
✅ **XSS** via htmlspecialchars in Frontend  
✅ **HTTPS** in Production via Reverse Proxy  
✅ **Datenbank** nur im internen Netzwerk  
✅ **.env** mit Secrets (nicht ins Git!)  

---

## 🛠️ Troubleshooting

### Ports in Verwendung
```bash
# Ports freigeben oder in docker-compose.yml ändern
lsof -i :8081
kill -9 <PID>
```

### Datenbank-Fehler
```bash
# DB Logs
docker compose logs postgres

# DB neustarten und Schema neu laden
docker compose down postgres
docker volume rm speiseplan-app_postgres_data
docker compose up -d postgres
```

### Socket.IO Verbindung fehlgeschlagen
```bash
# API Logs prüfen
docker compose logs backend

# Nginx Logs
docker compose logs nginx

# Frontend Socket-URL in .env prüfen
REACT_APP_SOCKET_URL=http://localhost:3000
```

### Frontend baut nicht
```bash
docker compose logs frontend

# Rebuild
docker compose build --no-cache frontend
docker compose up -d frontend
```

---

## 📈 Performance

- **Datenbank**: PostgreSQL 16 mit Indizes
- **Caching**: HTTP Caching Headers für Static Assets
- **Compression**: Gzip für API Responses
- **Socket.IO**: Namespace für Broadcast-Effizienz

---

## 📝 Lizenz

MIT – Frei nutzbar für private & kommerzielle Projekte.

---

## 🤝 Support

Bei Problemen:
1. Docker Logs prüfen
2. Health Checks verifizieren
3. Umgebungsvariablen in `.env` überprüfen
4. Services neu starten

```bash
docker compose restart
```

---

**Viel Erfolg mit deinem digitalen Speiseplan! 🍽️✨**
