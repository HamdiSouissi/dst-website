/**
 * labels.js — étiquettes de cagette.
 * - inclinaison 3D (±10°) selon la souris, avec reflet satiné qui suit le curseur ;
 * - au clic (ou au toucher) l'étiquette se retourne (verso = détail).
 */
export default function initLabels(DST) {
  const items = DST.$$('[data-tilt]');
  if (!items.length) return;
  const fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  const { reduced } = DST;

  items.forEach((tilt) => {
    const card = tilt.closest('.label');
    const btn = tilt.querySelector('[data-flip]');
    const sheen = tilt.querySelector('.label__sheen');
    let raf = 0;

    if (fine && !reduced) {
      const move = (e) => {
        cancelAnimationFrame(raf);
        raf = requestAnimationFrame(() => {
          const r = tilt.getBoundingClientRect();
          const px = (e.clientX - r.left) / r.width - 0.5;
          const py = (e.clientY - r.top) / r.height - 0.5;
          tilt.style.transform = `rotate(var(--rot)) rotateX(${(-py * 16).toFixed(2)}deg) rotateY(${(px * 18).toFixed(2)}deg) translateZ(0)`;
          if (sheen) {
            sheen.style.setProperty('--mx', ((px + 0.5) * 100).toFixed(1) + '%');
            sheen.style.setProperty('--my', ((py + 0.5) * 100).toFixed(1) + '%');
          }
        });
      };
      tilt.addEventListener('pointermove', move);
      tilt.addEventListener('pointerleave', () => {
        cancelAnimationFrame(raf);
        tilt.style.transform = '';
      });
    }

    if (!btn) return;
    btn.addEventListener('click', () => {
      const pressed = btn.getAttribute('aria-pressed') === 'true';
      btn.setAttribute('aria-pressed', String(!pressed));
      btn.setAttribute('aria-expanded', String(!pressed));
      card.classList.toggle('is-flipped', !pressed);
      if (window.gsap && !reduced) {
        gsap.fromTo(
          tilt.querySelector('.label__face--back') || tilt,
          { opacity: pressed ? 1 : 0.6 },
          { opacity: 1, duration: 0.4, delay: 0.25 }
        );
      }
    });

    // retour au recto quand on quitte la carte (souris)
    card.addEventListener('pointerleave', () => {
      if (!fine) return;
      if (btn.getAttribute('aria-pressed') === 'true') {
        btn.setAttribute('aria-pressed', 'false');
        btn.setAttribute('aria-expanded', 'false');
        card.classList.remove('is-flipped');
      }
    });
  });

  if (window.gsap && !reduced) {
    gsap.from('.labels .label', {
      y: 46,
      opacity: 0,
      rotate: () => gsap.utils.random(-3, 3),
      duration: 0.9,
      stagger: 0.07,
      ease: 'power3.out',
      scrollTrigger: { trigger: '.labels', start: 'top 82%', once: true },
      clearProps: 'opacity,transform',
    });
  }
}
