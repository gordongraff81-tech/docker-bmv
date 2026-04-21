<?php
/**
 * speiseplan_index.php – Druckversion Wochenspeiseplan
 * Gemeinsame Funktionen: includes/helpers.php
 */
require_once __DIR__ . '/includes/helpers.php';

$currentYear = (int)date('Y');
$currentKW   = (int)date('W');
$year = isset($_GET['year']) ? (int)$_GET['year'] : $currentYear;
$kw   = isset($_GET['kw'])   ? (int)$_GET['kw']   : $currentKW;

$days     = kwDates($year, $kw);
$dayNames = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];
$dayShort = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

// Navigation (ISO-korrekt, inkl. KW-53-Jahre)
$nav     = kwNavBounds($year, $kw, $currentYear, $currentKW, maxWeeksAhead: 4);
$isAtMin = $nav['isAtMin'];
$isAtMax = $nav['isAtMax'];
$prevYear = $nav['prevYear']; $prevKW = $nav['prevKW'];
$nextYear = $nav['nextYear']; $nextKW = $nav['nextKW'];

// API-Daten mit Caching laden (15 min TTL, Fallback auf Stale-Cache)
$cacheDir  = sys_get_temp_dir() . '/bmv_cache';
if (!is_dir($cacheDir)) mkdir($cacheDir, 0750, true);
$cacheFile = "$cacheDir/week_{$year}_kw{$kw}.json";
$apiUrl    = "http://localhost/api/get_week.php?year={$year}&kw={$kw}";
$plan      = fetchApiWithCache($apiUrl, $cacheFile, ttl: 900);

// Tage indizieren
$dayMap = [];
if ($plan && !empty($plan['days'])) {
    foreach ($plan['days'] as $d) {
        $dow          = (int)(new DateTimeImmutable($d['date']))->format('N') - 1;
        $dayMap[$dow] = $d;
    }
}
$prices  = $plan['prices']       ?? [1 => 6.20, 2 => 6.40, 3 => 7.20, 4 => 6.20];
$aprices = $plan['addon_prices'] ?? ['D' => 1.80, 'R' => 1.80, 'A' => 5.50, 'S' => 5.50];
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wochenspeiseplan KW <?= $kw ?>/<?= $year ?> – BMV Menüdienst</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@400;600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════════
   VARIABLEN & RESET
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
  --serif:   'Source Serif 4', Georgia, serif;
  --sans:    'DM Sans', system-ui, sans-serif;
  --r:       12px;
  --sh:      0 2px 8px rgba(11,42,91,.10);
  --sh2:     0 8px 32px rgba(11,42,91,.14);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { font-size: 18px; scroll-behavior: smooth; }
body {
  font-family: var(--sans);
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
}

/* ═══════════════════════════════════════════════════
   PRINT BUTTONS (nur Screen)
═══════════════════════════════════════════════════ */
.screen-only { }
@media print { .screen-only { display: none !important; } }

/* ═══════════════════════════════════════════════════
   HEADER
═══════════════════════════════════════════════════ */
.site-header {
  background: var(--navy);
  padding: 20px 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky; top: 0; z-index: 100;
  box-shadow: 0 2px 12px rgba(11,42,91,.3);
}
.site-header .logo {
  display: flex; align-items: center; gap: 14px;
}
.site-header .logo img {
  height: 44px; width: auto; filter: brightness(10);
}
.site-header .logo-text {
  font-family: var(--serif);
  font-size: 1.3rem; font-weight: 700;
  color: #fff; line-height: 1.2;
}
.site-header .logo-sub {
  font-size: .75rem; color: rgba(255,255,255,.7);
  font-weight: 400; font-family: var(--sans);
}
.site-header .contact {
  text-align: right; color: rgba(255,255,255,.85);
  font-size: .85rem; line-height: 1.7;
}
.site-header .contact a {
  color: #fff; text-decoration: none; font-weight: 600;
}
.print-btn {
  background: var(--orange);
  color: #fff; border: none;
  padding: 12px 28px; border-radius: 8px;
  font-family: var(--sans); font-size: 1rem; font-weight: 600;
  cursor: pointer; letter-spacing: .02em;
  transition: background .2s, transform .1s;
  white-space: nowrap;
}
.print-btn:hover { background: var(--orange2); transform: translateY(-1px); }
.print-btn:active { transform: translateY(0); }

