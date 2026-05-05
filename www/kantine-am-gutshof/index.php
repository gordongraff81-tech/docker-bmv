<?php
$page_title       = 'Kantine am Gutshof Werder (Havel) – Täglicher Mittagstisch';
$meta_description = 'Kantine am Gutshof in Werder (Havel): Täglich frischer Mittagstisch für Handwerker, Angestellte und Senioren. Mo–Fr 07:00–15:00 Uhr.';
$active_nav       = 'kantine';
$canonical        = 'https://www.bmv-kantinen.de/kantine-am-gutshof/';

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
  <meta property="og:title"       content="Kantine am Gutshof Werder (Havel) – Täglicher Mittagstisch">
  <meta property="og:description" content="Betriebskantine am Gutshof Werder (Havel). Frisch gekochter Mittagstisch fÜr Mitarbeiter und Gäste.">
  <meta property="og:image"       content="https://www.bmv-kantinen.de/assets/images/kantine-gutshof.jpg">
  <meta property="og:locale"      content="de_DE">
  <meta property="og:site_name"   content="BMV MenÜdienst">
  <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Startseite","item":"https://www.bmv-kantinen.de/"},{"@type":"ListItem","position":2,"name":"Kantine am Gutshof","item":"https://www.bmv-kantinen.de/kantine-am-gutshof/"}]}</script>
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
      <a href="/kantine-am-gutshof/" class="site-nav__link active">Kantine am Gutshof</a>
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
      <img src="/assets/images/kantine-gutshof.jpg" alt="Kantine am Gutshof Werder" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
      <div class="hero__overlay"></div>
    </div>
    <div class="container">
      <div class="hero__content">
        <div class="hero__badge">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
          Werder (Havel)
        </div>
        <h1 class="hero__title" id="hero-heading">
          Kantine am Gutshof –<br>
          <span style="color:#ff7a1a;">täglich frisch, offen für alle</span>
        </h1>
        <p class="hero__sub">
          Ein echter Mittagstisch in Werder (Havel): täglich frisch zubereitet, herzlich serviert. Für Handwerker, Angestellte, Senioren und alle, die gut essen wollen.
        </p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="#info">Öffnungszeiten & Kontakt</a>
          <a class="btn btn--ghost" href="#menu">Heutiger Speiseplan</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="about-heading">
    <div class="container">
      <div class="section-header fade-up">
        <h2 class="section-title" id="about-heading">Mehr als eine Kantine</h2>
        <p class="section-sub">Ein Ort, an dem man sich kennt, an dem gutes Essen selbstverständlich ist und an dem die Mittagspause tatsächlich Pause ist.</p>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center;margin-top:40px;">
        <div class="fade-up">
          <p style="color:#5a6a82;line-height:1.8;margin-bottom:20px;">Die Kantine am Gutshof ist kein gesichtsloses Unternehmensrestaurant. Es ist ein Treffpunkt für Menschen aus der Region. Hier werden Sie mit Namen begrüßt, hier gibt es kein Einerlei aus der Tiefkühltruhe – hier kochen wir täglich frisch.</p>
          <p style="color:#5a6a82;line-height:1.8;margin-bottom:20px;">Ob Sie im Gewerbegebiet arbeiten, in der Nähe wohnen oder einfach auf der Durchfahrt einen warmen Teller wünschen – Sie sind herzlich willkommen. Keine Mitgliedschaft, keine Anmeldung, keine versteckten Kosten.</p>
          <p style="color:#5a6a82;line-height:1.8;">Das Tagesmenü wechselt täglich und bietet immer mindestens ein Fleischgericht oder Fisch sowie eine vegetarische Alternative. Dazu Beilage, Salat und oft auch ein Dessert zum fairen Preis.</p>
        </div>
        <div class="fade-up" style="transition-delay:0.1s;">
          <img src="/assets/images/kantine-gutshof.jpg" alt="Innenraum Kantine am Gutshof" style="border-radius:16px;width:100%;display:block;box-shadow:0 8px 20px rgba(11,42,91,0.1);">
        </div>
      </div>
    </div>
  </section>

  <section class="section" id="info" aria-labelledby="info-heading">
    <div class="container">
      <div class="section-header fade-up">
        <h2 class="section-title" id="info-heading">Öffnungszeiten & Standort</h2>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-top:40px;">
        <div class="fade-up" style="padding:32px;background:#f8fafc;border-radius:16px;border-left:4px solid #D95A00;">
          <h3 style="color:#0B2A5B;margin-bottom:16px;font-size:1.1rem;font-weight:700;">⏰ Öffnungszeiten</h3>
          <p style="color:#5a6a82;margin-bottom:12px;"><strong style="color:#0B2A5B;">Montag bis Freitag</strong><br>07:00 – 15:00 Uhr</p>
          <p style="color:#5a6a82;font-size:0.9rem;">An Feiertagen und Wochenenden geschlossen</p>
        </div>
        <div class="fade-up" style="padding:32px;background:#f8fafc;border-radius:16px;border-left:4px solid #D95A00;transition-delay:0.1s;">
          <h3 style="color:#0B2A5B;margin-bottom:16px;font-size:1.1rem;font-weight:700;">📍 Adresse</h3>
          <p style="color:#5a6a82;margin-bottom:12px;"><strong style="color:#0B2A5B;">Am Gutshof 6</strong><br>14542 Werder (Havel)<br>Brandenburg</p>
          <p style="color:#5a6a82;font-size:0.9rem;">Kostenlose Parkplätze direkt vor Ort</p>
        </div>
        <div class="fade-up" style="padding:32px;background:#f8fafc;border-radius:16px;border-left:4px solid #D95A00;transition-delay:0.2s;">
          <h3 style="color:#0B2A5B;margin-bottom:16px;font-size:1.1rem;font-weight:700;">📞 Kontakt</h3>
          <p style="color:#5a6a82;margin-bottom:12px;"><a href="tel:<?= BMV_TEL ?>" style="color:#1046a0;text-decoration:none;font-weight:600;"><?= BMV_TEL_DISPLAY ?></a></p>
          <p style="color:#5a6a82;"><a href="mailto:<?= BMV_EMAIL ?>" style="color:#1046a0;text-decoration:none;font-weight:600;"><?= BMV_EMAIL ?></a></p>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" id="menu" aria-labelledby="menu-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <h2 class="section-title" id="menu-heading">Tägliches Angebot</h2>
        <p class="section-sub">Unser Menü wechselt täglich. Es gibt immer mindestens zwei Optionen – mit und ohne Fleisch.</p>
      </div>
      <div style="margin-top:40px;">
        <div class="fade-up" style="padding:28px;background:#fff;border-radius:12px;border-left:4px solid #D95A00;box-shadow:0 2px 6px rgba(11,42,91,0.07);margin-bottom:20px;">
          <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">Hauptgericht (Fleisch/Fisch)</h3>
          <p style="color:#5a6a82;">Wechselndes Angebot – z.B. Schnitzel, Rouladen, Hühnchen, Fischfilet</p>
        </div>
        <div class="fade-up" style="padding:28px;background:#fff;border-radius:12px;border-left:4px solid #D95A00;box-shadow:0 2px 6px rgba(11,42,91,0.07);margin-bottom:20px;">
          <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">Vegetarisches Gericht</h3>
          <p style="color:#5a6a82;">Immer vorhanden – Gemüseauflauf, Kartoffelpuffer, Linsensuppe, oder ähnliches</p>
        </div>
        <div class="fade-up" style="padding:28px;background:#fff;border-radius:12px;border-left:4px solid #D95A00;box-shadow:0 2px 6px rgba(11,42,91,0.07);">
          <h3 style="color:#0B2A5B;margin-bottom:8px;font-weight:700;">Beilage & Salat</h3>
          <p style="color:#5a6a82;">Kartoffeln, Reis oder Nudeln + frischer Salat + optional Dessert</p>
        </div>
      </div>
      <div style="margin-top:32px;text-align:center;">
        <p style="color:#5a6a82;margin-bottom:20px;">Den aktuellen Speiseplan finden Sie online oder fragen Sie beim Besuch.</p>
        <a class="btn btn--secondary" href="/speiseplan/">Speiseplan ansehen</a>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="faq-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <h2 class="section-title" id="faq-heading">Häufige Fragen</h2>
      </div>
      <div style="margin-top:40px;">
        <div class="fade-up" style="padding:24px 0;border-bottom:1px solid #e4edf8;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-weight:700;">Kann jeder in die Kantine?</h3>
          <p style="color:#5a6a82;line-height:1.6;">Ja! Die Kantine ist für alle offen – Handwerker, Angestellte, Senioren, Anwohner und alle, die gut essen möchten. Keine Anmeldung erforderlich, einfach vorbeikommen.</p>
        </div>
        <div class="fade-up" style="padding:24px 0;border-bottom:1px solid #e4edf8;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-weight:700;">Wie viel kostet ein Mittagessen?</h3>
          <p style="color:#5a6a82;line-height:1.6;">Die Preise sind sehr fair – meist zwischen 6,50 € und 8,00 € für ein vollständiges Menü mit Hauptgericht, Beilage und Salat. Kein versteckter Aufpreis.</p>
        </div>
        <div class="fade-up" style="padding:24px 0;border-bottom:1px solid #e4edf8;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-weight:700;">Kann man reservieren?</h3>
          <p style="color:#5a6a82;line-height:1.6;">Für kleine Gruppen können Sie gerne anrufen und reservieren. Normalerweise kommen Sie einfach rein und setzen sich – meist gibt es einen Platz.</p>
        </div>
        <div class="fade-up" style="padding:24px 0;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-weight:700;">Wird auf Diäten/Allergien Rücksicht genommen?</h3>
          <p style="color:#5a6a82;line-height:1.6;">Ja. Teilen Sie dem Personal einfach mit, wenn Sie Allergien oder besondere Wünsche haben. Wir versuchen, Ihnen zu helfen.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="cta-heading">
    <div class="container">
      <div style="text-align:center;">
        <h2 class="section-title" id="cta-heading">Besuchen Sie uns – einfach vorbeikommen</h2>
        <p class="section-sub" style="max-width:600px;margin:0 auto;">Mo–Fr ab 07:00 Uhr. Am Gutshof 6, 14542 Werder (Havel). Kostenlose Parkplätze vorhanden.</p>
        <div style="margin-top:var(--space-8);display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
          <a class="btn btn--primary" href="tel:<?= BMV_TEL ?>"><?= BMV_TEL_DISPLAY ?></a>
          <a class="btn btn--secondary" href="/kontakt/">Kontakt & Anfahrt</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
<script src="/assets/js/hero-upgrade.js" defer></script>
</body>
</html>
