<?php
/**
 * includes/header.php
 * Shared document shell + premium navigation.
 */

if (!defined('BMV_NAME')) {
    define('BMV_NAME',  'BMV-Menüdienst');
    define('BMV_TEL',   '+4933275745066');
    define('BMV_TEL_DISPLAY', '+49 3327 5745066');
    define('BMV_HOURS_DISPLAY', 'Mo–Fr 07:00–15:00 Uhr');
    define('BMV_HOURS_SCHEMA_OPEN',  '07:00');
    define('BMV_HOURS_SCHEMA_CLOSE', '15:00');
    define('BMV_EMAIL', 'info@bmv-kantinen.de');
    define('BMV_URL',   'https://www.bmv-kantinen.de');
}

$page_title       ??= 'Essen auf Rädern Potsdam & Werder – ' . BMV_NAME;
$meta_description ??= BMV_NAME . ': Frisches Mittagessen täglich geliefert in Potsdam und Werder (Havel). Essen auf Rädern für Senioren, Familien und Unternehmen. ☎ ' . BMV_TEL_DISPLAY;
$active_nav       ??= 'home';
$canonical        ??= BMV_URL . '/';
$schema_extra     ??= '';
$meta_robots      ??= 'index, follow';

if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }
}

if (!function_exists('bmv_nav_class')) {
    function bmv_nav_class(string $key, string $active): string {
        return $key === $active ? 'site-nav__link active' : 'site-nav__link';
    }
}
?>
<!DOCTYPE html>
<html lang="de" prefix="og: https://ogp.me/ns#">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($meta_description) ?>">
  <meta name="robots" content="<?= htmlspecialchars($meta_robots) ?>">
  <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">

  <!-- Open Graph -->
  <meta property="og:type"         content="website">
  <meta property="og:url"          content="<?= htmlspecialchars($canonical) ?>">
  <meta property="og:title"        content="<?= htmlspecialchars($page_title) ?>">
  <meta property="og:description"  content="<?= htmlspecialchars($meta_description) ?>">
  <meta property="og:image"        content="<?= BMV_URL ?>/assets/images/og-image.jpg">
  <meta property="og:image:width"  content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:locale"       content="de_DE">
  <meta property="og:site_name"    content="<?= BMV_NAME ?>">

  <!-- Twitter Card -->
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="<?= htmlspecialchars($page_title) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($meta_description) ?>">
  <meta name="twitter:image"       content="<?= BMV_URL ?>/assets/images/og-image.jpg">

  <!-- Favicon -->
  <link rel="icon"             href="/assets/images/Favicon.png" type="image/png">
  <link rel="apple-touch-icon" href="/assets/images/Favicon.png">

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=inter:400,500,700" rel="stylesheet">

  <link rel="stylesheet" href="/assets/css/tokens.css">
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/design-system.css">
  <link rel="stylesheet" href="/assets/css/hero-unified.css">
  <link rel="stylesheet" href="/assets/css/bmv-ci.css">

  <!-- Schema.org: Globales LocalBusiness -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": ["LocalBusiness", "FoodEstablishment"],
    "@id": "<?= BMV_URL ?>/#organization",
    "name": "<?= BMV_NAME ?>",
    "url": "<?= BMV_URL ?>",
    "telephone": "<?= BMV_TEL ?>",
    "email": "<?= BMV_EMAIL ?>",
    "description": "Essen auf Rädern, Catering und Kantine am Gutshof in Potsdam und Werder (Havel).",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Am Gutshof 6",
      "addressLocality": "Werder (Havel)",
      "postalCode": "14542",
      "addressRegion": "Brandenburg",
      "addressCountry": "DE"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": 52.3869,
      "longitude": 12.9344
    },
    "openingHours": "Mo-Fr <?= BMV_HOURS_SCHEMA_OPEN ?>-<?= BMV_HOURS_SCHEMA_CLOSE ?>",
    "openingHoursSpecification": [{
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
      "opens": "<?= BMV_HOURS_SCHEMA_OPEN ?>",
      "closes": "<?= BMV_HOURS_SCHEMA_CLOSE ?>"
    }],
    "servesCuisine": "German",
    "priceRange": "€€",
    "logo": "<?= BMV_URL ?>/assets/images/BMV_Logo_n.png",
    "image": "<?= BMV_URL ?>/assets/images/og-image.jpg",
    "areaServed": [
      {"@type": "City", "name": "Potsdam"},
      {"@type": "City", "name": "Werder (Havel)"},
      {"@type": "AdministrativeArea", "name": "Potsdam-Mittelmark"}
    ]
  }
  </script>
  <?php if (!empty($schema_extra)): ?>
  <?= $schema_extra ?>
  <?php endif; ?>
