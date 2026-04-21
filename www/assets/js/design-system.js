/**
 * BMV Menüdienst — Enterprise Design System JS
 * Scroll animations · Sticky nav · Micro-interactions
 */

(function () {
  'use strict';

  /* ── 1. STICKY HEADER ─────────────────────────────────────────── */
  const header = document.querySelector('.site-header');
  if (header) {
    const onScroll = () => {
      header.classList.toggle('scrolled', window.scrollY > 24);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ── 2. MOBILE NAV TOGGLE ─────────────────────────────────────── */
  const toggle = document.querySelector('.nav-toggle');
  const nav    = document.querySelector('.site-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open);
      document.body.style.overflow = open ? 'hidden' : '';
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
      if (nav.classList.contains('open') &&
          !nav.contains(e.target) &&
          !toggle.contains(e.target)) {
        nav.classList.remove('open');
        document.body.style.overflow = '';
      }
    });

    // Close on link click
    nav.querySelectorAll('.site-nav__link').forEach(link => {
      link.addEventListener('click', () => {
        nav.classList.remove('open');
        document.body.style.overflow = '';
      });
    });
  }

  /* ── 3. ACTIVE NAV LINK ───────────────────────────────────────── */
  const currentPath = window.location.pathname.replace(/\/$/, '') || '/';
  document.querySelectorAll('.site-nav__link').forEach(link => {
    const href = link.getAttribute('href')?.replace(/\/$/, '') || '';
    if (href === currentPath ||
        (href !== '' && href !== '/' && currentPath.startsWith(href))) {
      link.classList.add('active');
    }
  });

  /* ── 4. HERO LOAD ANIMATION ───────────────────────────────────── */
  const hero = document.querySelector('.hero');
  if (hero) {
    // Slight delay so CSS animations play after paint
    requestAnimationFrame(() => {
      setTimeout(() => hero.classList.add('loaded'), 100);
    });
  }

  /* ── 5. SCROLL REVEAL ─────────────────────────────────────────── */
  const revealEls = document.querySelectorAll('.reveal, .stagger');
  if (revealEls.length && 'IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          io.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.12,
      rootMargin: '0px 0px -40px 0px'
    });

    revealEls.forEach(el => io.observe(el));
  } else {
    // Fallback: show all immediately
    revealEls.forEach(el => el.classList.add('visible'));
  }

  /* ── 6. SMOOTH ANCHOR SCROLLING ───────────────────────────────── */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', (e) => {
      const target = document.querySelector(anchor.getAttribute('href'));
      if (!target) return;
      e.preventDefault();
      const offset = 80; // header height
      const top = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top, behavior: 'smooth' });
    });
  });

  /* ── 7. IMAGE LAZY LOAD with FADE-IN ──────────────────────────── */
  if ('IntersectionObserver' in window) {
    const imgObs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.addEventListener('load', () => {
              img.style.opacity = '1';
            });
          }
          imgObs.unobserve(img);
        }
      });
    }, { rootMargin: '200px' });

    document.querySelectorAll('img[data-src]').forEach(img => {
      img.style.opacity = '0';
      img.style.transition = 'opacity .4s ease';
      imgObs.observe(img);
    });
  }

  /* ── 8. PEXELS IMAGE PROGRESSIVE LOAD ────────────────────────── */
  // For dish cards with data-query attribute
  document.querySelectorAll('[data-pexels-query]').forEach(async (el) => {
    const query = el.dataset.pexelsQuery;
    const img   = el.querySelector('img');
    if (!img || !query) return;

    try {
      const res  = await fetch(`/api/pexels_image.php?q=${encodeURIComponent(query)}`);
      const data = await res.json();
      if (data.url) {
        const newImg = new Image();
        newImg.onload = () => {
          img.src = newImg.src;
          img.style.opacity = '1';
        };
        newImg.src = data.url_medium || data.url;
        img.style.opacity = '.4';
        img.style.transition = 'opacity .6s ease';
      }
    } catch (_) {
      // Silent fail — placeholder stays
    }
  });

  /* ── 9. FORM ENHANCEMENTS ─────────────────────────────────────── */
  // Live validation feedback
  document.querySelectorAll('.form-control').forEach(input => {
    const showState = () => {
      if (input.value === '') return;
      input.setAttribute('data-touched', '');
    };
    input.addEventListener('blur', showState);
  });

  // Floating label enhancement (if .form-group has label + input)
  document.querySelectorAll('.form-group').forEach(group => {
    const label = group.querySelector('.form-label');
    const input = group.querySelector('.form-control');
    if (!label || !input) return;

    const update = () => {
      group.classList.toggle('has-value', input.value.trim() !== '');
    };
    input.addEventListener('input', update);
    update();
  });

  /* ── 10. BUTTON RIPPLE EFFECT ─────────────────────────────────── */
  document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
      const rect   = btn.getBoundingClientRect();
      const x      = e.clientX - rect.left;
      const y      = e.clientY - rect.top;
      const ripple = document.createElement('span');

      ripple.style.cssText = `
        position: absolute;
        width: 4px; height: 4px;
        border-radius: 50%;
        background: rgba(255,255,255,.35);
        transform: translate(-50%, -50%) scale(0);
        animation: ripple-out .5s ease-out forwards;
        left: ${x}px; top: ${y}px;
        pointer-events: none;
      `;

      btn.appendChild(ripple);
      setTimeout(() => ripple.remove(), 500);
    });
  });

  // Add ripple keyframe once
  if (!document.getElementById('bmv-ripple-style')) {
    const style = document.createElement('style');
    style.id = 'bmv-ripple-style';
    style.textContent = `
      @keyframes ripple-out {
        to { transform: translate(-50%,-50%) scale(40); opacity: 0; }
      }
    `;
    document.head.appendChild(style);
  }

  /* ── 11. CART QUANTITY ANIMATION ──────────────────────────────── */
  const cartCount = document.querySelector('.cart__count');
  let prevCount = cartCount ? parseInt(cartCount.textContent) : 0;

  const observeCart = () => {
    if (!cartCount) return;
    const observer = new MutationObserver(() => {
      const newCount = parseInt(cartCount.textContent);
      if (newCount !== prevCount) {
        cartCount.animate([
          { transform: 'scale(1.5)', background: '#f06800' },
          { transform: 'scale(1)',   background: '#f06800' }
        ], { duration: 300, easing: 'cubic-bezier(0.34,1.56,0.64,1)' });
        prevCount = newCount;
      }
    });
    observer.observe(cartCount, { childList: true, characterData: true, subtree: true });
  };
  observeCart();

  /* ── 12. TABLE ROW HIGHLIGHT ──────────────────────────────────── */
  document.querySelectorAll('tbody tr').forEach(row => {
    row.style.transition = 'background 150ms ease';
  });

  /* ── 13. SECTION COUNTER ANIMATION ───────────────────────────── */
  const animateCounter = (el) => {
    const target = parseFloat(el.dataset.target || el.textContent);
    const suffix = el.dataset.suffix || '';
    const isDecimal = target % 1 !== 0;
    const duration = 1800;
    const start = performance.now();

    const tick = (now) => {
      const elapsed  = now - start;
      const progress = Math.min(elapsed / duration, 1);
      // Ease out expo
      const eased = 1 - Math.pow(1 - progress, 4);
      const value = target * eased;

      el.textContent = (isDecimal ? value.toFixed(1) : Math.round(value)) + suffix;
      if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  };

  const counterEls = document.querySelectorAll('[data-counter]');
  if (counterEls.length && 'IntersectionObserver' in window) {
    const cio = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          cio.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    counterEls.forEach(el => cio.observe(el));
  }

  /* ── 14. SYSTEM TABS (Admin) ──────────────────────────────────── */
  document.querySelectorAll('.system-tabs').forEach(tabs => {
    tabs.querySelectorAll('.system-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.querySelectorAll('.system-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        // Dispatch custom event for admin JS to listen to
        tabs.dispatchEvent(new CustomEvent('tab-change', {
          bubbles: true,
          detail: { system: tab.dataset.system }
        }));
      });
    });
  });

  /* ── 15. PRINT TRIGGER ────────────────────────────────────────── */
  document.querySelectorAll('[data-print]').forEach(btn => {
    btn.addEventListener('click', () => {
      // Calculate scale for A4 landscape
      const printArea = document.querySelector('.print-area');
      if (printArea) {
        const scaleX = (297 * 3.7795) / printArea.scrollWidth;
        const scaleY = (210 * 3.7795) / printArea.scrollHeight;
        const scale  = Math.min(scaleX, scaleY, 1);
        printArea.style.transform       = `scale(${scale})`;
        printArea.style.transformOrigin = 'top left';
      }
      window.print();
      if (printArea) {
        setTimeout(() => {
          printArea.style.transform = '';
        }, 500);
      }
    });
  });

})();
