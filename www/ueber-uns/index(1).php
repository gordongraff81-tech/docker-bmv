<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';

$page_title = 'Über uns | BMV Menüdienst in Werder (Havel)';
$meta_description = 'BMV Menüdienst: regional verwurzelt in Werder, täglich im Einsatz für Essen auf Rädern, Kantine und Catering in Potsdam und Umgebung.';
$active_nav = 'ueber-uns';
$canonical = 'https://www.bmv-kantinen.de/ueber-uns/';

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>
<main id="main-content" role="main">
  <section class="page-hero page-hero--sm" aria-labelledby="hero-heading">
    <div class="page-hero__bg">
      <?= bmv_img('/assets/images/ueber-uns-team.jpg', 'Team von BMV Menüdienst in Werder (Havel)', 1600, 900, true, 'page-hero__bg-img') ?>
      <div class="page-hero__overlay" aria-hidden="true"></div>
    </div>
    <div class="container">
      <div class="page-hero__content" data-reveal>
        <span class="page-hero__label">Über BMV</span>
        <h1 class="page-hero__heading" id="hero-heading">Regional verwurzelt. Operativ zuverlässig. Menschlich im Kontakt.</h1>
        <p class="page-hero__lead">
          BMV Menüdienst verbindet frische Küche mit einem Serviceverständnis, das in der Region
          Potsdam, Werder und dem Umland seit Jahren Vertrauen aufbaut.
        </p>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="about-heading">
    <div class="container">
      <div class="alt-block">
        <div data-reveal>
          <span class="eyebrow">Unternehmen</span>
          <h2 class="alt-block__title" id="about-heading">Ein regionaler Partner statt anonymer Versorger.</h2>
          <div class="alt-block__text">
            <p>
              BMV ist in Werder (Havel) zuhause. Von dort aus werden Essen auf Rädern, Catering und
              die Kantine am Gutshof mit einer Haltung betrieben, die Verlässlichkeit vor Lautstärke stellt.
            </p>
            <p>
              Für Kundinnen, Kunden und Angehörige bedeutet das: kurze Wege, klare Zuständigkeiten und
              ein Team, das den Unterschied zwischen Service und bloßer Abwicklung kennt.
            </p>
          </div>
          <div class="alt-block__checks">
            <div class="alt-block__check">Tägliche Frischproduktion in der Region</div>
            <div class="alt-block__check">Persönlicher Kontakt statt Hotline-Struktur</div>
            <div class="alt-block__check">Versorgung für private Haushalte und Unternehmen</div>
          </div>
        </div>

        <div class="contact-card" data-reveal>
          <h3 class="region-card__title">Wofür BMV steht</h3>
          <div class="service-card__checks">
            <div class="service-card__check">Verlässlichkeit im Alltag</div>
            <div class="service-card__check">Frische statt Convenience-Routine</div>
            <div class="service-card__check">Regionale Verantwortung für Potsdam-Mittelmark</div>
            <div class="service-card__check">Pragmatische Abstimmung statt unnötiger Komplexität</div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</body>
</html>
