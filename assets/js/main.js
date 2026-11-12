/* ==========================================================================
   DST — main.js
   Lenis, GSAP, header, curseur, révélations, transitions de fond, modales.
   ========================================================================== */

import { initHero, refreshHero } from './hero.js?v=1.1.4';
import { initColdChain, positionColdCurveVan } from './coldchain.js?v=1.1.4';
import { initFleet } from './fleet.js?v=1.1.4';
import { initMap } from './map.js';
import { initQuote } from './quote.js?v=1.1.4';
import { initCounters } from './counters.js';
import { initRoute } from './route.js';

const docEl = document.documentElement;
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
const reduceQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
let reduced = reduceQuery.matches;

/* --------------------------------------------------------------------------
   Utilitaires indépendants de GSAP
   -------------------------------------------------------------------------- */
const prefersFinePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

function clamp(v, min, max) {
    return Math.min(Math.max(v, min), max);
}

function lerp(a, b, t) {
    return a + (b - a) * t;
}

function hexToRgb(hex) {
    const v = hex.replace('#', '');
    return [
        parseInt(v.substring(0, 2), 16),
        parseInt(v.substring(2, 4), 16),
        parseInt(v.substring(4, 6), 16),
    ];
}

function mixColors(a, b, t) {
    const ca = hexToRgb(a);
    const cb = hexToRgb(b);
    const out = ca.map((c, i) => Math.round(lerp(c, cb[i], t)));
    return `rgb(${out[0]}, ${out[1]}, ${out[2]})`;
}

const THEMES = { dark: '#0E0D0B', light: '#F5F2E8' };

/* --------------------------------------------------------------------------
   Header, progression, nav active
   -------------------------------------------------------------------------- */
function initHeader() {
    const header = $('[data-header]');
    if (!header) return;
    const progress = $('[data-progress]');
    const nav = $('.nav');
    const underline = $('.nav__underline');
    let lastY = window.scrollY;
    let ticking = false;

    const update = () => {
        const y = window.scrollY;
        const max = document.documentElement.scrollHeight - window.innerHeight;
        if (progress) {
            progress.style.transform = `scaleX(${max > 0 ? clamp(y / max, 0, 1) : 0})`;
        }
        header.classList.toggle('is-scrolled', y > 60);

        if (y > lastY + 6 && y > 260) {
            header.classList.add('is-hidden');
        } else if (y < lastY - 6 || y < 160) {
            header.classList.remove('is-hidden');
        }
        lastY = y;

        /* Lien actif */
        if (nav && underline) {
            let found = null;
            $$('[data-nav]').forEach((link) => {
                const id = link.getAttribute('data-nav');
                const section = document.getElementById(id);
                if (!section) return;
                const rect = section.getBoundingClientRect();
                if (rect.top <= window.innerHeight * 0.45 && rect.bottom > window.innerHeight * 0.2) {
                    found = link;
                }
            });
            if (found) {
                underline.style.width = `${found.offsetWidth}px`;
                underline.style.transform = `translateX(${found.offsetLeft}px)`;
                underline.style.opacity = '1';
                $$('[data-nav]').forEach((l) => l.classList.toggle('is-active', l === found));
            } else {
                underline.style.opacity = '0';
                $$('[data-nav]').forEach((l) => l.classList.remove('is-active'));
            }
        }

        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) {
            ticking = true;
            window.requestAnimationFrame(update);
        }
    }, { passive: true });

    window.addEventListener('resize', update);
    update();

    /* Retour au calme quand le header est masqué au clavier */
    document.addEventListener('focusin', (e) => {
        if (e.target.closest('.site-header')) {
            header.classList.remove('is-hidden');
        }
    });
}

/* --------------------------------------------------------------------------
   Menu mobile
   -------------------------------------------------------------------------- */
function initMobileMenu() {
    const burger = $('[data-burger]');
    const menu = $('#mobile-menu');
    if (!burger || !menu) return;
    let isOpen = false;

    const setOpen = (open) => {
        isOpen = open;
        burger.setAttribute('aria-expanded', String(open));
        burger.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
        menu.classList.toggle('is-open', open);
        menu.setAttribute('aria-hidden', String(!open));
        document.body.classList.toggle('is-menu-open', open);
        if (open) {
            const first = menu.querySelector('a');
            if (first) setTimeout(() => first.focus(), 450);
        } else {
            burger.focus();
        }
    };

    burger.addEventListener('click', () => setOpen(!isOpen));
    $$('[data-menu-link]', menu).forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isOpen) setOpen(false);
    });
    window.addEventListener('resize', () => {
        if (window.innerWidth > 1080 && isOpen) setOpen(false);
    });
}

