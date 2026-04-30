<?php
/**
 * includes/footer.php v3.0 – Premium Redesign
 */
if (!defined('BMV_NAME'))         define('BMV_NAME',  'BMV-Menüdienst');
if (!defined('BMV_TEL'))          define('BMV_TEL',   '+4933275745066');
if (!defined('BMV_TEL_DISPLAY'))  define('BMV_TEL_DISPLAY', '+49 3327 5745066');
if (!defined('BMV_HOURS_DISPLAY'))define('BMV_HOURS_DISPLAY', 'Mo–Fr 07:00–15:00 Uhr');
if (!defined('BMV_EMAIL'))        define('BMV_EMAIL', 'info@bmv-kantinen.de');
?>

<footer class="site-footer" role="contentinfo">
  <div class="container site-footer__inner">
    <div class="footer-grid">

      <div class="footer-brand">
        <a href="/" class="footer-brand__logo-link" aria-label="<?= BMV_NAME ?> – Startseite">
          <img src="/assets/images/BMV_Logo_n.png" alt="BMV Menüdienst Logo" loading="lazy" decoding="async">
          <span>
            <span class="footer-brand__logo-name">BMV Menüdienst</span>
            <span class="footer-brand__logo-sub">Werder (Havel) · seit 2009</span>
          </span>
        </a>
        <p class="footer-brand__desc">
          Premium-Betriebsgastronomie mit regionaler Verlässlichkeit. Essen auf Rädern,
          Kantine am Gutshof und Catering aus einer Hand – täglich frisch zubereitet.
        </p>
        <div class="footer-brand__contact">
          <a href="tel:<?= BMV_TEL ?>">
            <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
            <?= BMV_TEL_DISPLAY ?>
          </a>
          <a href="mailto:<?= BMV_EMAIL ?>">
            <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
            <?= BMV_EMAIL ?>
          </a>
        </div>
      </div>

      <nav class="footer-col" aria-label="Service-Navigation">
        <div class="footer-col__title">Service</div>
        <div class="footer-col__links">
          <a href="/essen-auf-raedern/"  class="footer-col__link">Essen auf Rädern</a>
          <a href="/kantine-am-gutshof/" class="footer-col__link">Kantine am Gutshof</a>
          <a href="/speiseplan/"         class="footer-col__link">Speiseplan</a>
          <a href="/catering/"           class="footer-col__link">Catering</a>
        </div>
      </nav>

      <nav class="footer-col" aria-label="Unternehmens-Navigation">
        <div class="footer-col__title">Unternehmen</div>
        <div class="footer-col__links">
          <a href="/ueber-uns/"   class="footer-col__link">Über uns</a>
          <a href="/kontakt/"     class="footer-col__link">Kontakt</a>
          <a href="/impressum/"   class="footer-col__link">Impressum</a>
          <a href="/datenschutz/" class="footer-col__link">Datenschutz</a>
          <a href="/agb/"         class="footer-col__link">AGB</a>
        </div>
      </nav>

      <address class="footer-col">
        <div class="footer-col__title">Standort</div>
        <p class="footer-col__text">
          BMV-Menüdienst<br>
          Am Gutshof 6<br>
          14542 Werder (Havel)<br>
          Brandenburg
        </p>
        <div class="footer-hours" aria-label="Öffnungszeiten">
          <svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.5 5v5.25l4.5 2.67-.75 1.23L11 13V7h1.5z"/></svg>
          <?= BMV_HOURS_DISPLAY ?>
        </div>
      </address>

    </div>

    <div class="footer-bottom">
      <p class="footer-bottom__copy">
        &copy; <?= date('Y') ?> <?= BMV_NAME ?> &bull; Am Gutshof 6 &bull; 14542 Werder (Havel)
      </p>
      <nav class="footer-bottom__links" aria-label="Rechtliche Links">
        <a href="/impressum/">Impressum</a>
        <a href="/datenschutz/">Datenschutz</a>
        <a href="/agb/">AGB</a>
      </nav>
    </div>
  </div>
</footer>

<script src="/assets/js/main.js" defer></script>
<?php if (!empty($page_scripts) && is_array($page_scripts)): ?>
<?php foreach ($page_scripts as $page_script): ?>
<script src="<?= htmlspecialchars($page_script) ?>" defer></script>
<?php endforeach; ?>
<?php endif; ?>
