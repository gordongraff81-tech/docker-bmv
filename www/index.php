<?php
/**
 * BMV-Menüdienst Startseite v8.0 – Premium Redesign
 */
require_once __DIR__ . '/slug-router.php';

$page_title       = 'Essen auf Rädern in Potsdam & Werder – täglich frisch geliefert | BMV-Menüdienst';
$meta_description = 'Täglich warmes Mittagessen direkt an die Haustür – Potsdam, Werder (Havel) und Umland. ☎ +49 3327 5745066';
$active_nav       = 'home';
$canonical        = 'https://www.bmv-kantinen.de/';

require_once __DIR__ . '/includes/helpers.php';

if (!defined('BMV_NAME')) {
    define('BMV_NAME',  'BMV-Menüdienst');
    define('BMV_TEL',   '+4933275745066');
    define('BMV_TEL_DISPLAY', '+49 3327 5745066');
    define('BMV_EMAIL', 'info@bmv-kantinen.de');
    define('BMV_URL',   'https://www.bmv-kantinen.de');
}

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($meta_description) ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= htmlspecialchars($canonical) ?>">
  <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($meta_description) ?>">
  <meta property="og:image" content="<?= BMV_URL ?>/assets/images/og-image.jpg">
  <meta property="og:locale" content="de_DE">
  <link rel="icon" href="/assets/images/Favicon.png" type="image/png">
  <link rel="apple-touch-icon" href="/assets/images/Favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="/assets/css/bmv-premium.css">
  <link rel="stylesheet" href="/assets/css/bmv-overrides.css">
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":["LocalBusiness","FoodEstablishment"],"@id":"<?= BMV_URL ?>/#organization","name":"<?= BMV_NAME ?>","url":"<?= BMV_URL ?>","telephone":"<?= BMV_TEL ?>","email":"<?= BMV_EMAIL ?>","address":{"@type":"PostalAddress","streetAddress":"Am Gutshof 6","addressLocality":"Werder (Havel)","postalCode":"14542","addressRegion":"Brandenburg","addressCountry":"DE"},"geo":{"@type":"GeoCoordinates","latitude":52.3869,"longitude":12.9344},"openingHours":"Mo-Fr 07:00-15:00","priceRange":"€€","logo":"<?= BMV_URL ?>/assets/images/BMV_Logo_n.png"}
  </script>
</head>
<body>
<a class="skip-link" href="#main-content">Zum Hauptinhalt springen</a>