/* ═══════════════════════════════════════════════════
   WOCHENNAVIGATION
═══════════════════════════════════════════════════ */
.week-nav {
  background: #fff;
  border-bottom: 2px solid var(--border);
  padding: 20px 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 24px;
}
.week-nav-btn {
  display: flex; align-items: center; gap: 8px;
  background: var(--navy); color: #fff;
  border: none; border-radius: 8px;
  padding: 12px 24px; font-size: 1.05rem; font-weight: 600;
  font-family: var(--sans); cursor: pointer;
  transition: background .2s, opacity .2s;
  text-decoration: none;
}
.week-nav-btn:hover { background: var(--navy2); }
.week-nav-btn.disabled {
  opacity: .35; pointer-events: none; cursor: default;
}
.week-info {
  text-align: center; min-width: 280px;
}
.week-info .kw-label {
  font-family: var(--serif);
  font-size: 1.6rem; font-weight: 700;
  color: var(--navy); line-height: 1;
}
.week-info .date-range {
  font-size: .9rem; color: var(--muted); margin-top: 4px;
}

/* ═══════════════════════════════════════════════════
   HAUPTINHALT
═══════════════════════════════════════════════════ */
.main {
  max-width: 1400px;
  margin: 0 auto;
  padding: 32px 40px 60px;
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
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 24px;
  margin-bottom: 40px;
}
.day-card {
  background: var(--card);
  border-radius: var(--r);
  box-shadow: var(--sh);
  overflow: hidden;
  border: 1.5px solid var(--border);
  transition: box-shadow .2s;
}
.day-card:hover { box-shadow: var(--sh2); }
.day-card.empty { opacity: .6; }

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
  width: 64px; height: 64px;
  border-radius: 8px;
  flex-shrink: 0;
  background: linear-gradient(135deg, #e8edf5, #d0d8ea);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.5rem;
}

