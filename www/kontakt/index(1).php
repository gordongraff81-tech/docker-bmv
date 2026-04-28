<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';

$page_title = 'Kontakt | BMV Menüdienst in Werder (Havel)';
$meta_description = 'Kontakt zu BMV Menüdienst für Essen auf Rädern, Catering und Kantine. Telefonisch, per E-Mail oder direkt über das Kontaktformular.';
$active_nav = 'kontakt';
$canonical = 'https://www.bmv-kantinen.de/kontakt/';

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>
<main id="main-content" role="main">
  <section class="page-hero page-hero--sm" aria-labelledby="hero-heading">
    <div class="page-hero__bg">
      <?= bmv_img('/assets/images/og-image.jpg', 'Kontaktaufnahme mit BMV Menüdienst', 1600, 900, true, 'page-hero__bg-img') ?>
      <div class="page-hero__overlay" aria-hidden="true"></div>
    </div>
    <div class="container">
      <div class="page-hero__content" data-reveal>
        <span class="page-hero__label">Kontakt & Beratung</span>
        <h1 class="page-hero__heading" id="hero-heading">Direkter Kontakt statt Kontaktstrecke.</h1>
        <p class="page-hero__lead">
          Ob Versorgung zuhause, Firmen-Catering oder Fragen zur Kantine: Wir antworten persönlich,
          konkret und ohne Vertriebsschleife.
        </p>
        <div class="page-hero__actions">
          <a class="btn btn--primary" href="tel:+4933275745066">+49 3327 5745066</a>
          <a class="btn btn--ghost" href="mailto:info@bmv-kantinen.de">info@bmv-kantinen.de</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="channels-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">So erreichen Sie uns</span>
        <h2 class="section-title" id="channels-heading">Drei Wege, die schnell zum richtigen Gespräch führen.</h2>
      </div>

      <div class="contact-grid">
        <article class="contact-card" data-reveal>
          <h3 class="region-card__title">Telefon</h3>
          <p>Für Rückfragen, Liefergebiet oder einen schnellen Start am besten direkt anrufen.</p>
          <div class="service-card__checks">
            <div class="service-card__check">Mo–Fr 07:00 bis 15:00 Uhr</div>
            <div class="service-card__check">Schnellster Weg für neue Anfragen</div>
          </div>
          <a class="service-card__link" href="tel:+4933275745066">+49 3327 5745066</a>
        </article>

        <article class="contact-card" data-reveal>
          <h3 class="region-card__title">E-Mail</h3>
          <p>Gut geeignet für Catering-Anfragen, Rückfragen aus Unternehmen oder Unterlagen im Anhang.</p>
          <div class="service-card__checks">
            <div class="service-card__check">Persönliche Rückmeldung statt Ticket-System</div>
            <div class="service-card__check">Ideal für strukturierte Projektanfragen</div>
          </div>
          <a class="service-card__link" href="mailto:info@bmv-kantinen.de">info@bmv-kantinen.de</a>
        </article>
      </div>
    </div>
  </section>

  <section class="section section--bg" aria-labelledby="form-heading">
    <div class="container">
      <div class="alt-block">
        <div data-reveal>
          <span class="eyebrow">Formular</span>
          <h2 class="alt-block__title" id="form-heading">Nachricht senden</h2>
          <div class="alt-block__text">
            <p>
              Das Formular bleibt bewusst einfach. Relevante Basisdaten reichen, den Rest klären wir im
              Rückruf oder per E-Mail.
            </p>
          </div>

          <form id="contact-form" method="post" action="/kontakt/send.php" novalidate>
            <div class="form-group">
              <label class="form-label" for="name">Ihr Name *</label>
              <input class="form-input form-control" type="text" id="name" name="name" required placeholder="Max Mustermann">
            </div>

            <div class="form-group">
              <label class="form-label" for="email">E-Mail-Adresse *</label>
              <input class="form-input form-control" type="email" id="email" name="email" required placeholder="max@beispiel.de">
            </div>

            <div class="form-group">
              <label class="form-label" for="telefon">Telefonnummer</label>
              <input class="form-input form-control" type="tel" id="telefon" name="telefon" placeholder="+49 ...">
            </div>

            <div class="form-group">
              <label class="form-label" for="betreff">Anliegen *</label>
              <select class="form-select form-control" id="betreff" name="betreff" required>
                <option value="">Bitte wählen</option>
                <option value="essen-auf-raedern">Essen auf Rädern</option>
                <option value="catering">Catering</option>
                <option value="kantine">Kantine</option>
                <option value="speiseplan">Speiseplan</option>
                <option value="sonstiges">Sonstiges</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label" for="nachricht">Nachricht *</label>
              <textarea class="form-textarea form-control" id="nachricht" name="nachricht" required rows="6" placeholder="Worum geht es genau?"></textarea>
            </div>

            <div class="form-group form-group--checkbox">
              <input class="form-checkbox" type="checkbox" id="datenschutz" name="datenschutz" required>
              <label class="form-label" for="datenschutz">
                Ich habe die <a href="/datenschutz/">Datenschutzerklärung</a> gelesen und stimme der Verarbeitung meiner Daten zu. *
              </label>
            </div>

            <div id="bmv-form-status" role="alert" aria-live="polite" style="display:none;margin-top:16px;padding:14px 18px;border-radius:8px;font-weight:500"></div>

            <div class="hero__actions" style="margin-top: 24px;">
              <button class="btn btn--primary" type="submit" id="contact-submit">Nachricht senden</button>
            </div>
          </form>

          <script>
          (function () {
            var form   = document.getElementById('contact-form');
            var status = document.getElementById('bmv-form-status');
            var btn    = document.getElementById('contact-submit');
            if (!form) return;

            form.addEventListener('submit', async function (e) {
              e.preventDefault();
              btn.disabled = true;
              btn.textContent = 'Wird gesendet \u2026';
              status.style.display = 'none';

              try {
                var res  = await fetch('/kontakt/send.php', { method: 'POST', body: new FormData(form) });
                var json = await res.json();

                if (res.ok && json.success) {
                  status.style.cssText = 'display:block;margin-top:16px;padding:14px 18px;border-radius:8px;font-weight:500;background:#dcfce7;color:#166534;border:1px solid #86efac';
                  status.textContent = json.message || 'Vielen Dank! Wir melden uns in K\u00fcrze.';
                  form.reset();
                } else {
                  var msg = json.message || 'Ein Fehler ist aufgetreten.';
                  if (json.errors) msg = Object.values(json.errors).join(' ') || msg;
                  status.style.cssText = 'display:block;margin-top:16px;padding:14px 18px;border-radius:8px;font-weight:500;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5';
                  status.textContent = msg;
                }
              } catch (err) {
                status.style.cssText = 'display:block;margin-top:16px;padding:14px 18px;border-radius:8px;font-weight:500;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5';
                status.textContent = 'Verbindungsfehler. Bitte versuchen Sie es erneut oder rufen Sie uns an.';
              } finally {
                btn.disabled = false;
                btn.textContent = 'Nachricht senden';
                status.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
              }
            });
          })();
          </script>
        </div>

        <div data-reveal>
          <div class="contact-card">
            <h3 class="region-card__title">BMV Menüdienst</h3>
            <p>Am Gutshof 6<br>14542 Werder (Havel)<br>Brandenburg</p>
            <div class="service-card__checks">
              <div class="service-card__check">Telefon: +49 3327 5745066</div>
              <div class="service-card__check">E-Mail: info@bmv-kantinen.de</div>
              <div class="service-card__check">Kantine & Büro: Mo–Fr 07:00–15:00 Uhr</div>
            </div>
          </div>

          <div class="contact-card" style="margin-top: 24px;">
            <h3 class="region-card__title">Orientierung für Anfragen</h3>
            <p>Hilfreich sind vor allem diese drei Angaben:</p>
            <div class="service-card__checks">
              <div class="service-card__check">Welcher Leistungsbereich interessiert Sie?</div>
              <div class="service-card__check">Für welches Gebiet oder welchen Standort?</div>
              <div class="service-card__check">Ab wann wird die Leistung benötigt?</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section final-cta" aria-labelledby="cta-heading">
    <div class="container container--narrow">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Schnellster Weg</span>
        <h2 class="section-title" id="cta-heading">Wenn es zeitkritisch ist, rufen Sie direkt an.</h2>
        <p class="section-sub">Gerade bei Lieferstart, Versorgungslücken oder kurzfristigen Catering-Terminen ist das Telefon meist der effektivste erste Schritt.</p>
      </div>
      <div class="final-cta__actions" data-reveal>
        <a class="btn btn--primary" href="tel:+4933275745066">Jetzt anrufen</a>
        <a class="btn btn--ghost" href="mailto:info@bmv-kantinen.de">E-Mail schreiben</a>
      </div>
    </div>
  </section>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