<header class="site-header" id="site-header" role="banner">
  <div class="container site-header__inner">
    <a href="/" class="site-logo" aria-label="<?= BMV_NAME ?> – Startseite">
      <img src="/assets/images/BMV_Logo_n.png" alt="BMV Menüdienst Logo" width="110" height="33" loading="eager">
      <span class="site-logo__text">
        <span class="site-logo__wordmark">BMV Menüdienst</span>
        <span class="site-logo__tagline">Frisch. Verlässlich. Regional.</span>
      </span>
    </a>
    <nav class="site-nav" id="site-nav" aria-label="Hauptnavigation">
      <a href="/" class="site-nav__link <?= $active_nav === 'home' ? 'active' : '' ?>">Startseite</a>
      <a href="/speiseplan/" class="site-nav__link <?= $active_nav === 'speiseplan' ? 'active' : '' ?>">Speiseplan</a>
      <div class="site-nav__dropdown">
        <a href="/essen-auf-raedern/" class="site-nav__link site-nav__dropdown-toggle <?= $active_nav === 'ears' ? 'active' : '' ?>" aria-haspopup="true" aria-expanded="false">
          Essen auf Rädern
          <svg class="site-nav__chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 4l4 4 4-4"/></svg>
        </a>
        <ul class="site-nav__dropdown-menu" role="list">
          <li><a href="/essen-auf-raedern/potsdam/" class="site-nav__dropdown-link">Potsdam</a></li>
          <li><a href="/essen-auf-raedern/werder-havel/" class="site-nav__dropdown-link">Werder (Havel)</a></li>
          <li><a href="/essen-auf-raedern/umland/" class="site-nav__dropdown-link">Umland</a></li>
        </ul>
      </div>
      <a href="/kantine-am-gutshof/" class="site-nav__link <?= $active_nav === 'kantine' ? 'active' : '' ?>">Kantine am Gutshof</a>
      <div class="site-nav__dropdown">
        <a href="/catering/" class="site-nav__link site-nav__dropdown-toggle <?= $active_nav === 'catering' ? 'active' : '' ?>" aria-haspopup="true" aria-expanded="false">
          Catering
          <svg class="site-nav__chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 4l4 4 4-4"/></svg>
        </a>
        <ul class="site-nav__dropdown-menu" role="list">
          <li><a href="/catering/potsdam/" class="site-nav__dropdown-link">Potsdam</a></li>
          <li><a href="/catering/werder-havel/" class="site-nav__dropdown-link">Werder (Havel)</a></li>
        </ul>
      </div>
      <a href="/ueber-uns/" class="site-nav__link <?= $active_nav === 'ueber-uns' ? 'active' : '' ?>">Über uns</a>
      <a href="/kontakt/" class="site-nav__link <?= $active_nav === 'kontakt' ? 'active' : '' ?>">Kontakt</a>
      <div class="nav-cta">
        <a href="/kontakt/" class="btn btn--primary btn--sm">Jetzt anfragen</a>
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

<!-- HERO -->
<section class="hero" aria-labelledby="hero-heading">
  <div class="hero__bg">
    <img src="/assets/images/hero-bg.jpg" alt="Frisches warmes Mittagessen – täglich geliefert in Potsdam und Werder" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
    <div class="hero__overlay"></div>
  </div>
  <div class="hero__grain" aria-hidden="true"></div>
  <div class="container">
    <div class="hero__content">
      <div class="hero__badge">
        <span class="hero__badge-dot" aria-hidden="true"></span>
        Potsdam &amp; Werder (Havel) &bull; Täglich geliefert
      </div>
      <h1 class="hero__title" id="hero-heading">
        Warmes Mittagessen –<br>
        <span class="hero__title-accent">täglich an Ihre Tür.</span>
      </h1>
      <p class="hero__sub">
        Seit über 15 Jahren liefern wir frisch zubereitetes Mittagessen in Potsdam und Werder (Havel).
        Für Senioren, Familien und Betriebe – verlässlich, pünktlich und ohne Tiefkühlware.
      </p>
      <div class="hero__actions">
        <a class="btn btn--primary btn--lg" href="/speiseplan/">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
          Speiseplan ansehen
        </a>
        <a class="btn btn--ghost btn--lg" href="/kontakt/">Kostenlos anfragen</a>
      </div>
      <div class="hero__trust">
        <div class="hero__trust-item">
          <span class="hero__trust-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="12" height="12" fill="#FF6B00"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>
          Täglich frisch zubereitet
        </div>
        <div class="hero__trust-divider" aria-hidden="true"></div>
        <div class="hero__trust-item">
          <span class="hero__trust-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="12" height="12" fill="#FF6B00"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>
          Mo bis So geliefert
        </div>
        <div class="hero__trust-divider" aria-hidden="true"></div>
        <div class="hero__trust-item">
          <span class="hero__trust-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="12" height="12" fill="#FF6B00"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>
          Keine Mindestlaufzeit
        </div>
        <div class="hero__trust-divider" aria-hidden="true"></div>
        <div class="hero__trust-item">
          <span class="hero__trust-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="12" height="12" fill="#FF6B00"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>
          Pflegekassen-Abrechnung
        </div>
      </div>
    </div>
  </div>
  <div class="hero__scroll" aria-hidden="true">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>
  </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar" aria-label="Kennzahlen">
  <div class="container">
    <div class="stats-bar__inner">
      <div class="stat-item fade-in">
        <div class="stat-item__number">500+</div>
        <div class="stat-item__label">Kunden täglich</div>
        <div class="stat-item__sub">Potsdam, Werder &amp; Umland</div>
      </div>
      <div class="stat-item fade-in" style="transition-delay:.08s">
        <div class="stat-item__number">15+</div>
        <div class="stat-item__label">Jahre Erfahrung</div>
        <div class="stat-item__sub">Seit 2009 vor Ort</div>
      </div>
      <div class="stat-item fade-in" style="transition-delay:.16s">
        <div class="stat-item__number">100%</div>
        <div class="stat-item__label">Frisch zubereitet</div>
        <div class="stat-item__sub">Keine Tiefkühlware</div>
      </div>
      <div class="stat-item fade-in" style="transition-delay:.24s">
        <div class="stat-item__number">7</div>
        <div class="stat-item__label">Tage die Woche</div>
        <div class="stat-item__sub">Auch Sa &amp; So</div>
      </div>
    </div>
  </div>
