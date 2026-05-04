<?php
/**
 * speiseplan/index.php – Wochenspeiseplan
 * Nutzt helpers.php für alle Hilfsfunktionen (keine Duplikate)
 */
require_once __DIR__ . '/../includes/helpers.php';

$currentYear = (int)date('Y');
$currentKW   = (int)date('W');
$year = isset($_GET['year']) ? (int)$_GET['year'] : $currentYear;
$kw   = isset($_GET['kw'])   ? (int)$_GET['kw']   : $currentKW;

// Navigations-Grenzen (aus helpers.php)
[$prevYear, $prevKW] = addKW($year, $kw, -1);
[$nextYear, $nextKW] = addKW($year, $kw, +1);
$bounds   = kwNavBounds($year, $kw, $currentYear, $currentKW, 4);
$isAtMin  = $bounds['isAtMin'];
$isAtMax  = $bounds['isAtMax'];

$days      = kwDates($year, $kw);
$dayNames  = ['Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag','Sonntag'];
$dayShort  = ['Mo','Di','Mi','Do','Fr','Sa','So'];
$feiertage = getFeiertage($year);

// ── Daten laden ──────────────────────────────────────────────────
$kwStr   = str_pad($kw, 2, '0', STR_PAD_LEFT);
$docRoot = $_SERVER['DOCUMENT_ROOT'];
$newFile = "$docRoot/data/speiseplaene/essen_auf_raedern-{$year}-KW{$kwStr}.json";
$oldFile = "$docRoot/data/speiseplaene/{$year}-KW{$kwStr}.json";

$plan      = null;
$newFormat = false;

if (file_exists($newFile)) {
    $raw = json_decode(file_get_contents($newFile), true);
    if ($raw && isset($raw['data'])) { $plan = $raw; $newFormat = true; }
} elseif (file_exists($oldFile)) {
    $plan = json_decode(file_get_contents($oldFile), true);
}

// ── Daten normalisieren → einheitliches $dayMap ──────────────────
$dayMap  = [];
$prices  = [1=>7.50, 2=>7.20, 3=>9.80, 4=>6.50];
$aprices = ['D'=>1.80,'R'=>1.80,'A'=>5.50,'S'=>5.50];

$catToMenu  = ['vollkost'=>1,'leichte_kost'=>2,'premium'=>3,'tagesmenu'=>4];
$catToAddon = ['dessert'=>'D','rohkost'=>'R','abendessen'=>'A','salat'=>'S'];

if ($plan) {
    if ($newFormat) {
        foreach ($plan['data'] as $dayIdx => $cats) {
            $dayIdx = (int)$dayIdx;
            $menus  = []; $addons = [];
            foreach ($cats as $catKey => $entry) {
                if (!($entry['name'] ?? '')) continue;
                if (isset($catToMenu[$catKey])) {
                    $menus[] = ['menu_number'=>$catToMenu[$catKey],'title'=>$entry['name'],'allergens'=>$entry['allergens']??'','price'=>(float)($entry['price']??$prices[$catToMenu[$catKey]]),'available'=>true];
                } elseif (isset($catToAddon[$catKey])) {
                    $addons[] = ['code'=>$catToAddon[$catKey],'name'=>$entry['name'],'price'=>(float)($entry['price']??$aprices[$catToAddon[$catKey]])];
                }
            }
            $dayMap[$dayIdx] = ['menus'=>$menus,'addons'=>$addons];
        }
    } else {
        if (!empty($plan['days'])) {
            foreach ($plan['days'] as $d) {
                $dow = (int)(new DateTime($d['date']))->format('N') - 1;
                $dayMap[$dow] = $d;
            }
        }
        $prices  = $plan['prices']       ?? $prices;
        $aprices = $plan['addon_prices'] ?? $aprices;
    }
}
?>
<?php
// ── Seiten-Variablen für header.php ──────────────────────────────
$page_title       = 'Wochenspeiseplan KW ' . $kw . '/' . $year . ' – BMV-Menüdienst';
$meta_description = 'Wochenspeiseplan KW ' . $kw . '/' . $year . ' von BMV-Menüdienst. Frische Menüs für Potsdam und Werder (Havel).';
$active_nav       = 'speiseplan';
$canonical        = 'https://www.bmv-kantinen.de/speiseplan/?year=' . $year . '&kw=' . $kw;
require_once __DIR__ . '/../includes/header.php';
?>
<style>
/* ═══════════════════════════════════════════════════
   SPEISEPLAN-SPEZIFISCHE STYLES
   (nur seitenspezifisch — globale Styles kommen aus bmv-premium.css + bmv-overrides.css)
═══════════════════════════════════════════════════ */
:root {
  --navy:    #0B2A5B;
  --navy2:   #1a3f7a;
  --orange:  #D95A00;
  --orange2: #f06820;
  --bg:      #F8FAFC;
  --card:    #ffffff;
  --border:  #dde4ef;
  --text:    #1a2535;
  --muted:   #5a6a82;
  --green:   #16a34a;
  --serif:   'Bricolage Grotesque', Georgia, serif;
  --sans:    'DM Sans', system-ui, sans-serif;
  --r:       16px;
  --sh:      0 2px 8px rgba(11,42,91,.10);
  --sh2:     0 8px 32px rgba(11,42,91,.14);
}

/* ═══════════════════════════════════════════════════
   PRINT BUTTONS (nur Screen)
═══════════════════════════════════════════════════ */
.screen-only { }
@media print { .screen-only { display: none !important; } }

/* ═══════════════════════════════════════════════════
   PAGE-SPECIFIC: WEEK NAV
═══════════════════════════════════════════════════ */
.week-nav {
  background: #fff;
  border-bottom: 2px solid var(--border);
  padding: 24px 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 28px;
  flex-wrap: wrap;
}

.week-nav-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  background: var(--navy);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 14px 28px;
  min-height: 44px;
  font-size: 1.1rem;
  font-weight: 600;
  font-family: var(--sans);
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
  white-space: nowrap;
}

.week-nav-btn:hover {
  background: var(--navy2);
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(11,42,91,.15);
}

.week-nav-btn:active {
  transform: translateY(0);
}

.week-nav-btn.disabled {
  opacity: 0.35;
  pointer-events: none;
  cursor: default;
}

.week-info {
  text-align: center;
  min-width: 280px;
  padding: 0 20px;
}

