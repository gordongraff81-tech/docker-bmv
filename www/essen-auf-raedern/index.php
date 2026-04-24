<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/location-pages.php';

$page_title = 'Essen auf Rädern in Potsdam, Werder und Umland | BMV Menüdienst';
$meta_description = 'Warmes Mittagessen zuverlässig nach Hause geliefert. BMV versorgt Potsdam, Werder (Havel) und das Umland mit frischen Menüs an sieben Tagen pro Woche.';
$active_nav = 'ears';
$canonical = 'https://www.bmv-kantinen.de/essen-auf-raedern/';
$page_scripts = ['/assets/js/index.js'];
$locationPages = bmv_location_pages();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>
<main id="main-content" role="main">
  <section class="page-hero page-hero--sm" aria-labelledby="hero-heading">
    <div class="page-hero__bg">
      <?= bmv_img('/assets/images/essen-auf-raedern-lieferung.jpg', 'BMV liefert Essen auf Rädern in Potsdam und Werder aus', 1600, 900, true, 'page-hero__bg-img') ?>
      <div class="page-hero__overlay" aria-hidden="true"></div>
    </div>
    <div class="container">
      <div class="page-hero__content" data-reveal>
        <span class="page-hero__label">Essen auf Rädern</span>
        <h1 class="page-hero__heading" id="hero-heading">Frisch gekocht. Persönlich geliefert. Alltag spürbar leichter.</h1>
        <p class="page-hero__lead">
          BMV versorgt Menschen in Potsdam, Werder (Havel) und dem Umland mit warmem Mittagessen,
          das zuverlässig ankommt und nicht nach Kompromiss schmeckt.
        </p>
        <div class="page-hero__actions">
          <a class="btn btn--primary" href="/kontakt/">Unverbindlich anfragen</a>
          <a class="btn btn--ghost" href="/speiseplan/">Aktuellen Speiseplan öffnen</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="benefits-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Leistungsversprechen</span>
        <h2 class="section-title" id="benefits-heading">Die Versorgung soll entlasten, nicht zusätzlich Organisation erzeugen.</h2>
        <p class="section-sub">
          Deshalb ist unser Modell bewusst einfach: klare Menüs, berechenbare Lieferung und ein
          Ansprechpartner, der nicht hinter einer Plattform verschwindet.
        </p>
      </div>

      <div class="services-grid">
        <article class="service-card" data-reveal>
          <div class="service-card__img img-wrap">
            <?= bmv_img('/assets/images/potsdam-lieferung.jpg', 'Auslieferung von Menüs in Potsdam', 720, 450) ?>
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Sieben Tage pro Woche</h3>
            <p class="service-card__text">Auch am Wochenende und an Feiertagen planbar. Das ist für viele Familien der entscheidende Unterschied.</p>
            <div class="service-card__checks">
              <div class="service-card__check">Konstante Lieferlogik statt Sonderfälle</div>
              <div class="service-card__check">Verlässliche Tagesstruktur im Alltag</div>
            </div>
          </div>
        </article>

        <article class="service-card" data-reveal>
          <div class="service-card__img img-wrap">
            <?= bmv_img('/assets/images/speiseplan-kueche.jpg', 'Frische Küche von BMV Menüdienst', 720, 450) ?>
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Täglich frisch gekocht</h3>
            <p class="service-card__text">Vier Menülinien, klare Kennzeichnung und eine Küche, die nicht auf Tiefkühl-Komfort setzt.</p>
            <div class="service-card__checks">
              <div class="service-card__check">Vollkost, leichte Kost, Premium und Tagesmenü</div>
              <div class="service-card__check">Zusatzkarte mit Dessert, Rohkost, Abendessen und Salat</div>
            </div>
          </div>
        </article>

        <article class="service-card" data-reveal>
          <div class="service-card__img img-wrap">
            <?= bmv_img('/assets/images/umland-lieferung.jpg', 'Liefergebiet im Umland von Potsdam-Mittelmark', 720, 450) ?>
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Flexibel im Ablauf</h3>
            <p class="service-card__text">Keine unnötige Bindung. Lieferbeginn, Liefertage und Pausen lassen sich mit dem Team direkt abstimmen.</p>
            <div class="service-card__checks">
              <div class="service-card__check">Geeignet für dauerhafte oder temporäre Versorgung</div>
              <div class="service-card__check">Pflegekassen-Themen können direkt besprochen werden</div>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="regions-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Liefergebiete</span>
        <h2 class="section-title" id="regions-heading">Regional organisiert statt anonym verteilt.</h2>
      </div>
      <div class="region-grid">
        <article class="region-card" data-reveal>
          <div class="service-card__body">
            <h3 class="region-card__title">Potsdam</h3>
            <p>Alle wichtigen Stadtteile werden regelmäßig beliefert. Ideal für private Haushalte und Angehörige, die aus der Ferne mitorganisieren.</p>
            <div class="service-card__checks">
              <div class="service-card__check">Kurze Reaktionswege</div>
              <div class="service-card__check">Direkte Abstimmung mit dem Team</div>
            </div>
            <a class="service-card__link" href="/essen-auf-raedern/potsdam/">Seite für Potsdam</a>
          </div>
        </article>
        <article class="region-card" data-reveal>
          <div class="service-card__body">
            <h3 class="region-card__title">Werder (Havel)</h3>
            <p>Vom Standort aus besonders nah betreut. Hier verbinden sich Küche, Kantine und Lieferlogistik besonders effizient.</p>
            <div class="service-card__checks">
              <div class="service-card__check">Starker lokaler Bezug</div>
              <div class="service-card__check">Kürzere operative Wege</div>
            </div>
            <a class="service-card__link" href="/essen-auf-raedern/werder-havel/">Seite für Werder</a>
          </div>
        </article>
        <article class="region-card" data-reveal>
          <div class="service-card__body">
            <h3 class="region-card__title">Umland</h3>
            <p>Auch Gemeinden rund um Potsdam-Mittelmark werden versorgt. Am besten kurz anfragen, damit wir das Gebiet direkt bestätigen können.</p>
            <div class="service-card__checks">
              <div class="service-card__check">Klare Gebietsauskunft ohne Umwege</div>
              <div class="service-card__check">Geeignet für Familien im Berliner Umland</div>
            </div>
            <a class="service-card__link" href="/essen-auf-raedern/umland/">Seite für das Umland</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="locations-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Lokale SEO-Landingpages</span>
        <h2 class="section-title" id="locations-heading">Standortseiten für Städte und Regionen im erweiterten Lieferumfeld</h2>
        <p class="section-sub">Jede Seite bündelt Angebot, Region und Suchintention auf einer kanonischen URL. Das ist für lokale Rankings deutlich sauberer als eine generische Sammelseite.</p>
      </div>
      <div class="region-grid">
        <?php foreach ($locationPages as $slug => $locationPage): ?>
          <article class="region-card" data-reveal>
            <div class="service-card__body">
              <h3 class="region-card__title"><?= htmlspecialchars($locationPage['name']) ?></h3>
              <p><?= htmlspecialchars($locationPage['meta_description']) ?></p>
              <a class="service-card__link" href="<?= htmlspecialchars($locationPage['canonical_path']) ?>">Standortseite öffnen</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="faq-heading">
    <div class="container container--narrow">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Häufige Fragen</span>
        <h2 class="section-title" id="faq-heading">Was Angehörige und Kunden zuerst wissen wollen</h2>
      </div>
      <div class="faq-list">
        <div class="faq-item" data-reveal>
          <button class="faq-item__question" type="button" aria-expanded="false">Wie startet die Versorgung?</button>
          <div class="faq-item__answer">
            <p>Nach dem Erstgespräch stimmen wir Liefergebiet, Starttermin und die gewünschte Menülogik ab. Danach läuft die Versorgung planbar an.</p>
          </div>
        </div>
        <div class="faq-item" data-reveal>
          <button class="faq-item__question" type="button" aria-expanded="false">Gibt es eine Mindestlaufzeit?</button>
          <div class="faq-item__answer">
            <p>Nein. Die Lösung soll entlasten, nicht binden. Änderungen oder Pausen lassen sich direkt mit dem Team abstimmen.</p>
          </div>
        </div>
        <div class="faq-item" data-reveal>
          <button class="faq-item__question" type="button" aria-expanded="false">Wie sieht der Speiseplan aus?</button>
          <div class="faq-item__answer">
            <p>Vier Menülinien plus Zusatzkarte. Allergene und Preise sind sichtbar, der Wochenplan steht online zur Verfügung.</p>
          </div>
        </div>
        <div class="faq-item" data-reveal>
          <button class="faq-item__question" type="button" aria-expanded="false">Unterstützt BMV beim Thema Pflegekasse?</button>
          <div class="faq-item__answer">
            <p>Ja. Gerade bei Essen auf Rädern ist diese Einordnung oft relevant. Das kann im Beratungsgespräch direkt geklärt werden.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section final-cta" aria-labelledby="cta-heading">
    <div class="container container--narrow">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Jetzt starten</span>
        <h2 class="section-title" id="cta-heading">Wenn Versorgung zuverlässig laufen muss, ist ein kurzes Gespräch der schnellste Weg.</h2>
        <p class="section-sub">Wir klären Liefergebiet, Bedarf und den sinnvollsten Start ohne komplizierten Vorlauf.</p>
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
