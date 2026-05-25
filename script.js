 /* ============================================================
   DRISHYAAM SIGNAGE PVT. LTD. – script.js v3.0
   Complete, production-ready JavaScript
   ============================================================ */
'use strict';

/* ── PAGE LOADER ── dismiss after assets ready ── */
(function initLoader() {
  const loader = document.getElementById('pageLoader');
  if (!loader) return;
  const hide = () => {
    loader.classList.add('hidden');
    setTimeout(() => { loader.style.display = 'none'; }, 600);
  }; 
  if (document.readyState === 'complete') {
    setTimeout(hide, 1600);
  } else {
    window.addEventListener('load', () => setTimeout(hide, 1600));
  }
  // Safety fallback – never block page more than 3.5s
  setTimeout(hide, 3500);
})();

document.addEventListener('DOMContentLoaded', async () => {
  await loadDynamicData();
  initCursorGlow();
  initNavbar();
  initMobileMenu();
  initSmoothScroll();
  initScrollReveal();
  initHeroReveal();
  initParticles();
  initCounters();
  initHeroSlider();
  initFeaturedSlider();
  initGalleryFilter();
  initLightbox();
  initContactForm();
  initActiveNavLink();
  initFloatingBtns();
  initBackToTop();
  initTouchHover();
  initEnquiryModal();
  initPageTransition();
});

/* ── CURSOR GLOW ── */
function initCursorGlow() {
  const glow = document.getElementById('cursorGlow');
  if (!glow || window.innerWidth < 1024) { if (glow) glow.style.display = 'none'; return; }
  let raf;
  document.addEventListener('mousemove', e => {
    cancelAnimationFrame(raf);
    raf = requestAnimationFrame(() => {
      glow.style.left = e.clientX + 'px';
      glow.style.top  = e.clientY + 'px';
    });
  });
}

/* ── NAVBAR ── */
function initNavbar() {
  const nav = document.getElementById('navbar');
  if (!nav) return;
  const tick = () => nav.classList.toggle('scrolled', window.scrollY > 60);
  window.addEventListener('scroll', tick, { passive: true });
  tick();
}

/* ── MOBILE MENU ── */
function initMobileMenu() {
  const btn  = document.getElementById('hamburger');
  const menu = document.getElementById('navMenu');
  if (!btn || !menu) return;

  const close = () => {
    btn.classList.remove('open');
    menu.classList.remove('open');
    document.body.style.overflow = '';
  };

  btn.addEventListener('click', e => {
    e.stopPropagation();
    const isOpen = menu.classList.toggle('open');
    btn.classList.toggle('open', isOpen);
    document.body.style.overflow = isOpen ? 'hidden' : '';
  });

  menu.querySelectorAll('.nav-link').forEach(l => l.addEventListener('click', close));
  document.addEventListener('click', e => {
    if (!btn.contains(e.target) && !menu.contains(e.target)) close();
  });
}

/* ── SMOOTH SCROLL ── */
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const id = a.getAttribute('href');
      if (!id || id === '#') return;
      const target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      const navH = (document.getElementById('navbar')?.offsetHeight || 75) + 8;
      window.scrollTo({
        top: target.getBoundingClientRect().top + window.pageYOffset - navH,
        behavior: 'smooth'
      });
    });
  });
}

/* ── SCROLL REVEAL ── */
function initScrollReveal() {
  const selectors = '[data-reveal], .service-card, .product-card, .gallery-item, .why-item';
  const elements  = document.querySelectorAll(selectors);
  if (!elements.length) return;

  const io = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el    = entry.target;
      const delay = parseInt(el.dataset.delay || 0, 10);
      setTimeout(() => el.classList.add('revealed'), delay);
      io.unobserve(el);
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -50px 0px' });

  elements.forEach(el => io.observe(el));
}

/* ── HERO REVEAL ── */
function initHeroReveal() {
  setTimeout(() => document.querySelector('.hero-content')?.classList.add('revealed'), 1700);
  setTimeout(() => document.querySelector('.hero-visual')?.classList.add('revealed'),  1900);
}

