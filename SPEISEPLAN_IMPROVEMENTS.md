# 🍽️ SPEISEPLAN-SEITE – VERBESSERUNGSVORSCHLÄGE

**URL:** `http://localhost:8080/speiseplan/`

---

## 🔴 KRITISCHE PROBLEME

### **1. Header auf dieser Seite UNTERSCHIEDLICH zu Startseite**
- **Problem:** Navy-Header mit weißem Logo statt integr. Header mit nav
- **Impact:** Inkonsistente Navigation & UX
- **Lösung:** Unified Header verwenden (`includes/header.php`)
- **Aufwand:** Mittel (Template-Anpassung)

### **2. Week-Navigation hat zu viel Padding**
- **Problem:** Breites, platzintensives Layout bei 768px
- **Impact:** Auf Mobile unlesbar; Buttons wrappen ungünstig
- **Lösung:** Responsive Padding & Flex-Wrap optimieren
- **Aufwand:** Gering

### **3. Kontakt-Info im Header Fehlerhafte Telefon-Nummer**
```html
<!-- FEHLER: -->
<a href="tel:+493327574506603327 – 57 45 066">
<!-- Link-Ziel verstümmelt, Text != Link -->
```
- **Lösung:** `href="tel:+4933275745066"` korrigieren

### **4. Day-Card Grid auf Mobile nicht responsive**
- **Problem:** `minmax(340px, 1fr)` = zu breit auf <480px
- **Impact:** Horizontales Scrolling auf Small Mobile
- **Lösung:** `@media (max-width: 480px) { grid-template-columns: 1fr; }`

---

## 🟠 DESIGN-PROBLEME

### **5. Menü-Bilder: Pexels-Fallback zu grau**
- **Problem:** `background: linear-gradient(135deg, #e8edf5, #d0d8ea)` ist zu hell
- **Impact:** Placeholder sieht billig aus; keine Kontrastunterscheidung
- **Lösung:** 
```css
background: linear-gradient(135deg, #dce5f5, #c5d5e8);  /* weniger hell */
/* oder direkt: background: #d0d8ea; */
```

### **6. Holiday-Badge Gradient zu orange-saturiert**
```css
background: linear-gradient(135deg, #fff7ed, #ffedd5);
```
- **Problem:** Zu warm, kontrastiert nicht gut mit Text
- **Lösung:** `background: #fef3c7; border-left: 3px solid #d95a00;`

### **7. Menu-Item Hover-Background ist zu sehr blue**
```css
.menu-item:hover { background: #f0f5ff; }
```
- **Problem:** Zu blau, nicht neutral genug
- **Lösung:** `background: #f8fafc;` (nur sehr subtil)

### **8. Addon-Section Header: Orange-Gradient zu knallig**
- **Problem:** `linear-gradient(135deg, var(--orange), var(--orange2))`
- **Impact:** Wirkt aggressiv, nicht elegant
- **Lösung:** Solide Orange statt Gradient: `background: var(--orange);`

---

## 🟡 UX-PROBLEME

### **9. Week-Navigation: Prev/Next Buttons zu klein/ungestörbar**
- **Problem:** Font-size `1.05rem` ist klein; Button-Text nicht klar
- **Impact:** Schlechte Click-Target auf Mobile (Target sollte min. 44×44px sein)
- **Lösung:**
```css
.week-nav-btn {
  padding: 14px 28px;  /* 12px 24px → größer */
  font-size: 1.1rem;   /* 1.05rem → größer */
  min-height: 44px;    /* A11y: mind. 44px Click-Ziel */
}
```

### **10. Menu-Popup: Modal-Hintergrund zu dunkel**
```css
background: rgba(11,42,91,.5);
```
- **Problem:** Navy macht UI düster; Fokus zu sehr auf Overlay
- **Lösung:** `background: rgba(11,42,91,.35);` (weniger dramatisch)

### **11. No-Data Message: Zu viel Text-Größe**
```css
.no-data h2 { font-size: 1.6rem; }
.no-data p  { font-size: 1.1rem; }
```
- **Problem:** Zu groß für Simple Nachricht
- **Lösung:**
```css
.no-data h2 { font-size: 1.3rem; }
.no-data p  { font-size: 0.95rem; }
```

### **12. Addon-Sektion: 5-Spalten-Grid bricht auf <768px ungünstig**
- **Problem:** `grid-template-columns: repeat(5, 1fr)` = zu eng
- **Lösung:** `@media (max-width: 768px) { display: none; }` oder scroll-x

---

## 🟢 PERFORMANCE-OPPORTUNITÄTEN