/* --------------------------------------------------------------------------
   Modales
   -------------------------------------------------------------------------- */
function initModals() {
    const modals = $$('.modal');
    if (!modals.length) return;
    let lastFocus = null;

    const focusables = (modal) => $$('a[href], button:not([disabled]), input:not([disabled]), select, textarea, [tabindex]:not([tabindex="-1"])', modal)
        .filter((el) => el.offsetParent !== null || el === document.activeElement);

    const close = (modal) => {
        if (!modal.classList.contains('is-open')) return;
        modal.classList.remove('is-open');
        document.body.classList.remove('is-modal-open');
        window.setTimeout(() => {
            modal.hidden = true;
        }, 380);
        if (lastFocus && typeof lastFocus.focus === 'function') {
            lastFocus.focus();
        }
    };

    const open = (modal) => {
        if (!modal) return;
        lastFocus = document.activeElement;
        modal.hidden = false;
        document.body.classList.add('is-modal-open');
        requestAnimationFrame(() => {
            modal.classList.add('is-open');
            const first = focusables(modal)[0];
            if (first) first.focus();
        });
    };

    $$('[data-open-modal]').forEach((trigger) => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = document.getElementById(trigger.getAttribute('data-open-modal'));
            open(modal);
        });
    });

    modals.forEach((modal) => {
        $$('[data-close-modal]', modal).forEach((el) => {
            el.addEventListener('click', () => close(modal));
        });
        modal.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                e.preventDefault();
                close(modal);
            }
            if (e.key === 'Tab') {
                const list = focusables(modal);
                if (!list.length) return;
                const first = list[0];
                const last = list[list.length - 1];
                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        });
    });
}

/* --------------------------------------------------------------------------
   Cookies
   -------------------------------------------------------------------------- */
function initCookies() {
    const banner = $('[data-cookie-banner]');
    if (!banner) return;
    const KEY = 'dst-cookie-choice';
    let choice = null;
    try {
        choice = localStorage.getItem(KEY);
    } catch (e) {
        choice = null;
    }

    const show = (delay = 0) => {
        window.setTimeout(() => {
            banner.hidden = false;
            requestAnimationFrame(() => banner.classList.add('is-visible'));
        }, delay);
    };

    const hide = () => {
        banner.classList.remove('is-visible');
        window.setTimeout(() => {
            banner.hidden = true;
        }, 350);
    };

    if (!choice) show(1400);

    $$('[data-cookie-accept]').forEach((btn) => {
        btn.addEventListener('click', () => {
            try {
                localStorage.setItem(KEY, 'technical');
            } catch (e) {}
            hide();
            const modal = document.getElementById('modal-cookies');
            if (modal && modal.classList.contains('is-open')) {
                modal.querySelector('[data-close-modal]').click();
            }
        });
    });

    $$('[data-cookie-reset]').forEach((btn) => {
        btn.addEventListener('click', () => {
            try {
                localStorage.removeItem(KEY);
            } catch (e) {}
            show(60);
        });
    });
}

/* --------------------------------------------------------------------------
   Accordéon FAQ
   -------------------------------------------------------------------------- */
function initAccordion() {
    const accordion = $('[data-accordion]');
    if (!accordion) return;
    const triggers = $$('[data-accordion-trigger]', accordion);

    triggers.forEach((trigger) => {
        const panel = document.getElementById(trigger.getAttribute('aria-controls'));
        if (!panel) return;
        if (trigger.getAttribute('aria-expanded') === 'true') {
            panel.classList.add('is-open');
        }
        trigger.addEventListener('click', () => {
            const isOpen = trigger.getAttribute('aria-expanded') === 'true';
            triggers.forEach((other) => {
                const otherPanel = document.getElementById(other.getAttribute('aria-controls'));
                other.setAttribute('aria-expanded', 'false');
                if (otherPanel) otherPanel.classList.remove('is-open');
            });
            if (!isOpen) {
                trigger.setAttribute('aria-expanded', 'true');
                panel.classList.add('is-open');
            }
        });
    });
}

/* --------------------------------------------------------------------------
   Services — accordéon mobile + aperçu qui suit le curseur
   -------------------------------------------------------------------------- */