</div>

<!-- SERVICES -->
<section class="section section--bg" aria-labelledby="services-heading">
  <div class="container">
    <div class="section-header fade-up" style="text-align:center;display:flex;flex-direction:column;align-items:center;">
      <div class="eyebrow">Was wir anbieten</div>
      <h2 class="section-title" id="services-heading">Drei Wege zu gutem Essen</h2>
      <p class="section-sub" style="text-align:center;">Ob Lieferung nach Hause, Mittagstisch oder Event – wir kochen täglich frisch und liefern verlässlich.</p>
    </div>
    <div class="grid-3">
      <article class="service-card fade-up">
        <div class="service-card__img img-wrap">
          <img src="/assets/images/essen-auf-raedern-lieferung.jpg" alt="Essen auf Rädern – Lieferung in Potsdam und Werder" loading="lazy" decoding="async" width="480" height="220">
          <div class="service-card__badge">Beliebt</div>
        </div>
        <div class="service-card__body">
          <div class="service-card__eyebrow">Essen auf Rädern</div>
          <h3 class="service-card__title">Warmes Mittagessen an Ihre Haustür</h3>
          <p class="service-card__text">Pünktlich geliefert – auch am Wochenende. Ideal für Senioren, Pflegebedürftige und alle, die sich täglich gut versorgen möchten.</p>
          <div class="service-card__checks">
            <div class="service-card__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Lieferung auch Samstag &amp; Sonntag</div>
            <div class="service-card__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Direkte Pflegekassen-Abrechnung</div>
            <div class="service-card__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>4 Menüs täglich – inkl. Schonkost</div>
          </div>
          <a class="service-card__link" href="/essen-auf-raedern/">Jetzt informieren <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></a>
        </div>
      </article>
      <article class="service-card fade-up" style="transition-delay:.1s">
        <div class="service-card__img img-wrap">
          <img src="/assets/images/kantine-gutshof.jpg" alt="Kantine am Gutshof Werder – Mittagstisch" loading="lazy" decoding="async" width="480" height="220">
        </div>
        <div class="service-card__body">
          <div class="service-card__eyebrow">Kantine am Gutshof</div>
          <h3 class="service-card__title">Täglich frischer Mittagstisch in Werder</h3>
          <p class="service-card__text">Wechselnde Gerichte, frisch gekocht, faire Preise. Kein Kantinen-Einheitsbrei – echte Küche direkt in Werder (Havel).</p>
          <div class="service-card__checks">
            <div class="service-card__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Mo–Fr 07:00–15:00 Uhr</div>
            <div class="service-card__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>3 Menüs – auch vegetarisch</div>
            <div class="service-card__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Am Gutshof 6, Werder (Havel)</div>
          </div>
          <a class="service-card__link" href="/kantine-am-gutshof/">Mehr erfahren <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></a>
        </div>
      </article>
      <article class="service-card fade-up" style="transition-delay:.2s">
        <div class="service-card__img img-wrap">
          <img src="/assets/images/catering-setup.jpg" alt="Catering für Firmen und Events in Potsdam und Umland" loading="lazy" decoding="async" width="480" height="220">
        </div>
        <div class="service-card__body">
          <div class="service-card__eyebrow">Catering</div>
          <h3 class="service-card__title">Ihr Event – frisch und unkompliziert</h3>
          <p class="service-card__text">Firmenlunch, Betriebsfeier oder Meeting – wir liefern, bauen auf und räumen ab. Frisch zubereitet, pünktlich und individuell geplant.</p>
          <div class="service-card__checks">
            <div class="service-card__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Individuell geplant &amp; abgestimmt</div>
            <div class="service-card__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Lieferung, Aufbau &amp; Abbau inklusive</div>
            <div class="service-card__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Potsdam, Werder &amp; Umland</div>
          </div>
          <a class="service-card__link" href="/catering/">Angebot anfragen <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></a>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- FEATURE BLOCKS -->