.week-info .kw-label {
  font-family: var(--serif);
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--navy);
  line-height: 1;
}

.week-info .date-range {
  font-size: 0.95rem;
  color: var(--muted);
  margin-top: 6px;
}



/* ═══════════════════════════════════════════════════
   HAUPTINHALT
═══════════════════════════════════════════════════ */
.main {
  max-width: 1400px;
  margin: 0 auto;
  padding: 32px 40px 60px;
}

@media (max-width: 768px) {
  .main {
    padding: 24px 16px 48px;
  }
}

@media (max-width: 480px) {
  .main {
    padding: 20px 12px 40px;
  }
}

/* ═══════════════════════════════════════════════════
   KEINE DATEN
═══════════════════════════════════════════════════ */
.no-data {
  text-align: center;
  padding: 80px 40px;
  background: #fff;
  border-radius: var(--r);
  border: 2px dashed var(--border);
  margin-top: 32px;
}
.no-data h2 {
  font-family: var(--serif);
  font-size: 1.6rem; color: var(--muted);
  margin-bottom: 12px;
}
.no-data p { color: var(--muted); font-size: 1.1rem; }

/* ═══════════════════════════════════════════════════
   TAGESKARTEN
═══════════════════════════════════════════════════ */
.days-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

@media (max-width: 768px) {
  .days-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 32px;
  }
}