/* ── PARTICLES ── */
function initParticles() {
  const container = document.getElementById('heroParticles');
  if (!container) return;

  // inject keyframes once
  if (!document.getElementById('particleKF')) {
    const s = document.createElement('style');
    s.id = 'particleKF';
    s.textContent = `
      @keyframes particleRise {
        0%   { transform:translateY(0)      scale(1);   opacity:0   }
        10%  { opacity:1 }
        90%  { opacity:.35 }
        100% { transform:translateY(-100vh) scale(.4);  opacity:0   }
      }`;
    document.head.appendChild(s);
  }

  for (let i = 0; i < 26; i++) {
    const p    = document.createElement('div');
    const size = Math.random() * 3.5 + 1;
    const dur  = Math.random() * 14 + 10;
    const del  = Math.random() * 8;
    p.style.cssText = [
      'position:absolute',
      `width:${size}px`,
      `height:${size}px`,
      'border-radius:50%',
      `background:rgba(255,122,0,${(Math.random() * .3 + .07).toFixed(2)})`,
      `left:${(Math.random() * 100).toFixed(1)}%`,
      'bottom:-10px',
      `animation:particleRise ${dur.toFixed(1)}s linear ${del.toFixed(1)}s infinite`,
      'pointer-events:none'
    ].join(';');
    container.appendChild(p);
  }
}

/* ── COUNTERS ── */
function initCounters() {
  const animate = (els) => {
    els.forEach(el => {
      const target = parseInt(el.dataset.target, 10);
      if (isNaN(target)) return;
      const dur    = 2000;
      const start  = performance.now();
      const tick   = now => {
        const p    = Math.min((now - start) / dur, 1);
        const ease = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.floor(ease * target);
        if (p < 1) requestAnimationFrame(tick);
        else       el.textContent = target;
      };
      requestAnimationFrame(tick);
    });
  };

  // Hero stats — start after loader
  const heroCounters = document.querySelectorAll('.hero-stats .count[data-target]');
  if (heroCounters.length) setTimeout(() => animate(heroCounters), 2200);
}

/* ── HERO IMAGE SLIDER ── */
function initHeroSlider() {
  const track = document.querySelector('.hero-slider-track');
  if (!track) return;
  const slides = track.querySelectorAll('.hero-slide');
  if (slides.length < 2) return;

  let index = 0;
  let interval;

  function slideTo(i) {
    index = i;
    const target = index * slides[0].offsetWidth;
    const start = track.scrollLeft;
    const diff = target - start;
    const dur = 200;
    const t0 = performance.now();

    function tick(now) {
      const p = Math.min((now - t0) / dur, 1);
      const ease = 1 - Math.pow(1 - p, 3);
      track.scrollLeft = start + diff * ease;
      if (p < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }

  function advance() {
    slideTo((index + 1) % slides.length);
  }

  function startAuto() {
    stopAuto();
    interval = setInterval(advance, 4000);
  }

  function stopAuto() {
    clearInterval(interval);
  }

  track.addEventListener('scroll', () => {
    const w = slides[0].offsetWidth;
    index = Math.round(track.scrollLeft / w);
  }, { passive: true });

  const card = track.closest('.hero-main-card');
  if (card) {
    card.addEventListener('mouseenter', stopAuto);
    card.addEventListener('mouseleave', startAuto);
  }

  startAuto();
}

/* ── OFFER SLIDER NAVIGATION ── */
function initFeaturedSlider() {
  const wrap = document.querySelector('.featured-slider-wrap');
  const track = document.querySelector('.featured-track');
  const dots = document.querySelectorAll('.featured-dots .dot');
  if (!wrap || !track || !dots.length) return;

  const cards = track.querySelectorAll('.featured-card');

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      if (cards[i]) {
        cards[i].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
      }
    });
  });

  let ticking = false;
  wrap.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        const wrapRect = wrap.getBoundingClientRect();
        let activeIdx = 0;
        const centerX = wrapRect.left + wrapRect.width / 2;
        cards.forEach((card, i) => {
          const cardRect = card.getBoundingClientRect();
          if (cardRect.left <= centerX && cardRect.right >= centerX) {
            activeIdx = i;
          }
        });
        dots.forEach((d, i) => d.classList.toggle('active', i === activeIdx));
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
}