<section class="section section--light" aria-labelledby="leistungen-heading">
  <div class="container">
    <div class="section-header fade-up" style="text-align:center;display:flex;flex-direction:column;align-items:center;">
      <div class="eyebrow">Wie es funktioniert</div>
      <h2 class="section-title" id="leistungen-heading">Gutes Essen – ohne Aufwand</h2>
      <p class="section-sub" style="text-align:center;">Sie wählen, wir kochen und liefern. So einfach ist das.</p>
    </div>
    <div class="alt-block fade-up">
      <div class="alt-block__text">
        <div class="eyebrow">Frische Küche</div>
        <h3 class="alt-block__title">Täglich neu gekocht – kein Aufwärmen, kein Tiefkühl</h3>
        <p>Unsere Köche bereiten täglich frische Gerichte vor – in unserer Küche am Gutshof 6 in Werder. Regionale Zutaten, bewährte Rezepte, echte Handwerksküche.</p>
        <div class="alt-block__checks">
          <div class="alt-block__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Zutaten täglich frisch angeliefert</div>
          <div class="alt-block__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Erfahrene Köche – seit über 15 Jahren</div>
          <div class="alt-block__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Geprüfte Hygienestandards (HACCP)</div>
        </div>
        <a class="btn btn--navy" href="/speiseplan/">Aktuellen Speiseplan ansehen</a>
      </div>
      <div class="alt-block__visual img-wrap">
        <img src="/assets/images/speiseplan-kueche.jpg" alt="Koch bereitet frisches Essen vor – BMV-Menüdienst Küche" loading="lazy" decoding="async" width="600" height="450">
      </div>
    </div>
    <div class="alt-block alt-block--reverse fade-up">
      <div class="alt-block__text">
        <div class="eyebrow">Speiseplan &amp; Bestellung</div>
        <h3 class="alt-block__title">Woche für Woche – immer wissen, was es gibt</h3>
        <p>Unser Speiseplan steht bis zu 4 Wochen im Voraus online. Gerichte, Allergene und Preise auf einen Blick – Bestellung per Telefon oder online, ganz wie Sie möchten.</p>
        <div class="alt-block__checks">
          <div class="alt-block__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>4 Wochen voraus einsehbar</div>
          <div class="alt-block__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Bestellung per Telefon oder online</div>
          <div class="alt-block__check"><span class="check-icon"><svg viewBox="0 0 24 24" width="10" height="10" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg></span>Allergene &amp; Preise klar ausgewiesen</div>
        </div>
        <a class="btn btn--outline" href="/speiseplan/">Speiseplan ansehen</a>
      </div>
      <div class="alt-block__visual img-wrap">
        <img src="/assets/images/menue-auswahl.jpg" alt="Menüauswahl BMV-Menüdienst – Speiseplan online" loading="lazy" decoding="async" width="600" height="450">
      </div>
    </div>
  </div>
