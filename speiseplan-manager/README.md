# 📋 Speiseplan Manager - Docker-basierte Admin-Anwendung

Vollständiges, produktionsreifes System zur Verwaltung von Speiseplänen mit Admin-Konsole, Gerichte-Pool und Wochenplaner.

## 🎯 Features

✅ **8 Menü-Kategorien** mit vordefinierten Standardpreisen:
- Vollkost M1 (6,40 €)
- Leichte Kost M2 (6,60 €)
- Premium M3 (7,40 €)
- Tagesmenü M4 (6,40 €)
- Dessert (1,80 €)
- Rohkost (1,80 €)
- Abendessen (5,60 €)
- Salat (5,60 €)

✅ **Gerichte-Pool-Verwaltung**
- Pro Kategorie eine editierbare Liste
- Initial gefüllt mit bereinigten Speisen aus Speiseplan_Liste.txt
- Individuelle Preisanpassung pro Gericht
- Manuelle Neuanlage von Gerichten

✅ **Wochenplaner**
- Dropdown-Auswahl pro Tag & Kategorie
- Automatische Speicherung
- Mehrwöchige Planung (Jahr/Woche)
- Veröffentlichungsstatus

✅ **Technologie-Stack**
- Flask REST-API (Python)
- React Admin-Frontend
- PostgreSQL oder SQLite
- Nginx Reverse Proxy
- Docker Compose Orchestrierung

---

## 🚀 Installation & Start

### Voraussetzungen
- Docker & Docker Compose installiert
- Ports 80, 3000, 5000, 5432 verfügbar

### 1. Umgebungsvariablen setzen

```bash
cp .env.example .env
# Optional: .env bearbeiten für andere DB-Zugangsdaten
```

### 2. Container starten

```bash
docker compose up -d
```

Dieser Befehl:
- Startet PostgreSQL
- Initialisiert die Datenbankstruktur (8 Kategorien)
- Importiert alle Speisen aus der Datenliste
- Startet Flask Backend (Port 5000)
- Startet React Frontend (Port 3000)
- Konfiguriert Nginx (Port 80)

### 3. Admin-Konsole öffnen

```
http://localhost:3000
```

---

## 📚 Verwendung

### Gerichte-Pool verwalten

1. Klicke auf **"Gerichte-Pool"** in der Navigation
2. Wähle eine Kategorie aus dem Dropdown
3. Sehe alle Gerichte dieser Kategorie
4. **Gericht bearbeiten**: Doppelklick auf Preis/Allergen-Felder
5. **Neues Gericht**: Fülle das Formular aus und klicke "+ Hinzufügen"
6. **Gericht löschen**: Klick auf "Löschen"

### Wochenplan erstellen

1. Klicke auf **"Wochenplaner"** in der Navigation
2. Stelle Jahr und Woche ein
3. Wähle für jeden Tag und jede Kategorie ein Gericht
4. Wird automatisch gespeichert
5. Grüne Anzeige = Gericht bereits zugewiesen

---

## 📁 Projektstruktur

```
speiseplan-manager/
├── backend/
│   ├── app.py              # Flask App + SQLAlchemy Models
│   ├── init_data.py        # Datenbank-Import-Skript
│   ├── requirements.txt    # Python Dependencies
│   └── Dockerfile
├── frontend/
│   ├── src/
│   │   ├── App.js          # React Admin-App
│   │   ├── App.css         # Styles
│   │   └── index.js        # Entry
│   ├── public/index.html
│   ├── package.json
│   └── Dockerfile
├── docker-compose.yml      # Orchestrierung
├── nginx.conf              # Reverse Proxy
├── .env.example            # Umgebungsvariablen-Vorlage
└── README.md
```

---

## 🗄️ Datenbank-Schema

### Categories
```sql
id | name | display_name | default_price | position
```

### Dishes
```sql
id | category_id | name | price | allergens | description | active | created_at
```

### WeeklyPlans
```sql
id | year | week | published | created_at | updated_at
```

### WeeklyPlanItems
```sql
id | plan_id | dish_id | weekday | category_id
```

---

## 🔧 API-Endpoints

