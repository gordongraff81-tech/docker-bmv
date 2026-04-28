<#
.SYNOPSIS
    BMV Menüdienst – Direkte UX-Fixes aus dem Audit
    Ausführen aus dem Projektroot: .\bmv-fixes.ps1

.BESCHREIBUNG
    Fix 1 – Kontaktformular: JavaScript-Handler für JSON-Response von send.php
    Fix 2 – Catering-Subseiten: index.php für /catering/potsdam/ und /catering/werder-havel/
    Fix 3 – Speiseplan: doppeltes <!DOCTYPE html> entfernen
    Fix 4 – Impressum: doppeltes <!DOCTYPE html> entfernen
#>

$root = "C:\Users\gordo\OneDrive\Dokumente\GitHub\docker-bmv\www"

function Write-Step($msg) {
    Write-Host "`n[$msg]" -ForegroundColor Cyan
}
function Write-Ok($msg) {
    Write-Host "  OK  $msg" -ForegroundColor Green
}
function Write-Skip($msg) {
    Write-Host "  --  $msg (übersprungen, bereits vorhanden)" -ForegroundColor DarkGray
}

# ═══════════════════════════════════════════════════════════════
# FIX 1 – Kontaktformular: JS-Handler einfügen
# Das Form postet an send.php welches JSON zurückgibt, aber
# kontakt/index.php hat kein fetch()-Handler dafür.
# Wir ersetzen den <form>-Tag durch eine async fetch-Variante
# und fügen einen Status-Block + Script direkt vor </form> ein.
# ═══════════════════════════════════════════════════════════════
Write-Step "Fix 1: Kontaktformular JS-Response-Handler"

$kontaktPath = "$root\kontakt\index.php"
$kontaktContent = Get-Content $kontaktPath -Raw -Encoding UTF8

# Prüfen ob Fix bereits vorhanden
if ($kontaktContent -match "bmv-form-status") {
    Write-Skip "kontakt/index.php (JS-Handler bereits vorhanden)"
} else {
    # 1a: <form method="post"> → onsubmit-Handler + id hinzufügen
    $kontaktContent = $kontaktContent -replace `
        '<form method="post" action="/kontakt/send\.php" novalidate>', `
        '<form id="contact-form" method="post" action="/kontakt/send.php" novalidate>'

    # 1b: Status-Block + Script vor dem schließenden </form> einfügen
    $formScript = @'

            <div id="bmv-form-status" role="alert" aria-live="polite" style="display:none;margin-top:16px;padding:14px 18px;border-radius:8px;font-weight:500"></div>

          </form>

          <script>
          (function () {
            var form   = document.getElementById('contact-form');
            var status = document.getElementById('bmv-form-status');
            var btn    = form ? form.querySelector('[type="submit"]') : null;
            if (!form) return;

            form.addEventListener('submit', async function (e) {
              e.preventDefault();
              if (btn) { btn.disabled = true; btn.textContent = 'Wird gesendet …'; }
              status.style.display = 'none';

              var data = new FormData(form);
              try {
                var res  = await fetch('/kontakt/send.php', { method: 'POST', body: data });
                var json = await res.json();

                if (res.ok && json.success) {
                  status.style.background = '#dcfce7';
                  status.style.color      = '#166534';
                  status.style.border     = '1px solid #86efac';
                  status.textContent      = json.message || 'Vielen Dank! Wir melden uns in Kürze.';
                  form.reset();
                } else {
                  var msg = json.message || 'Ein Fehler ist aufgetreten.';
                  if (json.errors) {
                    var errs = Object.values(json.errors).join(' ');
                    msg = errs || msg;
                  }
                  status.style.background = '#fee2e2';
                  status.style.color      = '#991b1b';
                  status.style.border     = '1px solid #fca5a5';
                  status.textContent      = msg;
                }
              } catch (err) {
                status.style.background = '#fee2e2';
                status.style.color      = '#991b1b';
                status.style.border     = '1px solid #fca5a5';
                status.textContent      = 'Verbindungsfehler. Bitte versuchen Sie es erneut oder rufen Sie uns an.';
              } finally {
                status.style.display = 'block';
                if (btn) { btn.disabled = false; btn.textContent = 'Nachricht senden'; }
                status.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
              }
            });
          })();
          </script>
'@

    # Den original schließenden </form>-Tag ersetzen (der letzte im File)
    $kontaktContent = $kontaktContent -replace `
        '(?s)(.*)<button class="btn btn--primary" type="submit">Nachricht senden</button>\s*</div>\s*</form>', `
        '$1<button class="btn btn--primary" type="submit">Nachricht senden</button>
            </div>' + $formScript

    Set-Content $kontaktPath $kontaktContent -Encoding UTF8
    Write-Ok "kontakt/index.php (fetch-Handler + Status-Block eingefügt)"
}