</section>

<!-- MENU PREVIEW -->
<section class="menu-preview section" aria-labelledby="speiseplan-heading">
  <div class="container">
    <div class="section-header fade-up" style="text-align:center;display:flex;flex-direction:column;align-items:center;">
      <div class="eyebrow" style="color:rgba(255,107,0,0.85);">KW <?= $currentKW ?>/<?= $currentYear ?></div>
      <h2 class="section-title section-title--light" id="speiseplan-heading">Was gibt es diese Woche?</h2>
      <p class="section-sub section-sub--light" style="text-align:center;">Täglich wechselnde Gerichte – frisch, regional, ohne Einheitsbrei</p>
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
      <div class="menu-day-card fade-up" style="transition-delay:<?= ($shown-1)*.08 ?>s"
           onclick="location.href='/speiseplan/'" role="button" tabindex="0"
           aria-label="Speiseplan <?= htmlspecialchars($dayNames[$i]) ?>">
        <div class="menu-day-card__header">
          <span class="menu-day-card__name"><?= htmlspecialchars($dayNames[$i]) ?></span>
          <svg viewBox="0 0 24 24" width="14" height="14" fill="rgba(255,255,255,0.4)" aria-hidden="true"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
        </div>
        <?php if ($dayTitle): ?>
        <div class="menu-day-card__img img-wrap">
          <img src="/assets/images/placeholder-meal.jpg" class="pexels-img"
               data-query="<?= htmlspecialchars(dishSearchQuery($dayTitle)) ?>"
               alt="<?= htmlspecialchars($dayTitle) ?>" loading="lazy" decoding="async" width="260" height="150">
        </div>
        <div class="menu-day-card__body">
          <div class="menu-day-card__dish"><?= htmlspecialchars($dayTitle) ?></div>
          <div class="menu-day-card__more">Mehr ansehen <svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></div>
        </div>
        <?php else: ?>
        <div class="menu-day-card__img-placeholder">
          <svg viewBox="0 0 24 24" width="32" height="32" fill="rgba(255,255,255,0.15)" aria-hidden="true"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/></svg>
        </div>
        <div class="menu-day-card__body">
          <div class="menu-day-card__dish" style="color:rgba(255,255,255,.3);font-style:italic;font-size:.82rem;">Noch kein Menü eingetragen</div>
        </div>
        <?php endif; ?>
      </div>
      <?php endfor; ?>
    </div>
    <div class="menu-preview__cta fade-up">
      <a class="btn btn--primary btn--lg" href="/speiseplan/">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
        Alle Gerichte &amp; Preise ansehen
      </a>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section section--bg" aria-labelledby="testimonials-heading">
  <div class="container">
    <div class="section-header fade-up" style="text-align:center;display:flex;flex-direction:column;align-items:center;">
      <div class="eyebrow">Kundenstimmen</div>
      <h2 class="section-title" id="testimonials-heading">Was unsere Kunden sagen</h2>
      <p class="section-sub" style="text-align:center;">Vertrauen ist unser wichtigstes Gut – nach über 15 Jahren.</p>
    </div>
    <div class="testimonial-grid">
      <div class="testimonial-card fade-up">
        <div class="testimonial-card__google" aria-label="Google Bewertung">
          <svg viewBox="0 0 24 24" width="12" height="12" aria-hidden="true"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          Google
        </div>
        <div class="testimonial-card__quote-mark" aria-hidden="true">"</div>
        <div class="testimonial-card__stars" aria-label="5 von 5 Sternen">
          <?php for ($s = 0; $s < 5; $s++): ?>
          <svg class="testimonial-card__star" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
          <?php endfor; ?>
        </div>
        <p class="testimonial-card__text">Seit drei Jahren lasse ich mir das Essen liefern – immer pünktlich, immer frisch und die Fahrer kennen mich mit Namen. Das ist mehr als ein Lieferdienst, das ist echter Service.</p>
        <div class="testimonial-card__author">
          <div class="testimonial-card__avatar" aria-hidden="true">M</div>
          <div>
            <div class="testimonial-card__name">Margarete B.</div>
            <div class="testimonial-card__meta">Kundin seit 2021, Potsdam</div>
          </div>
        </div>
      </div>
      <div class="testimonial-card fade-up" style="transition-delay:.1s">
        <div class="testimonial-card__google" aria-label="Google Bewertung">
          <svg viewBox="0 0 24 24" width="12" height="12" aria-hidden="true"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          Google
        </div>
        <div class="testimonial-card__quote-mark" aria-hidden="true">"</div>
        <div class="testimonial-card__stars" aria-label="5 von 5 Sternen">
          <?php for ($s = 0; $s < 5; $s++): ?>
          <svg class="testimonial-card__star" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
          <?php endfor; ?>
        </div>
        <p class="testimonial-card__text">Das Catering für unsere Betriebsveranstaltung war top organisiert. Essen hervorragend, Aufbau pünktlich, Team freundlich. Absolute Empfehlung für Firmen im Raum Potsdam.</p>
        <div class="testimonial-card__author">
          <div class="testimonial-card__avatar" aria-hidden="true">T</div>
          <div>
            <div class="testimonial-card__name">Thomas K.</div>
            <div class="testimonial-card__meta">Geschäftsführer, Potsdam-Babelsberg</div>
          </div>
        </div>
      </div>
      <div class="testimonial-card fade-up" style="transition-delay:.2s">
        <div class="testimonial-card__google" aria-label="Google Bewertung">
          <svg viewBox="0 0 24 24" width="12" height="12" aria-hidden="true"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          Google
        </div>
        <div class="testimonial-card__quote-mark" aria-hidden="true">"</div>
        <div class="testimonial-card__stars" aria-label="5 von 5 Sternen">
          <?php for ($s = 0; $s < 5; $s++): ?>
          <svg class="testimonial-card__star" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
          <?php endfor; ?>
        </div>
        <p class="testimonial-card__text">Meine Mutter bekommt täglich das Mittagessen geliefert. Die Fahrer sind immer freundlich und das Essen schmeckt ihr wirklich. Das gibt mir als Tochter ein sehr gutes Gefühl.</p>
        <div class="testimonial-card__author">
          <div class="testimonial-card__avatar" aria-hidden="true">S</div>
          <div>
            <div class="testimonial-card__name">Sandra W.</div>
            <div class="testimonial-card__meta">Tochter einer Kundin, Werder (Havel)</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PROCESS STEPS -->
