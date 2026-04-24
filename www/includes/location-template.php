<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/location-pages.php';

$locationSlug = $locationSlug ?? '';
$locationPages = bmv_location_pages();

if (!isset($locationPages[$locationSlug])) {
    http_response_code(404);
    echo 'Standortseite nicht gefunden.';
    return;
}

$locationPage = $locationPages[$locationSlug];
$page_title = $locationPage['meta_title'];
$meta_description = $locationPage['meta_description'];
$active_nav = 'ears';
$canonical = 'https://www.bmv-kantinen.de' . $locationPage['canonical_path'];

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>
<main id="main-content" role="main">
  <section class="page-hero page-hero--sm" aria-labelledby="hero-heading">
    <div class="page-hero__bg">
      <?= bmv_img($locationPage['image'], 'Essen auf Rädern in ' . $locationPage['name'], 1600, 900, true, 'page-hero__bg-img') ?>
      <div class="page-hero__overlay" aria-hidden="true"></div>
    </div>
    <div class="container">
      <div class="page-hero__content" data-reveal>
        <span class="page-hero__label"><?= htmlspecialchars($locationPage['hero_badge']) ?></span>
        <h1 class="page-hero__heading" id="hero-heading"><?= htmlspecialchars($locationPage['hero_heading']) ?></h1>
        <p class="page-hero__lead"><?= htmlspecialchars($locationPage['hero_lead']) ?></p>
        <div class="page-hero__actions">
          <a class="btn btn--primary" href="/kontakt/">Lieferbarkeit anfragen</a>
          <a class="btn btn--ghost" href="/speiseplan/">Speiseplan ansehen</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="location-content-heading">
    <div class="container">
      <div class="alt-block">
        <div data-reveal>
          <span class="eyebrow">Standortfokus</span>
          <h2 class="alt-block__title" id="location-content-heading"><?= htmlspecialchars($locationPage['section_title']) ?></h2>
          <div class="alt-block__text">
            <p><?= htmlspecialchars($locationPage['section_intro']) ?></p>
          </div>
          <div class="alt-block__checks">
            <?php foreach ($locationPage['coverage'] as $coveragePoint): ?>
              <div class="alt-block__check"><?= htmlspecialchars($coveragePoint) ?></div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="contact-card" data-reveal>
          <h3 class="region-card__title">Warum diese Seite wichtig ist</h3>
          <p>Lokale Versorgung wird in Suchmaschinen meist dann stark, wenn Angebot, Region und konkrete Erwartung zusammen gedacht werden.</p>
          <div class="service-card__checks">
            <?php foreach ($locationPage['highlights'] as $highlight): ?>
              <div class="service-card__check"><?= htmlspecialchars($highlight) ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section final-cta" aria-labelledby="cta-heading">
    <div class="container container--narrow">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Nächster Schritt</span>
        <h2 class="section-title" id="cta-heading">Für <?= htmlspecialchars($locationPage['name']) ?> klären wir Lieferbarkeit und Start am schnellsten direkt im Gespräch.</h2>
        <p class="section-sub">Ein kurzer Kontakt reicht, um Gebiet, Bedarf und den passenden Startpunkt realistisch zu prüfen.</p>
      </div>
      <div class="final-cta__actions" data-reveal>
        <a class="btn btn--primary" href="/kontakt/">Kontakt aufnehmen</a>
        <a class="btn btn--ghost" href="tel:+4933275745066">+49 3327 5745066</a>
      </div>
    </div>
  </section>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</body>
</html>
