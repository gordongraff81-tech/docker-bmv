# Speiseplan – Vollständige Projektstruktur & Datei-Guide

## 📂 Verzeichnisbaum

```
speiseplan-app/
├── README.md                           # ← START HIER!
├── .env.example                        # ← Vorlage für Umgebungsvariablen
├── docker-compose.yml                  # ← Main Orchestrierung (Services, Ports, Volumes)
├── start.sh                            # ← Startup Script (Linux/Mac)
├── start.bat                           # ← Startup Script (Windows)
│
├── backend/
│   ├── Dockerfile                      # ← Node.js 20 Alpine Container
│   ├── package.json                    # ← Dependencies (Express, Socket.IO, pg)
│   ├── server.js                       # ← Express Server + Socket.IO + API
│   │
│   └── Funktionen:
│       ├── GET /api/menu/:year/:week   # Speiseplan laden
│       ├── POST /api/menu              # Speiseplan speichern
│       ├── PUT /api/menu-item/:id      # Menüitem aktualisieren
│       ├── DELETE /api/menu-item/:id   # Menüitem löschen
│       ├── POST /api/menu/:year/:week/publish  # Veröffentlichen
│       ├── GET /health                 # Health Check
│       └── Socket.IO Events            # Echtzeit Sync
│
├── frontend/
│   ├── Dockerfile                      # ← React Build + Serve
│   ├── package.json                    # ← Dependencies (React, Socket.io-client)
│   │
│   └── public/
│       └── index.html                  # ← HTML Root
│
│   └── src/
│       ├── index.js                    # ← React Einstiegspunkt
│       ├── App.js                      # ← Router Setup
│       ├── App.css                     # ← Globale Styles
│       │
│       ├── AdminDashboard.js           # ← Admin-Panel (Bearbeitung)
│       ├── AdminDashboard.css          # ← Admin Styles
│       │
│       ├── PublicMenu.js               # ← Kundenansicht (öffentlich)
│       ├── PublicMenu.css              # ← Kundenansicht + PRINT CSS
│       │
│       ├── index.css                   # ← HTML Base Styles
│       └── ...weitere React Module
│
├── nginx/
│   ├── nginx.conf                      # ← Nginx Hauptconfig (Worker, Gzip)
│   └── conf.d/
│       └── default.conf                # ← Reverse Proxy Setup
│           ├── Port 8080 → Health
│           ├── Port 8081 → Admin (Frontend + Socket.IO)
│           ├── Port 8082 → Public (Frontend + Socket.IO)
│           └── Port 3000 → API Direct
│
├── db/
│   └── init.sql                        # ← PostgreSQL Schema
│       ├── menu_plans (Tabelle)
│       ├── menu_items (Tabelle)
│       ├── Indizes
│       └── Sample Data
│
└── volumes/
    ├── postgres/                       # ← Persistente Datenbank (Bind Mount)
    └── uploads/                        # ← Datei-Speicher (Bind Mount)
```

---

## 🔧 Datei-Erklärungen

### Root-Konfiguration

| Datei | Beschreibung |
|-------|-------------|
| `docker-compose.yml` | **Hauptdatei** – Definiert alle 5 Services, Volumes, Networks, Ports |
| `.env.example` | Umgebungsvariablen-Vorlage (kopieren → `.env` + editieren) |
| `README.md` | Dokumentation & Quick Start |
| `start.sh` / `start.bat` | Ein-Klick Startup Scripts |

---

### Backend (`backend/`)

| Datei | Größe | Beschreibung |
|-------|-------|-------------|
| `Dockerfile` | ~356 Bytes | Node.js 20 Alpine Image, Health Check |
| `package.json` | ~572 Bytes | Dependencies: express, socket.io, pg, cors, dotenv |
| `server.js` | ~7.1 KB | **Core Backend**:
| | | • Express Server auf Port 3000
| | | • PostgreSQL Pool & Connection
| | | • 5 REST API Routes (GET/POST/PUT/DELETE)
| | | • Socket.IO für Echtzeit Events
| | | • CORS + Error Handling |