/* ── GALLERY FILTER ── */
function initGalleryFilter() {
  const btns  = document.querySelectorAll('.filter-btn');
  const items = document.querySelectorAll('.gallery-item');
  if (!btns.length) return;

  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      btns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const filter = btn.dataset.filter;
      items.forEach(item => {
        const show = filter === 'all' || item.dataset.category === filter;
        item.style.display = show ? '' : 'none';
        if (show) {
          item.classList.remove('revealed');
          requestAnimationFrame(() =>
            setTimeout(() => item.classList.add('revealed'), 60)
          );
        }
      });
    });
  });
}

/* ── LIGHTBOX ── */
function initLightbox() {
  const box      = document.getElementById('lightbox');
  const inner    = document.getElementById('lightboxInner');
  const closeBtn = document.getElementById('lightboxClose');
  if (!box || !inner || !closeBtn) return;

  const open  = (src, alt) => {
    inner.innerHTML = `<img src="${src}" alt="${alt || ''}" style="width:100%;border-radius:16px;"/>`;
    box.classList.add('open');
    document.body.style.overflow = 'hidden';
  };
  const close = () => {
    box.classList.remove('open');
    document.body.style.overflow = '';
    setTimeout(() => { inner.innerHTML = ''; }, 400);
  };

  document.querySelectorAll('.gallery-item').forEach(item => {
    item.addEventListener('click', () => {
      const img = item.querySelector('img');
      if (img) open(img.src, img.alt);
    });
  });

  closeBtn.addEventListener('click', close);
  box.addEventListener('click', e => { if (e.target === box) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && box.classList.contains('open')) close(); });
}

/* ── CONTACT FORM ── */
function initContactForm() {
  const form    = document.getElementById('inquiryForm');
  const success = document.getElementById('formSuccess');
  if (!form) return;

  const $ = id => document.getElementById(id);

  const setErr = (fieldId, errId, msg) => {
    const f = $(fieldId); const e = $(errId);
    if (f) f.style.borderColor = 'rgba(255,100,100,.6)';
    if (e) e.textContent = msg;
  };
  const clearErr = (fieldId, errId) => {
    const f = $(fieldId); const e = $(errId);
    if (f) f.style.borderColor = '';
    if (e) e.textContent = '';
  };

  // live clear on input
  [['fname','fNameErr'],['fphone','fPhoneErr'],['fservice','fServiceErr']].forEach(([f, e]) => {
    const el = $(f);
    if (el) el.addEventListener('input', () => clearErr(f, e));
    if (el) el.addEventListener('change', () => clearErr(f, e));
  });

  form.addEventListener('submit', e => {
    e.preventDefault();
    let valid = true;

    const name    = $('fname')?.value.trim()    || '';
    const phone   = $('fphone')?.value.trim()   || '';
    const service = $('fservice')?.value        || '';
    const email   = $('femail')?.value.trim()   || 'Not provided';
    const message = $('fmessage')?.value.trim() || 'No additional message';

    if (name.length < 2)                       { setErr('fname',    'fNameErr',    'Please enter your full name.');    valid = false; } else clearErr('fname','fNameErr');
    if (!/^[+\d\s\-(.)]{7,15}$/.test(phone))  { setErr('fphone',   'fPhoneErr',   'Enter a valid phone number.');     valid = false; } else clearErr('fphone','fPhoneErr');
    if (!service)                              { setErr('fservice', 'fServiceErr', 'Please select a service.');        valid = false; } else clearErr('fservice','fServiceErr');

    if (!valid) return;

    const submitBtn = form.querySelector('.btn-submit');
    if (!submitBtn) return;
    submitBtn.disabled  = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending…';

    setTimeout(() => {
      const waMsg = encodeURIComponent(
        `Hello Drishyaam Signage! 🙏\n\n` +
        `*Name:* ${name}\n*Phone:* ${phone}\n*Email:* ${email}\n` +
        `*Service:* ${service}\n*Message:* ${message}\n\n` +
        `Kindly share a quote. Thank you!`
      );
      const waURL = `https://wa.me/917972231388?text=${waMsg}`;

      form.reset();
      submitBtn.disabled  = false;
      submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Inquiry';

      if (success) {
        success.classList.add('show');
        setTimeout(() => success.classList.remove('show'), 7000);
      }

      // Redirect to WhatsApp
      setTimeout(() => window.open(waURL, '_blank'), 900);
    }, 1400);
  });
}