function initServices() {
    const list = $('[data-services]');
    if (!list) return;
    const services = $$('[data-service]', list);
    const preview = $('[data-service-preview]');
    const previewImg = $('[data-service-preview-img]');
    const previewTag = $('[data-service-preview-tag]');
    const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    services.forEach((service, i) => {
        const toggle = $('[data-service-toggle]', service);
        const panel = $('[data-service-panel]', service);
        if (!toggle || !panel) return;

        toggle.addEventListener('click', () => {
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';
            if (window.innerWidth > 900 && canHover && !isOpen) {
                /* Sur desktop, le survol suffit : on ouvre tout de même au clic. */
            }
            services.forEach((other) => {
                const otherToggle = $('[data-service-toggle]', other);
                const otherPanel = $('[data-service-panel]', other);
                other.classList.remove('is-open', 'is-hover');
                if (otherToggle) otherToggle.setAttribute('aria-expanded', 'false');
                if (otherPanel) otherPanel.classList.remove('is-open');
            });
            if (!isOpen) {
                toggle.setAttribute('aria-expanded', 'true');
                panel.classList.add('is-open');
                service.classList.add('is-open');
            }
        });

        if (canHover) {
            service.addEventListener('mouseenter', () => {
                service.classList.add('is-hover');
                if (preview && previewImg) {
                    const src = service.getAttribute('data-image');
                    const alt = service.getAttribute('data-image-alt') || '';
                    if (src) previewImg.src = src;
                    previewImg.alt = alt;
                    if (previewTag) previewTag.textContent = String(i + 1).padStart(2, '0');
                }
                if (window.gsap && preview) {
                    gsap.to(preview, { opacity: 1, scale: 1, rotate: 0, duration: .35, ease: 'power3.out' });
                }
            });
            service.addEventListener('mouseleave', () => {
                service.classList.remove('is-hover');
                if (window.gsap && preview) {
                    gsap.to(preview, { opacity: 0, scale: .9, rotate: -4, duration: .3, ease: 'power2.out' });
                }
            });

            let targetX = 0;
            let targetY = 0;
            let currentX = 0;
            let currentY = 0;
            let currentRot = 0;
            let rafId = null;

            const loop = () => {
                if (!preview) return;
                const dx = targetX - currentX;
                currentX += dx * 0.16;
                currentY += (targetY - currentY) * 0.16;
                currentRot = clamp(currentRot + (dx * 0.02 - currentRot) * 0.1, -9, 9);
                preview.style.transform = `translate(${currentX}px, ${currentY}px) translate(-50%, -50%) rotate(${currentRot}deg)`;
                rafId = Math.abs(dx) > 0.1 ? requestAnimationFrame(loop) : null;
            };

            list.addEventListener('mousemove', (e) => {
                targetX = e.clientX;
                targetY = e.clientY;
                if (!rafId) rafId = requestAnimationFrame(loop);
            });
        }

        /* Accessibilité clavier */
        toggle.addEventListener('focus', () => {
            service.classList.add('is-hover');
        });
        toggle.addEventListener('blur', () => {
            service.classList.remove('is-hover');
        });
    });
}

/* --------------------------------------------------------------------------
   Témoignages
   -------------------------------------------------------------------------- */
function initTestimonials() {
    const root = $('[data-quotes]');
    if (!root) return;
    const quotes = $$('[data-quote]', root);
    const dots = $$('[data-quote-dot]');
    if (quotes.length < 2) return;
    let index = 0;
    let timer = null;

    const show = (i) => {
        index = (i + quotes.length) % quotes.length;
        quotes.forEach((q, k) => {
            q.hidden = k !== index;
            q.classList.toggle('is-active', k === index);
        });
        dots.forEach((d, k) => d.classList.toggle('is-active', k === index));
    };

    const start = () => {
        if (reduced) return;
        stop();
        timer = window.setInterval(() => show(index + 1), 6000);
    };

    const stop = () => {
        if (timer) window.clearInterval(timer);
        timer = null;
    };

    dots.forEach((dot) => {
        dot.addEventListener('click', () => {
            show(parseInt(dot.getAttribute('data-quote-dot'), 10) || 0);
            start();
        });
    });

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    start();
}

/* --------------------------------------------------------------------------
   Ancres & scroll fluide
   -------------------------------------------------------------------------- */
let lenis = null;

