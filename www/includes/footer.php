<?php
/**
 * includes/footer.php
 * BMV-Menüdienst — Shared Footer v3.0
 * Einbinden: <?php include __DIR__ . '/../includes/footer.php'; ?>
 * 
 * WICHTIG: Diese Datei enthält NUR den Footer-HTML + Scripts
 * Keine </body> oder </html> Tags!
 */

// Konstanten aus header.php sind zur Laufzeit verfügbar.
// Sicherheits-Fallback falls footer ohne header eingebunden wird:
if (!defined('BMV_NAME'))  define('BMV_NAME',  'BMV-Menüdienst');
if (!defined('BMV_TEL'))   define('BMV_TEL',   '+4933275745066');
if (!defined('BMV_TEL_DISPLAY')) define('BMV_TEL_DISPLAY', '+49 3327 5745066');
if (!defined('BMV_HOURS_DISPLAY')) define('BMV_HOURS_DISPLAY', 'Mo–Fr 07:00–15:00 Uhr');
if (!defined('BMV_EMAIL')) define('BMV_EMAIL', 'info@bmv-kantinen.de');
?>

<!-- ═══════════════════════════════════════════════════════ -->
<!-- Site Footer                                            -->
<!-- ═══════════════════════════════════════════════════════ -->
<footer class="site-footer" role="contentinfo">
  <div class="container">
    <div class="footer-grid">

      <!-- Spalte 1: Marke + Kurzinfo ─────────────────────── -->
      <div class="footer-brand">
        <a href="/" class="footer-brand__logo-link" aria-label="<?= BMV_NAME ?> – Startseite">
          <img src="/assets/images/BMV_Logo_n.png"
               alt="<?= BMV_NAME ?> Logo"
               width="140" height="44"
               loading="lazy"
               decoding="async">
        </a>
        <p class="footer-brand__desc">
          Frisches Mittagessen täglich geliefert – seit über 15 Jahren Ihr verlässlicher Partner
          für Essen auf Rädern, Catering und Kantine in Potsdam und Werder (Havel).
        </p>
        <!-- Öffnungszeiten: Mo–Fr 07:00–15:00 Uhr -->
        <p class="footer-brand__desc" style="margin-top:.75rem;">
          <strong style="color:rgba(255,255,255,.85);"><?= BMV_HOURS_DISPLAY ?></strong>
        </p>
        <!-- Klickbare Telefonnummer: +49 3327 5745066 -->
        <div style="margin-top:1.25rem;">
          <a href="tel:<?= BMV_TEL ?>" class="btn btn--ghost btn--sm" aria-label="<?= BMV_NAME ?> anrufen">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.11 19.79 19.79 0 01.01 4.59 2 2 0 012 2.42L5.08 2a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
            </svg>
            <?= BMV_TEL_DISPLAY ?>
          </a>
        </div>
      </div>

      <!-- Spalte 2: Service-Links ─────────────────────────── -->
      <nav class="footer-col" aria-label="Service-Navigation">
        <div class="footer-col__title">Service</div>
        <div class="footer-col__links">
          <a href="/essen-auf-raedern/"  class="footer-col__link">Essen auf Rädern</a>
          <a href="/kantine-am-gutshof/" class="footer-col__link">Kantine am Gutshof</a>
          <a href="/speiseplan/"         class="footer-col__link">Speiseplan</a>
          <a href="/catering/"           class="footer-col__link">Catering</a>
        </div>
      </nav>

      <!-- Spalte 3: Unternehmen-Links ─────────────────────── -->
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

      <!-- Spalte 4: Kontakt-Details ───────────────────────── -->
      <address class="footer-col">
        <div class="footer-col__title">Kontakt</div>
        <p class="footer-col__text">
          BMV-Menüdienst<br>
          Am Gutshof 6<br>
          14542 Werder (Havel)<br>
          Brandenburg
        </p>
        <p class="footer-col__text" style="margin-top:.75rem;">
          <!-- Klickbare Telefonnummer -->
          <a href="tel:<?= BMV_TEL ?>" class="footer-col__link"><?= BMV_TEL_DISPLAY ?></a><br>
          <a href="mailto:<?= BMV_EMAIL ?>" class="footer-col__link"><?= BMV_EMAIL ?></a>
        </p>
        <!-- Öffnungszeiten: Mo–Fr 07:00–15:00 Uhr -->
        <p style="margin-top:.75rem;">
          <span class="badge badge--amber"><?= BMV_HOURS_DISPLAY ?></span>
        </p>
      </address>

    </div><!-- /footer-grid -->

    <!-- Footer Bottom ──────────────────────────────────────── -->
    <div class="footer-bottom">
      <p class="footer-bottom__copy">
        &copy; <?= date('Y') ?> <?= BMV_NAME ?> &middot; Am Gutshof 6 &middot; 14542 Werder (Havel)
      </p>
      <nav class="footer-bottom__links" aria-label="Rechtliche Links">
        <a href="/impressum/">Impressum</a>
        <a href="/datenschutz/">Datenschutz</a>
        <a href="/agb/">AGB</a>
      </nav>
    </div>

  </div>
</footer>

<!-- Scripts ──────────────────────────────────────────────────── -->
<!-- main.js: Nav-Toggle, FAQ-Accordion, Fade-Observer, Formular -->
<script src="/assets/js/main.js" defer></script>
<!-- design-system.js: Scroll-Reveal, Ripple, Counter-Animation  -->
<script src="/assets/js/design-system.js" defer></script>
