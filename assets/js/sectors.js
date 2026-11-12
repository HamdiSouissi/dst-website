/**
 * sectors.js — scènes par secteur.
 * Changement de scène avec Flip, lecture automatique (7 s, barre de
 * progression), pause au survol et au focus, clavier (flèches).
 */
export default function initSectors(DST) {
  const root = document.querySelector('[data-sectors-tabs]');
  const scenesRoot = document.querySelector('[data-sectors-scenes]');
  if (!root || !scenesRoot) return;
  const tabs = Array.from(root.querySelectorAll('[data-sector]'));
  const scenes = Array.from(scenesRoot.querySelectorAll('[data-scene]'));
  if (tabs.length < 2) return;
  const DURATION = 7000;
  let index = 0;
  let timer = null;
  let tween = null;
  let paused = false;

  const progress = (tab) => tab.querySelector('[data-progress]');

  const stop = () => {
    window.clearTimeout(timer);
    tween?.kill();
    tween = null;
  };

  const schedule = () => {
    stop();
    if (paused || DST.reduced) return;
    const bar = progress(tabs[index]);
    if (bar) {
      bar.style.width = '0%';
      tween = gsap.fromTo(bar, { width: '0%' }, { width: '100%', duration: DURATION / 1000, ease: 'none' });
    }
    timer = window.setTimeout(() => show(index + 1), DURATION);
  };

  const show = (next, userAction = true) => {
    const target = (next + tabs.length) % tabs.length;
    if (target === index) return;
    const from = scenes[index];
    const to = scenes[target];

    tabs.forEach((t, i) => {
      const active = i === target;
      t.classList.toggle('is-active', active);
      t.setAttribute('aria-selected', String(active));
      t.tabIndex = active ? 0 : -1;
    });

    index = target;

    if (!window.gsap || DST.reduced || !window.Flip) {
      scenes.forEach((s, i) => {
        s.hidden = i !== target;
        s.classList.toggle('is-active', i === target);
      });
      schedule();
      return;
    }

    const state = Flip.getState([from.querySelector('[data-scene-media]'), to.querySelector('[data-scene-media]')]);
    from.hidden = true;
    from.classList.remove('is-active');
    to.hidden = false;
    to.classList.add('is-active');
    Flip.from(state, { duration: 0.85, ease: 'power3.inOut', absolute: true, scale: true });
    gsap.fromTo(
      to.querySelectorAll('.scene__card > *'),
      { y: 22, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.6, stagger: 0.06, delay: 0.2, ease: 'power3.out', clearProps: 'transform' }
    );
    gsap.fromTo(to.querySelector('.scene__img img'), { scale: 1.08 }, { scale: 1, duration: 1.4, ease: 'power2.out' });

    schedule();
  };

  tabs.forEach((tab, i) => {
    tab.addEventListener('click', () => show(i, false));
    tab.addEventListener('focus', () => {
      paused = true;
      stop();
    });
    tab.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
        e.preventDefault();
        const dir = e.key === 'ArrowRight' ? 1 : -1;
        const next = (i + dir + tabs.length) % tabs.length;
        show(next, false);
        tabs[next].focus();
      }
    });
  });

  root.addEventListener('pointerenter', () => {
    paused = true;
    stop();
  });
  root.addEventListener('pointerleave', () => {
    paused = false;
    schedule();
  });
  root.addEventListener('focusout', (e) => {
    if (!root.contains(e.relatedTarget)) {
      paused = false;
      schedule();
    }
  });

  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) schedule();
        else stop();
      },
      { threshold: 0.2 }
    );
    io.observe(scenesRoot);
  } else {
    schedule();
  }

  document.addEventListener('visibilitychange', () => {
    if (document.hidden) stop();
    else schedule();
  });

  // petite apparition de la scène au chargement
  if (window.gsap && !DST.reduced) {
    gsap.from(scenesRoot, {
      y: 40,
      opacity: 0,
      duration: 0.9,
      ease: 'power3.out',
      scrollTrigger: { trigger: scenesRoot, start: 'top 85%', once: true },
      clearProps: 'transform',
    });
  }
}