```
GET    /api/categories              → Alle Kategorien
GET    /api/categories/<id>         → Kategorie + Gerichte
GET    /api/dishes?category_id=X    → Gerichte filtern
POST   /api/dishes                  → Neues Gericht
PUT    /api/dishes/<id>             → Gericht aktualisieren
DELETE /api/dishes/<id>             → Gericht löschen
GET    /api/weekly-plans/<Y>/<W>    → Wochenplan laden
POST   /api/weekly-plans/<Y>/<W>/items → Wochenplan speichern
POST   /api/weekly-plans/<Y>/<W>/publish → Veröffentlichen
```

---

## 📊 Initialisierung

Beim ersten Start:

1. **Docker Compose** startet alle Services
2. **PostgreSQL** initialisiert leere Datenbank
3. **Backend** führt `init_data.py` aus:
   - Erstellt 8 Kategorien mit Standardpreisen
   - Importiert 140+ Speisen aus `DISHES_DATA` Dict
4. **Frontend** verbindet sich mit API
5. Admin-Konsole ist bereit

Speisen können danach über die UI editiert, ergänzt oder gelöscht werden.

---

## 🛑 Container stoppen

```bash
docker compose down

# Daten bleiben erhalten in volumes/
```

## 🧹 Alles zurücksetzen

```bash
docker compose down -v

# Startet mit lerer Datenbank
docker compose up -d
```

---

## 🌍 Umgebungsvariablen

| Variable | Default | Beschreibung |
|----------|---------|-------------|
| `DB_USER` | speiseplan | PostgreSQL Benutzer |
| `DB_PASSWORD` | speiseplan_secure | PostgreSQL Passwort |
| `DB_NAME` | speiseplan | Datenbank-Name |
| `FLASK_ENV` | production | Flask Modus |
| `REACT_APP_API_URL` | http://localhost:5000 | Backend-URL |

---

## 📋 Datenbank-Optionen

### SQLite (Standard für Development)
```bash
DATABASE_URL=sqlite:////data/speiseplan.db
```

### PostgreSQL (Production)
```bash
DATABASE_URL=postgresql://user:pass@postgres:5432/speiseplan
```

---

## 🐳 Docker Compose Services

| Service | Port | Beschreibung |
|---------|------|-------------|
| nginx | 80 | Reverse Proxy |
| frontend | 3000 | React Admin-UI |
| backend | 5000 | Flask REST-API |
| postgres | 5432 | PostgreSQL Datenbank |

---

## 🔒 Production Checklist

Vor dem produktiven Einsatz:

- [ ] `.env` mit starken Passwörtern erstellen
- [ ] `SECRET_KEY` in Flask setzen
- [ ] CORS-Origins einschränken
- [ ] Nginx mit HTTPS/SSL konfigurieren
- [ ] Backup-Strategie für Volumes planen
- [ ] Monitoring (Logs, Metrics) einrichten
- [ ] Health Checks testen: `curl http://localhost/health`

---

## 📞 Troubleshooting

### "Fehler beim Laden der Kategorien"
```bash
# Backend-Logs prüfen
docker logs speiseplan_backend

# DB-Verbindung prüfen
docker logs speiseplan_postgres
```

### "Port 3000 bereits in Verwendung"
```bash
# Port freigeben oder ändern
docker compose down
lsof -ti:3000 | xargs kill -9
docker compose up -d
```

### "Datenbank ist leer nach Start"
```bash
# Import manuell triggern
docker exec speiseplan_backend python init_data.py
```

---

## 📦 Technische Details

- **Python**: 3.11
- **Node.js**: 18 (Frontend-Build)
- **React**: 18
- **Flask**: 3.0
- **SQLAlchemy**: 3.1
- **PostgreSQL**: 16
- **Nginx**: Alpine

---

## 🎉 Weitere Schritte

Nach dem Starten kannst du:

1. **Gerichte bearbeiten** im "Gerichte-Pool"
2. **Neue Gerichte** manuell hinzufügen
3. **Wochenplan** für beliebige KW/Jahr erstellen
4. **Preise anpassen** pro Gericht & Kategorie
5. **Kategorien erweitern** (optional in Code anpassen)

---

**Viel Erfolg mit dem Speiseplan Manager! 📋✨**