/* ── ACTIVE NAV LINK ── */
function initActiveNavLink() {
  const sections = document.querySelectorAll('section[id]');
  const links    = document.querySelectorAll('.nav-link');
  if (!sections.length || !links.length) return;

  const io = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        links.forEach(l =>
          l.classList.toggle('active', l.getAttribute('href') === `#${entry.target.id}`)
        );
      }
    });
  }, { threshold: 0.3, rootMargin: '-70px 0px 0px 0px' });

  sections.forEach(s => io.observe(s));
}

/* ═══════════════ ENQUIRY MODAL (popup on current page) ═══════════════ */
function initEnquiryModal() {
  var modal = document.getElementById('enquiryModal');
  var form  = document.getElementById('enqFormModal');
  if (!modal || !form) return;

  /* ── helpers ── */
  function $(id) { return document.getElementById(id); }

  function showErr(fieldId, errId, msg) {
    var f = $(fieldId), e = $(errId);
    var parent = f ? f.closest('.enq-field') : null;
    if (parent) parent.classList.add('input-error');
    if (e) { e.textContent = msg; e.classList.add('show'); }
  }

  function clearErr(fieldId, errId) {
    var f = $(fieldId), e = $(errId);
    var parent = f ? f.closest('.enq-field') : null;
    if (parent) parent.classList.remove('input-error');
    if (e) { e.textContent = ''; e.classList.remove('show'); }
  }

  function clearAllErr() {
    ['enqName','enqPhone','enqService'].forEach(function(id) {
      clearErr(id, id + 'Err');
    });
  }

  function closeEnquiry() {
    modal.classList.remove('active');
    document.body.style.overflow = '';
    clearAllErr();
  }

  /* ── close triggers ── */
  modal.addEventListener('click', function(e) {
    if (e.target === modal) closeEnquiry();
  });
  var cb = modal.querySelector('.enq-close-btn');
  if (cb) cb.addEventListener('click', closeEnquiry);
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.classList.contains('active')) closeEnquiry();
  });

  /* ── live clear on input ── */
  function addLiveClear(fieldId, errId) {
    var el = $(fieldId);
    if (el) el.addEventListener('input', function() { clearErr(fieldId, errId); });
  }
  addLiveClear('enqName','enqNameErr');
  addLiveClear('enqPhone','enqPhoneErr');

  /* ── form submit ── */
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    clearAllErr();

    var name    = ($('enqName')    || {}).value || '';
    var phone   = ($('enqPhone')   || {}).value || '';
    var email   = ($('enqEmail')   || {}).value || '';
    var service = ($('enqService') || {}).value || '';
    var msg     = ($('enqMsg')     || {}).value || '';

    var valid = true;
    if (name.length < 2)                { showErr('enqName','enqNameErr','Please enter your full name.'); valid = false; }
    if (!/^\d{10}$/.test(phone))        { showErr('enqPhone','enqPhoneErr','Enter a valid 10-digit mobile number.'); valid = false; }
    if (!service)                       { showErr('enqService','',''); valid = false; }
    if (!valid) return;

    var waMsg =
      'Hello, I am interested in your service.\n\n' +
      'Name: ' + name + '\n' +
      'Phone Number: ' + phone + '\n' +
      'Email: ' + (email || 'Not provided') + '\n\n' +
      'Selected Service: ' + service + '\n\n' +
      'Message:\n' + (msg || 'Not provided') + '\n\n' +
      'Please contact me with more details.';

    var btn = form.querySelector('.btn-submit');
    if (!btn) return;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Opening WhatsApp...';

    setTimeout(function() {
      window.open('https://wa.me/917972231388?text=' + encodeURIComponent(waMsg), '_blank');
      closeEnquiry();
      btn.disabled = false;
      btn.innerHTML = '<i class="fab fa-whatsapp"></i> Send Enquiry';
    }, 600);
  });

  /* ── inquire button clicks (delegated, once) ── */
  if (initEnquiryModal._delegated) return;
  initEnquiryModal._delegated = true;
  document.addEventListener('click', function inquireDelegate(e) {
    var btn = e.target.closest('.inquire-btn');
    if (!btn) return;
    e.preventDefault();
    var product = 'this product';
    var card = btn.closest('.product-card');
    if (card) {
      var h3 = card.querySelector('h3');
      if (h3) product = h3.textContent;
    }
    $('enqName').value = '';
    $('enqPhone').value = '';
    $('enqEmail').value = '';
    $('enqService').value = product;
    $('enqMsg').value = '';
    clearAllErr();
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  });
}

