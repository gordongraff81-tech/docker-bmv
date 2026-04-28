/**
 * main.js – BMV Menüdienst
 * Enthält: Mobile Nav, Sticky Header, FAQ Accordion, Fade-up Observer,
 *          Kontaktformular-Handling, Footer Jahr
 */
(function () {
  'use strict';

  /* ── Footer: Aktuelles Jahr ──────────────────────────────── */
  var yearEl = document.getElementById('current-year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  /* ── Sticky Header Scroll-Effekt ─────────────────────────── */
  var header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', function () {
      // CSS nutzt aktuell ".site-header.scrolled"
      header.classList.toggle('scrolled', window.scrollY > 20);
      // Backwards-compat (falls irgendwo noch ".is-scrolled" gestyled wird)
      header.classList.toggle('is-scrolled', window.scrollY > 20);
    }, { passive: true });
  }

  /* ── Mobile Navigation Toggle ────────────────────────────── */
  var toggle  = document.getElementById('nav-toggle');
  var mobileNav = document.getElementById('site-nav');

  if (toggle && mobileNav) {
    toggle.addEventListener('click', function () {
      var expanded = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!expanded));
      mobileNav.classList.toggle('is-open', !expanded);
      mobileNav.setAttribute('aria-hidden', String(expanded));
      document.body.style.overflow = expanded ? '' : 'hidden';
    });

    // Schließen bei Klick außerhalb
    document.addEventListener('click', function (e) {
      if (!toggle.contains(e.target) && !mobileNav.contains(e.target)) {
        toggle.setAttribute('aria-expanded', 'false');
        mobileNav.classList.remove('is-open');
        mobileNav.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
    });

    // Schließen bei Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && mobileNav.classList.contains('is-open')) {
        toggle.setAttribute('aria-expanded', 'false');
        mobileNav.classList.remove('is-open');
        mobileNav.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        toggle.focus();
      }
    });
  }

  /* ── FAQ Accordion ───────────────────────────────────────── */
  var faqButtons = document.querySelectorAll('.faq-item__question');
  if (faqButtons.length) {
    faqButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var expanded = btn.getAttribute('aria-expanded') === 'true';
        var answerId = btn.getAttribute('aria-controls');
        var answer   = document.getElementById(answerId);

        // Alle schließen
        faqButtons.forEach(function (b) {
          b.setAttribute('aria-expanded', 'false');
          var aId = b.getAttribute('aria-controls');
          var a   = document.getElementById(aId);
          if (a) a.classList.remove('is-open');
        });

        // Angeklicktes togglen
        if (!expanded && answer) {
          btn.setAttribute('aria-expanded', 'true');
          answer.classList.add('is-open');
        }
      });
    });
  }

  /* ── Fade-up Intersection Observer ───────────────────────── */
  var fadeEls = document.querySelectorAll('.fade-up');
  if (fadeEls.length) {
    if (!('IntersectionObserver' in window)) {
      // Fallback: alle sofort sichtbar
      fadeEls.forEach(function (el) { el.classList.add('is-visible'); });
    } else {
      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

      fadeEls.forEach(function (el) { observer.observe(el); });
    }
  }

  /* ── Kontaktformular ─────────────────────────────────────── */
  var contactForm = document.getElementById('contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      var submitBtn = contactForm.querySelector('[type="submit"]');
      var originalText = submitBtn ? submitBtn.textContent : '';

      // Validierung
      var required = contactForm.querySelectorAll('[required]');
      var valid = true;
      required.forEach(function (field) {
        if (!field.value.trim()) {
          field.style.borderColor = '#DC2626';
          valid = false;
        } else {
          field.style.borderColor = '';
        }
      });

      if (!valid) {
        showToast('Bitte füllen Sie alle Pflichtfelder aus.', 'error');
        return;
      }

      // Absenden
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Wird gesendet…';
      }

      var formData = new FormData(contactForm);

      fetch(contactForm.action || '/kontakt/send.php', {
        method: 'POST',
        body: formData
      })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = originalText;
        }
        if (data.success) {
          showToast('Vielen Dank! Wir melden uns in Kürze bei Ihnen.', 'success');
          contactForm.reset();
        } else {
          showToast(data.message || 'Fehler beim Senden. Bitte rufen Sie uns an.', 'error');
        }
      })
      .catch(function () {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = originalText;
        }
        showToast('Verbindungsfehler. Bitte rufen Sie uns an: +49 3327 5745066', 'error');
      });
    });
  }

  /* ── Toast Notification ──────────────────────────────────── */
  function showToast(message, type) {
    var existing = document.getElementById('bmv-toast');
    if (existing) existing.remove();

    var toast = document.createElement('div');
    toast.id = 'bmv-toast';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.style.cssText = [
      'position:fixed',
      'bottom:1.5rem',
      'right:1.5rem',
      'z-index:9999',
      'padding:.85rem 1.4rem',
      'border-radius:10px',
      'box-shadow:0 4px 20px rgba(0,0,0,.2)',
      'font-weight:600',
      'font-size:.95rem',
      'display:flex',
      'align-items:center',
      'gap:.5rem',
      'max-width:380px',
      'transform:translateY(100px)',
      'opacity:0',
      'transition:all .35s cubic-bezier(.34,1.56,.64,1)',
      'font-family:inherit',
      type === 'error'
        ? 'background:#DC2626;color:#fff'
        : 'background:#16a34a;color:#fff'
    ].join(';');

    var icon = type === 'error'
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>'
      : '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>';

    toast.innerHTML = icon + '<span>' + message + '</span>';
    document.body.appendChild(toast);

    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';
      });
    });

    setTimeout(function () {
      toast.style.transform = 'translateY(100px)';
      toast.style.opacity = '0';
      setTimeout(function () { if (toast.parentNode) toast.remove(); }, 400);
    }, 4500);
  }

  /* ── Active Nav Link ─────────────────────────────────────── */
  var currentPath = window.location.pathname;
  var navLinks = document.querySelectorAll('.primary-nav__link');
  navLinks.forEach(function (link) {
    var href = link.getAttribute('href');
    if (href && href !== '/' && currentPath.indexOf(href) === 0) {
      link.classList.add('primary-nav__link--active');
      link.setAttribute('aria-current', 'page');
    }
  });

})();