function initAnchors() {
    const header = $('[data-header]');
    const offset = () => (header ? header.offsetHeight : 80) + 8;

    $$('a[href^="#"]').forEach((link) => {
        const href = link.getAttribute('href');
        if (!href || href === '#') return;
        link.addEventListener('click', (e) => {
            const target = document.querySelector(href);
            if (!target) return;
            e.preventDefault();
            if (lenis) {
                lenis.scrollTo(target, { offset: -offset(), duration: 1.2 });
            } else {
                const top = target.getBoundingClientRect().top + window.scrollY - offset();
                window.scrollTo({ top, behavior: reduced ? 'auto' : 'smooth' });
            }
            if (history.replaceState) {
                history.replaceState(null, '', href);
            }
            target.setAttribute('tabindex', '-1');
            window.setTimeout(() => target.focus({ preventScroll: true }), 600);
            window.setTimeout(() => target.removeAttribute('tabindex'), 1200);
        });
    });
}

/* --------------------------------------------------------------------------
   Fond interpolé carbone ↔ givre
   -------------------------------------------------------------------------- */
let themeStops = [];

function initBackgroundTheme() {
    themeStops = $$('[data-theme]').map((el) => ({
        el,
        theme: el.getAttribute('data-theme') === 'light' ? 'light' : 'dark',
        top: el.offsetTop,
    }));
    updateBackgroundTheme();
}

let bgTicking = false;

function updateBackgroundTheme() {
    if (!themeStops.length) return;
    const mid = window.scrollY + window.innerHeight * 0.42;
    /* Positions recalculées à chaque frame : les sections épinglées (ScrollTrigger)
       décalent la page, un cache d'offset rendrait les thèmes faux. */
    const stops = themeStops.map((stop) => ({
        theme: stop.theme,
        top: stop.el.getBoundingClientRect().top + window.scrollY,
    }));
    let currentIndex = 0;
    for (let i = 0; i < stops.length; i++) {
        if (stops[i].top <= mid) currentIndex = i;
    }
    const current = stops[currentIndex];
    const previous = stops[currentIndex - 1] || current;
    const transition = Math.min(340, window.innerHeight * 0.4);
    const start = current.top - transition * 0.35;
    const t = clamp((mid - start) / transition, 0, 1);
    const bg = mixColors(THEMES[previous.theme], THEMES[current.theme], t);

    docEl.style.setProperty('--bg', bg);
    docEl.style.backgroundColor = bg;

    const surface = t > 0.5 ? current.theme : previous.theme;
    if (docEl.getAttribute('data-surface') !== surface) {
        docEl.setAttribute('data-surface', surface);
    }
}

function onBackgroundScroll() {
    if (bgTicking) return;
    bgTicking = true;
    requestAnimationFrame(() => {
        updateBackgroundTheme();
        bgTicking = false;
    });
}

/* --------------------------------------------------------------------------
   Révélations GSAP
   -------------------------------------------------------------------------- */
function initReveals(gsap, ScrollTrigger) {
    /* Lignes masquées (titres) */
    $$('.line-mask > .line').forEach((line) => {
        if (line.closest('.hero')) return;
        const mask = line.closest('.line-mask');
        gsap.set(line, { yPercent: 112 });
        gsap.to(line, {
            yPercent: 0,
            duration: 1.05,
            ease: 'expo.out',
            scrollTrigger: { trigger: mask, start: 'top 90%', once: true },
        });
        const serif = line.querySelector('.serif');
        if (serif) {
            gsap.from(serif, {
                opacity: 0,
                duration: .9,
                delay: .35,
                ease: 'power2.out',
                scrollTrigger: { trigger: mask, start: 'top 90%', once: true },
            });
        }
    });

    /* Fonds / blocs */
    gsap.utils.toArray('[data-reveal]').forEach((el) => {
        if (el.closest('.hero')) return;
        gsap.to(el, {
            opacity: 1,
            y: 0,
            duration: .9,
            ease: 'power3.out',
            scrollTrigger: { trigger: el, start: 'top 90%', once: true },
        });
    });

    /* Paragraphes découpés en lignes */
    if (window.SplitText) {
        $$('[data-split]').forEach((el) => {
            try {
                SplitText.create(el, {
                    type: 'lines',
                    mask: 'lines',
                    autoSplit: true,
                    onSplit(self) {
                        return gsap.from(self.lines, {
                            yPercent: 110,
                            opacity: 0,
                            duration: .9,
                            stagger: .06,
                            ease: 'power3.out',
                            scrollTrigger: { trigger: el, start: 'top 90%', once: true },
                        });
                    },
                });
            } catch (err) {
                /* pas bloquant */
            }
        });
    }
}

/* --------------------------------------------------------------------------
   Marquee
   -------------------------------------------------------------------------- */
