<?php
require_once __DIR__ . '/slug-router.php';
require_once __DIR__ . '/includes/helpers.php';

$page_title = 'BMV Menüdienst | Essen auf Rädern, Kantine und Catering in Potsdam & Werder';
$meta_description = 'Frisch gekochte Menüs für Zuhause, Unternehmen und Events. Essen auf Rädern, Betriebsgastronomie und Catering aus Werder für Potsdam und das Umland.';
$active_nav = 'home';
$canonical = 'https://www.bmv-kantinen.de/';
$page_scripts = ['/assets/js/index.js'];

$currentKW = (int)date('W');
$currentYear = (int)date('Y');
$kwString = str_pad((string)$currentKW, 2, '0', STR_PAD_LEFT);

$preview = null;
$newFile = $_SERVER['DOCUMENT_ROOT'] . "/data/speiseplaene/essen_auf_raedern-{$currentYear}-KW{$kwString}.json";
$oldFile = $_SERVER['DOCUMENT_ROOT'] . "/data/speiseplaene/{$currentYear}-KW{$kwString}.json";

$newData = loadJsonFile($newFile);
if ($newData && isset($newData['data'])) {
    $preview = $newData['data'];
}

if ($preview === null) {
    $oldData = loadJsonFile($oldFile);
    if ($oldData && !empty($oldData['days'])) {
        $preview = $oldData['days'];
    }
}

if ($preview === null) {
    $files = glob($_SERVER['DOCUMENT_ROOT'] . '/data/speiseplaene/*.json') ?: [];
    rsort($files);
    foreach ($files as $file) {
        $fallback = loadJsonFile($file);
        if ($fallback && isset($fallback['data'])) {
            $preview = $fallback['data'];
            break;
        }
        if ($fallback && !empty($fallback['days'])) {
            $preview = $fallback['days'];
            break;
        }
    }
}

$dayNames = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];

function bmv_preview_title(?array $entry): ?string
{
    if (!$entry) {
        return null;
    }

    if (isset($entry['menus'][0]['title']) && is_string($entry['menus'][0]['title'])) {
        return $entry['menus'][0]['title'];
    }

    foreach (['vollkost', 'leichte_kost', 'premium', 'tagesmenu'] as $category) {
        if (!empty($entry[$category]['name']) && is_string($entry[$category]['name'])) {
            return $entry[$category]['name'];
        }
    }

    return null;
}

