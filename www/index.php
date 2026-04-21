<?php
/**
 * www/index.php – BMV-Menüdienst Startseite
 * v7.1 – Responsive fixes: CSS reset, container fallback, flexible units
 */

// Slug router: redirects vanity URLs like /werder-havel to their canonical subpage.
// Must run before any output. No-op when the URI matches a physical directory.
require_once __DIR__ . '/slug-router.php';

// Page variables
$page_title       = 'Essen auf Rädern in Potsdam & Werder – täglich frisch geliefert | BMV-Menüdienst';
$meta_description = 'Täglich warmes Mittagessen direkt an die Haustür – in Potsdam, Werder (Havel) und Umland. Essen auf Rädern, Kantine & Catering von BMV-Menüdienst. ☎ +49 3327 5745066';
$active_nav       = 'home';
$canonical        = 'https://www.bmv-kantinen.de/';

require_once __DIR__ . '/includes/helpers.php';

// Load constants from header.php
if (!defined('BMV_NAME')) {
    define('BMV_NAME',  'BMV-Menüdienst');
    define('BMV_TEL',   '+4933275745066');
    define('BMV_TEL_DISPLAY', '+49 3327 5745066');
    define('BMV_EMAIL', 'info@bmv-kantinen.de');
    define('BMV_URL',   'https://www.bmv-kantinen.de');
}

// Load menu data
$currentKW   = (int)date('W');
$currentYear = (int)date('Y');
$kwStr       = str_pad($currentKW, 2, '0', STR_PAD_LEFT);
$newFile     = $_SERVER['DOCUMENT_ROOT'] . "/data/speiseplaene/essen_auf_raedern-{$currentYear}-KW{$kwStr}.json";
$oldFile     = $_SERVER['DOCUMENT_ROOT'] . "/data/speiseplaene/{$currentYear}-KW{$kwStr}.json";
$preview     = null;

if (file_exists($newFile)) {
    $raw = json_decode(file_get_contents($newFile), true);
    if ($raw && isset($raw['data'])) $preview = $raw['data'];
} elseif (file_exists($oldFile)) {
    $raw = json_decode(file_get_contents($oldFile), true);
    if ($raw && !empty($raw['days'])) $preview = $raw['days'];
}

if (!$preview) {
    $planDir = $_SERVER['DOCUMENT_ROOT'] . '/data/speiseplaene/';
    $files   = glob($planDir . '*.json');
    if ($files) {
        rsort($files);
        foreach ($files as $f) {
            $raw = json_decode(file_get_contents($f), true);
            if ($raw && !empty($raw['days'])) { $preview = $raw['days']; break; }
            if ($raw && isset($raw['data']))  { $preview = $raw['data'];  break; }
        }
    }
}

$dayNames = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];
?>
<!DOCTYPE html>
<html lang="de" prefix="og: https://ogp.me/ns#">
<head>
  <meta charset="UTF-8">
  <!-- FIX 1: Viewport-Tag mit korrekten Werten – verhindert das "zu klein"-Problem auf Mobilgeräten
              und in Docker-Umgebungen, die keinen eigenen Viewport setzen. -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($meta_description) ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">

  <!-- Open Graph -->
  <meta property="og:type"         content="website">
  <meta property="og:url"          content="<?= htmlspecialchars($canonical) ?>">
  <meta property="og:title"        content="<?= htmlspecialchars($page_title) ?>">
  <meta property="og:description"  content="<?= htmlspecialchars($meta_description) ?>">
  <meta property="og:image"        content="<?= BMV_URL ?>/assets/images/og-image.jpg">
  <meta property="og:locale"       content="de_DE">

  <!-- Favicon -->
  <link rel="icon"             href="/assets/images/Favicon.png" type="image/png">
  <link rel="apple-touch-icon" href="/assets/images/Favicon.png">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

  <!-- Stylesheets (externe CSS-Dateien) -->
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/design-system.css">
  <link rel="stylesheet" href="/assets/css/hero-unified.css">
  <link rel="stylesheet" href="/assets/css/bmv-ci.css">

  <!-- Skip link -->
  <style>
    .skip-link { position: absolute; top: -9999px; left: -9999px; z-index: 999; }
    .skip-link:focus { top: 0; left: 0; }
  </style>

  <style>
/* ══════════════════════════════════════════════════════
   FIX 2: CSS-Reset & Box-Sizing
   Fehlt dieser Block, interpretiert jeder Browser
   margin/padding/box-sizing unterschiedlich → Layout bricht.
   Dies ist der häufigste Grund für das "200%-Zoom"-Problem
   wenn externe CSS-Dateien (main.css etc.) nicht laden.
   ══════════════════════════════════════════════════════ */
*, *::before, *::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

/* FIX 3: Basis-Schriftgröße auf 100% (≈16px) setzen.
   Viele Zoom-Probleme entstehen, wenn html/body eine
   feste px-Größe haben. rem-Einheiten bauen darauf auf. */
html {
  font-size: 100%;
  -webkit-text-size-adjust: 100%;   /* iOS verhindert auto-zoom bei Rotation */
  text-size-adjust: 100%;
  scroll-behavior: smooth;
}