function initMarquees(gsap, ScrollTrigger) {
    $$('[data-marquee]').forEach((track) => {
        const tween = gsap.to(track, {
            xPercent: -50,
            repeat: -1,
            duration: 30,
            ease: 'none',
        });
        let boost = 0;
        ScrollTrigger.create({
            start: 0,
            end: 'max',
            onUpdate: (self) => {
                boost = Math.min(Math.abs(self.getVelocity()) / 900, 2.2);
            },
        });
        gsap.ticker.add(() => {
            boost *= 0.94;
            tween.timeScale(1 + boost);
        });
        track.closest('.marquee, .services')?.addEventListener('mouseenter', () => {
            gsap.to(tween, { timeScale: .25, duration: .6, overwrite: true });
        });
        track.closest('.marquee, .services')?.addEventListener('mouseleave', () => {
            gsap.to(tween, { timeScale: 1, duration: .6, overwrite: true });
        });
    });

    $$('[data-band-track]').forEach((track) => {
        const tween = gsap.to(track, { xPercent: -50, repeat: -1, duration: 38, ease: 'none' });
        track.closest('.contact-band')?.addEventListener('mouseenter', () => {
            gsap.to(tween, { timeScale: 2.4, duration: .5, overwrite: true });
        });
        track.closest('.contact-band')?.addEventListener('mouseleave', () => {
            gsap.to(tween, { timeScale: 1, duration: .5, overwrite: true });
        });
    });
}

/* --------------------------------------------------------------------------
   Curseur
   -------------------------------------------------------------------------- */
function initCursor(gsap) {
    if (!prefersFinePointer || reduced) return;
    const ring = $('[data-cursor-ring]');
    const dot = $('[data-cursor-dot]');
    if (!ring || !dot) return;
    docEl.classList.add('has-cursor');

    const ringX = gsap.quickTo(ring, 'x', { duration: .5, ease: 'power3' });
    const ringY = gsap.quickTo(ring, 'y', { duration: .5, ease: 'power3' });
    const dotX = gsap.quickTo(dot, 'x', { duration: .12, ease: 'power3' });
    const dotY = gsap.quickTo(dot, 'y', { duration: .12, ease: 'power3' });

    gsap.set([ring, dot], { x: -100, y: -100 });

    window.addEventListener('mousemove', (e) => {
        gsap.set([ring, dot], { xPercent: 0, yPercent: 0 });
        ringX(e.clientX);
        ringY(e.clientY);
        dotX(e.clientX);
        dotY(e.clientY);
    }, { passive: true });

    const setState = (target) => {
        const isView = target.closest('[data-cursor="view"]');
        const isLink = target.closest('a, button, input, textarea, select, label, [role="button"]');
        ring.classList.toggle('is-view', Boolean(isView));
        ring.classList.toggle('is-link', Boolean(isLink) && !isView);
    };

    document.addEventListener('mouseover', (e) => setState(e.target));
    document.addEventListener('mousedown', () => gsap.to(ring, { scale: .82, duration: .2 }));
    document.addEventListener('mouseup', () => gsap.to(ring, { scale: 1, duration: .25 }));
}

/* --------------------------------------------------------------------------
   Footer DST
   -------------------------------------------------------------------------- */
function initFooterGiant() {
    const wrap = $('[data-footer-giant]');
    const giant = $('[data-footer-dst]', wrap || document);
    if (!wrap || !giant) return;

    const spots = $$('[data-footer-spot]', giant);
    const targets = [giant, ...spots];
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Même halo lumineux sur les lettres et sur les mots, au pixel près. */
    wrap.addEventListener('mousemove', (e) => {
        targets.forEach((el) => {
            const r = el.getBoundingClientRect();
            if (!r.width || !r.height) return;
            const x = clamp(((e.clientX - r.left) / r.width) * 100, -30, 130);
            const y = clamp(((e.clientY - r.top) / r.height) * 100, -30, 130);
            el.style.setProperty('--x', `${x}%`);
            el.style.setProperty('--y', `${y}%`);
        });
    });
    wrap.addEventListener('mouseleave', () => {
        targets.forEach((el) => {
            el.style.setProperty('--x', '-30%');
            el.style.setProperty('--y', '50%');
        });
    });

    /* Apparition en cascade des trois groupes lettre + mot.
       Le scroll natif + un filet de sécurité couvrent aussi Lenis, le repli
       sans GSAP et les chargements où l'observateur ne se déclenche pas. */
    if (reduceMotion) {
        wrap.classList.add('is-visible');
        return;
    }
    const reveal = () => {
        if (wrap.classList.contains('is-visible')) return;
        const r = wrap.getBoundingClientRect();
        if (r.top < window.innerHeight * .95 && r.bottom > 0) {
            wrap.classList.add('is-visible');
            window.removeEventListener('scroll', reveal);
            window.removeEventListener('resize', reveal);
            window.removeEventListener('load', reveal);
        }
    };
    window.addEventListener('scroll', reveal, { passive: true });
    window.addEventListener('resize', reveal);
    window.addEventListener('load', reveal);
    requestAnimationFrame(reveal);
    window.setTimeout(reveal, 1400);
}

