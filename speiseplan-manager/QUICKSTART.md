# ⚡ QUICKSTART - Speiseplan Manager

## 🎯 In 5 Minuten einsatzbereit

### Schritt 1: Projekt klonen/downloaden
```bash
cd speiseplan-manager
```

### Schritt 2: Starten

**Linux/Mac:**
```bash
chmod +x start.sh
./start.sh
```

**Windows:**
```bash
start.bat
```

**Oder manuell:**
```bash
cp .env.example .env
docker compose up -d
```

### Schritt 3: Admin-Konsole öffnen
```
http://localhost:3000
```

---

## 🎮 Was kann ich sofort tun?

### 1. Gerichte-Pool ansehen
- Gehe zu **"Gerichte-Pool"**
- Wähle Kategorie (z.B. "Vollkost M1")
- Sehe 20+ vordefinierte Gerichte
- Preis editierbar (Doppelklick)
- Allergen-Codes hinzufügbar

### 2. Neues Gericht hinzufügen
- Fülle das Formular oben aus:
  - **Gerichtname**: z.B. "Hähnchenbrust mit Gemüse"
  - **Preis**: €6,50 (oder Default nutzen)
  - **Allergen**: z.B. "G, L"
- Klick "+ Hinzufügen"
- Sofort in der Tabelle sichtbar

### 3. Wochenplan erstellen
- Gehe zu **"Wochenplaner"**
- Stelle **Jahr** & **Woche** ein
- Für jeden Tag & Kategorie ein Gericht wählen
- **Automatisch gespeichert**
- Grüne Anzeige = Gericht zugewiesen

---

## 📊 Was ist vorinstalliert?

✅ **8 Kategorien** mit Standardpreisen:
- Vollkost M1 (6,40 €)
- Leichte Kost M2 (6,60 €)
- Premium M3 (7,40 €)
- Tagesmenü M4 (6,40 €)
- Dessert (1,80 €)
- Rohkost (1,80 €)
- Abendessen (5,60 €)
- Salat (5,60 €)

✅ **140+ Speisen** pro Kategorie
- Aus der Speiseplan_Liste.txt importiert
- Vollständig editierbar
- Individuelle Preisanpassung möglich

---

## 🔗 URLs & Ports

| Service | URL | Port |
|---------|-----|------|
| **Admin-UI** | http://localhost:3000 | 3000 |
| **Backend API** | http://localhost:5000 | 5000 |
| **Nginx Proxy** | http://localhost | 80 |
| **PostgreSQL DB** | localhost:5432 | 5432 |

---

## 📁 Wichtigste Dateien

```
speiseplan-manager/
├── backend/app.py          ← Flask API + Database Models
├── frontend/src/App.js     ← React Admin-UI
├── docker-compose.yml      ← Services orchestrieren
├── .env                    ← Datenbank-Zugangsdaten
└── README.md               ← Vollständige Dokumentation
```

---

## ⚙️ Datenbank-Zugangsdaten

```
Host: localhost
Port: 5432
User: speiseplan
Password: speiseplan_secure
Database: speiseplan
```

(Änderbar in `.env`)

---

## 🛑 Stoppen & Neustarten

### Alle Container stoppen (Daten bleiben!)
```bash
docker compose down
```

### Neu starten
```bash
docker compose up -d
```

### Komplett zurücksetzen (Achtung: Löscht Daten!)
```bash
docker compose down -v
docker compose up -d
```

---

## 🐛 Fehlerbehebung

### "Fehler beim Verbinden zu API"
```bash
docker logs speiseplan_backend
# Backend startet in ~30 Sekunden, db init dauert
```

### "Seite lädt nicht"
```bash
# Warten Sie 60 Sekunden nach docker compose up
# Services müssen hochfahren
docker compose ps  # Check status
```

### "Gerichte sind leer"
```bash
docker exec speiseplan_backend python init_data.py
```

---

## 🚀 Nächste Schritte

Nach erfolgreichem Start:

1. **[DONE]** System läuft
2. **[TODO]** Gerichte bearbeiten/ergänzen
3. **[TODO]** Wochenplan für aktuelle KW erstellen
4. **[TODO]** Individuelle Preise pro Gericht anpassen
5. **[TODO]** Optional: Backup-Strategie planen

---

## 💡 Pro-Tipps

- **Bulk-Edit**: Speichern von Gerichten triggert automatisch API-Update
- **Wochenplan-Vorlagen**: Einmal erstellen, später kopieren/anpassen
- **Allergen-Codes**: G=Gluten, L=Laktose, Ei=Eier, etc.
- **Preise**: Mit EUR-Symbol oder Dezimalpunkt eingeben
- **Backup**: Volumes sichern: `docker compose down && tar -czf backup.tar.gz speiseplan-manager/`

---

## 📖 Vollständige Doku

Siehe `README.md` für:
- API-Endpoints
- Datenbank-Schema
- Production Checklist
- Erweiterte Konfiguration

---

**Willkommen! Viel Erfolg mit dem Speiseplan Manager! 🎉**