/* ═══════════════ TOUCH HOVER — mobile hover simulation ═══════════════ */
function initTouchHover() {
  if (initTouchHover._done) return;
  if (!('ontouchstart' in window) && !navigator.maxTouchPoints) return;
  initTouchHover._done = true;

  var hovered = null;
  var cls = 'touch-hover';

  function injectTouchStyles() {
    if (document.getElementById('touch-hover-style')) return;
    var style = document.createElement('style');
    style.id = 'touch-hover-style';
    var css = '';

    try {
      for (var s = 0; s < document.styleSheets.length; s++) {
        var sheet = document.styleSheets[s];
        try {
          var rules = sheet.cssRules || sheet.rules;
          if (!rules) continue;
          for (var r = 0; r < rules.length; r++) {
            var rule = rules[r];
            if (rule.selectorText && rule.selectorText.indexOf(':hover') !== -1) {
              var newSel = rule.selectorText.split(',').map(function(part) {
                return part.trim().replace(/:hover/g, '.' + cls);
              }).join(',');
              if (rule.style && rule.style.cssText) {
                css += newSel + '{' + rule.style.cssText + '}';
              }
            }
          }
        } catch(e) {}
      }
    } catch(e) {}

    style.textContent = css;
    if (css) document.head.appendChild(style);
  }

  function setHover(el) {
    if (el === hovered) return;
    clearHover();
    if (el && el.classList) {
      el.classList.add(cls);
      hovered = el;
    }
  }

  function clearHover() {
    if (hovered) { hovered.classList.remove(cls); hovered = null; }
    document.querySelectorAll('.' + cls).forEach(function(el) { el.classList.remove(cls); });
  }

  function clearStickyHover() {
    clearHover();
    document.body.style.transform = 'translateZ(0)';
    void document.body.offsetHeight;
    document.body.style.transform = '';
  }

  var hoverTimer = null;

  document.addEventListener('touchstart', function(e) {
    if (hoverTimer) { clearTimeout(hoverTimer); hoverTimer = null; }
    var t = e.touches[0];
    var el = document.elementFromPoint(t.clientX, t.clientY);
    setHover(el);
  }, { passive: true });

  document.addEventListener('touchmove', function(e) {
    var t = e.touches[0];
    var el = document.elementFromPoint(t.clientX, t.clientY);
    setHover(el);
  }, { passive: true });

  document.addEventListener('touchend', function() {
    if (hoverTimer) clearTimeout(hoverTimer);
    hoverTimer = setTimeout(function() {
      clearStickyHover();
      hoverTimer = null;
    }, 80);
  }, { passive: true });

  document.addEventListener('touchcancel', clearStickyHover, { passive: true });
  window.addEventListener('scroll', clearStickyHover, { passive: true });
  window.addEventListener('resize', clearHover, { passive: true });

  if (document.readyState === 'complete') injectTouchStyles();
  else window.addEventListener('load', injectTouchStyles);
}

