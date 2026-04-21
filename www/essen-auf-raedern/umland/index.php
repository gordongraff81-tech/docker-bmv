<?php
$page_title       = 'Essen auf Rädern Umland & Gemeinden – Liefergebiete';
$meta_description = 'Essen auf Rädern in Potsdam-Mittelmark: BMV-Menüdienst beliefert auch Gemeinden im Umland. Fragen Sie Ihren Ort!';
$active_nav       = 'ears';
$canonical        = 'https://www.bmv-kantinen.de/essen-auf-raedern/umland/';

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
      <img src="/assets/images/umland-lieferung.jpg" alt="Essen auf Rädern im Umland" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
      <div class="hero__overlay"></div>
    </div>
    <div class="container">
      <div class="hero__content">
        <div class="hero__badge">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
          Potsdam-Mittelmark
        </div>
        <h1 class="hero__title" id="hero-heading">
          Essen auf Rädern im Umland –<br>
          <span style="color:#ff7a1a;">täglich frisch an Ihre Tür</span>
        </h1>
        <p class="hero__sub">
          Auch wenn Sie nicht in Potsdam oder Werder wohnen – wir beliefern zahlreiche Gemeinden in Potsdam-Mittelmark. Fragen Sie, ob Ihr Ort dabei ist!
        </p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="/kontakt/">Jetzt anfragen</a>
          <a class="btn btn--ghost" href="#gebiete">Unsere Gebiete</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" id="gebiete" aria-labelledby="gebiete-heading">
    <div class="container">
      <div class="section-header fade-up">
        <h2 class="section-title" id="gebiete-heading">Lieferbereiche im Umland</h2>
        <p class="section-sub">Diese Gemeinden beliefern wir regelmäßig. Ist Ihr Ort nicht dabei? Rufen Sie an – wir prüfen es!</p>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin-top:40px;">
        <?php
        $orte = [
          'Michendorf', 'Stahnsdorf', 'Geltow', 'Töplitz', 'Teltow', 
          'Kleinmachnow', 'Nuthetal', 'Langerwisch', 'Beelitz', 'Borkwalde'
        ];
        foreach ($orte as $i => $ort):
        ?>
        <div class="fade-up" style="transition-delay:<?= ($i % 3) * 0.1 ?>s;padding:24px;background:#fff;border-radius:12px;border-left:4px solid #D95A00;box-shadow:0 2px 4px rgba(11,42,91,0.07);">
          <h3 style="color:#0B2A5B;margin:0;font-size:1rem;font-weight:700;"><?= htmlspecialchars($ort) ?></h3>
          <p style="color:#5a6a82;margin:8px 0 0;font-size:0.9rem;">Essen auf Rädern möglich</p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="anfrage-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <h2 class="section-title" id="anfrage-heading">Ihr Ort nicht dabei?</h2>
        <p class="section-sub">Kontaktieren Sie uns – wir prüfen gerne, ob wir Sie auch beliefern können.</p>
      </div>
      <div style="background:#f8fafc;padding:32px;border-radius:16px;text-align:center;margin-top:32px;" class="fade-up">
        <p style="color:#5a6a82;margin-bottom:24px;">Senden Sie uns eine Anfrage mit Ihrer Adresse, und wir melden uns schnell bei Ihnen zurück.</p>
        <a class="btn btn--primary" href="/kontakt/">Anfrage senden</a>
      </div>
    </div>
  </section>

</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
<script src="/assets/js/hero-upgrade.js" defer></script>
</body>
</html>