# ═══════════════════════════════════════════════════════════════
# FIX 2 – Catering-Subseiten erstellen
# /catering/potsdam/ und /catering/werder-havel/ sind leere
# Verzeichnisse. Wir legen je eine vollständige index.php an
# die dem gleichen Pattern wie die Essen-auf-Rädern-Seiten folgt.
# ═══════════════════════════════════════════════════════════════
Write-Step "Fix 2: Catering-Subseiten (Potsdam + Werder-Havel)"

$cateringPages = @(
    @{
        Path    = "$root\catering\potsdam\index.php"
        City    = "Potsdam"
        Slug    = "potsdam"
        Title   = "Catering Potsdam | BMV Menüdienst"
        Desc    = "Firmen-Catering für Potsdam: Arbeitslunch, Meetings und Veranstaltungen. Frisch aus Werder (Havel), pünktlich geliefert. Jetzt anfragen."
        Eyebrow = "Catering Potsdam"
        Hero    = "Von Babelsberg bis Bornstedt: Catering für Potsdam."
        Lead    = "BMV liefert Firmenessen, Meeting-Catering und Veranstaltungsversorgung nach Potsdam. Frisch gekocht am Morgen, pünktlich bei Ihnen."
        Nav     = "catering"
        Canon   = "https://www.bmv-kantinen.de/catering/potsdam/"
    },
    @{
        Path    = "$root\catering\werder-havel\index.php"
        City    = "Werder (Havel)"
        Slug    = "werder-havel"
        Title   = "Catering Werder (Havel) | BMV Menüdienst"
        Desc    = "Firmen-Catering direkt vor Ort in Werder (Havel): Meetings, Betriebsfeiern und Arbeitslunch vom lokalen Küchenbetrieb. Anfragen willkommen."
        Eyebrow = "Catering Werder (Havel)"
        Hero    = "Catering direkt aus der Region – für Werder (Havel)."
        Lead    = "Als lokaler Küchenbetrieb beliefern wir Unternehmen in Werder (Havel) mit Firmen-Catering ohne Zwischenstufe. Kurze Wege, frische Ware, verlässliche Zeiten."
        Nav     = "catering"
        Canon   = "https://www.bmv-kantinen.de/catering/werder-havel/"
    }
)