<section class="section section--light" aria-labelledby="prozess-heading">
  <div class="container">
    <div class="section-header fade-up" style="text-align:center;display:flex;flex-direction:column;align-items:center;">
      <div class="eyebrow">In 4 Schritten starten</div>
      <h2 class="section-title" id="prozess-heading">Heute anrufen – morgen geliefert</h2>
      <p class="section-sub" style="text-align:center;">Kein Vertrag, keine Mindestlaufzeit, kein Aufwand.</p>
    </div>
    <div class="process-steps fade-up">
      <div class="process-connector" aria-hidden="true"></div>
      <div class="process-step"><div class="process-step__num" aria-hidden="true">1</div><h3 class="process-step__title">Anruf oder Anfrage</h3><p class="process-step__text">Kurzes Gespräch – wir klären Liefergebiet, Wünsche und Starttermin</p></div>
      <div class="process-step"><div class="process-step__num" aria-hidden="true">2</div><h3 class="process-step__title">Menü aussuchen</h3><p class="process-step__text">Sie wählen aus unserem Speiseplan – täglich oder für die ganze Woche</p></div>
      <div class="process-step"><div class="process-step__num" aria-hidden="true">3</div><h3 class="process-step__title">Wir kochen frisch</h3><p class="process-step__text">Jeden Morgen neu – in unserer Küche am Gutshof in Werder</p></div>
      <div class="process-step"><div class="process-step__num" aria-hidden="true">4</div><h3 class="process-step__title">Pünktliche Lieferung</h3><p class="process-step__text">An Ihre Tür – mit persönlichem Ansprechpartner, den Sie kennen</p></div>
    </div>
    <div class="fade-up" style="text-align:center;margin-top:3rem;">
      <a class="btn btn--primary btn--lg" href="/kontakt/">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
        Jetzt kostenlos anfragen
      </a>
    </div>
  </div>