### **13. Popup-Bilder: Keine Loading-Optimierung**
- **Problem:** `fetchPexelsImg(query, 560, 220)` wartet vollständig
- **Lösung:** Blur-Up Placeholder vor Vollbild:
```javascript
img.style.filter = 'blur(10px)';
// Nach Load:
img.style.filter = 'none';
img.style.transition = 'filter 0.3s';
```

### **14. Print-Scaling: JavaScript zu kompliziert**
- **Problem:** `calcPrintScale()` berechnet Skalierung neu
- **Lösung:** CSS-basiert via `@page` + `zoom` (einfacher):
```css
@page { zoom: 0.95; }
```

### **15. Header-Logo Filter: `brightness(10)` unnötig heftig**
```css
.site-header .logo img { filter: brightness(10); }
```
- **Problem:** Macht Bild überexponiert/weiß-ausgeblasen
- **Lösung:** `filter: invert(1) brightness(1.2);` oder nur `filter: none;` (Logo ist bereits weiß)

---

## 🎨 VISUELLE VERBESSERUNGEN

### **16. Konsistenz: Day-Card Rounding zu spitz**
```css
.day-card { border-radius: var(--r); }  /* 12px */
```
- **Problem:** Zu scharf für moderales Design
- **Lösung:** `border-radius: 16px;`

### **17. Menu-Badge: Kontrast zu gering**
```css
.menu-badge { background: var(--navy); color: #fff; }
```
- **Problem:** Navy auf grauem BG = zu dunkel zu sehen
- **Lösung:**
```css
.menu-badge {
  background: var(--navy);
  color: #fff;
  box-shadow: 0 2px 8px rgba(11,42,91,.15);  /* Erhöht visuelle Trennung */
}
```

### **18. Week-Info Center: Zu wenig Padding**
```css
.week-info { text-align: center; min-width: 280px; }
```
- **Problem:** Text ist gepresst
- **Lösung:** `padding: 0 20px;`

---

## 📱 MOBILE-SPEZIFISCHE FIXES

### **19. Header auf Mobile: Kontakt sollte Hidden sein**
```html
<div class="contact">.....</div>  <!-- Always visible, zu viel Platz -->
```
- **Problem:** Telefon + Email sichtbar → Header zu hoch
- **Lösung:**
```css
@media (max-width: 768px) {
  .site-header .contact { display: none; }
  .site-header { padding: 14px 16px; }  /* kompakter */
}
```

### **20. Print-Button: Zu prominent auf Mobile**
- **Problem:** Breitener Button ist nicht Primary-Action
- **Lösung:** Auf Mobile: Icon-Only oder Hidden
```css
@media (max-width: 768px) {
  .print-btn {
    padding: 8px 12px;
    font-size: 0.85rem;
  }
}
```

---

## 📋 PRIORISIERUNG

**MUST-FIX (P0 – Funktional):**
1. Header Konsistenz (inkl. Telefon-Link-Fehler) → P0.1
2. Day-Card Grid Mobile Responsive → P0.2
3. Week-Nav Touch-Target Größe → P0.3

**SHOULD-FIX (P1 – UX/Design):**
4. Addon-Section auf Mobile verbergen → P1.1
5. Header Kontakt auf Mobile hidden → P1.2
6. Menü-Bilder Placeholder Farbe → P1.3
7. Modal-Hintergrund weniger dunkel → P1.4

**NICE-TO-HAVE (P2 – Optimierung):**
8. Border-Radius konsistent erhöhen → P2.1
9. Badge Box-Shadow hinzufügen → P2.2
10. Print-Scaling CSS-basiert → P2.3

---

## 🔧 UMSETZUNGS-CHECKLIST

- [ ] Header zu unified Header.php migrieren
- [ ] Telefon-Link-Fehler fixen: `href="tel:+4933275745066"`
- [ ] Day-Card Grid: `@media (max-width: 480px) { grid-template-columns: 1fr; }`
- [ ] Week-Nav Button: `min-height: 44px; padding: 14px 28px;`
- [ ] Header Kontakt: `@media (max-width: 768px) { display: none; }`
- [ ] Placeholder-Farbe: `#d0d8ea` → `#dce5f5`
- [ ] Modal-BG: `rgba(11,42,91,.5)` → `rgba(11,42,91,.35)`
- [ ] Addon-Section: `@media (max-width: 768px) { display: none; }`
- [ ] Border-Radius erhöhen: `12px` → `16px`
- [ ] Print-Button Mobile: Icon-Only oder Hidden
