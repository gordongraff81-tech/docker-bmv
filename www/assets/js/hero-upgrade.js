/**
 * hero-upgrade.js – BMV Menüdienst
 * Parallax-Effekt + Dynamisches Bild-Mapping je Seite
 * Einbinden: <script src="/assets/js/hero-upgrade.js" defer></script>
 */
(function () {
  'use strict';

  /* ── Bild-Mapping je URL-Pfad ── */
  var IMAGE_MAP = {
    '/':                                    '/assets/images/hero-home.jpg',
    '/essen-auf-raedern/':                  '/assets/images/essen-auf-raedern-lieferung.jpg',
    '/essen-auf-raedern/potsdam/':          '/assets/images/potsdam-lieferung.jpg',
    '/essen-auf-raedern/werder-havel/':     '/assets/images/werder-havel-standort.jpg',
    '/essen-auf-raedern/umland/':           '/assets/images/umland-lieferung.jpg',
    '/catering/':                           '/assets/images/catering-setup.jpg',
    '/catering/potsdam/':                   '/assets/images/catering-potsdam.jpg',
    '/catering/werder-havel/':              '/assets/images/catering-werder.jpg',
    '/kantine-am-gutshof/':                 '/assets/images/kantine-gutshof.jpg',
    '/speiseplan/':                         '/assets/images/speiseplan-kueche.jpg',
    '/ueber-uns/':                          '/assets/images/ueber-uns-team.jpg',
    '/kontakt/':                            '/assets/images/hero-home.jpg',
    '/agb/':                                '/assets/images/speiseplan-kueche.jpg',
    '/impressum/':                          '/assets/images/speiseplan-kueche.jpg',
    '/datenschutz/':                        '/assets/images/speiseplan-kueche.jpg',
  };

  /* Fallback-Bild wenn kein Mapping vorhanden */
  var FALLBACK = '/assets/images/hero-home.jpg';

  /* ── Hero-Bild einbauen ── */
  function initHeroImage() {
    var hero = document.querySelector('.page-hero');
    if (!hero) return;

    /* Aktuellen Pfad ermitteln */
    var path = window.location.pathname;
    /* Normalisieren: immer mit Slash enden */
    if (path !== '/' && path.slice(-1) !== '/') path += '/';

    /* Bild bestimmen */
    var imgSrc = IMAGE_MAP[path] || FALLBACK;

    /* Bild-Layer in Hero einfügen */
    var bg = document.createElement('div');
    bg.className = 'page-hero__bg';
    bg.setAttribute('aria-hidden', 'true');

    var img = document.createElement('img');
    img.src       = imgSrc;
    img.alt       = '';
    img.className = 'page-hero__bg-img';
    img.loading   = 'eager';
    img.fetchpriority = 'high';

    /* Overlay */
    var overlay = document.createElement('div');
    overlay.className = 'page-hero__overlay';
    overlay.setAttribute('aria-hidden', 'true');

    bg.appendChild(img);
    hero.insertBefore(bg, hero.firstChild);
    hero.insertBefore(overlay, hero.children[1]);

    /* Content-Wrapper für Animation */
    var container = hero.querySelector('.container');
    if (container && !container.classList.contains('page-hero__content')) {
      container.classList.add('page-hero__content');
    }

    /* Fehler-Fallback: kein Bild → no-image Klasse */
    img.onerror = function () {
      hero.classList.add('page-hero--no-image');
      bg.style.display = 'none';
    };
  }

  /* ── Parallax-Effekt ── */
  function initParallax() {
    var bg = document.querySelector('.page-hero__bg');
    if (!bg) return;

    /* Auf Mobile deaktivieren — Performance */
    if (window.matchMedia('(max-width: 768px)').matches) return;
    /* Reduced motion respektieren */
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var hero     = document.querySelector('.page-hero');
    var heroTop  = 0;
    var heroH    = 0;
    var ticking  = false;
    var lastY    = 0;

    function measure() {
      var rect = hero.getBoundingClientRect();
      heroTop  = rect.top + window.scrollY;
      heroH    = rect.height;
    }

    function update() {
      var scrollY  = window.scrollY;
      var relative = scrollY - heroTop;
      /* Parallax-Faktor: 0.35 = sanft, nicht aufdringlich */
      var offset   = relative * 0.35;
      /* Nur wenn Hero sichtbar */
      if (relative > -heroH && relative < heroH) {
        bg.style.transform = 'translateY(' + offset + 'px)';
      }
      ticking = false;
    }

    function onScroll() {
      lastY = window.scrollY;
      if (!ticking) {
        requestAnimationFrame(update);
        ticking = true;
      }
    }

    measure();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', measure, { passive: true });
  }

  /* ── Init ── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initHeroImage();
      initParallax();
    });
  } else {
    initHeroImage();
    initParallax();
  }

})();
