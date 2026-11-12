/**
 * main.js — socle du site DST.
 * Lenis + GSAP, header condensé, curseur, surlignages, rideaux de section,
 * panneaux latéraux, menu mobile, barre mobile, cookies, navigation active.
 *
 * Tous les modules partagent un petit contexte exposé sur window.DST.
 */
import initHero from './hero.js';
import initCircuit from './circuit.js';
import initLabels from './labels.js';
import initSectors from './sectors.js';
import initFleet from './fleet.js';
import initMap from './map.js';
import initStats from './stats.js';
import initChecklist from './checklist.js';
import initGallery from './gallery.js';
import initChatForm from './chat-form.js';

const DST = (window.DST = window.DST || {});

const dataEl = document.getElementById('dst-data');
DST.data = dataEl ? JSON.parse(dataEl.textContent) : {};
DST.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
DST.touch = window.matchMedia('(hover: none), (pointer: coarse)').matches;
DST.$ = (sel, ctx = document) => ctx.querySelector(sel);
DST.$$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

if (window.gsap) {
  gsap.registerPlugin(ScrollTrigger, SplitText, Flip, Draggable, InertiaPlugin, MotionPathPlugin, DrawSVGPlugin, CustomEase);
  gsap.defaults({ ease: 'power3.out', duration: 0.8 });
  // « pose » : léger dépassement de 2 % puis stabilisation
  CustomEase.create('pose', 'M0,0 C0.18,0.9 0.28,1.02 0.42,1.02 0.58,1.02 0.72,1 1,1');
  DST.easePose = 'pose';
} else {
  DST.easePose = 'cubic-bezier(.2,1.03,.28,1)';
}

/* ---------------------------------------------------------------- */
/* Smooth scroll                                                     */
/* ---------------------------------------------------------------- */
DST.lenis = null;
function initLenis() {
  if (DST.reduced || !window.Lenis) return;
  DST.lenis = new Lenis({
    lerp: 0.09,
    wheelMultiplier: 1,
    smoothWheel: true,
    syncTouch: false,
  });
  DST.lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((t) => DST.lenis.raf(t * 1000));
  gsap.ticker.lagSmoothing(0);
  DST.scrollTo = (target) => {
    const el = typeof target === 'string' ? document.querySelector(target) : target;
    if (!el) return;
    DST.lenis.scrollTo(el, { offset: -80, duration: 1.35 });
  };
}
function fallbackScrollTo(target) {
  const el = typeof target === 'string' ? document.querySelector(target) : target;
  if (!el) return;
  const top = el.getBoundingClientRect().top + window.scrollY - 76;
  window.scrollTo({ top, behavior: DST.reduced ? 'auto' : 'smooth' });
}

/* ---------------------------------------------------------------- */
/* Header : topbar repliée, logotype condensé                        */
/* ---------------------------------------------------------------- */
function initHeader() {
  const header = document.querySelector('[data-header]');
  const topbar = document.getElementById('topbar');
  if (!header) return;
  let last = -1;
  const onScroll = () => {
    const y = window.scrollY;
    if (y === last) return;
    last = y;
    header.classList.toggle('is-stuck', y > 8);
    header.classList.toggle('is-condensed', y > 120);
    if (topbar) topbar.classList.toggle('is-hidden', y > 60);
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  // lien actif
  const links = DST.$$('[data-nav]');
  const map = new Map();
  links.forEach((a) => {
    const id = a.getAttribute('href');
    const sec = document.querySelector(id);
    if (sec) map.set(sec, a);
  });
  if (!('IntersectionObserver' in window)) return;
  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        const a = map.get(entry.target);
        if (!a) return;
        if (entry.isIntersecting) {
          links.forEach((l) => l.classList.remove('is-active'));
          a.classList.add('is-active');
        }
      });
    },
    { rootMargin: '-45% 0px -50% 0px' }
  );
  map.forEach((_, sec) => io.observe(sec));
}

