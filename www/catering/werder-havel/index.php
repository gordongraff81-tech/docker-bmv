<?php
$page_title       = 'Catering Werder (Havel) – von unserem Standort';
$meta_description = 'Catering von BMV-Menüdienst in Werder (Havel): Frisches Essen für Ihre Veranstaltung, schnell und zuverlässig.';
$active_nav       = 'catering';
$canonical        = 'https://www.bmv-kantinen.de/catering/werder-havel/';

if (!defined('BMV_NAME')) {
    define('BMV_NAME',  'BMV-Menüdienst');
    define('BMV_TEL',   '+4933275745066');
    define('BMV_TEL_DISPLAY', '+49 3327 5745066');
    define('BMV_EMAIL', 'info@bmv-kantinen.de');
    define('BMV_URL',   'https://www.bmv-kantinen.de');
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($meta_description) ?>">
  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= htmlspecialchars($canonical) ?>">
  <meta property="og:title"       content="Catering Werder (Havel) – von unserem Standort">
  <meta property="og:description" content="Catering von BMV-MenÜdienst in Werder (Havel). Frisches Essen fÜr Ihre Veranstaltung, schnell und zuverlässig.">
  <meta property="og:image"       content="https://www.bmv-kantinen.de/assets/images/catering-werder.jpg">
  <meta property="og:locale"      content="de_DE">
  <meta property="og:site_name"   content="BMV MenÜdienst">
  <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Startseite","item":"https://www.bmv-kantinen.de/"},{"@type":"ListItem","position":2,"name":"Catering","item":"https://www.bmv-kantinen.de/catering/"},{"@type":"ListItem","position":3,"name":"Werder (Havel)","item":"https://www.bmv-kantinen.de/catering/werder-havel/"}]}</script>
  <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
  <link rel="icon" href="/assets/images/Favicon.png" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/bmv-premium.css">
  <link rel="stylesheet" href="/assets/css/bmv-overrides.css">
  <style>
    .skip-link { position: absolute; top: -9999px; left: -9999px; z-index: 999; }
    .skip-link:focus { top: 0; left: 0; }
    .fade-up { opacity: 0; transform: translateY(24px); transition: opacity 0.55s ease, transform 0.55s ease; }
    .fade-up.is-visible { opacity: 1; transform: translateY(0); }
  </style>
</head>
<body>

<a class="skip-link" href="#main-content">Zum Hauptinhalt springen</a>

<header class="site-header" id="site-header" role="banner">
  <div class="container">
    <a href="/" class="site-logo" aria-label="<?= BMV_NAME ?> – Startseite">
      <img src="/assets/images/BMV_Logo_n.png" alt="<?= BMV_NAME ?> Logo" width="120" height="36" loading="eager">
    </a>
    <nav class="site-nav" id="site-nav" aria-label="Hauptnavigation">
      <a href="/" class="site-nav__link">Startseite</a>
      <a href="/speiseplan/" class="site-nav__link">Speiseplan</a>
      <a href="/essen-auf-raedern/" class="site-nav__link">Essen auf Rädern</a>
      <a href="/kantine-am-gutshof/" class="site-nav__link">Kantine am Gutshof</a>
      <a href="/catering/" class="site-nav__link active">Catering</a>
      <a href="/ueber-uns/" class="site-nav__link">Über uns</a>
      <a href="/kontakt/" class="site-nav__link">Kontakt</a>
      <div class="nav-cta"><a href="/kontakt/" class="btn btn--primary btn--sm">Jetzt bestellen</a></div>
    </nav>
    <button class="nav-toggle" id="nav-toggle" aria-controls="site-nav" aria-expanded="false" aria-label="Menü öffnen">
      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
        <line x1="2" y1="6" x2="20" y2="6"/><line x1="2" y1="11" x2="20" y2="11"/><line x1="2" y1="16" x2="20" y2="16"/>
      </svg>
    </button>
  </div>
</header>

<main id="main-content" role="main">

  <section class="hero" aria-labelledby="hero-heading">
    <div class="hero__bg">
      <img src="/assets/images/catering-werder.jpg" alt="Catering Werder" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
      <div class="hero__overlay"></div>
    </div>
    <div class="container">
      <div class="hero__content">
        <div class="hero__badge">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
          Werder (Havel)
        </div>
        <h1 class="hero__title" id="hero-heading">
          Catering Werder (Havel) –<br>
          <span style="color:#ff7a1a;">frisch, pünktlich, unkompliziert</span>
        </h1>
        <p class="hero__sub">
          Direkt von unserem Standort: Catering für Ihre Veranstaltung in Werder und der näheren Umgebung. Kurze Wege, frisches Essen.
        </p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="/kontakt/">Anfrage senden</a>
          <a class="btn btn--ghost" href="/catering/">Alle Angebote</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="standort-heading">
    <div class="container">
      <div class="section-header fade-up">
        <h2 class="section-title" id="standort-heading">Von hier für hier</h2>
        <p class="section-sub">Unser Standort Am Gutshof ist Vorteil für Sie: Kurze Lieferwege, volle Kontrolle über die Qualität, persönlicher Service.</p>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center;margin-top:40px;">
        <div class="fade-up">
          <h3 style="color:#0B2A5B;margin-bottom:20px;font-size:1.2rem;font-weight:700;">Die Vorteile für Ihre Veranstaltung</h3>
          <ul style="list-style:none;padding:0;">
            <li style="padding:12px 0;display:flex;gap:12px;">
              <svg style="width:20px;height:20px;flex-shrink:0;fill:#D95A00;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <span style="color:#5a6a82;"><strong style="color:#0B2A5B;">Frisch zubereitet</strong> – alles am Tag Ihrer Veranstaltung</span>
            </li>
            <li style="padding:12px 0;display:flex;gap:12px;">
              <svg style="width:20px;height:20px;flex-shrink:0;fill:#D95A00;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <span style="color:#5a6a82;"><strong style="color:#0B2A5B;">Pünktlich</strong> – kurze Anfahrtswege garantieren Pünktlichkeit</span>
            </li>
            <li style="padding:12px 0;display:flex;gap:12px;">
              <svg style="width:20px;height:20px;flex-shrink:0;fill:#D95A00;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <span style="color:#5a6a82;"><strong style="color:#0B2A5B;">Anpassbar</strong> – auch kurzfristige Änderungen sind möglich</span>
            </li>
            <li style="padding:12px 0;display:flex;gap:12px;">
              <svg style="width:20px;height:20px;flex-shrink:0;fill:#D95A00;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <span style="color:#5a6a82;"><strong style="color:#0B2A5B;">Persönlich</strong> – Sie kennen Ihren Ansprechpartner</span>
            </li>
          </ul>
        </div>
        <div class="fade-up" style="transition-delay:0.1s;">
          <img src="/assets/images/kantine-gutshof.jpg" alt="BMV-Menüdienst Standort Werder" style="border-radius:16px;width:100%;display:block;">
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="angebot-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <h2 class="section-title" id="angebot-heading">Das bieten wir</h2>
      </div>
      <ul style="list-style:none;padding:0;margin-top:32px;">
        <li style="padding:20px 0;border-bottom:1px solid #e4edf8;" class="fade-up">
          <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">Firmenlunch</h3>
          <p style="color:#5a6a82;">Warmes Mittagessen für Ihr Team oder Ihre Gäste – mit Getränken und Besteck.</p>
        </li>
        <li style="padding:20px 0;border-bottom:1px solid #e4edf8;" class="fade-up">
          <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">Betriebsfeste &amp; Feierlichkeiten</h3>
          <p style="color:#5a6a82;">Buffet, kalte Platten, Desserts – vollständige catering bis zum Abbau.</p>
        </li>
        <li style="padding:20px 0;border-bottom:1px solid #e4edf8;" class="fade-up">
          <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">Seminare & Tagungen</h3>
          <p style="color:#5a6a82;">Ganztägiges Catering mit Frühstück, Mittagessen, Getränken und Snacks.</p>
        </li>
        <li style="padding:20px 0;" class="fade-up">
          <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">Private Feiern</h3>
          <p style="color:#5a6a82;">Hochzeit, Geburtstag, Jubiläum – wir richten auch private Feiern aus.</p>
        </li>
      </ul>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="cta-heading">
    <div class="container">
      <div style="text-align:center;">
        <h2 class="section-title" id="cta-heading">Catering für Werder & Umgebung?</h2>
        <p class="section-sub" style="max-width:600px;margin:0 auto;">Kontaktieren Sie uns noch heute – am liebsten persönlich!</p>
        <div style="margin-top:var(--space-8);display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
          <a class="btn btn--primary" href="tel:<?= BMV_TEL ?>"><?= BMV_TEL_DISPLAY ?></a>
          <a class="btn btn--secondary" href="/kontakt/">Anfrage-Formular</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
<script src="/assets/js/hero-upgrade.js" defer></script>
</body>
</html>
