<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/location-pages.php';

$page_title = 'HTML-Sitemap | BMV Menüdienst';
$meta_description = 'Alle indexierbaren Seiten von BMV Menüdienst im Überblick: Leistungen, Standortseiten und XML-Sitemap-Ressourcen.';
$active_nav = 'home';
$canonical = 'https://www.bmv-kantinen.de/sitemap/';
$locationPages = bmv_location_pages();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>
<main id="main-content" role="main">
  <section class="page-hero page-hero--sm" aria-labelledby="hero-heading">
    <div class="page-hero__bg">
      <?= bmv_img('/assets/images/og-image.jpg', 'HTML-Sitemap von BMV Menüdienst', 1600, 900, true, 'page-hero__bg-img') ?>
      <div class="page-hero__overlay" aria-hidden="true"></div>
    </div>
    <div class="container">
      <div class="page-hero__content" data-reveal>
        <span class="page-hero__label">HTML-Sitemap</span>
        <h1 class="page-hero__heading" id="hero-heading">Alle indexierbaren Seiten in einer klaren Übersicht.</h1>
        <p class="page-hero__lead">Diese HTML-Sitemap ist für Nutzer und Crawler gedacht. XML-Sitemaps für Google Search Console sind separat verfügbar.</p>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="main-pages-heading">
    <div class="container">
      <div class="info-grid">
        <article class="info-card" data-reveal>
          <h2 class="info-card__title" id="main-pages-heading">Hauptseiten</h2>
          <div class="footer-col__links">
            <a class="footer-col__link" href="/">Startseite</a>
            <a class="footer-col__link" href="/essen-auf-raedern/">Essen auf Rädern</a>
            <a class="footer-col__link" href="/catering/">Catering</a>
            <a class="footer-col__link" href="/kantine-am-gutshof/">Kantine am Gutshof</a>
            <a class="footer-col__link" href="/speiseplan/">Speiseplan</a>
            <a class="footer-col__link" href="/kontakt/">Kontakt</a>
            <a class="footer-col__link" href="/ueber-uns/">Über uns</a>
          </div>
        </article>

        <article class="info-card" data-reveal>
          <h2 class="info-card__title">XML-Sitemaps</h2>
          <div class="footer-col__links">
            <a class="footer-col__link" href="/sitemap.xml">sitemap.xml</a>
            <a class="footer-col__link" href="/pages-sitemap.xml">pages-sitemap.xml</a>
            <a class="footer-col__link" href="/services-sitemap.xml">services-sitemap.xml</a>
            <a class="footer-col__link" href="/locations-sitemap.xml">locations-sitemap.xml</a>
            <a class="footer-col__link" href="/image-sitemap.xml">image-sitemap.xml</a>
            <a class="footer-col__link" href="/blog-sitemap.xml">blog-sitemap.xml</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="locations-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Standortseiten</span>
        <h2 class="section-title" id="locations-heading">Lokale Landingpages für Essen auf Rädern</h2>
      </div>
      <div class="region-grid">
        <?php foreach ($locationPages as $locationPage): ?>
          <article class="region-card" data-reveal>
            <div class="service-card__body">
              <h3 class="region-card__title"><?= htmlspecialchars($locationPage['name']) ?></h3>
              <p><?= htmlspecialchars($locationPage['meta_description']) ?></p>
              <a class="service-card__link" href="<?= htmlspecialchars($locationPage['canonical_path']) ?>">Seite öffnen</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</body>
</html>