.menu-content { flex: 1; min-width: 0; }
.menu-badge {
  display: inline-flex; align-items: center; justify-content: center;
  width: 28px; height: 28px; border-radius: 6px;
  background: var(--navy); color: #fff;
  font-size: .75rem; font-weight: 700;
  margin-bottom: 5px; flex-shrink: 0;
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
.addon-section-header {
  background: linear-gradient(135deg, var(--orange), var(--orange2));
  padding: 16px 24px;
  display: flex; align-items: center; gap: 12px;
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
  display: none; position: fixed; inset: 0;
  background: rgba(11,42,91,.5); backdrop-filter: blur(4px);
  z-index: 1000; align-items: center; justify-content: center;
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

/* ═══════════════════════════════════════════════════
   DRUCKLAYOUT – DIN A4 Querformat
   Nutzfläche: 285mm x 198mm (Rand 6mm rundum)
   Hauptteil: 232mm | Bestellung: 47mm | Gap: 3mm
   Vertikale Aufteilung: Header 13mm + Titel 7mm +
   Haupttabelle ~72mm + Addon 50mm + Footer 11mm = ~153mm
═══════════════════════════════════════════════════ */
@media print {
  @page {
    size: A4 landscape;
    margin: 6mm;
  }

  *, *::before, *::after {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    color-adjust: exact !important;
    box-sizing: border-box;
  }

  html {
    font-size: 7pt;
    width: 297mm;
  }

  body {
    width: 285mm;
    font-family: Arial, Helvetica, sans-serif;
    color: #000;
    background: #fff !important;
    margin: 0; padding: 0;
    overflow: hidden;
  }

  /* Alles ausblenden */
  .screen-only,
  .site-header,
  .week-nav,
  .main,
  .popup-overlay { display: none !important; }

  /* Drucklayout einblenden */
  .print-layout {
    display: block !important;
    width: 285mm;
  }

  /* ── Header: 13mm hoch ── */
  .print-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 13mm;
    padding: 2mm 4mm;
    margin-bottom: 3mm;
    background: #0B2A5B !important;
    border-radius: 2mm;
  }
  .print-header-left {
    display: flex; align-items: center; gap: 3mm;
  }
  .print-logo {
    height: 9mm; width: auto;
    filter: brightness(0) invert(1);
  }
  .print-company-name {
    font-size: 12pt; font-weight: 700; color: #fff;
    line-height: 1.1;
  }
  .print-company-sub {
    font-size: 5.5pt; color: rgba(255,255,255,.8);
  }
  .print-header-right {
    color: #fff; font-size: 6pt;
    text-align: right; line-height: 1.6;
  }

  /* ── KW-Titel: 7mm ── */
  .print-week-title {
    text-align: center;
    font-size: 10pt; font-weight: 700;
    color: #0B2A5B !important;
    margin-bottom: 3mm;
    line-height: 1.1;
  }

  /* ── Flex-Container: Hauptteil + Bestellung ── */
  .print-container {
    display: flex;
    gap: 3mm;
    align-items: flex-start;
  }
  .print-main { flex: 1; min-width: 0; }

  /* ── Tabellen allgemein ── */
  .print-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 6.5pt;
    table-layout: fixed;
  }
  .print-table th {
    background: #0B2A5B !important;
    color: #fff !important;
    padding: 1.2mm 1mm;
    border: .5pt solid #0B2A5B;
    font-weight: 700;
    text-align: center;
    line-height: 1.2;
    font-size: 6.5pt;
  }
  .price-sub {
    font-size: 5pt; font-weight: 400;
    display: block; opacity: .9;
  }
  .print-table td {
    border: .5pt solid #ccc;
    padding: 1mm 1.2mm;
    vertical-align: top;
    line-height: 1.25;
    font-size: 6.5pt;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }
  .print-table td.day-col {
    background: #edf1f9 !important;
    font-weight: 700;
    color: #0B2A5B !important;
    text-align: center;
    width: 11mm;
    font-size: 6.5pt;
    vertical-align: middle;
  }
  .print-table td.na {
    background: #f5f5f5 !important;
    color: #bbb !important;
    text-align: center;
    vertical-align: middle;
  }

  /* Spaltenbreiten Haupttabelle: Tag 11mm + 4x ~55mm */
  .print-table.main-table th:nth-child(1),
  .print-table.main-table td:nth-child(1) { width: 11mm; }

  /* Spaltenbreiten Addon-Tabelle: Tag 11mm + 4x ~55mm */
  .print-table.addon-table th:nth-child(1),
  .print-table.addon-table td:nth-child(1) { width: 11mm; }
  .print-table.addon-table th {
    background: #D95A00 !important;
    border-color: #D95A00;
  }

  .print-dish { font-weight: 700; color: #000; }
  .print-alg  { font-size: 5pt; color: #777; display: block; margin-top: .3mm; }
  .print-price-cell {
    font-size: 5.5pt; color: #D95A00 !important;
    font-weight: 700; display: block; margin-top: .3mm;
  }
  .print-veg { color: #16a34a !important; font-size: 5pt; font-weight: 700; }

  /* ── Zusatztabelle Titel ── */
  .print-addon-title {
    font-size: 6.5pt; font-weight: 700;
    color: #D95A00 !important;
    text-transform: uppercase;
    letter-spacing: .2mm;
    margin: 2mm 0 1mm;
  }

  /* ── Footer ── */
  .print-footer {
    margin-top: 2mm;
    font-size: 4.5pt;
    line-height: 1.4;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5mm;
  }
  .print-footer-box {
    padding: 1.2mm 1.5mm;
    background: #f8f9fa !important;
    border-left: 1.2mm solid #0B2A5B !important;
    border-radius: 1mm;
  }
  .print-footer-box:nth-child(2) {
    border-left-color: #D95A00 !important;
  }
  .print-footer-box strong {
    display: block; margin-bottom: .4mm; font-size: 5pt;
  }
  .print-footer-legal {
    grid-column: 1/-1;
    text-align: center;
    padding: 1mm;
    background: #e9ecef !important;
    border-radius: 1mm;
    font-size: 4pt;
    line-height: 1.5;
  }

  /* ── Bestellabschnitt: 47mm breit ── */
  .print-order {
    width: 47mm;
    flex-shrink: 0;
    border-left: 1.5mm dashed #D95A00 !important;
    padding-left: 3mm;
    position: relative;
  }
  .print-scissors {
    position: absolute;
    left: -3.5mm; top: 50%;
    transform: translateY(-50%);
    font-size: 9pt;
    color: #D95A00 !important;
  }
  .print-order-title {
    font-weight: 700; text-align: center;
    font-size: 8pt; color: #0B2A5B !important;
    text-transform: uppercase; letter-spacing: .3mm;
    margin-bottom: 1mm;
  }
  .print-order-sub {
    text-align: center; font-size: 5pt;
    color: #D95A00 !important; font-style: italic;
    margin-bottom: 2mm;
  }
  .print-order-table {
    width: 100%; border-collapse: collapse; margin-bottom: 1.5mm;
    table-layout: fixed;
  }
  .print-order-table th {
    background: #D95A00 !important; color: #fff !important;
    font-size: 5.5pt; padding: .8mm .3mm;
    border: .5pt solid #D95A00;
    text-align: center;
  }
  .print-order-table td {
    height: 5.5mm; text-align: center;
    border: .5pt solid #ccc;
    background: #fff !important;
    font-size: 5.5pt;
  }
  .print-order-table td.day-col {
    background: #edf1f9 !important;
    font-weight: 700; color: #0B2A5B !important;
    width: 7mm; vertical-align: middle;
  }
  .print-divider {
    border-top: .8pt solid #0B2A5B;
    margin: 2mm 0 1.5mm;
    position: relative;
  }
  .print-divider::after {
    content: '✂';
    position: absolute; left: 50%; top: -2.5mm;
    transform: translateX(-50%);
    background: #fff; padding: 0 1mm;
    color: #D95A00 !important; font-size: 8pt;
  }
  .print-cust { margin-top: 1.5mm; font-size: 5.5pt; }
  .print-cust-field { margin-bottom: 1.5mm; }
  .print-cust-label {
    font-weight: 700; display: block;
    color: #0B2A5B !important; font-size: 5.5pt;
    margin-bottom: .4mm;
  }
  .print-cust-line { border-bottom: .8pt solid #333; height: 4mm; }
  .print-notes { margin-top: 1.5mm; }
  .print-notes-label {
    font-weight: 700; color: #0B2A5B !important;
    font-size: 5.5pt; display: block; margin-bottom: .8mm;
  }
  .print-notes-area {
    border: .5pt solid #ccc; height: 7mm;
    border-radius: 1mm; background: #fafafa !important;
  }
  .print-addr {
    margin-top: 2mm; font-size: 5pt; line-height: 1.5;
    padding: 1.5mm;
    background: #f0f4fa !important;
    border-radius: 1mm; border: .5pt solid #dde4ef;
  }
  .print-addr strong {
    color: #0B2A5B !important; display: block;
    font-size: 5.5pt; margin-bottom: .5mm;
  }
  .print-addr .org { color: #D95A00 !important; font-weight: 700; }

  /* Alle Bilder ausblenden */
  img.menu-img, .menu-img-placeholder,
  .popup-overlay img { display: none !important; }
}

/* Drucklayout nur bei Druck sichtbar */
.print-layout { display: none; }
</style>
</head>
<body>

<!-- ═══════════════════════════════════════════════
     HEADER (Screen)
═══════════════════════════════════════════════════ -->
<header class="site-header screen-only">
  <div class="logo">
    <img src="/assets/images/BMV_Logo_n.png" alt="BMV"
         onerror="this.style.display='none'">
    <div>
      <div class="logo-text">BMV Menüdienst</div>
      <div class="logo-sub">Frisch · Regional · Gesund</div>
    </div>
  </div>
  <div class="contact">
    <a href="tel:+493327574506603327 – 57 45 066</a><br>
    info@bmv-kantinen.de
  </div>
  <button class="print-btn" onclick="window.print()">🖨️ Speiseplan drucken</button>
</header>

<!-- ═══════════════════════════════════════════════
     WOCHENNAVIGATION (Screen)
═══════════════════════════════════════════════════ -->
<nav class="week-nav screen-only">
  <a href="?year=<?= $prevYear ?>&kw=<?= $prevKW ?>"
     class="week-nav-btn <?= $isAtMin ? 'disabled' : '' ?>">
    ← Vorherige Woche
  </a>
  <div class="week-info">
    <div class="kw-label">Kalenderwoche <?= $kw ?> / <?= $year ?></div>
    <div class="date-range">
      <?= $days[0]->format('d.m.Y') ?> – <?= $days[6]->format('d.m.Y') ?>
    </div>
  </div>
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
  $day = $dayMap[$i] ?? null;
  $hasData = $day && (!empty($day['menus']) || !empty($day['addons']));
  $dateStr = $days[$i]->format('d.m.Y');
?>
<div class="day-card <?= !$hasData ? 'empty' : '' ?>">
  <div class="day-header">
    <span class="day-name"><?= $dayNames[$i] ?></span>
    <span class="day-date"><?= $dateStr ?></span>
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
  ]).")"" : '' ?>>
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
  ]).")"" : '' ?>>
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
<div class="print-layout">

  <div class="print-header">
    <div class="print-header-left">
      <img class="print-logo" src="/assets/images/BMV_Logo_n.png" alt="BMV">
      <div class="print-company">
        <div class="print-company-name">BMV Menüdienst</div>
        <div class="print-company-sub">Frisch · Regional · Gesund</div>
      </div>
    </div>
    <div class="print-header-right">
      Tel.: 03327 – 57 45 066<br>
      info@bmv-kantinen.de<br>
      www.bmv-kantinen.de
    </div>
  </div>

  <div class="print-week-title">
    Wochenspeiseplan – KW <?= $kw ?>/<?= $year ?>
    (<?= $days[0]->format('d.m.') ?> – <?= $days[6]->format('d.m.Y') ?>)
  </div>

  <div class="print-container">
    <div class="print-main">

      <!-- Haupttabelle -->
      <table class="print-table main-table">
        <thead>
          <tr>
            <th>Tag</th>
            <th>Vollkost M1<br><span class="price-sub"><?= number_format($prices[1]??6.20,2,',','.').' €' ?></span></th>
            <th style="width:11%">Leichte Kost M2<br><span class="price-sub"><?= number_format($prices[2]??6.40,2,',','.').' €' ?></span></th>
            <th style="width:11%">Premium M3<br><span class="price-sub"><?= number_format($prices[3]??7.20,2,',','.').' €' ?></span></th>
            <th style="width:11%">Tagesmenü M4</th>
          </tr>
        </thead>
        <tbody>
          <?php for ($i = 0; $i < 7; $i++):
            $day = $dayMap[$i] ?? null; ?>
          <tr>
            <td class="day-col"><?= $dayNames[$i] ?></td>
            <?php for ($n = 1; $n <= 4; $n++):
              $m = getMenu($day, $n);
              $title = $m['title'] ?? null;
              $alg   = $m['allergens'] ?? '';
              $p     = isset($m['price']) ? number_format($m['price'],2,',','.').' €' : '';
              $veg   = !empty($m['vegetarian']); ?>
            <?php if ($title): ?>
            <td>
              <span class="print-dish"><?= htmlspecialchars($title) ?></span>
              <?php if ($veg): ?><span class="print-veg"> veg.</span><?php endif; ?>
              <?php if ($alg): ?><br><span class="print-alg">(<?= htmlspecialchars($alg) ?>)</span><?php endif; ?>
              <?php if ($p): ?><br><span class="print-price-cell"><?= $p ?></span><?php endif; ?>
            </td>
            <?php else: ?>
            <td class="na">—</td>
            <?php endif; ?>
            <?php endfor; ?>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>

      <!-- Zusatztabelle -->
      <div class="print-addon-title">Zusatzkarte</div>
      <table class="print-table addon-table">
        <thead>
          <tr>
            <th>Tag</th>
            <th>Dessert<br><span class="price-sub"><?= number_format($aprices['D']??1.80,2,',','.').' €' ?></span></th>
            <th>Rohkost<br><span class="price-sub"><?= number_format($aprices['R']??1.80,2,',','.').' €' ?></span></th>
            <th>Abendessen<br><span class="price-sub"><?= number_format($aprices['A']??5.50,2,',','.').' €' ?></span></th>
            <th>Salatteller<br><span class="price-sub"><?= number_format($aprices['S']??5.50,2,',','.').' €' ?></span></th>
          </tr>
        </thead>
        <tbody>
          <?php for ($i = 0; $i < 5; $i++):
            $day = $dayMap[$i] ?? null; ?>
          <tr>
            <td class="day-col"><?= $dayNames[$i] ?></td>
            <?php foreach (['D','R','A','S'] as $code):
              $a = getAddon($day, $code);
              $n = $a['name'] ?? null;
              $p = isset($a['price']) ? number_format($a['price'],2,',','.').' €' : ''; ?>
            <?php if ($n): ?>
            <td>
              <span class="print-dish"><?= htmlspecialchars($n) ?></span>
              <?php if ($p): ?><br><span class="print-price-cell"><?= $p ?></span><?php endif; ?>
            </td>
            <?php else: ?><td class="na">—</td><?php endif; ?>
            <?php endforeach; ?>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>

      <!-- Footer -->
      <div class="print-footer">
        <div class="print-footer-box">
          <strong>Zusatzstoffe:</strong>
          1) Geschmacksverstärker · 2) Antioxidationsmittel · 3) Süßungsmittel · 4) Konservierungsmittel · 5) Farbstoff · 9) Milch/Sahne · 10) Formfleisch · 11) Nitritpökelsalz · 12) Phosphat
        </div>
        <div class="print-footer-box">
          <strong>Allergene:</strong>
          13) Getreide/Weizen · 16) Fisch · 18) Eier · 19) Senf · 21) Soja · 22) Sellerie · 23) Schalenfrüchte · 24) Erdnüsse · 25) Schwefeldioxid
        </div>
        <div class="print-footer-legal">
          <strong>Bestellmodalitäten:</strong>
          Telefonische Bestellung Mo.–Fr. 9–15 Uhr &nbsp;|&nbsp; Menüs zum Verzehr am Liefertag &nbsp;|&nbsp; Keine Bestellung an Sonn-/Feiertagen &nbsp;|&nbsp; Reklamationen nur am Liefertag &nbsp;|&nbsp; AGB: www.bmv-kantinen.de
        </div>
      </div>

    </div><!-- .print-main -->

    <!-- Bestellabschnitt -->
    <div class="print-order">
      <span class="print-scissors">✂</span>
      <div class="print-order-title">Bestellung</div>
      <div class="print-order-sub">Abtrennen und Fahrer mitgeben</div>

      <table class="print-order-table">
        <thead>
          <tr><th></th><th>M1</th><th>M2</th><th>M3</th><th>M4</th></tr>
        </thead>
        <tbody>
          <?php for ($i=0;$i<7;$i++): ?>
          <tr>
            <td class="day-col"><?= $dayShort[$i] ?></td>
            <td></td><td></td><td></td><td></td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>

      <div class="print-divider"></div>

      <table class="print-order-table">
        <thead>
          <tr><th colspan="5">Zusatzkarte</th></tr>
          <tr><th></th><th>De</th><th>Ro</th><th>Ab</th><th>Sa</th></tr>
        </thead>
        <tbody>
          <?php for ($i=0;$i<5;$i++): ?>
          <tr>
            <td class="day-col"><?= $dayShort[$i] ?></td>
            <td></td><td></td><td></td><td></td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>

      <div class="print-cust">
        <div class="print-cust-field">
          <span class="print-cust-label">Name:</span>
          <div class="print-cust-line"></div>
        </div>
        <div class="print-cust-field">
          <span class="print-cust-label">Kundennummer:</span>
          <div class="print-cust-line"></div>
        </div>
        <div class="print-cust-field">
          <span class="print-cust-label">Telefon:</span>
          <div class="print-cust-line"></div>
        </div>
      </div>
      <div class="print-notes">
        <span class="print-notes-label">Besondere Wünsche:</span>
        <div class="print-notes-area"></div>
      </div>
      <div class="print-addr">
        <strong>BMV Menüdienst</strong>
        Am Gutshof 6 · 14542 Werder (Havel)<br>
        <span class="org">Tel.: 03327 – 57 45 066</span><br>
        info@bmv-kantinen.de
      </div>
    </div><!-- .print-order -->

  </div><!-- .print-container -->