</section>

<!-- TRUST BADGES -->
<section class="section section--bg" aria-labelledby="vertrauen-heading">
  <div class="container">
    <div class="section-header fade-up" style="text-align:center;display:flex;flex-direction:column;align-items:center;">
      <div class="eyebrow">Qualität &amp; Vertrauen</div>
      <h2 class="section-title" id="vertrauen-heading">Warum Kunden sich für uns entscheiden</h2>
    </div>
    <div class="trust-grid">
      <div class="trust-card fade-up">
        <div class="trust-card__icon"><svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg></div>
        <div class="trust-card__title">HACCP-zertifiziert</div>
        <div class="trust-card__text">Geprüfte Hygienestandards in allen Küchen- und Lieferprozessen</div>
      </div>
      <div class="trust-card fade-up" style="transition-delay:.08s">
        <div class="trust-card__icon"><svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg></div>
        <div class="trust-card__title">Regional verankert</div>
        <div class="trust-card__text">Seit über 15 Jahren bekannt und verlässlich in Potsdam und Werder</div>
      </div>
      <div class="trust-card fade-up" style="transition-delay:.16s">
        <div class="trust-card__icon"><svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg></div>
        <div class="trust-card__title">Pflegekassen-Abrechnung</div>
        <div class="trust-card__text">Direkte Abrechnung mit Ihrer Pflegekasse – kein bürokratischer Aufwand</div>
      </div>
      <div class="trust-card fade-up" style="transition-delay:.24s">
        <div class="trust-card__icon"><svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg></div>
        <div class="trust-card__title">Keine Mindestlaufzeit</div>
        <div class="trust-card__text">Flexibel bestellen – täglich, wöchentlich oder nach Bedarf. Kein Vertrag</div>
      </div>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section class="final-cta section" aria-labelledby="cta-heading">
  <div class="container">
    <div class="fade-up">
      <div class="eyebrow final-cta__eyebrow" style="justify-content:center;">Noch heute starten</div>
      <h2 class="final-cta__title" id="cta-heading">Heute anmelden –<br>morgen kommt das Essen.</h2>
      <p class="final-cta__sub">Kostenlose Beratung, keine Mindestlaufzeit, kein Papierkram. Einfach anrufen – wir kümmern uns um alles.</p>
      <div class="final-cta__actions">
        <a class="btn btn--primary btn--lg" href="tel:<?= BMV_TEL ?>">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
          <?= BMV_TEL_DISPLAY ?>
        </a>
        <a class="btn btn--ghost btn--lg" href="/kontakt/">Online anfragen</a>
      </div>
      <p class="final-cta__reassurance">Mo–Fr 07:00–15:00 Uhr &bull; Kein Vertrag &bull; Keine Mindestlaufzeit</p>
    </div>
  </div>
</section>

</main>