**API Endpoints:**
```
GET    /health                    → {"status": "OK"}
GET    /api/menu/:year/:week      → {plan, items}
POST   /api/menu                  → Speichern mit Items
PUT    /api/menu-item/:id         → Einzelnes Item updaten
DELETE /api/menu-item/:id         → Löschen
POST   /api/menu/:year/:week/publish → Veröffentlichen
```

---

### Frontend (`frontend/`)

| Datei | Größe | Beschreibung |
|-------|-------|-------------|
| `Dockerfile` | ~261 Bytes | 2-stufiger Build (build + serve) |
| `package.json` | ~724 Bytes | react, react-dom, axios, socket.io-client, react-router-dom |
| **src/App.js** | ~524 Bytes | Router: /admin, /speiseplan, / |
| **src/AdminDashboard.js** | ~6.3 KB | **Admin Panel**:
| | | • Grid Layout (8 Kategorien × 7 Tage)
| | | • Real-time fetch & save
| | | • Socket.IO subscribe
| | | • Speichern, Veröffentlichen, Refresh Buttons
| | | • Vollständige Kategorien (M1–M4, Dessert, etc.) |
| **src/AdminDashboard.css** | ~3.6 KB | Admin Styles (Grid, Buttons, Responsive) |
| **src/PublicMenu.js** | ~4.7 KB | **Kundenansicht**:
| | | • Tabelle (8 Kategorien × 5 Wochentage)
| | | • Echtzeit Socket.IO Sync
| | | • Allergen-Kennzeichnung
| | | • Preis-Anzeige
| | | • Print-Button
| | | • Empty State Handling |
| **src/PublicMenu.css** | ~7.1 KB | **KRITISCH – Print CSS**:
| | | • @media print { @page A4 landscape }
| | | • Paginated Druck (Tabellenkopf repeat)
| | | • Keine Farben/Buttons beim Druck
| | | • Optimierte Schriftgrößen (8–10pt)
| | | • Responsive für Desktop/Tablet/Mobile |
| `src/index.js` | ~254 Bytes | React Root |
| `public/index.html` | ~467 Bytes | HTML Vorlage |

---

### Nginx (`nginx/`)

| Datei | Größe | Beschreibung |
|-------|-------|-------------|
| `nginx.conf` | ~961 Bytes | Nginx Master Config:
| | | • Worker Processes & Connections
| | | • Gzip Compression (CSS, JS, JSON)
| | | • MIME Types & Security Headers |
| `conf.d/default.conf` | ~2.7 KB | **Reverse Proxy Routes**:
| | | • Port 8081 → Admin Panel (frontend)
| | | • Port 8082 → Kundenansicht (frontend)
| | | • /socket.io → Backend (WebSocket Upgrade)
| | | • /api → Backend (REST)
| | | • Health Check auf / |

---

### Datenbank (`db/`)

| Datei | Größe | Beschreibung |
|-------|-------|-------------|
| `init.sql` | ~2.2 KB | **PostgreSQL Schema**:
| | | • `menu_plans` Tabelle (year, week, published)
| | | • `menu_items` Tabelle (category, weekday, title, allergens, price)
| | | • Indizes (year_week, plan_id, category, weekday)
| | | • Sample Data für KW 21/2026 |

**Tabellen-Struktur:**
```sql
menu_plans:
  - id (PK, AUTO)
  - year (INT)
  - week (INT)
  - published (BOOL, DEFAULT false)
  - published_at (TIMESTAMP)
  - created_at, updated_at (TIMESTAMP)
  - UNIQUE(year, week)

menu_items:
  - id (PK, AUTO)
  - plan_id (FK → menu_plans.id)
  - category (VARCHAR) → 'Vollkost M1', 'Leichte Kost M2', ...
  - weekday (INT) → 0–6 (Mo–So)
  - title (VARCHAR) → Gerichtname
  - description (TEXT)
  - allergens (VARCHAR) → 'G, L, Ei'
  - price (NUMERIC 8.2) → 6.20
  - visible (BOOL, DEFAULT true)
  - sort_order (INT)
  - created_at, updated_at
```

---

## 🚀 Startup Ablauf

### 1. docker-compose.yml wird gelesen

Services werden in dieser Reihenfolge gestartet:
1. **postgres** (healthcheck: `pg_isready`)
2. **backend** (wartet auf postgres, healthcheck: `curl /health`)
3. **frontend** (baut React App, startet mit `serve`)
4. **nginx** (reverse proxy, healthcheck: `wget /health`)