@media (max-width: 480px) {
  .days-grid {
    grid-template-columns: 1fr;
    gap: 12px;
    margin-bottom: 24px;
  }
}
.day-card {
  background: var(--card);
  border-radius: var(--r);
  box-shadow: var(--sh);
  overflow: hidden;
  border: 1.5px solid var(--border);
  transition: all 0.2s ease;
}
.day-card:hover {
  box-shadow: var(--sh2);
  transform: translateY(-2px);
}
.day-card.empty { opacity: 0.6; }
.day-card.weekend { background: #f8f9ff; border-color: #c8d4f0; }
.day-header.holiday { background: #fef3c7; border-bottom: 3px solid var(--orange); }
.holiday-badge {
  display: block; width: 100%;
  font-size: .75rem; color: var(--orange); font-weight: 600;
  margin-top: 2px;
}

.day-header {
  background: var(--navy);
  padding: 14px 20px;
  display: flex; align-items: baseline; gap: 12px;
}
.day-name {
  font-family: var(--serif);
  font-size: 1.25rem; font-weight: 700;
  color: #fff;
}
.day-date {
  font-size: .85rem;
  color: rgba(255,255,255,.7);
}

.menu-list { padding: 0; }
.menu-item {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 20px;
  border-bottom: 1px solid var(--border);
  cursor: pointer;
  transition: background .15s;
  position: relative;
}
.menu-item:last-child { border-bottom: none; }
.menu-item:hover { background: #f0f5ff; }
.menu-item.addon { background: #fafbff; }
.menu-item.addon:hover { background: #f0f5ff; }

.menu-img {
  width: 64px; height: 64px;
  border-radius: 8px;
  object-fit: cover;
  flex-shrink: 0;
  background: var(--border);
}
.menu-img-placeholder {
  width: 64px;
  height: 64px;
  border-radius: 8px;
  flex-shrink: 0;
  background: #dce5f5;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: rgba(11,42,91,.4);
}

.menu-content { flex: 1; min-width: 0; }
.menu-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 6px;
  background: var(--navy);
  color: #fff;
  font-size: 0.75rem;
  font-weight: 700;
  margin-bottom: 5px;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(11,42,91,.15);
}
.menu-badge.addon-badge {
  background: var(--orange);
  font-size: .65rem; width: 26px; height: 26px;
}
.menu-title {
  font-size: 1rem; font-weight: 600;
  color: var(--text); line-height: 1.3;
}
.menu-title.empty-title {
  color: var(--muted); font-style: italic; font-weight: 400;
}
.menu-price {
  font-size: .85rem; color: var(--orange);
  font-weight: 600; margin-top: 3px;
}
.menu-alg {
  font-size: .75rem; color: var(--muted); margin-top: 2px;
}
.veg-badge {
  display: inline-block;
  background: #dcfce7; color: var(--green);
  font-size: .7rem; font-weight: 700;
  padding: 1px 7px; border-radius: 20px;
  margin-left: 6px; vertical-align: middle;
}

/* ═══════════════════════════════════════════════════
   ZUSATZKARTE (Addon-Sektion)
═══════════════════════════════════════════════════ */
.addon-section {
  background: var(--card);
  border-radius: var(--r);
  box-shadow: var(--sh);
  border: 1.5px solid var(--border);
  overflow: hidden;
  margin-bottom: 40px;
}

@media (max-width: 768px) {
  .addon-section {
    display: none;
  }
}

.addon-section-header {
  background: var(--orange);
  padding: 16px 24px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.addon-section-header h2 {
  font-family: var(--serif);
  font-size: 1.25rem; font-weight: 700; color: #fff;
}
.addon-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  border-top: 1px solid var(--border);
}
.addon-col-header {
  padding: 12px 16px;
  background: #f5f7fb;
  font-weight: 700; font-size: .85rem;
  color: var(--navy);
  border-right: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
}
.addon-col-header:last-child { border-right: none; }
.addon-cell {
  padding: 12px 16px;
  border-right: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
  font-size: .95rem; vertical-align: top;
  display: flex; align-items: flex-start; gap: 10px;
}
.addon-cell:last-child { border-right: none; }
.addon-cell .day-label {
  font-weight: 700; color: var(--navy); min-width: 28px;
}
.addon-cell .addon-img {
  width: 44px; height: 44px; border-radius: 6px;
  object-fit: cover; flex-shrink: 0;
  background: var(--border);
}

/* ═══════════════════════════════════════════════════
   POPUP / MODAL
═══════════════════════════════════════════════════ */
.popup-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(11,42,91,.35);
  backdrop-filter: blur(4px);
  z-index: 1000;
  align-items: center;
  justify-content: center;
}
.popup-overlay.open { display: flex; }
.popup-box {
  background: #fff; border-radius: 16px;
  box-shadow: 0 20px 60px rgba(11,42,91,.25);
  width: min(560px, 95vw);
  overflow: hidden; animation: popIn .2s ease;
}
@keyframes popIn {
  from { transform: scale(.95); opacity: 0; }
  to   { transform: scale(1);   opacity: 1; }
}
.popup-img {
  width: 100%; height: 220px; object-fit: cover;
}
.popup-body { padding: 28px; }
.popup-badge {
  display: inline-block;
  background: var(--navy); color: #fff;
  font-size: .8rem; font-weight: 700;
  padding: 4px 12px; border-radius: 20px;
  margin-bottom: 12px;
}
.popup-title {
  font-family: var(--serif);
  font-size: 1.5rem; font-weight: 700;
  color: var(--text); margin-bottom: 8px;
}
.popup-price {
  font-size: 1.1rem; color: var(--orange); font-weight: 600;
  margin-bottom: 16px;
}
.popup-alg {
  font-size: .9rem; color: var(--muted);
  background: #f5f7fb; padding: 10px 14px; border-radius: 8px;
}
.popup-close {
  display: block; width: 100%; margin-top: 20px;
  background: var(--navy); color: #fff; border: none;
  padding: 14px; border-radius: 8px;
  font-family: var(--sans); font-size: 1rem; font-weight: 600;
  cursor: pointer; transition: background .2s;
}
.popup-close:hover { background: var(--navy2); }

/* ═══════════════════════════════════════════════════════════
   DRUCKLAYOUT – Auto-Scaling A4 Querformat
   297mm × 210mm, Rand 4mm → Nutzfläche 289mm × 202mm
   CSS-Grid Layout, clamp() Schriften, JS-Skalierung
═══════════════════════════════════════════════════════════ */

/* ── Screen: Print-Button ── */
.print-btn-wrap {
  display: flex; justify-content: flex-end;
  padding: 0 40px 0 0;
}

/* ── Print-Layout: im Screen unsichtbar ── */
.print-layout { display: none; }

@media print {

  /* ── Seite: A4 Querformat, 8mm Rand ── */
  @page {
    size: A4 landscape;
    margin: 8mm 8mm 8mm 8mm;
  }

  /* ── Alles ausblenden außer Print-Layout ── */
  *, *::before, *::after {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    box-sizing: border-box;
  }

  .screen-only, .site-header, .week-nav,
  .main, .popup-overlay, .print-btn-wrap,
  img.menu-img, .menu-img-placeholder,
  .pexels-img { display: none !important; }

  html, body {
    margin: 0; padding: 0;
    background: #fff !important;
    font-family: Arial, Helvetica, sans-serif;
    color: #000;
    font-size: 10pt;
  }

  /* ── Print-Layout einblenden, KEIN JS-Scaling ── */
  .print-layout {
    display: block !important;
    width: 277mm; /* 297mm - 2×8mm Rand = 281mm, etwas Puffer */
    max-width: 100%;
    overflow: visible;
    position: static;
    transform: none !important; /* JS-Scale deaktivieren */
  }

  .print-scale-wrap {
    transform: none !important;
    width: 100%;
  }

  /* ── Header: Firmenlogo + Kontakt ── */
  .ph-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #0B2A5B !important;
    border-radius: 3mm;
    padding: 3mm 5mm;
    margin-bottom: 3mm;
    min-height: 14mm;
  }
  .ph-logo-wrap { display: flex; align-items: center; gap: 3mm; }
  .ph-logo { height: 10mm; width: auto; filter: brightness(0) invert(1); }
  .ph-company-name { font-size: 14pt; font-weight: 700; color: #fff; line-height: 1.1; }
  .ph-company-sub  { font-size: 7pt; color: rgba(255,255,255,.85); margin-top: 1px; }
  .ph-contact { color: #fff; font-size: 7pt; text-align: right; line-height: 1.8; }

  /* ── KW-Titel ── */
  .ph-title {
    text-align: center;
    font-size: 12pt;
    font-weight: 700;
    color: #0B2A5B !important;
    margin-bottom: 3mm;
    line-height: 1.1;
  }

  /* ── Body-Grid: Haupttabellen + Bestellblock ── */
  .ph-body {
    display: grid;
    grid-template-columns: 1fr 50mm;
    gap: 4mm;
    align-items: start;
  }
  .ph-main { min-width: 0; }

  /* ── CSS-Grid Speiseplan ── */
  .ph-grid {
    display: grid;
    grid-template-columns: 12mm repeat(var(--day-count, 7), 1fr);
    width: 100%;
    border: .5pt solid #0B2A5B;
  }

  /* ── Alle Zellen ── */
  .ph-cell {
    border: .4pt solid #bbb;
    padding: 1.2mm 1.5mm;
    overflow: hidden;
    font-size: 7pt;         /* FEST — kein clamp, kein vw */
    line-height: 1.3;
    word-break: break-word;
    page-break-inside: avoid;
    break-inside: avoid;
    min-height: 7mm;
  }

  /* ── Spalten-Header (Tage) ── */
  .ph-th {
    background: #0B2A5B !important;
    color: #fff !important;
    font-weight: 700;
    text-align: center;
    padding: 1.5mm 1mm;
    font-size: 7.5pt;
    line-height: 1.2;
    border: .4pt solid #0B2A5B;
  }
  .ph-th-orange {
    background: #D95A00 !important;
    color: #fff !important;
    font-weight: 700;
    text-align: center;
    padding: 1.5mm 1mm;
    font-size: 7.5pt;
    line-height: 1.2;
    border: .4pt solid #D95A00;
  }
  .ph-price-sub {
    display: block;
    font-size: 6pt;
    font-weight: 400;
    opacity: .9;
  }

  /* ── Tag-Header ── */
  .ph-day-th {
    background: #0B2A5B !important;
    color: #fff !important;
    text-align: center;
    font-size: 7.5pt;
    font-weight: 700;
    padding: 1.5mm 1mm;
    border: .4pt solid #0B2A5B;
    line-height: 1.3;
  }
  .ph-day-th .ph-day-date {
    display: block;
    font-weight: 400;
    font-size: 6pt;
    opacity: .85;
  }
  .ph-day-th.holiday {
    background: #D95A00 !important;
    border-color: #D95A00;
  }
  .ph-day-th .ph-holiday-name {
    display: block;
    font-size: 5.5pt;
    font-weight: 400;
    opacity: .9;
  }

  /* ── Zeilen-Label (M1/M2/M3/M4) — BREITER, LESBARER ── */
  .ph-row-label {
    background: #edf1f9 !important;
    font-weight: 700;
    color: #0B2A5B !important;
    font-size: 6.5pt;      /* FEST */
    padding: 1mm .8mm;
    text-align: center;
    vertical-align: middle;
    display: flex;
    align-items: center;
    justify-content: center;
    border: .4pt solid #bbb;
    writing-mode: vertical-rl;
    text-orientation: mixed;
    white-space: nowrap;
  }

  /* ── Gericht-Inhalt ── */
  .ph-dish {
    font-weight: 700;
    font-size: 7pt;        /* FEST */
    color: #000;
    display: block;
  }
  .ph-alg {
    font-size: 5.5pt;      /* FEST */
    color: #555;
    display: block;
    margin-top: .3mm;
  }
  .ph-price {
    font-size: 6pt;        /* FEST */
    color: #D95A00 !important;
    font-weight: 700;
    display: block;
    margin-top: .3mm;
  }
  .ph-cell.na {
    background: #f5f5f5 !important;
    color: #ccc !important;
    text-align: center;
    vertical-align: middle;
    font-size: 6pt;
  }
  .ph-cell.holiday-cell { background: #fff8f0 !important; }
  .ph-holiday-note {
    font-size: 5.5pt;
    color: #D95A00 !important;
    font-style: italic;
    display: block;
  }

  /* ── Zusatzkarte-Titel ── */
  .ph-section-title {
    font-size: 7pt;
    font-weight: 700;
    color: #D95A00 !important;
    text-transform: uppercase;
    letter-spacing: .2mm;
    margin: 2mm 0 1mm;
  }

  /* ── Footer Hinweise ── */
  .ph-footer {
    margin-top: 2mm;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5mm;
    font-size: 5pt;
    line-height: 1.4;
    page-break-inside: avoid;
    break-inside: avoid;
  }
  .ph-footer-box {
    padding: 1mm 1.5mm;
    background: #f8f9fa !important;
    border-left: 1.2mm solid #0B2A5B !important;
    border-radius: 1mm;
  }
  .ph-footer-box:nth-child(2) { border-left-color: #D95A00 !important; }
  .ph-footer-box strong { display: block; margin-bottom: .3mm; font-size: 5.5pt; }
  .ph-footer-legal {
    grid-column: 1/-1;
    text-align: center;
    padding: 1mm;
    background: #e9ecef !important;
    border-radius: 1mm;
    font-size: 4.5pt;
  }

  /* ── Bestellblock ── */
  .ph-order {
    width: 50mm;
    border-left: 1.5mm dashed #D95A00 !important;
    padding-left: 3mm;
    position: relative;
    font-size: 6pt;
    page-break-inside: avoid;
    break-inside: avoid;
  }
  .ph-scissors {
    position: absolute; left: -3.5mm; top: 50%;
    transform: translateY(-50%);
    font-size: 9pt; color: #D95A00 !important;
  }
  .ph-order-title {
    font-weight: 700; text-align: center;
    font-size: 8.5pt;
    color: #0B2A5B !important;
    text-transform: uppercase;
    letter-spacing: .2mm;
    margin-bottom: 1mm;
  }
  .ph-order-sub {
    text-align: center; font-size: 5pt;
    color: #D95A00 !important;
    font-style: italic; margin-bottom: 2mm;
  }
  .ph-order-table {
    width: 100%; border-collapse: collapse;
    margin-bottom: 1.5mm; table-layout: fixed;
    font-size: 5.5pt;
  }
  .ph-order-table th {
    background: #D95A00 !important; color: #fff !important;
    padding: .8mm .3mm; border: .4pt solid #D95A00;
    text-align: center; font-size: 5.5pt;
  }
  .ph-order-table td {
    height: 5mm; text-align: center;
    border: .4pt solid #ccc; background: #fff !important;
    font-size: 5.5pt;
  }
  .ph-order-table td.dc {
    background: #edf1f9 !important; font-weight: 700;
    color: #0B2A5B !important; width: 7mm; vertical-align: middle;
  }
  .ph-order-table td.ft { background: #fff8f0 !important; }
  .ph-divider {
    border-top: .6pt solid #0B2A5B; margin: 1.5mm 0; position: relative;
  }
  .ph-divider::after {
    content: '✂'; position: absolute; left: 50%; top: -2.5mm;
    transform: translateX(-50%); background: #fff; padding: 0 1mm;
    color: #D95A00 !important; font-size: 8pt;
  }
  .ph-cust { margin-top: 1.5mm; font-size: 5.5pt; }
  .ph-cust-row { margin-bottom: 1.5mm; }
  .ph-cust-label {
    font-weight: 700; display: block;
    color: #0B2A5B !important; font-size: 5.5pt; margin-bottom: .3mm;
  }
  .ph-cust-line { border-bottom: .7pt solid #333; height: 4mm; }
  .ph-notes-label {
    font-weight: 700; color: #0B2A5B !important;
    font-size: 5.5pt; display: block;
    margin: 1.5mm 0 .5mm;
  }
  .ph-notes-area {
    border: .4pt solid #ccc; height: 8mm; border-radius: 1mm;
  }
  .ph-addr {
    margin-top: 1.5mm; font-size: 5pt; line-height: 1.5;
    padding: 1mm 1.5mm;
    background: #f0f4fa !important;
    border-radius: 1mm; border: .4pt solid #dde4ef;
  }
  .ph-addr strong {
    color: #0B2A5B !important; display: block;
    font-size: 5.5pt; margin-bottom: .3mm;
  }
  .ph-addr-tel { color: #D95A00 !important; font-weight: 700; }
}
/* Drucklayout nur bei Druck sichtbar */
.print-layout { display: none; }
</style>

<!-- ═══════════════════════════════════════════════
     SPEISEPLAN CONTENT
═══════════════════════════════════════════════════ -->
<!-- ═══════════════════════════════════════════════
     WOCHENNAVIGATION (Screen)
═══════════════════════════════════════════════════ -->
<nav class="week-nav screen-only" style="display: flex; justify-content: space-between; align-items: center; padding: 16px 40px;">
  <a href="?year=<?= $prevYear ?>&kw=<?= $prevKW ?>"
     class="week-nav-btn <?= $isAtMin ? 'disabled' : '' ?>">
    ← Vorherige Woche
  </a>
  <div class="week-info">
    <div class="kw-label">KW <?= $kw ?> / <?= $year ?></div>
    <div class="date-range">
      <?= $days[0]->format('d.m.') ?> – <?= $days[6]->format('d.m.Y') ?>
    </div>
  </div>
  <button class="print-btn" onclick="doPrint()" style="margin-left: auto; padding: 12px 20px; font-size: 0.9rem;">🖨️ Drucken</button>
  <a href="?year=<?= $nextYear ?>&kw=<?= $nextKW ?>"
     class="week-nav-btn <?= $isAtMax ? 'disabled' : '' ?>">
    Nächste Woche →
  </a>
</nav>

<!-- ═══════════════════════════════════════════════
     HAUPTINHALT (Screen)
═══════════════════════════════════════════════════ -->
<main class="main screen-only">

<?php if (!$plan): ?>
<div class="no-data">
  <h2>Kein Speiseplan vorhanden</h2>
  <p>Für KW <?= $kw ?>/<?= $year ?> wurde noch kein Speiseplan eingetragen.</p>
</div>
<?php else: ?>

<!-- Tageskarten -->
<div class="days-grid">
<?php for ($i = 0; $i < 7; $i++):
  $day       = $dayMap[$i] ?? null;
  $hasData   = $day && (!empty($day['menus']) || !empty($day['addons']));
  $dateStr   = $days[$i]->format('d.m.Y');
  $isoDate   = $days[$i]->format('Y-m-d');
  $feiertag  = $feiertage[$isoDate] ?? null;
  $isWeekend = $i >= 5;
?>
<div class="day-card <?= !$hasData ? 'empty' : '' ?> <?= ($isWeekend||$feiertag) ? 'weekend' : '' ?>">
  <div class="day-header <?= $feiertag ? 'holiday' : '' ?>">
    <span class="day-name"><?= $dayNames[$i] ?></span>
    <span class="day-date"><?= $dateStr ?></span>
    <?php if ($feiertag): ?><span class="holiday-badge">🎉 <?= htmlspecialchars($feiertag) ?></span><?php endif; ?>
  </div>
  <div class="menu-list">
  <?php
  $menuDefs = [
    1 => ['label'=>'M1','name'=>'Vollkost',    'price'=>$prices[1]??6.20],
    2 => ['label'=>'M2','name'=>'Leichte Kost','price'=>$prices[2]??6.40],
    3 => ['label'=>'M3','name'=>'Premium',     'price'=>$prices[3]??7.20],
    4 => ['label'=>'M4','name'=>'Tagesmenü',   'price'=>$prices[4]??6.20],
  ];
  foreach ($menuDefs as $n => $def):
    $m = getMenu($day, $n);
    $title = $m['title'] ?? null;
    $alg   = $m['allergens'] ?? '';
    $price = isset($m['price']) ? $m['price'] : $def['price'];
    $veg   = !empty($m['vegetarian']);
    $imgQ  = $title ? dishSearchQuery($title) : 'food dish';
    $empty = !$title;
  ?>
  <div class="menu-item" <?= !$empty ? "onclick=\"openPopup(".json_encode([
    'badge' => $def['label'].' – '.$def['name'],
    'title' => $title,
    'price' => number_format($price,2,',','.').' €',
    'alg'   => $alg,
    'img'   => $imgQ,
    'veg'   => $veg,
  ])."')\"" : '' ?>>
    <?php if ($title): ?>
    <img class="menu-img pexels-img" loading="lazy"
         src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Crect width='80' height='80' fill='%23edf1f9'/%3E%3C/svg%3E"
         data-query="<?= htmlspecialchars(dishSearchQuery($title)) ?>"
         alt="<?= htmlspecialchars($title) ?>"
         onerror="this.style.opacity='.3'">
    <?php else: ?>
    <div class="menu-img-placeholder">—</div>
    <?php endif; ?>
    <div class="menu-content">
      <div class="menu-badge"><?= $def['label'] ?></div>
      <div class="menu-title <?= $empty ? 'empty-title' : '' ?>">
        <?= $title ? htmlspecialchars($title) : $def['name'].' nicht verfügbar' ?>
        <?php if ($veg): ?><span class="veg-badge">veg.</span><?php endif; ?>
      </div>
      <?php if ($title): ?>
      <div class="menu-price"><?= number_format($price,2,',','.').' €' ?></div>
      <?php if ($alg): ?><div class="menu-alg">(<?= htmlspecialchars($alg) ?>)</div><?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>

  <?php
  $addonDefs = [
    'D' => ['label'=>'De','name'=>'Dessert',     'price'=>$aprices['D']??1.80, 'icon'=>'🍮'],
    'R' => ['label'=>'Ro','name'=>'Rohkost',     'price'=>$aprices['R']??1.80, 'icon'=>'🥗'],
    'A' => ['label'=>'Ab','name'=>'Abendessen',  'price'=>$aprices['A']??5.50, 'icon'=>'🍽️'],
    'S' => ['label'=>'Sa','name'=>'Salatteller', 'price'=>$aprices['S']??5.50, 'icon'=>'🥙'],
  ];
  if ($i < 5): // Addons nur Mo–Fr
  foreach ($addonDefs as $code => $def):
    $a = getAddon($day, $code);
    $title = $a['name'] ?? null;
    $price = $a['price'] ?? $def['price'];
    $imgQ  = $title ? dishSearchQuery($title) : 'food dish';
  ?>
  <div class="menu-item addon" <?= $title ? "onclick=\"openPopup(".json_encode([
    'badge' => $def['icon'].' '.$def['name'],
    'title' => $title,
    'price' => number_format($price,2,',','.').' €',
    'alg'   => '',
    'img'   => $imgQ,
    'veg'   => false,
  ])."')\"" : '' ?>>
    <?php if ($title): ?>
    <img class="menu-img pexels-img" loading="lazy"
         src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Crect width='80' height='80' fill='%23edf1f9'/%3E%3C/svg%3E"
         data-query="<?= htmlspecialchars(dishSearchQuery($title)) ?>"
         alt="<?= htmlspecialchars($title) ?>"
         onerror="this.style.opacity='.3'">
    <?php else: ?>
    <div class="menu-img-placeholder"><?= $def['icon'] ?></div>
    <?php endif; ?>
    <div class="menu-content">
      <div class="menu-badge addon-badge"><?= $def['label'] ?></div>
      <div class="menu-title <?= !$title ? 'empty-title' : '' ?>">
        <?= $title ? htmlspecialchars($title) : $def['name'].' nicht verfügbar' ?>
      </div>
      <?php if ($title): ?>
      <div class="menu-price"><?= number_format($price,2,',','.').' €' ?></div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; endif; ?>
  </div>
</div>
<?php endfor; ?>
</div><!-- .days-grid -->

<?php endif; ?>
</main>

<!-- ═══════════════════════════════════════════════
     POPUP
═══════════════════════════════════════════════════ -->
<div class="popup-overlay screen-only" id="popup" onclick="if(event.target===this)closePopup()">
  <div class="popup-box">
    <img class="popup-img" id="popup-img" src="" alt="">
    <div class="popup-body">
      <div class="popup-badge" id="popup-badge"></div>
      <div class="popup-title" id="popup-title"></div>
      <div class="popup-price" id="popup-price"></div>
      <div class="popup-alg"  id="popup-alg"  style="display:none"></div>
      <button class="popup-close" onclick="closePopup()">Schließen</button>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════
     DRUCKLAYOUT (nur beim Drucken sichtbar)
═══════════════════════════════════════════════════ -->
<div class="print-layout" id="print-layout">
<div class="print-scale-wrap" id="print-scale-wrap">

  <!-- ── Header ── -->
  <div class="ph-header">
    <div class="ph-logo-wrap">
      <img class="ph-logo" src="/assets/images/BMV_Logo_n.svg" alt="BMV">
      <div>
        <div class="ph-company-name">BMV-Menüdienst</div>
        <div class="ph-company-sub">Frisch · Regional · Gesund</div>
      </div>
    </div>
    <div class="ph-contact">
      Tel.: 03327 – 57 45 066<br>
      info@bmv-kantinen.de · www.bmv-kantinen.de
    </div>
  </div>

  <!-- ── KW-Titel ── -->
  <div class="ph-title">
    Wochenspeiseplan – KW <?= $kw ?>/<?= $year ?>
    &nbsp;·&nbsp; <?= $days[0]->format('d.m.') ?> – <?= $days[6]->format('d.m.Y') ?>
  </div>

  <!-- ── Haupt-Body: Speiseplan + Bestellblock ── -->
  <div class="ph-body">
    <div class="ph-main">

      <!-- ────────────────────────────────────────
           HAUPTTABELLE: CSS-Grid Mo–So × M1–M4
           grid-template-columns: 10mm repeat(7,1fr)
           Zeile 1: Ecke + 7 Tag-Header
           Zeile 2–5: Menü-Label + 7 Gerichte
      ──────────────────────────────────────── -->
      <div class="ph-grid" style="--day-count:7">

        <!-- Zeile 1: Ecke + Tag-Header -->
        <div class="ph-th" style="grid-column:1;grid-row:1"></div>
        <?php for ($i = 0; $i < 7; $i++):
          $iso = $days[$i]->format('Y-m-d');
          $ft  = $feiertage[$iso] ?? null;
          $isWe = $i >= 5;
        ?>
        <div class="ph-day-th <?= $ft ? 'holiday' : '' ?>" style="grid-column:<?= $i+2 ?>;grid-row:1">
          <?= $dayShort[$i] ?>
          <span class="ph-day-date"><?= $days[$i]->format('d.m.') ?></span>
          <?php if ($ft): ?>
          <span class="ph-holiday-name"><?= htmlspecialchars(mb_substr($ft,0,12)) ?></span>
          <?php elseif ($isWe): ?>
          <span class="ph-holiday-name" style="opacity:.6">Wochenende</span>
          <?php endif; ?>
        </div>
        <?php endfor; ?>

        <!-- Zeilen 2–5: M1–M4 -->
        <?php
        $menuDefs = [
          1 => ['label'=>'M1','name'=>'Vollkost',     'price'=>$prices[1]??7.50],
          2 => ['label'=>'M2','name'=>'Leichte Kost', 'price'=>$prices[2]??7.20],
          3 => ['label'=>'M3','name'=>'Premium',      'price'=>$prices[3]??9.80],
          4 => ['label'=>'M4','name'=>'Tagesmenü',    'price'=>$prices[4]??6.50],
        ];
        foreach ($menuDefs as $n => $def):
          $row = $n + 1; // Zeile 2–5
        ?>
        <!-- Row-Label -->
        <div class="ph-row-label" style="grid-column:1;grid-row:<?= $row ?>">
          <?= $def['label'] ?><br><span style="font-size:3.5pt;font-weight:400"><?= $def['name'] ?><br><?= number_format($def['price'],2,',','.').' €' ?></span>
        </div>
        <!-- Gericht-Zellen -->
        <?php for ($i = 0; $i < 7; $i++):
          $day     = $dayMap[$i] ?? null;
          $iso     = $days[$i]->format('Y-m-d');
          $ft      = $feiertage[$iso] ?? null;
          $m       = getMenu($day, $n);
          $title   = $m['title']     ?? null;
          $alg     = $m['allergens'] ?? '';
          $p       = isset($m['price']) ? number_format($m['price'],2,',','.').' €' : '';
          $isWe    = $i >= 5;
          $col     = $i + 2;
        ?>
        <?php if ($ft && !$title): ?>
        <div class="ph-cell holiday-cell" style="grid-column:<?= $col ?>;grid-row:<?= $row ?>">
          <?php if ($n===1): ?><span class="ph-holiday-note"><?= htmlspecialchars($ft) ?></span><?php endif; ?>
        </div>
        <?php elseif ($title): ?>
        <div class="ph-cell" style="grid-column:<?= $col ?>;grid-row:<?= $row ?>">
          <span class="ph-dish"><?= htmlspecialchars($title) ?></span>
          <?php if ($alg): ?><span class="ph-alg">(<?= htmlspecialchars($alg) ?>)</span><?php endif; ?>
          <?php if ($p): ?><span class="ph-price"><?= $p ?></span><?php endif; ?>
        </div>
        <?php else: ?>
        <div class="ph-cell na" style="grid-column:<?= $col ?>;grid-row:<?= $row ?>">—</div>
        <?php endif; ?>
        <?php endfor; // Tage ?>
        <?php endforeach; // Menüs ?>

      </div><!-- .ph-grid -->

      <!-- ────────────────────────────────────────
           ZUSATZKARTE: NUR Mo–Fr (Spalten 0–4)
           CSS-Grid: 10mm + repeat(5,1fr)
      ──────────────────────────────────────── -->
      <div class="ph-section-title">Zusatzkarte</div>
      <div class="ph-grid" style="--day-count:5">

        <!-- Zeile 1: Ecke + Mo–Fr Header -->
        <div class="ph-th-orange" style="grid-column:1;grid-row:1"></div>
        <?php for ($i = 0; $i < 5; $i++):
          $iso = $days[$i]->format('Y-m-d');
          $ft  = $feiertage[$iso] ?? null;
        ?>
        <div class="ph-day-th <?= $ft ? 'holiday' : '' ?>" style="grid-column:<?= $i+2 ?>;grid-row:1;background:<?= $ft ? '#D95A00' : '#D95A00' ?> !important;border-color:<?= $ft ? '#b34800' : '#D95A00' ?> !important">
          <?= $dayShort[$i] ?>
          <span class="ph-day-date"><?= $days[$i]->format('d.m.') ?></span>
          <?php if ($ft): ?><span class="ph-holiday-name"><?= htmlspecialchars(mb_substr($ft,0,10)) ?></span><?php endif; ?>
        </div>
        <?php endfor; ?>

        <!-- Zeilen 2–5: D,R,A,S -->
        <?php
        $addonDefs = [
          'D' => ['label'=>'De','name'=>'Dessert',    'price'=>$aprices['D']??1.80],
          'R' => ['label'=>'Ro','name'=>'Rohkost',    'price'=>$aprices['R']??1.80],
          'A' => ['label'=>'Ab','name'=>'Abendessen', 'price'=>$aprices['A']??5.50],
          'S' => ['label'=>'Sa','name'=>'Salatteller','price'=>$aprices['S']??5.50],
        ];
        $aRow = 1;
        foreach ($addonDefs as $code => $def):
          $aRow++;
        ?>
        <div class="ph-row-label" style="grid-column:1;grid-row:<?= $aRow ?>;background:#fff3e8 !important;color:#D95A00 !important">
          <?= $def['label'] ?><br><span style="font-size:3.5pt;font-weight:400"><?= $def['name'] ?><br><?= number_format($def['price'],2,',','.').' €' ?></span>
        </div>
        <?php for ($i = 0; $i < 5; $i++):
          $day  = $dayMap[$i] ?? null;
          $iso  = $days[$i]->format('Y-m-d');
          $ft   = $feiertage[$iso] ?? null;
          $a    = getAddon($day, $code);
          $name = $a['name'] ?? null;
          $p    = isset($a['price']) ? number_format($a['price'],2,',','.').' €' : '';
          $col  = $i + 2;
        ?>
        <?php if ($ft && !$name): ?>
        <div class="ph-cell holiday-cell" style="grid-column:<?= $col ?>;grid-row:<?= $aRow ?>">
          <?php if ($code==='D'): ?><span class="ph-holiday-note" style="font-size:3.5pt"><?= htmlspecialchars(mb_substr($ft,0,12)) ?></span><?php endif; ?>
        </div>
        <?php elseif ($name): ?>
        <div class="ph-cell" style="grid-column:<?= $col ?>;grid-row:<?= $aRow ?>">
          <span class="ph-dish"><?= htmlspecialchars($name) ?></span>
          <?php if ($p): ?><span class="ph-price"><?= $p ?></span><?php endif; ?>
        </div>
        <?php else: ?>
        <div class="ph-cell na" style="grid-column:<?= $col ?>;grid-row:<?= $aRow ?>">—</div>
        <?php endif; ?>
        <?php endfor; ?>
        <?php endforeach; ?>

      </div><!-- .ph-grid Zusatz -->

      <!-- ── Footer ── -->
      <div class="ph-footer">
        <div class="ph-footer-box">
          <strong>Zusatzstoffe:</strong>
          1) Geschmacksverstärker · 2) Antioxidationsmittel · 3) Süßungsmittel ·
          4) Konservierungsmittel · 9) Milch/Sahne · 10) Formfleisch ·
          11) Nitritpökelsalz · 12) Phosphat
        </div>
        <div class="ph-footer-box">
          <strong>Allergene:</strong>
          13) Getreide/Weizen · 16) Fisch · 18) Eier · 19) Senf ·
          21) Soja · 22) Sellerie · 23) Schalenfrüchte · 24) Erdnüsse · 25) Schwefeldioxid
        </div>
        <div class="ph-footer-legal">
          Bestellung Mo.–So. 9–15 Uhr &nbsp;|&nbsp;
          Menüs zum Verzehr am Liefertag &nbsp;|&nbsp;
          Keine Lieferung an Feiertagen &nbsp;|&nbsp;
          AGB: www.bmv-kantinen.de
        </div>
      </div>

    </div><!-- .ph-main -->

    <!-- ────────────────────────────────────
         BESTELLBLOCK rechts (46mm)
    ──────────────────────────────────── -->
    <div class="ph-order">
      <span class="ph-scissors">✂</span>
      <div class="ph-order-title">Bestellung BMV-Menüdienst</div>
      <div class="ph-order-sub">Abtrennen &amp; Fahrer mitgeben</div>

      <!-- Hauptmenüs Mo–So -->
      <table class="ph-order-table">
        <thead>
          <tr><th>Tag</th><th>M1</th><th>M2</th><th>M3</th><th>M4</th></tr>
        </thead>
        <tbody>
          <?php for ($i=0; $i<7; $i++):
            $ft = $feiertage[$days[$i]->format('Y-m-d')] ?? null;
          ?>
          <tr>
            <td class="dc <?= $ft?'ft':'' ?>"><?= $dayShort[$i] ?></td>
            <?php if ($ft): ?>
            <td class="ft" colspan="4" style="text-align:center;color:#D95A00;font-style:italic;font-size:3.5pt"><?= htmlspecialchars(mb_substr($ft,0,14)) ?></td>
            <?php else: ?>
            <td></td><td></td><td></td><td></td>
            <?php endif; ?>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>

      <div class="ph-divider"></div>

      <!-- Zusatzkarte Mo–Fr -->
      <table class="ph-order-table">
        <thead>
          <tr><th colspan="5" style="background:#D95A00 !important;border-color:#D95A00">Zusatzkarte</th></tr>
          <tr><th>Tag</th><th>De</th><th>Ro</th><th>Ab</th><th>Sa</th></tr>
        </thead>
        <tbody>
          <?php for ($i=0; $i<5; $i++):
            $ft = $feiertage[$days[$i]->format('Y-m-d')] ?? null;
          ?>
          <tr>
            <td class="dc <?= $ft?'ft':'' ?>"><?= $dayShort[$i] ?></td>
            <?php if ($ft): ?>
            <td class="ft" colspan="4" style="text-align:center;color:#D95A00;font-size:3.5pt">Feiertag</td>
            <?php else: ?>
            <td></td><td></td><td></td><td></td>
            <?php endif; ?>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>

      <!-- Kundendaten -->
      <div class="ph-cust">
        <div class="ph-cust-row">
          <span class="ph-cust-label">Name:</span>
          <div class="ph-cust-line"></div>
        </div>
        <div class="ph-cust-row">
          <span class="ph-cust-label">Kundennummer:</span>
          <div class="ph-cust-line"></div>
        </div>
        <div class="ph-cust-row">
          <span class="ph-cust-label">Telefon:</span>
          <div class="ph-cust-line"></div>
        </div>
      </div>
      <span class="ph-notes-label">Besondere Wünsche:</span>
      <div class="ph-notes-area"></div>
      <div class="ph-addr">
        <strong>BMV-Menüdienst</strong><br>
        Am Gutshof 6<br>
        14542 Werder (Havel)<br>
        <span class="ph-addr-tel">Tel.: 03327 – 57 45 066</span><br>
        info@bmv-kantinen.de
      </div>
    </div><!-- .ph-order -->

  </div><!-- .ph-body -->

