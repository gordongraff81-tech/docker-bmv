<?php
$page_title       = 'Kontakt – BMV-Menüdienst Werder (Havel)';
$meta_description = 'Kontakt zu BMV-Menüdienst: Essen auf Rädern anmelden, Catering anfragen oder die Kantine besuchen. Tel: +49 3327 5745066.';
$active_nav       = 'kontakt';
$canonical        = 'https://www.bmv-kantinen.de/kontakt/';

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
      <a href="/catering/" class="site-nav__link">Catering</a>
      <a href="/ueber-uns/" class="site-nav__link">Über uns</a>
      <a href="/kontakt/" class="site-nav__link active">Kontakt</a>
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
      <img src="/assets/images/hero-bg.jpg" alt="Kontakt BMV-Menüdienst" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
      <div class="hero__overlay"></div>
    </div>
    <div class="container">
      <div class="hero__content">
        <div class="hero__badge">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
          Wir freuen uns auf Sie
        </div>
        <h1 class="hero__title" id="hero-heading">
          Sprechen Sie uns an –<br>
          <span style="color:#ff7a1a;">wir sind für Sie da</span>
        </h1>
        <p class="hero__sub">
          Ob Essen auf Rädern, Catering oder ein Besuch in der Kantine: Kontaktieren Sie das <?= BMV_NAME ?> Team direkt. Wir antworten schnell und persönlich.
        </p>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="kontakt-heading">
    <div class="container">
      <div class="section-header fade-up">
        <h2 class="section-title" id="kontakt-heading">Direkter Kontakt</h2>
        <p class="section-sub">Mehrere Wege – eine Antwort: Rufen Sie an, schreiben Sie eine Mail oder nutzen Sie das Formular. Wir kümmern uns darum.</p>
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;margin-top:40px;">
        <div class="fade-up" style="padding:32px;background:#f8fafc;border-radius:16px;border-left:4px solid #D95A00;">
          <h3 style="color:#0B2A5B;margin-bottom:16px;font-size:1.1rem;font-weight:700;">📞 Telefonisch</h3>
          <p style="color:#5a6a82;margin-bottom:16px;">Mo–Fr 07:00 – 15:00 Uhr</p>
          <a href="tel:<?= BMV_TEL ?>" style="display:inline-block;padding:12px 20px;background:#D95A00;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;"><?= BMV_TEL_DISPLAY ?></a>
        </div>
        <div class="fade-up" style="padding:32px;background:#f8fafc;border-radius:16px;border-left:4px solid #D95A00;transition-delay:0.1s;">
          <h3 style="color:#0B2A5B;margin-bottom:16px;font-size:1.1rem;font-weight:700;">✉️ E-Mail</h3>
          <p style="color:#5a6a82;margin-bottom:16px;">Gerne auch per E-Mail</p>
          <a href="mailto:<?= BMV_EMAIL ?>" style="display:inline-block;padding:12px 20px;background:#1046a0;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;"><?= BMV_EMAIL ?></a>
        </div>
        <div class="fade-up" style="padding:32px;background:#f8fafc;border-radius:16px;border-left:4px solid #D95A00;transition-delay:0.2s;">
          <h3 style="color:#0B2A5B;margin-bottom:16px;font-size:1.1rem;font-weight:700;">📍 Besuchen Sie uns</h3>
          <p style="color:#5a6a82;margin-bottom:16px;">Am Gutshof 6, 14542 Werder (Havel)</p>
          <a href="/kontakt/#map" style="display:inline-block;padding:12px 20px;background:#0B2A5B;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;">Zur Karte</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="form-heading">
    <div class="container">
      <div style="max-width:700px;margin:0 auto;">
        <div class="section-header fade-up">
          <h2 class="section-title" id="form-heading">Anfrage-Formular</h2>
          <p class="section-sub">Schreiben Sie uns – wir melden uns schnell bei Ihnen.</p>
        </div>
        <form method="post" action="/api/kontakt.php" novalidate style="margin-top:40px;" class="fade-up">
          <div style="margin-bottom:24px;">
            <label style="display:block;margin-bottom:8px;color:#0B2A5B;font-weight:600;" for="name">Ihr Name *</label>
            <input type="text" name="name" id="name" required style="width:100%;padding:12px 16px;border:1px solid #dde4ef;border-radius:8px;font-family:inherit;font-size:1rem;" placeholder="Max Mustermann">
          </div>
          <div style="margin-bottom:24px;">
            <label style="display:block;margin-bottom:8px;color:#0B2A5B;font-weight:600;" for="email">E-Mail-Adresse *</label>
            <input type="email" name="email" id="email" required style="width:100%;padding:12px 16px;border:1px solid #dde4ef;border-radius:8px;font-family:inherit;font-size:1rem;" placeholder="max@beispiel.de">
          </div>
          <div style="margin-bottom:24px;">
            <label style="display:block;margin-bottom:8px;color:#0B2A5B;font-weight:600;" for="phone">Telefonnummer</label>
            <input type="tel" name="phone" id="phone" style="width:100%;padding:12px 16px;border:1px solid #dde4ef;border-radius:8px;font-family:inherit;font-size:1rem;" placeholder="+49 ...">
          </div>
          <div style="margin-bottom:24px;">
            <label style="display:block;margin-bottom:8px;color:#0B2A5B;font-weight:600;" for="subject">Anliegen *</label>
            <select name="subject" id="subject" required style="width:100%;padding:12px 16px;border:1px solid #dde4ef;border-radius:8px;font-family:inherit;font-size:1rem;">
              <option value="">Bitte wählen …</option>
              <option value="essen-auf-raedern">Essen auf Rädern anfragen</option>
              <option value="catering">Catering-Anfrage</option>
              <option value="kantine">Frage zur Kantine</option>
              <option value="speiseplan">Speiseplan-Frage</option>
              <option value="sonstiges">Sonstiges</option>
            </select>
          </div>
          <div style="margin-bottom:24px;">
            <label style="display:block;margin-bottom:8px;color:#0B2A5B;font-weight:600;" for="message">Nachricht *</label>
            <textarea name="message" id="message" required rows="6" style="width:100%;padding:12px 16px;border:1px solid #dde4ef;border-radius:8px;font-family:inherit;font-size:1rem;resize:vertical;" placeholder="Ihre Nachricht …"></textarea>
          </div>
          <button type="submit" style="display:inline-block;padding:14px 32px;background:#D95A00;color:#fff;border:none;border-radius:8px;font-family:inherit;font-size:1rem;font-weight:600;cursor:pointer;transition:background 0.2s;">Nachricht senden</button>
        </form>
      </div>
    </div>
  </section>

  <section class="section" id="map" aria-labelledby="map-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <h2 class="section-title" id="map-heading">Anfahrt & Standort</h2>
        <p class="section-sub">Unser Standort Am Gutshof 6 liegt im Gewerbegebiet Werder (Havel) und ist gut erreichbar.</p>
      </div>
      <div style="margin-top:40px;border-radius:16px;overflow:hidden;box-shadow:0 8px 20px rgba(11,42,91,0.1);" class="fade-up">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2432.797!2d12.9319!3d52.3884!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47a8ae1d4a8ae1d5%3A0xd4d4d4d4d4d4d4d4!2sAm%20Gutshof%206%2C%2014542%20Werder%20(Havel)!5e0!3m2!1sde!2sde!4v1712674800000!5m2!1sde!2sde" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?= BMV_NAME ?> – Am Gutshof 6, 14542 Werder (Havel)"></iframe>
      </div>
      <div style="margin-top:32px;padding:32px;background:#f8fafc;border-radius:16px;border-left:4px solid #D95A00;" class="fade-up">
        <h3 style="color:#0B2A5B;margin-bottom:12px;font-weight:700;">Anfahrtsinformationen</h3>
        <ul style="list-style:none;padding:0;color:#5a6a82;line-height:1.8;">
          <li>📍 <strong style="color:#0B2A5B;">Adresse:</strong> Am Gutshof 6, 14542 Werder (Havel)</li>
          <li>🚗 <strong style="color:#0B2A5B;">Mit dem Auto:</strong> Von der A10/E55 (Autobahnabfahrt Werder) etwa 5 Minuten</li>
          <li>🚌 <strong style="color:#0B2A5B;">Öffentliche Verkehrsmittel:</strong> Bushaltestellen in Werder erreichbar</li>
          <li>🅿️ <strong style="color:#0B2A5B;">Parkplätze:</strong> Kostenlos direkt vor Ort vorhanden</li>
        </ul>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="times-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <h2 class="section-title" id="times-heading">Erreichbarkeit</h2>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:40px;">
        <div class="fade-up" style="padding:28px;background:#fff;border-radius:12px;border-top:4px solid #D95A00;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-weight:700;">📞 Telefon</h3>
          <p style="color:#5a6a82;"><strong>Mo–Fr:</strong> 07:00 – 15:00 Uhr</p>
          <p style="color:#5a6a82;margin-top:8px;font-size:0.9rem;">Außerhalb dieser Zeiten können Sie eine Nachricht hinterlassen.</p>
        </div>
        <div class="fade-up" style="padding:28px;background:#fff;border-radius:12px;border-top:4px solid #D95A00;transition-delay:0.1s;">
          <h3 style="color:#0B2A5B;margin-bottom:12px;font-weight:700;">🍽️ Kantine</h3>
          <p style="color:#5a6a82;"><strong>Mo–Fr:</strong> 07:00 – 15:00 Uhr</p>
          <p style="color:#5a6a82;margin-top:8px;font-size:0.9rem;">Einfach vorbeikommen – keine Anmeldung nötig!</p>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
<script src="/assets/js/hero-upgrade.js" defer></script>
</body>
</html>