### 2. PostgreSQL Initialization

```sql
-- init.sql wird automatisch ausgeführt
CREATE TABLE menu_plans (...)
CREATE TABLE menu_items (...)
CREATE INDEX ...
INSERT INTO menu_plans ...
```

### 3. Backend Server

```javascript
// server.js startet
→ Verbindung zu PostgreSQL
→ Express auf Port 3000
→ Socket.IO registriert
```

### 4. Frontend Build

```bash
npm ci              # Install deps
npm run build       # React Build → build/
serve -s build      # Serve auf Port 3000
```

### 5. Nginx Proxy

Alle Requests:
- `localhost:8081` → Frontend (Admin)
- `localhost:8082` → Frontend (Public)
- `/api/**` → Backend
- `/socket.io/**` → Backend (WebSocket)

---

## 🔄 Echtzeit-Sync Flow

```
┌──────────────┐
│ Admin Panel  │
│ (localhost:8081)
└──────┬───────┘
       │ Speichert Gericht
       ├→ POST /api/menu
       │   Backend speichert in DB
       │   Broadcast: socket.emit('menu:updated')
       │
       ├→ Socket.IO Server
       │   └─→ Alle connected Clients erhalten Event
       │
       └→ Kundenansicht (localhost:8082)
           └─→ Empfängt 'menu:updated'
               └─→ Ruft fetchMenu() auf (OHNE Reload!)
                   └─→ GET /api/menu/2026/21
                       └─→ Updated Daten anzeigen
```

**Wichtig:** Socket.IO läuft über WebSocket (persistent connection), nicht HTTP Polling!

---

## 📋 Checkliste: Alles funktioniert?

- [ ] `docker compose up -d` startet ohne Fehler
- [ ] `docker compose ps` zeigt 4 "healthy" Services
- [ ] http://localhost:8081/admin lädt Admin-Panel
- [ ] http://localhost:8082/speiseplan zeigt Kundenansicht
- [ ] Admin: Gericht speichern
- [ ] Kundenansicht: wird automatisch aktualisiert (kein Reload!)
- [ ] Druck (Ctrl+P): A4 Querformat ist lesbar
- [ ] `docker compose logs backend` zeigt keine Fehler
- [ ] `docker compose exec postgres psql -U speiseplan -d speiseplan -c "SELECT * FROM menu_items"`
- [ ] Alle Umlaute/Sonderzeichen werden korrekt angezeigt

---

## 🎯 Key Technical Specs

| Aspekt | Technologie |
|--------|-------------|
| **Container Orchestration** | Docker Compose v3.9 |
| **Backend** | Node.js 20 Alpine (lightweight!) |
| **Frontend** | React 18 + React Router v6 |
| **Real-time** | Socket.IO v4.7 (WebSocket) |
| **Database** | PostgreSQL 16 Alpine |
| **HTTP Server** | Nginx Alpine (Reverse Proxy) |
| **Architecture** | Microservices (Backend ↔ Frontend ↔ Database) |
| **Network** | Isoliert (`speiseplan_net` bridge) |
| **Persistence** | Named Volumes (postgres_data, uploads_data) |
| **Health Checks** | Alle Services (TCP + HTTP) |

---

## 💡 Best Practices implementiert

✅ **Production-ready:**
- Multi-stage Docker builds (Frontend)
- Health checks auf allen Services
- Restart policies (`unless-stopped`)
- Getrennte Netzwerke
- Persistente Volumes
- Environment-Variablen aus .env
- Nginx Gzip Compression

✅ **Sicherheit:**
- CORS Whitelist
- SQL Injection Prevention (Parameterized Queries)
- XSS Prevention (htmlspecialchars)
- No Secrets in Code (alle in .env)
- Database nur im internen Netzwerk

✅ **Performance:**
- Datenbank Indizes
- Socket.IO Namespaces
- Gzip HTTP Compression
- Async/Await für DB Queries
- Connection Pooling (pg)

---

**Viel Erfolg! Bei Fragen: Logs anschauen mit `docker compose logs -f`** 🍽️✨
