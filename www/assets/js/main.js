/**
 * main.js – BMV Menüdienst v3.0
 * Sticky header, mobile nav, dropdowns, scroll animations,
 * FAQ accordion, contact form, toast notifications.
 */
(function () {
  'use strict';

  /* ── Sticky Header ── */
  var header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', function () {
      header.classList.toggle('scrolled', window.scrollY > 32);
    }, { passive: true });
  }

  /* ── Mobile Nav Toggle ── */
  var toggle   = document.getElementById('nav-toggle');
  var mobileNav = document.getElementById('site-nav');

  if (toggle && mobileNav) {
    toggle.addEventListener('click', function () {
      var expanded = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!expanded));
      mobileNav.classList.toggle('is-open', !expanded);
      document.body.style.overflow = expanded ? '' : 'hidden';
    });
    document.addEventListener('click', function (e) {
      if (!toggle.contains(e.target) && !mobileNav.contains(e.target)) {
        toggle.setAttribute('aria-expanded', 'false');
        mobileNav.classList.remove('is-open');
        document.body.style.overflow = '';
      }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && mobileNav.classList.contains('is-open')) {
        toggle.setAttribute('aria-expanded', 'false');
        mobileNav.classList.remove('is-open');
        document.body.style.overflow = '';
        toggle.focus();
      }
    });
  }

  /* ── Dropdown Nav (mobile click, desktop hover via CSS) ── */
  document.querySelectorAll('.site-nav__dropdown').forEach(function (dd) {
    var t = dd.querySelector('.site-nav__dropdown-toggle');
    if (!t) return;
    t.addEventListener('click', function (e) {
      if (window.innerWidth < 769) {
        e.preventDefault();
        dd.classList.toggle('is-open');
        t.setAttribute('aria-expanded', dd.classList.contains('is-open') ? 'true' : 'false');
      }
    });
  });

  /* ── Scroll Animations (fade-up / fade-in) ── */
  var fadeEls = document.querySelectorAll('.fade-up, .fade-in');
  if (fadeEls.length) {
    if (!('IntersectionObserver' in window)) {
      fadeEls.forEach(function (el) { el.classList.add('is-visible'); });
    } else {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target);
          }
        });
      }, { threshold: 0.08, rootMargin: '0px 0px -48px 0px' });
      fadeEls.forEach(function (el) { io.observe(el); });
    }
  }

  /* ── Image wrapper shimmer ── */
  document.querySelectorAll('.img-wrap').forEach(function (el) {
    var img = el.querySelector('img');
    if (!img) return;
    if (img.complete && img.naturalWidth) { el.classList.add('img-loaded'); }
    else {
      img.addEventListener('load',  function () { el.classList.add('img-loaded'); });
      img.addEventListener('error', function () { el.classList.add('img-loaded'); });
    }
  });

  /* ── FAQ Accordion ── */
  document.querySelectorAll('.faq-item__question').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var expanded = btn.getAttribute('aria-expanded') === 'true';
      /* close all */
      document.querySelectorAll('.faq-item__question').forEach(function (b) {
        b.setAttribute('aria-expanded', 'false');
        var a = document.getElementById(b.getAttribute('aria-controls'));
        if (a) a.classList.remove('is-open');
      });
      /* open clicked if it was closed */
      if (!expanded) {
        btn.setAttribute('aria-expanded', 'true');
        var answer = document.getElementById(btn.getAttribute('aria-controls'));
        if (answer) answer.classList.add('is-open');
      }
    });
  });

  /* ── Contact Form ── */
  var form = document.getElementById('contact-form');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var submitBtn = form.querySelector('[type="submit"]');
      var origText  = submitBtn ? submitBtn.textContent : '';
      var required  = form.querySelectorAll('[required]');
      var valid     = true;

      required.forEach(function (f) {
        if (!f.value.trim()) { f.style.borderColor = '#DC2626'; valid = false; }
        else { f.style.borderColor = ''; }
      });

      if (!valid) { showToast('Bitte füllen Sie alle Pflichtfelder aus.', 'error'); return; }

      if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Wird gesendet…'; }

      fetch(form.action || '/kontakt/send.php', { method: 'POST', body: new FormData(form) })
        .then(function (r) { return r.json(); })
        .then(function (d) {
          if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = origText; }
          if (d.success) { showToast('Vielen Dank! Wir melden uns in Kürze.', 'success'); form.reset(); }
          else { showToast(d.message || 'Fehler beim Senden. Bitte rufen Sie uns an.', 'error'); }
        })
        .catch(function () {
          if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = origText; }
          showToast('Verbindungsfehler. Bitte rufen Sie uns an: +49 3327 5745066', 'error');
        });
    });
  }

  /* ── Toast Notification ── */
  function showToast(msg, type) {
    var old = document.getElementById('bmv-toast');
    if (old) old.remove();
    var t = document.createElement('div');
    t.id = 'bmv-toast';
    t.setAttribute('role', 'alert');
    t.setAttribute('aria-live', 'assertive');
    t.style.cssText = 'position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;padding:.85rem 1.4rem;border-radius:50px;box-shadow:0 8px 32px rgba(0,0,0,.2);font-weight:600;font-size:.95rem;display:flex;align-items:center;gap:.5rem;max-width:380px;transform:translateY(80px);opacity:0;transition:all .4s cubic-bezier(.34,1.56,.64,1);font-family:inherit;' + (type === 'error' ? 'background:#DC2626;color:#fff' : 'background:#059669;color:#fff');
    t.innerHTML = (type === 'error'
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>'
      : '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>')
      + '<span>' + msg + '</span>';
    document.body.appendChild(t);
    requestAnimationFrame(function () {
      requestAnimationFrame(function () { t.style.transform = 'translateY(0)'; t.style.opacity = '1'; });
    });
    setTimeout(function () {
      t.style.transform = 'translateY(80px)'; t.style.opacity = '0';
      setTimeout(function () { if (t.parentNode) t.remove(); }, 450);
    }, 4500);
  }

})();
