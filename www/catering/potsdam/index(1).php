<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';

$page_title       = 'Catering Potsdam | BMV Menüdienst';
$meta_description = 'Firmen-Catering für Potsdam: Arbeitslunch, Meetings und Veranstaltungen. Frisch aus Werder (Havel), pünktlich geliefert. Jetzt anfragen.';
$active_nav       = 'catering';
$canonical        = 'https://www.bmv-kantinen.de/catering/potsdam/';

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>
<main id="main-content" role="main">

  <section class="page-hero page-hero--sm" aria-labelledby="hero-heading">
    <div class="page-hero__bg">
      <?= bmv_img('/assets/images/catering-setup.jpg', 'Catering Potsdam durch BMV Menüdienst', 1600, 900, true, 'page-hero__bg-img') ?>
      <div class="page-hero__overlay" aria-hidden="true"></div>
    </div>
    <div class="container">
      <div class="page-hero__content" data-reveal>
        <span class="page-hero__label">Catering Potsdam</span>
        <h1 class="page-hero__heading" id="hero-heading">Von Babelsberg bis Bornstedt: Catering für Potsdam.</h1>
        <p class="page-hero__lead">
          BMV liefert Firmenessen, Meeting-Catering und Veranstaltungsversorgung nach Potsdam.
          Frisch gekocht am Morgen, pünktlich bei Ihnen.
        </p>
        <div class="page-hero__actions">
          <a class="btn btn--primary" href="/kontakt/?betreff=catering">Catering anfragen</a>
          <a class="btn btn--ghost"   href="/catering/">Alle Catering-Infos</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="leistungen-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Was wir liefern</span>
        <h2 class="section-title" id="leistungen-heading">Catering für jeden Anlass in Potsdam.</h2>
      </div>
      <div class="service-grid">
        <article class="service-card" data-reveal>
          <h3 class="service-card__title">Arbeitslunch &amp; Meeting-Catering</h3>
          <p>Warmes Mittagessen oder belegte Platten direkt ins Büro. Planbar, pünktlich und ohne eigenen Aufwand.</p>
          <div class="service-card__checks">
            <div class="service-card__check">Tages- und Wochenbestellungen möglich</div>
            <div class="service-card__check">Lieferung direkt ins Büro oder in den Konferenzraum</div>
          </div>
        </article>
        <article class="service-card" data-reveal>
          <h3 class="service-card__title">Betriebsfeiern &amp; Veranstaltungen</h3>
          <p>Sommerfeier, Jubiläum oder Kundenevent: Wir liefern frisch zubereitete Menüs, abgestimmt auf Ihre Teilnehmerzahl.</p>
          <div class="service-card__checks">
            <div class="service-card__check">Individuelle Menüabstimmung vorab</div>
            <div class="service-card__check">Auch kurzfristige Termine nach Absprache</div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="section final-cta" aria-labelledby="cta-heading">
    <div class="container container--narrow">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Jetzt anfragen</span>
        <h2 class="section-title" id="cta-heading">Catering für Potsdam direkt anfragen.</h2>
        <p class="section-sub">Beschreiben Sie kurz Ihren Anlass und wir melden uns mit einem konkreten Angebot.</p>
      </div>
      <div class="final-cta__actions" data-reveal>
        <a class="btn btn--primary" href="/kontakt/?betreff=catering">Anfrage senden</a>
        <a class="btn btn--ghost"   href="tel:+4933275745066">+49 3327 5745066</a>
      </div>
    </div>
  </section>

</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