foreach ($page in $cateringPages) {
    if (Test-Path $page.Path) {
        Write-Skip "catering/$($page.Slug)/index.php"
        continue
    }

    $php = @"
<?php
require_once `$_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';

`$page_title       = '$($page.Title)';
`$meta_description = '$($page.Desc)';
`$active_nav       = '$($page.Nav)';
`$canonical        = '$($page.Canon)';

include `$_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>
<main id="main-content" role="main">

  <section class="page-hero page-hero--sm" aria-labelledby="hero-heading">
    <div class="page-hero__bg">
      <?= bmv_img('/assets/images/catering-setup.jpg', 'Catering $($page.City) durch BMV Menüdienst', 1600, 900, true, 'page-hero__bg-img') ?>
      <div class="page-hero__overlay" aria-hidden="true"></div>
    </div>
    <div class="container">
      <div class="page-hero__content" data-reveal>
        <span class="page-hero__label">$($page.Eyebrow)</span>
        <h1 class="page-hero__heading" id="hero-heading">$($page.Hero)</h1>
        <p class="page-hero__lead">$($page.Lead)</p>
        <div class="page-hero__actions">
          <a class="btn btn--primary" href="/kontakt/?betreff=catering">Catering anfragen</a>
          <a class="btn btn--ghost"   href="/catering/">Alle Catering-Infos</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="leistungen-heading">
    <div class="container">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Was wir liefern</span>
        <h2 class="section-title" id="leistungen-heading">Catering für jeden Anlass in $($page.City).</h2>
      </div>
      <div class="service-grid">
        <article class="service-card" data-reveal>
          <h3 class="service-card__title">Arbeitslunch &amp; Meeting-Catering</h3>
          <p>Warmes Mittagessen oder belegte Platten direkt ins Büro. Planbar, pünktlich und ohne eigenen Aufwand.</p>
          <div class="service-card__checks">
            <div class="service-card__check">Tages- und Wochenbestellungen möglich</div>
            <div class="service-card__check">Lieferung direkt ins Büro oder in den Konferenzraum</div>
          </div>
        </article>
        <article class="service-card" data-reveal>
          <h3 class="service-card__title">Betriebsfeiern &amp; Veranstaltungen</h3>
          <p>Sommerfeier, Jubiläum oder Kundenevent: Wir liefern frisch zubereitete Menüs, abgestimmt auf Ihre Teilnehmerzahl.</p>
          <div class="service-card__checks">
            <div class="service-card__check">Individuelle Menüabstimmung vorab</div>
            <div class="service-card__check">Auch kurzfristige Termine nach Absprache</div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="section final-cta" aria-labelledby="cta-heading">
    <div class="container container--narrow">
      <div class="section-header" data-reveal>
        <span class="section-header__eyebrow">Jetzt anfragen</span>
        <h2 class="section-title" id="cta-heading">Catering für $($page.City) direkt anfragen.</h2>
        <p class="section-sub">Beschreiben Sie kurz Ihren Anlass und wir melden uns mit einem konkreten Angebot.</p>
      </div>
      <div class="final-cta__actions" data-reveal>
        <a class="btn btn--primary" href="/kontakt/?betreff=catering">Anfrage senden</a>
        <a class="btn btn--ghost"   href="tel:+4933275745066">+49 3327 5745066</a>
      </div>
    </div>
  </section>

</main>
<?php include `$_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
"@

    Set-Content $page.Path $php -Encoding UTF8
    Write-Ok "catering/$($page.Slug)/index.php erstellt"
}

# ═══════════════════════════════════════════════════════════════
# FIX 3 – Speiseplan: doppeltes DOCTYPE entfernen
# speiseplan/index.php ruft include header.php auf (der bereits
# <!DOCTYPE html><html> schreibt) und danach kommt nochmals
# <!DOCTYPE html><html lang="de"><head> mit einem <style>-Block.
# Wir entfernen den zweiten DOCTYPE-Block inkl. <html> und <head>
# sowie das abschließende doppelte </body></html>.
# ═══════════════════════════════════════════════════════════════
Write-Step "Fix 3: Speiseplan doppeltes DOCTYPE"

$speiseplanPath = "$root\speiseplan\index.php"
$sp = Get-Content $speiseplanPath -Raw -Encoding UTF8

if ($sp -match "bmv-speiseplan-fixed") {
    Write-Skip "speiseplan/index.php (bereits gefixt)"
} else {
    # Das zweite DOCTYPE+html+head+body entfernen.
    # Pattern: nach dem include header.php kommt ?>  danach <!DOCTYPE html>
    # bis zum schließenden </style></head><body>
    # Wir suchen das Muster und entfernen die doppelte Hülle.

    # Schritt A: zweites <!DOCTYPE ... <body> entfernen
    $sp = $sp -replace '(?s)\?>\s*<!DOCTYPE html>\s*<html[^>]*>\s*<head>\s*<style>', "?>`n<!-- bmv-speiseplan-fixed -->`n<style>"

    # Schritt B: </head><body> unmittelbar nach dem Style-Block entfernen
    # Das Pattern endet auf </style> dann </head>\n<body>
    $sp = $sp -replace '(?s)(</style>)\s*</head>\s*<body>', '$1'

    # Schritt C: doppeltes </body></html> am Ende entfernen
    # Das File hat jetzt zwei </body></html> Blöcke (eines vom footer.php, eines von speiseplan selbst)
    # Wir entfernen das letzte nackte </body>\n</html> das nicht vom footer kommt
    $sp = $sp -replace '(?s)(include __DIR__ \. \Q/\E\.\./includes/footer\.php.*?\?>\s*)</body>\s*</html>\s*$', '$1'

    Set-Content $speiseplanPath $sp -Encoding UTF8
    Write-Ok "speiseplan/index.php (doppeltes DOCTYPE entfernt)"
}

# ═══════════════════════════════════════════════════════════════
# FIX 4 – Impressum: doppeltes DOCTYPE entfernen
# impressum/index.php schreibt zuerst <!DOCTYPE html><html><head>
# und danach include header.php der nochmals <!DOCTYPE html><html>
# schreibt. Hier ist die Reihenfolge umgekehrt zum Speiseplan.
# Wir wandeln die Seite um sodass sie dem Standard-Pattern folgt:
# Nur PHP-Variablen setzen, dann include header.php.
# ═══════════════════════════════════════════════════════════════
Write-Step "Fix 4: Impressum doppeltes DOCTYPE"

$impressumPath = "$root\impressum\index.php"
$imp = Get-Content $impressumPath -Raw -Encoding UTF8

if ($imp -match "bmv-impressum-fixed") {
    Write-Skip "impressum/index.php (bereits gefixt)"
} else {
    # Das Impressum hat ein eigenes <!DOCTYPE...><html><head>...</head><body>
    # und dann include header.php. Wir bauen die Datei nach dem
    # Standard-Pattern um: Variablen oben, include header.php,
    # dann <main>-Inhalt, dann include footer.php + </body></html>
    # Den <style>-Block verschieben wir in einen $page_extra_css hook.

    $newImpressum = @'
<?php
/* bmv-impressum-fixed */
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';

$page_title       = 'Impressum – BMV Menüdienst Werder (Havel)';
$meta_description = 'Impressum des BMV Menüdienst, Inhaber Jürgen Koschnick, Am Gutshof 6, 14542 Werder (Havel). Kontakt, Angaben nach § 5 TMG.';
$active_nav       = '';
$canonical        = 'https://www.bmv-kantinen.de/impressum/';
$meta_robots      = 'noindex, follow';

// Seitenspezifisches CSS wird über einen optionalen Hook in header.php
// als <style>-Block im <head> ausgegeben, sofern header.php das unterstützt.
// Andernfalls landet es inline nach dem header-Include ohne DOM-Schaden.
$page_inline_css = <<<'CSS'
.legal-content h2 { font-size: 1.5rem; color: var(--clr-primary); margin: 2.5rem 0 1rem; border-bottom: 2px solid var(--clr-primary-light); padding-bottom: .5rem; }
.legal-content h3 { font-size: 1.125rem; color: var(--clr-primary); margin: 1.75rem 0 .75rem; }
.legal-content p  { color: var(--clr-text-meta); line-height: 1.8; margin-bottom: 1rem; }
.legal-content ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
.legal-content ul li { color: var(--clr-text-meta); line-height: 1.8; margin-bottom: .4rem; }
.legal-content table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; font-size: .9375rem; }
.legal-content th { background: var(--clr-primary); color: #fff; padding: .75rem 1rem; text-align: left; font-weight: 500; }
.legal-content td { padding: .75rem 1rem; border-bottom: 1px solid var(--clr-border); color: var(--clr-text-meta); vertical-align: top; }
.legal-content tr:nth-child(even) td { background: var(--clr-bg-section); }
.legal-content a { color: var(--clr-accent); font-weight: 500; }
.legal-content .highlight-box { background: var(--clr-accent-light); border-left: 4px solid var(--clr-accent); padding: 1rem 1.5rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-bottom: 1.5rem; }
.legal-content .highlight-box p { color: var(--clr-accent-dark); margin: 0; font-weight: 500; }
CSS;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';

// Inline-CSS als Fallback falls header.php keinen Hook hat
if (!empty($page_inline_css)): ?>
<style><?= $page_inline_css ?></style>
<?php endif; ?>

<main id="main-content" role="main">
  <section class="page-hero page-hero--sm" aria-labelledby="page-hero-heading">
    <div class="container">
      <nav class="breadcrumb" aria-label="Brotkrumennavigation">
        <ol class="breadcrumb__list" role="list">
          <li class="breadcrumb__item"><a class="breadcrumb__link" href="/">Startseite</a></li>
          <li class="breadcrumb__item breadcrumb__item--current" aria-current="page">Impressum</li>
        </ol>
      </nav>
      <p class="eyebrow">Rechtliches</p>
      <h1 class="heading-lg page-hero__heading text-balance" id="page-hero-heading">Impressum</h1>
      <p class="page-hero__lead">Angaben gemäß § 5 TMG – Verantwortlicher für diese Website und alle rechtlich relevanten Kontaktinformationen.</p>
    </div>
  </section>
  <section class="section">
    <div class="container container--narrow">
      <div class="legal-content" style="max-width:72ch;">

<h2>Angaben gemäß § 5 TMG</h2>
<p>
  <strong>BMV Menüdienst</strong><br>
  Inhaber: Jürgen Koschnick<br>
  Am Gutshof 6<br>
  14542 Werder (Havel)<br>
  Deutschland
</p>

<h2>Kontakt</h2>
<p>
  Telefon: <a href="tel:+4933275745066">+49 (0)3327 574 50 66</a><br>
  Telefax: +49 (0)3327 574 50 22<br>
  E-Mail: <a href="mailto:info@bmv-kantinen.de">info@bmv-kantinen.de</a>
</p>

<h2>Umsatzsteuer-Identifikationsnummer</h2>
<p>Sofern vorhanden, wird die USt-IdNr. gemäß § 27a Umsatzsteuergesetz auf Anfrage mitgeteilt.</p>

<h2>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h2>
<p>
  Jürgen Koschnick<br>
  Am Gutshof 6<br>
  14542 Werder (Havel)
</p>

<h2>Streitschlichtung</h2>
<p>Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit:
<a href="https://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener noreferrer">https://ec.europa.eu/consumers/odr/</a>.</p>
<p>Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.</p>

<h2>Haftung für Inhalte</h2>
<p>Als Diensteanbieter sind wir gemäß § 7 Abs. 1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen.</p>

<h2>Haftung für Links</h2>
<p>Unser Angebot enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen Einfluss haben. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber verantwortlich.</p>

<h2>Urheberrecht</h2>
<p>Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers.</p>

      </div>
    </div>
  </section>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
'@

    Set-Content $impressumPath $newImpressum -Encoding UTF8
    Write-Ok "impressum/index.php (auf Standard-Shell umgestellt, doppeltes DOCTYPE entfernt)"
}

# ═══════════════════════════════════════════════════════════════
# ZUSAMMENFASSUNG
# ═══════════════════════════════════════════════════════════════
Write-Host "`n" + ("=" * 62) -ForegroundColor White
Write-Host " Fertig. Übersicht der durchgeführten Fixes:" -ForegroundColor White
Write-Host ("=" * 62) -ForegroundColor White
Write-Host ""
Write-Host " Fix 1  Kontaktformular-Handler      kontakt/index.php" -ForegroundColor Yellow
Write-Host "        fetch() + Status-Block + Error-Rendering"
Write-Host ""
Write-Host " Fix 2  Catering-Subseiten            catering/potsdam/"
Write-Host "        (neue index.php je Stadt)     catering/werder-havel/" -ForegroundColor Yellow
Write-Host ""
Write-Host " Fix 3  Speiseplan DOM-Struktur       speiseplan/index.php" -ForegroundColor Yellow
Write-Host "        doppeltes DOCTYPE entfernt"
Write-Host ""
Write-Host " Fix 4  Impressum DOM-Struktur        impressum/index.php" -ForegroundColor Yellow
Write-Host "        auf Standard-Shell umgestellt"
Write-Host ""
Write-Host " Zum Testen: Docker-Container neu starten und alle vier" -ForegroundColor DarkGray
Write-Host " Seiten im Browser öffnen. Formular-Netzwerktraffic in" -ForegroundColor DarkGray
Write-Host " DevTools prüfen (POST auf /kontakt/send.php, JSON zurück)." -ForegroundColor DarkGray
Write-Host ""