/* --------------------------------------------------------------------------
   Bouton d'appel flottant
   -------------------------------------------------------------------------- */
function initFloating() {
    const hero = $('.hero');
    const call = $('.floating__btn--call');
    if (!hero || !call) return;

    const toggle = () => {
        const visible = window.scrollY > hero.offsetHeight * 0.7;
        call.classList.toggle('is-visible', visible);
    };
    window.addEventListener('scroll', toggle, { passive: true });
    toggle();
}

/* --------------------------------------------------------------------------
   Vidéo du hero (économie de données)
   -------------------------------------------------------------------------- */
function initHeroVideo() {
    const video = $('[data-hero-video]');
    if (!video) return;
    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    const saveData = conn && (conn.saveData || /2g/.test(conn.effectiveType || ''));
    if (saveData || reduced) {
        video.querySelectorAll('source').forEach((s) => s.remove());
        video.removeAttribute('autoplay');
        try {
            video.load();
        } catch (e) {}
        return;
    }
    const play = () => {
        const promise = video.play();
        if (promise && promise.catch) promise.catch(() => {});
    };
    play();
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            video.pause();
        } else {
            play();
        }
    });
}

/* --------------------------------------------------------------------------
   Préchargeur
   -------------------------------------------------------------------------- */
function runPreloader(gsap) {
    const preloader = $('[data-preloader]');
    const isPreloading = docEl.classList.contains('preloading');

    if (!preloader || !isPreloading || reduced) {
        docEl.classList.remove('preloading');
        docEl.classList.add('preloader-done');
        if (preloader) preloader.classList.add('is-open');
        return Promise.resolve();
    }

    try {
        sessionStorage.setItem('dst-preloader', '1');
    } catch (e) {}

    return new Promise((resolve) => {
        const temp = $('[data-preloader-temp]', preloader);
        const fill = $('[data-preloader-fill]', preloader);
        const status = $('[data-preloader-status]', preloader);
        const mark = $('.logo-mark--preloader', preloader);

        if (mark) {
            gsap.set(mark, { opacity: 0, scale: .82, transformOrigin: '50% 50%' });
        }
        gsap.set(fill, { scaleX: .06 });
        const value = { t: 20 };

        const tl = gsap.timeline({
            defaults: { ease: 'power2.inOut' },
            onComplete: () => {
                /* Les portes s'ouvrent sur le contenu, puis l'overlay disparaît.
                   Durée totale maîtrisée : ~0,9 s de séquence + 0,7 s d'ouverture. */
                preloader.classList.add('is-open');
                window.setTimeout(() => {
                    docEl.classList.add('preloader-done');
                    docEl.classList.remove('preloading');
                    preloader.style.display = 'none';
                    resolve();
                }, 700);
            },
        });

        if (mark) {
            tl.to(mark, { opacity: 1, scale: 1, duration: .6, ease: 'power3.out' }, 0);
        }
        tl.to(fill, { scaleX: .9, duration: .78, ease: 'power2.inOut' }, .1)
            .to(value, {
                t: 3,
                duration: .78,
                ease: 'power2.inOut',
                onUpdate: () => {
                    if (temp) temp.textContent = `${value.t.toFixed(1).replace('.', ',')} °C`;
                },
            }, .1)
            .call(() => {
                if (status) status.textContent = 'Chaîne du froid activée';
            }, null, .8)
            .to({}, { duration: .05 });
    });
}


/* --------------------------------------------------------------------------
   Portes de caisson (secteurs, engagements)
   -------------------------------------------------------------------------- */