/* ═══════════════ PAGE TRANSITION (products → home, no refresh) ═══════════════ */
function initPageTransition() {
  if (!document.querySelector('.products-page-nav')) return;

  var links = document.querySelectorAll(
    '.back-home-link, .nav-logo[href="index.html"], .products-footer a[href="index.html"]'
  );

  links.forEach(function(link) {
    link.addEventListener('click', function(e) {
      var href = this.getAttribute('href');
      if (href === 'index.html') {
        e.preventDefault();
        transitionTo('index.html');
      }
    });
  });
}

function transitionTo(url) {
  document.body.style.transition = 'opacity .25s ease';
  document.body.style.opacity = '0';

  fetch(url)
    .then(function(r) { if (!r.ok) throw new Error(); return r.text(); })
    .then(function(html) {
      var parser = new DOMParser();
      var doc = parser.parseFromString(html, 'text/html');
      if (!doc.body) throw new Error();

      setTimeout(function() {
        try {
          document.title = doc.querySelector('title')?.textContent || '';
          document.body.innerHTML = doc.body.innerHTML;
          document.body.style.opacity = '1';
          reInit();
        } catch(e) {
          window.location.href = url;
        }
      }, 250);
    })
    .catch(function() {
      document.body.style.opacity = '1';
      window.location.href = url;
    });
}

function reInit() {
  [initCursorGlow, initNavbar, initMobileMenu, initSmoothScroll,
   initScrollReveal, initHeroReveal, initParticles, initCounters,
   initHeroSlider, initFeaturedSlider, initGalleryFilter, initLightbox,
   initContactForm, initActiveNavLink, initFloatingBtns, initBackToTop,
   initTouchHover, initEnquiryModal, initPageTransition
  ].forEach(function(fn) { if (typeof fn === 'function') fn(); });
  window.dispatchEvent(new Event('scroll'));
}

/* ═══════════════ DYNAMIC DATA LOADER ═══════════════ */
/* Loads saved JSON data from admin into the frontend   */
/* ===================================================== */

function getApiBase() {
  var path = window.location.pathname;
  var base = '';
  if (path.indexOf('/admin/') !== -1) {
    base = '../';
  }
  return base;
}

async function loadDynamicData() {
  var base = getApiBase();

  // Hero
  try {
    var r = await fetch(base + 'api/hero.php');
    var d = await r.json();
    if (d && d.badge) updateHeroSection(d);
  } catch(e) { /* ignore */ }

  // Products
  try {
    var r = await fetch(base + 'api/products.php');
    var d = await r.json();
    if (d && d.length) updateProductsGrid(d);
  } catch(e) { /* ignore */ }

  // Feed
  try {
    var r = await fetch(base + 'api/feed.php');
    var d = await r.json();
    if (d && d.length) updateFeedGrid(d);
  } catch(e) { /* ignore */ }

  // Contact
  try {
    var r = await fetch(base + 'api/contact.php');
    var d = await r.json();
    if (d && d.phone_primary) updateContactSection(d);
  } catch(e) { /* ignore */ }
}

