/**
 * hero.js — le film en boucle et le mot cinétique découpé dedans.
 *
 * 1. La vidéo démarre quand le hero entre dans le viewport, se met en pause
 *    quand on le quitte, et n'est jamais chargée si l'utilisateur économise
 *    ses données ou si son réseau est lent.
 * 2. Le mot géant est une découpe dans le bandeau crème posé sur le film :
 *    FRAIS → DIRECT → À L'HEURE. Les trois découpes sont superposées dans le
 *    même masque SVG, on les fait se fondre l'une dans l'autre — aucune
 *    duplication du fichier vidéo, aucun décalage d'image.
 * 3. `prefers-reduced-motion` : la vidéo reste sur son affiche et le mot ne
 *    change plus.
 */
export default function initHero(DST) {
  const hero = document.querySelector('[data-hero]');
  if (!hero) return;

  const video = hero.querySelector('[data-hero-video]');
  const holes = Array.from(hero.querySelectorAll('[data-hole]'));
  const outlines = Array.from(hero.querySelectorAll('[data-outline]'));
  const sticker = hero.querySelector('.sticker--hero');
  const { reduced } = DST;

  /* ---------- 1. le film ---------- */
  const conn = navigator.connection || {};
  const frugal = conn.saveData === true || ['slow-2g', '2g'].includes(conn.effectiveType);

  if (video) {
    if (frugal) {
      video.removeAttribute('autoplay');
      video.pause?.();
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
              if (!reduced) start();
            } else if (!video.paused) {
              video.pause();
            }
          },
          { threshold: 0.05 }
        );
        io.observe(video);
      } else if (!reduced) {
        start();
      }

      video.addEventListener('canplay', () => hero.classList.add('is-film-ready'), { once: true });

      // si le navigateur refuse la lecture auto, on garde l'affiche : rien ne casse
      const attempt = video.play?.();
      if (attempt && attempt.catch) attempt.catch(() => hero.classList.add('is-film-blocked'));
    }
  }

  if (!window.gsap) return;

  /* ---------- 2. le sticker tombe à l'arrivée ---------- */
  if (sticker && !reduced) {
    gsap.from(sticker, {
      y: -70,
      rotate: -36,
      scale: 1.55,
      opacity: 0,
      duration: 1.5,
      delay: 0.35,
      ease: 'elastic.out(1, .55)',
    });
  }

  /* ---------- 3. contours des lettres, puis effacement ---------- */
  if (!reduced && outlines.length) {
    const uses = outlines[0].querySelectorAll('use');
    if (uses.length) {
      gsap.set(outlines[0], { opacity: 0.55 });
      gsap.set(uses, { drawSVG: '0%' });
      gsap.to(uses, {
        drawSVG: '100%',
        duration: 0.85,
        stagger: 0.045,
        ease: 'power2.inOut',
        delay: 0.15,
        onComplete() {
          gsap.to(outlines[0], { opacity: 0, duration: 0.9, delay: 0.2 });
        },
      });
    }
  }

  /* ---------- 4. le mot cinétique ---------- */
  if (reduced || holes.length < 2) return;

  let index = 0;
  let held = false;
  let loop = null;

  const show = (next) => {
    const from = holes[index];
    const fromLine = outlines[index];
    const to = holes[next];
    const toLine = outlines[next];
    index = next;

    gsap.to(from, { opacity: 0, duration: 0.55, ease: 'power2.inOut' });
    gsap.to(to, { opacity: 1, duration: 0.55, ease: 'power2.inOut' });
    from.classList.remove('is-on');
    to.classList.add('is-on');

    if (fromLine) gsap.to(fromLine, { opacity: 0, duration: 0.4 });
    if (toLine) {
      gsap.fromTo(toLine, { opacity: 0 }, { opacity: 0.5, duration: 0.5, delay: 0.15 });
      gsap.to(toLine, { opacity: 0, duration: 0.8, delay: 1.6 });
    }
  };

  const cycle = () => {
    show((index + 1) % holes.length);
    loop = gsap.delayedCall(4.6, cycle);
  };

  loop = gsap.delayedCall(3.4, cycle);

  // survol : on met la rotation en pause, la vidéo continue
  const wordEl = hero.querySelector('[data-hero-word]');
  wordEl?.addEventListener('pointerenter', () => {
    held = true;
    loop?.pause();
  });
  wordEl?.addEventListener('pointerleave', () => {
    held = false;
    loop?.resume();
  });

  // hors viewport : plus rien ne tourne
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(
      ([e]) => {
        if (!e.isIntersecting) {
          loop?.pause();
        } else if (!held) {
          loop?.resume();
        }
      },
      { threshold: 0 }
    );
    io.observe(hero);
  }

  // respect du réglage système si l'utilisateur le change en cours de route
  const mq = window.matchMedia('(prefers-reduced-motion: reduce)');
  mq.addEventListener?.('change', (e) => {
    if (e.matches) {
      loop?.pause();
      video?.pause();
      gsap.set(holes, { opacity: 0 });
      gsap.set(holes[0], { opacity: 1 });
      index = 0;
    } else {
      loop?.resume();
    }
  });
}