</div><!-- .print-layout -->

<!-- ═══════════════════════════════════════════════
     JAVASCRIPT
═══════════════════════════════════════════════════ -->
<script>
(function () {
  'use strict';

  /* ── Pexels Bildersuche ─────────────────────────────
     Cache im IIFE-Scope – kein globales const-Leak     */
  const cache = Object.create(null);

  async function fetchPexelsImg(query, width, height) {
    const key = query + '_' + width + 'x' + height;
    if (key in cache) return cache[key];
    try {
      const res = await fetch('/api/pexels_image.php?q=' + encodeURIComponent(query));
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const data = await res.json();
      return (cache[key] = data.url || null);
    } catch (e) {
      return (cache[key] = null);
    }
  }

  /* ── Lazy-Load aller Kartenbilder ── */
  async function loadAllCardImages() {
    const imgs = [...document.querySelectorAll('img.pexels-img[data-query]')];
    for (let i = 0; i < imgs.length; i += 6) {
      await Promise.all(imgs.slice(i, i + 6).map(async img => {
        if (!img.dataset.query) return;
        const url = await fetchPexelsImg(img.dataset.query, 80, 80);
        if (url) {
          img.onload = () => img.classList.add('loaded');
          img.src = url;
        } else {
          img.style.display = 'none';
        }
      }));
    }
  }

  /* ── Popup öffnen ── */
  function openPopup(data) {
    document.getElementById('popup-badge').textContent = data.badge;
    document.getElementById('popup-title').textContent = data.title || '—';
    document.getElementById('popup-price').textContent = data.price;
    const algEl = document.getElementById('popup-alg');
    algEl.style.display = data.alg ? 'block' : 'none';
    if (data.alg) algEl.textContent = 'Allergene: ' + data.alg;

    const img = document.getElementById('popup-img');
    img.src = '';
    img.style.opacity = '0';
    img.style.display = 'block';

    fetchPexelsImg(data.img || data.title || 'food dish', 560, 220).then(url => {
      if (url) {
        img.onload = () => { img.style.opacity = '1'; };
        img.src = url;
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

  // Globals für onclick-Handler im HTML
  window.openPopup  = openPopup;
  window.closePopup = closePopup;

  /* ── Init ── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadAllCardImages);
  } else {
    loadAllCardImages();
  }
})();
</script>
</body>
</html>
