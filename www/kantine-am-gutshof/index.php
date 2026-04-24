<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';

$page_title = 'Kantine am Gutshof in Werder (Havel) | BMV Menüdienst';
$meta_description = 'Die Kantine am Gutshof in Werder (Havel): frischer Mittagstisch für Handwerk, Büro und Nachbarschaft. Offen, ehrlich, alltagstauglich.';
$active_nav = 'kantine';
$canonical = 'https://www.bmv-kantinen.de/kantine-am-gutshof/';

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>
<main id="main-content" role="main">
  <section class="page-hero page-hero--sm" aria-labelledby="hero-heading">
    <div class="page-hero__bg">
      <?= bmv_img('/assets/images/speiseplan-kueche.jpg', 'Frischer Mittagstisch in der Kantine am Gutshof', 1600, 900, true, 'page-hero__bg-img') ?>
      <div class="page-hero__overlay" aria-hidden="true"></div>
    </div>
    <div class="container">
      <div class="page-hero__content" data-reveal>
        <span class="page-hero__label">Kantine am Gutshof</span>
        <h1 class="page-hero__heading" id="hero-heading">Mittagspause mit echter Küche statt Kantinen-Routine.</h1>
        <p class="page-hero__lead">
          In Werder (Havel) bietet BMV einen offenen Mittagstisch für Handwerk, Büro, Nachbarschaft
          und alle, die mittags ordentlich essen möchten.
        </p>
        <div class="page-hero__actions">
          <a class="btn btn--primary" href="/speiseplan/">Speiseplan ansehen</a>
          <a class="btn btn--ghost" href="/kontakt/">Kontakt & Anfahrt</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="kantine-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Vor Ort in Werder</span>
        <h2 class="section-title" id="kantine-heading">Die Kantine ist offen, unkompliziert und regional verankert.</h2>
      </div>
      <div class="info-grid">
        <article class="info-card" data-reveal>
          <h3 class="info-card__title">Öffnungszeiten</h3>
          <p>Montag bis Freitag von 07:00 bis 15:00 Uhr. Ideal für Frühstücksstart, Mittagspause und kurze Besorgungen vor Ort.</p>
        </article>
        <article class="info-card" data-reveal>
          <h3 class="info-card__title">Zielgruppe</h3>
          <p>Handwerker, Mitarbeitende aus umliegenden Betrieben, Anwohner und Gäste. Kein geschlossenes Betriebsrestaurant.</p>
        </article>
        <article class="info-card" data-reveal>
          <h3 class="info-card__title">Angebot</h3>
          <p>Täglich frische Gerichte mit Fokus auf solide, ehrliche Küche statt austauschbarem Kantinenstandard.</p>
        </article>
        <article class="info-card" data-reveal>
          <h3 class="info-card__title">Standort</h3>
          <p>Am Gutshof 6 in 14542 Werder (Havel). Mit kurzen Wegen und guter Erreichbarkeit für die Region.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="visit-heading">
    <div class="container">
      <div class="alt-block">
        <div data-reveal>
          <span class="eyebrow">Warum hingehen</span>
          <h2 class="alt-block__title" id="visit-heading">Die Kantine wirkt wie ein guter lokaler Treffpunkt, nicht wie ein Pflichtstopp.</h2>
          <div class="alt-block__text">
            <p>
              Wer mittags wenig Zeit hat, braucht Klarheit: gutes Essen, ein verlässlicher Rhythmus
              und eine Umgebung, die angenehm funktioniert. Genau darauf ist die Kantine ausgelegt.
            </p>
            <p>
              Damit wird sie für viele Gäste zu einem festen Teil ihres Arbeits- oder Wochenalltags.
            </p>
          </div>
          <div class="alt-block__checks">
            <div class="alt-block__check">Frisch gekocht und direkt serviert</div>
            <div class="alt-block__check">Offen für Stammgäste und spontane Besuche</div>
            <div class="alt-block__check">In Werder schnell erreichbar</div>
          </div>
        </div>
        <div class="alt-block__visual img-wrap" data-reveal>
          <?= bmv_img('/assets/images/og-image.jpg', 'Frisch angerichtete Mittagsgerichte von BMV', 960, 720) ?>
        </div>
      </div>
    </div>
  </section>

  <section class="section final-cta" aria-labelledby="cta-heading">
    <div class="container container--narrow">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Vorbeikommen</span>
        <h2 class="section-title" id="cta-heading">Wenn Sie in Werder gut zu Mittag essen möchten, ist die Kantine am Gutshof die direkte Adresse.</h2>
        <p class="section-sub">Für Fragen, Gruppen oder Anfahrt einfach kurz melden.</p>
      </div>
      <div class="final-cta__actions" data-reveal>
        <a class="btn btn--primary" href="tel:+4933275745066">Jetzt anrufen</a>
        <a class="btn btn--ghost" href="/kontakt/">Kontakt & Anfahrt</a>
      </div>
    </div>
  </section>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</body>
</html>