<!-- FOOTER -->
<footer class="site-footer" role="contentinfo">
  <div class="container site-footer__inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="/" class="footer-brand__logo-link" aria-label="<?= BMV_NAME ?> – Startseite">
          <img src="/assets/images/BMV_Logo_n.png" alt="BMV Menüdienst Logo" loading="lazy" decoding="async">
          <span>
            <span class="footer-brand__logo-name">BMV Menüdienst</span>
            <span class="footer-brand__logo-sub">Werder (Havel) · seit 2009</span>
          </span>
        </a>
        <p class="footer-brand__desc">Premium-Betriebsgastronomie mit regionaler Verlässlichkeit. Essen auf Rädern, Kantine am Gutshof und Catering aus einer Hand – täglich frisch zubereitet.</p>
        <div class="footer-brand__contact">
          <a href="tel:<?= BMV_TEL ?>"><svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg><?= BMV_TEL_DISPLAY ?></a>
          <a href="mailto:<?= BMV_EMAIL ?>"><svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg><?= BMV_EMAIL ?></a>
        </div>
      </div>
      <nav class="footer-col" aria-label="Service-Navigation">
        <div class="footer-col__title">Service</div>
        <div class="footer-col__links">
          <a href="/essen-auf-raedern/" class="footer-col__link">Essen auf Rädern</a>
          <a href="/kantine-am-gutshof/" class="footer-col__link">Kantine am Gutshof</a>
          <a href="/speiseplan/" class="footer-col__link">Speiseplan</a>
          <a href="/catering/" class="footer-col__link">Catering</a>
        </div>
      </nav>
      <nav class="footer-col" aria-label="Unternehmens-Navigation">
        <div class="footer-col__title">Unternehmen</div>
        <div class="footer-col__links">
          <a href="/ueber-uns/" class="footer-col__link">Über uns</a>
          <a href="/kontakt/" class="footer-col__link">Kontakt</a>
          <a href="/impressum/" class="footer-col__link">Impressum</a>
          <a href="/datenschutz/" class="footer-col__link">Datenschutz</a>
          <a href="/agb/" class="footer-col__link">AGB</a>
        </div>
      </nav>
      <address class="footer-col">
        <div class="footer-col__title">Standort</div>
        <p class="footer-col__text">BMV-Menüdienst<br>Am Gutshof 6<br>14542 Werder (Havel)<br>Brandenburg</p>
        <div class="footer-hours" aria-label="Öffnungszeiten">
          <svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.5 5v5.25l4.5 2.67-.75 1.23L11 13V7h1.5z"/></svg>
          Mo–Fr 07:00–15:00 Uhr
        </div>
      </address>
    </div>
    <div class="footer-bottom">
      <p class="footer-bottom__copy">&copy; <?= date('Y') ?> <?= BMV_NAME ?> &bull; Am Gutshof 6 &bull; 14542 Werder (Havel)</p>
      <nav class="footer-bottom__links" aria-label="Rechtliche Links">
        <a href="/impressum/">Impressum</a>
        <a href="/datenschutz/">Datenschutz</a>
        <a href="/agb/">AGB</a>
      </nav>
    </div>
  </div>
</footer>

<script src="/assets/js/main.js" defer></script>
<script>
// Zusätzliches Pexels-Lazy-Load für Startseite
(function(){
  var pc=Object.create(null);
  function fetchP(q){if(q in pc)return Promise.resolve(pc[q]);return fetch('/api/pexels_image.php?q='+encodeURIComponent(q)).then(function(r){return r.ok?r.json():Promise.reject();}).then(function(d){return(pc[q]=d.url||null);}).catch(function(){return(pc[q]=null);});}
  function loadMenuImgs(){Array.prototype.slice.call(document.querySelectorAll('img.pexels-img[data-query]')).forEach(function(img){fetchP(img.dataset.query).then(function(url){if(!url)return;var w=img.closest('.img-wrap');img.onload=function(){img.classList.add('loaded');if(w)w.classList.add('img-loaded');};img.src=url;});});}
  if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',loadMenuImgs);}else{loadMenuImgs();}
  document.querySelectorAll('.menu-day-card').forEach(function(c){c.addEventListener('keydown',function(e){if(e.key==='Enter'||e.key===' '){e.preventDefault();location.href='/speiseplan/';}});});
})();
</script>
</body>
</html>
