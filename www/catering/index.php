<?php
$page_title       = 'Catering Potsdam & Werder – Unternehmensevents & Feiern';
$meta_description = 'Catering von BMV-Menüdienst: Firmenessen, Betriebsfeste, Veranstaltungen in Potsdam und Werder (Havel). Frisch, individuell, professionell.';
$active_nav       = 'catering';
$canonical        = 'https://www.bmv-kantinen.de/catering/';

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
      <img src="/assets/images/catering-setup.jpg" alt="Catering Betriebsfeier" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
      <div class="hero__overlay"></div>
    </div>
    <div class="container">
      <div class="hero__content">
        <div class="hero__badge">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
          Potsdam & Werder (Havel)
        </div>
        <h1 class="hero__title" id="hero-heading">
          Catering –<br>
          <span style="color:#ff7a1a;">frisch, pünktlich, unkompliziert</span>
        </h1>
        <p class="hero__sub">
          Von der Firmenfeier bis zum Betriebslunch: Wir liefern und bauen auf. Frisch zubereitet, professionell serviert – für Ihre Veranstaltung.
        </p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="/kontakt/">Anfrage senden</a>
          <a class="btn btn--ghost" href="#leistungen">Unsere Leistungen</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" id="leistungen" aria-labelledby="leistungen-heading">
    <div class="container">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">Was wir bieten</div>
        <h2 class="section-title" id="leistungen-heading">Catering für jede Art von Veranstaltung</h2>
        <p class="section-sub">Ob klein oder groß – wir planen, kochen und bauen auf. Ganz nach Ihren Wünschen.</p>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-top:40px;">
        <div class="fade-up" style="padding:32px;background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(11,42,91,0.07);">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-size:1.1rem;font-weight:700;">🍽️ Firmenlunch</h3>
          <p style="color:#5a6a82;line-height:1.6;">Mittagessen für Ihr Team – mit Getränken, Servietten, alles organisiert. Sie kümmern sich nur um die Arbeit, der Rest läuft.</p>
        </div>
        <div class="fade-up" style="padding:32px;background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(11,42,91,0.07);transition-delay:0.1s;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-size:1.1rem;font-weight:700;">🎉 Betriebsfeste</h3>
          <p style="color:#5a6a82;line-height:1.6;">Buffet, kalte Platten, Desserts – wir stellen es zusammen, liefern es und bauen auf. Ihre Gäste sind begeistert.</p>
        </div>
        <div class="fade-up" style="padding:32px;background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(11,42,91,0.07);transition-delay:0.2s;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-size:1.1rem;font-weight:700;">📋 Meetings & Seminare</h3>
          <p style="color:#5a6a82;line-height:1.6;">Catering für ganztägige Veranstaltungen – Frühstück, Mittagessen, Getränke und Snacks über den Tag verteilt.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="highlights-heading">
    <div class="container">
      <div class="section-header fade-up">
        <h2 class="section-title" id="highlights-heading">Was macht unser Catering besonders?</h2>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center;margin-top:40px;">
        <div class="fade-up">
          <ul style="list-style:none;padding:0;">
            <li style="padding:16px 0;display:flex;align-items:flex-start;gap:12px;">
              <svg style="width:24px;height:24px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <div>
                <strong style="color:#0B2A5B;display:block;">Individuell geplant</strong>
                <span style="color:#5a6a82;font-size:0.9rem;">Keine Standard-Menüs – wir besprechen mit Ihnen, was genau Sie brauchen.</span>
              </div>
            </li>
            <li style="padding:16px 0;display:flex;align-items:flex-start;gap:12px;">
              <svg style="width:24px;height:24px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <div>
                <strong style="color:#0B2A5B;display:block;">Frisch zubereitet</strong>
                <span style="color:#5a6a82;font-size:0.9rem;">Am Tag der Veranstaltung gekocht – garantiert frisch und heiß.</span>
              </div>
            </li>
            <li style="padding:16px 0;display:flex;align-items:flex-start;gap:12px;">
              <svg style="width:24px;height:24px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <div>
                <strong style="color:#0B2A5B;display:block;">Lieferung + Aufbau</strong>
                <span style="color:#5a82;font-size:0.9rem;">Wir liefern, bauen auf und räumen hinterher wieder auf – Sie genießen!</span>
              </div>
            </li>
            <li style="padding:16px 0;display:flex;align-items:flex-start;gap:12px;">
              <svg style="width:24px;height:24px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <div>
                <strong style="color:#0B2A5B;display:block;">Flexible Preise</strong>
                <span style="color:#5a6a82;font-size:0.9rem;">Von klein bis groß – wir machen ein Angebot, das zu Ihrem Budget passt.</span>
              </div>
            </li>
          </ul>
        </div>
        <div class="fade-up" style="transition-delay:0.1s;">
          <img src="/assets/images/catering-werder.jpg" alt="Catering beim Kunden vor Ort" style="border-radius:16px;width:100%;display:block;">
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="gebiete-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <h2 class="section-title" id="gebiete-heading">Lieferbereiche</h2>
        <p class="section-sub">Wir catern für Veranstaltungen in Potsdam, Werder (Havel) und der Region Potsdam-Mittelmark.</p>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:32px;">
        <div class="fade-up" style="padding:24px;background:#f8fafc;border-radius:12px;">
          <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">📍 Potsdam</h3>
          <p style="color:#5a6a82;font-size:0.9rem;">Alle Stadtteile, Hotels, Konferenzorte, private Räume</p>
          <a href="/catering/potsdam/" style="color:#1046a0;text-decoration:none;font-weight:600;">Mehr Info →</a>
        </div>
        <div class="fade-up" style="padding:24px;background:#f8fafc;border-radius:12px;transition-delay:0.1s;">
          <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">📍 Werder (Havel)</h3>
          <p style="color:#5a6a82;font-size:0.9rem;">Von unserem Standort aus – kurze Lieferwege</p>
          <a href="/catering/werder-havel/" style="color:#1046a0;text-decoration:none;font-weight:600;">Mehr Info →</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="cta-heading">
    <div class="container">
      <div style="text-align:center;">
        <h2 class="section-title" id="cta-heading">Catering für Ihre Veranstaltung?</h2>
        <p class="section-sub" style="max-width:600px;margin:0 auto;">Schreiben oder rufen Sie uns an. Wir besprechen Ihre Wünsche und machen ein Angebot.</p>
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
