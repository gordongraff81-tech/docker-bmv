<?php
$page_title       = 'Catering Potsdam – Firmenessen & Betriebsfeste';
$meta_description = 'Catering in Potsdam: Firmenlunch, Betriebsfeste, Veranstaltungen von BMV-Menüdienst. Frisch, professionell, vor Ort.';
$active_nav       = 'catering';
$canonical        = 'https://www.bmv-kantinen.de/catering/potsdam/';

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
      <img src="/assets/images/catering-setup.jpg" alt="Catering Potsdam" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
      <div class="hero__overlay"></div>
    </div>
    <div class="container">
      <div class="hero__content">
        <div class="hero__badge">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
          Potsdam
        </div>
        <h1 class="hero__title" id="hero-heading">
          Catering Potsdam –<br>
          <span style="color:#ff7a1a;">frisch, pünktlich, unkompliziert</span>
        </h1>
        <p class="hero__sub">
          Firmenlunch, Betriebsfest, Konferenzessen – wir liefern und bauen auf. In allen Potsdamer Stadtteilen.
        </p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="/kontakt/">Anfrage senden</a>
          <a class="btn btn--ghost" href="/catering/">Alle Catering-Optionen</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="potsdam-heading">
    <div class="container">
      <div class="section-header fade-up">
        <h2 class="section-title" id="potsdam-heading">Catering für Potsdam</h2>
        <p class="section-sub">Egal ob in Ihrem Unternehmen, im Hotel oder privat – wir begleiten Ihre Veranstaltung kulinarisch.</p>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-top:40px;">
        <div class="fade-up" style="padding:32px;background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(11,42,91,0.07);">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-size:1.15rem;font-weight:700;">Stadtzentrum</h3>
          <p style="color:#5a6a82;line-height:1.6;">Schnelle Anlieferung, zentrale Lage. Perfekt für Bürogebäude in der Innenstadt.</p>
        </div>
        <div class="fade-up" style="padding:32px;background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(11,42,91,0.07);transition-delay:0.1s;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-size:1.15rem;font-weight:700;">Babelsberg &amp; Süden</h3>
          <p style="color:#5a6a82;line-height:1.6;">Auch die südlichen Stadtteile beliefern wir zuverlässig – mit kurzen Lieferwegen.</p>
        </div>
        <div class="fade-up" style="padding:32px;background:#fff;border-radius:16px;box-shadow:0 4px 6px rgba(11,42,91,0.07);transition-delay:0.2s;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-size:1.15rem;font-weight:700;">Hotels &amp; Konferenzen</h3>
          <p style="color:#5a6a82;line-height:1.6;">Professionelle Catering für große Veranstaltungen, Konferenzen und Tagungen.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="prozess-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <h2 class="section-title" id="prozess-heading">So läuft's ab</h2>
      </div>
      <ol style="list-style:none;padding:0;counter-reset:step;">
        <li style="padding:24px 0;border-bottom:1px solid #e4edf8;display:grid;grid-template-columns:60px 1fr;gap:20px;align-items:start;" class="fade-up">
          <div style="width:60px;height:60px;background:#D95A00;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:700;">1</div>
          <div>
            <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">Anfrage & Beratung</h3>
            <p style="color:#5a6a82;">Sie nennen Termin, Gästezahl und Wünsche. Wir besprechen Details und Preise.</p>
          </div>
        </li>
        <li style="padding:24px 0;border-bottom:1px solid #e4edf8;display:grid;grid-template-columns:60px 1fr;gap:20px;align-items:start;" class="fade-up">
          <div style="width:60px;height:60px;background:#D95A00;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:700;">2</div>
          <div>
            <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">Menü absprechen</h3>
            <p style="color:#5a6a82;">Gemeinsam mit Ihnen planen wir das Menü – individuell auf Ihre Vorstellungen abgestimmt.</p>
          </div>
        </li>
        <li style="padding:24px 0;border-bottom:1px solid #e4edf8;display:grid;grid-template-columns:60px 1fr;gap:20px;align-items:start;" class="fade-up">
          <div style="width:60px;height:60px;background:#D95A00;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:700;">3</div>
          <div>
            <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">Zubereitung</h3>
            <p style="color:#5a6a82;">Am Tag der Veranstaltung bereiten wir alles frisch zu – für maximale Qualität.</p>
          </div>
        </li>
        <li style="padding:24px 0;display:grid;grid-template-columns:60px 1fr;gap:20px;align-items:start;" class="fade-up">
          <div style="width:60px;height:60px;background:#D95A00;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:700;">4</div>
          <div>
            <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">Lieferung & Aufbau</h3>
            <p style="color:#5a6a82;">Wir liefern pünktlich, bauen auf und kümmern uns um den Abbau. Sie genießen!</p>
          </div>
        </li>
      </ol>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="cta-heading">
    <div class="container">
      <div style="text-align:center;">
        <h2 class="section-title" id="cta-heading">Catering für Ihre Veranstaltung in Potsdam?</h2>
        <p class="section-sub" style="max-width:600px;margin:0 auto;">Rufen Sie uns an oder senden Sie eine Anfrage. Wir kümmern uns um den Rest!</p>
        <div style="margin-top:var(--space-8);display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
          <a class="btn btn--primary" href="tel:<?= BMV_TEL ?>"><?= BMV_TEL_DISPLAY ?></a>
          <a class="btn btn--secondary" href="/kontakt/">Anfrage senden</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
<script src="/assets/js/hero-upgrade.js" defer></script>
</body>
</html>
