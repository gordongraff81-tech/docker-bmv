<?php
/**
 * speiseplan-index.php – Hauptansicht Wochenspeiseplan
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

// Feiertage (jetzt aus helpers.php statt lokal)
$feiertage = getFeiertage($year);

// Speiseplan: JSON sicher laden mit Größenlimit + JSON_THROW_ON_ERROR
$kwStr   = str_pad($kw, 2, '0', STR_PAD_LEFT);
$docRoot = $_SERVER['DOCUMENT_ROOT'];
$newFile = "$docRoot/data/speiseplaene/essen_auf_raedern-{$year}-KW{$kwStr}.json";
$oldFile = "$docRoot/data/speiseplaene/{$year}-KW{$kwStr}.json";
$plan      = null;
$newFormat = false;

$raw = loadJsonFile($newFile) ?? loadJsonFile($oldFile);

if ($raw !== null) {
    if (isset($raw['data'])) {
        $plan      = $raw;
        $newFormat = true;
    } elseif (isset($raw['days'])) {
        $plan = $raw;
    }
}

$dayMap  = [];
$prices  = [1 => 7.50, 2 => 7.20, 3 => 9.80, 4 => 6.50];
$aprices = ['D' => 1.80, 'R' => 1.80, 'A' => 5.50, 'S' => 5.50];
$catToMenu  = ['vollkost' => 1, 'leichte_kost' => 2, 'premium' => 3, 'tagesmenu' => 4];
$catToAddon = ['dessert' => 'D', 'rohkost' => 'R', 'abendessen' => 'A', 'salat' => 'S'];

if ($plan) {
    if ($newFormat) {
        foreach ($plan['data'] as $dayIdx => $cats) {
            $dayIdx = (int)$dayIdx;
            $menus  = [];
            $addons = [];
            foreach ($cats as $catKey => $entry) {
                if (!($entry['name'] ?? '')) continue;
                if (isset($catToMenu[$catKey])) {
                    $mn = $catToMenu[$catKey];
                    $menus[] = [
                        'menu_number' => $mn,
                        'title'       => $entry['name'],
                        'allergens'   => $entry['allergens'] ?? '',
                        'price'       => (float)($entry['price'] ?? $prices[$mn]),
                        'available'   => true,
                    ];
                } elseif (isset($catToAddon[$catKey])) {
                    $ac = $catToAddon[$catKey];
                    $addons[] = [
                        'code'  => $ac,
                        'name'  => $entry['name'],
                        'price' => (float)($entry['price'] ?? $aprices[$ac]),
                    ];
                }
            }
            $dayMap[$dayIdx] = ['menus' => $menus, 'addons' => $addons];
        }
    } else {
        if (!empty($plan['days'])) {
            foreach ($plan['days'] as $d) {
                $dow          = (int)(new DateTimeImmutable($d['date']))->format('N') - 1;
                $dayMap[$dow] = $d;
            }
        }
        $prices  = $plan['prices']       ?? $prices;
        $aprices = $plan['addon_prices'] ?? $aprices;
    }
}

$menuDefs = [
    1 => ['label' => 'M1', 'name' => 'Vollkost',     'color' => '#0B2A5B', 'price' => $prices[1] ?? 7.50],
    2 => ['label' => 'M2', 'name' => 'Leichte Kost', 'color' => '#1a3f7a', 'price' => $prices[2] ?? 7.20],
    3 => ['label' => 'M3', 'name' => 'Premium',       'color' => '#D95A00', 'price' => $prices[3] ?? 9.80],
    4 => ['label' => 'M4', 'name' => 'Tagesmenü',    'color' => '#5a6a82', 'price' => $prices[4] ?? 6.50],
];
$addonDefs = [
    'D' => ['label' => 'De', 'name' => 'Dessert',     'price' => $aprices['D'] ?? 1.80],
    'R' => ['label' => 'Ro', 'name' => 'Rohkost',     'price' => $aprices['R'] ?? 1.80],
    'A' => ['label' => 'Ab', 'name' => 'Abendessen',  'price' => $aprices['A'] ?? 5.50],
    'S' => ['label' => 'Sa', 'name' => 'Salatteller', 'price' => $aprices['S'] ?? 5.50],
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Speiseplan KW <?= $kw ?>/<?= $year ?> – BMV-Menüdienst</title>
<meta name="description" content="Wochenspeiseplan KW <?= $kw ?>/<?= $year ?> des BMV-Menüdienst. Täglich frische Menüs für Essen auf Rädern in Potsdam und Werder (Havel).">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
/* ── TOKENS ─────────────────────────────────────────── */
:root {
  --navy:      #0B2A5B;
  --navy-d:    #071d40;
  --navy-l:    #1a3f7a;
  --orange:    #D95A00;
  --orange-l:  #f06820;
  --surface:   #F4F7FC;
  --surface-2: #EDF1F9;
  --white:     #ffffff;
  --text:      #0d1117;
  --muted:     #5a6a82;
  --border:    #dde4ef;
  --green:     #16a34a;
  --green-bg:  #dcfce7;
  --serif:     'DM Serif Display', Georgia, serif;
  --sans:      'DM Sans', system-ui, sans-serif;
  --r-sm:      8px;
  --r:         14px;
  --r-lg:      20px;
  --sh-sm:     0 1px 4px rgba(11,42,91,.08);
  --sh:        0 4px 16px rgba(11,42,91,.10);
  --sh-lg:     0 12px 40px rgba(11,42,91,.16);
  --t:         .2s ease;
}

