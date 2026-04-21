<?php
$page_title       = 'Essen auf Rädern Potsdam – täglich frisch geliefert';
$meta_description = 'Essen auf Rädern in Potsdam: Täglich warmes Mittagessen für alle Stadtteile. BMV-Menüdienst liefert Mo–So pünktlich zu Ihnen nach Hause.';
$active_nav       = 'ears';
$canonical        = 'https://www.bmv-kantinen.de/essen-auf-raedern/potsdam/';

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
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
  <link rel="icon" href="/assets/images/Favicon.png" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/design-system.css">
  <link rel="stylesheet" href="/assets/css/hero-unified.css">
  <link rel="stylesheet" href="/assets/css/bmv-ci.css">
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
      <img src="/assets/images/potsdam-lieferung.jpg" alt="Essen auf Rädern Lieferung in Potsdam" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
      <div class="hero__overlay"></div>
    </div>
    <div class="container">
      <div class="hero__content">
        <div class="hero__badge">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
          Potsdam – alle Stadtteile
        </div>
        <h1 class="hero__title" id="hero-heading">
          Essen auf Rädern Potsdam –<br>
          <span style="color:#ff7a1a;">täglich frisch an Ihre Tür</span>
        </h1>
        <p class="hero__sub">
          Von der Innenstadt bis Babelsberg: Wir beliefern alle Potsdamer Stadtteile täglich mit frisch zubereiteten Mittagsmahlzeiten. Pünktlich, zuverlässig, flexibel.
        </p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="/kontakt/">Jetzt bestellen</a>
          <a class="btn btn--ghost" href="/essen-auf-raedern/">Mehr erfahren</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="potsdam-info">
    <div class="container">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">Potsdam</div>
        <h2 class="section-title" id="potsdam-info">Essen auf Rädern in ganz Potsdam</h2>
        <p class="section-sub">Alle Stadtteile werden regelmäßig von uns beliefert – Sie wählen nur noch Ihr Menü und den Liefertag.</p>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-top:40px;">
        <div class="fade-up" style="padding:32px;background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(11,42,91,0.07);">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-size:1.1rem;font-weight:700;">Stadtzentrum &amp; Innenstadt</h3>
          <p style="color:#5a6a82;line-height:1.6;">Regelmäßige Lieferungen in der gesamten Innenstadt und angrenzenden Wohngebieten. Schnelle Anlieferung, persönlicher Kontakt.</p>
        </div>
        <div class="fade-up" style="padding:32px;background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(11,42,91,0.07);transition-delay:0.1s;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-size:1.1rem;font-weight:700;">Babelsberg &amp; Drewitz</h3>
          <p style="color:#5a6a82;line-height:1.6;">Auch in den südlichen Stadtteilen sind wir täglich unterwegs – von Mo bis So, pünktlich zwischen 11–13 Uhr.</p>
        </div>
        <div class="fade-up" style="padding:32px;background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(11,42,91,0.07);transition-delay:0.2s;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-size:1.1rem;font-weight:700;">Nördliche Stadtteile</h3>
          <p style="color:#5a6a82;line-height:1.6;">Von Eiche bis Klarenberge – alle nördlichen Stadtteile werden regelmäßig beliefert. Fragen Sie nach Ihrem Stadtteil!</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="vorteile-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <h2 class="section-title" id="vorteile-heading">Warum Potsdam auf BMV vertraut</h2>
      </div>
      <ul style="list-style:none;padding:0;">
        <li style="padding:20px 0;border-bottom:1px solid #e4edf8;display:flex;align-items:flex-start;gap:16px;" class="fade-up">
          <svg style="width:24px;height:24px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          <div>
            <strong style="color:#0B2A5B;display:block;margin-bottom:4px;">15+ Jahre Erfahrung in der Region</strong>
            <span style="color:#5a6a82;">Wir kennen Potsdam, die Menschen und ihre Bedürfnisse.</span>
          </div>
        </li>
        <li style="padding:20px 0;border-bottom:1px solid #e4edf8;display:flex;align-items:flex-start;gap:16px;" class="fade-up">
          <svg style="width:24px;height:24px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          <div>
            <strong style="color:#0B2A5B;display:block;margin-bottom:4px;">Täglich frisch zubereitet</strong>
            <span style="color:#5a6a82;">Keine Tiefkühlware – alles kommt morgens aus unserer Küche in Werder.</span>
          </div>
        </li>
        <li style="padding:20px 0;border-bottom:1px solid #e4edf8;display:flex;align-items:flex-start;gap:16px;" class="fade-up">
          <svg style="width:24px;height:24px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          <div>
            <strong style="color:#0B2A5B;display:block;margin-bottom:4px;">Persönlicher Service</strong>
            <span style="color:#5a6a82;">Sie sprechen mit Menschen, nicht mit einer Hotline – und der Fahrer ist Ihr Ansprechpartner.</span>
          </div>
        </li>
        <li style="padding:20px 0;display:flex;align-items:flex-start;gap:16px;" class="fade-up">
          <svg style="width:24px;height:24px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          <div>
            <strong style="color:#0B2A5B;display:block;margin-bottom:4px;">Flexible Bestellung</strong>
            <span style="color:#5a6a82;">Keine Mindestlaufzeit, jederzeit änderbar, auch einzelne Tage auswählbar.</span>
          </div>
        </li>
      </ul>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="cta-heading">
    <div class="container">
      <div style="text-align:center;">
        <h2 class="section-title" id="cta-heading">Bereit für Essen auf Rädern in Potsdam?</h2>
        <p class="section-sub" style="max-width:600px;margin:0 auto;">Einfach anrufen, kostenlos beraten lassen und morgen kann es losgehen.</p>
        <div style="margin-top:var(--space-8);display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
          <a class="btn btn--primary" href="tel:<?= BMV_TEL ?>"><?= BMV_TEL_DISPLAY ?></a>
          <a class="btn btn--secondary" href="/kontakt/">Online anfragen</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
<script src="/assets/js/hero-upgrade.js" defer></script>
</body>
</html>