</div><!-- .print-scale-wrap -->
</div><!-- .print-layout -->

<!-- ═══════════════════════════════════════════════
     JAVASCRIPT
═══════════════════════════════════════════════════ -->
<script>
// ── Pexels Bildersuche ──────────────────────────────
// Cache im Memory damit gleiche Gerichte nicht doppelt geladen werden
const pexelsCache = {};

async function fetchPexelsImg(query, width, height) {
  const key = query + '_' + width + 'x' + height;
  if (pexelsCache[key]) return pexelsCache[key];

  try {
    const res = await fetch('/api/pexels_image.php?q=' + encodeURIComponent(query));
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const data = await res.json();
    const url = data.url || null;
    pexelsCache[key] = url;
    return url;
  } catch (e) {
    pexelsCache[key] = null;
    return null;
  }
}

// Lazy-Load aller Karten-Bilder beim Start
async function loadAllCardImages() {
  const imgs = document.querySelectorAll('img.pexels-img[data-query]');
  // Parallel laden, max 6 gleichzeitig (Rate-Limit schonen)
  const chunks = [];
  for (let i = 0; i < imgs.length; i += 6) chunks.push([...imgs].slice(i, i + 6));

  for (const chunk of chunks) {
    await Promise.all(chunk.map(async img => {
      const q = img.dataset.query;
      if (!q) return;
      const url = await fetchPexelsImg(q, 80, 80);
      if (url) {
        img.src = url;
        img.style.opacity = '1';
      } else {
        img.style.display = 'none';
      }
    }));
  }
}