function updateHeroSection(data) {
  var badge = document.querySelector('.hero-badge span');
  if (badge && data.badge) badge.textContent = data.badge;

  var titleLine1 = document.querySelector('.title-line');
  var titleHighlight = document.querySelector('.title-highlight');
  if (titleLine1 && data.title_line1) titleLine1.textContent = data.title_line1;
  if (titleHighlight && data.title_line2) titleHighlight.textContent = data.title_line2;

  var subtitle = document.querySelector('.hero-subtitle');
  if (subtitle && data.subtitle) subtitle.textContent = data.subtitle;

  var btns = document.querySelectorAll('.hero-actions .btn');
  if (btns.length >= 2) {
    if (data.btn1_text) btns[0].innerHTML = '<i class="fas fa-paper-plane"></i> ' + data.btn1_text;
    if (data.btn1_link) btns[0].href = data.btn1_link;
    if (data.btn2_text) btns[1].innerHTML = '<i class="fab fa-whatsapp"></i> ' + data.btn2_text;
    if (data.btn2_link) btns[1].href = data.btn2_link;
  }

  var chipsContainer = document.querySelector('.hero-chips');
  if (chipsContainer && data.chips && data.chips.length) {
    chipsContainer.innerHTML = data.chips.map(function(c) {
      return '<span class="chip"><i class="fas fa-check-circle"></i> ' + c + '</span>';
    }).join('');
  }

  var bgImage = document.querySelector('.hero-bg-image');
  if (bgImage && data.bg_image) {
    bgImage.style.backgroundImage = "url('" + data.bg_image + "')";
  }

  // Slider images
  var sliderTrack = document.querySelector('.hero-slider-track');
  if (sliderTrack && data.slider_images && data.slider_images.length) {
    sliderTrack.innerHTML = data.slider_images.map(function(img, i) {
      var loading = i === 0 ? 'eager' : 'lazy';
      return '<div class="hero-slide"><img src="' + img + '" alt="Slide ' + (i+1) + '" loading="' + loading + '"/></div>';
    }).join('');
  }

  // Stats - update counter targets
  var statItems = document.querySelectorAll('.stat-item');
  if (data.stats && data.stats.length) {
    statItems.forEach(function(item, i) {
      if (data.stats[i]) {
        var countEl = item.querySelector('.count');
        var labelEl = item.querySelector('.stat-lbl');
        var plusEl = item.querySelector('.stat-plus');
        if (countEl) {
          countEl.dataset.target = data.stats[i].value;
          countEl.textContent = '0';
        }
        if (labelEl) labelEl.textContent = data.stats[i].label;
        if (plusEl) plusEl.textContent = data.stats[i].suffix || '+';
      }
    });
  }
}

function updateProductsGrid(products) {
  var grid = document.querySelector('.products-grid');
  if (!grid) return;

  // Check if on products.html (has products-page-grid class parent)
  var isProductsPage = document.querySelector('.products-page-grid') !== null;

  // Only update if there are saved products
  if (!products || !products.length) return;

  var badgeMap = { 'best seller': 'Best Seller', 'popular': 'popular', 'eco': 'new-badge', 'new': 'new-badge', 'premium': 'premium', 'trending': 'new-badge' };

  function badgeClass(b) {
    if (!b) return '';
    var key = b.toLowerCase();
    for (var k in badgeMap) {
      if (key.indexOf(k) !== -1) return badgeMap[k];
    }
    return '';
  }

  grid.innerHTML = products.map(function(p) {
    var badgeHtml = p.badge ? '<span class="product-badge ' + badgeClass(p.badge) + '">' + p.badge + '</span>' : '';
    var tagsHtml = (p.tags && p.tags.length) ? p.tags.map(function(t) { return '<span>' + t + '</span>'; }).join('') : '';
    return '<div class="product-card revealed">' +
      '<div class="product-img-wrap">' +
        '<img src="' + (p.image || '') + '" alt="' + (p.title || '') + '" loading="lazy"/>' +
        '<div class="product-img-overlay"><a class="btn btn-sm-white inquire-btn">Inquire Now <i class="fas fa-arrow-right"></i></a></div>' +
        badgeHtml +
        '<div class="product-shine"></div>' +
      '</div>' +
      '<div class="product-info">' +
        '<span class="product-cat"><i class="fas fa-circle-dot"></i> ' + (p.category || '') + '</span>' +
        '<h3>' + (p.title || '') + '</h3>' +
        '<p>' + (p.description || '') + '</p>' +
        '<div class="product-tags">' + tagsHtml + '</div>' +
      '</div>' +
    '</div>';
  }).join('');
}