include __DIR__ . '/includes/header.php';
?>
<main id="main-content" role="main">
  <section class="hero hero--home" aria-labelledby="hero-heading">
    <div class="hero__bg">
      <?= bmv_img('/assets/images/essen-auf-raedern-lieferung.jpg', 'BMV Menüdienst liefert frisch gekochte Menüs aus', 1600, 900, true) ?>
      <div class="hero__overlay" aria-hidden="true"></div>
    </div>
    <div class="container">
      <div class="hero__content" data-reveal>
        <span class="hero__badge">BMV Menüdienst aus Werder (Havel)</span>
        <h1 class="hero__title text-balance" id="hero-heading">
          Premium-Betriebsgastronomie mit der Verlässlichkeit des Mittelstands.
        </h1>
        <p class="hero__sub">
          Für Senioren, Unternehmen und Veranstalter: frisch gekochte Menüs, saubere Prozesse,
          pünktliche Lieferung und ein Team, das Verantwortung wirklich ernst nimmt.
        </p>
        <div class="hero__actions">
          <a class="btn btn--primary" href="/kontakt/">Beratung anfragen</a>
          <a class="btn btn--ghost" href="/speiseplan/">Speiseplan ansehen</a>
        </div>
        <div class="hero__trust">
          <span class="hero__trust-item">Mo–So Essen auf Rädern</span>
          <span class="hero__trust-item">Kantine in Werder</span>
          <span class="hero__trust-item">Catering für Firmen & Events</span>
        </div>
      </div>
    </div>
  </section>

  <section class="trust-bar" aria-label="Kennzahlen">
    <div class="container">
      <div class="trust-bar__grid">
        <div class="trust-bar__item" data-reveal>
          <div class="trust-item-number">15+</div>
          <div class="trust-item-label">Jahre operative Erfahrung</div>
        </div>
        <div class="trust-bar__item" data-reveal>
          <div class="trust-item-number">7</div>
          <div class="trust-item-label">Tage pro Woche lieferbar</div>
        </div>
        <div class="trust-bar__item" data-reveal>
          <div class="trust-item-number">4</div>
          <div class="trust-item-label">Menülinien im Speiseplan</div>
        </div>
        <div class="trust-bar__item" data-reveal>
          <div class="trust-item-number">100%</div>
          <div class="trust-item-label">Frisch gekocht statt Convenience</div>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="services-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Unsere Leistungen</span>
        <h2 class="section-title text-balance" id="services-heading">Drei klare Angebote. Ein Qualitätsversprechen.</h2>
        <p class="section-sub">
          BMV ist keine diffuse Sammelmarke, sondern ein operativ eingespielter Partner für
          Versorgung zuhause, Betriebsgastronomie und Event-Catering.
        </p>
      </div>

      <div class="services-grid">
        <article class="service-card" data-reveal>
          <div class="service-card__img img-wrap">
            <?= bmv_img('/assets/images/essen-auf-raedern-lieferung.jpg', 'Fahrer liefert ein warmes Menü von BMV aus', 720, 450, false) ?>
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Essen auf Rädern</h3>
            <p class="service-card__text">
              Täglich warmes Mittagessen für Potsdam, Werder und das Umland. Flexibel bestellbar,
              mit persönlichem Kontakt und Pflegekassen-Know-how.
            </p>
            <div class="service-card__checks">
              <div class="service-card__check">Lieferung auch am Wochenende</div>
              <div class="service-card__check">Klare Menüauswahl inklusive Schonkost</div>
              <div class="service-card__check">Persönlich, planbar und alltagstauglich</div>
            </div>
            <a class="service-card__link" href="/essen-auf-raedern/">Mehr zu Essen auf Rädern</a>
          </div>
        </article>

        <article class="service-card" data-reveal>
          <div class="service-card__img img-wrap">
            <?= bmv_img('/assets/images/speiseplan-kueche.jpg', 'BMV kocht täglich frisch in der eigenen Küche', 720, 450, false) ?>
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Kantine am Gutshof</h3>
            <p class="service-card__text">
              Mittagstisch in Werder (Havel) mit ehrlicher Küche, fairen Preisen und einem Umfeld,
              das eher nach guter Adresse als nach Standard-Kantine wirkt.
            </p>
            <div class="service-card__checks">
              <div class="service-card__check">Mo–Fr von 07:00 bis 15:00 Uhr</div>
              <div class="service-card__check">Frisch gekocht statt aufgewärmt</div>
              <div class="service-card__check">Offen für Handwerk, Büro und Nachbarschaft</div>
            </div>
            <a class="service-card__link" href="/kantine-am-gutshof/">Mehr zur Kantine</a>
          </div>
        </article>

        <article class="service-card" data-reveal>
          <div class="service-card__img img-wrap">
            <?= bmv_img('/assets/images/catering-setup.jpg', 'Catering-Aufbau von BMV für Firmenveranstaltungen', 720, 450, false) ?>
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Catering</h3>
            <p class="service-card__text">
              Für Firmenlunch, Betriebsfeier oder Meeting. Planung, Aufbau und Lieferung kommen aus
              einer Hand, damit auf Kundenseite kein Koordinationschaos entsteht.
            </p>
            <div class="service-card__checks">
              <div class="service-card__check">Angebote für kleine und große Formate</div>
              <div class="service-card__check">Professionell in Abstimmung und Timing</div>
              <div class="service-card__check">Regionaler Einsatz mit kurzen Wegen</div>
            </div>
            <a class="service-card__link" href="/catering/">Mehr zum Catering</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="why-heading">
    <div class="container">
      <div class="alt-block">
        <div data-reveal>
          <span class="eyebrow">Warum BMV</span>
          <h2 class="alt-block__title">Warm, verlässlich, professionell. Ohne Großküchen-Routinegefühl.</h2>
          <div class="alt-block__text">
            <p>
              Entscheider brauchen keine bunte Food-Sprache, sondern Klarheit: Wer kocht? Wer liefert?
              Wer reagiert, wenn sich etwas ändert? BMV beantwortet genau diese Fragen sauber.
            </p>
            <p>
              Das Ergebnis ist eine Marke, die warm wirkt, aber operativ ernst genommen wird. Genau dort
              liegt das Potenzial dieser Website und genau dort setze ich das Redesign an.
            </p>
          </div>
          <div class="alt-block__checks">
            <div class="alt-block__check">Klare Kontaktwege statt anonymer Plattformlogik</div>
            <div class="alt-block__check">Transparente Menü- und Leistungsstruktur</div>
            <div class="alt-block__check">Regionale Kompetenz für Potsdam, Werder und Umfeld</div>
          </div>
        </div>

        <div class="alt-block__visual img-wrap" data-reveal>
          <?= bmv_img('/assets/images/og-image.jpg', 'BMV Menüdienst richtet frisch gekochte Menüs professionell an', 960, 720, false) ?>
        </div>
      </div>
    </div>
  </section>

  <section class="menu-preview section" aria-labelledby="preview-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Aktueller Speiseplan</span>
        <h2 class="section-title" id="preview-heading">Diese Woche im Überblick</h2>
        <p class="section-sub">
          Ein schneller Blick auf die laufende Woche. Für den vollen Plan mit weiteren Gerichten und
          Zusatzkarte geht es direkt in den Speiseplan.
        </p>
      </div>

      <div class="menu-scroll">
        <?php for ($i = 0; $i < 5; $i++): ?>
          <?php $title = bmv_preview_title($preview[$i] ?? null); ?>
          <div class="menu-day-card" data-reveal role="button" tabindex="0" onclick="location.href='/speiseplan/'" aria-label="Speiseplan für <?= htmlspecialchars($dayNames[$i]) ?>">
            <div class="menu-day-card__header">
              <span class="menu-day-card__name"><?= htmlspecialchars($dayNames[$i]) ?></span>
              <span class="menu-day-card__name">KW <?= $currentKW ?></span>
            </div>
            <div class="menu-day-card__img img-wrap">
              <?php if ($title): ?>
                <img
                  class="pexels-img"
                  src="/assets/images/og-image.jpg"
                  alt="<?= htmlspecialchars($title) ?>"
                  width="640"
                  height="360"
                  loading="lazy"
                  decoding="async"
                  data-query="<?= htmlspecialchars(dishSearchQuery($title), ENT_QUOTES, 'UTF-8') ?>"
                >
              <?php else: ?>
                <?= bmv_img('/assets/images/og-image.jpg', 'BMV Speiseplan Vorschau', 640, 360, false) ?>
              <?php endif; ?>
            </div>
            <div class="menu-day-card__body">
              <div class="menu-day-card__dish">
                <?= htmlspecialchars($title ?? 'Die Wochenkarte wird gerade aktualisiert.') ?>
              </div>
              <div class="menu-day-card__more">Vollständigen Plan öffnen</div>
            </div>
          </div>
        <?php endfor; ?>
      </div>

      <div class="final-cta__actions" data-reveal style="margin-top: 32px;">
        <a class="btn btn--primary" href="/speiseplan/">Kompletten Speiseplan öffnen</a>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="process-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">So läuft es ab</span>
        <h2 class="section-title" id="process-heading">Vier Schritte von der Anfrage bis zur Versorgung</h2>
      </div>
      <div class="process-steps">
        <article class="process-step" data-reveal>
          <div class="process-step__num">1</div>
          <h3 class="process-step__title">Anfrage</h3>
          <p class="process-step__text">Telefonisch oder online. Wir klären Gebiet, Bedarf und den passenden Leistungsbereich.</p>
        </article>
        <article class="process-step" data-reveal>
          <div class="process-step__num">2</div>
          <h3 class="process-step__title">Abstimmung</h3>
          <p class="process-step__text">Menüs, Lieferstart, Turnus oder Eventdetails werden sauber und ohne unnötige Schleifen festgelegt.</p>
        </article>
        <article class="process-step" data-reveal>
          <div class="process-step__num">3</div>
          <h3 class="process-step__title">Produktion</h3>
          <p class="process-step__text">Frische Küche vor Ort statt ausgelagerter Massenlogik. Qualität bleibt in operativer Hand.</p>
        </article>
        <article class="process-step" data-reveal>
          <div class="process-step__num">4</div>
          <h3 class="process-step__title">Lieferung</h3>
          <p class="process-step__text">Pünktlich, regional und mit direktem Ansprechpartner. Genau das schafft Vertrauen über Zeit.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="section final-cta" aria-labelledby="cta-heading">
    <div class="container container--narrow">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Nächster Schritt</span>
        <h2 class="section-title text-balance" id="cta-heading">Wenn Sie einen zuverlässigen Menüpartner suchen, ist jetzt der richtige Zeitpunkt für das erste Gespräch.</h2>
        <p class="section-sub">
          Ob private Versorgung, Mitarbeitendenverpflegung oder Event-Catering: Sie bekommen eine
          realistische Einschätzung statt Vertriebssprache.
        </p>
      </div>
      <div class="final-cta__actions" data-reveal>
        <a class="btn btn--primary" href="/kontakt/">Jetzt Kontakt aufnehmen</a>
        <a class="btn btn--ghost" href="tel:+4933275745066">+49 3327 5745066</a>
      </div>
    </div>
  </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