/* ---------------------------------------------------------------- */
/* Menu mobile                                                      */
/* ---------------------------------------------------------------- */
function initMenu() {
  const panel = document.getElementById('mobile-panel');
  const openBtn = document.querySelector('[data-menu-open]');
  if (!panel || !openBtn) return;
  let lastFocus = null;

  const open = () => {
    lastFocus = document.activeElement;
    panel.hidden = false;
    requestAnimationFrame(() => panel.classList.add('is-open'));
    openBtn.setAttribute('aria-expanded', 'true');
    document.documentElement.style.overflow = 'hidden';
    DST.lenis?.stop();
    panel.querySelector('a, button')?.focus();
    if (window.gsap && !DST.reduced) {
      gsap.from(panel.querySelectorAll('.mobile-panel__nav li'), {
        y: 28,
        opacity: 0,
        duration: 0.6,
        stagger: 0.05,
        ease: 'power3.out',
        delay: 0.05,
        clearProps: 'all',
      });
    }
  };
  const close = () => {
    panel.classList.remove('is-open');
    openBtn.setAttribute('aria-expanded', 'false');
    document.documentElement.style.overflow = '';
    DST.lenis?.start();
    setTimeout(() => {
      panel.hidden = true;
      lastFocus?.focus();
    }, DST.reduced ? 0 : 320);
  };

  openBtn.addEventListener('click', open);
  panel.querySelectorAll('[data-menu-close]').forEach((b) => b.addEventListener('click', close));
  panel.querySelectorAll('[data-menu-link]').forEach((a) =>
    a.addEventListener('click', () => {
      close();
    })
  );
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !panel.hidden) close();
  });
}

/* ---------------------------------------------------------------- */
/* Liens internes → smooth scroll                                    */
/* ---------------------------------------------------------------- */
function initAnchors() {
  document.addEventListener('click', (e) => {
    const a = e.target.closest('a[href^="#"]');
    if (!a) return;
    const id = a.getAttribute('href');
    if (!id || id === '#') return;
    const el = document.querySelector(id);
    if (!el) return;
    e.preventDefault();
    if (DST.lenis) {
      DST.scrollTo(el);
    } else {
      fallbackScrollTo(el);
    }
    history.replaceState(null, '', id);
  });
}

/* ---------------------------------------------------------------- */
/* Surlignage au feutre                                              */
/* ---------------------------------------------------------------- */
function initHighlights() {
  const marks = DST.$$('.hl__mark');
  if (!marks.length) return;
  if (DST.reduced) {
    marks.forEach((m) => (m.style.transform = 'skewX(-1.2deg) rotate(-.5deg)'));
    return;
  }
  if (!window.gsap) return;
  marks.forEach((m) => {
    gsap.to(m, {
      scaleX: 1,
      duration: 0.6,
      ease: 'power2.out',
      scrollTrigger: { trigger: m, start: 'top 88%', once: true },
      onStart() {
        gsap.set(m, { transformOrigin: 'left center' });
      },
    });
  });
}

/* ---------------------------------------------------------------- */
/* Grille suisse qui se dessine                                      */
/* ---------------------------------------------------------------- */
function initGrids() {
  DST.$$('[data-grid-lines]').forEach((grid) => {
    if (grid.children.length) return;
    for (let i = 0; i < 12; i++) grid.appendChild(document.createElement('i'));
    if (DST.reduced || !window.gsap) return;
    gsap.from(grid.children, {
      scaleY: 0,
      transformOrigin: 'top center',
      duration: 1.1,
      stagger: 0.04,
      ease: 'power2.out',
      scrollTrigger: { trigger: grid, start: 'top 92%', once: true },
    });
  });
}

