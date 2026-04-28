<?php
// Page variables
$page_title       = 'Essen auf Rädern Potsdam & Werder – täglich frisch geliefert | BMV-Menüdienst';
$meta_description = 'Essen auf Rädern in Potsdam und Werder (Havel): Täglich frisch zubereitete Mittagsmahlzeiten für Senioren. Mo–So, Pflegekassen-Abrechnung, keine Mindestlaufzeit.';
$active_nav       = 'ears';
$canonical        = 'https://www.bmv-kantinen.de/essen-auf-raedern/';

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
      <img src="/assets/images/essen-auf-raedern-lieferung.jpg" alt="Essen auf Rädern Lieferung" loading="eager" fetchpriority="high" width="1400" height="800" decoding="async">
      <div class="hero__overlay"></div>
    </div>
    <div class="container">
      <div class="hero__content">
        <div class="hero__badge">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
          Potsdam & Werder (Havel)
        </div>
        <h1 class="hero__title" id="hero-heading">
          Essen auf Rädern –<br>
          <span style="color:#ff7a1a;">täglich frisch an Ihre Tür</span>
        </h1>
        <p class="hero__sub">
          Warmes Mittagessen pünktlich geliefert – vom Montag bis Sonntag, für Senioren, Pflegebedürftige und alle, die sich täglich gut versorgen möchten.
        </p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="/kontakt/">Kostenlos anfragen</a>
          <a class="btn btn--ghost" href="#services">Mehr erfahren</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="services-heading">
    <div class="container">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">Unsere Leistung</div>
        <h2 class="section-title" id="services-heading">Essen auf Rädern in Potsdam &amp; Werder</h2>
        <p class="section-sub">Täglich frisch zubereitet, pünktlich angeliefert – mit voller Flexibilität und ohne Mindestlaufzeit.</p>
      </div>
      <div class="services-grid">
        <article class="service-card fade-up">
          <div class="service-card__img img-wrap">
            <img src="/assets/images/potsdam-lieferung.jpg" alt="Lieferfahrzeug BMV-Menüdienst" loading="lazy" decoding="async" width="400" height="200">
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Täglich Mo–So</h3>
            <p class="service-card__text">Mittagessen wird auch am Wochenende und an Feiertagen geliefert. Sie bestimmen, an welchen Tagen Sie Essen wünschen – flexibel und jederzeit anpassbar.</p>
            <div class="service-card__checks">
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Lieferung auch Sa &amp; So</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Pünktlich zwischen 11–13 Uhr</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Persönlicher Kontakt zum Fahrer</div>
            </div>
          </div>
        </article>

        <article class="service-card fade-up" style="transition-delay:.1s">
          <div class="service-card__img img-wrap">
            <img src="/assets/images/speiseplan-kueche.jpg" alt="Frische Zubereitung in der Küche" loading="lazy" decoding="async" width="400" height="200">
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">4 Menüs täglich</h3>
            <p class="service-card__text">Täglich vier verschiedene Menüs zur Auswahl: Vollkost, Leichte Kost, Premium und unser Tagesmenü. Alle frisch gekocht, keine Tiefkühlware.</p>
            <div class="service-card__checks">
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Schonkost &amp; vegetarische Optionen</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Allergien &amp; Unverträglichkeiten berücksichtigt</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Zusatzkarte: Dessert, Rohkost, Salat</div>
            </div>
          </div>
        </article>

        <article class="service-card fade-up" style="transition-delay:.2s">
          <div class="service-card__img img-wrap">
            <img src="/assets/images/menue-auswahl.jpg" alt="Menüplan und Bestellung" loading="lazy" decoding="async" width="400" height="200">
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Flexibel &amp; einfach</h3>
            <p class="service-card__text">Keine Mindestlaufzeit, keine versteckten Gebühren. Sie bestellen für die Woche, den Monat oder so lange Sie möchten – jederzeit änderbar.</p>
            <div class="service-card__checks">
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Pflegekassen-Abrechnung möglich</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Bestellung per Telefon oder online</div>
              <div class="service-card__check"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Speiseplan 4 Wochen voraus</div>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="gebiete-heading">
    <div class="container">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">Lieferbereiche</div>
        <h2 class="section-title" id="gebiete-heading">Wir liefern in Ihrem Gebiet</h2>
        <p class="section-sub">Essen auf Rädern in ganz Potsdam, Werder (Havel) und zahlreichen Gemeinden in Potsdam-Mittelmark.</p>
      </div>
      <div class="services-grid">
        <div class="service-card fade-up">
          <div class="service-card__body">
            <h3 class="service-card__title">Potsdam</h3>
            <p class="service-card__text">Alle Stadtteile von Potsdam werden von uns regelmäßig beliefert – von Babelsberg bis zur Innenstadt.</p>
            <a class="service-card__link" href="/essen-auf-raedern/potsdam/">Mehr zu Potsdam<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></a>
          </div>
        </div>
        <div class="service-card fade-up" style="transition-delay:.1s">
          <div class="service-card__body">
            <h3 class="service-card__title">Werder (Havel)</h3>
            <p class="service-card__text">Von unserem Standort aus versorgen wir Werder und die direkt angrenzenden Ortschaften täglich.</p>
            <a class="service-card__link" href="/essen-auf-raedern/werder-havel/">Mehr zu Werder<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></a>
          </div>
        </div>
        <div class="service-card fade-up" style="transition-delay:.2s">
          <div class="service-card__body">
            <h3 class="service-card__title">Umland &amp; Gemeinden</h3>
            <p class="service-card__text">Auch in vielen Gemeinden von Potsdam-Mittelmark bieten wir Essen auf Rädern an. Fragen Sie uns!</p>
            <a class="service-card__link" href="/essen-auf-raedern/umland/">Mehr zum Umland<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="faq-heading">
    <div class="container container--narrow">
      <div class="section-header fade-up">
        <div class="section-header__eyebrow">Häufige Fragen</div>
        <h2 class="section-title" id="faq-heading">Alles zum Thema Essen auf Rädern</h2>
      </div>
      <div class="faq-list" role="list">
        <div class="faq-item fade-up" role="listitem">
          <button class="faq-item__question" aria-expanded="false">Wie wird das Essen angeliefert?</button>
          <div class="faq-item__answer" role="region">
            <p>Das Essen kommt in isolierten Behältern, um die Wärme zu bewahren. Es wird täglich zwischen 11:00 und 13:00 Uhr angeliefert. Sie müssen nicht anwesend sein – der Fahrer kann die Mahlzeit auch in einer Transportbox vor der Tür deponieren.</p>
          </div>
        </div>
        <div class="faq-item fade-up" role="listitem">
          <button class="faq-item__question" aria-expanded="false">Kann die Pflegekasse zahlen?</button>
          <div class="faq-item__answer" role="region">
            <p>Ja. Essen auf Rädern kann unter dem Pflegebedarf „Entlastung von Pflegepersonen" über die Pflegekasse abgerechnet werden. Wir helfen Ihnen gerne bei der Antragstellung und begleiten den Prozess.</p>
          </div>
        </div>
        <div class="faq-item fade-up" role="listitem">
          <button class="faq-item__question" aria-expanded="false">Wie lange bin ich gebunden?</button>
          <div class="faq-item__answer" role="region">
            <p>Gar nicht. Es gibt keine Mindestlaufzeit. Sie können Ihre Bestellung jederzeit beenden oder anpassen. Auch einzelne Tage können Sie überspringen – völlige Flexibilität.</p>
          </div>
        </div>
        <div class="faq-item fade-up" role="listitem">
          <button class="faq-item__question" aria-expanded="false">Was passiert bei Allergien oder Unverträglichkeiten?</button>
          <div class="faq-item__answer" role="region">
            <p>Wir nehmen Allergien und Unverträglichkeiten ernst. Bei der Bestellung geben Sie alle Einschränkungen an, und unsere Küche berücksichtigt diese. Alle Menüs sind entsprechend gekennzeichnet.</p>
          </div>
        </div>
        <div class="faq-item fade-up" role="listitem">
          <button class="faq-item__question" aria-expanded="false">Kann ich einen Tag aussetzen?</button>
          <div class="faq-item__answer" role="region">
            <p>Selbstverständlich. Sie können beliebig viele Tage auswählen oder aussparen – zum Beispiel wenn Sie im Urlaub sind oder Besuch haben, der für Sie kocht.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="cta-heading">
    <div class="container">
      <div style="text-align:center;">
        <h2 class="section-title" id="cta-heading">Jetzt Essen auf Rädern anfragen</h2>
        <p class="section-sub" style="max-width:600px;margin:0 auto;">Kostenlos beraten lassen, Speiseplan anschauen, gleich starten. Rufen Sie uns an oder füllen Sie das Online-Formular aus.</p>
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
