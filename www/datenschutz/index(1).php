<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';

$page_title = 'Datenschutzerklärung | BMV Menüdienst';
$meta_description = 'Datenschutzerklärung von BMV Menüdienst gemäß DSGVO.';
$active_nav = 'kontakt';
$canonical = 'https://www.bmv-kantinen.de/datenschutz/';
$meta_robots = 'noindex, follow';

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>
<main id="main-content" role="main">
  <section class="page-hero page-hero--sm" aria-labelledby="hero-heading">
    <div class="page-hero__bg">
      <?= bmv_img('/assets/images/og-image.jpg', 'Datenschutzerklärung von BMV Menüdienst', 1600, 900, true, 'page-hero__bg-img') ?>
      <div class="page-hero__overlay" aria-hidden="true"></div>
    </div>
    <div class="container">
      <div class="page-hero__content" data-reveal>
        <span class="page-hero__label">Rechtliches</span>
        <h1 class="page-hero__heading" id="hero-heading">Datenschutzerklärung</h1>
        <p class="page-hero__lead">Diese Seite ist rechtlich notwendig und bewusst nicht für SEO-Indexierung vorgesehen.</p>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container container--narrow">
      <div class="content-prose" style="max-width: 72ch;" data-reveal>
        <p><strong>Hinweis:</strong> Diese Seite dient der Information gemäß DSGVO und sollte nicht als SEO-Landingpage behandelt werden.</p>

        <h2>1. Verantwortlicher</h2>
        <p>
          BMV Menüdienst<br>
          Inhaber: Jürgen Koschnick<br>
          Am Gutshof 6<br>
          14542 Werder (Havel)<br>
          E-Mail: <a href="mailto:info@bmv-kantinen.de">info@bmv-kantinen.de</a><br>
          Telefon: <a href="tel:+4933275745066">+49 3327 5745066</a>
        </p>

        <h2>2. Verarbeitung personenbezogener Daten</h2>
        <p>Wir verarbeiten personenbezogene Daten nur, soweit dies zur Bearbeitung von Anfragen, zur Durchführung von Vertragsverhältnissen oder zur Erfüllung gesetzlicher Pflichten erforderlich ist.</p>

        <h2>3. Kontaktaufnahme</h2>
        <p>Wenn Sie uns über das Kontaktformular oder per E-Mail schreiben, speichern wir Ihre Angaben ausschließlich zur Bearbeitung Ihrer Anfrage und für eventuelle Rückfragen.</p>

        <h2>4. Server-Logfiles</h2>
        <p>Beim Aufruf dieser Website werden technisch notwendige Server-Logdaten verarbeitet. Diese dienen der Stabilität, Sicherheit und Fehleranalyse des Webangebots.</p>

        <h2>5. Ihre Rechte</h2>
        <p>Sie haben das Recht auf Auskunft, Berichtigung, Löschung, Einschränkung der Verarbeitung, Datenübertragbarkeit sowie Widerspruch im Rahmen der gesetzlichen Vorgaben.</p>
      </div>
    </div>
  </section>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</body>
</html>