/* ---------------------------------------------------------------- */
/* Rideaux de section (esprit marché)                                */
/* ---------------------------------------------------------------- */
function initCurtains() {
  if (DST.reduced || !window.gsap) return;
  const spec = [
    ['#circuit', '#E5334B'],
    ['#services', '#1F7A4C'],
    ['#secteurs', '#F39A55'],
    ['#chiffres', '#F5DC4A'],
    ['#galerie', '#1F7A4C'],
    ['#devis', '#E5334B'],
  ];
  spec.forEach(([sel, color]) => {
    const sec = document.querySelector(sel);
    if (!sec) return;
    const curtain = document.createElement('div');
    curtain.className = 'curtain';
    curtain.style.background = color;
    curtain.setAttribute('aria-hidden', 'true');
    sec.prepend(curtain);
    gsap.fromTo(
      curtain,
      { scaleY: 1, transformOrigin: 'bottom center' },
      {
        scaleY: 0,
        transformOrigin: 'top center',
        ease: 'none',
        scrollTrigger: { trigger: sec, start: 'top bottom', end: 'top 60%', scrub: 0.6 },
      }
    );
  });
}

/* ---------------------------------------------------------------- */
/* Curseur custom                                                    */
/* ---------------------------------------------------------------- */
function initCursor() {
  if (DST.touch || DST.reduced) return;
  const cursor = document.querySelector('[data-cursor]');
  if (!cursor) return;
  const label = cursor.querySelector('[data-cursor-label]');
  const dot = cursor.querySelector('.cursor__dot');
  const pill = cursor.querySelector('.cursor__pill');
  const pos = { x: window.innerWidth / 2, y: window.innerHeight / 2 };
  const target = { ...pos };
  let raf = 0;

  const loop = () => {
    pos.x += (target.x - pos.x) * 0.18;
    pos.y += (target.y - pos.y) * 0.18;
    dot.style.transform = `translate(${pos.x}px, ${pos.y}px) translate(-50%, -50%)`;
    pill.style.left = pos.x + 'px';
    pill.style.top = pos.y + 'px';
    raf = requestAnimationFrame(loop);
  };
  loop();

  window.addEventListener(
    'pointermove',
    (e) => {
      target.x = e.clientX;
      target.y = e.clientY;
      if (cursor.hidden) cursor.hidden = false;
    },
    { passive: true }
  );
  window.addEventListener('pointerdown', () => cursor.classList.add('is-down'));
  window.addEventListener('pointerup', () => cursor.classList.remove('is-down'));

  const setPill = (text) => {
    if (text) {
      label.textContent = text;
      cursor.classList.add('is-pill');
    } else {
      cursor.classList.remove('is-pill');
    }
  };
  document.querySelectorAll('[data-cursor]').forEach(() => {});
  document.addEventListener('pointerover', (e) => {
    const media = e.target.closest('.gallery__viewport');
    const van = e.target.closest('[data-vehicle-btn]');
    const img = e.target.closest('.scene__media, .stat--photo, .label__btn');
    if (media) setPill('Glisser');
    else if (van) setPill('Choisir');
    else if (img) setPill('Voir');
    else setPill('');
  });
  window.addEventListener('scroll', () => (cursor.hidden = window.scrollY < 10), { passive: true });
  window.addEventListener('resize', () => {
    cancelAnimationFrame(raf);
    loop();
  });
}

/* ---------------------------------------------------------------- */
/* Barre mobile + WhatsApp flottant                                  */
/* ---------------------------------------------------------------- */
function initMobileBar() {
  const bar = document.querySelector('[data-mobile-bar]');
  if (!bar) return;
  const hero = document.querySelector('[data-hero]');
  if (!hero) return;
  const io = new IntersectionObserver(
    ([entry]) => bar.classList.toggle('is-on', !entry.isIntersecting),
    { threshold: 0.05 }
  );
  io.observe(hero);
}

