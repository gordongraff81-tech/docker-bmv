<?php
$page_title       = 'Essen auf Rädern Werder (Havel) – von hier geliefert';
$meta_description = 'Essen auf Rädern in Werder (Havel): Täglich frische Mittagsmahlzeiten vom Standort Am Gutshof. Lieferung auch in der Region.';
$active_nav       = 'ears';
$canonical        = 'https://www.bmv-kantinen.de/essen-auf-raedern/werder-havel/';

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
      <a href="/essen-auf-raedern/" class="site-nav__link active">Essen auf Rädern</a>
      <a href="/kantine-am-gutshof/" class="site-nav__link">Kantine am Gutshof</a>
      <a href="/catering/" class="site-nav__link">Catering</a>
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
      <img src="/assets/images/werder-havel-standort.jpg" alt="Standort Werder (Havel)" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
      <div class="hero__overlay"></div>
    </div>
    <div class="container">
      <div class="hero__content">
        <div class="hero__badge">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
          Werder (Havel) & Umgebung
        </div>
        <h1 class="hero__title" id="hero-heading">
          Essen auf Rädern Werder (Havel) –<br>
          <span style="color:#ff7a1a;">täglich frisch an Ihre Tür</span>
        </h1>
        <p class="hero__sub">
          Von unserem Standort Am Gutshof liefern wir täglich frische Mittagsmahlzeiten in Werder und die direkt angrenzenden Ortschaften.
        </p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="/kontakt/">Jetzt bestellen</a>
          <a class="btn btn--ghost" href="/kantine-am-gutshof/">Besuchen Sie unsere Kantine</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="werder-info">
    <div class="container">
      <div class="section-header fade-up">
        <h2 class="section-title" id="werder-info">Direkt von hier – für hier</h2>
        <p class="section-sub">Unser Standort Am Gutshof 6 ist nicht nur eine Lieferadresse. Hier kochen wir, hier organisieren wir, hier ist unser Team zu Hause.</p>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center;margin-top:40px;">
        <div class="fade-up">
          <h3 style="color:#0B2A5B;font-size:1.3rem;margin-bottom:16px;font-weight:700;">Unser Vorteil für Sie</h3>
          <ul style="list-style:none;padding:0;">
            <li style="padding:12px 0;display:flex;align-items:flex-start;gap:12px;">
              <svg style="width:20px;height:20px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <span style="color:#5a6a82;">Kurze Lieferwege = warmes Essen bei Ihnen</span>
            </li>
            <li style="padding:12px 0;display:flex;align-items:flex-start;gap:12px;">
              <svg style="width:20px;height:20px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <span style="color:#5a6a82;">Der Fahrer kennt sich und seine Kunden</span>
            </li>
            <li style="padding:12px 0;display:flex;align-items:flex-start;gap:12px;">
              <svg style="width:20px;height:20px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <span style="color:#5a6a82;">Bei Fragen direkt vor Ort erreichbar</span>
            </li>
            <li style="padding:12px 0;display:flex;align-items:flex-start;gap:12px;">
              <svg style="width:20px;height:20px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <span style="color:#5a6a82;">Besuchen Sie unsere Kantine nebenan</span>
            </li>
          </ul>
        </div>
        <div class="fade-up" style="transition-delay:0.1s;">
          <img src="/assets/images/kantine-gutshof.jpg" alt="Kantine am Gutshof in Werder" style="border-radius:16px;width:100%;display:block;">
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="standort-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <h2 class="section-title" id="standort-heading">Besuchen Sie uns</h2>
        <p class="section-sub">Sie können unsere Kantine besuchen, den Ort des Geschehens sehen und mit unserem Team sprechen.</p>
      </div>
      <div style="background:#f8fafc;padding:32px;border-radius:16px;border-left:4px solid #D95A00;margin-top:32px;" class="fade-up">
        <h3 style="color:#0B2A5B;margin-bottom:12px;">Am Gutshof 6, 14542 Werder (Havel)</h3>
        <p style="color:#5a6a82;margin-bottom:16px;"><strong>Öffnungszeiten Kantine:</strong> Mo–Fr 07:00 – 15:00 Uhr</p>
        <p style="color:#5a6a82;"><a href="/kontakt/" style="color:#1046a0;text-decoration:none;font-weight:600;">Anfahrtsbeschreibung &amp; Google Maps →</a></p>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="cta-heading">
    <div class="container">
      <div style="text-align:center;">
        <h2 class="section-title" id="cta-heading">Essen auf Rädern auch für Sie?</h2>
        <p class="section-sub" style="max-width:600px;margin:0 auto;">Kontaktieren Sie uns noch heute. Wir freuen uns auf Sie!</p>
        <div style="margin-top:var(--space-8);display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
          <a class="btn btn--primary" href="tel:<?= BMV_TEL ?>"><?= BMV_TEL_DISPLAY ?></a>
          <a class="btn btn--secondary" href="/essen-auf-raedern/">Zurück zu Essen auf Rädern</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
<script src="/assets/js/hero-upgrade.js" defer></script>
</body>
</html>