/* ── RESET ──────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; -webkit-font-smoothing: antialiased; }
body { font-family: var(--sans); background: var(--surface); color: var(--text); min-height: 100vh; }
img { display: block; max-width: 100%; height: auto; }
a { color: inherit; text-decoration: none; }

/* ── HEADER ─────────────────────────────────────────── */
.site-header {
  background: var(--navy-d);
  position: sticky; top: 0; z-index: 200;
  border-bottom: 1px solid rgba(255,255,255,.06);
  box-shadow: 0 2px 20px rgba(0,0,0,.3);
}
.header-inner {
  max-width: 1400px; margin: 0 auto;
  padding: 0 32px;
  height: 72px;
  display: flex; align-items: center; gap: 20px;
}
.logo-wrap { display: flex; align-items: center; gap: 14px; }
.logo-wrap img {
  height: 42px; width: auto;
  image-rendering: auto;
  backface-visibility: hidden;
}
.logo-name {
  font-family: var(--serif);
  font-size: 1.2rem; color: #fff; line-height: 1.1;
}
.logo-sub { font-size: .72rem; color: rgba(255,255,255,.55); margin-top: 1px; }
.header-contact {
  margin-left: auto;
  font-size: .82rem; color: rgba(255,255,255,.65);
  line-height: 1.7; text-align: right;
}
.header-contact a { color: rgba(255,255,255,.9); font-weight: 600; }
.header-contact a:hover { color: #fff; }
.print-btn {
  display: flex; align-items: center; gap: 8px;
  background: var(--orange);
  color: #fff; border: none;
  padding: 11px 22px; border-radius: var(--r-sm);
  font-family: var(--sans); font-size: .88rem; font-weight: 600;
  cursor: pointer; white-space: nowrap;
  box-shadow: 0 4px 16px rgba(217,90,0,.30);
  transition: background var(--t), transform var(--t), box-shadow var(--t);
}
.print-btn:hover { background: var(--orange-l); transform: translateY(-1px); box-shadow: 0 6px 22px rgba(217,90,0,.40); }
.print-btn svg { width: 16px; height: 16px; fill: currentColor; }

/* ── WEEK NAV ───────────────────────────────────────── */
.week-nav {
  background: var(--white);
  border-bottom: 1px solid var(--border);
  box-shadow: var(--sh-sm);
}
.week-nav-inner {
  max-width: 1400px; margin: 0 auto;
  padding: 18px 32px;
  display: flex; align-items: center; justify-content: center; gap: 32px;
}
.nav-btn {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--navy); color: #fff;
  padding: 11px 22px; border-radius: var(--r-sm);
  font-family: var(--sans); font-size: .88rem; font-weight: 600;
  border: none; cursor: pointer;
  transition: background var(--t), transform var(--t);
  text-decoration: none;
}
.nav-btn:hover { background: var(--navy-l); transform: translateY(-1px); }
.nav-btn.disabled { opacity: .3; pointer-events: none; }
.nav-btn svg { width: 16px; height: 16px; fill: currentColor; }
.week-info { text-align: center; }
.kw-title {
  font-family: var(--serif);
  font-size: 1.7rem; color: var(--navy); line-height: 1;
  font-style: italic;
}
.kw-range { font-size: .85rem; color: var(--muted); margin-top: 5px; }

/* ── MAIN ───────────────────────────────────────────── */
.main-wrap {
  max-width: 1400px; margin: 0 auto;
  padding: 36px 32px 72px;
}

/* ── NO DATA ────────────────────────────────────────── */
.no-data {
  text-align: center; padding: 100px 40px;
  background: var(--white); border-radius: var(--r-lg);
  border: 2px dashed var(--border); margin-top: 32px;
}
.no-data-icon { font-size: 3rem; margin-bottom: 20px; opacity: .4; }
.no-data h2 { font-family: var(--serif); font-size: 1.8rem; color: var(--muted); margin-bottom: 10px; }
.no-data p { color: var(--muted); font-size: 1.05rem; }

/* ── DAY GRID ───────────────────────────────────────── */
.days-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px;
}