/* ---------------------------------------------------------------- */
/* Cookies                                                           */
/* ---------------------------------------------------------------- */
function initCookies() {
  const box = document.querySelector('[data-cookies]');
  if (!box) return;
  const KEY = 'dst_cookie_ok';
  const show = () => {
    if (localStorage.getItem(KEY)) return;
    box.hidden = false;
    if (window.gsap && !DST.reduced) {
      gsap.from(box, { y: 30, opacity: 0, duration: 0.7, delay: 1.4, ease: 'power3.out' });
    }
  };
  box.querySelector('[data-cookies-ok]')?.addEventListener('click', () => {
    localStorage.setItem(KEY, '1');
    box.hidden = true;
  });
  show();
}

/* ---------------------------------------------------------------- */
/* Panneaux latéraux (mentions, confidentialité, cookies)            */
/* ---------------------------------------------------------------- */
function initPanels() {
  const panels = DST.$$('.panel');
  if (!panels.length) return;
  let current = null;
  let lastFocus = null;

  const focusables = (root) =>
    Array.from(
      root.querySelectorAll(
        'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])'
      )
    ).filter((el) => el.offsetParent !== null || el === document.activeElement);

  const open = (id) => {
    const p = document.getElementById('panel-' + id);
    if (!p) return;
    lastFocus = document.activeElement;
    p.hidden = false;
    document.documentElement.style.overflow = 'hidden';
    DST.lenis?.stop();
    current = p;
    if (window.gsap && !DST.reduced) {
      gsap.fromTo(p.querySelector('.panel__sheet'), { xPercent: 100 }, { xPercent: 0, duration: 0.7, ease: 'power3.out' });
      gsap.fromTo(p.querySelector('.panel__backdrop'), { opacity: 0 }, { opacity: 1, duration: 0.4 });
    }
    requestAnimationFrame(() => p.querySelector('[data-panel-close]')?.focus());
  };
  const close = () => {
    if (!current) return;
    const p = current;
    const done = () => {
      p.hidden = true;
      document.documentElement.style.overflow = '';
      DST.lenis?.start();
      lastFocus?.focus();
    };
    if (window.gsap && !DST.reduced) {
      gsap.to(p.querySelector('.panel__sheet'), { xPercent: 100, duration: 0.5, ease: 'power3.in' });
      gsap.to(p.querySelector('.panel__backdrop'), { opacity: 0, duration: 0.4, onComplete: done });
    } else {
      done();
    }
    current = null;
  };

  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-open-panel]');
    if (trigger) {
      e.preventDefault();
      open(trigger.getAttribute('data-open-panel'));
      return;
    }
    if (e.target.closest('[data-panel-close]') && current) {
      e.preventDefault();
      close();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (!current) return;
    if (e.key === 'Escape') {
      close();
      return;
    }
    if (e.key === 'Tab') {
      const items = focusables(current);
      if (!items.length) return;
      const first = items[0];
      const last = items[items.length - 1];
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }
  });

  // ouverture directe via #mentions-legales / #confidentialite / #cookies
  const hash = { '#mentions-legales': 'legal', '#confidentialite': 'privacy', '#cookies': 'cookies' }[location.hash];
  if (hash) setTimeout(() => open(hash), 300);
}

/* ---------------------------------------------------------------- */
/* Footer : DST géant réactif                                        */
/* ---------------------------------------------------------------- */
function initFooterWord() {
  const word = document.querySelector('[data-footer-word]');
  if (!word || DST.touch) return;
  if (!word.dataset.split) {
    const text = word.textContent.trim();
    word.textContent = '';
    Array.from(text).forEach((ch) => {
      const span = document.createElement('span');
      span.textContent = ch;
      word.appendChild(span);
    });
    word.dataset.split = '1';
  }
  const letters = Array.from(word.children);
  let raf = 0;
  const onMove = (e) => {
    cancelAnimationFrame(raf);
    raf = requestAnimationFrame(() => {
      letters.forEach((l) => {
        const r = l.getBoundingClientRect();
        const d = Math.min(1, Math.abs(e.clientX - (r.left + r.width / 2)) / (window.innerWidth * 0.45));
        const width = 100 - d * 25;
        l.style.fontVariationSettings = `"wght" 800, "wdth" ${width.toFixed(1)}`;
        l.style.transform = `translateY(${((1 - d) * -6).toFixed(1)}px)`;
      });
    });
  };
  word.addEventListener('pointermove', onMove);
  word.addEventListener('pointerleave', () => {
    letters.forEach((l) => {
      l.style.fontVariationSettings = '';
      l.style.transform = '';
    });
  });
}