</head>
<body>

<a class="skip-link" href="#main-content">Zum Hauptinhalt springen</a>

<header class="site-header" id="site-header" role="banner">
  <div class="container site-header__inner">

    <a href="/" class="site-logo" aria-label="<?= BMV_NAME ?> – Startseite">
      <span class="site-logo__mark" aria-hidden="true">BMV</span>
      <span class="site-logo__text">
        <strong>BMV Menüdienst</strong>
        <span>Frisch kochen. Verlässlich liefern.</span>
      </span>
    </a>

    <nav class="site-nav" id="site-nav" aria-label="Hauptnavigation">
      <a href="/"                    class="<?= bmv_nav_class('home',      $active_nav) ?>">Startseite</a>
      <a href="/speiseplan/"         class="<?= bmv_nav_class('speiseplan',$active_nav) ?>">Speiseplan</a>

      <!-- Essen auf Rädern mit Dropdown -->
      <div class="site-nav__dropdown">
        <a href="/essen-auf-raedern/" class="<?= bmv_nav_class('ears', $active_nav) ?> site-nav__dropdown-toggle" aria-haspopup="true" aria-expanded="false">
          Essen auf Rädern
          <svg class="site-nav__chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 4l4 4 4-4"/></svg>
        </a>
        <ul class="site-nav__dropdown-menu" role="list">
          <li><a href="/essen-auf-raedern/potsdam/"      class="site-nav__dropdown-link">Potsdam</a></li>
          <li><a href="/essen-auf-raedern/werder-havel/" class="site-nav__dropdown-link">Werder (Havel)</a></li>
          <li><a href="/essen-auf-raedern/umland/"       class="site-nav__dropdown-link">Umland</a></li>
        </ul>
      </div>

      <a href="/kantine-am-gutshof/" class="<?= bmv_nav_class('kantine',   $active_nav) ?>">Kantine am Gutshof</a>

      <!-- Catering mit Dropdown -->
      <div class="site-nav__dropdown">
        <a href="/catering/" class="<?= bmv_nav_class('catering', $active_nav) ?> site-nav__dropdown-toggle" aria-haspopup="true" aria-expanded="false">
          Catering
          <svg class="site-nav__chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 4l4 4 4-4"/></svg>
        </a>
        <ul class="site-nav__dropdown-menu" role="list">
          <li><a href="/catering/potsdam/"      class="site-nav__dropdown-link">Potsdam</a></li>
          <li><a href="/catering/werder-havel/" class="site-nav__dropdown-link">Werder (Havel)</a></li>
        </ul>
      </div>

      <a href="/ueber-uns/"          class="<?= bmv_nav_class('ueber-uns', $active_nav) ?>">Über uns</a>
      <a href="/kontakt/"            class="<?= bmv_nav_class('kontakt',   $active_nav) ?>">Kontakt</a>

      <div class="nav-cta">
        <a href="/kontakt/" class="btn btn--primary btn--sm">Beratung anfragen</a>
      </div>
    </nav>

    <button class="nav-toggle" id="nav-toggle"
            aria-controls="site-nav"
            aria-expanded="false"
            aria-label="Menü öffnen">
      <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
           stroke="currentColor" stroke-width="2" stroke-linecap="round"
           aria-hidden="true">
        <line x1="2" y1="6"  x2="20" y2="6"/>
        <line x1="2" y1="11" x2="20" y2="11"/>
        <line x1="2" y1="16" x2="20" y2="16"/>
      </svg>
    </button>

  </div>
</header>
