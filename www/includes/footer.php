<?php
/**
 * includes/footer.php
 * Shared footer + script includes.
 */

// Konstanten aus header.php sind zur Laufzeit verfügbar.
// Sicherheits-Fallback falls footer ohne header eingebunden wird:
if (!defined('BMV_NAME'))  define('BMV_NAME',  'BMV-Menüdienst');
if (!defined('BMV_TEL'))   define('BMV_TEL',   '+4933275745066');
if (!defined('BMV_TEL_DISPLAY')) define('BMV_TEL_DISPLAY', '+49 3327 5745066');
if (!defined('BMV_HOURS_DISPLAY')) define('BMV_HOURS_DISPLAY', 'Mo–Fr 07:00–15:00 Uhr');
if (!defined('BMV_EMAIL')) define('BMV_EMAIL', 'info@bmv-kantinen.de');
?>

<footer class="site-footer" role="contentinfo">
  <div class="container site-footer__inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="/" class="footer-brand__logo-link" aria-label="<?= BMV_NAME ?> – Startseite">
          <span class="site-logo__mark" aria-hidden="true">BMV</span>
          <span>
            <strong>BMV Menüdienst</strong><br>
            <span>Seit 15+ Jahren in Potsdam, Werder und Umland</span>
          </span>
        </a>
        <p class="footer-brand__desc">
          Premium-Betriebsgastronomie mit regionaler Verlässlichkeit: Essen auf Rädern,
          Kantine und Catering aus einer Hand.
        </p>
        <p class="footer-brand__desc" style="margin-top: 12px;">
          <strong style="color: <?= 'var(--color-text-on-dark)' ?>;"><?= BMV_HOURS_DISPLAY ?></strong>
        </p>
        <div style="margin-top: 20px;">
          <a href="tel:<?= BMV_TEL ?>" class="btn btn--ghost btn--sm" aria-label="<?= BMV_NAME ?> anrufen">
            <?= BMV_TEL_DISPLAY ?>
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
        </div>
      </nav>

      <address class="footer-col">
        <div class="footer-col__title">Kontakt</div>
        <p class="footer-col__text">
          BMV-Menüdienst<br>
          Am Gutshof 6<br>
          14542 Werder (Havel)<br>
          Brandenburg
        </p>
        <p class="footer-col__text" style="margin-top: 12px;">
          <a href="tel:<?= BMV_TEL ?>" class="footer-col__link"><?= BMV_TEL_DISPLAY ?></a><br>
          <a href="mailto:<?= BMV_EMAIL ?>" class="footer-col__link"><?= BMV_EMAIL ?></a>
        </p>
        <p style="margin-top: 12px;">
          <span class="badge badge--amber"><?= BMV_HOURS_DISPLAY ?></span>
        </p>
      </address>
    </div>

    <div class="footer-bottom">
      <p class="footer-bottom__copy">
        &copy; <?= date('Y') ?> <?= BMV_NAME ?> &middot; Am Gutshof 6 &middot; 14542 Werder (Havel)
      </p>
      <nav class="footer-bottom__links" aria-label="Rechtliche Links">
        <a href="/impressum/">Impressum</a>
        <a href="/datenschutz/">Datenschutz</a>
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