/* ---------------------------------------------------------------- */
/* Bascule aubergine de la section flotte                            */
/* ---------------------------------------------------------------- */
function initFleetReveal() {
  const section = document.getElementById('flotte');
  if (!section) return;
  const bg = section.querySelector('[data-fleet-bg]');
  if (!bg) return;
  const stickerEl = document.querySelector('.sticker--header, .sticker--hero');

  const origin = () => {
    const rect = section.getBoundingClientRect();
    let x = window.innerWidth / 2;
    let y = 0;
    if (stickerEl) {
      const r = stickerEl.getBoundingClientRect();
      x = r.left + r.width / 2;
      y = r.top + r.height / 2 - rect.top;
    }
    bg.style.setProperty('--ox', x + 'px');
    bg.style.setProperty('--oy', Math.max(0, y) + 'px');
    const dx = Math.max(x, window.innerWidth - x);
    const dy = Math.max(Math.abs(y), Math.abs(rect.height - y));
    return Math.ceil(Math.hypot(dx, dy) * 1.05);
  };

  if (DST.reduced || !window.gsap) {
    bg.style.clipPath = 'none';
    return;
  }

  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: section,
      start: 'top 70%',
      end: 'top 10%',
      scrub: 0.7,
    },
  });
  tl.fromTo(
    bg,
    { clipPath: () => `circle(0px at var(--ox) var(--oy))` },
    { clipPath: () => `circle(${origin()}px at var(--ox) var(--oy))`, ease: 'none' }
  );

  // sortie : le cercle se rétracte vers le bas
  gsap.fromTo(
    bg,
    { clipPath: () => `circle(${origin()}px at var(--ox) var(--oy))` },
    {
      clipPath: 'circle(0px at 50% 120%)',
      ease: 'none',
      immediateRender: false,
      scrollTrigger: { trigger: section, start: 'bottom 85%', end: 'bottom 20%', scrub: 0.7 },
    }
  );
  ScrollTrigger.addEventListener('refreshInit', () => {
    bg.style.setProperty('--ox', '50%');
    bg.style.setProperty('--oy', '0%');
  });

  // le texte passe en crème pendant la bascule
  gsap.to(section, {
    '--fleet-text': 1,
    scrollTrigger: { trigger: section, start: 'top 60%', end: 'top 20%', scrub: true },
  });
}

/* ---------------------------------------------------------------- */
/* Initialisation                                                    */
/* ---------------------------------------------------------------- */
function init() {
  initLenis();
  initHeader();
  initMenu();
  initAnchors();
  initHighlights();
  initGrids();
  initCurtains();
  initCursor();
  initMobileBar();
  initCookies();
  initPanels();
  initFooterWord();
  initFleetReveal();

  // modules de section
  const modules = [initHero, initCircuit, initLabels, initSectors, initFleet, initMap, initStats, initChecklist, initGallery, initChatForm];
  modules.forEach((fn) => {
    try {
      fn(DST);
    } catch (err) {
      console.warn('[DST] module en échec', err);
    }
  });

  if (window.gsap) {
    ScrollTrigger.refresh();
    setTimeout(() => ScrollTrigger.refresh(), 800);
  }
  window.addEventListener('load', () => window.gsap && ScrollTrigger.refresh());
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}

export { DST };