// Popup öffnen
function openPopup(data) {
  document.getElementById('popup-badge').textContent = data.badge;
  document.getElementById('popup-title').textContent = data.title || '—';
  document.getElementById('popup-price').textContent = data.price;
  const algEl = document.getElementById('popup-alg');
  if (data.alg) {
    algEl.style.display = 'block';
    algEl.textContent = 'Allergene: ' + data.alg;
  } else {
    algEl.style.display = 'none';
  }

  // Popup-Bild: Pexels 560x220
  const img = document.getElementById('popup-img');
  img.src = '';
  img.style.display = 'block';
  img.style.opacity = '0';

  const query = data.img || data.title || 'food dish';
  fetchPexelsImg(query, 560, 220).then(url => {
    if (url) {
      img.src = url;
      img.style.opacity = '1';
      img.style.transition = 'opacity .3s';
    } else {
      img.style.display = 'none';
    }
  });

  img.onerror = () => { img.style.display = 'none'; };
  document.getElementById('popup').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closePopup() {
  document.getElementById('popup').classList.remove('open');
  document.body.style.overflow = '';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closePopup(); });

// Bilder nach DOM-Ready laden
document.addEventListener('DOMContentLoaded', loadAllCardImages);

// ── Auto-Scaling für Druck ──────────────────────────────────────
function calcPrintScale() {
  const layout = document.getElementById('print-layout');
  const wrap   = document.getElementById('print-scale-wrap');
  if (!layout || !wrap) return;

  // A4 Querformat Nutzfläche in px bei 96dpi
  // 289mm × 202mm → px: 289/25.4*96 ≈ 1091 × 202/25.4*96 ≈ 763
  const pageW = 289 / 25.4 * 96;
  const pageH = 202 / 25.4 * 96;

  // Natürliche Größe des Inhalts messen
  wrap.style.transform = 'none';
  const contentW = wrap.scrollWidth;
  const contentH = wrap.scrollHeight;

  const scaleX = pageW / contentW;
  const scaleY = pageH / contentH;
  const scale  = Math.min(scaleX, scaleY, 1); // nie größer als 1

  wrap.style.transform       = `scale(${scale})`;
  wrap.style.transformOrigin = 'top left';

  // Layout-Höhe anpassen
  layout.style.height = (contentH * scale) + 'px';
}

// Bei Druck-Event skalieren
window.addEventListener('beforeprint', () => {
  document.getElementById('print-layout').style.display = 'block';
  calcPrintScale();
});
window.addEventListener('afterprint', () => {
  const layout = document.getElementById('print-layout');
  layout.style.display = '';
  const wrap = document.getElementById('print-scale-wrap');
  if (wrap) wrap.style.transform = 'none';
});

// Druckbutton-Handler
function doPrint() {
  document.getElementById('print-layout').style.display = 'block';
  calcPrintScale();
  setTimeout(() => {
    window.print();
  }, 150);
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