function initDoors(gsap, ScrollTrigger, reduced) {
    if (reduced) {
        document.querySelectorAll('.sector__shutter').forEach((s) => { s.style.display = 'none'; });
        return;
    }
    document.querySelectorAll('[data-doors]').forEach((el) => {
        const shutters = Array.from(el.querySelectorAll('.sector__shutter'));
        if (!shutters.length) return;
        const img = el.querySelector('img');
        gsap.set(shutters, { scaleX: 1 });
        ScrollTrigger.create({
            trigger: el,
            start: 'top 84%',
            once: true,
            onEnter: () => {
                gsap.to(shutters, {
                    scaleX: 0,
                    duration: 1.15,
                    ease: 'expo.inOut',
                    stagger: .04,
                });
                if (img) {
                    gsap.fromTo(img, { scale: 1.12 }, { scale: 1, duration: 1.5, ease: 'power2.out' });
                }
            },
        });
    });
}

/* --------------------------------------------------------------------------
   Givre au curseur sur les tuiles secteurs
   -------------------------------------------------------------------------- */
function initFrost() {
    if (!prefersFinePointer) return;
    document.querySelectorAll('[data-sector]').forEach((sector) => {
        sector.addEventListener('mousemove', (e) => {
            const r = sector.getBoundingClientRect();
            const x = ((e.clientX - r.left) / r.width) * 100;
            const y = ((e.clientY - r.top) / r.height) * 100;
            sector.style.setProperty('--mx', `${x}%`);
            sector.style.setProperty('--my', `${y}%`);
        }, { passive: true });
    });
}

/* --------------------------------------------------------------------------
   Engagements : lignes qui se remplissent + icônes qui se dessinent
   -------------------------------------------------------------------------- */
function initCommitments(gsap, ScrollTrigger, reduced) {
    const items = document.querySelectorAll('[data-commit]');
    items.forEach((item, i) => {
        const line = item.querySelector('[data-commit-line]');
        const icon = item.querySelector('.commit-item__icon svg');
        if (reduced) {
            if (line) line.style.transform = 'none';
            return;
        }
        ScrollTrigger.create({
            trigger: item,
            start: 'top 88%',
            once: true,
            onEnter: () => {
                if (line) {
                    gsap.to(line, { scaleX: 1, duration: .9, delay: i * .05, ease: 'power2.out' });
                }
                if (icon && window.DrawSVGPlugin) {
                    gsap.fromTo(icon, { drawSVG: '0%' }, { drawSVG: '100%', duration: .9, ease: 'power2.inOut' });
                }
            },
        });
    });

    const band = document.querySelector('.trust-band');
    if (band && !reduced) {
        gsap.from(band.querySelectorAll('.trust-stat'), {
            opacity: 0,
            y: 20,
            duration: .8,
            stagger: .08,
            ease: 'power3.out',
            scrollTrigger: { trigger: band, start: 'top 88%', once: true },
        });
    }
}

/* --------------------------------------------------------------------------
   Route de la section « comment ça marche »
   -------------------------------------------------------------------------- */
function initMethod(gsap, ScrollTrigger, reduced) {
    const path = document.querySelector('[data-method-path]');
    const dot = document.querySelector('[data-method-dot]');
    if (!path) return;
    const section = path.closest('.method__track') || path.closest('.method');

    if (reduced) {
        path.style.strokeDashoffset = '0';
        if (dot) {
            const pt = path.getPointAtLength(path.getTotalLength());
            dot.setAttribute('cx', pt.x);
            dot.setAttribute('cy', pt.y);
        }
        return;
    }

    const length = path.getTotalLength();
    gsap.set(path, { strokeDashoffset: 1 });
    if (dot) {
        const pt0 = path.getPointAtLength(0);
        dot.setAttribute('cx', pt0.x);
        dot.setAttribute('cy', pt0.y);
    }

    ScrollTrigger.create({
        trigger: section,
        start: 'top 80%',
        end: 'bottom 65%',
        scrub: true,
        onUpdate: (self) => {
            path.style.strokeDashoffset = String(1 - self.progress);
            if (dot && length) {
                const pt = path.getPointAtLength(length * self.progress);
                dot.setAttribute('cx', pt.x);
                dot.setAttribute('cy', pt.y);
            }
        },
    });
}

/* --------------------------------------------------------------------------
   Boot
   -------------------------------------------------------------------------- */
async function waitForLibs(timeout = 7000) {
    const start = performance.now();
    while (performance.now() - start < timeout) {
        if (window.gsap && window.ScrollTrigger) return true;
        await new Promise((r) => setTimeout(r, 60));
    }
    return false;
}

