<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';

$page_title = 'Catering für Firmen, Meetings und Events | BMV Menüdienst';
$meta_description = 'Catering aus Werder (Havel) für Firmenessen, Meetings und Veranstaltungen in Potsdam und dem Umland. Frisch gekocht, präzise organisiert, sauber ausgeliefert.';
$active_nav = 'catering';
$canonical = 'https://www.bmv-kantinen.de/catering/';

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>
<main id="main-content" role="main">
  <section class="page-hero page-hero--sm" aria-labelledby="hero-heading">
    <div class="page-hero__bg">
      <?= bmv_img('/assets/images/catering-setup.jpg', 'Catering-Aufbau für eine Firmenveranstaltung durch BMV', 1600, 900, true, 'page-hero__bg-img') ?>
      <div class="page-hero__overlay" aria-hidden="true"></div>
    </div>
    <div class="container">
      <div class="page-hero__content" data-reveal>
        <span class="page-hero__label">Catering</span>
        <h1 class="page-hero__heading" id="hero-heading">Firmen-Catering mit Hands-on-Mentalität statt Veranstaltungsnebel.</h1>
        <p class="page-hero__lead">
          Von Arbeitslunch bis Betriebsfeier: BMV liefert ein Catering, das frisch wirkt,
          sauber getaktet ist und auf Kundenseite keine Zusatzkoordination produziert.
        </p>
        <div class="page-hero__actions">
          <a class="btn btn--primary" href="/kontakt/">Anfrage senden</a>
          <a class="btn btn--ghost" href="tel:+4933275745066">Direkt anrufen</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="offer-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Formate</span>
        <h2 class="section-title" id="offer-heading">Für Meetings, Belegschaft und Anlässe mit Anspruch.</h2>
      </div>
      <div class="services-grid">
        <article class="service-card" data-reveal>
          <div class="service-card__img img-wrap">
            <?= bmv_img('/assets/images/og-image.jpg', 'Business Lunch Catering von BMV', 720, 450) ?>
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Business Lunch</h3>
            <p class="service-card__text">Für Teams, Jour-fixe-Runden und Gäste im Haus. Pünktlich, übersichtlich und ohne unnötigen Event-Look.</p>
            <div class="service-card__checks">
              <div class="service-card__check">Geeignet für kleine bis mittlere Gruppen</div>
              <div class="service-card__check">Klar kalkulierbar im Ablauf</div>
            </div>
          </div>
        </article>
        <article class="service-card" data-reveal>
          <div class="service-card__img img-wrap">
            <?= bmv_img('/assets/images/catering-setup.jpg', 'Catering-Buffet für Firmenveranstaltung', 720, 450) ?>
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Betriebsfeier</h3>
            <p class="service-card__text">Buffet, Ausgabe oder Mischformat. Wir planen entlang des tatsächlichen Ablaufs Ihrer Veranstaltung.</p>
            <div class="service-card__checks">
              <div class="service-card__check">Lieferung, Aufbau und Timing aus einer Hand</div>
              <div class="service-card__check">Passend für Innen- und Außenflächen</div>
            </div>
          </div>
        </article>
        <article class="service-card" data-reveal>
          <div class="service-card__img img-wrap">
            <?= bmv_img('/assets/images/speiseplan-kueche.jpg', 'Frische Produktion in der Küche von BMV', 720, 450) ?>
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title">Meeting & Seminar</h3>
            <p class="service-card__text">Verpflegung für halbe oder ganze Tage mit dem Fokus auf reibungslose Integration in Ihren Ablauf.</p>
            <div class="service-card__checks">
              <div class="service-card__check">Planbar für Vorträge, Workshops und Schulungen</div>
              <div class="service-card__check">Auf Wunsch mit mehreren Servicemomenten</div>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="difference-heading">
    <div class="container">
      <div class="alt-block">
        <div data-reveal>
          <span class="eyebrow">Der Unterschied</span>
          <h2 class="alt-block__title" id="difference-heading">Catering ist dann gut, wenn es im Hintergrund professionell wirkt und im Vordergrund entspannt.</h2>
          <div class="alt-block__text">
            <p>
              Viele Anfragen scheitern nicht am Essen, sondern an unklarer Abstimmung. BMV bringt hier
              die operative Stärke eines eingespielten Küchenteams mit.
            </p>
            <p>
              Deshalb ist unser Catering besonders dann stark, wenn ein Unternehmen Verlässlichkeit,
              Regionalität und einen greifbaren Ansprechpartner wichtiger findet als Showeffekte.
            </p>
          </div>
          <div class="alt-block__checks">
            <div class="alt-block__check">Frischproduktion statt austauschbarer Standardplatten</div>
            <div class="alt-block__check">Klare Kommunikation vor dem Termin</div>
            <div class="alt-block__check">Saubere Lieferung und realistische Zusagen</div>
          </div>
        </div>
        <div class="alt-block__visual img-wrap" data-reveal>
          <?= bmv_img('/assets/images/og-image.jpg', 'Ansprechend angerichtetes Catering von BMV', 960, 720) ?>
        </div>
      </div>
    </div>
  </section>

  <section class="section final-cta" aria-labelledby="cta-heading">
    <div class="container container--narrow">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Anfrage</span>
        <h2 class="section-title" id="cta-heading">Wenn Sie ein Catering brauchen, das organisatorisch mitdenkt, sprechen wir am besten direkt.</h2>
        <p class="section-sub">Kurz Anlass, Personenanzahl und Termin nennen. Den Rest strukturieren wir gemeinsam.</p>
      </div>
      <div class="final-cta__actions" data-reveal>
        <a class="btn btn--primary" href="/kontakt/">Catering anfragen</a>
        <a class="btn btn--ghost" href="tel:+4933275745066">+49 3327 5745066</a>
      </div>
    </div>
  </section>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</body>
</html>