function updateFeedGrid(feed) {
  var grid = document.querySelector('.sfp-grid');
  if (!grid || !feed || !feed.length) return;

  grid.innerHTML = feed.map(function(f) {
    var label = f.title || 'Post';
    return '<div class="sfp-item">' +
      '<img src="' + (f.image || '') + '" alt="' + label + '" loading="lazy"/>' +
      '<div class="sfp-overlay"><i class="fab fa-instagram"></i><span>' + label + '</span></div>' +
    '</div>';
  }).join('');
}

function updateContactSection(data) {
  // Update phone numbers
  var phoneLinks = document.querySelectorAll('.cip-item a[href^="tel:"]');
  phoneLinks.forEach(function(a) {
    var strong = a.querySelector('strong');
    if (!strong) return;
    if (a.href.indexOf('7972231388') !== -1 && data.phone_primary) {
      a.href = 'tel:' + data.phone_primary.replace(/[\s\-]/g, '');
      strong.textContent = data.phone_primary;
    } else if (a.href.indexOf('9175580099') !== -1 && data.phone_secondary) {
      a.href = 'tel:' + data.phone_secondary.replace(/[\s\-]/g, '');
      strong.textContent = data.phone_secondary;
    }
  });

  // WhatsApp
  var waItems = document.querySelectorAll('.cip-item a[href*="wa.me"]');
  waItems.forEach(function(a) {
    var strong = a.querySelector('strong');
    if (strong && data.whatsapp) {
      var num = data.whatsapp.replace(/[\s\-\+]/g, '');
      if (num.indexOf('+') !== 0) num = '+' + num;
      a.href = 'https://wa.me/' + num.replace('+', '');
      strong.textContent = data.whatsapp;
    }
  });

  // Email
  var mailItems = document.querySelectorAll('.cip-item a[href^="mailto:"]');
  mailItems.forEach(function(a) {
    var strong = a.querySelector('strong');
    if (strong && data.email) {
      a.href = 'mailto:' + data.email;
      strong.textContent = data.email;
    }
  });

  // Address
  var addrItems = document.querySelectorAll('.cip-item .cip-text strong');
  addrItems.forEach(function(s) {
    var parent = s.closest('.cip-item');
    if (parent && parent.querySelector('.loc-icon') && data.address) {
      s.textContent = data.address;
    }
  });

  // Map embed
  var mapIframe = document.querySelector('.cip-map iframe');
  if (mapIframe && data.map_embed) {
    mapIframe.src = data.map_embed;
  }

  // Social links
  if (data.social) {
    var socLinks = document.querySelectorAll('.cip-soc-icons a');
    socLinks.forEach(function(a) {
      var icon = a.querySelector('i');
      if (!icon) return;
      var cls = icon.className;
      if (cls.indexOf('instagram') !== -1 && data.social.instagram) a.href = data.social.instagram;
      else if (cls.indexOf('facebook') !== -1 && data.social.facebook) a.href = data.social.facebook;
      else if (cls.indexOf('linkedin') !== -1 && data.social.linkedin) a.href = data.social.linkedin;
      else if (cls.indexOf('whatsapp') !== -1 && data.social.whatsapp) a.href = data.social.whatsapp;
    });

    // Footer social links
    var footerSocLinks = document.querySelectorAll('.footer-social a');
    footerSocLinks.forEach(function(a) {
      var icon = a.querySelector('i');
      if (!icon) return;
      var cls = icon.className;
      if (cls.indexOf('instagram') !== -1 && data.social.instagram) a.href = data.social.instagram;
      else if (cls.indexOf('facebook') !== -1 && data.social.facebook) a.href = data.social.facebook;
      else if (cls.indexOf('linkedin') !== -1 && data.social.linkedin) a.href = data.social.linkedin;
      else if (cls.indexOf('whatsapp') !== -1 && data.social.whatsapp) a.href = data.social.whatsapp;
    });
  }
}