function noGsapFallback() {
    docEl.classList.add('no-gsap');
    docEl.classList.remove('preloading');
    docEl.classList.add('preloader-done');
    $$('.sector__shutter').forEach((s) => { s.style.display = 'none'; });
    $$('[data-cold-curve]').forEach((p) => { p.style.strokeDashoffset = '0'; });
    positionColdCurveVan(1);
    $$('[data-commit-line]').forEach((s) => { s.style.transform = 'none'; });
    const proof = $('[data-cold-proof]');
    if (proof) proof.classList.add('is-visible');
    $$('[data-method-path]').forEach((p) => { p.style.strokeDashoffset = '0'; });
    const footerGiant = $('[data-footer-giant]');
    if (footerGiant) footerGiant.classList.add('is-visible');
    /* Le formulaire, la flotte et la carte restent utilisables sans GSAP :
       seule la mise en scène disparaît. */
    try { initQuote(null, null, true); } catch (e) { console.warn('quote fallback', e); }
    try { initFleet(null, null, true); } catch (e) { console.warn('fleet fallback', e); }
    try { initMap(null, null, true); } catch (e) { console.warn('map fallback', e); }
}

async function boot() {
    /* Interface indépendante des animations */
    initHeader();
    initMobileMenu();
    initModals();
    initCookies();
    initAccordion();
    initServices();
    initTestimonials();
    initAnchors();
    initFooterGiant();
    initFloating();
    initHeroVideo();
    initBackgroundTheme();
    window.addEventListener('scroll', onBackgroundScroll, { passive: true });
    window.addEventListener('resize', () => {
        initBackgroundTheme();
        if (window.ScrollTrigger) ScrollTrigger.refresh();
    });

    const hasLibs = await waitForLibs();

    if (!hasLibs) {
        noGsapFallback();
        return;
    }

    const gsap = window.gsap;
    gsap.registerPlugin(window.ScrollTrigger, window.SplitText, window.DrawSVGPlugin, window.MotionPathPlugin, window.Flip, window.ScrambleTextPlugin);
    docEl.classList.add('gsap-ready');

    /* Lenis — scroll fluide, sauf mouvement réduit */
    if (!reduced && window.Lenis) {
        lenis = new window.Lenis({ lerp: .1, smoothWheel: true, wheelMultiplier: 1, autoRaf: false });
        lenis.on('scroll', () => {
            ScrollTrigger.update();
            onBackgroundScroll();
        });
        gsap.ticker.add((time) => lenis.raf(time * 1000));
        gsap.ticker.lagSmoothing(0);
    }

    ScrollTrigger.config({ ignoreMobileResize: true });

    await runPreloader(gsap);

    try {
        initHero(gsap, ScrollTrigger, reduced, lenis);
    } catch (e) {
        console.warn('hero', e);
        gsap.set('.hero__title .line', { yPercent: 0, opacity: 1 });
        gsap.set('.hero [data-reveal]', { opacity: 1, y: 0 });
    }
    try { initRoute(gsap, ScrollTrigger, reduced); } catch (e) { console.warn('route', e); }
    try { initColdChain(gsap, ScrollTrigger, reduced); } catch (e) { console.warn('cold', e); }
    try { initFleet(gsap, ScrollTrigger, reduced); } catch (e) { console.warn('fleet', e); }
    try { initMap(gsap, ScrollTrigger, reduced); } catch (e) { console.warn('map', e); }
    try { initQuote(gsap, ScrollTrigger, reduced); } catch (e) { console.warn('quote', e); }
    try { initCounters(gsap, ScrollTrigger, reduced); } catch (e) { console.warn('counters', e); }
    try { initDoors(gsap, ScrollTrigger, reduced); } catch (e) { console.warn('doors', e); }
    try { initCommitments(gsap, ScrollTrigger, reduced); } catch (e) { console.warn('commitments', e); }
    try { initMethod(gsap, ScrollTrigger, reduced); } catch (e) { console.warn('method', e); }
    try { initFrost(); } catch (e) { console.warn('frost', e); }
    try { initReveals(gsap, ScrollTrigger); } catch (e) { console.warn('reveals', e); }
    try { initMarquees(gsap, ScrollTrigger); } catch (e) { console.warn('marquee', e); }
    try { initCursor(gsap); } catch (e) { console.warn('cursor', e); }

    ScrollTrigger.refresh();

    reduceQuery.addEventListener?.('change', (e) => {
        if (e.matches) {
            lenis?.destroy();
            lenis = null;
            window.location.reload();
        }
    });
}

boot();