body {
  font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
  font-size: 1rem;          /* = 16px – niemals px direkt auf body */
  line-height: 1.6;
  color: #1a2535;
  background: #fff;
  min-width: 320px;         /* verhindert Layout-Bruch auf sehr kleinen Screens */
  overflow-x: hidden;
}

/* FIX 4: Bilder immer responsiv.
   width/height-Attribute im HTML sind nur Hints; ohne
   max-width:100% sprengen sie ihren Container. */
img, video, svg {
  max-width: 100%;
  height: auto;
  display: block;
}

/* FIX 5: Container-Fallback.
   Wenn main.css nicht lädt, fehlt .container → alle Inhalte
   kleben am Rand oder sind 100vw breit. Diese Definition
   greift als Fallback und schadet nicht, wenn main.css vorhanden. */
.container {
  width: 100%;
  max-width: 1200px;
  margin-inline: auto;
  padding-inline: clamp(1rem, 4vw, 2rem);  /* fluid padding statt fester px */
}

/* ── Skip Link ── */
a { color: inherit; }

/* ── Header Fallback ── */
.site-header {
  position: sticky;
  top: 0;
  z-index: 100;
  background: #fff;
  border-bottom: 1px solid #e4edf8;
  box-shadow: 0 2px 8px rgba(7,21,38,.06);
}
.site-header .container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  min-height: 4rem;     /* rem statt px */
}
/* FIX 6: Logo-Größe in rem, nicht px.
   width="120" im HTML-Attribut wird vom Browser als
   physikalische Pixelangabe behandelt → auf HiDPI-Screens
   wirkt es zu klein; in Docker-VMs zu groß. */
.site-logo img {
  width: clamp(90px, 12vw, 140px);
  height: auto;
}

/* ── Navigation Fallback ── */
.site-nav {
  display: flex;
  align-items: center;
  gap: clamp(0.5rem, 1.5vw, 1.5rem);
  flex-wrap: wrap;
}
.site-nav__link {
  font-size: 0.9rem;
  font-weight: 500;
  color: #1a2535;
  text-decoration: none;
  padding: 0.25rem 0;
  border-bottom: 2px solid transparent;
  transition: color .2s, border-color .2s;
  white-space: nowrap;
}
.site-nav__link:hover,
.site-nav__link.active {
  color: #0B2A5B;
  border-bottom-color: #D95A00;
}
.nav-cta { margin-left: 0.5rem; }
.nav-toggle {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.25rem;
  color: #0B2A5B;
}

