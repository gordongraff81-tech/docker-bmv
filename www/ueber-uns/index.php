<?php
$page_title       = 'Über uns – BMV-Menüdienst Potsdam & Werder (Havel)';
$meta_description = 'BMV-Menüdienst: Wer wir sind, was uns antreibt und warum wir seit über 15 Jahren der verlässliche Partner für Essen auf Rädern in Potsdam und Werder (Havel) sind.';
$active_nav       = 'ueber-uns';
$canonical        = 'https://www.bmv-kantinen.de/ueber-uns/';

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
  <meta property="og:title"       content="Über uns – BMV-MenÜdienst Potsdam & Werder (Havel)">
  <meta property="og:description" content="Das Team hinter BMV-MenÜdienst: seit 2009 frisches Essen fÜr Senioren und Betriebe in Potsdam und Werder (Havel).">
  <meta property="og:image"       content="https://www.bmv-kantinen.de/assets/og-image.jpg">
  <meta property="og:locale"      content="de_DE">
  <meta property="og:site_name"   content="BMV MenÜdienst">
  <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Startseite","item":"https://www.bmv-kantinen.de/"},{"@type":"ListItem","position":2,"name":"Ueber uns","item":"https://www.bmv-kantinen.de/ueber-uns/"}]}</script>
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
      <a href="/catering/" class="site-nav__link">Catering</a>
      <a href="/ueber-uns/" class="site-nav__link active">Über uns</a>
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
      <img src="/assets/images/ueber-uns-team.jpg" alt="BMV-Menüdienst Team" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
      <div class="hero__overlay"></div>
    </div>
    <div class="container">
      <div class="hero__content">
        <div class="hero__badge">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
          Seit über 15 Jahren
        </div>
        <h1 class="hero__title" id="hero-heading">
          Über uns –<br>
          <span style="color:#ff7a1a;">regional verwurzelt, täglich engagiert</span>
        </h1>
        <p class="hero__sub">
          <?= BMV_NAME ?> steht für tägliches Engagement in der Region Potsdam und Werder (Havel). Frisches Essen, zuverlässiger Service und persönliche Nähe zu unseren Kunden – das ist unser Anspruch.
        </p>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="wer-heading">
    <div class="container">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">Wer wir sind</div>
        <h2 class="section-title" id="wer-heading">Ein regionales Unternehmen mit klarem Auftrag</h2>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center;margin-top:40px;">
        <div class="fade-up">
          <p style="color:#5a6a82;line-height:1.8;margin-bottom:20px;"><?= BMV_NAME ?> ist ein inhabergeführtes Unternehmen mit Sitz in Werder (Havel). Wir versorgen täglich Seniorinnen und Senioren in Potsdam, Werder und dem westlichen Umland mit frisch zubereiteten Mittagsmahlzeiten – und betreiben darüber hinaus einen professionellen Catering-Service für Unternehmen und Veranstaltungen in der Region.</p>
          <p style="color:#5a6a82;line-height:1.8;margin-bottom:20px;">Was uns von überregionalen Anbietern unterscheidet? Wir sind hier. Unsere Küche ist in Werder (Havel), unsere Fahrer kennen die Straßen und ihre Kunden persönlich, und wenn jemand aus unserem Team anruft, ist es tatsächlich jemand, der die Region kennt – kein Callcenter in einer anderen Stadt.</p>
          <p style="color:#5a6a82;line-height:1.8;">Diese regionale Verwurzelung ist kein Zufallsprodukt, sondern Überzeugung. Wir glauben, dass gute Versorgung im Alter oder in schwierigen Lebensphasen von Menschen geleistet wird, die sich kümmern – und das lässt sich nicht digitalisieren oder automatisieren.</p>
        </div>
        <div class="fade-up" style="transition-delay:0.1s;">
          <img src="/assets/images/ueber-uns-team.jpg" alt="<?= BMV_NAME ?> Team" style="border-radius:16px;width:100%;display:block;box-shadow:0 8px 20px rgba(11,42,91,0.1);">
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="werte-heading">
    <div class="container">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">Unsere Werte</div>
        <h2 class="section-title" id="werte-heading">Was uns täglich antreibt</h2>
        <p class="section-sub">Diese Grundsätze leiten unsere Arbeit – bei jedem Gericht, jedem Gespräch, jedem Einsatz.</p>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;margin-top:40px;">
        <div class="fade-up" style="padding:32px;background:#f8fafc;border-radius:16px;border-top:4px solid #D95A00;">
          <h3 style="color:#0B2A5B;margin-bottom:16px;font-size:1.1rem;font-weight:700;">✓ Verlässlichkeit</h3>
          <p style="color:#5a6a82;line-height:1.6;">Unsere Kunden verlassen sich darauf, dass das Essen kommt – auch an Regentagen, auch in der Ferienzeit. Dieser Verlass ist für uns nicht verhandelbar.</p>
        </div>
        <div class="fade-up" style="padding:32px;background:#f8fafc;border-radius:16px;border-top:4px solid #D95A00;transition-delay:0.1s;">
          <h3 style="color:#0B2A5B;margin-bottom:16px;font-size:1.1rem;font-weight:700;">✓ Frische</h3>
          <p style="color:#5a6a82;line-height:1.6;">Täglich frisch zubereitet, täglich warm angeliefert. Keine Fertigware, keine Kompromisse bei der Qualität der Speisen. Das ist unser Standard.</p>
        </div>
        <div class="fade-up" style="padding:32px;background:#f8fafc;border-radius:16px;border-top:4px solid #D95A00;transition-delay:0.2s;">
          <h3 style="color:#0B2A5B;margin-bottom:16px;font-size:1.1rem;font-weight:700;">✓ Regionalität</h3>
          <p style="color:#5a6a82;line-height:1.6;">Wir sind Teil der Region Potsdam-Mittelmark und verstehen uns als lokaler Partner – nicht als externer Dienstleister, der seine Leistung von außen einspielt.</p>
        </div>
        <div class="fade-up" style="padding:32px;background:#f8fafc;border-radius:16px;border-top:4px solid #D95A00;transition-delay:0.3s;">
          <h3 style="color:#0B2A5B;margin-bottom:16px;font-size:1.1rem;font-weight:700;">✓ Menschlichkeit</h3>
          <p style="color:#5a6a82;line-height:1.6;">Hinter jedem Kunden steht ein Mensch mit einer Geschichte. Das vergessen wir nie – weder beim Kochen noch beim Liefern.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="geschichte-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">Unsere Geschichte</div>
        <h2 class="section-title" id="geschichte-heading">15+ Jahre Erfahrung</h2>
      </div>
      <div style="margin-top:40px;">
        <div class="fade-up" style="padding:28px;background:#fff;border-radius:12px;border-left:4px solid #D95A00;margin-bottom:20px;box-shadow:0 2px 6px rgba(11,42,91,0.07);">
          <p style="color:#5a6a82;line-height:1.8;">Seit der Gründung vor über 15 Jahren haben wir tausende Mittagessen zubereitet und ausgeliefert. Wir kennen unsere Kunden, wissen, wer Schonkost braucht, wer vegetarisch isst, wem die Portion zu klein war. Diese Kontinuität ist unsere Stärke.</p>
        </div>
        <div class="fade-up" style="padding:28px;background:#fff;border-radius:12px;border-left:4px solid #D95A00;margin-bottom:20px;box-shadow:0 2px 6px rgba(11,42,91,0.07);">
          <p style="color:#5a6a82;line-height:1.8;">Wir haben die Region durchlebt – Veränderungen, Krisen, Wachstum. Und wir sind geblieben. Nicht weil es immer einfach war, sondern weil wir an unsere Aufgabe glauben.</p>
        </div>
        <div class="fade-up" style="padding:28px;background:#fff;border-radius:12px;border-left:4px solid #D95A00;box-shadow:0 2px 6px rgba(11,42,91,0.07);">
          <p style="color:#5a6a82;line-height:1.8;">Heute sind wir stolz, dass Generationen von Menschen unsere Dienste nutzen – Großeltern, ihre Kinder, manchmal sogar die Enkel als Kunden der Kantine. Das ist echte Bindung.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="team-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">Unser Team</div>
        <h2 class="section-title" id="team-heading">Menschen, die sich kümmern</h2>
        <p class="section-sub">Hinter jedem Mittagessen steht ein kleines Team von engagierten Menschen – Köche, Fahrer, Verwaltung.</p>
      </div>
      <ul style="list-style:none;padding:0;margin-top:40px;">
        <li style="padding:20px 0;border-bottom:1px solid #e4edf8;display:flex;gap:16px;align-items:flex-start;" class="fade-up">
          <svg style="width:24px;height:24px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          <div>
            <strong style="color:#0B2A5B;display:block;margin-bottom:4px;">Köche & Küchenpersonal</strong>
            <span style="color:#5a6a82;">Erfahrene Köche, die täglich frisch kochen – mit traditionellen Rezepten und neuem Geschmack.</span>
          </div>
        </li>
        <li style="padding:20px 0;border-bottom:1px solid #e4edf8;display:flex;gap:16px;align-items:flex-start;" class="fade-up">
          <svg style="width:24px;height:24px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          <div>
            <strong style="color:#0B2A5B;display:block;margin-bottom:4px;">Lieferfahrer</strong>
            <span style="color:#5a6a82;">Zuverlässig, freundlich, pünktlich. Der Kontakt zu unseren Kunden – jeden Tag aufs Neue.</span>
          </div>
        </li>
        <li style="padding:20px 0;display:flex;gap:16px;align-items:flex-start;" class="fade-up">
          <svg style="width:24px;height:24px;flex-shrink:0;fill:#D95A00;margin-top:2px;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          <div>
            <strong style="color:#0B2A5B;display:block;margin-bottom:4px;">Verwaltung & Koordination</strong>
            <span style="color:#5a6a82;">Das Rückgrat – Bestellungen, Lieferpläne, Kundenkontakt. Alles funktioniert, weil hier im Hintergrund alles stimmt.</span>
          </div>
        </li>
      </ul>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="cta-heading">
    <div class="container">
      <div style="text-align:center;">
        <h2 class="section-title" id="cta-heading">Lernen Sie uns kennen</h2>
        <p class="section-sub" style="max-width:600px;margin:0 auto;">Besuchen Sie unsere Kantine, rufen Sie an oder bestellen Sie Essen auf Rädern. Sie werden schnell merken, dass wir ernst meinen, was wir sagen.</p>
        <div style="margin-top:var(--space-8);display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
          <a class="btn btn--primary" href="/kontakt/">Kontakt aufnehmen</a>
          <a class="btn btn--secondary" href="/kantine-am-gutshof/">Besuchen Sie unsere Kantine</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
<script src="/assets/js/hero-upgrade.js" defer></script>
</body>
</html>
