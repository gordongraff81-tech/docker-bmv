(function () {
  'use strict';

  var body = document.body;
  var header = document.querySelector('.site-header');
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.querySelector('.site-nav');

  function setHeaderState() {
    if (!header) {
      return;
    }
    header.classList.toggle('scrolled', window.scrollY > 20);
  }

  function closeNav() {
    if (!toggle || !nav) {
      return;
    }

    nav.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    body.style.overflow = '';
  }

  function openNav() {
    if (!toggle || !nav) {
      return;
    }

    nav.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
    body.style.overflow = 'hidden';
  }

  if (header) {
    window.addEventListener('scroll', setHeaderState, { passive: true });
    setHeaderState();
  }

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      if (nav.classList.contains('is-open')) {
        closeNav();
      } else {
        openNav();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closeNav();
      }
    });

    document.addEventListener('click', function (event) {
      if (!nav.classList.contains('is-open')) {
        return;
      }

      if (nav.contains(event.target) || toggle.contains(event.target)) {
        return;
      }

      closeNav();
    });

    nav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        if (window.matchMedia('(max-width: 900px)').matches) {
          closeNav();
        }
      });
    });
  }

  document.querySelectorAll('.site-nav__dropdown-toggle').forEach(function (toggleLink) {
    toggleLink.addEventListener('click', function (event) {
      if (!window.matchMedia('(max-width: 900px)').matches) {
        return;
      }

      var parent = toggleLink.closest('.site-nav__dropdown');
      if (!parent) {
        return;
      }

      event.preventDefault();
      var open = !parent.classList.contains('is-open');
      parent.classList.toggle('is-open', open);
      toggleLink.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  document.querySelectorAll('[data-reveal], .fade-up').forEach(function (element) {
    if (!('IntersectionObserver' in window)) {
      element.classList.add('revealed');
      element.classList.add('is-visible');
      return;
    }
  });

  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) {
          return;
        }

        entry.target.classList.add('revealed');
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    }, {
      threshold: 0.12,
      rootMargin: '0px 0px -40px 0px'
    });

    document.querySelectorAll('[data-reveal], .fade-up').forEach(function (element) {
      observer.observe(element);
    });
  }

  document.querySelectorAll('.img-wrap').forEach(function (wrap) {
    var image = wrap.querySelector('img');

    if (!image) {
      return;
    }

    function markLoaded() {
      wrap.classList.add('img-loaded');
      image.classList.add('is-loaded');
    }

    if (image.complete && image.naturalWidth > 0) {
      markLoaded();
      return;
    }

    image.addEventListener('load', markLoaded, { once: true });
    image.addEventListener('error', function () {
      wrap.classList.add('img-loaded');
    }, { once: true });
  });

  document.querySelectorAll('.faq-item').forEach(function (item) {
    var button = item.querySelector('.faq-item__question');
    var answer = item.querySelector('.faq-item__answer');

    if (!button || !answer) {
      return;
    }

    button.addEventListener('click', function () {
      var open = !item.classList.contains('is-open');
      item.classList.toggle('is-open', open);
      button.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  document.querySelectorAll('.menu-day-card[role="button"]').forEach(function (card) {
    card.addEventListener('keydown', function (event) {
      if (event.key !== 'Enter' && event.key !== ' ') {
        return;
      }

      event.preventDefault();
      card.click();
    });
  });
})();