/* ── DAY CARD ───────────────────────────────────────── */
.day-card {
  background: var(--white);
  border-radius: var(--r-lg);
  border: 1px solid var(--border);
  box-shadow: var(--sh-sm);
  overflow: hidden;
  transition: box-shadow var(--t), transform var(--t), border-color var(--t);
  display: flex; flex-direction: column;
}
.day-card:hover { box-shadow: var(--sh-lg); transform: translateY(-3px); border-color: #b8c8e8; }
.day-card.weekend { background: #fafbff; border-color: #c8d4ef; }
.day-card.holiday-day .day-header { background: linear-gradient(135deg, #7c3000, #D95A00); }

.day-header {
  background: linear-gradient(135deg, var(--navy-d) 0%, var(--navy) 100%);
  padding: 16px 20px;
  display: flex; flex-direction: column; gap: 4px;
}
.day-header-top { display: flex; align-items: baseline; gap: 10px; }
.day-name {
  font-family: var(--serif);
  font-size: 1.3rem; font-weight: 400; color: #fff; font-style: italic;
}
.day-date { font-size: .8rem; color: rgba(255,255,255,.6); }
.holiday-tag {
  display: inline-flex; align-items: center; gap: 5px;
  background: rgba(255,255,255,.15);
  color: #fff; font-size: .72rem; font-weight: 600;
  padding: 3px 10px; border-radius: 999px;
  width: fit-content; margin-top: 4px;
}

/* ── MENU ITEMS ─────────────────────────────────────── */
.menu-list { flex: 1; display: flex; flex-direction: column; }

.menu-item {
  display: flex; align-items: center; gap: 14px;
  padding: 13px 18px;
  border-bottom: 1px solid var(--border);
  cursor: pointer;
  transition: background var(--t);
  position: relative;
}
.menu-item:last-child { border-bottom: none; }
.menu-item:hover { background: #f0f5ff; }
.menu-item.is-addon { background: #fdfaff; }
.menu-item.is-addon:hover { background: #f5eeff; }
.menu-item.is-empty { cursor: default; opacity: .55; }
.menu-item.is-empty:hover { background: transparent; }

.dish-img-wrap {
  width: 60px; height: 60px; flex-shrink: 0;
  border-radius: 10px; overflow: hidden;
  background: var(--surface-2);
  position: relative;
}
.dish-img-wrap img {
  width: 100%; height: 100%;
  object-fit: cover;
  opacity: 0;
  transition: opacity .35s ease;
  image-rendering: auto;
  backface-visibility: hidden;
}
.dish-img-wrap img.loaded { opacity: 1; }
.dish-img-placeholder {
  width: 100%; height: 100%;
  display: flex; align-items: center; justify-content: center;
  color: var(--border); font-size: 1.4rem;
}

.menu-badge {
  width: 28px; height: 28px; flex-shrink: 0;
  border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  font-size: .7rem; font-weight: 700; color: #fff;
  margin-bottom: 4px;
}

.menu-info { flex: 1; min-width: 0; }
.menu-label-row { display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
.menu-type {
  font-size: .7rem; font-weight: 600;
  color: var(--muted); text-transform: uppercase; letter-spacing: .06em;
}
.veg-pill {
  font-size: .65rem; font-weight: 700;
  background: var(--green-bg); color: var(--green);
  padding: 1px 7px; border-radius: 999px;
}
.menu-title {
  font-size: .92rem; font-weight: 600;
  color: var(--text); line-height: 1.35;
  overflow: hidden;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
}
.menu-title.empty { color: var(--muted); font-style: italic; font-weight: 400; }
.menu-meta { display: flex; align-items: center; gap: 8px; margin-top: 4px; }
.menu-price { font-size: .82rem; font-weight: 700; color: var(--orange); }
.menu-alg { font-size: .72rem; color: var(--muted); }

/* ── ADDON SECTION ──────────────────────────────────── */
.addon-section {
  background: var(--white);
  border-radius: var(--r-lg);
  border: 1px solid var(--border);
  box-shadow: var(--sh-sm);
  overflow: hidden;
  margin-top: 28px;
}
.addon-header {
  background: linear-gradient(135deg, #7c3000, var(--orange));
  padding: 16px 24px;
  display: flex; align-items: center; gap: 10px;
}
.addon-header h2 {
  font-family: var(--serif);
  font-size: 1.15rem; font-style: italic; color: #fff; font-weight: 400;
}
.addon-header-sub { font-size: .8rem; color: rgba(255,255,255,.7); margin-left: auto; }

.addon-table-wrap { overflow-x: auto; }
.addon-table {
  width: 100%; border-collapse: collapse; min-width: 600px;
}
.addon-table th {
  background: var(--surface-2);
  padding: 10px 16px;
  font-size: .78rem; font-weight: 700;
  color: var(--navy); text-align: left;
  border-bottom: 2px solid var(--border);
}
.addon-table th:first-child { width: 140px; }
.addon-table td {
  padding: 10px 16px;
  border-bottom: 1px solid var(--border);
  font-size: .88rem; color: var(--text); vertical-align: middle;
}
.addon-table tr:last-child td { border-bottom: none; }
.addon-table tr:hover td { background: #f8faff; }
.addon-table .addon-row-label {
  font-weight: 700; color: var(--navy);
  display: flex; align-items: center; gap: 8px;
}
.addon-table .addon-code {
  width: 28px; height: 28px;
  background: var(--orange); color: #fff;
  border-radius: 6px; font-size: .7rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.addon-table .addon-price { color: var(--orange); font-weight: 600; font-size: .8rem; }
.addon-table .na-cell { color: var(--border); text-align: center; }
.addon-table .holiday-cell { text-align: center; font-size: .75rem; color: var(--orange); font-style: italic; }

/* ── MODAL ──────────────────────────────────────────── */
.modal-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(7,21,38,.65);
  backdrop-filter: blur(8px);
  z-index: 500;
  align-items: center; justify-content: center;
  padding: 20px;
}
.modal-overlay.open { display: flex; }
.modal {
  background: var(--white);
  border-radius: var(--r-lg);
  box-shadow: 0 24px 80px rgba(7,21,38,.35);
  width: min(560px, 100%);
  overflow: hidden;
  animation: modalIn .22s cubic-bezier(.16,1,.3,1);
}
@keyframes modalIn {
  from { transform: scale(.94) translateY(12px); opacity: 0; }
  to   { transform: scale(1)   translateY(0);    opacity: 1; }
}
.modal-img-wrap {
  width: 100%; height: 220px;
  background: var(--surface-2);
  overflow: hidden; position: relative;
}
.modal-img-wrap img {
  width: 100%; height: 100%; object-fit: cover;
  opacity: 0; transition: opacity .35s ease;
  image-rendering: auto; backface-visibility: hidden;
}
.modal-img-wrap img.loaded { opacity: 1; }
.modal-img-placeholder {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 3rem; opacity: .25;
}
.modal-body { padding: 28px 28px 24px; }
.modal-badge {
  display: inline-flex; align-items: center;
  background: var(--navy); color: #fff;
  font-size: .75rem; font-weight: 700;
  padding: 4px 12px; border-radius: 999px;
  margin-bottom: 14px;
}
.modal-title {
  font-family: var(--serif);
  font-size: 1.5rem; color: var(--text);
  line-height: 1.2; margin-bottom: 8px;
}
.modal-price { font-size: 1.1rem; color: var(--orange); font-weight: 700; margin-bottom: 16px; }
.modal-alg {
  background: var(--surface); padding: 10px 14px;
  border-radius: var(--r-sm); font-size: .85rem; color: var(--muted);
}
.modal-close {
  display: block; width: 100%; margin-top: 20px;
  background: var(--navy); color: #fff; border: none;
  padding: 14px; border-radius: var(--r-sm);
  font-family: var(--sans); font-size: .95rem; font-weight: 600;
  cursor: pointer; transition: background var(--t);
}
.modal-close:hover { background: var(--navy-l); }

/* ── PRINT LAYOUT ───────────────────────────────────── */
.print-layout { display: none; }

@media print {
  @page { size: A4 landscape; margin: 8mm; }
  *, *::before, *::after {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    box-sizing: border-box;
  }
  .site-header, .week-nav, .main-wrap, .modal-overlay { display: none !important; }
  html, body { margin: 0; padding: 0; background: #fff !important; font-family: Arial, sans-serif; color: #000; font-size: 10pt; }
  .print-layout { display: block !important; width: 277mm; max-width: 100%; }
  .print-scale-wrap { transform: none !important; width: 100%; }

  .ph-header {
    display: flex; align-items: center; justify-content: space-between;
    background: #0B2A5B !important; border-radius: 3mm;
    padding: 3mm 5mm; margin-bottom: 3mm; min-height: 14mm;
  }
  .ph-logo-wrap { display: flex; align-items: center; gap: 3mm; }
  .ph-logo { height: 10mm; width: auto; filter: brightness(0) invert(1); }
  .ph-company-name { font-size: 14pt; font-weight: 700; color: #fff; line-height: 1.1; }
  .ph-company-sub  { font-size: 7pt; color: rgba(255,255,255,.85); }
  .ph-contact { color: #fff; font-size: 7pt; text-align: right; line-height: 1.8; }
  .ph-title { text-align: center; font-size: 12pt; font-weight: 700; color: #0B2A5B !important; margin-bottom: 3mm; }
  .ph-body { display: grid; grid-template-columns: 1fr 50mm; gap: 4mm; align-items: start; }
  .ph-main { min-width: 0; }
  .ph-grid { display: grid; grid-template-columns: 12mm repeat(var(--day-count,7), 1fr); width: 100%; border: .5pt solid #0B2A5B; }
  .ph-cell { border: .4pt solid #bbb; padding: 1.2mm 1.5mm; overflow: hidden; font-size: 7pt; line-height: 1.3; word-break: break-word; page-break-inside: avoid; break-inside: avoid; min-height: 7mm; }
  .ph-th { background: #0B2A5B !important; color: #fff !important; font-weight: 700; text-align: center; padding: 1.5mm 1mm; font-size: 7.5pt; border: .4pt solid #0B2A5B; }
  .ph-th-orange { background: #D95A00 !important; color: #fff !important; font-weight: 700; text-align: center; padding: 1.5mm 1mm; font-size: 7.5pt; border: .4pt solid #D95A00; }
  .ph-day-th { background: #0B2A5B !important; color: #fff !important; text-align: center; font-size: 7.5pt; font-weight: 700; padding: 1.5mm 1mm; border: .4pt solid #0B2A5B; line-height: 1.3; }
  .ph-day-th .ph-day-date { display: block; font-weight: 400; font-size: 6pt; opacity: .85; }
  .ph-day-th.holiday { background: #D95A00 !important; border-color: #D95A00; }
  .ph-day-th .ph-holiday-name { display: block; font-size: 5.5pt; font-weight: 400; opacity: .9; }
  .ph-row-label { background: #edf1f9 !important; font-weight: 700; color: #0B2A5B !important; font-size: 6.5pt; padding: 1mm .8mm; text-align: center; vertical-align: middle; display: flex; align-items: center; justify-content: center; border: .4pt solid #bbb; writing-mode: vertical-rl; text-orientation: mixed; white-space: nowrap; }
  .ph-dish { font-weight: 700; font-size: 7pt; color: #000; display: block; }
  .ph-alg { font-size: 5.5pt; color: #555; display: block; margin-top: .3mm; }
  .ph-price { font-size: 6pt; color: #D95A00 !important; font-weight: 700; display: block; margin-top: .3mm; }
  .ph-cell.na { background: #f5f5f5 !important; color: #ccc !important; text-align: center; font-size: 6pt; }
  .ph-cell.holiday-cell { background: #fff8f0 !important; }
  .ph-holiday-note { font-size: 5.5pt; color: #D95A00 !important; font-style: italic; display: block; }
  .ph-section-title { font-size: 7pt; font-weight: 700; color: #D95A00 !important; text-transform: uppercase; letter-spacing: .2mm; margin: 2mm 0 1mm; }
  .ph-footer { margin-top: 2mm; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5mm; font-size: 5pt; line-height: 1.4; page-break-inside: avoid; }
  .ph-footer-box { padding: 1mm 1.5mm; background: #f8f9fa !important; border-left: 1.2mm solid #0B2A5B !important; border-radius: 1mm; }
  .ph-footer-box:nth-child(2) { border-left-color: #D95A00 !important; }
  .ph-footer-box strong { display: block; margin-bottom: .3mm; font-size: 5.5pt; }
  .ph-footer-legal { grid-column: 1/-1; text-align: center; padding: 1mm; background: #e9ecef !important; border-radius: 1mm; font-size: 4.5pt; }
  .ph-order { width: 50mm; border-left: 1.5mm dashed #D95A00 !important; padding-left: 3mm; position: relative; font-size: 6pt; page-break-inside: avoid; }
  .ph-scissors { position: absolute; left: -3.5mm; top: 50%; transform: translateY(-50%); font-size: 9pt; color: #D95A00 !important; }
  .ph-order-title { font-weight: 700; text-align: center; font-size: 8.5pt; color: #0B2A5B !important; text-transform: uppercase; letter-spacing: .2mm; margin-bottom: 1mm; }
  .ph-order-sub { text-align: center; font-size: 5pt; color: #D95A00 !important; font-style: italic; margin-bottom: 2mm; }
  .ph-order-table { width: 100%; border-collapse: collapse; margin-bottom: 1.5mm; table-layout: fixed; font-size: 5.5pt; }
  .ph-order-table th { background: #D95A00 !important; color: #fff !important; padding: .8mm .3mm; border: .4pt solid #D95A00; text-align: center; font-size: 5.5pt; }
  .ph-order-table td { height: 5mm; text-align: center; border: .4pt solid #ccc; background: #fff !important; font-size: 5.5pt; }
  .ph-order-table td.dc { background: #edf1f9 !important; font-weight: 700; color: #0B2A5B !important; width: 7mm; vertical-align: middle; }
  .ph-order-table td.ft { background: #fff8f0 !important; }
  .ph-divider { border-top: .6pt solid #0B2A5B; margin: 1.5mm 0; }
  .ph-cust { margin-top: 1.5mm; font-size: 5.5pt; }
  .ph-cust-row { margin-bottom: 1.5mm; }
  .ph-cust-label { font-weight: 700; display: block; color: #0B2A5B !important; font-size: 5.5pt; margin-bottom: .3mm; }
  .ph-cust-line { border-bottom: .7pt solid #333; height: 4mm; }
  .ph-notes-label { font-weight: 700; color: #0B2A5B !important; font-size: 5.5pt; display: block; margin: 1.5mm 0 .5mm; }
  .ph-notes-area { border: .4pt solid #ccc; height: 8mm; border-radius: 1mm; }
  .ph-addr { margin-top: 1.5mm; font-size: 5pt; line-height: 1.5; padding: 1mm 1.5mm; background: #f0f4fa !important; border-radius: 1mm; border: .4pt solid #dde4ef; }
  .ph-addr strong { color: #0B2A5B !important; display: block; font-size: 5.5pt; margin-bottom: .3mm; }
  .ph-addr-tel { color: #D95A00 !important; font-weight: 700; }
}

/* ── RESPONSIVE ─────────────────────────────────────── */
@media (max-width: 900px) {
  .header-inner { padding: 0 20px; }
  .header-contact { display: none; }
  .week-nav-inner { padding: 14px 20px; gap: 16px; }
  .main-wrap { padding: 24px 20px 60px; }
  .days-grid { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
  .header-inner { height: 60px; }
  .logo-name { font-size: 1rem; }
  .kw-title { font-size: 1.3rem; }
  .nav-btn span { display: none; }
}
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { transition: none !important; animation: none !important; }
}
</style>
</head>
<body>

<!-- ── HEADER ──────────────────────────────────────────── -->
<header class="site-header">
  <div class="header-inner">
    <div class="logo-wrap">
      <img src="/assets/images/BMV_Logo_n.png"
           alt="BMV-Menüdienst Logo"
           width="120" height="42"
           loading="eager" decoding="sync">
      <div>
        <div class="logo-name">BMV-Menüdienst</div>
        <div class="logo-sub">Frisch · Regional · Gesund</div>
      </div>
    </div>
    <div class="header-contact">
      <a href="tel:+4933275745066">03327 – 57 45 066</a><br>
      info@bmv-kantinen.de
    </div>
    <button class="print-btn" onclick="doPrint()">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
      Drucken
    </button>
  </div>
</header>

<!-- ── WOCHENNAVIGATION ────────────────────────────────── -->
<nav class="week-nav" aria-label="Wochennavigation">
  <div class="week-nav-inner">
    <a href="?year=<?= $prevYear ?>&kw=<?= $prevKW ?>"
       class="nav-btn <?= $isAtMin ? 'disabled' : '' ?>"
       aria-label="Vorherige Woche"
       <?= $isAtMin ? 'aria-disabled="true"' : '' ?>>
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
      <span>Vorherige Woche</span>
    </a>
    <div class="week-info">
      <div class="kw-title">Kalenderwoche <?= $kw ?> / <?= $year ?></div>
      <div class="kw-range"><?= $days[0]->format('d.m.Y') ?> – <?= $days[6]->format('d.m.Y') ?></div>
    </div>
    <a href="?year=<?= $nextYear ?>&kw=<?= $nextKW ?>"
       class="nav-btn <?= $isAtMax ? 'disabled' : '' ?>"
       aria-label="Nächste Woche"
       <?= $isAtMax ? 'aria-disabled="true"' : '' ?>>
      <span>Nächste Woche</span>
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
    </a>
  </div>
</nav>

<!-- ── HAUPTINHALT ─────────────────────────────────────── -->
<main class="main-wrap" id="main-content">

<?php if (!$plan): ?>
<div class="no-data" role="status">
  <div class="no-data-icon">📋</div>
  <h2>Kein Speiseplan vorhanden</h2>
  <p>Für KW <?= $kw ?>/<?= $year ?> wurde noch kein Speiseplan hinterlegt.</p>
</div>
<?php else: ?>

<div class="days-grid" role="list" aria-label="Wochenspeiseplan">
<?php for ($i = 0; $i < 7; $i++):
  $day      = $dayMap[$i] ?? null;
  $hasData  = $day && (!empty($day['menus']) || !empty($day['addons']));
  $dateStr  = $days[$i]->format('d.m.Y');
  $isoDate  = $days[$i]->format('Y-m-d');
  $feiertag = $feiertage[$isoDate] ?? null;
  $isWe     = $i >= 5;
?>
<article class="day-card <?= $isWe || $feiertag ? 'weekend' : '' ?> <?= $feiertag ? 'holiday-day' : '' ?>"
         role="listitem"
         aria-label="<?= $dayNames[$i] ?>, <?= $dateStr ?>">

  <div class="day-header">
    <div class="day-header-top">
      <span class="day-name"><?= $dayNames[$i] ?></span>
      <span class="day-date"><?= $dateStr ?></span>
    </div>
    <?php if ($feiertag): ?>
    <div class="holiday-tag" role="note" aria-label="Feiertag">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
      <?= htmlspecialchars($feiertag) ?>
    </div>
    <?php endif; ?>
  </div>

  <div class="menu-list">
  <?php foreach ($menuDefs as $n => $def):
    $m     = getMenu($day, $n);
    $title = $m['title'] ?? null;
    $alg   = $m['allergens'] ?? '';
    $price = $m['price'] ?? $def['price'];
    $veg   = !empty($m['vegetarian']);
    $isEmpty = !$title;
    $popupData = !$isEmpty ? json_encode([
      'badge' => $def['label'].' – '.$def['name'],
      'title' => $title, 'price' => number_format($price,2,',','.').' €',
      'alg' => $alg, 'img' => dishSearchQuery($title), 'veg' => $veg,
    ]) : '';
  ?>
  <div class="menu-item <?= $isEmpty ? 'is-empty' : '' ?>"
       <?= !$isEmpty ? "onclick=\"openModal($popupData)\" role=\"button\" tabindex=\"0\" aria-label=\"".htmlspecialchars($title)." ansehen\"" : '' ?>>
    <div class="dish-img-wrap" aria-hidden="true">
      <?php if ($title): ?>
      <img class="pexels-img"
           src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3C/svg%3E"
           data-query="<?= htmlspecialchars(dishSearchQuery($title)) ?>"
           alt="<?= htmlspecialchars($title) ?>"
           loading="lazy" decoding="async"
           width="60" height="60">
      <?php else: ?>
      <div class="dish-img-placeholder">–</div>
      <?php endif; ?>
    </div>
    <div class="menu-info">
      <div class="menu-label-row">
        <div class="menu-badge" style="background:<?= $def['color'] ?>" aria-hidden="true"><?= $def['label'] ?></div>
        <span class="menu-type"><?= $def['name'] ?></span>
        <?php if ($veg): ?><span class="veg-pill">veg.</span><?php endif; ?>
      </div>
      <div class="menu-title <?= $isEmpty ? 'empty' : '' ?>">
        <?= $title ? htmlspecialchars($title) : 'nicht verfügbar' ?>
      </div>
      <?php if ($title): ?>
      <div class="menu-meta">
        <span class="menu-price"><?= number_format($price,2,',','.').' €' ?></span>
        <?php if ($alg): ?><span class="menu-alg">(<?= htmlspecialchars($alg) ?>)</span><?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>

  <?php if ($i < 5): // Addons nur Mo–Fr
  foreach ($addonDefs as $code => $def):
    $a = getAddon($day, $code);
    $title = $a['name'] ?? null;
    $price = $a['price'] ?? $def['price'];
    $popupData = $title ? json_encode([
      'badge' => $def['name'], 'title' => $title,
      'price' => number_format($price,2,',','.').' €',
      'alg' => '', 'img' => dishSearchQuery($title), 'veg' => false,
    ]) : '';
  ?>
  <div class="menu-item is-addon <?= !$title ? 'is-empty' : '' ?>"
       <?= $title ? "onclick=\"openModal($popupData)\" role=\"button\" tabindex=\"0\"" : '' ?>>
    <div class="dish-img-wrap" aria-hidden="true">
      <?php if ($title): ?>
      <img class="pexels-img"
           src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3C/svg%3E"
           data-query="<?= htmlspecialchars(dishSearchQuery($title)) ?>"
           alt="<?= htmlspecialchars($title) ?>"
           loading="lazy" decoding="async"
           width="60" height="60">
      <?php else: ?>
      <div class="dish-img-placeholder" style="color:#e8d5ff">+</div>
      <?php endif; ?>
    </div>
    <div class="menu-info">
      <div class="menu-label-row">
        <div class="menu-badge" style="background:var(--orange);font-size:.65rem"><?= $def['label'] ?></div>
        <span class="menu-type"><?= $def['name'] ?></span>
      </div>
      <div class="menu-title <?= !$title ? 'empty' : '' ?>">
        <?= $title ? htmlspecialchars($title) : 'nicht verfügbar' ?>
      </div>
      <?php if ($title): ?>
      <div class="menu-meta">
        <span class="menu-price"><?= number_format($price,2,',','.').' €' ?></span>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; endif; ?>
  </div>
</article>
<?php endfor; ?>
</div>

<!-- ── ZUSATZKARTE-TABELLE ──────────────────────────── -->
<section class="addon-section" aria-labelledby="addon-heading">
  <div class="addon-header">
    <h2 id="addon-heading">Zusatzkarte – Montag bis Freitag</h2>
    <span class="addon-header-sub">Mo–Fr täglich verfügbar</span>
  </div>
  <div class="addon-table-wrap">
    <table class="addon-table" role="table">
      <thead>
        <tr>
          <th scope="col">Zusatz</th>
          <?php for ($i = 0; $i < 5; $i++): ?>
          <th scope="col"><?= $dayShort[$i] ?> <?= $days[$i]->format('d.m.') ?></th>
          <?php endfor; ?>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($addonDefs as $code => $def): ?>
      <tr>
        <td>
          <div class="addon-row-label">
            <div class="addon-code"><?= $def['label'] ?></div>
            <div>
              <div style="font-size:.85rem"><?= $def['name'] ?></div>
              <div class="addon-price"><?= number_format($def['price'],2,',','.').' €' ?></div>
            </div>
          </div>
        </td>
        <?php for ($i = 0; $i < 5; $i++):
          $day  = $dayMap[$i] ?? null;
          $iso  = $days[$i]->format('Y-m-d');
          $ft   = $feiertage[$iso] ?? null;
          $a    = getAddon($day, $code);
          $name = $a['name'] ?? null;
        ?>
        <td>
          <?php if ($ft && !$name): ?>
          <span class="holiday-cell">Feiertag</span>
          <?php elseif ($name): ?>
          <?= htmlspecialchars($name) ?>
          <?php else: ?>
          <span class="na-cell">–</span>
          <?php endif; ?>
        </td>
        <?php endfor; ?>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<?php endif; ?>
</main>

<!-- ── MODAL ───────────────────────────────────────────── -->
<div class="modal-overlay" id="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title"
     onclick="if(event.target===this)closeModal()">
  <div class="modal">
    <div class="modal-img-wrap">
      <img id="modal-img" src="" alt="" width="560" height="220" loading="lazy" decoding="async">
      <div class="modal-img-placeholder" id="modal-img-placeholder" aria-hidden="true">🍽️</div>
    </div>
    <div class="modal-body">
      <div class="modal-badge" id="modal-badge"></div>
      <div class="modal-title" id="modal-title"></div>
      <div class="modal-price" id="modal-price"></div>
      <div class="modal-alg" id="modal-alg" style="display:none"></div>
      <button class="modal-close" onclick="closeModal()">Schließen</button>
    </div>
  </div>
</div>

<!-- ── DRUCKLAYOUT ─────────────────────────────────────── -->
<div class="print-layout" id="print-layout" aria-hidden="true">
<div class="print-scale-wrap" id="print-scale-wrap">
  <div class="ph-header">
    <div class="ph-logo-wrap">
      <img class="ph-logo" src="/assets/images/BMV_Logo_n.png" alt="BMV" width="80" height="28">
      <div>
        <div class="ph-company-name">BMV-Menüdienst</div>
        <div class="ph-company-sub">Frisch · Regional · Gesund</div>
      </div>
    </div>
    <div class="ph-contact">Tel.: 03327 – 57 45 066<br>info@bmv-kantinen.de · www.bmv-kantinen.de</div>
  </div>
  <div class="ph-title">Wochenspeiseplan – KW <?= $kw ?>/<?= $year ?> &nbsp;·&nbsp; <?= $days[0]->format('d.m.') ?> – <?= $days[6]->format('d.m.Y') ?></div>
  <div class="ph-body">
    <div class="ph-main">
      <div class="ph-grid" style="--day-count:7">
        <div class="ph-th" style="grid-column:1;grid-row:1"></div>
        <?php for ($i = 0; $i < 7; $i++):
          $iso = $days[$i]->format('Y-m-d'); $ft = $feiertage[$iso] ?? null; ?>
        <div class="ph-day-th <?= $ft?'holiday':'' ?>" style="grid-column:<?= $i+2 ?>;grid-row:1">
          <?= $dayShort[$i] ?><span class="ph-day-date"><?= $days[$i]->format('d.m.') ?></span>
          <?php if ($ft): ?><span class="ph-holiday-name"><?= htmlspecialchars(mb_substr($ft,0,12)) ?></span><?php endif; ?>
        </div>
        <?php endfor; ?>
        <?php foreach ($menuDefs as $n => $def): $row = $n + 1; ?>
        <div class="ph-row-label" style="grid-column:1;grid-row:<?= $row ?>">
          <?= $def['label'] ?><br><span style="font-size:3.5pt;font-weight:400"><?= $def['name'] ?><br><?= number_format($def['price'],2,',','.').' €' ?></span>
        </div>
        <?php for ($i = 0; $i < 7; $i++):
          $day=$dayMap[$i]??null; $iso=$days[$i]->format('Y-m-d'); $ft=$feiertage[$iso]??null;
          $m=getMenu($day,$n); $title=$m['title']??null; $alg=$m['allergens']??'';
          $p=isset($m['price'])?number_format($m['price'],2,',','.').' €':''; $col=$i+2; ?>
        <?php if ($ft&&!$title): ?>
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
        <?php endfor; endforeach; ?>
      </div>
      <div class="ph-section-title">Zusatzkarte</div>
      <div class="ph-grid" style="--day-count:5">
        <div class="ph-th-orange" style="grid-column:1;grid-row:1"></div>
        <?php for ($i=0;$i<5;$i++): $iso=$days[$i]->format('Y-m-d'); $ft=$feiertage[$iso]??null; ?>
        <div class="ph-day-th <?= $ft?'holiday':'' ?>" style="grid-column:<?= $i+2 ?>;grid-row:1;background:#D95A00 !important;border-color:#D95A00 !important">
          <?= $dayShort[$i] ?><span class="ph-day-date"><?= $days[$i]->format('d.m.') ?></span>
          <?php if ($ft): ?><span class="ph-holiday-name"><?= htmlspecialchars(mb_substr($ft,0,10)) ?></span><?php endif; ?>
        </div>
        <?php endfor; ?>
        <?php $aRow=1; foreach ($addonDefs as $code=>$def): $aRow++; ?>
        <div class="ph-row-label" style="grid-column:1;grid-row:<?= $aRow ?>;background:#fff3e8 !important;color:#D95A00 !important">
          <?= $def['label'] ?><br><span style="font-size:3.5pt;font-weight:400"><?= $def['name'] ?><br><?= number_format($def['price'],2,',','.').' €' ?></span>
        </div>
        <?php for ($i=0;$i<5;$i++):
          $day=$dayMap[$i]??null; $iso=$days[$i]->format('Y-m-d'); $ft=$feiertage[$iso]??null;
          $a=getAddon($day,$code); $name=$a['name']??null;
          $p=isset($a['price'])?number_format($a['price'],2,',','.').' €':''; $col=$i+2; ?>
        <?php if ($ft&&!$name): ?>
        <div class="ph-cell holiday-cell" style="grid-column:<?= $col ?>;grid-row:<?= $aRow ?>">
          <?php if ($code==='D'): ?><span class="ph-holiday-note"><?= htmlspecialchars(mb_substr($ft,0,12)) ?></span><?php endif; ?>
        </div>
        <?php elseif ($name): ?>
        <div class="ph-cell" style="grid-column:<?= $col ?>;grid-row:<?= $aRow ?>">
          <span class="ph-dish"><?= htmlspecialchars($name) ?></span>
          <?php if ($p): ?><span class="ph-price"><?= $p ?></span><?php endif; ?>
        </div>
        <?php else: ?>
        <div class="ph-cell na" style="grid-column:<?= $col ?>;grid-row:<?= $aRow ?>">—</div>
        <?php endif; ?>
        <?php endfor; endforeach; ?>
      </div>
      <div class="ph-footer">
        <div class="ph-footer-box"><strong>Zusatzstoffe:</strong>1) Geschmacksverstärker · 2) Antioxidationsmittel · 3) Süßungsmittel · 4) Konservierungsmittel · 9) Milch/Sahne · 10) Formfleisch · 11) Nitritpökelsalz · 12) Phosphat</div>
        <div class="ph-footer-box"><strong>Allergene:</strong>13) Getreide/Weizen · 16) Fisch · 18) Eier · 19) Senf · 21) Soja · 22) Sellerie · 23) Schalenfrüchte · 24) Erdnüsse · 25) Schwefeldioxid</div>
        <div class="ph-footer-legal">Bestellung Mo.–So. 9–15 Uhr &nbsp;|&nbsp; Menüs zum Verzehr am Liefertag &nbsp;|&nbsp; Keine Lieferung an Feiertagen &nbsp;|&nbsp; AGB: www.bmv-kantinen.de</div>
      </div>
    </div>
    <div class="ph-order">
      <span class="ph-scissors">✂</span>
      <div class="ph-order-title">Bestellung BMV-Menüdienst</div>
      <div class="ph-order-sub">Abtrennen &amp; Fahrer mitgeben</div>
      <table class="ph-order-table">
        <thead><tr><th>Tag</th><th>M1</th><th>M2</th><th>M3</th><th>M4</th></tr></thead>
        <tbody>
        <?php for ($i=0;$i<7;$i++): $ft=$feiertage[$days[$i]->format('Y-m-d')]??null; ?>
        <tr>
          <td class="dc <?= $ft?'ft':'' ?>"><?= $dayShort[$i] ?></td>
          <?php if ($ft): ?>
          <td class="ft" colspan="4" style="text-align:center;color:#D95A00;font-style:italic;font-size:3.5pt"><?= htmlspecialchars(mb_substr($ft,0,14)) ?></td>
          <?php else: ?><td></td><td></td><td></td><td></td><?php endif; ?>
        </tr>
        <?php endfor; ?>
        </tbody>
      </table>
      <div class="ph-divider"></div>
      <table class="ph-order-table">
        <thead>
          <tr><th colspan="5" style="background:#D95A00 !important;border-color:#D95A00">Zusatzkarte</th></tr>
          <tr><th>Tag</th><th>De</th><th>Ro</th><th>Ab</th><th>Sa</th></tr>
        </thead>
        <tbody>
        <?php for ($i=0;$i<5;$i++): $ft=$feiertage[$days[$i]->format('Y-m-d')]??null; ?>
        <tr>
          <td class="dc <?= $ft?'ft':'' ?>"><?= $dayShort[$i] ?></td>
          <?php if ($ft): ?>
          <td class="ft" colspan="4" style="text-align:center;color:#D95A00;font-size:3.5pt">Feiertag</td>
          <?php else: ?><td></td><td></td><td></td><td></td><?php endif; ?>
        </tr>
        <?php endfor; ?>
        </tbody>
      </table>
      <div class="ph-cust">
        <div class="ph-cust-row"><span class="ph-cust-label">Name:</span><div class="ph-cust-line"></div></div>
        <div class="ph-cust-row"><span class="ph-cust-label">Kundennummer:</span><div class="ph-cust-line"></div></div>
        <div class="ph-cust-row"><span class="ph-cust-label">Telefon:</span><div class="ph-cust-line"></div></div>
      </div>
      <span class="ph-notes-label">Besondere Wünsche:</span>
      <div class="ph-notes-area"></div>
      <div class="ph-addr">
        <strong>BMV-Menüdienst</strong>
        Am Gutshof 6 · 14542 Werder (Havel)<br>
        <span class="ph-addr-tel">Tel.: 03327 – 57 45 066</span><br>
        info@bmv-kantinen.de
      </div>
    </div>
  </div>
</div>
</div>

<script>
(function() {
  'use strict';

  /* ── Pexels Image Loader ──────────────────────────── */
  const cache = Object.create(null);

  async function fetchImg(query) {
    if (query in cache) return cache[query];
    try {
      const r = await fetch('/api/pexels_image.php?q=' + encodeURIComponent(query));
      if (!r.ok) throw new Error();
      const d = await r.json();
      return (cache[query] = d.url || null);
    } catch {
      return (cache[query] = null);
    }
  }

  async function loadImages() {
    const imgs = [...document.querySelectorAll('img.pexels-img[data-query]')];
    // Batches of 6 to respect rate limits
    for (let i = 0; i < imgs.length; i += 6) {
      await Promise.all(imgs.slice(i, i + 6).map(async img => {
        const url = await fetchImg(img.dataset.query);
        if (url) {
          img.onload = () => img.classList.add('loaded');
          img.src = url;
        }
      }));
    }
  }

  /* ── Modal ─────────────────────────────────────────── */
  function openModal(data) {
    document.getElementById('modal-badge').textContent  = data.badge;
    document.getElementById('modal-title').textContent  = data.title || '—';
    document.getElementById('modal-price').textContent  = data.price;
    const algEl = document.getElementById('modal-alg');
    algEl.style.display = data.alg ? 'block' : 'none';
    if (data.alg) algEl.textContent = 'Allergene: ' + data.alg;

    const img = document.getElementById('modal-img');
    const ph  = document.getElementById('modal-img-placeholder');
    img.src = ''; img.classList.remove('loaded'); ph.style.display = 'flex';

    fetchImg(data.img || 'food dish').then(url => {
      if (url) {
        img.onload = () => { img.classList.add('loaded'); ph.style.display = 'none'; };
        img.src = url;
      }
    });

    document.getElementById('modal').classList.add('open');
    document.body.style.overflow = 'hidden';
    document.getElementById('modal-title').focus();
  }

  function closeModal() {
    document.getElementById('modal').classList.remove('open');
    document.body.style.overflow = '';
  }

  // Keyboard: Enter on menu items + Escape to close
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
    if (e.key === 'Enter' && e.target.classList.contains('menu-item')) e.target.click();
  });

  // Expose globals
  window.openModal  = openModal;
  window.closeModal = closeModal;

  /* ── Print Scaling ────────────────────────────────── */
  // A4 quer (289 × 202 mm) in CSS-Pixel bei 96 dpi
  const PRINT_PAGE_W_PX = 289 / 25.4 * 96; // ≈ 1093 px
  const PRINT_PAGE_H_PX = 202 / 25.4 * 96; // ≈  764 px

  function calcScale() {
    const layout = document.getElementById('print-layout');
    const wrap   = document.getElementById('print-scale-wrap');
    if (!wrap) return;
    wrap.style.transform = 'none';
    const scale = Math.min(PRINT_PAGE_W_PX / wrap.scrollWidth, PRINT_PAGE_H_PX / wrap.scrollHeight, 1);
    wrap.style.transform      = `scale(${scale})`;
    wrap.style.transformOrigin = 'top left';
    layout.style.height        = (wrap.scrollHeight * scale) + 'px';
  }

  window.addEventListener('beforeprint', () => {
    document.getElementById('print-layout').style.display = 'block';
    calcScale();
  });
  window.addEventListener('afterprint', () => {
    const l = document.getElementById('print-layout');
    l.style.display = '';
    const w = document.getElementById('print-scale-wrap');
    if (w) w.style.transform = 'none';
  });

  window.doPrint = function() {
    document.getElementById('print-layout').style.display = 'block';
    calcScale();
    setTimeout(window.print, 150);
  };

  /* ── Init ─────────────────────────────────────────── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadImages);
  } else {
    loadImages();
  }
})();
</script>
</body>
</html>
