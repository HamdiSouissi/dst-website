/**
 * hero.js — le mot géant, la vidéo dans les lettres et la camionnette.
 *
 * 1. La vidéo démarre quand le hero entre dans le viewport (coupée si Save-Data).
 * 2. La camionnette traverse le mot. Elle est masquée par les lettres du mot
 *    affiché : elle passe derrière elles, et devant le fond. Un givre discret
 *    s'échappe du caisson.
 * 3. Le mot devient cinétique : FRAIS → DIRECT → À L'HEURE, en boucle lente,
 *    avec une pause au survol et à la perte de visibilité.
 *
 * Les déplacements se font dans le repère du SVG (unités de police, largeur
 * 2019) : le rendu est identique de 320 px à 2560 px de large.
 */
export default function initHero(DST) {
  const hero = document.querySelector('[data-hero]');
  if (!hero) return;

  const sign = hero.querySelector('[data-hero-sign]');
  const van = hero.querySelector('[data-hero-van]');
  const words = Array.from(hero.querySelectorAll('[data-word]'));
  const video = hero.querySelector('[data-hero-video]');
  const sticker = hero.querySelector('.sticker--hero');
  const box = sign ? sign.viewBox.baseVal : null;
  const W = box && box.width ? box.width : 2019;

  /* ---------- 1. vidéo ---------- */
  const conn = navigator.connection || {};
  const saveData = conn.saveData === true;
  const slow = ['slow-2g', '2g'].includes(conn.effectiveType);
  if (video) {
    if (saveData || slow) {
      video.remove();
    } else {
      video.muted = true;
      const start = () => {
        const p = video.play();
        if (p && p.catch) p.catch(() => {});
      };
      if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(
          ([e]) => {
            if (e.isIntersecting) {
              video.preload = 'auto';
              start();
            } else {
              video.pause();
            }
          },
          { threshold: 0.05 }
        );
        io.observe(video);
      } else {
        start();
      }
      video.addEventListener('canplay', () => hero.classList.add('is-video-ready'), { once: true });
    }
  }

  if (!window.gsap) return;
  const { reduced } = DST;

  /* ---------- 2. le sticker tombe à l'arrivée ---------- */
  if (sticker && !reduced) {
    gsap.from(sticker, {
      y: -70,
      rotate: -36,
      scale: 1.55,
      opacity: 0,
      duration: 1.5,
      delay: 0.45,
      ease: 'elastic.out(1, .55)',
    });
  }

  /* ---------- 3. contours des lettres, puis remplissage ---------- */
  const outlines = hero.querySelectorAll('.hero__outline');
  if (!reduced && outlines.length) {
    const first = outlines[0].querySelectorAll('use');
    if (first.length) {
      gsap.set(outlines[0], { opacity: 0.5 });
      gsap.set(first, { drawSVG: '0%' });
      gsap.to(first, {
        drawSVG: '100%',
        duration: 0.85,
        stagger: 0.05,
        ease: 'power2.inOut',
        delay: 0.1,
        onComplete() {
          gsap.to(outlines[0], { opacity: 0, duration: 0.8, delay: 0.12 });
        },
      });
    }
  }

  if (!van || !words.length) return;

  const frost = van.querySelectorAll('.hero__frost circle');
  const VAN_W = 600;                                   // largeur du dessin de la camionnette
  const START = -VAN_W - 40;                           // hors cadre, à gauche
  const END = W + 40;                                  // hors cadre, à droite

  gsap.set(van, { x: START });

  if (reduced) {
    // image fixe : la camionnette au centre, mot FRAIS seulement
    gsap.set(van, { x: (W - VAN_W) / 2 });
    words.forEach((w, i) => {
      w.classList.toggle('is-on', i === 0);
      w.style.visibility = i === 0 ? 'visible' : 'hidden';
    });
    return;
  }

  let index = 0;
  let paused = false;
  let loop = null;

  const showWord = (next, animate) => {
    const from = words[index];
    const to = words[next];
    index = next;
    if (!animate) {
      words.forEach((w, i) => {
        w.classList.toggle('is-on', i === index);
        w.style.visibility = i === index ? 'visible' : 'hidden';
      });
      return;
    }
    gsap.to(from, {
      opacity: 0,
      y: -18,
      duration: 0.34,
      ease: 'power2.in',
      onComplete() {
        from.classList.remove('is-on');
        from.style.visibility = 'hidden';
        gsap.set(from, { opacity: 1, y: 0 });
      },
    });
    to.classList.add('is-on');
    to.style.visibility = 'visible';
    gsap.fromTo(
      to,
      { opacity: 0, y: 26, scaleY: 0.94, transformOrigin: '50% 100%' },
      { opacity: 1, y: 0, scaleY: 1, duration: 0.62, ease: 'expo.out', delay: 0.12 }
    );
  };

  const burst = () => {
    gsap.fromTo(
      frost,
      { opacity: 0, x: 0, y: 0, scale: 0.4 },
      {
        opacity: 1,
        duration: 0.3,
        stagger: 0.05,
        onComplete() {
          gsap.to(frost, {
            opacity: 0,
            x: () => gsap.utils.random(-150, -60),
            y: () => gsap.utils.random(-40, 40),
            scale: 1.5,
            duration: 1.2,
            stagger: 0.06,
            ease: 'power1.out',
          });
        },
      }
    );
  };

  // un passage complet : entrée par la gauche, traversée, sortie à droite
  const pass = () => {
    const tl = gsap.timeline({
      onComplete() {
        if (!paused) loop = gsap.delayedCall(1.6, pass);
      },
    });
    current = tl;
    tl.to(van, { x: START, duration: 0 })
      .to(van, { opacity: 1, duration: 0.2 }, 0)
      .to(van, { x: END, duration: 4.4, ease: 'none' }, 0)
      .call(burst, [], 2.15)
      .call(() => showWord((index + 1) % words.length, true), [], 2.3)
      .to(van, { opacity: 0, duration: 0.2 }, 4.2);
    return tl;
  };

  // première apparition : elle entre et s'arrête au centre, avec une retombée
  let current = null;
  const intro = gsap.timeline({ delay: 0.9 });
  current = intro;
  intro
    .to(van, { opacity: 1, duration: 0.25 }, 0)
    .to(van, { x: (W - VAN_W) / 2, duration: 1.55, ease: 'power3.out' }, 0)
    .to(van, { y: '+=8', duration: 0.16, yoyo: true, repeat: 1, ease: 'power2.inOut' }, '-=0.12')
    .call(burst, [], '-=0.2')
    .call(() => {
      loop = gsap.delayedCall(1.4, pass);
    });

  // pause au survol du mot (on n'interrompt pas un passage en cours)
  const wordEl = hero.querySelector('[data-hero-word]');
  wordEl?.addEventListener('pointerenter', () => {
    paused = true;
    loop?.pause();
  });
  wordEl?.addEventListener('pointerleave', () => {
    paused = false;
    loop?.resume();
  });

  // hors viewport : on met tout en pause (batterie, CPU)
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(
      ([e]) => {
        if (!e.isIntersecting) {
          loop?.pause();
          current?.pause();
          video?.pause();
        } else if (!paused) {
          current?.resume();
          loop?.resume();
        }
      },
      { threshold: 0 }
    );
    io.observe(hero);
  }
}