/* ── Buttons Fallback ── */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1.25rem;
  font-size: 0.9rem;
  font-weight: 600;
  border-radius: 8px;
  text-decoration: none;
  cursor: pointer;
  border: 2px solid transparent;
  transition: background .2s, color .2s, border-color .2s, transform .15s;
  white-space: nowrap;
}
.btn:hover { transform: translateY(-1px); }
.btn--primary  { background: #D95A00; color: #fff; border-color: #D95A00; }
.btn--primary:hover { background: #b84d00; border-color: #b84d00; }
.btn--secondary{ background: #0B2A5B; color: #fff; border-color: #0B2A5B; }
.btn--secondary:hover { background: #0a2350; }
.btn--outline  { background: transparent; color: #0B2A5B; border-color: #0B2A5B; }
.btn--outline:hover { background: #0B2A5B; color: #fff; }
.btn--ghost    { background: rgba(255,255,255,.15); color: #fff; border-color: rgba(255,255,255,.4); }
.btn--ghost:hover { background: rgba(255,255,255,.25); }
.btn--sm { padding: 0.4rem 1rem; font-size: 0.82rem; }

/* ── Section-Grundlayout Fallback ── */
.section        { padding: clamp(3rem, 6vw, 5rem) 0; }
.section--bg    { background: #f5f8ff; }
.section-header { margin-bottom: clamp(1.5rem, 3vw, 2.5rem); }
.section-title  {
  font-size: clamp(1.6rem, 3vw, 2.4rem);
  font-weight: 800;
  color: #0B2A5B;
  line-height: 1.2;
  margin-bottom: 0.5rem;
  letter-spacing: -0.02em;
}
.section-sub {
  font-size: clamp(0.95rem, 1.6vw, 1.1rem);
  color: #5a6a82;
  max-width: 640px;
}

/* ── Hero Fallback ── */
.hero {
  position: relative;
  min-height: clamp(420px, 60vh, 700px);
  display: flex;
  align-items: center;
  overflow: hidden;
}
.hero__bg {
  position: absolute;
  inset: 0;
  z-index: 0;
}
.hero__bg img {
  width: 100%;
  height: 100%;
  max-width: none;          /* FIX 7: Hero-Bild darf den Container überschreiten */
  object-fit: cover;
}
.hero__overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(11,42,91,.75) 0%, rgba(11,42,91,.45) 100%);
}
.hero .container {
  position: relative;
  z-index: 1;
  padding-top: 3rem;
  padding-bottom: 3rem;
}
.hero__content { max-width: 600px; }
.hero__title {
  font-size: clamp(2rem, 5vw, 3.5rem);
  font-weight: 800;
  color: #fff;
  line-height: 1.1;
  margin-bottom: 1rem;
  letter-spacing: -0.03em;
  text-shadow: 0 2px 8px rgba(0,0,0,.3);
}
.hero__actions {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  margin-bottom: 2rem;
}
.hero__trust {
  display: flex;
  gap: 1.25rem;
  flex-wrap: wrap;
}
.hero__trust-item {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.88rem;
  font-weight: 500;
  color: rgba(255,255,255,.9);
}

/* ── Fade-Up Utility ── */
.fade-up{opacity:0;transform:translateY(24px);transition:opacity .55s ease,transform .55s ease}
.fade-up.is-visible{opacity:1;transform:translateY(0)}

/* ── Hero Badge ── */
.hero__badge{display:inline-flex;align-items:center;gap:6px;font-size:.78rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#ff7a1a;margin-bottom:20px;text-shadow:0 1px 4px rgba(0,0,0,.3)}

/* ── Hero Sub ── */
.hero__sub{font-size:clamp(.95rem,1.8vw,1.15rem);line-height:1.75;color:rgba(255,255,255,.92);max-width:580px;margin-bottom:clamp(24px,3vw,36px);text-shadow:0 1px 4px rgba(0,0,0,.35),0 3px 12px rgba(0,0,0,.25)}

/* ── Section Header Eyebrow ── */
.section-header__eyebrow{display:inline-block;font-size:.75rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#D95A00;margin-bottom:10px}

/* ── Stats ── */
.stats{padding:clamp(2.5rem,5vw,4rem) 0;background:#fff}   /* FIX 8: clamp statt fester px */
.stats__grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
.stat-card{background:#fff;border:1px solid #e4edf8;border-radius:16px;padding:clamp(1.25rem,3vw,2rem) clamp(1rem,2.5vw,1.75rem);box-shadow:0 4px 6px rgba(7,21,38,.07),0 2px 4px rgba(7,21,38,.06);transition:transform .25s ease,box-shadow .25s ease}
.stat-card:hover{transform:translateY(-4px);box-shadow:0 20px 25px rgba(7,21,38,.08),0 8px 10px rgba(7,21,38,.04)}
.stat-card__icon{width:3rem;height:3rem;background:#f0f6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:1rem}
.stat-card__icon svg{fill:#0B2A5B}
.stat-card__number{font-size:clamp(2rem,4vw,3rem);font-weight:800;color:#0B2A5B;line-height:1;margin-bottom:.25rem}
.stat-card__label{font-size:1rem;font-weight:700;color:#1a2535;margin-bottom:.4rem}
.stat-card__sub{font-size:.85rem;color:#5a6a82;line-height:1.5;margin-bottom:1rem}
.stat-card__check{display:flex;align-items:center;gap:6px;font-size:.82rem;font-weight:500;color:#1a2535}
.stat-card__check svg{fill:#D95A00;flex-shrink:0}

/* ── Services Grid ── */
.services-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
.service-card{background:#fff;border:1px solid #e4edf8;border-radius:16px;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 4px 6px rgba(7,21,38,.07);transition:transform .25s ease,box-shadow .25s ease}
.service-card:hover{transform:translateY(-4px);box-shadow:0 20px 25px rgba(7,21,38,.08)}
.service-card__img{overflow:hidden;height:200px}
.service-card__img.img-wrap{background:#e4edf8}
.service-card__img img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease,opacity .35s ease}
.service-card:hover .service-card__img img{transform:scale(1.04)}
.service-card__body{padding:clamp(1rem,2.5vw,1.75rem);flex:1;display:flex;flex-direction:column}
.service-card__title{font-size:1.1rem;font-weight:700;color:#0B2A5B;margin-bottom:.6rem}
.service-card__text{font-size:.9rem;color:#5a6a82;line-height:1.7;margin-bottom:1rem;flex:1}
.service-card__checks{display:flex;flex-direction:column;gap:6px;margin-bottom:1.25rem}
.service-card__check{display:flex;align-items:center;gap:6px;font-size:.83rem;color:#1a2535}
.service-card__check svg{fill:#D95A00;flex-shrink:0}
.service-card__link{display:inline-flex;align-items:center;gap:4px;font-size:.88rem;font-weight:600;color:#1046a0;text-decoration:none;margin-top:auto;transition:gap .2s ease}
.service-card__link:hover{gap:8px;color:#0B2A5B}
.service-card__link svg{fill:currentColor;flex-shrink:0}

/* ── Alt Blocks ── */
.alt-block{display:grid;grid-template-columns:1fr 1fr;gap:clamp(2rem,5vw,4rem);align-items:center;padding:3rem 0}
.alt-block+.alt-block{border-top:1px solid #e4edf8}
.alt-block--reverse{direction:rtl}
.alt-block--reverse>*{direction:ltr}
.alt-block__eyebrow{display:inline-block;font-size:.75rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#D95A00;margin-bottom:10px}
.alt-block__title{font-size:clamp(1.4rem,2.5vw,2rem);font-weight:800;color:#0B2A5B;line-height:1.15;margin-bottom:.9rem}
.alt-block__text p,.alt-block__text{font-size:.95rem;color:#5a6a82;line-height:1.8;margin-bottom:1.25rem}
.alt-block__checks{display:flex;flex-direction:column;gap:8px;margin-bottom:1.5rem}
.alt-block__check{display:flex;align-items:center;gap:8px;font-size:.9rem;color:#1a2535}
.alt-block__check svg{fill:#D95A00;flex-shrink:0}
.alt-block__visual{border-radius:16px;overflow:hidden;box-shadow:0 10px 15px rgba(7,21,38,.08)}
.alt-block__visual img{width:100%;height:100%;object-fit:cover;display:block;transition:opacity .35s ease}

/* ── Menu Preview ── */
.menu-preview{padding:clamp(3rem,6vw,5rem) 0;background:#0B2A5B}
.menu-preview .section-title,.menu-preview h2{color:#fff}
.menu-preview .section-sub{color:rgba(255,255,255,.68)}
.menu-preview .section-header__eyebrow{color:rgba(255,255,255,.55)}
.menu-scroll{display:flex;gap:1rem;overflow-x:auto;padding-bottom:8px;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.2) transparent}
.menu-day-card{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:14px;min-width:clamp(180px,22vw,240px);flex-shrink:0;overflow:hidden;cursor:pointer;transition:background .2s ease,transform .2s ease;scroll-snap-align:start}  /* FIX 9: min-width fluid */
.menu-day-card:hover{background:rgba(255,255,255,.14);transform:translateY(-3px)}
.menu-day-card__header{display:flex;align-items:center;justify-content:space-between;padding:.9rem 1rem .6rem}
.menu-day-card__name{font-size:.85rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.7)}
.menu-day-card__arrow svg{fill:rgba(255,255,255,.4);transition:fill .2s ease}
.menu-day-card:hover .menu-day-card__arrow svg{fill:#ff7a1a}
.menu-day-card__img{height:140px;overflow:hidden}
.menu-day-card__img.img-wrap{background:rgba(255,255,255,.06)}
.menu-day-card__img.img-wrap::before{background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,.2) 40%,rgba(255,255,255,.35) 50%,rgba(255,255,255,.2) 60%,transparent 100%);background-size:200% 100%;animation:shimmer 1.4s infinite linear}
.menu-day-card__img img{width:100%;height:100%;object-fit:cover;display:block;transition:opacity .35s ease}
.menu-day-card__img img.pexels-img{opacity:0}
.menu-day-card__img-placeholder{height:140px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.04)}
.menu-day-card__img-placeholder svg{fill:rgba(255,255,255,.15)}
.menu-day-card__body{padding:.75rem 1rem 1rem}
.menu-day-card__dish{font-size:.9rem;font-weight:600;color:#fff;line-height:1.4;margin-bottom:.5rem}
.menu-day-card__more{display:inline-flex;align-items:center;gap:4px;font-size:.78rem;font-weight:600;color:#ff7a1a}
.menu-day-card__more svg{fill:currentColor}
.menu-preview__cta{margin-top:2rem;text-align:center}

/* ── Process Steps ── */
.process-steps{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;position:relative}
.process-steps::before{content:'';position:absolute;top:27px;left:calc(12.5% + 27px);right:calc(12.5% + 27px);height:2px;background:linear-gradient(90deg,#1046a0,#3b72d4);z-index:0}
.process-step{text-align:center;position:relative;z-index:1}
.process-step__num{width:3.375rem;height:3.375rem;border-radius:50%;background:#1046a0;color:#fff;font-size:1.1rem;font-weight:800;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;box-shadow:0 4px 14px rgba(30,111,217,.35)}
.process-step__title{font-size:.95rem;font-weight:700;color:#0B2A5B;margin-bottom:.375rem}
.process-step__text{font-size:.83rem;color:#5a6a82;line-height:1.6}

/* ── Final CTA ── */
.final-cta{padding:clamp(3rem,6vw,5rem) 0;background:linear-gradient(135deg,#0B2A5B 0%,#1a3f7a 100%);text-align:center;position:relative;overflow:hidden}
.final-cta::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at 50% 50%,rgba(30,111,217,.15) 0%,transparent 65%);pointer-events:none}
.final-cta .container{position:relative;z-index:1}
.final-cta__title{font-size:clamp(1.6rem,3.5vw,2.4rem);font-weight:800;color:#fff;margin-bottom:.875rem;letter-spacing:-.02em}
.final-cta__sub{font-size:1rem;color:rgba(255,255,255,.72);margin-bottom:2rem}
.final-cta__actions{display:flex;align-items:center;justify-content:center;gap:1rem;flex-wrap:wrap}

/* ── Skeleton / Shimmer ── */
.img-wrap{position:relative;overflow:hidden;background:#e4edf8}
.img-wrap::before{
  content:'';
  position:absolute;inset:0;
  background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,.55) 40%,rgba(255,255,255,.8) 50%,rgba(255,255,255,.55) 60%,transparent 100%);
  background-size:200% 100%;
  animation:shimmer 1.4s infinite linear;
  z-index:1;
  pointer-events:none;
}
.img-wrap.img-loaded::before{display:none}
.img-wrap img{opacity:0;transition:opacity .35s ease;position:relative;z-index:2}
.img-wrap.img-loaded img{opacity:1}
@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}

/* ══════════════════════════════════════════════════════
   FIX 10: Responsive Breakpoints
   Tablet  → 2-spaltig
   Mobile  → 1-spaltig, Navigation als Burger-Menü
   ══════════════════════════════════════════════════════ */
@media (max-width: 1024px) {
  .stats__grid,
  .services-grid       { grid-template-columns: repeat(2, 1fr); }
  .process-steps       { grid-template-columns: repeat(2, 1fr); }
  .process-steps::before { display: none; }
  .alt-block           { gap: 2.5rem; }
}

@media (max-width: 768px) {
  /* Navigation: Burger-Menü */
  .site-nav {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    flex-direction: column;
    align-items: flex-start;
    gap: 0;
    padding: 1rem;
    box-shadow: 0 8px 20px rgba(7,21,38,.1);
    border-top: 1px solid #e4edf8;
  }
  .site-nav.is-open   { display: flex; }
  .site-nav__link     { padding: .75rem .5rem; width: 100%; }
  .nav-cta            { width: 100%; padding: .5rem 0; }
  .nav-cta .btn       { width: 100%; justify-content: center; }
  .nav-toggle         { display: block; }

  /* Layouts */
  .stats__grid,
  .services-grid        { grid-template-columns: 1fr; }
  .alt-block,
  .alt-block--reverse   { grid-template-columns: 1fr; direction: ltr; gap: 2rem; padding: 2rem 0; }
  .alt-block__visual    { order: -1; }
  .process-steps        { grid-template-columns: 1fr; gap: 1rem; }
  .hero__actions        { flex-direction: column; }
  .hero__actions .btn   { width: 100%; justify-content: center; }
  .final-cta__actions   { flex-direction: column; }
  .final-cta__actions .btn { width: 100%; justify-content: center; }

  /* Hero-Höhe auf Mobilgeräten reduzieren */
  .hero { min-height: clamp(360px, 70vw, 480px); }
}

@media (max-width: 480px) {
  /* Sehr kleine Screens (≤480px) */
  .menu-day-card { min-width: 72vw; }
  .stats__grid   { gap: 1rem; }
}
  </style>
</head>
<body>

<!-- Skip link -->
<a class="skip-link" href="#main-content">Zum Hauptinhalt springen</a>

<!-- Header -->
<header class="site-header" id="site-header" role="banner">
  <div class="container">
    <a href="/" class="site-logo" aria-label="<?= BMV_NAME ?> – Startseite">
      <!-- FIX 11: width/height als Attribute beibehalten (für CLS-Score),
           aber CSS übernimmt die tatsächliche Darstellungsgröße (clamp oben). -->
      <img src="/assets/images/BMV_Logo_n.png" alt="<?= BMV_NAME ?> Logo" width="120" height="36" loading="eager">
    </a>
    <nav class="site-nav" id="site-nav" aria-label="Hauptnavigation">
      <a href="/" class="site-nav__link <?= $active_nav === 'home' ? 'active' : '' ?>">Startseite</a>
      <a href="/speiseplan/" class="site-nav__link <?= $active_nav === 'speiseplan' ? 'active' : '' ?>">Speiseplan</a>
      <a href="/essen-auf-raedern/" class="site-nav__link <?= $active_nav === 'ears' ? 'active' : '' ?>">Essen auf Rädern</a>
      <a href="/kantine-am-gutshof/" class="site-nav__link <?= $active_nav === 'kantine' ? 'active' : '' ?>">Kantine am Gutshof</a>
      <a href="/catering/" class="site-nav__link <?= $active_nav === 'catering' ? 'active' : '' ?>">Catering</a>
      <a href="/ueber-uns/" class="site-nav__link <?= $active_nav === 'ueber-uns' ? 'active' : '' ?>">Über uns</a>
      <a href="/kontakt/" class="site-nav__link <?= $active_nav === 'kontakt' ? 'active' : '' ?>">Kontakt</a>
      <div class="nav-cta">
        <a href="/kontakt/" class="btn btn--primary btn--sm">Jetzt bestellen</a>
      </div>
    </nav>
    <button class="nav-toggle" id="nav-toggle" aria-controls="site-nav" aria-expanded="false" aria-label="Menü öffnen">
      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
        <line x1="2" y1="6" x2="20" y2="6"/><line x1="2" y1="11" x2="20" y2="11"/><line x1="2" y1="16" x2="20" y2="16"/>
      </svg>
    </button>
  </div>
</header>

<main id="main-content" role="main">

  <!-- Hero -->
  <section class="hero" aria-labelledby="hero-heading">
    <div class="hero__bg">
      <img src="/assets/images/hero-bg.jpg" alt="Frisches Essen auf Rädern – Lieferung in Potsdam" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
      <div class="hero__overlay"></div>
    </div>
    <div class="container">
      <div class="hero__content">
        <div class="hero__badge">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
          Potsdam &amp; Werder (Havel)
        </div>
        <h1 class="hero__title" id="hero-heading">
          Warmes Mittagessen –<br>
          <span style="color:#ff7a1a;">täglich an Ihre Tür</span>
        </h1>
        <p class="hero__sub">
          Seit Jahren liefern wir frisch zubereitetes Mittagessen in Potsdam und Werder (Havel).
          Verlässlich, pünktlich – für Senioren, Familien und Betriebe.
        </p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="/speiseplan/">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
            Speiseplan diese Woche
          </a>
          <a class="btn btn--ghost" href="/kontakt/">Kostenlos anfragen</a>
        </div>
        <div class="hero__trust">
          <div class="hero__trust-item">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="#f06820" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            Täglich frisch zubereitet
          </div>
          <div class="hero__trust-item">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="#f06820" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            Pünktlich – Mo bis So
          </div>
          <div class="hero__trust-item">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="#f06820" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            Keine Mindestlaufzeit
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats -->
  <section class="stats" aria-label="Kennzahlen">
    <div class="container">
      <div class="stats__grid">
        <div class="stat-card fade-up">
          <div class="stat-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
          </div>
          <div class="stat-card__number">500+</div>
          <div class="stat-card__label">Kunden täglich</div>
          <div class="stat-card__sub">In Potsdam &amp; Werder beliefert</div>
          <div class="stat-card__check">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            Potsdam, Werder &amp; Umland
          </div>
        </div>
        <div class="stat-card fade-up" style="transition-delay:.1s">
          <div class="stat-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
          </div>
          <div class="stat-card__number">100%</div>
          <div class="stat-card__label">Regional &amp; frisch</div>
          <div class="stat-card__sub">Täglich neu zubereitet – keine Tiefkühlware</div>
          <div class="stat-card__check">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            Keine Convenience – echte Küche
          </div>
        </div>
        <div class="stat-card fade-up" style="transition-delay:.2s">
          <div class="stat-card__icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
          </div>
          <div class="stat-card__number">15+</div>
          <div class="stat-card__label">Jahre Erfahrung</div>
          <div class="stat-card__sub">Zuverlässig seit über 15 Jahren</div>
          <div class="stat-card__check">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            Geprüfte Hygienestandards
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services -->
  <section class="section section--bg" aria-labelledby="services-heading">
    <div class="container">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">Was wir anbieten</div>
        <h2 class="section-title" id="services-heading">Drei Wege zu gutem Essen</h2>
        <p class="section-sub">Ob Lieferung nach Hause, Kantine oder Event – wir kochen täglich frisch für Sie.</p>
      </div>
      <div class="services-grid">
        <article class="service-card fade-up">
          <div class="service-card__img img-wrap">
            <img src="/assets/images/essen-auf-raedern-lieferung.jpg" alt="Essen auf Rädern Lieferung von BMV-Menüdienst" loading="lazy" decoding="async" width="400" height="200">
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Essen auf Rädern</h3>
            <p class="service-card__text">Warmes Mittagessen pünktlich an Ihre Tür – auch am Wochenende. Ideal für Senioren, Pflegebedürftige und alle, die sich täglich gut versorgen möchten.</p>
            <div class="service-card__checks">
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Lieferung auch Sa &amp; So</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Direkte Pflegekassen-Abrechnung</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Täglich 4 Menüs – inkl. Schonkost</div>
            </div>
            <a class="service-card__link" href="/essen-auf-raedern/">Jetzt informieren<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></a>
          </div>
        </article>
        <article class="service-card fade-up" style="transition-delay:.1s">
          <div class="service-card__img img-wrap">
            <img src="/assets/images/kantine-gutshof.jpg" alt="Kantine am Gutshof Werder von BMV-Menüdienst" loading="lazy" decoding="async" width="400" height="200">
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Kantine am Gutshof</h3>
            <p class="service-card__text">Unser Mittagstisch in Werder (Havel): täglich wechselnde Gerichte, frisch gekocht, faire Preise. Kein Kantinen-Einheitsbrei.</p>
            <div class="service-card__checks">
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Mo–Fr 07:00–15:00 Uhr</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>3 Menüs – auch vegetarisch</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Direkt vor Ort in Werder (Havel)</div>
            </div>
            <a class="service-card__link" href="/kantine-am-gutshof/">Mehr erfahren<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></a>
          </div>
        </article>
        <article class="service-card fade-up" style="transition-delay:.2s">
          <div class="service-card__img img-wrap">
            <img src="/assets/images/catering-setup.jpg" alt="Catering Service BMV-Menüdienst für Firmen und Events" loading="lazy" decoding="async" width="400" height="200">
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Catering für Ihr Event</h3>
            <p class="service-card__text">Firmenlunch, Betriebsfeier oder Meeting – wir liefern und bauen auf. Frisch zubereitet, pünktlich und unkompliziert.</p>
            <div class="service-card__checks">
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Individuell geplant &amp; abgestimmt</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Lieferung, Aufbau &amp; Abbau</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Potsdam, Werder &amp; Umland</div>
            </div>
            <a class="service-card__link" href="/catering/">Mehr erfahren<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- Feature Rows -->
  <section class="section" aria-labelledby="leistungen-heading">
    <div class="container">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">Wie es funktioniert</div>
        <h2 class="section-title" id="leistungen-heading">Gutes Essen – ohne Aufwand</h2>
        <p class="section-sub">Sie bestellen, wir kochen und liefern. So einfach ist das.</p>
      </div>
      <div class="alt-block fade-up">
        <div class="alt-block__text">
          <div class="alt-block__eyebrow">Kantinenbetrieb</div>
          <h3 class="alt-block__title">Frisch gekocht – jeden Tag neu</h3>
          <p>Kein Tiefkühl, kein Aufwärmen. Unsere Köche bereiten täglich frische Gerichte vor – in unserer Küche oder direkt bei Ihnen vor Ort.</p>
          <div class="alt-block__checks">
            <div class="alt-block__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Zutaten täglich frisch angeliefert</div>
            <div class="alt-block__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Erfahrene Köche – seit über 15 Jahren</div>
            <div class="alt-block__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Menüplan auf Ihre Wünsche abgestimmt</div>
          </div>
          <a class="btn btn--secondary" href="/speiseplan/">Aktuellen Speiseplan ansehen</a>
        </div>
        <div class="alt-block__visual img-wrap">
          <img src="/assets/images/speiseplan-kueche.jpg" alt="Koch bereitet frisches Essen bei BMV-Menüdienst zu" loading="lazy" decoding="async" width="560" height="360">
        </div>
      </div>
      <div class="alt-block alt-block--reverse fade-up">
        <div class="alt-block__text">
          <div class="alt-block__eyebrow">Speiseplan &amp; Bestellung</div>
          <h3 class="alt-block__title">Immer wissen, was es gibt</h3>
          <p>Unser Speiseplan steht bis zu 4 Wochen im Voraus online. Sie sehen Gerichte, Allergene und Preise – und können direkt bestellen.</p>
          <div class="alt-block__checks">
            <div class="alt-block__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Speiseplan 4 Wochen voraus einsehbar</div>
            <div class="alt-block__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Bestellung per Telefon oder online</div>
            <div class="alt-block__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Allergene &amp; Preise klar ausgewiesen</div>
          </div>
          <a class="btn btn--outline" href="/speiseplan/">Speiseplan ansehen</a>
        </div>
        <div class="alt-block__visual img-wrap">
          <img src="/assets/images/menue-auswahl.jpg" alt="Menüauswahl BMV-Menüdienst auf Laptop und Smartphone" loading="lazy" decoding="async" width="560" height="360">
        </div>
      </div>
    </div>
  </section>

  <!-- Menu Preview -->
  <section class="menu-preview" aria-labelledby="speiseplan-heading">
    <div class="container">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">KW <?= $currentKW ?>/<?= $currentYear ?></div>
        <h2 class="section-title" id="speiseplan-heading">Was gibt's diese Woche?</h2>
        <p class="section-sub">Täglich wechselnde Gerichte – frisch, regional, ohne Einheitsbrei</p>
      </div>
      <div class="menu-scroll fade-up">
        <?php
        $shown = 0;
        for ($i = 0; $i < 7 && $shown < 5; $i++):
            $dayTitle = null;
            if ($preview && isset($preview[$i])) {
                if (is_array($preview[$i]) && !isset($preview[$i]['menus'])) {
                    foreach ($preview[$i] as $catKey => $entry) {
                        if (!empty($entry['name']) && in_array($catKey, ['vollkost','leichte_kost','premium'])) {
                            $dayTitle = $entry['name']; break;
                        }
                    }
                }
                if (!$dayTitle && isset($preview[$i]['menus'][0]['title'])) {
                    $dayTitle = $preview[$i]['menus'][0]['title'];
                }
            }
            $shown++;
        ?>
        <div class="menu-day-card fade-up"
             style="transition-delay:<?= ($shown - 1) * .1 ?>s"
             onclick="location.href='/speiseplan/'"
             role="button" tabindex="0"
             aria-label="Speiseplan <?= htmlspecialchars($dayNames[$i]) ?>">
          <div class="menu-day-card__header">
            <span class="menu-day-card__name"><?= htmlspecialchars($dayNames[$i]) ?></span>
            <span class="menu-day-card__arrow"><svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z" fill="currentColor"/></svg></span>
          </div>
          <?php if ($dayTitle): ?>
          <div class="menu-day-card__img img-wrap">
            <img src="/assets/images/placeholder-meal.jpg" class="pexels-img" data-query="<?= htmlspecialchars(dishSearchQuery($dayTitle)) ?>" alt="<?= htmlspecialchars($dayTitle) ?>" loading="lazy" decoding="async" width="260" height="140">
          </div>
          <div class="menu-day-card__body">
            <div class="menu-day-card__dish"><?= htmlspecialchars($dayTitle) ?></div>
            <div class="menu-day-card__more">
              <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z" fill="currentColor"/></svg>
              Mehr ansehen
            </div>
          </div>
          <?php else: ?>
          <div class="menu-day-card__img-placeholder">
            <svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor" aria-hidden="true"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/></svg>
          </div>
          <div class="menu-day-card__body">
            <div class="menu-day-card__dish" style="color:rgba(255,255,255,.4);font-style:italic">Noch kein Menü eingetragen</div>
          </div>
          <?php endif; ?>
        </div>
        <?php endfor; ?>
      </div>
      <div class="menu-preview__cta fade-up">
        <a class="btn btn--primary" href="/speiseplan/">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
          Alle Gerichte ansehen
        </a>
      </div>
    </div>
  </section>

  <!-- Process -->
  <section class="section section--bg" aria-labelledby="prozess-heading">
    <div class="container">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">In 4 Schritten starten</div>
        <h2 class="section-title" id="prozess-heading">Heute anrufen – morgen geliefert</h2>
      </div>
      <div class="process-steps fade-up">
        <div class="process-step">
          <div class="process-step__num">1</div>
          <h3 class="process-step__title">Anruf oder Anfrage</h3>
          <p class="process-step__text">Kurzes Gespräch – wir klären Liefergebiet, Wünsche und Starttermin</p>
        </div>
        <div class="process-step">
          <div class="process-step__num">2</div>
          <h3 class="process-step__title">Menü aussuchen</h3>
          <p class="process-step__text">Sie wählen aus unserem Speiseplan – täglich oder für die ganze Woche</p>
        </div>
        <div class="process-step">
          <div class="process-step__num">3</div>
          <h3 class="process-step__title">Wir kochen</h3>
          <p class="process-step__text">Frisch zubereitet – in unserer Küche, jeden Morgen neu</p>
        </div>
        <div class="process-step">
          <div class="process-step__num">4</div>
          <h3 class="process-step__title">Lieferung</h3>
          <p class="process-step__text">Pünktlich an Ihre Tür – mit persönlichem Ansprechpartner</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Final CTA -->
  <section class="final-cta" aria-labelledby="cta-heading">
    <div class="container">
      <h2 class="final-cta__title" id="cta-heading">Noch heute anmelden – morgen kommt das Essen</h2>
      <p class="final-cta__sub">Kostenlose Beratung, keine Mindestlaufzeit. Einfach anrufen.</p>
      <div class="final-cta__actions">
        <a class="btn btn--primary" href="tel:<?= BMV_TEL ?>">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
          <?= BMV_TEL_DISPLAY ?>
        </a>
        <a class="btn btn--ghost" href="/kontakt/">Online anfragen</a>
      </div>
    </div>
  </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="/assets/js/index.js" defer></script>
<script>
(function(){
  // FIX 12: Burger-Menü Toggle – war im Original nicht implementiert,
  // obwohl .nav-toggle im HTML vorhanden ist. Ohne diesen Code
  // ist die mobile Navigation nicht bedienbar.
  var toggle = document.getElementById('nav-toggle');
  var nav    = document.getElementById('site-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function() {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.setAttribute('aria-label', open ? 'Menü schließen' : 'Menü öffnen');
    });
    // Menü schließen wenn außerhalb geklickt wird
    document.addEventListener('click', function(e) {
      if (!nav.contains(e.target) && !toggle.contains(e.target)) {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  function initImgWrap(el){
    var img = el.querySelector('img');
    if (!img) return;
    if (img.complete && img.naturalWidth){
      el.classList.add('img-loaded');
    } else {
      img.addEventListener('load', function(){ el.classList.add('img-loaded'); });
      img.addEventListener('error', function(){ el.classList.add('img-loaded'); });
    }
  }
  document.querySelectorAll('.img-wrap').forEach(initImgWrap);

  var pexelsCache = Object.create(null);
  function fetchPexelsImg(query) {
    if (query in pexelsCache) return Promise.resolve(pexelsCache[query]);
    return fetch('/api/pexels_image.php?q=' + encodeURIComponent(query))
      .then(function(r){ return r.ok ? r.json() : Promise.reject(); })
      .then(function(d){ return (pexelsCache[query] = d.url || null); })
      .catch(function(){ return (pexelsCache[query] = null); });
  }

  function loadMenuCardImages() {
    var imgs = [].slice.call(document.querySelectorAll('img.pexels-img[data-query]'));
    function loadBatch(batch) {
      return Promise.all(batch.map(function(img) {
        return fetchPexelsImg(img.dataset.query).then(function(url) {
          if (!url) return;
          var wrap = img.closest('.img-wrap');
          img.onload = function() { if (wrap) wrap.classList.add('img-loaded'); };
          img.src = url;
        });
      }));
    }
    var i = 0;
    function next() {
      if (i >= imgs.length) return;
      loadBatch(imgs.slice(i, i + 6)).then(function(){ i += 6; next(); });
    }
    next();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadMenuCardImages);
  } else {
    loadMenuCardImages();
  }
})();
</script>
<script>
(function(){
  var els = document.querySelectorAll('.fade-up');
  if (!els.length) return;
  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if (e.isIntersecting){ e.target.classList.add('is-visible'); io.unobserve(e.target); }
    });
  },{threshold:0.12});
  els.forEach(function(el){ io.observe(el); });
})();
</script>
</body>
</html>
